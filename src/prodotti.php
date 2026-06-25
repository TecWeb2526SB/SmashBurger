<?php
require_once __DIR__ . '/includes/resources.php';

$branchWarning = null;
$initialFlash = flash_get();
if (is_array($initialFlash) && ($initialFlash['type'] ?? '') === 'error') {
    $branchWarning = (string) ($initialFlash['message'] ?? '');
}

$selectedBranch = branch_get_selected($pdo);

render_page('public/prodotti.php', [
    'pageTitle' => 'I nostri prodotti - Smash Burger Original',
    'currentPage' => 'prodotti',
    'breadcrumb' => [['Home', './'], ['I nostri prodotti', null]],
    'allBranches' => branches_get_all($pdo),
    'selectedBranch' => $selectedBranch,
    'catalogoCategorie' => catalog_get($pdo, $selectedBranch ? (int)$selectedBranch['id'] : null),
    'branchWarning' => $branchWarning
]);
