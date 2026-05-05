<?php
/**
 * config.php: Costanti e configurazioni globali dell'applicazione.
 */

// Credenziali del database
// In locale si usano le variabili d'ambiente; su TecWeb il deploy deposita
// un file privato dentro `src/includes/` con i valori reali del database.
$deploymentConfig = [];
foreach ([
    __DIR__ . '/.smashburger-config.php',
    dirname(__DIR__, 2) . '/.smashburger-config.php',
] as $deploymentConfigPath) {
    if (!is_file($deploymentConfigPath)) {
        continue;
    }

    $loadedConfig = require $deploymentConfigPath;
    if (is_array($loadedConfig)) {
        $deploymentConfig = $loadedConfig;
        break;
    }
}

define('DB_HOST', (string) ($deploymentConfig['DB_HOST'] ?? 'db'));
define('DB_NAME', (string) ($deploymentConfig['DB_NAME'] ?? (getenv('DB_NAME') ?: 'progetto_db')));
define('DB_USER', (string) ($deploymentConfig['DB_USER'] ?? (getenv('DB_USER') ?: 'utente_fallback')));
define('DB_PASS', (string) ($deploymentConfig['DB_PASSWORD'] ?? (getenv('DB_PASSWORD') ?: 'password_fallback_sicura_123')));
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

if (!function_exists('app_route')) {
    function app_route(string $route, array $query = [], ?string $fragment = null): string
    {
        $route = trim($route);

        if ($route === '' || $route === './' || $route === '.') {
            $url = './';
        } elseif (preg_match('/^[a-z][a-z0-9+.-]*:\/\//i', $route) === 1 || str_starts_with($route, '//') || str_starts_with($route, 'mailto:') || str_starts_with($route, 'tel:') || str_starts_with($route, '#')) {
            return $route;
        } else {
            $parsed = parse_url($route);
            $path = (string) ($parsed['path'] ?? $route);
            $path = trim($path);

            if ($path === '' || $path === '/') {
                $url = './';
            } else {
                $path = ltrim($path, '/');
                $path = preg_replace('/\.php$/i', '', $path);
                $path = trim($path, '/');

                $url = $path === '' || $path === 'index' ? './' : $path;
            }

            if (!empty($parsed['query'])) {
                parse_str((string) $parsed['query'], $parsedQuery);
                $query = array_merge($parsedQuery, $query);
            }

            if (!empty($parsed['fragment']) && $fragment === null) {
                $fragment = (string) $parsed['fragment'];
            }
        }

        if (!empty($query)) {
            $url .= '?' . http_build_query($query);
        }

        if ($fragment !== null && $fragment !== '') {
            $url .= '#' . ltrim($fragment, '#');
        }

        return $url;
    }
}

if (!function_exists('app_route_name')) {
    function app_route_name(string $route): string
    {
        $route = trim($route);

        if ($route === '' || $route === './' || $route === '.') {
            return './';
        }

        $parsed = parse_url($route);
        $path = trim((string) ($parsed['path'] ?? $route), '/');

        if ($path === '' || $path === 'index' || $path === 'index.php') {
            return './';
        }

        return preg_replace('/\.php$/i', '', basename($path));
    }
}

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
