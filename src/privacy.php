<?php
require_once __DIR__ . '/includes/resources.php';

render_page('info/privacy.php', [
    'pageTitle' => 'Privacy Policy - Smash Burger Original',
    'pageDescription' => 'Informativa sul trattamento dei dati personali degli utenti Smash Burger Original.',
    'currentPage' => 'privacy',
    'breadcrumb' => [['Home', './'], ['Privacy', null]],
    'policyUpdatedAt' => '03/05/2026'
]);
