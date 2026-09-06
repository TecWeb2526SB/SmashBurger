<?php
/**
 * Categorie del catalogo: creazione, rinomina e cancellazione.
 *
 * Le categorie non si creano scrivendo un nome nuovo nel modulo del prodotto: un refuso
 * produrrebbe un doppione invece di un errore.
 */

require_once __DIR__ . '/includes/risorse.php';

richiedi_permesso($pdo);

$daCancellare = null;

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
    richiedi_post_valido();

    $categoriaId = identificativo($_POST, 'categoria_id');

    if (isset($_POST['elimina'])) {
        $categoriaId = identificativo($_POST, 'elimina');
        $esito = $categoriaId === null
            ? ['ok' => false, 'messaggio' => 'Categoria non riconosciuta.']
            : categoria_elimina($pdo, $categoriaId);
    } else {
        $esito = categoria_salva($pdo, $_POST, $categoriaId);
    }

    messaggio_imposta($esito['ok'] ? 'successo' : 'errore', $esito['messaggio']);
    vai_a('controllo-categorie');
}

$idDaCancellare = identificativo($_GET, 'elimina');

if ($idDaCancellare !== null) {
    $daCancellare = categoria_per_id($pdo, $idDaCancellare);
}

mostra_pagina('controllo/categorie.php', [
    'breadcrumb' => [['Home', url()], ['Controllo', url('controllo')], ['Categorie', null]],
    'categorie' => categorie_tutte($pdo),
    'daCancellare' => $daCancellare,
]);
