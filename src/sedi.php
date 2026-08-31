<?php
/**
 * Elenco sintetico delle sedi. Gli orari si leggono nel dettaglio di ogni locale.
 *
 * Le citta' compaiono nel testo e non solo dentro una mappa: le ricerche a cui il sito
 * deve rispondere sono in larga parte locali.
 */

require_once __DIR__ . '/includes/risorse.php';

richiedi_permesso($pdo);

mostra_pagina('pubbliche/sedi.php', [
    'breadcrumb' => [['Home', url()], ['Sedi', null]],
    'sedi' => sedi_attive($pdo),
]);
