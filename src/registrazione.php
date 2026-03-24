<?php
require_once __DIR__ . '/includes/resources.php';

/**
 * registrazione.php — Controller pagina di registrazione.
 * Gestisce sia GET (mostra form) sia POST (valida dati e crea account).
 */

$errori = [];
$valori = [
    'username' => '',
];
$csrfToken = csrf_token();

if (is_logged_in()) {
    header('Location: area_personale.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $csrfTokenForm = $_POST['csrf_token'] ?? null;
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $conferma = trim($_POST['conferma'] ?? '');

    if (!csrf_is_valid($csrfTokenForm)) {
        $errori['generale'] = 'Sessione scaduta o richiesta non valida. Ricarica la pagina e riprova.';
    }

    if ($username === '') {
        $errori['username'] = 'Inserisci uno username.';
    } elseif (!preg_match('/^[a-zA-Z0-9_.-]{3,50}$/', $username)) {
        $errori['username'] = 'Username non valido (3-50 caratteri: lettere, numeri, . _ -).';
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

    $valori['username'] = htmlspecialchars($username, ENT_QUOTES, 'UTF-8');

    if (empty($errori)) {
        $existsStmt = $pdo->prepare(
            'SELECT id FROM users WHERE username = :username LIMIT 1'
        );
        $existsStmt->execute(['username' => $username]);
        if ($existsStmt->fetchColumn() !== false) {
            $errori['username'] = 'Username gia in uso. Scegline un altro.';
        } else {
            $insertStmt = $pdo->prepare(
                'INSERT INTO users (username, password_hash, role, created_at, updated_at)
                 VALUES (:username, :password_hash, "user", NOW(), NOW())'
            );
            $insertStmt->execute([
                'username' => $username,
                'password_hash' => password_hash($password, PASSWORD_DEFAULT),
            ]);

            $nuovoUtenteId = (int) $pdo->lastInsertId();
            login_user([
                'id' => $nuovoUtenteId,
                'username' => $username,
                'role' => 'user',
            ]);
            flash_set('success', 'Registrazione completata. Benvenuto, ' . $username . '!');
            header('Location: area_personale.php');
            exit;
        }
    }
}

$pageTitle       = 'Crea un account - Smash Burger Original';
$pageDescription = 'Registrati su Smash Burger per ordinare più velocemente e tenere traccia dei tuoi ordini.';
$currentPage     = 'registrazione.php';
$breadcrumb      = [['Home', 'index.php'], ['Crea un account', null]];

include_once __DIR__ . '/views/template/header.php';
include_once __DIR__ . '/views/registrazione.php';
include_once __DIR__ . '/views/template/footer.php';
