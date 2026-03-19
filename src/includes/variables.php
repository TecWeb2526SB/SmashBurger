<?php
/**
 * variables.php: Variabili globali del progetto.
 * $navItems è la fonte di verità per la navigazione principale.
 * Viene letto da header.php per generare il menu.
 */

$appName    = 'SmashBurger';
$appVersion = '1.0.0';

$navItems = [
    'Home'              => 'index.php',
    'I nostri prodotti' => 'prodotti.php',
    'Servizi'           => 'servizi.php',
    'Chi siamo'         => 'sedi.php',
    'Accedi'            => 'login.php',
];
