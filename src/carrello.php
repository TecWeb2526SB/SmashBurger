<?php
require_once __DIR__ . '/includes/resources.php';
$pageTitle       = 'Carrello - Smash Burger Original';
$pageDescription = 'Visualizza e gestisci i prodotti nel tuo carrello Smash Burger.';
$currentPage     = 'carrello.php';
include_once __DIR__ . '/views/template/header.php';
include_once __DIR__ . '/views/carrello.php';
include_once __DIR__ . '/views/template/footer.php';
