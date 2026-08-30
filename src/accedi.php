<?php
/**
 * Modulo di accesso.
 *
 * Chi e' gia' collegato viene mandato in area personale: la pagina non ha senso per lui.
 */

require_once __DIR__ . '/includes/risorse.php';

richiedi_permesso($pdo);

if (utente_corrente($pdo) !== null) {
    vai_a('area-personale');
}

$nomeUtente = '';
$errore = '';

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
    richiedi_post_valido();

    $nomeUtente = is_string($_POST['nome_utente'] ?? null) ? trim($_POST['nome_utente']) : '';
    $password = is_string($_POST['password'] ?? null) ? $_POST['password'] : '';

    $esito = utente_accedi($pdo, $nomeUtente, $password);

    if ($esito['ok']) {
        messaggio_imposta('successo', $esito['messaggio']);
        vai_a('area-personale');
    }

    $errore = $esito['messaggio'];
}

mostra_pagina('account/accedi.php', [
    'breadcrumb' => [['Home', url()], ['Accedi', null]],
    'nomeUtente' => $nomeUtente,
    'errore' => $errore,
]);
