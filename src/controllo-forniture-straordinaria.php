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

$builderKey = 'extra';
$builderMeta = admin_supply_builder_meta($builderKey);
$builderUrl = admin_supply_builder_url($builderKey, $selectedBranchSlug, $isGeneralAdmin);
$inventoryItems = admin_prepare_supply_products($pdo, $selectedBranchId, inventory_get_branch_products($pdo, $selectedBranchId));
$productsById = admin_build_products_lookup($inventoryItems);
$draft = admin_supply_extra_default_draft();
$csrfToken = csrf_token();
$flash = flash_get();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $csrfTokenForm = $_POST['csrf_token'] ?? null;
    if (!csrf_is_valid($csrfTokenForm)) {
        flash_set('error', 'Sessione scaduta o richiesta non valida. Ricarica la pagina e riprova.');
        header('Location: ' . $builderUrl);
        exit;
    }

    $draft = admin_supply_extra_draft_from_payload($_POST);
    $action = (string) ($_POST['action'] ?? '');

    try {
        if ($action !== 'create_extra_supply') {
            throw new RuntimeException('Azione builder non riconosciuta.');
        }

        $items = admin_extract_supply_items($pdo, $selectedBranchId, $_POST, 'extra', $productsById);
        $scheduledForSql = parse_local_datetime_to_sql((string) ($draft['scheduled_for'] ?? ''));
        $status = $scheduledForSql !== null ? 'scheduled' : 'ordered';

        supply_create_order(
            $pdo,
            $selectedBranchId,
            (int) $utente['id'],
            'extraordinary',
            $status,
            $items,
            $scheduledForSql,
            null,
            (string) ($draft['notes'] ?? ''),
            (string) ($draft['supplier_name'] ?? 'Centro forniture SmashBurger')
        );

        flash_set('success', 'Fornitura straordinaria registrata.');
        header('Location: ' . $hubUrl);
        exit;
    } catch (\Throwable $e) {
        $flash = ['type' => 'error', 'message' => $e->getMessage()];
    }
}

$templates = supply_get_templates($pdo, $selectedBranchId);
$supplyOrders = supply_get_orders($pdo, $selectedBranchId);
$policies = auto_reorder_get_policies($pdo, $selectedBranchId);

$pageTitle = 'Intervento una tantum forniture - Smash Burger Original';
$pageDescription = 'Builder guidato per registrare forniture straordinarie della filiale.';
$currentPage = 'controllo';
$breadcrumb = [
    ['Home', './'],
    ['Controllo', admin_panel_section_url('dashboard', $selectedBranchSlug, $isGeneralAdmin, $canManageBranchManagers)],
    ['Forniture', $hubUrl],
    [(string) ($builderMeta['breadcrumb'] ?? 'Intervento una tantum'), null],
];

include_once __DIR__ . '/views/template/header.php';
include_once __DIR__ . '/views/controllo/forniture-builder.php';
include_once __DIR__ . '/views/template/footer.php';
