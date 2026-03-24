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
            flash_set('success', $sync['message'] ?? 'Sede checkout aggiornata.');
        } else {
            flash_set('error', $sync['message'] ?? 'Impossibile cambiare sede con carrello attivo.');
        }
    } else {
        flash_set('error', 'La sede selezionata non e disponibile.');
    }
    header('Location: checkout.php');
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

$errori = [];
$form = [
    'fulfillment_type' => 'ritiro',
    'pickup_at' => '',
    'payment_method' => 'card',
    'card_number' => '',
    'paypal_email' => '',
    'branch_id' => $selectedBranchId,
];

$carrello = cart_get_summary($pdo, $userId, $selectedBranchId);
if (empty($carrello['items'])) {
    $nomeSede = !empty($selectedBranch['name']) ? ' per ' . $selectedBranch['name'] : '';
    flash_set('error', 'Il carrello' . $nomeSede . ' e vuoto. Aggiungi almeno un prodotto prima del checkout.');
    header('Location: carrello.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $csrfTokenForm = $_POST['csrf_token'] ?? null;
    if (!csrf_is_valid($csrfTokenForm)) {
        $errori['generale'] = 'Sessione scaduta o richiesta non valida.';
    }

    $form['fulfillment_type'] = (string) ($_POST['fulfillment_type'] ?? 'ritiro');
    $form['pickup_at'] = (string) ($_POST['pickup_at'] ?? '');
    $form['payment_method'] = (string) ($_POST['payment_method'] ?? 'card');
    $form['card_number'] = preg_replace('/\s+/', '', (string) ($_POST['card_number'] ?? ''));
    $form['paypal_email'] = trim((string) ($_POST['paypal_email'] ?? ''));

    if (!in_array($form['fulfillment_type'], ['asporto', 'ritiro'], true)) {
        $errori['fulfillment_type'] = 'Modalita ritiro non valida.';
    }
    if (!in_array($form['payment_method'], ['card', 'paypal', 'cash'], true)) {
        $errori['payment_method'] = 'Metodo di pagamento non valido.';
    }
    if ($form['fulfillment_type'] === 'ritiro' && $form['pickup_at'] === '') {
        $errori['pickup_at'] = 'Seleziona data e orario per il ritiro.';
    }

    if (empty($errori)) {
        $result = order_place(
            $pdo,
            $userId,
            $form['fulfillment_type'],
            $form['payment_method'],
            $form['card_number'],
            $form['paypal_email'],
            $form['pickup_at'],
            $selectedBranchId
        );

        if ($result['ok']) {
            flash_set(
                'success',
                'Ordine ' . $result['order_number'] . ' confermato. ' . $result['message']
            );
            header('Location: area_personale.php');
            exit;
        }

        $errori['generale'] = $result['message'];
    }
}

$flash = flash_get();

$pageTitle       = 'Checkout - Smash Burger Original';
$pageDescription = 'Completa il tuo ordine Smash Burger in modo rapido e sicuro.';
$currentPage     = 'checkout.php';
$breadcrumb      = [['Home', 'index.php'], ['Checkout', null]];
include_once __DIR__ . '/views/template/header.php';
include_once __DIR__ . '/views/checkout.php';
include_once __DIR__ . '/views/template/footer.php';
