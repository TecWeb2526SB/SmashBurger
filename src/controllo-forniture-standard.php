<?php
require_once __DIR__ . '/includes/resources.php';

$context = admin_panel_bootstrap_context($pdo);
extract($context, EXTR_SKIP);

$hubUrl = admin_panel_section_url('forniture', $selectedBranchSlug, $isGeneralAdmin, $canManageBranchManagers);
if (!$canModifyBranchOperations) {
    flash_set('error', 'Solo il manager della filiale può aprire il builder operativo delle forniture.');
    header('Location: ' . $hubUrl);
    exit;
}

$builderKey = 'standard';
$builderMeta = admin_supply_builder_meta($builderKey);
$builderUrl = admin_supply_builder_url($builderKey, $selectedBranchSlug, $isGeneralAdmin);
$inventoryItems = admin_prepare_supply_products($pdo, $selectedBranchId, inventory_get_branch_products($pdo, $selectedBranchId));
$productsById = admin_build_products_lookup($inventoryItems);
$draft = admin_supply_standard_default_draft();
$csrfToken = csrf_token();
$flash = flash_get();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $csrfTokenForm = $_POST['csrf_token'] ?? null;
    if (!csrf_is_valid($csrfTokenForm)) {
        flash_set('error', 'Sessione scaduta o richiesta non valida. Ricarica la pagina e riprova.');
        header('Location: ' . $builderUrl);
        exit;
    }

    $draft = admin_supply_standard_draft_from_payload($_POST);
    $action = (string) ($_POST['action'] ?? '');

    try {
        if ($action !== 'create_standard_template') {
            throw new RuntimeException('Azione builder non riconosciuta.');
        }

        $items = admin_extract_supply_items($pdo, $selectedBranchId, $_POST, 'template', $productsById);
        $nextRunAtSql = (string) parse_local_datetime_to_sql((string) ($draft['next_run_at'] ?? ''));

        supply_create_template(
            $pdo,
            $selectedBranchId,
            (int) $utente['id'],
            (string) ($draft['template_name'] ?? ''),
            (string) ($draft['frequency'] ?? 'weekly'),
            $nextRunAtSql,
            $items,
            (string) ($draft['notes'] ?? '')
        );

        flash_set('success', 'Fornitura standard programmata con successo.');
        header('Location: ' . $hubUrl);
        exit;
    } catch (\Throwable $e) {
        $flash = ['type' => 'error', 'message' => $e->getMessage()];
    }
}

$templates = supply_get_templates($pdo, $selectedBranchId);
$supplyOrders = supply_get_orders($pdo, $selectedBranchId);
$policies = auto_reorder_get_policies($pdo, $selectedBranchId);
$hubUrl = admin_panel_section_url('forniture', $selectedBranchSlug, $isGeneralAdmin, $canManageBranchManagers);

render_admin_page('forniture', [
    'pageDescription' => 'Builder guidato per creare template di fornitura ricorrenti per la filiale.',
    'breadcrumb' => [
        ['Home', './'],
        ['Controllo', admin_panel_section_url('dashboard', $selectedBranchSlug, $isGeneralAdmin, $canManageBranchManagers)],
        ['Forniture', $hubUrl],
        [(string) ($builderMeta['breadcrumb'] ?? 'Routine ricorrente'), null],
    ],
    'builderKey' => $builderKey,
    'builderMeta' => $builderMeta,
    'builderUrl' => $builderUrl,
    'hubUrl' => $hubUrl,
    'inventoryItems' => $inventoryItems,
    'draft' => $draft,
    'templates' => $templates,
    'supplyOrders' => $supplyOrders,
    'policies' => $policies
], 'controllo/forniture-builder.php');
