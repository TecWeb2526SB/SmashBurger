<?php
/**
 * Controller del modulo di una sede: anagrafica e orari settimanali.
 *
 * Senza parametro id il modulo crea una sede nuova, con id valido la modifica. Gli orari
 * si possono impostare solo su una sede già registrata.
 */

require_once __DIR__ . '/includes/risorse.php';

utente_richiedi_amministratore();

$sede = null;
$idRichiesto = (int) ($_GET['id'] ?? 0);

if ($idRichiesto > 0) {
    $sede = sede_per_id($pdo, $idRichiesto);

    if ($sede === null) {
        messaggio_imposta('errore', 'Sede non trovata.');
        vai_a('controllo-sedi');
    }
}

$campi = ['nome', 'citta', 'provincia', 'indirizzo', 'cap', 'telefono', 'email', 'note_ritiro'];
$valori = ['attiva' => $sede['attiva'] ?? 1, 'ordine' => $sede['ordine'] ?? 0];

foreach ($campi as $campo) {
    $valori[$campo] = $sede[$campo] ?? '';
}

$errori = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_valido($_POST['token_csrf'] ?? null)) {
        messaggio_imposta('errore', 'La pagina è rimasta aperta troppo a lungo, riprova.');
        vai_a('controllo-sedi');
    }

    if ((string) ($_POST['azione'] ?? '') === 'orari' && $sede !== null) {
        $esito = sede_orari_aggiorna($pdo, (int) $sede['id'], (array) ($_POST['giorni'] ?? []));
        messaggio_imposta($esito['ok'] ? 'successo' : 'errore', $esito['messaggio']);
        vai_a('controllo-sede', ['id' => (int) $sede['id']]);
    }

    foreach ($campi as $campo) {
        $valori[$campo] = trim((string) ($_POST[$campo] ?? ''));
    }
    $valori['attiva'] = isset($_POST['attiva']) ? 1 : 0;
    $valori['ordine'] = (int) ($_POST['ordine'] ?? 0);

    $esito = $sede === null
        ? sede_crea($pdo, $valori)
        : sede_aggiorna($pdo, (int) $sede['id'], $valori);

    if ($esito['ok']) {
        messaggio_imposta('successo', $sede === null ? 'Sede creata.' : 'Sede aggiornata.');
        vai_a('controllo-sedi');
    }

    $errori = $esito['errori'];
}

mostra_pagina('controllo/sede.php', [
    'titolo' => ($sede === null ? 'Nuova sede' : 'Modifica sede') . ' - Pannello',
    'pagina' => 'Controllo',
    'breadcrumb' => [
        ['Home', url()],
        ['Controllo', url('controllo')],
        ['Sedi', url('controllo-sedi')],
        [$sede === null ? 'Nuova sede' : 'Modifica', null],
    ],
    'sezione' => 'Sedi',
    'sede' => $sede,
    'valori' => $valori,
    'errori' => $errori,
    'orari' => $sede === null ? [] : sede_orari($pdo, (int) $sede['id']),
    'giorni' => [1 => 'Lunedì', 'Martedì', 'Mercoledì', 'Giovedì', 'Venerdì', 'Sabato', 'Domenica'],
]);
