<?php
require_once __DIR__ . '/includes/resources.php';

render_page('info/accessibilita.php', [
    'pageTitle' => 'Dichiarazione di Accessibilità - Smash Burger Original',
    'pageDescription' => 'Dichiarazione di accessibilità del sito Smash Burger Original.',
    'currentPage' => 'accessibilita',
    'breadcrumb' => [['Home', './'], ['Accessibilità', null]],
    'accessibilityUpdatedAt' => '03/05/2026'
]);
