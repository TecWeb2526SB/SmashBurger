<?php
require_once __DIR__ . '/includes/resources.php';
$brandContacts = brand_contact_get($pdo);
$policyUpdatedAt = '1 aprile 2026';

$pageTitle       = 'Privacy Policy - Smash Burger Original';
$pageDescription = 'Informativa sul trattamento dei dati personali per account, ordini, checkout e navigazione su Smash Burger Original.';
$currentPage     = 'privacy';
$breadcrumb      = [['Home', './'], ['Privacy Policy', null]];
include_once __DIR__ . '/views/template/header.php';
include_once __DIR__ . '/views/info/privacy.php';
include_once __DIR__ . '/views/template/footer.php';
