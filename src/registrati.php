<?php
require_once __DIR__ . '/includes/resources.php';

/**
 * registrati — Controller pagina di registrazione.
 * Gestisce sia GET (mostra form) sia POST (valida dati e crea account).
 */

$errori = [];
$valori = [
    'username' => '',
    'email' => '',
];
$csrfToken = csrf_token();

if (is_logged_in()) {
    header('Location: account');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $csrfTokenForm = $_POST['csrf_token'] ?? null;
    $username = trim((string) ($_POST['username'] ?? ''));
    $email = auth_normalize_email((string) ($_POST['email'] ?? ''));
    $password = (string) ($_POST['password'] ?? '');
    $conferma = (string) ($_POST['conferma'] ?? '');

    if (!csrf_is_valid($csrfTokenForm)) {
        $errori['generale'] = 'Sessione scaduta o richiesta non valida. Ricarica la pagina e riprova.';
    }

    if ($username === '') {
        $errori['username'] = 'Inserisci uno username.';
    } elseif (!auth_is_valid_username($username)) {
        $errori['username'] = 'Username non valido (3-50 caratteri: lettere, numeri, . _ -).';
    }

    if ($email === '') {
        $errori['email'] = 'Inserisci una email.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errori['email'] = 'Inserisci una email valida.';
    }

    if ($password === '') {
        $errori['password'] = 'Inserisci una password.';
    } elseif (mb_strlen($password) < 8) {
        $errori['password'] = 'La password deve contenere almeno 8 caratteri.';
    } elseif (!auth_is_valid_password($password)) {
        $errori['password'] = 'La password può contenere solo lettere, numeri, underscore (_) e questi simboli: ! @ # $ % &';
    }

    if ($conferma === '') {
        $errori['conferma'] = 'Conferma la tua password.';
    } elseif ($conferma !== $password) {
        $errori['conferma'] = 'Le password non coincidono.';
    }

    $valori['username'] = $username;
    $valori['email'] = $email;

    if (empty($errori)) {
        if (auth_username_exists($pdo, $username)) {
            $errori['username'] = 'Username già in uso. Scegline un altro.';
        } elseif (auth_email_exists($pdo, $email)) {
            $errori['email'] = 'Email già in uso. Usane un\'altra oppure accedi.';
        } else {
            $insertStmt = $pdo->prepare(
                'INSERT INTO users (username, email, password_hash, role, email_verified_at, created_at, updated_at)
                 VALUES (:username, :email, :password_hash, "user", NOW(), NOW(), NOW())'
            );
            $insertStmt->execute([
                'username' => $username,
                'email' => $email,
                'password_hash' => password_hash($password, PASSWORD_DEFAULT),
            ]);

            $nuovoUtenteId = (int) $pdo->lastInsertId();
            login_user([
                'id' => $nuovoUtenteId,
                'username' => $username,
                'email' => $email,
                'role' => 'user',
            ]);
            flash_set('success', 'Registrazione completata. Benvenuto, ' . $username . '!');
            header('Location: account');
            exit;
        }
    }
}

render_page('account/registrati.php', [
    'pageTitle' => 'Crea un account - Smash Burger Original',
    'pageDescription' => 'Registrati su Smash Burger per ordinare più velocemente e tenere traccia dei tuoi ordini.',
    'currentPage' => 'registrati',
    'breadcrumb' => [['Home', './'], ['Crea un account', null]],
    'errori' => $errori,
    'valori' => $valori,
    'csrfToken' => $csrfToken
]);
