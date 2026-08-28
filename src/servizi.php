<?php
/**
 * Controller della pagina dei servizi.
 */

require_once __DIR__ . '/includes/risorse.php';

mostra_pagina('pubbliche/servizi.php', [
    'titolo' => 'Servizi: asporto, gruppi, eventi - Smash Burger',
    'descrizione' => 'Ordine con ritiro in sede, proposte per gruppi ed eventi, informazioni su allergeni e modalità di pagamento.',
    'pagina' => 'Servizi',
    'breadcrumb' => [['Home', url()], ['Servizi', null]],
]);
