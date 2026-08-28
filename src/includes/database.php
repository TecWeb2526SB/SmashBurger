<?php
/**
 * Connessione al database.
 *
 * La variabile $pdo è usata da tutte le funzioni di dominio. Gli errori diventano
 * eccezioni e le query non sono emulate, così i parametri restano tipizzati.
 */

$dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NOME . ';charset=utf8mb4';

try {
    $pdo = new PDO($dsn, DB_UTENTE, DB_PASSWORD, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);
} catch (PDOException $errore) {
    error_log('Connessione al database non riuscita: ' . $errore->getMessage());
    http_response_code(500);
    include __DIR__ . '/../errors/500.php';
    exit;
}
