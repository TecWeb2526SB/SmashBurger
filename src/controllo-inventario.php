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
        header('Location: ' . ($sectionUrls['inventario'] ?? 'controllo-inventario'));
        exit;
    }

    try {
        if (!$canModifyBranchOperations) {
            throw new RuntimeException('Solo il manager della filiale puo modificare inventario e prezzi locali.');
        }

        $action = (string) ($_POST['action'] ?? '');
        if ($action !== 'update_branch_pricing') {
            throw new RuntimeException('Azione inventario non riconosciuta.');
        }

        $productId = (int) ($_POST['product_id'] ?? 0);
        if ($productId <= 0 || !isset($productsById[$productId])) {
            throw new RuntimeException('Seleziona un prodotto valido per il prezzo di filiale.');
        }

        $branchPriceCents = admin_parse_money_to_cents((string) ($_POST['sale_price'] ?? ''));
        if ($branchPriceCents <= 0) {
            throw new RuntimeException('Inserisci un prezzo di filiale valido.');
        }

        $product = $productsById[$productId];
        $basePriceCents = (int) ($product['base_price_cents'] ?? 0);
        $priceOverride = $branchPriceCents === $basePriceCents ? null : $branchPriceCents;

        branch_catalog_set_product_state(
            $pdo,
            $selectedBranchId,
            $productId,
            (int) ($product['is_listed'] ?? 0) === 1,
            (int) ($product['branch_availability_flag'] ?? 0) === 1,
            $priceOverride
        );

        flash_set(
            'success',
            $priceOverride === null
                ? 'Prezzo filiale riallineato al prezzo base del catalogo.'
                : 'Prezzo filiale aggiornato con successo.'
        );
    } catch (\Throwable $e) {
        flash_set('error', $e->getMessage());
    }

    header('Location: ' . ($sectionUrls['inventario'] ?? 'controllo-inventario'));
    exit;
}

$flash = flash_get();
$backgroundMessages = admin_panel_background_messages($pdo, $isBranchManager, $selectedBranchId, (int) $utente['id']);
$inventoryItems = inventory_get_branch_products($pdo, $selectedBranchId);
$kpis = analytics_get_branch_kpis($pdo, $selectedBranchId);
$inventoryAdjustmentUrl = admin_inventory_adjustment_url($selectedBranchSlug, $isGeneralAdmin);

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
$currentPage = 'controllo';
$breadcrumb = [
    ['Home', './'],
    ['Controllo', 'controllo' . ($isGeneralAdmin ? '?sede=' . rawurlencode($selectedBranchSlug) : '')],
    ['Inventario', null],
];

include_once __DIR__ . '/views/template/header.php';
include_once __DIR__ . '/views/controllo/pannello.php';
include_once __DIR__ . '/views/template/footer.php';
