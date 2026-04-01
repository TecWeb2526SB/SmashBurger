<?php
/**
 * config.php: Costanti e configurazioni globali dell'applicazione.
 */

// Credenziali del database
define('DB_HOST', 'db');
define('DB_NAME', getenv('DB_NAME') ?: 'progetto_db');
define('DB_USER', getenv('DB_USER') ?: 'utente_fallback');
define('DB_PASS', getenv('DB_PASSWORD') ?: 'password_fallback_sicura_123');
define('DB_CHARSET', 'utf8mb4');

// Percorsi di base (opzionale, ma utile)
define('BASE_PATH', dirname(__DIR__) . '/');
define('INCLUDES_PATH', BASE_PATH . 'includes/');
define('VIEWS_PATH', BASE_PATH . 'views/');
define('STYLES_PATH', BASE_PATH . 'styles/');

// Configurazione PDO
define('PDO_OPTIONS', [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
]);

// Protezione base delle sessioni in ambienti reali.
define('LOGIN_RATE_LIMIT_WINDOW_SECONDS', 900);
define('LOGIN_RATE_LIMIT_MAX_ATTEMPTS_PER_IP', 12);
define('LOGIN_RATE_LIMIT_MAX_ATTEMPTS_PER_IDENTIFIER', 6);
define('LOGIN_RATE_LIMIT_BLOCK_SECONDS', 900);

$sessionIsSecure = false;
$httpsValue = strtolower((string) ($_SERVER['HTTPS'] ?? ''));
$forwardedProto = strtolower(trim(explode(',', (string) ($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? ''))[0] ?? ''));

if ($httpsValue === 'on' || $httpsValue === '1' || $forwardedProto === 'https') {
    $sessionIsSecure = true;
}

// Sessione applicativa (necessaria per CSRF e stato utente)
if (session_status() === PHP_SESSION_NONE) {
    ini_set('session.use_strict_mode', '1');
    ini_set('session.use_only_cookies', '1');
    ini_set('session.cookie_httponly', '1');
    ini_set('session.cookie_secure', $sessionIsSecure ? '1' : '0');
    ini_set('session.cookie_samesite', 'Lax');
    ini_set('session.gc_maxlifetime', '7200');

    session_name('smashburger_session');
    session_set_cookie_params([
        'lifetime' => 0,
        'path' => '/',
        'domain' => '',
        'secure' => $sessionIsSecure,
        'httponly' => true,
        'samesite' => 'Lax',
    ]);
    session_start();
}
