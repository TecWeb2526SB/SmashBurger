<?php
require_once __DIR__ . '/includes/resources.php';

$branchWarning = null;
$initialFlash = flash_get();
if (is_array($initialFlash) && ($initialFlash['type'] ?? '') === 'error') {
    $branchWarning = (string) ($initialFlash['message'] ?? '');
}

$allBranches = branches_get_all($pdo);
$selectedBranch = branch_get_selected($pdo);
$viewedBranch = $selectedBranch;

if ($viewedBranch === null && !empty($allBranches)) {
    $viewedBranch = $allBranches[0];
}

if ($selectedBranch === null && !empty($allBranches)) {
    $selectedBranch = $viewedBranch;
}

$branchesJson = json_encode(
    array_map(static function (array $branch): array {
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
    }, $allBranches),
    JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
);

render_page('public/sedi.php', [
    'pageTitle' => 'Le nostre sedi - Smash Burger Original',
    'pageDescription' => 'Trova la sede Smash Burger più vicina: orari, indirizzi, contatti e mappa.',
    'currentPage' => 'sedi',
    'breadcrumb' => [['Home', './'], ['Sedi', null]],
    'allBranches' => $allBranches,
    'selectedBranch' => $selectedBranch,
    'viewedBranch' => $viewedBranch,
    'branchWarning' => $branchWarning,
    'branchesJson' => $branchesJson,
]);
