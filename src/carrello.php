<?php
require_once __DIR__ . '/includes/resources.php';

require_login();

$utente = current_user();
$userId = (int) $utente['id'];
$csrfToken = csrf_token();
$allBranches = branches_get_all($pdo);

$requestedBranchSlug = trim((string) ($_GET['sede'] ?? ''));
if ($requestedBranchSlug !== '') {
    if (branch_select_by_slug($pdo, $requestedBranchSlug)) {
        $branchAfterSelect = branch_get_selected($pdo);
        $sync = cart_sync_with_selected_branch(
            $pdo,
            $userId,
            $branchAfterSelect ? (int) $branchAfterSelect['id'] : 0
        );
        if ($sync['ok']) {
            flash_set('success', $sync['message'] ?? 'Sede aggiornata con successo.');
        } else {
            flash_set('error', $sync['message'] ?? 'Impossibile cambiare sede con carrello attivo.');
        }
    } else {
        flash_set('error', 'La sede selezionata non e disponibile.');
    }
    header('Location: carrello.php');
    exit;
}

$selectedBranch = branch_get_selected($pdo);
$selectedBranchId = $selectedBranch ? (int) $selectedBranch['id'] : 0;
$syncOnLoad = cart_sync_with_selected_branch($pdo, $userId, $selectedBranchId);
if (!$syncOnLoad['ok']) {
    flash_set('error', $syncOnLoad['message'] ?? 'Impossibile allineare sede e carrello.');
    $selectedBranch = branch_get_selected($pdo);
    $selectedBranchId = $selectedBranch ? (int) $selectedBranch['id'] : 0;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $csrfTokenForm = $_POST['csrf_token'] ?? null;
    if (!csrf_is_valid($csrfTokenForm)) {
        flash_set('error', 'Sessione scaduta o richiesta non valida.');
        header('Location: carrello.php');
        exit;
    }

    $action = (string) ($_POST['action'] ?? '');
    $redirectTo = (string) ($_POST['redirect_to'] ?? 'carrello.php');
    $allowedRedirects = ['carrello.php', 'prodotti.php'];
    if (!in_array($redirectTo, $allowedRedirects, true)) {
        $redirectTo = 'carrello.php';
    }

    if ($action === 'add_product') {
        $productId = (int) ($_POST['product_id'] ?? 0);
        $qty = max(1, (int) ($_POST['quantity'] ?? 1));
        $result = cart_add_product($pdo, $userId, $productId, $qty, $selectedBranchId);
        flash_set($result['ok'] ? 'success' : 'error', $result['message']);
    } elseif ($action === 'update_item') {
        $itemId = (int) ($_POST['item_id'] ?? 0);
        $qty = (int) ($_POST['quantity'] ?? 0);
        $result = cart_update_item_qty($pdo, $userId, $itemId, $qty, $selectedBranchId);
        flash_set($result['ok'] ? 'success' : 'error', $result['message']);
    } elseif ($action === 'remove_item') {
        $itemId = (int) ($_POST['item_id'] ?? 0);
        $result = cart_remove_item($pdo, $userId, $itemId, $selectedBranchId);
        flash_set($result['ok'] ? 'success' : 'error', $result['message']);
    } elseif ($action === 'clear_cart') {
        cart_clear($pdo, $userId, $selectedBranchId);
        flash_set('success', 'Carrello svuotato.');
    }

    header('Location: ' . $redirectTo);
    exit;
}

$carrello = cart_get_summary($pdo, $userId, $selectedBranchId);
$flash = flash_get();

$pageTitle       = 'Carrello - Smash Burger Original';
$pageDescription = 'Visualizza e gestisci i prodotti nel tuo carrello Smash Burger.';
$currentPage     = 'carrello.php';
$breadcrumb      = [['Home', 'index.php'], ['Carrello', null]];
include_once __DIR__ . '/views/template/header.php';
include_once __DIR__ . '/views/carrello.php';
include_once __DIR__ . '/views/template/footer.php';
