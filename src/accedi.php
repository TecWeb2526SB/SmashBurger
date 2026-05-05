<?php
require_once __DIR__ . '/includes/resources.php';

/**
 * accedi — Controller pagina di accesso.
 * Gestisce sia GET (mostra form) sia POST (valida credenziali).
 */

$errori               = [];
$valoreIdentificativo = '';
$csrfToken            = csrf_token();
$flash                = flash_get();
$redirectTo           = auth_normalize_redirect_target((string) ($_GET['redirect'] ?? $_POST['redirect'] ?? ''), 'account');

if (is_logged_in()) {
    header('Location: ' . app_route('account'));
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $csrfTokenForm = $_POST['csrf_token'] ?? null;
    $identifier = trim((string) ($_POST['identifier'] ?? ''));
    $password = (string) ($_POST['password'] ?? '');
    $lookupIdentifier = '';

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

    $valoreIdentificativo = $identifier;

    if (empty($errori)) {
        $lookupIdentifier = filter_var(auth_normalize_email($identifier), FILTER_VALIDATE_EMAIL)
            ? auth_normalize_email($identifier)
            : $identifier;
        $rateLimitStatus = login_rate_limit_get_status($lookupIdentifier);

        if (!$rateLimitStatus['allowed']) {
            $errori['generale'] = $rateLimitStatus['message'];
        }
    }

    if (empty($errori)) {
        $utente = auth_get_user_for_login($pdo, $lookupIdentifier);

        if (!$utente || !password_verify($password, (string) $utente['password_hash'])) {
            login_rate_limit_register_failure($lookupIdentifier);
            $rateLimitStatus = login_rate_limit_get_status($lookupIdentifier);
            $errori['generale'] = $rateLimitStatus['allowed']
                ? 'Credenziali non valide.'
                : $rateLimitStatus['message'];
        } elseif ((int) ($utente['is_active'] ?? 1) !== 1) {
            $errori['generale'] = 'Questo account è stato disattivato. Contatta l amministrazione centrale.';
        } else {
            login_rate_limit_clear_identifier($lookupIdentifier);
            login_rate_limit_clear_identifier((string) ($utente['username'] ?? ''));
            login_rate_limit_clear_identifier((string) ($utente['email'] ?? ''));

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
            header('Location: ' . $redirectTo);
            exit;
        }
    }
}

render_page('account/accedi.php', [
    'pageTitle' => 'Accedi al tuo account - Smash Burger Original',
    'pageDescription' => 'Accedi al tuo account Smash Burger per gestire i tuoi ordini e le tue preferenze.',
    'currentPage' => 'accedi',
    'breadcrumb' => [['Home', './'], ['Accedi', null]],
    'errori' => $errori,
    'valoreIdentificativo' => $valoreIdentificativo,
    'redirectTo' => $redirectTo,
    'csrfToken' => $csrfToken,
    'flash' => $flash
]);
