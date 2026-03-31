<?php
/**
 * variables.php: Variabili globali del progetto.
 * $navItems è la fonte di verità per la navigazione principale.
 * Viene letto da header.php per generare il menu.
 */

$appName    = 'SmashBurger';
$appVersion = '1.0.0';
$isLoggedIn = isset($_SESSION['user']) && isset($_SESSION['user']['id']);

$navItems = [
    'Home'              => 'index.php',
    'I nostri prodotti' => 'prodotti.php',
    'Servizi'           => 'servizi.php',
    'Chi siamo'         => 'sedi.php',
];

if ($isLoggedIn) {
    $navItems['Area personale'] = 'area_personale.php';
    $navItems['Carrello'] = 'carrello.php';
    $navItems['Esci'] = 'logout.php';
} else {
    $navItems['Accedi'] = 'login.php';
}

$siteMapItems = [
    'Home'                   => 'index.php',
    'I nostri prodotti'      => 'prodotti.php',
    'Servizi'                => 'servizi.php',
    'Chi siamo e le sedi'    => 'sedi.php',
    'Accedi'                 => 'login.php',
    'Registrazione'          => 'registrazione.php',
    'Privacy Policy'         => 'policy.php',
    'Accessibilita'          => 'accessibilita.php',
    'Mappa del sito'         => 'mappa.php',
];

if ($isLoggedIn) {
    unset($siteMapItems['Accedi'], $siteMapItems['Registrazione']);
    $siteMapItems['Carrello'] = 'carrello.php';
    $siteMapItems['Checkout'] = 'checkout.php';
    $siteMapItems['Area personale'] = 'area_personale.php';
    $siteMapItems['Esci'] = 'logout.php';
}
