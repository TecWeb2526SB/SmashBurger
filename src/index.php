<?php
/**
 * index.php: Entry point principale (Controller) per la homepage di SmashBurger.
 * Carica le risorse globali, le view e i template necessari.
 */

// 1. Includi il file delle risorse centrale
require_once __DIR__ . '/includes/resources.php';

// 2. Definizione variabili specifiche per la pagina
$pageTitle = 'Home - Smash Burger Original | Il vero gusto dello smash';
$pageDescription = 'Scopri l\'autentico Smash Burger: carne croccante fuori e succosa dentro. Ordina a domicilio o ritira in sede.';
$isHomepage = true;       // Usa <h1> per il brand solo nella home
$currentPage = 'index.php'; // Indica la voce attiva nel menu principale

// 3. Caricamento della struttura della pagina (Template)
include_once __DIR__ . '/views/template/header.php';

// 4. Caricamento del contenuto specifico (View)
include_once __DIR__ . '/views/homepage.php';

// 5. Caricamento del footer comune
include_once __DIR__ . '/views/template/footer.php';