<?php
/**
 * variables.php: Variabili globali del progetto.
 * $navItems è la fonte di verità per la navigazione principale.
 * Viene letto da header.php per generare il menu.
 */

$appName    = 'SmashBurger';
$appVersion = '1.0.0';
$isLoggedIn = isset($_SESSION['user']) && isset($_SESSION['user']['id']);
$sessionRole = (string) ($_SESSION['user']['role'] ?? 'user');
$canAccessAdminPanel = $isLoggedIn && in_array($sessionRole, ['admin', 'branch_manager'], true);
$canPlaceOrders = !$isLoggedIn || $sessionRole === 'user';

$navItems = [
    'Home'              => './',
    'I nostri prodotti' => 'prodotti',
    'Servizi'           => 'servizi',
    'Chi siamo'         => 'chi-siamo',
];

if ($isLoggedIn) {
    $navItems['Area personale'] = 'account';
    if ($canAccessAdminPanel) {
        $navItems['Controllo'] = 'controllo';
    }
    if ($canPlaceOrders) {
        $navItems['Carrello'] = 'carrello';
    }
    $navItems['Esci'] = 'esci';
} else {
    $navItems['Accedi'] = 'accedi';
}

$siteMapItems = [
    'Home'                   => './',
    'I nostri prodotti'      => 'prodotti',
    'Servizi'                => 'servizi',
    'Chi siamo'              => 'chi-siamo',
    'Accedi'                 => 'accedi',
    'Registrazione'          => 'registrati',
    'Privacy Policy'         => 'privacy',
    'Accessibilita'          => 'accessibilita',
    'Mappa del sito'         => 'mappa-sito',
];

if ($isLoggedIn) {
    unset($siteMapItems['Accedi'], $siteMapItems['Registrazione']);
    $siteMapItems['Area personale'] = 'account';
    if ($canPlaceOrders) {
        $siteMapItems['Carrello'] = 'carrello';
        $siteMapItems['Checkout'] = 'checkout';
    }
    if ($canAccessAdminPanel) {
        $siteMapItems['Pannello controllo'] = 'controllo';
    }
    $siteMapItems['Profilo account'] = 'account-profilo';
    $siteMapItems['Esci'] = 'esci';
}
