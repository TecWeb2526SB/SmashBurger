<?php
require_once __DIR__ . '/includes/resources.php';
$pageTitle       = 'I nostri prodotti - Smash Burger Original';
$pageDescription = 'Scopri tutti i burger, i contorni e le bevande di Smash Burger Original.';
$currentPage     = 'prodotti.php';
$breadcrumb      = [['Home', 'index.php'], ['I nostri prodotti', null]];
include_once __DIR__ . '/views/template/header.php';
include_once __DIR__ . '/views/prodotti.php';
include_once __DIR__ . '/views/template/footer.php';
