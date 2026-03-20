<?php
require_once __DIR__ . '/includes/resources.php';
$pageTitle       = 'Privacy Policy - Smash Burger Original';
$pageDescription = 'Informativa sulla privacy e sul trattamento dei dati personali di Smash Burger Original.';
$currentPage     = 'policy.php';
$breadcrumb      = [['Home', 'index.php'], ['Privacy Policy', null]];
include_once __DIR__ . '/views/template/header.php';
include_once __DIR__ . '/views/policy.php';
include_once __DIR__ . '/views/template/footer.php';
