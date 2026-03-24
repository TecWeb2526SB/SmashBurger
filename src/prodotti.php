<?php
require_once __DIR__ . '/includes/resources.php';

$branchWarning = null;
$requestedBranchSlug = trim((string) ($_GET['sede'] ?? ''));
if ($requestedBranchSlug !== '' && !branch_select_by_slug($pdo, $requestedBranchSlug)) {
    $branchWarning = 'La sede richiesta non e disponibile. Ti mostriamo una sede valida.';
} elseif ($requestedBranchSlug !== '' && is_logged_in()) {
    $utente = current_user();
    $branchNow = branch_get_selected($pdo);
    $sync = cart_sync_with_selected_branch(
        $pdo,
        (int) ($utente['id'] ?? 0),
        $branchNow ? (int) $branchNow['id'] : 0
    );
    if (!$sync['ok']) {
        $branchWarning = $sync['message'] ?? 'Impossibile cambiare sede con carrello attivo.';
    }
}

$allBranches = branches_get_all($pdo);
$selectedBranch = branch_get_selected($pdo);
$catalogoCategorie = catalog_get($pdo, $selectedBranch ? (int) $selectedBranch['id'] : null);
$flash = flash_get();

$pageTitle       = 'I nostri prodotti - Smash Burger Original';
$pageDescription = 'Scopri tutti i burger, i contorni e le bevande di Smash Burger Original.';
$currentPage     = 'prodotti.php';
$breadcrumb      = [['Home', 'index.php'], ['I nostri prodotti', null]];
include_once __DIR__ . '/views/template/header.php';
include_once __DIR__ . '/views/prodotti.php';
include_once __DIR__ . '/views/template/footer.php';
