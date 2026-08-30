<?php
/**
 * Elenco delle sedi con indirizzi e orari.
 *
 * Le citta' compaiono nel testo e non solo dentro una mappa: le ricerche a cui il sito
 * deve rispondere sono in larga parte locali.
 */

require_once __DIR__ . '/includes/risorse.php';

richiedi_permesso($pdo);

mostra_pagina('pubbliche/sedi.php', [
    'breadcrumb' => [['Home', url()], ['Sedi', null]],
    'sedi' => sedi_attive($pdo),
    'orari' => orari_di_tutte_le_sedi($pdo),
    'giorni' => giorni_settimana(),
]);
