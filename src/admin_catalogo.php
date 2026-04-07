<?php
require_once __DIR__ . '/includes/resources.php';

$context = admin_panel_bootstrap_context($pdo);
extract($context, EXTR_SKIP);

$currentSection = 'catalogo';
$sectionMeta = admin_panel_section_meta($currentSection, $canManageBranchManagers);
$sectionLinks = admin_panel_build_navigation($selectedBranchSlug, $isGeneralAdmin, $canManageBranchManagers, $currentSection);
$sectionUrls = [];
foreach ($sectionLinks as $sectionLink) {
    $sectionUrls[(string) $sectionLink['section']] = (string) $sectionLink['href'];
}

$inventoryItems = inventory_get_branch_products($pdo, $selectedBranchId);
$productsById = admin_build_products_lookup($inventoryItems);
$globalCatalog = catalog_get_all_products_with_branch_usage($pdo);
$categories = categories_get_all($pdo);

$csrfToken = csrf_token();
$flash = null;
$backgroundMessages = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $csrfTokenForm = $_POST['csrf_token'] ?? null;
    if (!csrf_is_valid($csrfTokenForm)) {
        flash_set('error', 'Sessione scaduta o richiesta non valida. Ricarica la pagina e riprova.');
        header('Location: ' . ($sectionUrls['catalogo'] ?? 'admin_catalogo.php'));
        exit;
    }

    $action = (string) ($_POST['action'] ?? '');

    try {
        if ($action === 'branch_catalog_state') {
            if (!$canModifyBranchOperations) {
                throw new RuntimeException('Solo il manager della filiale puo gestire il catalogo locale.');
            }

            $productId = (int) ($_POST['product_id'] ?? 0);
            if ($productId <= 0 || !isset($productsById[$productId])) {
                throw new RuntimeException('Prodotto non valido per il catalogo di filiale.');
            }

            $isListed = (string) ($_POST['is_listed'] ?? '0') === '1';
            $isAvailable = (string) ($_POST['is_available'] ?? '0') === '1';

            branch_catalog_set_product_state($pdo, $selectedBranchId, $productId, $isListed, $isAvailable);
            flash_set(
                'success',
                $isListed
                    ? ($isAvailable ? 'Prodotto aggiunto e reso disponibile nel catalogo di filiale.' : 'Prodotto presente nel catalogo di filiale ma segnato come non disponibile.')
                    : 'Prodotto nascosto dal catalogo della filiale.'
            );
        } elseif ($action === 'delete_product') {
            if (!$canManageGlobalCatalog) {
                throw new RuntimeException('Solo l admin centrale puo eliminare prodotti dal catalogo globale.');
            }

            $productId = (int) ($_POST['product_id'] ?? 0);
            if ($productId <= 0) {
                throw new RuntimeException('Prodotto non valido.');
            }

            catalog_delete_product($pdo, $productId);
            flash_set('success', 'Prodotto eliminato dal catalogo globale.');
        } else {
            throw new RuntimeException('Azione catalogo non riconosciuta.');
        }
    } catch (\Throwable $e) {
        flash_set('error', $e->getMessage());
    }

    header('Location: ' . ($sectionUrls['catalogo'] ?? 'admin_catalogo.php'));
    exit;
}

$flash = flash_get();
$backgroundMessages = admin_panel_background_messages($pdo, $isBranchManager, $selectedBranchId, (int) $utente['id']);

$branchManagers = [];
$templates = [];
$supplyOrders = [];
$policies = [];
$recentCustomerOrders = [];
$branchComparison = [];
$topProducts = [];
$salesTrend = [];
$categoryMix = [];
$kpis = analytics_get_branch_kpis($pdo, $selectedBranchId);

$pageTitle = 'Catalogo controllo - Smash Burger Original';
$pageDescription = 'Catalogo globale e configurazione locale di filiale.';
$currentPage = 'admin.php';
$breadcrumb = [
    ['Home', 'index.php'],
    ['Controllo', 'admin.php' . ($isGeneralAdmin ? '?sede=' . rawurlencode($selectedBranchSlug) : '')],
    ['Catalogo', null],
];

include_once __DIR__ . '/views/template/header.php';
include_once __DIR__ . '/views/admin.php';
include_once __DIR__ . '/views/template/footer.php';
