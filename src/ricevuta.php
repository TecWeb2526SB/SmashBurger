<?php
/**
 * Controller della ricevuta di un ordine.
 *
 * L'ordine viene cercato fra quelli dell'utente in sessione: un numero appartenente a
 * un altro account non produce risultati.
 */

require_once __DIR__ . '/includes/risorse.php';

utente_richiedi_accesso();

$numero = trim((string) ($_GET['numero'] ?? ''));
$ordine = $numero === '' ? null : ordine_per_numero($pdo, $numero, (int) utente_corrente()['id']);

if ($ordine === null) {
    http_response_code(404);
    messaggio_imposta('errore', 'Ordine non trovato.');
    vai_a('area-personale');
}

mostra_pagina('ordine/ricevuta.php', [
    'titolo' => 'Ricevuta ordine - Smash Burger',
    'pagina' => 'Area personale',
    'breadcrumb' => [['Home', url()], ['Area personale', url('area-personale')], ['Ricevuta', null]],
    'ordine' => $ordine,
]);
