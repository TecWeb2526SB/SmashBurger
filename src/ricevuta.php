<?php
require_once __DIR__ . '/includes/resources.php';

require_login();

$sessionUser = current_user();
$utente = auth_get_user_by_id($pdo, (int) ($sessionUser['id'] ?? 0));

if ($utente === null) {
    logout_user();
    flash_set('error', 'Sessione utente non valida. Effettua di nuovo l\'accesso.');
    header('Location: login.php');
    exit;
}

login_user($utente, false);

$receiptType = trim((string) ($_GET['tipo'] ?? 'ordine'));
$receiptId = (int) ($_GET['id'] ?? 0);
$receipt = null;
$backHref = 'area_personale.php';
$backLabel = 'Torna all\'area personale';

if ($receiptId <= 0) {
    flash_set('error', 'Ricevuta richiesta non valida.');
    header('Location: area_personale.php');
    exit;
}

if ($receiptType === 'fornitura') {
    if (!can_access_admin_panel()) {
        flash_set('error', 'Non hai i permessi necessari per visualizzare questa ricevuta di fornitura.');
        header('Location: area_personale.php');
        exit;
    }

    $receipt = receipt_get_supply_order($pdo, $receiptId);
    if ($receipt === null) {
        flash_set('error', 'Ricevuta fornitura non trovata.');
        header('Location: admin_ricevute.php');
        exit;
    }

    if ((string) ($receipt['status'] ?? '') !== 'received') {
        flash_set('error', 'La ricevuta di fornitura è disponibile solo dopo la conferma di ricezione.');
        header('Location: admin_forniture.php');
        exit;
    }

    if (is_branch_manager() && !can_modify_managed_branch((int) $receipt['branch_id'])) {
        flash_set('error', 'Non puoi consultare ricevute di altre filiali.');
        header('Location: admin_ricevute.php');
        exit;
    }

    $backHref = 'admin_ricevute.php';
    $backLabel = 'Torna al pannello controllo';
} else {
    $receiptType = 'ordine';

    if (!can_place_customer_orders()) {
        flash_set('error', 'Le ricevute ordine cliente non sono disponibili per i profili interni.');
        header('Location: area_personale.php');
        exit;
    }

    $receipt = receipt_get_customer_order($pdo, $receiptId);
    if ($receipt === null) {
        flash_set('error', 'Ricevuta ordine non trovata.');
        header('Location: area_personale.php');
        exit;
    }

    $isOwner = (int) $receipt['user_id'] === (int) $utente['id'];
    $canViewBranchOrder = false;

    if (!$isOwner) {
        flash_set('error', 'Non puoi consultare la ricevuta richiesta.');
        header('Location: area_personale.php');
        exit;
    }
}

$pageTitle = 'Ricevuta - Smash Burger Original';
$pageDescription = 'Ricevuta stampabile ordine cliente o ricezione fornitura.';
$currentPage = $receiptType === 'fornitura' ? 'admin.php' : 'area_personale.php';
$breadcrumb = [
    ['Home', 'index.php'],
    [$receiptType === 'fornitura' || (!empty($canViewBranchOrder) && !$isOwner) ? 'Controllo' : 'Area personale', $backHref],
    ['Ricevuta', null],
];

include_once __DIR__ . '/views/template/header.php';
include_once __DIR__ . '/views/ricevuta.php';
include_once __DIR__ . '/views/template/footer.php';
