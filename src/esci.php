<?php
/**
 * Uscita dalla sessione.
 *
 * Avviene solo in POST: un'uscita raggiungibile con un collegamento potrebbe essere
 * innescata da un'immagine su un altro sito.
 */

require_once __DIR__ . '/includes/risorse.php';

richiedi_permesso($pdo);

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
    richiedi_post_valido();
    utente_esci();
    vai_a();
}

mostra_pagina('account/esci.php', [
    'breadcrumb' => [['Home', url()], ['Esci', null]],
]);
