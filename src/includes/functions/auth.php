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

function current_user_role(): string
{
    $user = current_user();

    return (string) ($user['role'] ?? 'user');
}

function current_user_managed_branch_id(): ?int
{
    $user = current_user();
    $branchId = isset($user['managed_branch_id']) ? (int) $user['managed_branch_id'] : 0;

    return $branchId > 0 ? $branchId : null;
}

function is_general_admin(): bool
{
    return current_user_role() === 'admin';
}

function is_branch_manager(): bool
{
    return current_user_role() === 'branch_manager';
}

function can_access_admin_panel(): bool
{
    return is_general_admin() || is_branch_manager();
}

function can_modify_managed_branch(int $branchId): bool
{
    $managedBranchId = current_user_managed_branch_id();

    return is_branch_manager() && $managedBranchId !== null && $managedBranchId === $branchId;
}

function can_view_branch_admin_area(int $branchId): bool
{
    return is_general_admin() || can_modify_managed_branch($branchId);
}

function can_manage_global_catalog(): bool
{
    return is_general_admin();
}

function can_manage_branch_managers(): bool
{
    return is_general_admin();
}

function can_place_customer_orders(): bool
{
    return current_user_role() === 'user';
}

function require_customer_order_access(string $redirectTo = 'account'): void
{
    require_login();

    if (can_place_customer_orders()) {
        return;
    }

    flash_set('error', 'Gli account amministrativi e manager non possono effettuare ordini cliente.');
    header('Location: ' . app_route($redirectTo));
    exit;
}

function login_user(array $user, bool $regenerateSessionId = true): void
{
    $_SESSION['user'] = [
        'id' => (int) ($user['id'] ?? 0),
        'username' => (string) ($user['username'] ?? ''),
        'email' => (string) ($user['email'] ?? ''),
        'role' => (string) ($user['role'] ?? 'user'),
        'managed_branch_id' => isset($user['managed_branch_id']) ? (int) $user['managed_branch_id'] : null,
        'is_active' => isset($user['is_active']) ? (int) $user['is_active'] : 1,
    ];

    if ($regenerateSessionId) {
        session_regenerate_id(true);
    }
}

function logout_user(): void
{
    unset($_SESSION['user']);
    session_regenerate_id(true);
}

function require_login(string $redirectTo = 'accedi'): void
{
    if (is_logged_in()) {
        return;
    }

    flash_set('error', 'Per continuare devi effettuare l\'accesso.');
    header('Location: ' . app_route($redirectTo));
    exit;
}

/**
 * Carica l'utente corrente dal DB, riallinea la sessione e lo restituisce.
 * Se l'utente non e valido, chiude la sessione e reindirizza.
 */
function auth_require_fresh_user(
    PDO $pdo,
    string $invalidSessionMessage = 'Sessione utente non valida. Effettua di nuovo l\'accesso.',
    string $redirectTo = 'accedi'
): array {
    require_login($redirectTo);

    $sessionUser = current_user();
    $userId = (int) ($sessionUser['id'] ?? 0);
    $utente = $userId > 0 ? auth_get_user_by_id($pdo, $userId) : null;

    if ($utente === null) {
        logout_user();
        flash_set('error', $invalidSessionMessage);
        header('Location: ' . app_route($redirectTo));
        exit;
    }

    login_user($utente, false);

    return $utente;
}

function auth_normalize_redirect_target(string $redirectTo, string $default = 'account'): string
{
    $redirectTo = trim($redirectTo);

    if ($redirectTo === '') {
        return app_route($default);
    }

    if (str_contains($redirectTo, "\n") || str_contains($redirectTo, "\r")) {
        return app_route($default);
    }

    if (preg_match('/^[a-z][a-z0-9+.-]*:\/\//i', $redirectTo) === 1 || str_starts_with($redirectTo, '//')) {
        return app_route($default);
    }

    $parsed = parse_url($redirectTo);
    if ($parsed === false) {
        return app_route($default);
    }

    $path = (string) ($parsed['path'] ?? '');
    if ($path === '' || $path === '/') {
        $path = $default;
    } elseif (str_contains($path, '..') || str_contains($path, '\\') || str_starts_with($path, '/')) {
        $path = $default;
    }

    $normalized = app_route($path);

    if (!empty($parsed['query'])) {
        $normalized .= '?' . $parsed['query'];
    }

    if (!empty($parsed['fragment'])) {
        $normalized .= '#' . $parsed['fragment'];
    }

    return $normalized;
}

function require_admin_panel_access(string $redirectTo = 'account'): void
{
    require_login();

    if (can_access_admin_panel()) {
        return;
    }

    flash_set('error', 'Non hai i permessi necessari per accedere al pannello di controllo.');
    header('Location: ' . app_route($redirectTo));
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
    return (bool) preg_match('/\A[\p{L}\p{N}_!@#$%&]+\z/u', $password);
}

function auth_get_user_by_id(PDO $pdo, int $userId): ?array
{
    $stmt = $pdo->prepare(
        'SELECT id, username, email, password_hash, role, managed_branch_id, is_active, email_verified_at
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
        'SELECT id, username, email, password_hash, role, managed_branch_id, is_active, email_verified_at
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

function auth_get_branch_managers(PDO $pdo): array
{
    $stmt = $pdo->query(
        'SELECT
            u.id,
            u.username,
            u.email,
            u.role,
            u.managed_branch_id,
            u.is_active,
            u.created_at,
            b.name AS managed_branch_name,
            b.slug AS managed_branch_slug
         FROM users u
         LEFT JOIN branches b ON b.id = u.managed_branch_id
         WHERE u.role = "branch_manager"
         ORDER BY b.sort_order ASC, u.username ASC'
    );

    return $stmt->fetchAll();
}

function auth_get_branch_manager_by_id(PDO $pdo, int $userId): ?array
{
    $stmt = $pdo->prepare(
        'SELECT
            u.id,
            u.username,
            u.email,
            u.role,
            u.managed_branch_id,
            u.is_active,
            b.name AS managed_branch_name
         FROM users u
         LEFT JOIN branches b ON b.id = u.managed_branch_id
         WHERE u.id = :id
           AND u.role = "branch_manager"
         LIMIT 1'
    );
    $stmt->execute(['id' => $userId]);
    $row = $stmt->fetch();

    return $row ?: null;
}
function auth_branch_manager_exists_for_branch(PDO $pdo, int $branchId, ?int $excludeUserId = null): bool
{
    $sql = 'SELECT id
            FROM users
            WHERE role = "branch_manager"
              AND managed_branch_id = :managed_branch_id
              AND is_active = 1';
    $params = ['managed_branch_id' => $branchId];

    if ($excludeUserId !== null) {
        $sql .= ' AND id <> :exclude_user_id';
        $params['exclude_user_id'] = $excludeUserId;
    }

    $sql .= ' LIMIT 1';
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);

    return $stmt->fetchColumn() !== false;
}

function auth_create_branch_manager(
    PDO $pdo,
    string $username,
    string $email,
    string $passwordHash,
    int $branchId
): int {
    $stmt = $pdo->prepare(
        'INSERT INTO users
            (username, email, password_hash, role, managed_branch_id, is_active, email_verified_at, created_at, updated_at)
         VALUES
            (:username, :email, :password_hash, "branch_manager", :managed_branch_id, 1, NOW(), NOW(), NOW())'
    );
    $stmt->execute([
        'username' => $username,
        'email' => $email,
        'password_hash' => $passwordHash,
        'managed_branch_id' => $branchId,
    ]);

    return (int) $pdo->lastInsertId();
}

function auth_update_branch_manager(
    PDO $pdo,
    int $userId,
    string $username,
    string $email,
    int $branchId,
    ?string $passwordHash = null
): void {
    $sql = 'UPDATE users
            SET username = :username,
                email = :email,
                managed_branch_id = :managed_branch_id,
                updated_at = NOW()';
    $params = [
        'id' => $userId,
        'username' => $username,
        'email' => $email,
        'managed_branch_id' => $branchId,
    ];

    if ($passwordHash !== null) {
        $sql .= ', password_hash = :password_hash';
        $params['password_hash'] = $passwordHash;
    }

    $sql .= ' WHERE id = :id
              AND role = "branch_manager"
              LIMIT 1';

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
}

function auth_toggle_branch_manager(PDO $pdo, int $userId, bool $isActive): void
{
    $stmt = $pdo->prepare(
        'UPDATE users
         SET is_active = :is_active,
             updated_at = NOW()
         WHERE id = :id
           AND role = "branch_manager"
         LIMIT 1'
    );
    $stmt->execute([
        'is_active' => $isActive ? 1 : 0,
        'id' => $userId,
    ]);
}

function auth_delete_branch_manager(PDO $pdo, int $userId): void
{
    $stmt = $pdo->prepare(
        'DELETE FROM users
         WHERE id = :id
           AND role = "branch_manager"
         LIMIT 1'
    );
    $stmt->execute(['id' => $userId]);
}

function auth_delete_user(PDO $pdo, int $userId): void
{
    $stmt = $pdo->prepare('DELETE FROM users WHERE id = :id LIMIT 1');
    $stmt->execute(['id' => $userId]);
}
