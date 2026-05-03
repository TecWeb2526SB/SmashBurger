<?php
require_once __DIR__ . '/includes/resources.php';

$context = admin_panel_bootstrap_context($pdo);
if (!can_manage_branch_managers()) {
    flash_set('error', 'Solo l admin centrale può gestire i manager.');
    header('Location: controllo');
    exit;
}

$draft = admin_team_manager_default_draft();
$editManagerId = max(0, (int) ($_GET['modifica'] ?? 0));
$requestedMode = (string) ($_GET['modalita'] ?? '');

if (isset($_GET['reset'])) {
    unset($_SESSION['admin_team_manager_draft']);
}

if ($editManagerId > 0) {
    $manager = auth_get_branch_manager_by_id($pdo, $editManagerId);
    if ($manager) {
        $draft = admin_team_manager_draft_from_manager($manager);
    } else {
        flash_set('error', 'Manager non trovato.');
        header('Location: controllo-manager');
        exit;
    }
}

$teamMode = match (true) {
    $requestedMode === 'review' => 'review',
    in_array($requestedMode, ['nuovo', 'create_details'], true) => 'create_details',
    $editManagerId > 0 => 'edit_details',
    default => 'list',
};

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (csrf_is_valid($_POST['csrf_token'] ?? null)) {
        $action = (string) ($_POST['action'] ?? '');
        try {
            if ($action === 'save_details') {
                $_SESSION['admin_team_manager_draft'] = admin_team_manager_draft_from_payload($pdo, $_POST);
                header('Location: controllo-manager?modalita=review');
                exit;
            } elseif ($action === 'confirm_manager') {
                admin_team_manager_confirm($pdo, $_SESSION['admin_team_manager_draft'] ?? []);
                unset($_SESSION['admin_team_manager_draft']);
                flash_set('success', 'Manager salvato.');
                header('Location: controllo-manager');
                exit;
            } elseif ($action === 'toggle_branch_manager') {
                auth_toggle_branch_manager($pdo, (int) ($_POST['manager_id'] ?? 0), (string) ($_POST['is_active'] ?? '0') === '1');
                flash_set('success', 'Stato manager aggiornato.');
                header('Location: controllo-manager');
                exit;
            } elseif ($action === 'delete_branch_manager') {
                auth_delete_branch_manager($pdo, (int) ($_POST['manager_id'] ?? 0));
                flash_set('success', 'Manager eliminato.');
                header('Location: controllo-manager');
                exit;
            }
        } catch (\Throwable $e) {
            flash_set('error', $e->getMessage());
        }
    } else {
        flash_set('error', 'Sessione scaduta o richiesta non valida.');
    }

    header('Location: controllo-manager');
    exit;
}

render_admin_page('team', [
    'branchManagers' => auth_get_branch_managers($pdo),
    'allBranches' => branches_get_all($pdo),
    'teamMode' => $teamMode,
    'draft' => $_SESSION['admin_team_manager_draft'] ?? $draft
]);
