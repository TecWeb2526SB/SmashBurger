<?php
require_once __DIR__ . '/includes/resources.php';

$context = admin_panel_bootstrap_context($pdo);
$selectedBranchId = (int) ($context['selectedBranch']['id'] ?? 0);
$selectedBranchSlug = (string) ($context['selectedBranch']['slug'] ?? '');
$isGeneralAdmin = (string) ($context['utente']['role'] ?? '') === 'admin';

$templates = supply_get_templates($pdo, $selectedBranchId);
$supplyOrders = supply_get_orders($pdo, $selectedBranchId);
$policies = auto_reorder_get_policies($pdo, $selectedBranchId);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (csrf_is_valid($_POST['csrf_token'] ?? null)) {
        $action = (string) ($_POST['action'] ?? '');
        try {
            if ($action === 'toggle_template') {
                supply_template_set_active($pdo, (int)($_POST['template_id'] ?? 0), (string)($_POST['is_active'] ?? '0') === '1');
            } elseif ($action === 'toggle_policy') {
                auto_reorder_policy_set_active($pdo, (int)($_POST['policy_id'] ?? 0), (string)($_POST['is_active'] ?? '0') === '1');
            } elseif ($action === 'receive_supply') {
                supply_receive_order($pdo, (int)($_POST['supply_order_id'] ?? 0), (int)$context['utente']['id']);
            } elseif ($action === 'cancel_supply') {
                supply_cancel_order($pdo, (int)($_POST['supply_order_id'] ?? 0));
            }
            flash_set('success', 'Operazione completata.');
        } catch (\Throwable $e) { flash_set('error', $e->getMessage()); }
    }
    header('Location: ' . $_SERVER['REQUEST_URI']);
    exit;
}

render_admin_page('forniture', [
    'templates' => $templates,
    'supplyOrders' => $supplyOrders,
    'policies' => $policies,
    'frequencyLabels' => supply_frequency_options(),
    'activeTemplatesCount' => count(array_filter($templates, static fn($t) => (int)$t['is_active'] === 1)),
    'openSupplyOrdersCount' => count(array_filter($supplyOrders, static fn($o) => in_array((string)$o['status'], ['draft', 'scheduled', 'ordered'], true))),
    'receivedSupplyOrdersCount' => count(array_filter($supplyOrders, static fn($o) => (string)$o['status'] === 'received')),
    'activePoliciesCount' => count(array_filter($policies, static fn($p) => (int)$p['is_active'] === 1)),
    'standardSupplyBuilderUrl' => admin_supply_builder_url('standard', $selectedBranchSlug, $isGeneralAdmin),
    'extraSupplyBuilderUrl' => admin_supply_builder_url('extra', $selectedBranchSlug, $isGeneralAdmin),
    'automaticSupplyBuilderUrl' => admin_supply_builder_url('automatic', $selectedBranchSlug, $isGeneralAdmin)
]);
