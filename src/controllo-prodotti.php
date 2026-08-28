<?php
/**
 * Controller dell'elenco prodotti nel pannello.
 */

require_once __DIR__ . '/includes/risorse.php';

utente_richiedi_amministratore();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_valido($_POST['token_csrf'] ?? null)) {
        messaggio_imposta('errore', 'Sessione scaduta, riprova a inviare il modulo.');
        vai_a('controllo-prodotti');
    }

    $esito = ((string) ($_POST['azione'] ?? '')) === 'cancella'
        ? catalogo_cancella($pdo, (int) ($_POST['prodotto_id'] ?? 0))
        : ['ok' => false, 'messaggio' => 'Azione non riconosciuta.'];

    messaggio_imposta($esito['ok'] ? 'successo' : 'errore', $esito['messaggio']);
    vai_a('controllo-prodotti');
}

mostra_pagina('controllo/prodotti.php', [
    'titolo' => 'Prodotti - Pannello di controllo',
    'pagina' => 'Controllo',
    'breadcrumb' => [['Home', url()], ['Controllo', url('controllo')], ['Prodotti', null]],
    'sezione' => 'Prodotti',
    'prodotti' => catalogo_prodotti($pdo),
]);
