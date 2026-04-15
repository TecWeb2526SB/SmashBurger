<?php
require_once __DIR__ . '/includes/resources.php';

$context = admin_panel_bootstrap_context($pdo);
extract($context, EXTR_SKIP);

$currentSection = 'dashboard';
$sectionMeta = admin_panel_section_meta($currentSection, $canManageBranchManagers);
$sectionLinks = admin_panel_build_navigation($selectedBranchSlug, $isGeneralAdmin, $canManageBranchManagers, $currentSection);
$sectionUrls = [];
foreach ($sectionLinks as $sectionLink) {
    $sectionUrls[(string) $sectionLink['section']] = (string) $sectionLink['href'];
}

$csrfToken = csrf_token();
$flash = flash_get();
$backgroundMessages = admin_panel_background_messages($pdo, $isBranchManager, $selectedBranchId, (int) $utente['id']);

$inventoryItems = [];
$globalCatalog = [];
$categories = [];
$branchManagers = [];
$templates = [];
$supplyOrders = [];
$policies = [];
$recentCustomerOrders = [];

$kpis = analytics_get_branch_kpis($pdo, $selectedBranchId);
$topProducts = analytics_get_top_products($pdo, $selectedBranchId);
$salesTrend = analytics_get_sales_trend($pdo, $selectedBranchId);
$categoryMix = analytics_get_category_mix($pdo, $selectedBranchId);
$branchComparison = [];

if ($isGeneralAdmin) {
    foreach ($allBranches as $branch) {
        $branchComparison[] = [
            'branch' => $branch,
            'kpis' => analytics_get_branch_kpis($pdo, (int) $branch['id']),
        ];
    }
}

$pageTitle = 'Controllo - Smash Burger Original';
$pageDescription = 'Dashboard di controllo per analisi filiale, magazzino e operativita.';
$currentPage = 'controllo';
$breadcrumb = [
    ['Home', './'],
    ['Controllo', null],
];

include_once __DIR__ . '/views/template/header.php';
include_once __DIR__ . '/views/controllo/pannello.php';
include_once __DIR__ . '/views/template/footer.php';
