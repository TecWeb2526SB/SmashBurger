<?php
/**
 * resources.php: File centrale per l'inclusione ordinata degli script necessari.
 */

// 1. Carica la configurazione e le costanti globali
require_once __DIR__ . '/config.php';

// 2. Carica le variabili globali del sito
require_once __DIR__ . '/variables.php';

// 3. Carica le classi e le funzioni (da popolare man mano)
require_once __DIR__ . '/functions/security.php';
require_once __DIR__ . '/functions/auth.php';
require_once __DIR__ . '/functions/shop.php';
// require_once __DIR__ . '/functions/utility.php';
// require_once __DIR__ . '/class/Database.php';

// 4. Carica la connessione al database
require_once __DIR__ . '/db_connection.php';
