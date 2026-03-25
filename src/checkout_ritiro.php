<?php
require_once __DIR__ . '/includes/resources.php';

require_login();

$utente = current_user();
$userId = (int) $utente['id'];
$csrfToken = csrf_token();

$selectedBranch = branch_get_selected($pdo);
$selectedBranchId = $selectedBranch ? (int) $selectedBranch['id'] : 0;
$syncOnLoad = cart_sync_with_selected_branch($pdo, $userId, $selectedBranchId);
if (!$syncOnLoad['ok']) {
    flash_set('error', $syncOnLoad['message'] ?? 'Impossibile allineare sede e carrello.');
    header('Location: checkout.php');
    exit;
}

$carrello = cart_get_summary($pdo, $userId, $selectedBranchId);
if (empty($carrello['items'])) {
    flash_set('error', 'Il carrello e vuoto. Aggiungi almeno un prodotto prima del checkout.');
    header('Location: carrello.php');
    exit;
}

$checkoutFlow = $_SESSION['checkout_flow'] ?? [];
if (
    !is_array($checkoutFlow)
    || empty($checkoutFlow['started_at'])
    || (int) ($checkoutFlow['branch_id'] ?? 0) !== $selectedBranchId
) {
    flash_set('error', 'Per procedere, conferma prima il riepilogo ordine.');
    header('Location: checkout.php');
    exit;
}

$todaySlots = branch_get_today_pickup_slots($pdo, $selectedBranchId, 10);
$defaultSlot = !empty($todaySlots) ? (string) $todaySlots[0]['time'] : '';

$flowFulfillmentType = (string) ($checkoutFlow['fulfillment_type'] ?? 'asporto');
$flowPickupRaw = (string) ($checkoutFlow['pickup_at'] ?? '');
$flowPickupTime = '';
if ($flowPickupRaw !== '') {
    $flowDt = \DateTimeImmutable::createFromFormat('Y-m-d\TH:i', $flowPickupRaw, new \DateTimeZone('Europe/Rome'));
    if ($flowDt !== false) {
        $flowPickupTime = $flowDt->format('H:i');
    }
}

$errori = [];
$form = [
    'pickup_mode' => $flowFulfillmentType === 'ritiro' ? 'orario' : 'immediato',
    'pickup_time' => $flowPickupTime !== '' ? $flowPickupTime : $defaultSlot,
];
if (empty($todaySlots) && $form['pickup_mode'] === 'orario') {
    $form['pickup_mode'] = 'immediato';
    $form['pickup_time'] = '';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $csrfTokenForm = $_POST['csrf_token'] ?? null;
    if (!csrf_is_valid($csrfTokenForm)) {
        $errori['generale'] = 'Sessione scaduta o richiesta non valida.';
    }

    $form['pickup_mode'] = (string) ($_POST['pickup_mode'] ?? 'immediato');
    $form['pickup_time'] = trim((string) ($_POST['pickup_time'] ?? ''));

    if (!in_array($form['pickup_mode'], ['immediato', 'orario'], true)) {
        $errori['fulfillment_type'] = 'Metodo di ritiro non valido.';
    }

    $fulfillmentType = $form['pickup_mode'] === 'orario' ? 'ritiro' : 'asporto';
    $pickupRaw = '';

    if ($form['pickup_mode'] === 'orario') {
        if (empty($todaySlots)) {
            $errori['pickup_at'] = 'Oggi non ci sono orari disponibili per il ritiro programmato.';
        }

        if (!preg_match('/^([01][0-9]|2[0-3]):[0-5][0-9]$/', $form['pickup_time'])) {
            $errori['pickup_at'] = 'Seleziona un orario valido.';
        }

        if (empty($errori['pickup_at'])) {
            $today = new \DateTimeImmutable('now', new \DateTimeZone('Europe/Rome'));
            $pickupRaw = $today->format('Y-m-d') . 'T' . $form['pickup_time'];
            $pickupValidation = branch_validate_pickup_datetime($pdo, $selectedBranchId, $pickupRaw, null, true);
        } else {
            $pickupValidation = ['ok' => false, 'message' => (string) $errori['pickup_at']];
        }

        if (!$pickupValidation['ok']) {
            $errori['pickup_at'] = (string) $pickupValidation['message'];
        } else {
            $pickupRaw = (string) $pickupValidation['pickup_raw'];
            $form['pickup_time'] = substr($pickupRaw, 11, 5);
        }
    }

    if (empty($errori)) {
        $_SESSION['checkout_flow'] = [
            'started_at' => (int) ($checkoutFlow['started_at'] ?? time()),
            'branch_id' => $selectedBranchId,
            'fulfillment_type' => $fulfillmentType,
            'pickup_at' => $pickupRaw,
        ];
        header('Location: checkout_pagamento.php');
        exit;
    }
}

$flash = flash_get();

$pageTitle = 'Checkout - Metodo di ritiro';
$pageDescription = 'Scegli come ritirare il tuo ordine.';
$currentPage = 'checkout.php';
$breadcrumb = [['Home', 'index.php'], ['Carrello', 'carrello.php'], ['Checkout', 'checkout.php'], ['Metodo ritiro', null]];
include_once __DIR__ . '/views/template/header.php';
include_once __DIR__ . '/views/checkout_ritiro.php';
include_once __DIR__ . '/views/template/footer.php';
