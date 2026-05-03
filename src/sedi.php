<?php
require_once __DIR__ . '/includes/resources.php';

$branchWarning = null;
$requestedBranchSlug = trim((string) ($_GET['sede'] ?? ''));
$allBranches = branches_get_all($pdo);
$selectedBranch = branch_get_selected($pdo);
$viewedBranch = $selectedBranch;

if ($requestedBranchSlug !== '') {
    $found = null;
    foreach ($allBranches as $b) {
        if ($b['slug'] === $requestedBranchSlug) { $found = $b; break; }
    }
    if ($found) { $viewedBranch = $found; } else { $branchWarning = 'La sede richiesta non e disponibile.'; }
}

$branchesJson = json_encode(array_map(static fn($b) => [
    'id' => (int) $b['id'], 'slug' => (string) $b['slug'], 'name' => (string) $b['name'],
    'city' => (string) $b['city'], 'province' => (string) $b['province'], 'address_line' => (string) $b['address_line'],
    'postal_code' => (string) $b['postal_code'], 'phone' => (string) $b['phone'], 'email' => (string) $b['email'],
    'pickup_notes' => (string) ($b['pickup_notes'] ?? ''), 'hours_compact' => (string) ($b['hours_compact'] ?? ''),
    'map_embed_url' => (string) ($b['map_embed_url'] ?? ''),
], $allBranches), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

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
