<?php
/**
 * Profilo: dati personali, password, indirizzo di consegna, metodo di pagamento e
 * cancellazione dell'account.
 *
 * Ogni riquadro e' un modulo a se': salva per conto suo e torna qui con un avviso, cosi'
 * un errore in una sezione non fa perdere quello che si stava scrivendo nelle altre.
 */

require_once __DIR__ . '/includes/risorse.php';

richiedi_permesso($pdo);

$utente = utente_corrente($pdo);
$id = (int) $utente['id'];
$errori = [];
$sezione = '';
$confermaCancellazione = isset($_GET['cancella']);

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
    } elseif ($azione === 'consegna') {
        $esito = utente_salva_consegna($pdo, $id, $_POST);
        $errori = $esito['errori'];
        $sezione = 'consegna';

        if ($esito['ok']) {
            messaggio_imposta('successo', 'Indirizzo di consegna salvato.');
            vai_a('profilo');
        }
    } elseif ($azione === 'rimuovi-consegna') {
        utente_rimuovi_consegna($pdo, $id);
        messaggio_imposta('successo', 'Indirizzo di consegna rimosso.');
        vai_a('profilo');
    } elseif ($azione === 'pagamento') {
        $metodo = is_string($_POST['metodo'] ?? null) ? $_POST['metodo'] : '';
        utente_salva_pagamento($pdo, $id, $metodo === '' ? null : $metodo);
        messaggio_imposta('successo', 'Metodo di pagamento preferito aggiornato.');
        vai_a('profilo');
    } elseif ($azione === 'cancella') {
        utente_elimina($pdo, $id);
        utente_esci();
        vai_a();
    } else {
        // Nessun modulo del sito invia altri valori: la richiesta e' stata costruita a mano.
        errore(403);
    }
}

mostra_pagina('account/profilo.php', [
    'breadcrumb' => [['Home', url()], ['Area personale', url('area-personale')], ['Profilo', null]],
    'utente' => utente_completo($pdo, $id),
    'errori' => $errori,
    'sezione' => $sezione,
    'confermaCancellazione' => $confermaCancellazione,
]);
