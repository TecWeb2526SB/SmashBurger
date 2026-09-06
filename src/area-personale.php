<?php
/**
 * Area personale: ordini, prenotazioni e riepilogo dei propri dati.
 *
 * E' la pagina su cui atterra il redirect dopo ogni operazione conclusa.
 *
 * Ordini e prenotazioni riguardano solo chi compra: manager e amministratore usano il
 * pannello di controllo, dove vedono quelli di tutta la sede. Per loro le due sezioni
 * non compaiono e le due query non vengono nemmeno eseguite.
 */

require_once __DIR__ . '/includes/risorse.php';

richiedi_permesso($pdo);

$utente = utente_corrente($pdo);
$cliente = $utente['ruolo'] === 'cliente';

mostra_pagina('account/area-personale.php', [
    'breadcrumb' => [['Home', url()], ['Area personale', null]],
    'utente' => $utente,
    'cliente' => $cliente,
    'ordini' => $cliente ? ordini_dell_utente($pdo, (int) $utente['id']) : [],
    'prenotazioni' => $cliente ? prenotazioni_dell_utente($pdo, (int) $utente['id']) : [],
]);
