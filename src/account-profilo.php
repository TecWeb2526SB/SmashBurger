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

$erroriIdentita = [];
$erroriPassword = [];
$formIdentita = [
    'username' => (string) $utente['username'],
    'email' => (string) $utente['email'],
    'current_password' => '',
];
$formPassword = [
    'current_password' => '',
    'new_password' => '',
    'confirm_password' => '',
];
$csrfToken = csrf_token();
$flash = flash_get();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $csrfTokenForm = $_POST['csrf_token'] ?? null;
    $action = (string) ($_POST['action'] ?? '');

    if (!csrf_is_valid($csrfTokenForm)) {
        flash_set('error', 'Sessione scaduta o richiesta non valida. Ricarica la pagina e riprova.');
        header('Location: account-profilo');
        exit;
    }

    if ($action === 'update_identity') {
        $formIdentita['username'] = trim((string) ($_POST['username'] ?? ''));
        $formIdentita['email'] = auth_normalize_email((string) ($_POST['email'] ?? ''));
        $formIdentita['current_password'] = (string) ($_POST['current_password'] ?? '');

        if ($formIdentita['username'] === '') {
            $erroriIdentita['username'] = 'Inserisci uno username.';
        } elseif (!auth_is_valid_username($formIdentita['username'])) {
            $erroriIdentita['username'] = 'Username non valido (3-50 caratteri: lettere, numeri, . _ -).';
        }

        if ($formIdentita['email'] === '') {
            $erroriIdentita['email'] = 'Inserisci una email.';
        } elseif (!filter_var($formIdentita['email'], FILTER_VALIDATE_EMAIL)) {
            $erroriIdentita['email'] = 'Inserisci una email valida.';
        }

        if ($formIdentita['current_password'] === '') {
            $erroriIdentita['current_password'] = 'Inserisci la password attuale per confermare le modifiche.';
        } elseif (!password_verify($formIdentita['current_password'], (string) $utente['password_hash'])) {
            $erroriIdentita['current_password'] = 'La password attuale non è corretta.';
        }

        $usernameChanged = $formIdentita['username'] !== (string) $utente['username'];
        $emailChanged = $formIdentita['email'] !== (string) $utente['email'];

        if (!$usernameChanged && !$emailChanged) {
            $erroriIdentita['generale'] = 'Non ci sono modifiche da salvare.';
        }

        if (empty($erroriIdentita) && auth_username_exists($pdo, $formIdentita['username'], (int) $utente['id'])) {
            $erroriIdentita['username'] = 'Username già in uso. Scegline un altro.';
        }

        if (empty($erroriIdentita) && auth_email_exists($pdo, $formIdentita['email'], (int) $utente['id'])) {
            $erroriIdentita['email'] = 'Email già in uso. Usane un\'altra.';
        }

        if (empty($erroriIdentita)) {
            auth_update_user_identity(
                $pdo,
                (int) $utente['id'],
                $formIdentita['username'],
                $formIdentita['email']
            );

            $utente = auth_get_user_by_id($pdo, (int) $utente['id']) ?? $utente;
            login_user($utente);

            $messaggi = [];
            if ($usernameChanged) {
                $messaggi[] = 'Username aggiornato con successo.';
            }
            if ($emailChanged) {
                $messaggi[] = 'Email aggiornata con successo.';
            }

            flash_set('success', implode(' ', $messaggi));
            header('Location: account-profilo');
            exit;
        }
    } elseif ($action === 'update_password') {
        $formPassword['current_password'] = (string) ($_POST['current_password'] ?? '');
        $formPassword['new_password'] = (string) ($_POST['new_password'] ?? '');
        $formPassword['confirm_password'] = (string) ($_POST['confirm_password'] ?? '');

        if ($formPassword['current_password'] === '') {
            $erroriPassword['current_password'] = 'Inserisci la password attuale.';
        } elseif (!password_verify($formPassword['current_password'], (string) $utente['password_hash'])) {
            $erroriPassword['current_password'] = 'La password attuale non è corretta.';
        }

        if ($formPassword['new_password'] === '') {
            $erroriPassword['new_password'] = 'Inserisci una nuova password.';
        } elseif (mb_strlen($formPassword['new_password']) < 8) {
            $erroriPassword['new_password'] = 'La nuova password deve contenere almeno 8 caratteri.';
        } elseif (!auth_is_valid_password($formPassword['new_password'])) {
            $erroriPassword['new_password'] = 'La nuova password può contenere solo lettere, numeri, underscore (_) e questi simboli: ! @ # $ % &';
        } elseif (hash_equals($formPassword['current_password'], $formPassword['new_password'])) {
            $erroriPassword['new_password'] = 'La nuova password deve essere diversa da quella attuale.';
        }

        if ($formPassword['confirm_password'] === '') {
            $erroriPassword['confirm_password'] = 'Conferma la nuova password.';
        } elseif ($formPassword['confirm_password'] !== $formPassword['new_password']) {
            $erroriPassword['confirm_password'] = 'Le password non coincidono.';
        }

        if (empty($erroriPassword)) {
            auth_update_user_password(
                $pdo,
                (int) $utente['id'],
                password_hash($formPassword['new_password'], PASSWORD_DEFAULT)
            );

            $utente = auth_get_user_by_id($pdo, (int) $utente['id']) ?? $utente;
            login_user($utente);

            flash_set('success', 'Password aggiornata con successo.');
            header('Location: account-profilo');
            exit;
        }
    }
}

$pageTitle = 'Gestisci account - Smash Burger Original';
$pageDescription = 'Aggiorna username, email e password del tuo account Smash Burger.';
$currentPage = 'account-profilo';
$breadcrumb = [['Home', './'], ['Area personale', 'account'], ['Gestisci account', null]];

render_page('account/profilo.php', [
    'pageTitle' => $pageTitle,
    'pageDescription' => $pageDescription,
    'currentPage' => $currentPage,
    'breadcrumb' => $breadcrumb,
    'utente' => $utente,
    'flash' => $flash,
    'csrfToken' => $csrfToken,
    'formIdentita' => $formIdentita,
    'formPassword' => $formPassword,
    'erroriIdentita' => $erroriIdentita,
    'erroriPassword' => $erroriPassword
]);
