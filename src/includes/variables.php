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
    'Home'              => app_route('./'),
    'I nostri prodotti' => app_route('prodotti'),
    'Servizi'           => app_route('servizi'),
    'Chi siamo'         => app_route('chi-siamo'),
];

if ($isLoggedIn) {
    $navItems['Area personale'] = app_route('account');
    if ($canAccessAdminPanel) {
        $navItems['Controllo'] = app_route('controllo');
    }
    if ($canPlaceOrders) {
        $navItems['Carrello'] = app_route('carrello');
    }
    $navItems['Esci'] = app_route('esci');
} else {
    $navItems['Accedi'] = app_route('accedi');
}

$siteMapItems = [
    'Home'                   => app_route('./'),
    'I nostri prodotti'      => app_route('prodotti'),
    'Servizi'                => app_route('servizi'),
    'Chi siamo'              => app_route('chi-siamo'),
    'Accedi'                 => app_route('accedi'),
    'Registrazione'          => app_route('registrati'),
    'Privacy Policy'         => app_route('privacy'),
    'Accessibilità'          => app_route('accessibilita'),
    'Mappa del sito'         => app_route('mappa-sito'),
];

if ($isLoggedIn) {
    unset($siteMapItems['Accedi'], $siteMapItems['Registrazione']);
    $siteMapItems['Area personale'] = app_route('account');
    if ($canPlaceOrders) {
        $siteMapItems['Carrello'] = app_route('carrello');
        $siteMapItems['Checkout'] = app_route('checkout');
    }
    if ($canAccessAdminPanel) {
        $siteMapItems['Pannello controllo'] = app_route('controllo');
    }
    $siteMapItems['Profilo account'] = app_route('account-profilo');
    $siteMapItems['Esci'] = app_route('esci');
}
