<?php
require_once __DIR__ . '/includes/resources.php';

require_login();

$utente = auth_require_fresh_user($pdo);

$receiptType = trim((string) ($_GET['tipo'] ?? 'ordine'));
$receiptId = (int) ($_GET['id'] ?? 0);
$receipt = null;
$backHref = 'account';
$backLabel = 'Torna all\'area personale';

if ($receiptId <= 0) {
    flash_set('error', 'Ricevuta richiesta non valida.');
    header('Location: ' . app_route('account'));
    exit;
}

if ($receiptType === 'fornitura') {
    if (!can_access_admin_panel()) {
        flash_set('error', 'Non hai i permessi necessari per visualizzare questa ricevuta di fornitura.');
        header('Location: ' . app_route('account'));
        exit;
    }

    $receipt = receipt_get_supply_order($pdo, $receiptId);
    if ($receipt === null) {
        flash_set('error', 'Ricevuta fornitura non trovata.');
        header('Location: ' . app_route('controllo-forniture'));
        exit;
    }

    if ((string) ($receipt['status'] ?? '') !== 'received') {
        flash_set('error', 'La ricevuta di fornitura è disponibile solo dopo la conferma di ricezione.');
        header('Location: ' . app_route('controllo-forniture'));
        exit;
    }

    if (is_branch_manager() && !can_modify_managed_branch((int) $receipt['branch_id'])) {
        flash_set('error', 'Non puoi consultare ricevute di altre filiali.');
        header('Location: ' . app_route('controllo-forniture'));
        exit;
    }

    $backHref = 'controllo-forniture';
    $backLabel = 'Torna al pannello controllo';
} else {
    $receiptType = 'ordine';

    if (!can_place_customer_orders()) {
        flash_set('error', 'Le ricevute ordine cliente non sono disponibili per i profili interni.');
        header('Location: ' . app_route('account'));
        exit;
    }

    $receipt = receipt_get_customer_order($pdo, $receiptId);
    if ($receipt === null) {
        flash_set('error', 'Ricevuta ordine non trovata.');
        header('Location: ' . app_route('account'));
        exit;
    }

    $isOwner = (int) $receipt['user_id'] === (int) $utente['id'];
    if (!$isOwner) {
        flash_set('error', 'Non puoi consultare la ricevuta richiesta.');
        header('Location: ' . app_route('account'));
        exit;
    }
}

$pageTitle = 'Ricevuta - Smash Burger Original';
$pageDescription = 'Ricevuta stampabile ordine cliente o ricezione fornitura.';
$currentPage = $receiptType === 'fornitura' ? 'controllo' : 'account';
$breadcrumb = [
    ['Home', './'],
    [$receiptType === 'fornitura' ? 'Controllo' : 'Area personale', $backHref],
    ['Ricevuta', null],
];

render_page('checkout/ricevuta.php', [
    'pageTitle' => $pageTitle,
    'pageDescription' => $pageDescription,
    'currentPage' => $currentPage,
    'breadcrumb' => $breadcrumb,
    'receiptType' => $receiptType,
    'receipt' => $receipt,
    'backHref' => $backHref,
    'backLabel' => $backLabel
]);
