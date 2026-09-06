<?php
/**
 * Modulo di contatto pubblico.
 *
 * In caso di errore i valori inseriti restano nel modulo, cosi' non vanno riscritti.
 */

require_once __DIR__ . '/includes/risorse.php';

richiedi_permesso($pdo);

$valori = ['nome' => '', 'email' => '', 'categoria' => '', 'testo' => ''];
$errori = [];

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
    richiedi_post_valido();

    foreach (array_keys($valori) as $campo) {
        $valori[$campo] = is_string($_POST[$campo] ?? null) ? $_POST[$campo] : '';
    }

    $errori = contatto_errori($valori);

    if ($errori === []) {
        contatto_salva($pdo, $valori);
        messaggio_imposta('successo', 'Messaggio inviato. Ti rispondiamo via email.');
        vai_a('contatti');
    }
}

mostra_pagina('pubbliche/contatti.php', [
    'breadcrumb' => [['Home', url()], ['Contatti', null]],
    'valori' => $valori,
    'errori' => $errori,
    'categorie' => categorie_messaggio(),
]);
