<?php
require_once __DIR__ . '/includes/resources.php';

/**
 * registrazione.php — Controller pagina di registrazione.
 * Gestisce sia GET (mostra form) sia POST (valida dati e crea account).
 */

$errori = [];
$valori = [
    'nome'  => '',
    'email' => '',
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome     = trim($_POST['nome']     ?? '');
    $email    = trim($_POST['email']    ?? '');
    $password = trim($_POST['password'] ?? '');
    $conferma = trim($_POST['conferma'] ?? '');

    if ($nome === '') {
        $errori['nome'] = 'Inserisci il tuo nome.';
    } elseif (mb_strlen($nome) < 2) {
        $errori['nome'] = 'Il nome deve contenere almeno 2 caratteri.';
    }

    if ($email === '') {
        $errori['email'] = 'Inserisci la tua e-mail.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errori['email'] = 'Formato e-mail non valido.';
    }

    if ($password === '') {
        $errori['password'] = 'Inserisci una password.';
    } elseif (mb_strlen($password) < 8) {
        $errori['password'] = 'La password deve contenere almeno 8 caratteri.';
    }

    if ($conferma === '') {
        $errori['conferma'] = 'Conferma la tua password.';
    } elseif ($conferma !== $password) {
        $errori['conferma'] = 'Le password non coincidono.';
    }

    $valori['nome']  = htmlspecialchars($nome,  ENT_QUOTES, 'UTF-8');
    $valori['email'] = htmlspecialchars($email, ENT_QUOTES, 'UTF-8');

    if (empty($errori)) {
        /* TODO: inserire utente nel database */
        /* Esempio di redirect dopo registrazione: */
        /* header('Location: area_personale.php'); exit; */
    }
}

$pageTitle       = 'Crea un account - Smash Burger Original';
$pageDescription = 'Registrati su Smash Burger per ordinare più velocemente e tenere traccia dei tuoi ordini.';
$currentPage     = 'registrazione.php';
$breadcrumb      = [['Home', 'index.php'], ['Crea un account', null]];

include_once __DIR__ . '/views/template/header.php';
include_once __DIR__ . '/views/registrazione.php';
include_once __DIR__ . '/views/template/footer.php';
