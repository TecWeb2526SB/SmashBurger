<?php
require_once __DIR__ . '/includes/resources.php';

render_page('public/servizi.php', [
    'pageTitle' => 'I nostri servizi - Smash Burger Original',
    'currentPage' => 'servizi',
    'breadcrumb' => [['Home', './'], ['Servizi', null]]
]);
