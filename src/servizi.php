<?php
require_once __DIR__ . '/includes/resources.php';

$selectedBranch = branch_get_selected($pdo);
$brandContacts = brand_contact_get($pdo);
$allBranches = branches_get_all($pdo);

$pageTitle       = 'Servizi - Smash Burger Original';
$pageDescription = 'Scopri i servizi Smash Burger: ordine online, asporto, ritiro in sede e supporto clienti.';
$currentPage     = 'servizi.php';
$breadcrumb      = [['Home', 'index.php'], ['Servizi', null]];
include_once __DIR__ . '/views/template/header.php';
include_once __DIR__ . '/views/servizi.php';
include_once __DIR__ . '/views/template/footer.php';
