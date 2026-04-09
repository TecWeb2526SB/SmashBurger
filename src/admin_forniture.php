<?php
require_once __DIR__ . '/includes/resources.php';

$context = admin_panel_bootstrap_context($pdo);
extract($context, EXTR_SKIP);

$currentSection = 'forniture';
$sectionMeta = admin_panel_section_meta($currentSection, $canManageBranchManagers);
$sectionLinks = admin_panel_build_navigation($selectedBranchSlug, $isGeneralAdmin, $canManageBranchManagers, $currentSection);
$sectionUrls = [];
foreach ($sectionLinks as $sectionLink) {
    $sectionUrls[(string) $sectionLink['section']] = (string) $sectionLink['href'];
}

$inventoryItems = admin_prepare_supply_products($pdo, $selectedBranchId, inventory_get_branch_products($pdo, $selectedBranchId));
$productsById = admin_build_products_lookup($inventoryItems);
$csrfToken = csrf_token();
$flash = null;
$backgroundMessages = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $csrfTokenForm = $_POST['csrf_token'] ?? null;
    if (!csrf_is_valid($csrfTokenForm)) {
        flash_set('error', 'Sessione scaduta o richiesta non valida. Ricarica la pagina e riprova.');
        header('Location: ' . ($sectionUrls['forniture'] ?? 'admin_forniture.php'));
        exit;
    }

    $action = (string) ($_POST['action'] ?? '');

    try {
        if (!$canModifyBranchOperations) {
            throw new RuntimeException('Solo il manager della filiale puo gestire le forniture.');
        }

        if ($action === 'create_standard_template') {
            $templateName = trim((string) ($_POST['template_name'] ?? ''));
            $frequency = (string) ($_POST['frequency'] ?? 'weekly');
            $nextRunAtSql = parse_local_datetime_to_sql((string) ($_POST['next_run_at'] ?? ''));
            $notes = trim((string) ($_POST['notes'] ?? ''));
            $items = admin_extract_supply_items($pdo, $selectedBranchId, $_POST, 'template', $productsById);

            supply_create_template($pdo, $selectedBranchId, (int) $utente['id'], $templateName, $frequency, (string) $nextRunAtSql, $items, $notes);
            flash_set('success', 'Fornitura standard programmata con successo.');
        } elseif ($action === 'toggle_template') {
            $templateId = (int) ($_POST['template_id'] ?? 0);
            $isActive = (string) ($_POST['is_active'] ?? '0') === '1';
            supply_toggle_template($pdo, $selectedBranchId, $templateId, $isActive);
            flash_set('success', $isActive ? 'Template riattivato.' : 'Template sospeso.');
        } elseif ($action === 'create_extra_supply') {
            $scheduledForSql = parse_local_datetime_to_sql((string) ($_POST['scheduled_for'] ?? ''));
            $notes = trim((string) ($_POST['notes'] ?? ''));
            $supplierName = trim((string) ($_POST['supplier_name'] ?? 'Centro forniture SmashBurger'));
            $items = admin_extract_supply_items($pdo, $selectedBranchId, $_POST, 'extra', $productsById);
            $status = $scheduledForSql !== null ? 'scheduled' : 'ordered';

            supply_create_order($pdo, $selectedBranchId, (int) $utente['id'], 'extraordinary', $status, $items, $scheduledForSql, null, $notes, $supplierName);
            flash_set('success', 'Fornitura straordinaria registrata.');
        } elseif ($action === 'receive_supply') {
            $supplyOrderId = (int) ($_POST['supply_order_id'] ?? 0);
            supply_receive_order($pdo, $selectedBranchId, $supplyOrderId, (int) $utente['id']);
            flash_set('success', 'Ricezione fornitura confermata e inventario aggiornato.');
        } elseif ($action === 'cancel_supply') {
            $supplyOrderId = (int) ($_POST['supply_order_id'] ?? 0);
            supply_cancel_order($pdo, $selectedBranchId, $supplyOrderId);
            flash_set('success', 'Fornitura annullata.');
        } elseif ($action === 'create_policy') {
            $productId = (int) ($_POST['product_id'] ?? 0);
            if ($productId <= 0 || !isset($productsById[$productId])) {
                throw new RuntimeException('Seleziona un prodotto valido per la policy di riordino.');
            }

            auto_reorder_upsert_policy(
                $pdo,
                $selectedBranchId,
                $productId,
                (int) ($_POST['threshold_qty'] ?? 0),
                (int) ($_POST['reorder_qty'] ?? 0),
                (int) ($_POST['cooldown_hours'] ?? 6),
                (int) ($_POST['max_pending_qty'] ?? 0),
                (string) ($_POST['mode'] ?? 'draft')
            );
            flash_set('success', 'Policy di ordine automatico salvata.');
        } elseif ($action === 'toggle_policy') {
            $policyId = (int) ($_POST['policy_id'] ?? 0);
            $isActive = (string) ($_POST['is_active'] ?? '0') === '1';
            auto_reorder_toggle_policy($pdo, $selectedBranchId, $policyId, $isActive);
            flash_set('success', $isActive ? 'Policy riattivata.' : 'Policy sospesa.');
        } elseif ($action === 'run_auto_reorder') {
            $generatedOrders = auto_reorder_evaluate_branch($pdo, $selectedBranchId, (int) $utente['id']);
            flash_set(
                'success',
                $generatedOrders > 0
                    ? 'Controllo ordine automatico completato: generate ' . $generatedOrders . ' forniture.'
                    : 'Controllo ordine automatico completato: nessuna nuova fornitura necessaria.'
            );
        } else {
            throw new RuntimeException('Azione forniture non riconosciuta.');
        }
    } catch (\Throwable $e) {
        flash_set('error', $e->getMessage());
    }

    header('Location: ' . ($sectionUrls['forniture'] ?? 'admin_forniture.php'));
    exit;
}

$flash = flash_get();
$backgroundMessages = admin_panel_background_messages($pdo, $isBranchManager, $selectedBranchId, (int) $utente['id']);
$templates = supply_get_templates($pdo, $selectedBranchId);
$supplyOrders = supply_get_orders($pdo, $selectedBranchId);
$policies = auto_reorder_get_policies($pdo, $selectedBranchId);
$kpis = analytics_get_branch_kpis($pdo, $selectedBranchId);

$globalCatalog = [];
$categories = [];
$branchManagers = [];
$recentCustomerOrders = [];
$branchComparison = [];
$topProducts = [];
$salesTrend = [];
$categoryMix = [];

$pageTitle = 'Forniture controllo - Smash Burger Original';
$pageDescription = 'Forniture periodiche, ordini straordinari, riordino automatico e ricevute.';
$currentPage = 'admin.php';
$breadcrumb = [
    ['Home', 'index.php'],
    ['Controllo', 'admin.php' . ($isGeneralAdmin ? '?sede=' . rawurlencode($selectedBranchSlug) : '')],
    ['Forniture', null],
];

include_once __DIR__ . '/views/template/header.php';
include_once __DIR__ . '/views/admin.php';
include_once __DIR__ . '/views/template/footer.php';
