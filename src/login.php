<?php
require_once __DIR__ . '/includes/resources.php';

/**
 * login.php — Controller pagina di accesso.
 * Gestisce sia GET (mostra form) sia POST (valida credenziali).
 */

$errori      = [];
$valoreEmail = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = trim($_POST['email']    ?? '');
    $password = trim($_POST['password'] ?? '');

    if ($email === '') {
        $errori['email'] = 'Inserisci la tua e-mail.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errori['email'] = 'Formato e-mail non valido.';
    }

    if ($password === '') {
        $errori['password'] = 'Inserisci la tua password.';
    }

    $valoreEmail = htmlspecialchars($email, ENT_QUOTES, 'UTF-8');

    if (empty($errori)) {
        /* TODO: verificare le credenziali nel database */
        /* Esempio di redirect dopo login riuscito: */
        /* header('Location: area_personale.php'); exit; */
    }
}

$pageTitle       = 'Accedi al tuo account - Smash Burger Original';
$pageDescription = 'Accedi al tuo account Smash Burger per gestire i tuoi ordini e le tue preferenze.';
$currentPage     = 'login.php';
$breadcrumb      = [['Home', 'index.php'], ['Accedi', null]];

include_once __DIR__ . '/views/template/header.php';
include_once __DIR__ . '/views/login.php';
include_once __DIR__ . '/views/template/footer.php';