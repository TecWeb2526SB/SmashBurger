<?php
/**
 * Carrello e scelta dei prodotti.
 *
 * Senza una sede scelta la pagina chiede da dove si vuole ordinare. Con la sede scelta
 * mostra la griglia dei prodotti disponibili li' e, in fondo, il riepilogo di quanto e'
 * gia' stato aggiunto.
 */

require_once __DIR__ . '/includes/risorse.php';

richiedi_permesso($pdo);

$utente = utente_corrente($pdo);
$utenteId = (int) $utente['id'];

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
    richiedi_post_valido();

    $azione = is_string($_POST['azione'] ?? null) ? $_POST['azione'] : '';
    $carrello = carrello_corrente($pdo, $utenteId);

    if ($azione === 'scegli-sede') {
        $slug = slug_richiesto('sede', $_POST);
        $sede = $slug === null ? null : sede_per_slug($pdo, $slug);

        if ($sede === null) {
            messaggio_imposta('errore', 'Scegli una delle sedi disponibili.');
            vai_a('carrello');
        }

        carrello_apri($pdo, $utenteId, (int) $sede['id']);
        vai_a('carrello');
    }

    if ($carrello === null) {
        messaggio_imposta('errore', 'Il carrello e scaduto: scegli di nuovo la sede.');
        vai_a('carrello');
    }

    $carrelloId = (int) $carrello['id'];
    $sedeId = (int) $carrello['sede_id'];

    // I pulsanti che agiscono su una riga portano il nome dell'azione e, come valore,
    // l'identificativo del prodotto: il browser invia solo quello premuto.
    $prodottoId = $azione === 'aggiungi' ? identificativo($_POST, 'prodotto_id') : null;

    foreach (['aggiungi', 'diminuisci', 'togli'] as $nome) {
        if (isset($_POST[$nome])) {
            $azione = $nome;
            $prodottoId = identificativo($_POST, $nome);
            break;
        }
    }

    if ($azione === 'svuota') {
        $esito = carrello_svuota($pdo, $carrelloId);
    } elseif ($prodottoId === null) {
        errore(403);
    } elseif ($azione === 'aggiungi') {
        $esito = carrello_aggiungi($pdo, $carrelloId, $sedeId, $prodottoId);
    } elseif ($azione === 'togli') {
        $esito = carrello_togli($pdo, $carrelloId, $prodottoId);
    } elseif ($azione === 'diminuisci') {
        $esito = carrello_diminuisci($pdo, $carrelloId, $sedeId, $prodottoId);
    } else {
        errore(403);
    }

    messaggio_imposta($esito['ok'] ? 'successo' : 'errore', $esito['messaggio']);
    vai_a('carrello');
}

$carrello = carrello_corrente($pdo, $utenteId);

// La sede puo' arrivare anche da un collegamento della pagina di una sede.
if ($carrello === null && isset($_GET['sede'])) {
    $slug = slug_richiesto('sede');
    $sede = $slug === null ? null : sede_per_slug($pdo, $slug);

    if ($sede !== null) {
        carrello_apri($pdo, $utenteId, (int) $sede['id']);
        vai_a('carrello');
    }
}

$righe = $carrello === null ? [] : carrello_righe($pdo, (int) $carrello['id'], (int) $carrello['sede_id']);

mostra_pagina('ordine/carrello.php', [
    'breadcrumb' => [['Home', url()], ['Carrello', null]],
    'carrello' => $carrello,
    'sedi' => sedi_attive($pdo),
    'prodotti' => $carrello === null ? [] : prodotti_in_sede($pdo, (int) $carrello['sede_id']),
    'righe' => $righe,
    'totale' => carrello_totale($righe),
    'articoli' => carrello_articoli($righe),
]);
