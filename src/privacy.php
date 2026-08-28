<?php
/**
 * Controller della privacy policy.
 */

require_once __DIR__ . '/includes/risorse.php';

mostra_pagina('informazioni/privacy.php', [
    'titolo' => 'Privacy policy - Smash Burger',
    'descrizione' => 'Quali dati raccogliamo, per quali finalità, per quanto tempo li conserviamo e come esercitare i propri diritti.',
    'pagina' => 'Privacy',
    'breadcrumb' => [['Home', url()], ['Privacy policy', null]],
]);
