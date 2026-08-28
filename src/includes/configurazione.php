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
define('EMAIL_SITO', 'info@smashburger.it');
define('TELEFONO_SITO', '049 111 2201');

// Minuti di attesa fra la conferma dell'ordine e il primo orario di ritiro possibile.
define('MINUTI_PREPARAZIONE', 20);

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

// Gli errori non vengono stampati nella pagina: finiscono nel log del server e la
// richiesta si chiude con la pagina di errore 500.
ini_set('display_errors', '0');
ini_set('log_errors', '1');

set_exception_handler(function (Throwable $errore): void {
    error_log('Errore non gestito: ' . $errore->getMessage());
    http_response_code(500);
    include __DIR__ . '/../errors/500.php';
});

// La sessione serve per l'accesso, per il token CSRF e per la sede scelta.
if (session_status() === PHP_SESSION_NONE) {
    session_name('smashburger');
    session_set_cookie_params([
        'lifetime' => 0,
        'path' => '/',
        'httponly' => true,
        'samesite' => 'Lax',
    ]);
    session_start();
}
