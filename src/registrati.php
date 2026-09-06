<?php
/**
 * Registrazione di un nuovo account cliente.
 */

require_once __DIR__ . '/includes/risorse.php';

richiedi_permesso($pdo);

if (utente_corrente($pdo) !== null) {
    vai_a('area-personale');
}

$valori = ['nome' => '', 'cognome' => '', 'nome_utente' => '', 'email' => ''];
$errori = [];

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
    richiedi_post_valido();

    foreach (array_keys($valori) as $campo) {
        $valori[$campo] = is_string($_POST[$campo] ?? null) ? $_POST[$campo] : '';
    }

    $dati = $valori + [
        'password' => is_string($_POST['password'] ?? null) ? $_POST['password'] : '',
        'conferma' => is_string($_POST['conferma'] ?? null) ? $_POST['conferma'] : '',
    ];

    $errori = utente_errori_registrazione($pdo, $dati);

    if ($errori === []) {
        utente_registra($pdo, $dati);
        messaggio_imposta('successo', 'Account creato. Ora puoi accedere.');
        vai_a('accedi');
    }
}

mostra_pagina('account/registrati.php', [
    'breadcrumb' => [['Home', url()], ['Registrati', null]],
    'valori' => $valori,
    'errori' => $errori,
]);
