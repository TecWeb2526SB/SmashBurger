<?php
require_once __DIR__ . '/includes/resources.php';
$brandContacts = brand_contact_get($pdo);
$accessibilityUpdatedAt = '1 aprile 2026';

$pageTitle       = 'Accessibilità - Smash Burger Original';
$pageDescription = 'Dichiarazione di accessibilità, stato di conformità e canali di feedback di Smash Burger Original.';
$currentPage     = 'accessibilita.php';
$breadcrumb      = [['Home', 'index.php'], ['Accessibilità', null]];
include_once __DIR__ . '/views/template/header.php';
include_once __DIR__ . '/views/accessibilita.php';
include_once __DIR__ . '/views/template/footer.php';
