<?php
require_once __DIR__ . '/includes/resources.php';

$context = admin_panel_bootstrap_context($pdo);
if (!can_manage_branch_managers()) {
    flash_set('error', 'Solo l admin centrale può gestire i manager.');
    header('Location: controllo');
    exit;
}

$teamMode = (string) ($_GET['modalita'] ?? ($_GET['modifica'] ? 'edit_details' : 'list'));
$draft = admin_team_manager_default_draft();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (csrf_is_valid($_POST['csrf_token'] ?? null)) {
        $action = (string) ($_POST['action'] ?? '');
        try {
            if ($action === 'save_details') {
                $_SESSION['admin_team_manager_draft'] = admin_team_manager_draft_from_payload($_POST);
                header('Location: controllo-manager?modalita=review');
                exit;
            } elseif ($action === 'confirm_manager') {
                admin_team_manager_confirm($pdo, $_SESSION['admin_team_manager_draft'] ?? []);
                unset($_SESSION['admin_team_manager_draft']);
                flash_set('success', 'Manager salvato.');
                header('Location: controllo-manager');
                exit;
            }
        } catch (\Throwable $e) { flash_set('error', $e->getMessage()); }
    }
}

render_admin_page('team', [
    'branchManagers' => auth_get_all_branch_managers($pdo),
    'allBranches' => branches_get_all($pdo),
    'teamMode' => $teamMode,
    'draft' => $_SESSION['admin_team_manager_draft'] ?? $draft
]);
