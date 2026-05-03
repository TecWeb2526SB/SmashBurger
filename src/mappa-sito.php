<?php
require_once __DIR__ . '/includes/resources.php';

render_page('info/mappa-sito.php', [
    'pageTitle' => 'Mappa del sito - Smash Burger Original',
    'currentPage' => 'mappa-sito',
    'breadcrumb' => [['Home', './'], ['Mappa del sito', null]]
]);
