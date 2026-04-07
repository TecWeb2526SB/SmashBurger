<?php
require_once __DIR__ . '/includes/resources.php';

$context = admin_panel_bootstrap_context($pdo);
extract($context, EXTR_SKIP);

if (!$canManageBranchManagers) {
    flash_set('error', 'Solo l admin centrale puo gestire le credenziali dei manager di filiale.');
    header('Location: admin.php');
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
$teamMode = 'list';
$editingManager = null;

$requestedMode = trim((string) ($_GET['modalita'] ?? ''));
$requestedManagerId = (int) ($_GET['modifica'] ?? 0);

if ($requestedMode === 'nuovo') {
    $teamMode = 'create';
} elseif ($requestedManagerId > 0) {
    $editingManager = auth_get_branch_manager_by_id($pdo, $requestedManagerId);
    if ($editingManager === null) {
        flash_set('error', 'Manager di filiale non trovato.');
        header('Location: ' . ($sectionUrls['team'] ?? 'admin_team.php'));
        exit;
    }
    $teamMode = 'edit';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $csrfTokenForm = $_POST['csrf_token'] ?? null;
    if (!csrf_is_valid($csrfTokenForm)) {
        flash_set('error', 'Sessione scaduta o richiesta non valida. Ricarica la pagina e riprova.');
        header('Location: ' . ($sectionUrls['team'] ?? 'admin_team.php'));
        exit;
    }

    $action = (string) ($_POST['action'] ?? '');
    $managerId = (int) ($_POST['manager_id'] ?? 0);
    $redirectAfterPost = $sectionUrls['team'] ?? 'admin_team.php';
    if ($action === 'save_branch_manager' && $managerId > 0) {
        $redirectAfterPost .= '?modifica=' . $managerId;
    } elseif ($action === 'save_branch_manager') {
        $redirectAfterPost .= '?modalita=nuovo';
    }

    try {
        if ($action === 'save_branch_manager') {
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

            if ($branchId <= 0 || branch_get_by_id($pdo, $branchId) === null) {
                throw new RuntimeException('Seleziona una filiale valida per il manager.');
            }

            if ($managerId > 0) {
                $existingManager = auth_get_branch_manager_by_id($pdo, $managerId);
                if ($existingManager === null) {
                    throw new RuntimeException('Manager di filiale non trovato.');
                }

                if (auth_username_exists($pdo, $username, $managerId)) {
                    throw new RuntimeException('Username gia in uso.');
                }

                if (auth_email_exists($pdo, $email, $managerId)) {
                    throw new RuntimeException('Email gia in uso.');
                }

                if (auth_branch_manager_exists_for_branch($pdo, $branchId, $managerId)) {
                    throw new RuntimeException('Esiste gia un manager attivo associato a questa filiale.');
                }

                $passwordHash = null;
                if ($password !== '') {
                    if (mb_strlen($password) < 8 || !auth_is_valid_password($password)) {
                        throw new RuntimeException('Password manager non valida. Usa almeno 8 caratteri con lettere, numeri, underscore o simboli consentiti.');
                    }
                    $passwordHash = password_hash($password, PASSWORD_DEFAULT);
                }

                auth_update_branch_manager($pdo, $managerId, $username, $email, $branchId, $passwordHash);
                flash_set('success', 'Credenziali manager aggiornate.');
            } else {
                if (mb_strlen($password) < 8 || !auth_is_valid_password($password)) {
                    throw new RuntimeException('Password manager non valida. Usa almeno 8 caratteri con lettere, numeri, underscore o simboli consentiti.');
                }

                if (auth_username_exists($pdo, $username)) {
                    throw new RuntimeException('Username gia in uso.');
                }

                if (auth_email_exists($pdo, $email)) {
                    throw new RuntimeException('Email gia in uso.');
                }

                if (auth_branch_manager_exists_for_branch($pdo, $branchId)) {
                    throw new RuntimeException('Esiste gia un manager associato a questa filiale.');
                }

                auth_create_branch_manager($pdo, $username, $email, password_hash($password, PASSWORD_DEFAULT), $branchId);
                flash_set('success', 'Manager di filiale creato con successo.');
                $redirectAfterPost = $sectionUrls['team'] ?? 'admin_team.php';
            }
        } elseif ($action === 'toggle_branch_manager') {
            $managerId = (int) ($_POST['manager_id'] ?? 0);
            $isActive = (string) ($_POST['is_active'] ?? '0') === '1';
            auth_toggle_branch_manager($pdo, $managerId, $isActive);
            flash_set('success', $isActive ? 'Manager riattivato.' : 'Credenziali manager revocate.');
            $redirectAfterPost = $sectionUrls['team'] ?? 'admin_team.php';
        } else {
            throw new RuntimeException('Azione manager non riconosciuta.');
        }
    } catch (\Throwable $e) {
        flash_set('error', $e->getMessage());
    }

    header('Location: ' . $redirectAfterPost);
    exit;
}

$flash = flash_get();
$inventoryItems = [];
$globalCatalog = [];
$categories = [];
$branchManagers = auth_get_branch_managers($pdo);
$templates = [];
$supplyOrders = [];
$policies = [];
$recentCustomerOrders = [];
$branchComparison = [];
$topProducts = [];
$salesTrend = [];
$categoryMix = [];
$kpis = analytics_get_branch_kpis($pdo, $selectedBranchId);

$pageTitle = 'Manager controllo - Smash Burger Original';
$pageDescription = 'Gestione centralizzata delle credenziali manager di filiale.';
$currentPage = 'admin.php';
$breadcrumb = [
    ['Home', 'index.php'],
    ['Controllo', 'admin.php'],
    ['Manager', null],
];

include_once __DIR__ . '/views/template/header.php';
include_once __DIR__ . '/views/admin.php';
include_once __DIR__ . '/views/template/footer.php';
