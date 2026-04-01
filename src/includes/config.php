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

// Sessione applicativa (necessaria per CSRF e stato utente)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
