<?php
require_once __DIR__ . '/includes/resources.php';

$context = admin_panel_bootstrap_context($pdo);
extract($context, EXTR_SKIP);

$currentSection = 'inventario';
$sectionMeta = admin_panel_section_meta($currentSection, $canManageBranchManagers);
$sectionLinks = admin_panel_build_navigation($selectedBranchSlug, $isGeneralAdmin, $canManageBranchManagers, $currentSection);
$sectionUrls = [];
foreach ($sectionLinks as $sectionLink) {
    $sectionUrls[(string) $sectionLink['section']] = (string) $sectionLink['href'];
}

$inventoryItems = inventory_get_branch_products($pdo, $selectedBranchId);
$productsById = admin_build_products_lookup($inventoryItems);
$csrfToken = csrf_token();
$flash = null;
$backgroundMessages = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $csrfTokenForm = $_POST['csrf_token'] ?? null;
    if (!csrf_is_valid($csrfTokenForm)) {
        flash_set('error', 'Sessione scaduta o richiesta non valida. Ricarica la pagina e riprova.');
        header('Location: ' . ($sectionUrls['inventario'] ?? 'admin_inventario.php'));
        exit;
    }

    try {
        if (!$canModifyBranchOperations) {
            throw new RuntimeException('Solo il manager della filiale puo rettificare l inventario.');
        }

        $productId = (int) ($_POST['product_id'] ?? 0);
        $quantityDelta = (int) ($_POST['quantity_delta'] ?? 0);
        $unitCostCents = max(0, (int) ($_POST['unit_cost_cents'] ?? 0));
        $notes = trim((string) ($_POST['notes'] ?? ''));

        if ($productId <= 0 || !isset($productsById[$productId])) {
            throw new RuntimeException('Seleziona un prodotto valido per la rettifica inventario.');
        }

        if ($quantityDelta === 0) {
            throw new RuntimeException('Inserisci una variazione quantita diversa da zero.');
        }

        $pdo->beginTransaction();
        inventory_adjust_stock(
            $pdo,
            $selectedBranchId,
            $productId,
            $quantityDelta,
            'manual_adjustment',
            'manual',
            null,
            $notes !== '' ? $notes : 'Rettifica manuale inventario da pannello manager.',
            (int) $utente['id'],
            $quantityDelta > 0 ? $unitCostCents : null
        );
        $pdo->commit();

        try {
            auto_reorder_evaluate_branch($pdo, $selectedBranchId, (int) $utente['id']);
        } catch (\Throwable $autoReorderException) {
            error_log('Errore auto-riordino dopo rettifica inventario: ' . $autoReorderException->getMessage());
        }

        flash_set('success', 'Inventario aggiornato con successo.');
    } catch (\Throwable $e) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        flash_set('error', $e->getMessage());
    }

    header('Location: ' . ($sectionUrls['inventario'] ?? 'admin_inventario.php'));
    exit;
}

$flash = flash_get();
$backgroundMessages = admin_panel_background_messages($pdo, $isBranchManager, $selectedBranchId, (int) $utente['id']);
$inventoryItems = inventory_get_branch_products($pdo, $selectedBranchId);
$kpis = analytics_get_branch_kpis($pdo, $selectedBranchId);

$globalCatalog = [];
$categories = [];
$branchManagers = [];
$templates = [];
$supplyOrders = [];
$policies = [];
$recentCustomerOrders = [];
$branchComparison = [];
$topProducts = [];
$salesTrend = [];
$categoryMix = [];

$pageTitle = 'Inventario controllo - Smash Burger Original';
$pageDescription = 'Inventario di filiale e rettifiche operative.';
$currentPage = 'admin.php';
$breadcrumb = [
    ['Home', 'index.php'],
    ['Controllo', 'admin.php' . ($isGeneralAdmin ? '?sede=' . rawurlencode($selectedBranchSlug) : '')],
    ['Inventario', null],
];

include_once __DIR__ . '/views/template/header.php';
include_once __DIR__ . '/views/admin.php';
include_once __DIR__ . '/views/template/footer.php';
