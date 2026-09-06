<?php
/**
 * Connessione al database.
 *
 * Le credenziali non stanno nel codice versionato: in sviluppo arrivano dall'ambiente
 * passato dal contenitore, in produzione da .configurazione-locale.php, che il deploy
 * scrive fuori dal repository. Il file inizia con un punto, quindi Apache non lo serve.
 */

$configurazioneLocale = __DIR__ . '/.configurazione-locale.php';
$credenziali = is_readable($configurazioneLocale) ? require $configurazioneLocale : [];

$host = $credenziali['host'] ?? getenv('DB_HOST') ?: 'db';
$database = $credenziali['database'] ?? getenv('DB_NAME') ?: '';
$utente = $credenziali['utente'] ?? getenv('DB_USER') ?: '';
$password = $credenziali['password'] ?? getenv('DB_PASSWORD') ?: '';

try {
    $pdo = new PDO(
        "mysql:host={$host};dbname={$database};charset=utf8mb4",
        $utente,
        $password,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            // Senza emulazione i parametri arrivano tipizzati al database. Un segnaposto
            // vale per una sola occorrenza, quindi un valore ripetuto ha nomi distinti.
            PDO::ATTR_EMULATE_PREPARES => false,
        ]
    );
} catch (PDOException $errore) {
    // Il dettaglio tecnico finisce nel log del server, mai nella pagina.
    error_log('Connessione al database non riuscita: ' . $errore->getMessage());

    http_response_code(500);
    require __DIR__ . '/../errors/500.php';
    exit;
}
