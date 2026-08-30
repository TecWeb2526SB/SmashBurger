<?php
/**
 * Area personale: ordini, prenotazioni e riepilogo dei propri dati.
 *
 * E' la pagina su cui atterra il redirect dopo ogni operazione conclusa.
 */

require_once __DIR__ . '/includes/risorse.php';

richiedi_permesso($pdo);

$utente = utente_corrente($pdo);

mostra_pagina('account/area-personale.php', [
    'breadcrumb' => [['Home', url()], ['Area personale', null]],
    'utente' => $utente,
    'ordini' => ordini_dell_utente($pdo, (int) $utente['id']),
    'prenotazioni' => prenotazioni_dell_utente($pdo, (int) $utente['id']),
]);
