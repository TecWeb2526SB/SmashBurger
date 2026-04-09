<?php
require_once __DIR__ . '/includes/resources.php';

$context = admin_panel_bootstrap_context($pdo);
extract($context, EXTR_SKIP);

$hubUrl = admin_panel_section_url('forniture', $selectedBranchSlug, $isGeneralAdmin, $canManageBranchManagers);
if (!$canModifyBranchOperations) {
    flash_set('error', 'Solo il manager della filiale puo aprire il builder operativo delle forniture.');
    header('Location: ' . $hubUrl);
    exit;
}

$builderKey = 'automatic';
$builderMeta = admin_supply_builder_meta($builderKey);
$builderUrl = admin_supply_builder_url($builderKey, $selectedBranchSlug, $isGeneralAdmin);
$inventoryItems = inventory_get_branch_products($pdo, $selectedBranchId);
$draft = admin_supply_policy_default_draft();
$csrfToken = csrf_token();
$flash = flash_get();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $csrfTokenForm = $_POST['csrf_token'] ?? null;
    if (!csrf_is_valid($csrfTokenForm)) {
        flash_set('error', 'Sessione scaduta o richiesta non valida. Ricarica la pagina e riprova.');
        header('Location: ' . $builderUrl);
        exit;
    }

    $action = (string) ($_POST['action'] ?? '');
    if ($action === 'create_policy') {
        $draft = admin_supply_policy_draft_from_payload($_POST);
    }

    try {
        if ($action === 'create_policy') {
            auto_reorder_upsert_policy(
                $pdo,
                $selectedBranchId,
                (int) ($draft['product_id'] ?? 0),
                (int) ($draft['threshold_qty'] ?? 0),
                (int) ($draft['reorder_qty'] ?? 0),
                (int) ($draft['cooldown_hours'] ?? 6),
                (int) ($draft['max_pending_qty'] ?? 0),
                (string) ($draft['mode'] ?? 'draft')
            );

            flash_set('success', 'Policy di ordine automatico salvata.');
            header('Location: ' . $hubUrl);
            exit;
        }

        if ($action === 'run_auto_reorder') {
            $generatedOrders = auto_reorder_evaluate_branch($pdo, $selectedBranchId, (int) $utente['id']);
            flash_set(
                'success',
                $generatedOrders > 0
                    ? 'Controllo ordine automatico completato: generate ' . $generatedOrders . ' forniture.'
                    : 'Controllo ordine automatico completato: nessuna nuova fornitura necessaria.'
            );
            header('Location: ' . $builderUrl);
            exit;
        }

        throw new RuntimeException('Azione builder non riconosciuta.');
    } catch (\Throwable $e) {
        $flash = ['type' => 'error', 'message' => $e->getMessage()];
    }
}

$templates = supply_get_templates($pdo, $selectedBranchId);
$supplyOrders = supply_get_orders($pdo, $selectedBranchId);
$policies = auto_reorder_get_policies($pdo, $selectedBranchId);

$pageTitle = 'Automazione stock forniture - Smash Burger Original';
$pageDescription = 'Builder guidato per le policy di riordino automatico della filiale.';
$currentPage = 'admin.php';
$breadcrumb = [
    ['Home', 'index.php'],
    ['Controllo', admin_panel_section_url('dashboard', $selectedBranchSlug, $isGeneralAdmin, $canManageBranchManagers)],
    ['Forniture', $hubUrl],
    [(string) ($builderMeta['breadcrumb'] ?? 'Automazione stock'), null],
];

include_once __DIR__ . '/views/template/header.php';
include_once __DIR__ . '/views/admin_forniture_builder.php';
include_once __DIR__ . '/views/template/footer.php';
