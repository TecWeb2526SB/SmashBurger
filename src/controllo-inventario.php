<?php
require_once __DIR__ . '/includes/resources.php';

$context = admin_panel_bootstrap_context($pdo);
$selectedBranchId = (int) ($context['selectedBranch']['id'] ?? 0);

$inventoryItems = inventory_get_branch_products($pdo, $selectedBranchId);

render_admin_page('inventario', [
    'inventoryItems' => $inventoryItems,
    'inventoryProductCount' => count($inventoryItems),
    'inventoryAvailableCount' => count(array_filter($inventoryItems, static fn($i) => (int)$i['is_available_for_sale'] === 1)),
    'inventoryPendingUnits' => (int) array_sum(array_column($inventoryItems, 'pending_supply_qty')),
    'inventoryBelowThresholdCount' => count(array_filter($inventoryItems, static fn($i) => (int)$i['is_below_threshold'] === 1)),
    'inventoryAdjustmentModes' => admin_inventory_adjustment_modes(),
    'inventoryPendingProductsCount' => count(array_filter($inventoryItems, static fn($i) => (int)$i['pending_supply_qty'] > 0)),
    'kpis' => analytics_get_branch_kpis($pdo, $selectedBranchId)
]);
