<?php
/**
 * Controller dell'area personale: dati dell'account ed elenco degli ordini.
 */

require_once __DIR__ . '/includes/risorse.php';

utente_richiedi_accesso();

$utente = utente_corrente();

mostra_pagina('account/area-personale.php', [
    'titolo' => 'Area personale - Smash Burger',
    'pagina' => 'Area personale',
    'breadcrumb' => [['Home', url()], ['Area personale', null]],
    'utente' => $utente,
    'ordini' => ordini_utente($pdo, (int) $utente['id']),
]);
