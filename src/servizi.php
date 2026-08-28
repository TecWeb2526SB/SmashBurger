<?php
/**
 * Controller della pagina dei servizi.
 */

require_once __DIR__ . '/includes/risorse.php';

mostra_pagina('pubbliche/servizi.php', [
    'titolo' => 'Servizi: ritiro, gruppi, allergeni - Smash Burger',
    'descrizione' => 'Come funziona l\'ordine con ritiro in sede, i metodi di pagamento e le informazioni sugli allergeni.',
    'pagina' => 'Servizi',
    'breadcrumb' => [['Home', url()], ['Servizi', null]],
]);
