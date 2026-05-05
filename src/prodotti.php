<?php
require_once __DIR__ . '/includes/resources.php';

$isAjax = isset($_GET['ajax']) || (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest');
$branchWarning = null;
$requestedBranchSlug = trim((string) ($_GET['sede'] ?? ''));

if ($requestedBranchSlug !== '') {
    $ok = branch_select_by_slug($pdo, $requestedBranchSlug);
    if (!$ok) {
        $branchWarning = 'La sede richiesta non e disponibile.';
    } elseif (is_logged_in()) {
        $branchNow = branch_get_selected($pdo);
        $sync = cart_sync_with_selected_branch($pdo, (int)$_SESSION['user']['id'], $branchNow ? (int)$branchNow['id'] : 0, (isset($_GET['force']) && $_GET['force'] === '1'));
        if (!$sync['ok']) { $branchWarning = $sync['message']; }
    }
    if ($isAjax) {
        header('Content-Type: application/json');
        echo json_encode(['ok' => ($branchWarning === null), 'message' => $branchWarning]);
        exit;
    }
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
