<?php
/**
 * Controller dell'elenco delle sedi nel pannello.
 */

require_once __DIR__ . '/includes/risorse.php';

utente_richiedi_amministratore();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_valido($_POST['token_csrf'] ?? null)) {
        messaggio_imposta('errore', 'La pagina è rimasta aperta troppo a lungo, riprova.');
        vai_a('controllo-sedi');
    }

    $esito = ((string) ($_POST['azione'] ?? '')) === 'cancella'
        ? sede_cancella($pdo, (int) ($_POST['sede_id'] ?? 0))
        : ['ok' => false, 'messaggio' => 'Azione non riconosciuta.'];

    messaggio_imposta($esito['ok'] ? 'successo' : 'errore', $esito['messaggio']);
    vai_a('controllo-sedi');
}

// Il collegamento porta qui in GET e mostra la conferma; la cancellazione avviene in POST.
$daCancellare = isset($_GET['cancella']) ? sede_per_id($pdo, (int) $_GET['cancella']) : null;

$query = $pdo->query('SELECT id, slug, nome, citta, provincia, indirizzo, attiva FROM sedi ORDER BY ordine, citta');

mostra_pagina('controllo/sedi.php', [
    'titolo' => 'Sedi - Pannello di controllo',
    'pagina' => 'Controllo',
    'breadcrumb' => [['Home', url()], ['Controllo', url('controllo')], ['Sedi', null]],
    'sezione' => 'Sedi',
    'sedi' => $query->fetchAll(),
    'daCancellare' => $daCancellare,
]);
