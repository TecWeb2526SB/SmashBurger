<?php
require_once __DIR__ . '/includes/resources.php';

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

$carrello = cart_get_summary($pdo, $userId, $selectedBranchId);
if (empty($carrello['items'])) {
    $nomeSede = !empty($selectedBranch['name']) ? ' per ' . $selectedBranch['name'] : '';
    flash_set('error', 'Il carrello' . $nomeSede . ' e vuoto. Aggiungi almeno un prodotto prima del checkout.');
    header('Location: carrello');
    exit;
}

$errori = [];
$checkoutFlow = $_SESSION['checkout_flow'] ?? [];
if (!is_array($checkoutFlow) || (int) ($checkoutFlow['branch_id'] ?? 0) !== $selectedBranchId) {
    $checkoutFlow = [];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $csrfTokenForm = $_POST['csrf_token'] ?? null;
    if (!csrf_is_valid($csrfTokenForm)) {
        $errori['generale'] = 'Sessione scaduta o richiesta non valida.';
    } else {
        $_SESSION['checkout_flow'] = [
            'started_at' => time(),
            'branch_id' => $selectedBranchId,
            'fulfillment_type' => (string) ($checkoutFlow['fulfillment_type'] ?? 'asporto'),
            'pickup_at' => (string) ($checkoutFlow['pickup_at'] ?? ''),
        ];
        header('Location: checkout-ritiro');
        exit;
    }
}

$flash = flash_get();

$pageTitle       = 'Checkout';
$pageDescription = 'Riepilogo ordine e avvio checkout.';
$currentPage     = 'checkout';
$breadcrumb      = [['Home', './'], ['Carrello', 'carrello'], ['Checkout', null]];
include_once __DIR__ . '/views/template/header.php';
include_once __DIR__ . '/views/checkout/checkout.php';
include_once __DIR__ . '/views/template/footer.php';
