<?php
/**
 * Controller dell'elenco delle sedi con gli orari settimanali.
 */

require_once __DIR__ . '/includes/risorse.php';

$sedi = sedi_tutte($pdo);
$orari = [];

foreach ($sedi as $sede) {
    $orari[(int) $sede['id']] = sede_orari($pdo, (int) $sede['id']);
}

mostra_pagina('pubbliche/sedi.php', [
    'titolo' => 'Sedi e orari - Smash Burger',
    'descrizione' => 'Indirizzi, orari di apertura e indicazioni per il ritiro nelle sedi di Padova, Treviso, Vicenza e Udine.',
    'pagina' => 'Sedi',
    'breadcrumb' => [['Home', url()], ['Sedi', null]],
    'sedi' => $sedi,
    'orari' => $orari,
    'giorni' => [1 => 'Lunedì', 'Martedì', 'Mercoledì', 'Giovedì', 'Venerdì', 'Sabato', 'Domenica'],
]);
