<?php
require_once __DIR__ . '/includes/resources.php';
$pageTitle       = 'Mappa del sito - Smash Burger Original';
$pageDescription = 'Panoramica di tutte le pagine disponibili sul sito Smash Burger Original.';
$currentPage     = 'mappa-sito';
$breadcrumb      = [['Home', './'], ['Mappa del sito', null]];
include_once __DIR__ . '/views/template/header.php';
include_once __DIR__ . '/views/info/mappa-sito.php';
include_once __DIR__ . '/views/template/footer.php';
