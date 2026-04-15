<?php
require_once __DIR__ . '/includes/resources.php';

$context = admin_panel_bootstrap_context($pdo);
extract($context, EXTR_SKIP);

if (!$canManageBranchManagers) {
    flash_set('error', 'Solo l admin centrale puo gestire le credenziali dei manager di filiale.');
    header('Location: controllo');
    exit;
}

$currentSection = 'team';
$sectionMeta = admin_panel_section_meta($currentSection, $canManageBranchManagers);
$sectionLinks = admin_panel_build_navigation($selectedBranchSlug, $isGeneralAdmin, $canManageBranchManagers, $currentSection);
$sectionUrls = [];
foreach ($sectionLinks as $sectionLink) {
    $sectionUrls[(string) $sectionLink['section']] = (string) $sectionLink['href'];
}

$csrfToken = csrf_token();
$flash = null;
$backgroundMessages = [];
$teamMode = 'list'; // list, create_details, edit_details, review
$editingManager = null;
$draft = null;

$requestedMode = trim((string) ($_GET['modalita'] ?? ''));
$requestedManagerId = (int) ($_GET['modifica'] ?? 0);
$requestedStep = trim((string) ($_GET['step'] ?? 'dettagli'));

$flowKey = 'admin_team_flow';
if (isset($_GET['reset']) && $_GET['reset'] === '1') {
    unset($_SESSION[$flowKey]);
}

if ($requestedMode === 'nuovo') {
    $teamMode = ($requestedStep === 'riepilogo') ? 'review' : 'create_details';
} elseif ($requestedManagerId > 0) {
    $editingManager = auth_get_branch_manager_by_id($pdo, $requestedManagerId);
    if ($editingManager === null) {
        flash_set('error', 'Manager di filiale non trovato.');
        header('Location: ' . ($sectionUrls['team'] ?? 'controllo-manager'));
        exit;
    }
    $teamMode = ($requestedStep === 'riepilogo') ? 'review' : 'edit_details';
}

$draft = $_SESSION[$flowKey]['draft'] ?? null;
if (!is_array($draft) && ($teamMode === 'create_details' || $teamMode === 'edit_details')) {
    if ($editingManager !== null) {
        $draft = [
            'id' => $editingManager['id'],
            'username' => $editingManager['username'],
            'email' => $editingManager['email'],
            'managed_branch_id' => $editingManager['managed_branch_id'],
            'managed_branch_name' => $editingManager['managed_branch_name'],
            'password' => '', // Non mostriamo la vecchia password
        ];
    } else {
        $draft = [
            'id' => 0,
            'username' => '',
            'email' => '',
            'managed_branch_id' => '',
            'managed_branch_name' => '',
            'password' => '',
        ];
    }
    $_SESSION[$flowKey] = ['draft' => $draft];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $csrfTokenForm = $_POST['csrf_token'] ?? null;
    if (!csrf_is_valid($csrfTokenForm)) {
        flash_set('error', 'Sessione scaduta o richiesta non valida. Ricarica la pagina e riprova.');
        header('Location: ' . ($sectionUrls['team'] ?? 'controllo-manager'));
        exit;
    }

    $action = (string) ($_POST['action'] ?? '');
    $managerId = (int) ($_POST['manager_id'] ?? 0);

    try {
        if ($action === 'save_details') {
            $username = trim((string) ($_POST['username'] ?? ''));
            $email = auth_normalize_email((string) ($_POST['email'] ?? ''));
            $password = (string) ($_POST['password'] ?? '');
            $branchId = (int) ($_POST['managed_branch_id'] ?? 0);

            if ($username === '' || !auth_is_valid_username($username)) {
                throw new RuntimeException('Username manager non valido.');
            }
            if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                throw new RuntimeException('Email manager non valida.');
            }
            if ($branchId <= 0) {
                throw new RuntimeException('Seleziona una filiale valida per il manager.');
            }
            
            $branch = branch_get_by_id($pdo, $branchId);
            if ($branch === null) {
                throw new RuntimeException('Filiale non trovata.');
            }

            // Validazione duplicati immediata per feedback rapido
            if (auth_username_exists($pdo, $username, $managerId)) {
                throw new RuntimeException('Username gia in uso.');
            }
            if (auth_email_exists($pdo, $email, $managerId)) {
                throw new RuntimeException('Email gia in uso.');
            }

            if ($managerId === 0 || $password !== '') {
                if (mb_strlen($password) < 8) {
                    throw new RuntimeException('La password deve contenere almeno 8 caratteri.');
                }
                if (!auth_is_valid_password($password)) {
                    throw new RuntimeException('La password puo contenere solo lettere, numeri e questi simboli: ! @ # $ % &');
                }
            }

            $draft = [
                'id' => $managerId,
                'username' => $username,
                'email' => $email,
                'managed_branch_id' => $branchId,
                'managed_branch_name' => $branch['name'],
                'password' => $password,
            ];
            $_SESSION[$flowKey] = ['draft' => $draft];

            header('Location: controllo-manager?' . http_build_query(array_filter([
                'modifica' => $managerId > 0 ? $managerId : null,
                'modalita' => $managerId === 0 ? 'nuovo' : null,
                'step' => 'riepilogo'
            ])));
            exit;
        } elseif ($action === 'confirm_manager') {
            if (!$draft) throw new RuntimeException('Dati sessione non trovati.');

            $passwordHash = null;
            if ($draft['password'] !== '') {
                $passwordHash = password_hash($draft['password'], PASSWORD_DEFAULT);
            }

            if ($draft['id'] > 0) {
                auth_update_branch_manager($pdo, $draft['id'], $draft['username'], $draft['email'], $draft['managed_branch_id'], $passwordHash);
                flash_set('success', 'Credenziali manager aggiornate.');
            } else {
                auth_create_branch_manager($pdo, $draft['username'], $draft['email'], $passwordHash, $draft['managed_branch_id']);
                flash_set('success', 'Manager di filiale creato con successo.');
            }

            unset($_SESSION[$flowKey]);
            header('Location: ' . ($sectionUrls['team'] ?? 'controllo-manager'));
            exit;
        } elseif ($action === 'toggle_branch_manager') {
            $managerId = (int) ($_POST['manager_id'] ?? 0);
            $isActive = (string) ($_POST['is_active'] ?? '0') === '1';
            auth_toggle_branch_manager($pdo, $managerId, $isActive);
            flash_set('success', $isActive ? 'Manager riattivato.' : 'Credenziali manager revocate.');
            header('Location: ' . ($sectionUrls['team'] ?? 'controllo-manager'));
            exit;
        } elseif ($action === 'delete_branch_manager') {
            $managerId = (int) ($_POST['manager_id'] ?? 0);
            auth_delete_user($pdo, $managerId);
            flash_set('success', 'Credenziali manager eliminate definitivamente.');
            header('Location: ' . ($sectionUrls['team'] ?? 'controllo-manager'));
            exit;
        }
    } catch (\Throwable $e) {
        flash_set('error', $e->getMessage());
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
    }
}

$flash = flash_get();
$branchManagers = auth_get_branch_managers($pdo);
$allBranches = branches_get_all($pdo);

$pageTitle = 'Manager controllo - Smash Burger Original';
$pageDescription = 'Gestione centralizzata delle credenziali manager di filiale.';
$currentPage = 'controllo';
$breadcrumb = [
    ['Home', './'],
    ['Controllo', 'controllo'],
];

if ($teamMode === 'create_details' || $teamMode === 'edit_details' || $teamMode === 'review') {
    $breadcrumb[] = ['Manager', 'controllo-manager'];
    $breadcrumb[] = [$teamMode === 'review' ? 'Riepilogo' : 'Modifica credenziali', null];
} else {
    $breadcrumb[] = ['Manager', null];
}

include_once __DIR__ . '/views/template/header.php';
include_once __DIR__ . '/views/controllo/pannello.php';
include_once __DIR__ . '/views/template/footer.php';
