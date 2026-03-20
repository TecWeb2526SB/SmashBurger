<?php
require_once __DIR__ . '/includes/resources.php';
$pageTitle       = 'Area personale - Smash Burger Original';
$pageDescription = 'Gestisci il tuo account, i tuoi ordini e le tue preferenze Smash Burger.';
$currentPage     = 'area_personale.php';
$breadcrumb      = [['Home', 'index.php'], ['Area personale', null]];
include_once __DIR__ . '/views/template/header.php';
include_once __DIR__ . '/views/area_personale.php';
include_once __DIR__ . '/views/template/footer.php';
