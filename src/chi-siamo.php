<?php
/**
 * chi-siamo.php: Controller della pagina "Chi siamo" di SmashBurger.
 * Mostra la storia, i valori e la filosofia dell'azienda.
 */

require_once __DIR__ . '/includes/resources.php';

$allBranches = branches_get_all($pdo);
$selectedBranch = branch_get_selected($pdo);

$pageTitle       = 'Chi siamo - Smash Burger Original | La nostra storia';
$pageDescription = 'Scopri la storia di Smash Burger: dal 2018 portiamo lo smash burger americano in Italia con ingredienti 100% italiani.';
$currentPage     = 'chi-siamo';
$breadcrumb      = [['Home', './'], ['Chi siamo', null]];

include_once __DIR__ . '/views/template/header.php';
include_once __DIR__ . '/views/public/chi-siamo.php';
include_once __DIR__ . '/views/template/footer.php';
