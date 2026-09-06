<?php
/**
 * Pagina pubblica di una singola sede, raggiunta con sede?slug=città.
 *
 * Risponde alle ricerche locali meglio dell'elenco, ed è il punto da cui si avvia la
 * prenotazione della sala eventi.
 */

require_once __DIR__ . '/includes/risorse.php';

richiedi_permesso($pdo);

$slug = slug_richiesto('slug');
$sede = $slug === null ? null : sede_per_slug($pdo, $slug);

if ($sede === null) {
    errore(404);
}

mostra_pagina('pubbliche/sede.php', [
    'titolo' => 'Smash Burger ' . $sede['citta'] . ': indirizzo e orari',
    'descrizione' => 'Smash Burger a ' . $sede['citta'] . ', ' . $sede['indirizzo']
        . '. Orari di apertura, ritiro degli ordini e sala eventi prenotabile.',
    'breadcrumb' => [['Home', url()], ['Sedi', url('sedi')], [$sede['citta'], null]],
    'sede' => $sede,
    'orari' => orari_sede($pdo, (int) $sede['id']),
    'giorni' => giorni_settimana(),
]);
