<?php
require_once __DIR__ . '/includes/resources.php';

if (!is_logged_in() && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $csrfTokenForm = $_POST['csrf_token'] ?? null;
    if (!csrf_is_valid($csrfTokenForm)) {
        flash_set('error', 'Sessione scaduta o richiesta non valida.');
        header('Location: accedi?redirect=' . rawurlencode(auth_normalize_redirect_target((string) ($_POST['redirect_to'] ?? 'prodotti'), 'prodotti')));
        exit;
    }

    $redirectTo = auth_normalize_redirect_target((string) ($_POST['redirect_to'] ?? 'prodotti'), 'prodotti');
    flash_set('error', 'Per continuare devi effettuare l\'accesso.');
    header('Location: accedi?redirect=' . rawurlencode($redirectTo));
    exit;
}

require_customer_order_access();

$utente = current_user();
$userId = (int) $utente['id'];
$csrfToken = csrf_token();

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
        header('Location: carrello');
        exit;
    }

    $action = (string) ($_POST['action'] ?? '');
    $isAjax = isset($_POST['ajax']) || (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest');
    $result = ['ok' => false, 'message' => 'Azione non valida.'];

    if ($action === 'add_product') {
        $productId = (int) ($_POST['product_id'] ?? 0);
        $qty = max(1, (int) ($_POST['quantity'] ?? 1));
        $result = cart_add_product($pdo, $userId, $productId, $qty, $selectedBranchId);
    } elseif ($action === 'update_item') {
        $itemId = (int) ($_POST['item_id'] ?? 0);
        $qty = (int) ($_POST['quantity'] ?? 0);
        $result = cart_update_item_qty($pdo, $userId, $itemId, $qty, $selectedBranchId);
    } elseif ($action === 'remove_item') {
        $itemId = (int) ($_POST['item_id'] ?? 0);
        $result = cart_remove_item($pdo, $userId, $itemId, $selectedBranchId);
    } elseif ($action === 'clear_cart') {
        cart_clear($pdo, $userId, $selectedBranchId);
        $result = ['ok' => true, 'message' => 'Carrello svuotato.'];
    }

    if ($isAjax) {
        if ($result['ok']) {
            $summary = cart_get_summary($pdo, $userId, $selectedBranchId);
            $result['cart_count'] = (int) ($summary['items_count'] ?? 0);
            $result['cart_total_formatted'] = money_eur((int) ($summary['total_cents'] ?? 0));
            $result['cart_is_empty'] = empty($summary['items']);

            if ($action === 'update_item' || $action === 'remove_item') {
                $itemId = (int) ($_POST['item_id'] ?? 0);
                foreach ($summary['items'] as $summaryItem) {
                    if ((int) $summaryItem['id'] !== $itemId) {
                        continue;
                    }

                    $result['item'] = [
                        'id' => (int) $summaryItem['id'],
                        'quantity' => (int) $summaryItem['quantity'],
                        'line_total_formatted' => money_eur((int) $summaryItem['line_total_cents']),
                    ];
                    break;
                }
            }
        }
        header('Content-Type: application/json');
        echo json_encode($result);
        exit;
    }

    flash_set($result['ok'] ? 'success' : 'error', $result['message']);
    
    $allowedRedirects = ['./', 'carrello', 'prodotti'];
    $redirectTo = (string) ($_POST['redirect_to'] ?? 'carrello');
    if (!in_array($redirectTo, $allowedRedirects, true)) {
        $redirectTo = 'carrello';
    }

    header('Location: ' . $redirectTo);
    exit;
}

$carrello = cart_get_summary($pdo, $userId, $selectedBranchId);
$flash = flash_get();

render_page('checkout/carrello.php', [
    'pageTitle' => 'Carrello - Smash Burger Original',
    'pageDescription' => 'Visualizza e gestisci i prodotti nel tuo carrello Smash Burger.',
    'currentPage' => 'carrello',
    'breadcrumb' => [['Home', './'], ['Carrello', null]],
    'carrello' => $carrello,
    'flash' => $flash,
    'selectedBranch' => $selectedBranch,
    'csrfToken' => $csrfToken
]);
