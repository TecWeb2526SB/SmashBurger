<?php
/**
 * Controller della dichiarazione di accessibilità.
 */

require_once __DIR__ . '/includes/risorse.php';

mostra_pagina('informazioni/accessibilita.php', [
    'titolo' => 'Accessibilità - Smash Burger',
    'descrizione' => 'Stato di conformità del sito alle linee guida WCAG 2.1 AA e modalità per segnalare un problema.',
    'pagina' => 'Accessibilità',
    'breadcrumb' => [['Home', url()], ['Accessibilità', null]],
]);
