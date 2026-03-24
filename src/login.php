<?php
require_once __DIR__ . '/includes/resources.php';

/**
 * login.php — Controller pagina di accesso.
 * Gestisce sia GET (mostra form) sia POST (valida credenziali).
 */

$errori          = [];
$valoreUsername  = '';
$csrfToken       = csrf_token();
$flash           = flash_get();

if (is_logged_in()) {
    header('Location: area_personale.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $csrfTokenForm = $_POST['csrf_token'] ?? null;
    $username = trim($_POST['username'] ?? '');
    $password = (string) ($_POST['password'] ?? '');

    if (!csrf_is_valid($csrfTokenForm)) {
        $errori['generale'] = 'Sessione scaduta o richiesta non valida. Ricarica la pagina e riprova.';
    }

    if ($username === '') {
        $errori['username'] = 'Inserisci il tuo username.';
    } elseif (!preg_match('/^[a-zA-Z0-9_.-]{3,50}$/', $username)) {
        $errori['username'] = 'Username non valido (3-50 caratteri: lettere, numeri, . _ -).';
    }

    if ($password === '') {
        $errori['password'] = 'Inserisci la tua password.';
    }

    $valoreUsername = htmlspecialchars($username, ENT_QUOTES, 'UTF-8');

    if (empty($errori)) {
        $stmt = $pdo->prepare(
            'SELECT id, username, password_hash, role
             FROM users
             WHERE username = :username
             LIMIT 1'
        );
        $stmt->execute(['username' => $username]);
        $utente = $stmt->fetch();

        if (!$utente || !password_verify($password, (string) $utente['password_hash'])) {
            $errori['generale'] = 'Credenziali non valide.';
        } else {
            login_user($utente);
            flash_set('success', 'Accesso effettuato con successo. Bentornato, ' . $utente['username'] . '!');
            header('Location: area_personale.php');
            exit;
        }
    }
}

$pageTitle       = 'Accedi al tuo account - Smash Burger Original';
$pageDescription = 'Accedi al tuo account Smash Burger per gestire i tuoi ordini e le tue preferenze.';
$currentPage     = 'login.php';
$breadcrumb      = [['Home', 'index.php'], ['Accedi', null]];

include_once __DIR__ . '/views/template/header.php';
include_once __DIR__ . '/views/login.php';
include_once __DIR__ . '/views/template/footer.php';
