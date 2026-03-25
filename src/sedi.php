<?php
require_once __DIR__ . '/includes/resources.php';

$branchWarning = null;
$requestedBranchSlug = trim((string) ($_GET['sede'] ?? ''));
if ($requestedBranchSlug !== '' && !branch_select_by_slug($pdo, $requestedBranchSlug)) {
    $branchWarning = 'La sede selezionata non e disponibile. Ti mostriamo una sede valida.';
}

$allBranches = branches_get_all($pdo);
$selectedBranch = branch_get_selected($pdo);

$branchesForJs = array_map(static function (array $branch): array {
    return [
        'id' => (int) $branch['id'],
        'slug' => (string) $branch['slug'],
        'name' => (string) $branch['name'],
        'city' => (string) $branch['city'],
        'province' => (string) $branch['province'],
        'address_line' => (string) $branch['address_line'],
        'postal_code' => (string) $branch['postal_code'],
        'phone' => (string) $branch['phone'],
        'email' => (string) $branch['email'],
        'pickup_notes' => (string) ($branch['pickup_notes'] ?? ''),
        'hours_compact' => (string) ($branch['hours_compact'] ?? ''),
        'map_embed_url' => (string) ($branch['map_embed_url'] ?? ''),
    ];
}, $allBranches);

$branchesJson = json_encode($branchesForJs, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

$pageTitle       = 'Chi siamo e le nostre sedi - Smash Burger Original';
$pageDescription = 'Scopri la storia di Smash Burger e trova la sede più vicina a te.';
$currentPage     = 'sedi.php';
$breadcrumb      = [['Home', 'index.php'], ['Chi siamo', null]];
include_once __DIR__ . '/views/template/header.php';
include_once __DIR__ . '/views/sedi.php';
include_once __DIR__ . '/views/template/footer.php';
