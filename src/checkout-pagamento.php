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
    header('Location: ' . app_route('checkout'));
    exit;
}

$carrello = cart_get_summary($pdo, $userId, $selectedBranchId);
if (empty($carrello['items'])) {
    flash_set('error', 'Il carrello è vuoto. Aggiungi almeno un prodotto prima del checkout.');
    header('Location: ' . app_route('carrello'));
    exit;
}

$checkoutFlow = $_SESSION['checkout_flow'] ?? [];
if (
    !is_array($checkoutFlow)
    || empty($checkoutFlow['started_at'])
    || (int) ($checkoutFlow['branch_id'] ?? 0) !== $selectedBranchId
) {
    flash_set('error', 'Per procedere, completa prima i passaggi precedenti del checkout.');
    header('Location: ' . app_route('checkout'));
    exit;
}

$fulfillmentType = (string) ($checkoutFlow['fulfillment_type'] ?? '');
if (!in_array($fulfillmentType, ['asporto', 'ritiro'], true)) {
    flash_set('error', 'Seleziona prima il metodo di ritiro.');
    header('Location: ' . app_route('checkout-ritiro'));
    exit;
}

$pickupAtRaw = (string) ($checkoutFlow['pickup_at'] ?? '');
$pickupDisplay = null;
if ($fulfillmentType === 'ritiro') {
    $pickupValidation = branch_validate_pickup_datetime($pdo, $selectedBranchId, $pickupAtRaw, null, true);
    if (!$pickupValidation['ok']) {
        flash_set('error', 'L\'orario di ritiro non è più valido. Selezionane uno nuovo.');
        header('Location: ' . app_route('checkout-ritiro'));
        exit;
    }
    $pickupAtRaw = (string) $pickupValidation['pickup_raw'];
    $pickupDisplay = (string) $pickupValidation['pickup_display'];
}

$errori = [];
$form = [
    'payment_method' => 'card',
    'card_number' => '',
    'card_holder' => '',
    'card_expiry' => '',
    'card_cvv' => '',
    'paypal_email' => '',
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $csrfTokenForm = $_POST['csrf_token'] ?? null;
    if (!csrf_is_valid($csrfTokenForm)) {
        $errori['generale'] = 'Sessione scaduta o richiesta non valida.';
    }

    $form['payment_method'] = (string) ($_POST['payment_method'] ?? 'card');
    $form['card_number'] = preg_replace('/\s+/', '', (string) ($_POST['card_number'] ?? ''));
    $form['card_holder'] = trim((string) ($_POST['card_holder'] ?? ''));
    $form['card_expiry'] = trim((string) ($_POST['card_expiry'] ?? ''));
    $form['card_cvv'] = trim((string) ($_POST['card_cvv'] ?? ''));
    $form['paypal_email'] = trim((string) ($_POST['paypal_email'] ?? ''));

    if (!in_array($form['payment_method'], ['card', 'paypal'], true)) {
        $errori['payment_method'] = 'Metodo di pagamento non valido.';
    }

    if ($form['payment_method'] === 'card') {
        if (strlen(preg_replace('/\D+/', '', $form['card_number'])) < 13) {
            $errori['card_number'] = 'Inserisci un numero carta valido.';
        }
        if (mb_strlen($form['card_holder']) < 3) {
            $errori['card_holder'] = 'Inserisci il nome intestatario della carta.';
        }
        if (!preg_match('/^(0[1-9]|1[0-2])\/[0-9]{2}$/', $form['card_expiry'])) {
            $errori['card_expiry'] = 'Formato scadenza non valido. Usa MM/AA.';
        }
        if (!preg_match('/^[0-9]{3,4}$/', $form['card_cvv'])) {
            $errori['card_cvv'] = 'CVV non valido.';
        }
    }

    if ($form['payment_method'] === 'paypal' && !filter_var($form['paypal_email'], FILTER_VALIDATE_EMAIL)) {
        $errori['paypal_email'] = 'Inserisci una email PayPal valida.';
    }

    if (empty($errori)) {
        $result = order_place(
            $pdo,
            $userId,
            $fulfillmentType,
            $form['payment_method'],
            $form['card_number'],
            $form['paypal_email'],
            $pickupAtRaw,
            $selectedBranchId
        );

        if ($result['ok']) {
            unset($_SESSION['checkout_flow']);

            $ritiroInfo = $fulfillmentType === 'asporto'
                ? 'Ritiro immediato presso ' . ($selectedBranch['name'] ?? 'la sede selezionata') . ': ordine pronto in pochi minuti.'
                : 'Ritiro previsto per ' . ($pickupDisplay ?? '') . ' presso ' . ($selectedBranch['name'] ?? 'la sede selezionata') . '.';

            flash_set(
                'success',
                'Ordine ' . $result['order_number'] . ' confermato. ' . $ritiroInfo
            );
            header('Location: ' . app_route('account'));
            exit;
        }

        $errori['generale'] = (string) ($result['message'] ?? 'Impossibile completare il pagamento.');
    }
}

$flash = flash_get();

render_page('checkout/checkout-pagamento.php', [
    'pageTitle' => 'Checkout - Pagamento - Smash Burger Original',
    'pageDescription' => 'Completa il pagamento del tuo ordine.',
    'currentPage' => 'checkout',
    'breadcrumb' => [
        ['Home', './'],
        ['Carrello', 'carrello'],
        ['Checkout', 'checkout'],
        ['Metodo ritiro', 'checkout-ritiro'],
        ['Pagamento', null],
    ],
    'carrello' => $carrello,
    'selectedBranch' => $selectedBranch,
    'fulfillmentType' => $fulfillmentType,
    'pickupDisplay' => $pickupDisplay,
    'form' => $form,
    'errori' => $errori,
    'flash' => $flash,
    'csrfToken' => $csrfToken
]);
