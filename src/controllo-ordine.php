<?php
/**
 * Dettaglio di un ordine nel pannello: righe, cliente, recapito e annullamento.
 *
 * L'annullamento chiede un motivo e rimette la merce a disposizione, quindi ha bisogno
 * di piu' spazio di una cella di tabella: sta qui e non nell'elenco.
 */

require_once __DIR__ . '/includes/risorse.php';

richiedi_permesso($pdo);

$sedeId = sede_limite($pdo);
$ordineId = identificativo($_GET, 'ordine') ?? identificativo($_POST, 'ordine');
$ordine = $ordineId === null ? null : ordine_per_pannello($pdo, $ordineId, $sedeId);

// Un ordine di un'altra sede non esiste, per chi guarda: la 404 non rivela che c'e'.
if ($ordine === null) {
    errore(404);
}

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
    richiedi_post_valido();

    $esito = ordine_annulla(
        $pdo,
        $ordineId,
        (string) ($_POST['motivo'] ?? ''),
        isset($_POST['rimborsa'])
    );

    messaggio_imposta($esito['ok'] ? 'successo' : 'errore', $esito['messaggio']);
    vai_a('controllo-ordine', ['ordine' => $ordineId]);
}

mostra_pagina('controllo/ordine.php', [
    'breadcrumb' => [
        ['Home', url()],
        ['Ordini', url('controllo')],
        [$ordine['numero_ordine'], null],
    ],
    'ordine' => $ordine,
    'righe' => righe_dell_ordine($pdo, $ordineId),
    'confermaAnnullamento' => isset($_GET['annulla']),
]);
