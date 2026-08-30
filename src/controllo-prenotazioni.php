<?php
/**
 * Prenotazioni della sala eventi da approvare o rifiutare.
 */

require_once __DIR__ . '/includes/risorse.php';

richiedi_permesso($pdo);

$sedeId = sede_limite($pdo);

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
    richiedi_post_valido();

    $prenotazioneId = null;
    $stato = '';

    foreach (['approvata', 'rifiutata', 'annullata'] as $possibile) {
        if (isset($_POST[$possibile])) {
            $stato = $possibile;
            $prenotazioneId = identificativo($_POST, $possibile);
            break;
        }
    }

    if ($prenotazioneId === null) {
        errore(403);
    }

    $esito = prenotazione_cambia_stato($pdo, $prenotazioneId, $stato, $sedeId);
    messaggio_imposta($esito['ok'] ? 'successo' : 'errore', $esito['messaggio']);
    vai_a('controllo-prenotazioni');
}

mostra_pagina('controllo/prenotazioni.php', [
    'breadcrumb' => [['Home', url()], ['Ordini', url('controllo')], ['Prenotazioni', null]],
    'prenotazioni' => prenotazioni_da_gestire($pdo, $sedeId),
]);
