<?php
require_once __DIR__ . '/includes/resources.php';

/**
 * login.php — Controller pagina di accesso.
 * Gestisce sia GET (mostra form) sia POST (valida credenziali).
 */

$errori               = [];
$valoreIdentificativo = '';
$csrfToken            = csrf_token();
$flash                = flash_get();

if (is_logged_in()) {
    header('Location: area_personale.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $csrfTokenForm = $_POST['csrf_token'] ?? null;
    $identifier = trim((string) ($_POST['identifier'] ?? ''));
    $password = (string) ($_POST['password'] ?? '');

    if (!csrf_is_valid($csrfTokenForm)) {
        $errori['generale'] = 'Sessione scaduta o richiesta non valida. Ricarica la pagina e riprova.';
    }

    if ($identifier === '') {
        $errori['identifier'] = 'Inserisci username o email.';
    } elseif (
        !auth_is_valid_username($identifier)
        && !filter_var(auth_normalize_email($identifier), FILTER_VALIDATE_EMAIL)
    ) {
        $errori['identifier'] = 'Inserisci uno username o una email valida.';
    }

    if ($password === '') {
        $errori['password'] = 'Inserisci la tua password.';
    }

    $valoreIdentificativo = htmlspecialchars($identifier, ENT_QUOTES, 'UTF-8');

    if (empty($errori)) {
        $lookupIdentifier = filter_var(auth_normalize_email($identifier), FILTER_VALIDATE_EMAIL)
            ? auth_normalize_email($identifier)
            : $identifier;
        $utente = auth_get_user_for_login($pdo, $lookupIdentifier);

        if (!$utente || !password_verify($password, (string) $utente['password_hash'])) {
            $errori['generale'] = 'Credenziali non valide.';
        } else {
            if (password_needs_rehash((string) $utente['password_hash'], PASSWORD_DEFAULT)) {
                $nuovoHash = password_hash($password, PASSWORD_DEFAULT);
                auth_update_user_password($pdo, (int) $utente['id'], $nuovoHash);
                $utente['password_hash'] = $nuovoHash;
            }

            login_user($utente);
            
            // Se l'utente ha un carrello attivo, sincronizziamo la sede della sessione con quella del carrello
            if (function_exists('cart_get_active_row')) {
                $activeCart = cart_get_active_row($pdo, (int)$utente['id']);
                if ($activeCart && !empty($activeCart['branch_id'])) {
                    $cartBranch = branch_get_by_id($pdo, (int)$activeCart['branch_id']);
                    if ($cartBranch) {
                        $_SESSION['selected_branch_id'] = (int)$cartBranch['id'];
                        $_SESSION['selected_branch_slug'] = (string)$cartBranch['slug'];
                    }
                }
            }

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
