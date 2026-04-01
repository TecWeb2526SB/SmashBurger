<?php
/**
 * Helper autenticazione/sessione.
 */

function is_logged_in(): bool
{
    return isset($_SESSION['user']) && isset($_SESSION['user']['id']);
}

function current_user(): ?array
{
    return is_logged_in() ? $_SESSION['user'] : null;
}

function login_user(array $user): void
{
    $_SESSION['user'] = [
        'id' => (int) ($user['id'] ?? 0),
        'username' => (string) ($user['username'] ?? ''),
        'role' => (string) ($user['role'] ?? 'user'),
    ];
    session_regenerate_id(true);
}

function logout_user(): void
{
    unset($_SESSION['user']);
    session_regenerate_id(true);
}

function require_login(string $redirectTo = 'login.php'): void
{
    if (is_logged_in()) {
        return;
    }

    flash_set('error', 'Per continuare devi effettuare l\'accesso.');
    header('Location: ' . $redirectTo);
    exit;
}

function flash_set(string $type, string $message): void
{
    $_SESSION['flash'] = [
        'type' => $type,
        'message' => $message,
    ];
}

function flash_get(): ?array
{
    if (!isset($_SESSION['flash']) || !is_array($_SESSION['flash'])) {
        return null;
    }

    $flash = $_SESSION['flash'];
    unset($_SESSION['flash']);
    return $flash;
}
