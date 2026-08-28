<?php
/**
 * Controller dell'accesso.
 *
 * In POST verifica il token della sessione e le credenziali; in caso di successo manda
 * all'area personale, altrimenti ripropone il modulo con il messaggio di errore.
 */

require_once __DIR__ . '/includes/risorse.php';

if (utente_autenticato()) {
    vai_a('area-personale');
}

$nome = '';
$errore = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim((string) ($_POST['nome_utente'] ?? ''));
    $password = (string) ($_POST['password'] ?? '');

    if (!csrf_valido($_POST['token_csrf'] ?? null)) {
        $errore = 'La pagina è rimasta aperta troppo a lungo, riprova.';
    } else {
        $esito = utente_accedi($pdo, $nome, $password);

        if ($esito['ok']) {
            messaggio_imposta('successo', $esito['messaggio']);
            vai_a('area-personale');
        }

        $errore = $esito['messaggio'];
    }
}

mostra_pagina('account/accedi.php', [
    'titolo' => 'Accedi - Smash Burger',
    'pagina' => 'Accedi',
    'breadcrumb' => [['Home', url()], ['Accedi', null]],
    'nome' => $nome,
    'errore' => $errore,
]);
