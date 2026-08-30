<?php
/**
 * Prenotazione della sala eventi, avviata dalla pagina di una sede.
 *
 * Il percorso e' in due passi sulla stessa pagina: prima si scelgono sede e data, poi si
 * sceglie fra le fasce libere di quel giorno. Senza JavaScript il secondo passo arriva
 * con un invio in GET, che non modifica nulla.
 */

require_once __DIR__ . '/includes/risorse.php';

richiedi_permesso($pdo);

$utente = utente_corrente($pdo);
$sedi = sedi_attive($pdo);

$slugSede = slug_richiesto('sede') ?? '';
$sede = $slugSede === '' ? null : sede_per_slug($pdo, $slugSede);

if ($slugSede !== '' && $sede === null) {
    errore(404);
}

$data = is_string($_GET['data'] ?? null) && data_valida($_GET['data']) ? $_GET['data'] : '';
$valori = ['numero_persone' => '', 'note' => '', 'fascia' => ''];
$errori = [];

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
    richiedi_post_valido();

    $slugSede = slug_richiesto('sede', $_POST) ?? '';
    $sede = $slugSede === '' ? null : sede_per_slug($pdo, $slugSede);

    if ($sede === null) {
        errore(404);
    }

    $data = is_string($_POST['data'] ?? null) ? $_POST['data'] : '';

    foreach (array_keys($valori) as $campo) {
        $valori[$campo] = is_string($_POST[$campo] ?? null) ? $_POST[$campo] : '';
    }

    $dati = $valori + ['data' => $data];
    $errori = prenotazione_errori($pdo, $sede, $dati);

    if ($errori === []) {
        $fascia = fascia_scelta($pdo, (int) $sede['id'], $data, $valori['fascia']);
        $esito = prenotazione_crea($pdo, (int) $sede['id'], (int) $utente['id'], $dati, $fascia);

        messaggio_imposta($esito['ok'] ? 'successo' : 'errore', $esito['messaggio']);

        if ($esito['ok']) {
            vai_a('area-personale');
        }
    }
}

mostra_pagina('prenotazione/prenota.php', [
    'breadcrumb' => [
        ['Home', url()],
        ['Sedi', url('sedi')],
        ['Prenota la sala', null],
    ],
    'sedi' => $sedi,
    'sede' => $sede,
    'data' => $data,
    'fasce' => $sede === null || $data === '' ? [] : fasce_prenotabili($pdo, (int) $sede['id'], $data),
    'valori' => $valori,
    'errori' => $errori,
    'minimo' => date('Y-m-d'),
    'massimo' => date('Y-m-d', strtotime('+' . giorni_prenotabili() . ' day')),
]);
