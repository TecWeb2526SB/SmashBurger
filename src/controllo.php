<?php
require_once __DIR__ . '/includes/resources.php';

$context = admin_panel_bootstrap_context($pdo);
$selectedBranchId = (int) ($context['selectedBranch']['id'] ?? 0);
$isGeneralAdmin = (string) ($context['utente']['role'] ?? '') === 'admin';

$branchComparison = [];
if ($isGeneralAdmin) {
    foreach ($context['allBranches'] as $branch) {
        $branchComparison[] = [
            'branch' => $branch,
            'kpis' => analytics_get_branch_kpis($pdo, (int) $branch['id']),
        ];
    }
}

render_admin_page('dashboard', [
    'pageDescription' => 'Dashboard di controllo per analisi filiale, magazzino e operatività.',
    'kpis' => analytics_get_branch_kpis($pdo, $selectedBranchId),
    'topProducts' => analytics_get_top_products($pdo, $selectedBranchId),
    'salesTrend' => analytics_get_sales_trend($pdo, $selectedBranchId),
    'categoryMix' => analytics_get_category_mix($pdo, $selectedBranchId),
    'branchComparison' => $branchComparison
]);
