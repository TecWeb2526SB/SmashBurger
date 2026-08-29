<?php
/**
 * Controller del carrello.
 *
 * Raccoglie le azioni inviate in POST dal carrello stesso e dalla pagina del catalogo.
 * Dopo ogni azione risponde con un redirect, così un aggiornamento della pagina non
 * ripete l'operazione.
 */

require_once __DIR__ . '/includes/risorse.php';

utente_richiedi_accesso();

$utenteId = (int) utente_corrente()['id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // La pagina di ritorno arriva dal modulo: ammessi solo i due valori previsti.
    $ritorno = ((string) ($_POST['ritorno'] ?? 'carrello')) === 'prodotti' ? 'prodotti' : 'carrello';

    if (!csrf_valido($_POST['token_csrf'] ?? null)) {
        messaggio_imposta('errore', 'La pagina è rimasta aperta troppo a lungo, riprova.');
        vai_a($ritorno);
    }

    // Ogni pulsante di riga porta l'identificativo come valore: il browser invia solo
    // quello premuto.
    foreach (['aumenta' => 1, 'diminuisci' => -1] as $pulsante => $variazione) {
        if (isset($_POST[$pulsante])) {
            $esito = carrello_varia_quantita($pdo, $utenteId, (int) $_POST[$pulsante], $variazione);
            messaggio_imposta($esito['ok'] ? 'successo' : 'errore', $esito['messaggio']);
            vai_a($ritorno);
        }
    }

    if (isset($_POST['togli'])) {
        $esito = carrello_rimuovi($pdo, $utenteId, (int) $_POST['togli']);
        messaggio_imposta($esito['ok'] ? 'successo' : 'errore', $esito['messaggio']);
        vai_a($ritorno);
    }

    $azione = (string) ($_POST['azione'] ?? '');

    switch ($azione) {
        case 'aggiungi':
            $esito = carrello_aggiungi(
                $pdo,
                $utenteId,
                (int) ($_POST['prodotto_id'] ?? 0),
                (int) ($_POST['quantita'] ?? 1)
            );
            break;

        case 'aggiorna':
            $esito = carrello_aggiorna_righe($pdo, $utenteId, (array) ($_POST['quantita'] ?? []));
            break;

        case 'svuota':
            carrello_svuota($pdo, $utenteId);
            $esito = ['ok' => true, 'messaggio' => 'Carrello svuotato.'];
            break;

        default:
            $esito = ['ok' => false, 'messaggio' => 'Azione non riconosciuta.'];
    }

    messaggio_imposta($esito['ok'] ? 'successo' : 'errore', $esito['messaggio']);
    vai_a($ritorno);
}

$riepilogo = carrello_riepilogo($pdo, $utenteId);

mostra_pagina('ordine/carrello.php', [
    'titolo' => 'Carrello - Smash Burger',
    'pagina' => 'Carrello',
    'breadcrumb' => [['Home', url()], ['Carrello', null]],
    'righe' => $riepilogo['righe'],
    'articoli' => $riepilogo['articoli'],
    'totale' => $riepilogo['totale_centesimi'],
    'confermaSvuota' => isset($_GET['svuota']) && $riepilogo['righe'] !== [],
]);
