<?php
require_once __DIR__ . '/includes/resources.php';

$context = admin_panel_bootstrap_context($pdo);
$selectedBranchId = (int) ($context['selectedBranch']['id'] ?? 0);
$selectedBranchSlug = (string) ($context['selectedBranch']['slug'] ?? '');
$isGeneralAdmin = (string) ($context['utente']['role'] ?? '') === 'admin';
$canModifyBranchOperations = (string) ($context['utente']['role'] ?? '') === 'branch_manager';
$canManageGlobalCatalog = can_manage_global_catalog();

$inventoryItems = inventory_get_branch_products($pdo, $selectedBranchId);
$productsById = admin_build_products_lookup($inventoryItems);
$globalCatalog = catalog_get_all_products_with_branch_usage($pdo);
$categories = categories_get_all($pdo);

$catalogSelectedCategoryId = max(0, (int) ($_GET['categoria'] ?? 0));
$categoriesById = [];
$catalogCategoryCounts = [];
foreach ($categories as $category) {
    $categoryId = (int) $category['id'];
    $categoriesById[$categoryId] = $category;
    $catalogCategoryCounts[$categoryId] = 0;
}

if ($catalogSelectedCategoryId > 0 && !isset($categoriesById[$catalogSelectedCategoryId])) {
    $catalogSelectedCategoryId = 0;
}

foreach ($inventoryItems as $inventoryItem) {
    $categoryId = (int) ($inventoryItem['category_id'] ?? 0);
    if (isset($catalogCategoryCounts[$categoryId])) {
        $catalogCategoryCounts[$categoryId]++;
    }
}

$filteredGlobalCatalog = array_values(array_filter(
    $globalCatalog,
    static function (array $product) use ($catalogSelectedCategoryId): bool {
        return $catalogSelectedCategoryId <= 0
            || (int) ($product['category_id'] ?? 0) === $catalogSelectedCategoryId;
    }
));

$filteredInventoryItems = array_values(array_filter(
    $inventoryItems,
    static function (array $inventoryItem) use ($catalogSelectedCategoryId): bool {
        return $catalogSelectedCategoryId <= 0
            || (int) ($inventoryItem['category_id'] ?? 0) === $catalogSelectedCategoryId;
    }
));

$catalogCategoryLinks = [[
    'label' => 'Tutte',
    'href' => app_route('controllo-catalogo', $isGeneralAdmin ? ['sede' => $selectedBranchSlug] : []),
    'is_active' => $catalogSelectedCategoryId === 0,
    'count' => count($inventoryItems),
]];

foreach ($categories as $category) {
    $categoryId = (int) $category['id'];
    $params = $isGeneralAdmin ? ['sede' => $selectedBranchSlug] : [];
    $params['categoria'] = $categoryId;
    $catalogCategoryLinks[] = [
        'label' => (string) $category['name'],
        'href' => app_route('controllo-catalogo', $params),
        'is_active' => $catalogSelectedCategoryId === $categoryId,
        'count' => (int) ($catalogCategoryCounts[$categoryId] ?? 0),
    ];
}

$catalogMetrics = [
    'global_total' => count($filteredGlobalCatalog),
    'branch_total' => count($filteredInventoryItems),
    'branch_available' => count(array_filter($filteredInventoryItems, static fn($i) => (int)($i['is_listed'] ?? 0) === 1 && (int)($i['is_available_for_sale'] ?? 0) === 1)),
];

$catalogSelectedCategoryLabel = $catalogSelectedCategoryId > 0 ? (string) ($categoriesById[$catalogSelectedCategoryId]['name'] ?? 'Categoria') : 'Tutte le categorie';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_is_valid($_POST['csrf_token'] ?? null)) {
        flash_set('error', 'Sessione scaduta. Riprova.');
    } else {
        $action = (string) ($_POST['action'] ?? '');
        try {
            if ($action === 'branch_catalog_state' && $canModifyBranchOperations) {
                $productId = (int) ($_POST['product_id'] ?? 0);
                branch_catalog_set_product_state($pdo, $selectedBranchId, $productId, (string)($_POST['is_listed'] ?? '0') === '1', (string)($_POST['is_available'] ?? '0') === '1');
                flash_set('success', 'Stato prodotto aggiornato.');
            } elseif ($action === 'update_branch_pricing' && $canModifyBranchOperations) {
                $productId = (int) ($_POST['product_id'] ?? 0);
                $branchPriceCents = admin_parse_money_to_cents((string) ($_POST['sale_price'] ?? ''));
                $product = $productsById[$productId];
                branch_catalog_set_product_state($pdo, $selectedBranchId, $productId, (int)($product['is_listed'] ?? 0) === 1, (int)($product['branch_availability_flag'] ?? 0) === 1, $branchPriceCents === (int)$product['base_price_cents'] ? null : $branchPriceCents);
                flash_set('success', 'Prezzo aggiornato.');
            } elseif ($action === 'delete_product' && $canManageGlobalCatalog) {
                catalog_delete_product($pdo, (int)($_POST['product_id'] ?? 0));
                flash_set('success', 'Prodotto eliminato.');
            }
        } catch (\Throwable $e) { flash_set('error', $e->getMessage()); }
    }
    header('Location: ' . $_SERVER['REQUEST_URI']);
    exit;
}

render_admin_page('catalogo', [
    'catalogSelectedCategoryLabel' => $catalogSelectedCategoryLabel,
    'catalogCategoryLinks' => $catalogCategoryLinks,
    'catalogMetrics' => $catalogMetrics,
    'filteredGlobalCatalog' => $filteredGlobalCatalog,
    'filteredInventoryItems' => $filteredInventoryItems,
    'inventoryItems' => $inventoryItems
]);
