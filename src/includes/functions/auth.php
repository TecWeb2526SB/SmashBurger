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
        'email' => (string) ($user['email'] ?? ''),
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

function auth_is_valid_username(string $username): bool
{
    return (bool) preg_match('/^[a-zA-Z0-9_.-]{3,50}$/', $username);
}

function auth_normalize_email(string $email): string
{
    return mb_strtolower(trim($email));
}

function auth_is_valid_password(string $password): bool
{
    return (bool) preg_match('/\A[\p{L}\p{N}!@#$%&]+\z/u', $password);
}

function auth_get_user_by_id(PDO $pdo, int $userId): ?array
{
    $stmt = $pdo->prepare(
        'SELECT id, username, email, password_hash, role, email_verified_at
         FROM users
         WHERE id = :id
         LIMIT 1'
    );
    $stmt->execute(['id' => $userId]);
    $user = $stmt->fetch();

    return $user ?: null;
}

function auth_get_user_for_login(PDO $pdo, string $identifier): ?array
{
    $stmt = $pdo->prepare(
        'SELECT id, username, email, password_hash, role, email_verified_at
         FROM users
         WHERE username = :username_identifier OR email = :email_identifier
         LIMIT 1'
    );
    $stmt->execute([
        'username_identifier' => $identifier,
        'email_identifier' => $identifier,
    ]);
    $user = $stmt->fetch();

    return $user ?: null;
}

function auth_username_exists(PDO $pdo, string $username, ?int $excludeUserId = null): bool
{
    $sql = 'SELECT id FROM users WHERE username = :username';
    $params = ['username' => $username];

    if ($excludeUserId !== null) {
        $sql .= ' AND id <> :exclude_user_id';
        $params['exclude_user_id'] = $excludeUserId;
    }

    $sql .= ' LIMIT 1';

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);

    return $stmt->fetchColumn() !== false;
}

function auth_email_exists(PDO $pdo, string $email, ?int $excludeUserId = null): bool
{
    $sql = 'SELECT id FROM users WHERE email = :email';
    $params = ['email' => $email];

    if ($excludeUserId !== null) {
        $sql .= ' AND id <> :exclude_user_id';
        $params['exclude_user_id'] = $excludeUserId;
    }

    $sql .= ' LIMIT 1';

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);

    return $stmt->fetchColumn() !== false;
}

function auth_update_user_identity(PDO $pdo, int $userId, string $username, string $email): void
{
    $stmt = $pdo->prepare(
        'UPDATE users
         SET username = :username,
             email = :email,
             email_verified_at = NOW(),
             updated_at = NOW()
         WHERE id = :id
         LIMIT 1'
    );
    $stmt->execute([
        'id' => $userId,
        'username' => $username,
        'email' => $email,
    ]);
}

function auth_update_user_password(PDO $pdo, int $userId, string $passwordHash): void
{
    $stmt = $pdo->prepare(
        'UPDATE users
         SET password_hash = :password_hash,
             updated_at = NOW()
         WHERE id = :id
         LIMIT 1'
    );
    $stmt->execute([
        'id' => $userId,
        'password_hash' => $passwordHash,
    ]);
}
