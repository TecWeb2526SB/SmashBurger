<?php
/**
 * Costanti dell'applicazione, dati di contatto e avvio della sessione.
 *
 * In sviluppo le credenziali del database arrivano dalle variabili d'ambiente definite
 * nel file .env letto da Docker Compose. Sul server di consegna il deploy deposita un
 * file privato accanto a questo, che ha la precedenza.
 */

$configurazioneServer = [];
$fileConfigurazione = __DIR__ . '/.configurazione-locale.php';
if (is_file($fileConfigurazione)) {
    $valori = require $fileConfigurazione;
    if (is_array($valori)) {
        $configurazioneServer = $valori;
    }
}

define('DB_HOST', (string) ($configurazioneServer['DB_HOST'] ?? getenv('DB_HOST') ?: 'db'));
define('DB_NOME', (string) ($configurazioneServer['DB_NOME'] ?? getenv('DB_NAME') ?: 'esame_web'));
define('DB_UTENTE', (string) ($configurazioneServer['DB_UTENTE'] ?? getenv('DB_USER') ?: 'utente'));
define('DB_PASSWORD', (string) ($configurazioneServer['DB_PASSWORD'] ?? getenv('DB_PASSWORD') ?: 'password'));

define('NOME_SITO', 'Smash Burger');

// Cambiando questo numero il browser scarica di nuovo foglio di stile e script.
define('VERSIONE_RISORSE', '5');
define('EMAIL_SITO', 'info@smashburger.it');
define('TELEFONO_SITO', '049 111 2201');

// Minuti di attesa fra la conferma dell'ordine e il primo orario di ritiro possibile.
define('MINUTI_PREPARAZIONE', 20);

// Regole della password: valgono per il controllo lato server, per gli attributi del
// markup e per il testo mostrato accanto al campo.
define('PASSWORD_MINIMO', 8);
define('PASSWORD_MASSIMO', 64);
define('PASSWORD_CARATTERI', '!?@#$%&*+-_.');
define('PASSWORD_CARATTERI_REGOLA', '/^[A-Za-z0-9!?@#$%&*+\-_.]+$/');
define('PASSWORD_CONDIZIONI', 'Da 8 a 64 caratteri. Ammessi lettere, cifre e i simboli ! ? @ # $ % & * + - _ . senza spazi.');

/**
 * Costruisce un indirizzo relativo a partire dal nome di una pagina.
 *
 * Gli indirizzi non contengono l'estensione .php: la riscrittura in .htaccess associa
 * il nome al file corrispondente. La home usa './' così il sito resta installabile in
 * una sottocartella qualsiasi.
 *
 * @param string $pagina  nome della pagina senza estensione, vuoto per la home
 * @param array  $parametri  coppie nome/valore aggiunte come stringa di ricerca
 */
function url(string $pagina = '', array $parametri = []): string
{
    $pagina = trim($pagina, '/');
    $indirizzo = ($pagina === '' || $pagina === 'index') ? './' : $pagina;

    if ($parametri !== []) {
        $indirizzo .= '?' . http_build_query($parametri);
    }

    return $indirizzo;
}

// Gli errori finiscono nel log del server; la richiesta si chiude con la pagina 500.
ini_set('display_errors', '0');
ini_set('log_errors', '1');

set_exception_handler(function (Throwable $errore): void {
    error_log('Errore non gestito: ' . $errore->getMessage());
    http_response_code(500);
    include __DIR__ . '/../errors/500.php';
});

/**
 * Indica se la richiesta corrente viaggia su HTTPS, tenendo conto di un eventuale
 * proxy che inoltra il protocollo originale nell'header X-Forwarded-Proto.
 */
function richiesta_su_https(): bool
{
    $https = strtolower((string) ($_SERVER['HTTPS'] ?? ''));
    $inoltrato = strtolower(trim(explode(',', (string) ($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? ''))[0]));

    return $https === 'on' || $https === '1' || $inoltrato === 'https';
}

// Header di sicurezza inviati da PHP: valgono anche dove .htaccess non viene letto.
header('Content-Type: text/html; charset=UTF-8');
header('X-Content-Type-Options: nosniff');
header('Referrer-Policy: strict-origin-when-cross-origin');
header('X-Frame-Options: SAMEORIGIN');
header("Content-Security-Policy: default-src 'self'; base-uri 'self'; form-action 'self'; frame-ancestors 'self'; img-src 'self' data:; object-src 'none'");

/**
 * Restituisce il nome della pagina che il browser sta chiedendo, senza estensione.
 *
 * Serve ai moduli che devono riportare alla pagina di partenza. Il valore viene ripulito
 * da tutto quello che non è un nome di pagina, e chi lo riceve lo confronta comunque con
 * i controller esistenti.
 */
function app_pagina_corrente(): string
{
    $percorso = (string) parse_url((string) ($_SERVER['REQUEST_URI'] ?? ''), PHP_URL_PATH);
    $nome = basename(trim($percorso, '/'));
    $nome = preg_replace('/\.php$/i', '', $nome);

    return preg_match('/^[A-Za-z0-9_-]+$/', (string) $nome) === 1 ? (string) $nome : '';
}

// La sessione tiene accesso, token CSRF e sede scelta. La modalità stretta rifiuta gli
// identificativi non generati dal server, che viaggiano solo nel cookie.
if (session_status() === PHP_SESSION_NONE) {
    ini_set('session.use_strict_mode', '1');
    ini_set('session.use_only_cookies', '1');

    session_name('smashburger');
    session_set_cookie_params([
        'lifetime' => 0,
        'path' => '/',
        'secure' => richiesta_su_https(),
        'httponly' => true,
        'samesite' => 'Lax',
    ]);
    session_start();
}
