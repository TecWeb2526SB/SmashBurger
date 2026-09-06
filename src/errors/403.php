<?php
/**
 * Pagina mostrata a chi ha fatto l'accesso ma non ha i permessi per la risorsa
 * richiesta, oppure a chi chiede direttamente una cartella di codice.
 */

require_once __DIR__ . '/../includes/risorse.php';

http_response_code(403);

mostra_pagina('errori/403.php', [
    'titolo' => 'Accesso non consentito - Smash Burger',
    'breadcrumb' => [['Home', url()], ['Accesso non consentito', null]],
]);
