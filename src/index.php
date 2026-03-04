<?php
/**
 * index.php: Entry point principale (Controller) per la homepage di SmashBurger.
 * Carica le risorse globali, le view e i template necessari.
 */

// 1. Includi il file delle risorse centrale
require_once __DIR__ . '/includes/resources.php';

// 2. Definizione variabili specifiche per la pagina (opzionale)
$pageTitle = 'SmashBurger - Homepage - I migliori Burger Smash';
$pageDescription = 'Scopri i segreti della cottura smash di SmashBurger: carne di qualità, ingredienti freschi e gusto inimitabile. Prenota il tuo tavolo!';

// 3. Caricamento della struttura della pagina (Template)
include_once __DIR__ . '/views/template/header.php';

// 4. Caricamento del contenuto specifico (View)
include_once __DIR__ . '/views/homepage.php';

// 5. Caricamento del footer comune
include_once __DIR__ . '/views/template/footer.php';