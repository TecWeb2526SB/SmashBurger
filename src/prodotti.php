<?php
require_once __DIR__ . '/includes/resources.php';

$isAjax = isset($_GET['ajax']) || (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest');
$branchWarning = null;
$requestedBranchSlug = trim((string) ($_GET['sede'] ?? ''));
$force = isset($_GET['force']) && $_GET['force'] === '1';

if ($requestedBranchSlug !== '') {
    $ok = branch_select_by_slug($pdo, $requestedBranchSlug);
    if (!$ok) {
        $branchWarning = 'La sede richiesta non è disponibile.';
    } elseif (is_logged_in()) {
        $utente = current_user();
        $branchNow = branch_get_selected($pdo);
        $sync = cart_sync_with_selected_branch($pdo, (int)$utente['id'], $branchNow ? (int)$branchNow['id'] : 0, $force);
        if (!$sync['ok']) {
            $branchWarning = $sync['message'];
        }
    }

    if ($isAjax) {
        header('Content-Type: application/json');
        echo json_encode(['ok' => ($branchWarning === null), 'message' => $branchWarning]);
        exit;
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
