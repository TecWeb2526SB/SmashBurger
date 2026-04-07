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
    'Home'              => 'index.php',
    'I nostri prodotti' => 'prodotti.php',
    'Servizi'           => 'servizi.php',
    'Chi siamo'         => 'sedi.php',
];

if ($isLoggedIn) {
    $navItems['Area personale'] = 'area_personale.php';
    if ($canAccessAdminPanel) {
        $navItems['Controllo'] = 'admin.php';
    }
    if ($canPlaceOrders) {
        $navItems['Carrello'] = 'carrello.php';
    }
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
    $siteMapItems['Area personale'] = 'area_personale.php';
    if ($canPlaceOrders) {
        $siteMapItems['Carrello'] = 'carrello.php';
        $siteMapItems['Checkout'] = 'checkout.php';
    }
    if ($canAccessAdminPanel) {
        $siteMapItems['Pannello controllo'] = 'admin.php';
    }
    $siteMapItems['Profilo account'] = 'profilo.php';
    $siteMapItems['Esci'] = 'logout.php';
}
