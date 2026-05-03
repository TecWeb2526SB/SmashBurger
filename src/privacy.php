<?php
require_once __DIR__ . '/includes/resources.php';

render_page('info/privacy.php', [
    'pageTitle' => 'Privacy Policy - Smash Burger Original',
    'currentPage' => 'privacy',
    'breadcrumb' => [['Home', './'], ['Privacy', null]]
]);
