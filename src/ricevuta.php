<?php
/**
 * Ricevuta di un ordine, aperta dallo storico dell'area personale.
 *
 * L'ordine si cerca dentro quelli di chi lo chiede: un identificativo che arriva da
 * fuori non basta mai da solo ad aprire i dati di qualcun altro.
 */

require_once __DIR__ . '/includes/risorse.php';

richiedi_permesso($pdo);

$utente = utente_corrente($pdo);
$ordineId = identificativo($_GET, 'ordine');
$ordine = $ordineId === null ? null : ordine_dell_utente($pdo, $ordineId, (int) $utente['id']);

if ($ordine === null) {
    errore(404);
}

mostra_pagina('ordine/ricevuta.php', [
    'breadcrumb' => [
        ['Home', url()],
        ['Area personale', url('area-personale')],
        ['Ricevuta ' . $ordine['numero_ordine'], null],
    ],
    'ordine' => $ordine,
    'righe' => righe_dell_ordine($pdo, (int) $ordine['id']),
    'utente' => $utente,
]);
