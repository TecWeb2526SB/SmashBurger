<?php
/**
 * Pagina mostrata a chi tenta di aprire una risorsa che richiede l'accesso.
 *
 * Si distingue dalla 403: qui il problema è che nessuno ha fatto l'accesso, non che
 * l'account collegato non abbia i permessi.
 */

require_once __DIR__ . '/../includes/risorse.php';

http_response_code(401);

mostra_pagina('errori/401.php', [
    'titolo' => 'Serve l accesso - Smash Burger',
    'breadcrumb' => [['Home', url()], ['Serve l accesso', null]],
]);
