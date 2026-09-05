<?php
/**
 * Account registrati: ruolo, attivazione e cancellazione.
 */

require_once __DIR__ . '/includes/risorse.php';

richiedi_permesso($pdo);

$utenteCorrente = (int) utente_corrente($pdo)['id'];
$daCancellare = null;

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
    richiedi_post_valido();

    $azione = is_string($_POST['azione'] ?? null) ? $_POST['azione'] : '';
    $utenteId = identificativo($_POST, 'utente_id');

    if ($utenteId === null) {
        errore(403);
    }

    if ($azione === 'ruolo') {
        $esito = utente_cambia_ruolo(
            $pdo,
            $utenteId,
            (string) ($_POST['ruolo'] ?? ''),
            identificativo($_POST, 'sede_id')
        );
    } elseif ($azione === 'attiva' || $azione === 'disattiva') {
        $esito = utente_cambia_stato($pdo, $utenteId, $azione === 'attiva');
    } elseif ($azione === 'cancella') {
        $esito = utente_cancella($pdo, $utenteId, $utenteCorrente);
    } else {
        errore(403);
    }

    messaggio_imposta($esito['ok'] ? 'successo' : 'errore', $esito['messaggio']);
    vai_a('controllo-utenti');
}

$idDaCancellare = identificativo($_GET, 'cancella');

if ($idDaCancellare !== null) {
    $daCancellare = utente_completo($pdo, $idDaCancellare);
}

mostra_pagina('controllo/utenti.php', [
    'breadcrumb' => [['Home', url()], ['Controllo', url('controllo')], ['Utenti', null]],
    'utenti' => utenti_elenco($pdo),
    'sedi' => sedi_tutte($pdo),
    'ruoli' => ruoli_assegnabili(),
    'utenteCorrente' => $utenteCorrente,
    'daCancellare' => $daCancellare,
]);
