<?php
require_once __DIR__ . '/includes/resources.php';
$pageTitle       = 'Checkout - Smash Burger Original';
$pageDescription = 'Completa il tuo ordine Smash Burger in modo rapido e sicuro.';
$currentPage     = 'checkout.php';
include_once __DIR__ . '/views/template/header.php';
include_once __DIR__ . '/views/checkout.php';
include_once __DIR__ . '/views/template/footer.php';
