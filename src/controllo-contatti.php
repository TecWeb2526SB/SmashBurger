<?php
/**
 * Messaggi arrivati dal modulo di contatto.
 */

require_once __DIR__ . '/includes/risorse.php';

richiedi_permesso($pdo);

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
    richiedi_post_valido();

    $messaggioId = null;
    $stato = '';

    foreach (stati_messaggio() as $possibile) {
        $campo = str_replace(' ', '-', $possibile);

        if (isset($_POST[$campo])) {
            $stato = $possibile;
            $messaggioId = identificativo($_POST, $campo);
            break;
        }
    }

    if ($messaggioId === null) {
        errore(403);
    }

    $esito = contatto_cambia_stato($pdo, $messaggioId, $stato);
    messaggio_imposta($esito['ok'] ? 'successo' : 'errore', $esito['messaggio']);
    vai_a('controllo-contatti');
}

mostra_pagina('controllo/contatti.php', [
    'breadcrumb' => [['Home', url()], ['Controllo', url('controllo')], ['Messaggi', null]],
    'messaggi' => contatti_elenco($pdo),
    'stati' => stati_messaggio(),
    'categorie' => categorie_messaggio(),
]);
