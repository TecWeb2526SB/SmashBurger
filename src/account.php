<?php
require_once __DIR__ . '/includes/resources.php';

require_login();

$sessionUser = current_user();
$utente = auth_get_user_by_id($pdo, (int) ($sessionUser['id'] ?? 0));

if ($utente === null) {
    logout_user();
    flash_set('error', 'Sessione utente non valida. Effettua di nuovo l\'accesso.');
    header('Location: accedi');
    exit;
}

$_SESSION['user']['username'] = (string) $utente['username'];
$_SESSION['user']['email'] = (string) $utente['email'];
$_SESSION['user']['role'] = (string) $utente['role'];
$_SESSION['user']['managed_branch_id'] = isset($utente['managed_branch_id']) ? (int) $utente['managed_branch_id'] : null;
$_SESSION['user']['is_active'] = isset($utente['is_active']) ? (int) $utente['is_active'] : 1;
$showCustomerOrders = can_place_customer_orders();
$orders = $showCustomerOrders ? orders_get_for_user($pdo, (int) $utente['id']) : [];
$flash = flash_get();
$canAccessAdminPanel = can_access_admin_panel();

$pageTitle       = 'Area personale - Smash Burger Original';
$pageDescription = 'Gestisci il tuo account, i tuoi ordini e le tue preferenze Smash Burger.';
$currentPage     = 'account';
$breadcrumb      = [['Home', './'], ['Area personale', null]];
include_once __DIR__ . '/views/template/header.php';
include_once __DIR__ . '/views/account/dashboard.php';
include_once __DIR__ . '/views/template/footer.php';
