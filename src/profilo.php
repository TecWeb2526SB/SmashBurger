<?php
/**
 * Profilo: dati personali e password.
 *
 * Ogni riquadro è un modulo a se': salva per conto suo e torna qui con un avviso, cosi'
 * un errore in una sezione non fa perdere quello che si stava scrivendo nelle altre.
 *
 * L'indirizzo di consegna si scrive al momento dell'ordine, dove serve, e la
 * cancellazione di un account passa dal pannello: qui non ci sono.
 */

require_once __DIR__ . '/includes/risorse.php';

richiedi_permesso($pdo);

$utente = utente_corrente($pdo);
$id = (int) $utente['id'];
$errori = [];
$sezione = '';

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
    richiedi_post_valido();

    $azione = is_string($_POST['azione'] ?? null) ? $_POST['azione'] : '';

    if ($azione === 'dati') {
        $esito = utente_aggiorna_dati($pdo, $id, $_POST);
        $errori = $esito['errori'];
        $sezione = 'dati';

        if ($esito['ok']) {
            messaggio_imposta('successo', 'Dati aggiornati.');
            vai_a('profilo');
        }
    } elseif ($azione === 'password') {
        $esito = utente_cambia_password(
            $pdo,
            $id,
            (string) ($_POST['attuale'] ?? ''),
            (string) ($_POST['nuova'] ?? ''),
            (string) ($_POST['conferma'] ?? '')
        );
        $errori = $esito['errori'];
        $sezione = 'password';

        if ($esito['ok']) {
            messaggio_imposta('successo', 'Password aggiornata.');
            vai_a('profilo');
        }
    } else {
        // Nessun modulo del sito invia altri valori: la richiesta è stata costruita a mano.
        errore(403);
    }
}

mostra_pagina('account/profilo.php', [
    'breadcrumb' => [['Home', url()], ['Area personale', url('area-personale')], ['Profilo', null]],
    'utente' => utente_completo($pdo, $id),
    'errori' => $errori,
    'sezione' => $sezione,
]);
