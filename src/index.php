<?php
/**
 * index.php: Entry point principale (Controller) per la homepage di SmashBurger.
 * Carica le risorse globali, le view e i template necessari.
 */

// 1. Includi il file delle risorse centrale
require_once __DIR__ . '/includes/resources.php';

$selectedBranch = branch_get_selected($pdo);

// 2. Definizione variabili specifiche per la pagina
render_page('public/homepage.php', [
    'pageTitle' => 'Home - Smash Burger Original | Il vero gusto dello smash',
    'pageDescription' => 'Scopri l\'autentico Smash Burger: carne croccante fuori e succosa dentro. Ordina online e ritira in sede.',
    'isHomepage' => true,
    'currentPage' => './',
    'selectedBranch' => $selectedBranch
]);
