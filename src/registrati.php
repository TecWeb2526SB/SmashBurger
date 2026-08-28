<?php
/**
 * Controller della registrazione.
 *
 * I controlli sui dati sono gli stessi eseguiti dalle funzioni di dominio: la pagina si
 * limita a raccogliere gli errori e a ripresentare i valori inseriti.
 */

require_once __DIR__ . '/includes/risorse.php';

if (utente_autenticato()) {
    vai_a('area-personale');
}

$valori = ['nome_utente' => '', 'email' => ''];
$errori = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $valori['nome_utente'] = trim((string) ($_POST['nome_utente'] ?? ''));
    $valori['email'] = trim((string) ($_POST['email'] ?? ''));
    $password = (string) ($_POST['password'] ?? '');

    if (!csrf_valido($_POST['token_csrf'] ?? null)) {
        $errori['modulo'] = 'Sessione scaduta, riprova a inviare il modulo.';
    } else {
        $esito = utente_registra($pdo, $valori['nome_utente'], $valori['email'], $password);

        if ($esito['ok']) {
            messaggio_imposta('successo', 'Registrazione completata, ora puoi accedere.');
            vai_a('accedi');
        }

        $errori = $esito['errori'];
    }
}

mostra_pagina('account/registrati.php', [
    'titolo' => 'Registrati - Smash Burger',
    'pagina' => 'Registrati',
    'breadcrumb' => [['Home', url()], ['Registrati', null]],
    'valori' => $valori,
    'errori' => $errori,
]);
