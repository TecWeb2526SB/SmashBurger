<?php
require_once __DIR__ . '/includes/resources.php';

require_login();

$utente = auth_require_fresh_user($pdo);
$showCustomerOrders = can_place_customer_orders();
$orders = $showCustomerOrders ? orders_get_for_user($pdo, (int) $utente['id']) : [];
$flash = flash_get();
$canAccessAdminPanel = can_access_admin_panel();

render_page('account/dashboard.php', [
    'pageTitle' => 'Area personale - Smash Burger Original',
    'pageDescription' => 'Gestisci il tuo account, i tuoi ordini e le tue preferenze Smash Burger.',
    'currentPage' => 'account',
    'breadcrumb' => [['Home', './'], ['Area personale', null]],
    'utente' => $utente,
    'orders' => $orders,
    'flash' => $flash,
    'canAccessAdminPanel' => $canAccessAdminPanel,
    'showCustomerOrders' => $showCustomerOrders,
]);
