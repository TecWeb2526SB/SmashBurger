<?php
require_once __DIR__ . '/includes/resources.php';

$context = admin_panel_bootstrap_context($pdo);
extract($context, EXTR_SKIP);

$inventoryUrl = admin_panel_section_url('inventario', $selectedBranchSlug, $isGeneralAdmin, $canManageBranchManagers);
if (!$canModifyBranchOperations) {
    flash_set('error', 'Solo il manager della filiale puo aprire il flusso di rettifica inventario.');
    header('Location: ' . $inventoryUrl);
    exit;
}

$inventoryItems = inventory_get_branch_products($pdo, $selectedBranchId);
$productsById = admin_build_products_lookup($inventoryItems);
$modes = admin_inventory_adjustment_modes();
$requestedMode = trim((string) ($_GET['modo'] ?? 'carico'));
$mode = isset($modes[$requestedMode]) ? $requestedMode : 'carico';
$requestedProductId = max(0, (int) ($_GET['prodotto'] ?? 0));
$draft = admin_inventory_adjustment_default_draft($mode, isset($productsById[$requestedProductId]) ? $requestedProductId : 0);
$flash = flash_get();
$csrfToken = csrf_token();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $csrfTokenForm = $_POST['csrf_token'] ?? null;
    if (!csrf_is_valid($csrfTokenForm)) {
        flash_set('error', 'Sessione scaduta o richiesta non valida. Ricarica la pagina e riprova.');
        header('Location: ' . admin_inventory_adjustment_url($selectedBranchSlug, $isGeneralAdmin, $mode, $requestedProductId > 0 ? $requestedProductId : null));
        exit;
    }

    $action = (string) ($_POST['action'] ?? '');
    $draft = admin_inventory_adjustment_draft_from_payload($_POST);
    $mode = (string) ($draft['mode'] ?? 'carico');

    try {
        if ($action !== 'apply_inventory_adjustment') {
            throw new RuntimeException('Azione rettifica inventario non riconosciuta.');
        }

        inventory_apply_manual_adjustment_flow(
            $pdo,
            $selectedBranchId,
            (int) $utente['id'],
            $draft,
            $productsById
        );

        flash_set('success', 'Rettifica inventario registrata con successo.');
        header('Location: ' . $inventoryUrl);
        exit;
    } catch (\Throwable $e) {
        $flash = ['type' => 'error', 'message' => $e->getMessage()];
    }
}

$selectedProductId = max(0, (int) ($draft['product_id'] ?? 0));
$selectedProduct = $selectedProductId > 0 && isset($productsById[$selectedProductId]) ? $productsById[$selectedProductId] : null;
$modeMeta = $modes[$mode] ?? $modes['carico'];

$pageTitle = 'Rettifica inventario - Smash Burger Original';
$pageDescription = 'Flusso guidato per carichi, scarichi e conteggi fisici di inventario.';
$currentPage = 'admin.php';
$breadcrumb = [
    ['Home', 'index.php'],
    ['Controllo', 'admin.php' . ($isGeneralAdmin ? '?sede=' . rawurlencode($selectedBranchSlug) : '')],
    ['Inventario', $inventoryUrl],
    ['Rettifica', null],
];

include_once __DIR__ . '/views/template/header.php';
include_once __DIR__ . '/views/admin_inventario_rettifica.php';
include_once __DIR__ . '/views/template/footer.php';
