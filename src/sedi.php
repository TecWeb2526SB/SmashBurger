<?php
require_once __DIR__ . '/includes/resources.php';

$branchWarning = null;
$requestedBranchSlug = trim((string) ($_GET['sede'] ?? ''));
$allBranches = branches_get_all($pdo);
$selectedBranch = branch_get_selected($pdo);
$viewedBranch = $selectedBranch;

if ($requestedBranchSlug !== '') {
    $requestedBranch = branch_get_by_slug($pdo, $requestedBranchSlug);
    if ($requestedBranch !== null) {
        $viewedBranch = $requestedBranch;
    } else {
        $branchWarning = 'La sede richiesta non e disponibile.';
    }
}

$branchToClientPayload = static function (array $branch): array {
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
};

$branchesJson = json_encode(
    array_map($branchToClientPayload, $allBranches),
    JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
);

render_page('public/sedi.php', [
    'pageTitle' => 'Le nostre sedi - Smash Burger Original',
    'currentPage' => 'sedi',
    'breadcrumb' => [['Home', './'], ['Sedi', null]],
    'allBranches' => $allBranches,
    'selectedBranch' => $selectedBranch,
    'viewedBranch' => $viewedBranch,
    'branchesJson' => $branchesJson,
    'branchWarning' => $branchWarning
]);
