<?php
/**
 * Scheda di una sede: dati del locale, orari settimanali e sala eventi.
 *
 * Un manager puo' aprire solo la propria sede, e ci arriva anche senza indicarla.
 */

require_once __DIR__ . '/includes/risorse.php';

richiedi_permesso($pdo);

$limite = sede_limite($pdo);
$richiesta = identificativo($_GET, 'sede') ?? identificativo($_POST, 'sede_id');

// Un manager lavora solo sulla propria sede. Una richiesta che ne indica un'altra viene
// rifiutata, non dirottata in silenzio sulla sede giusta: chi la manda deve saperlo.
if ($limite !== null && $richiesta !== null && $richiesta !== $limite) {
    errore(403);
}

$sedeId = $limite ?? $richiesta;
$sede = $sedeId === null ? null : sede_per_id($pdo, $sedeId);

if ($sede === null) {
    errore(404);
}

$sedeId = (int) $sede['id'];
$errori = [];

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
    richiedi_post_valido();

    $azione = is_string($_POST['azione'] ?? null) ? $_POST['azione'] : '';

    if ($azione === 'dati') {
        $errori = sede_errori($_POST);
        $esito = $errori === []
            ? sede_salva($pdo, $sedeId, $_POST, $limite)
            : ['ok' => false, 'messaggio' => 'Controlla i campi segnalati.'];
    } elseif ($azione === 'orari') {
        $esito = orari_salva($pdo, $sedeId, $_POST, $limite);
    } elseif ($azione === 'sala') {
        $esito = sala_cambia($pdo, $sedeId, isset($_POST['apri']), $limite);
    } else {
        errore(403);
    }

    if ($errori === []) {
        messaggio_imposta($esito['ok'] ? 'successo' : 'errore', $esito['messaggio']);
        vai_a('controllo-sede', $limite === null ? ['sede' => $sedeId] : []);
    }

    $sede = sede_per_id($pdo, $sedeId);
}

mostra_pagina('controllo/sede.php', [
    'breadcrumb' => $limite === null
        ? [
            ['Home', url()],
            ['Controllo', url('controllo')],
            ['Sedi', url('controllo-sedi'), 'del pannello'],
            [$sede['citta'], null],
        ]
        : [
            ['Home', url()],
            ['Controllo', url('controllo')],
            ['La tua sede', null],
        ],
    'sede' => $sede,
    'orari' => orari_sede($pdo, $sedeId),
    'giorni' => giorni_settimana(),
    'errori' => $errori,
    'valori' => $errori === [] ? $sede : ($_POST + $sede),
]);
