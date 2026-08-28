<?php
/**
 * Controller dell'uscita.
 *
 * L'uscita cambia lo stato della sessione, quindi avviene solo in POST con token
 * verificato: la pagina raggiunta in GET mostra la richiesta di conferma.
 */

require_once __DIR__ . '/includes/risorse.php';

if (!utente_autenticato()) {
    vai_a();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && csrf_valido($_POST['token_csrf'] ?? null)) {
    utente_esci();
    messaggio_imposta('successo', 'Sei uscito dal tuo account.');
    vai_a();
}

mostra_pagina('account/esci.php', [
    'titolo' => 'Esci - Smash Burger',
    'pagina' => 'Esci',
    'breadcrumb' => [['Home', url()], ['Esci', null]],
]);
