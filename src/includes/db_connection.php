<?php
/**
 * db_connection.php: Logica per la connessione al database MariaDB utilizzando PDO.
 */

// Utilizza le costanti definite in config.php
$dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;

try {
     $pdo = new PDO($dsn, DB_USER, DB_PASS, PDO_OPTIONS);
} catch (\PDOException $e) {
     // Log dell'errore e caricamento della pagina 500 in caso di fallimento della connessione.
     error_log("Errore di connessione al DB: " . $e->getMessage());
     // Percorso relativo per l'errore 500 come da specifica
     include(__DIR__ . '/../errors/500.php');
     exit;
}
