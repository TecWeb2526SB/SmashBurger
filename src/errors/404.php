<?php
/**
 * Pagina mostrata quando l'indirizzo non esiste, oppure quando un identificativo
 * ricevuto da fuori non corrisponde a nessun dato.
 */

require_once __DIR__ . '/../includes/risorse.php';

http_response_code(404);

mostra_pagina('errori/404.php', [
    'titolo' => 'Pagina non trovata - Smash Burger',
    'breadcrumb' => [['Home', url()], ['Pagina non trovata', null]],
]);
