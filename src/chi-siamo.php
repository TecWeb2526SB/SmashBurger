<?php
/**
 * Controller della pagina di presentazione.
 */

require_once __DIR__ . '/includes/risorse.php';

mostra_pagina('pubbliche/chi-siamo.php', [
    'titolo' => 'Chi siamo - Smash Burger',
    'descrizione' => 'Come prepariamo gli hamburger smash, che cosa usiamo e come sono organizzate le quattro sedi.',
    'pagina' => 'Chi siamo',
    'breadcrumb' => [['Home', url()], ['Chi siamo', null]],
    'sedi' => sedi_tutte($pdo),
]);
