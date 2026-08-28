<?php
/**
 * Punto di ingresso comune a tutti i controller: carica configurazione, funzioni e
 * connessione al database nell'ordine richiesto dalle dipendenze.
 */

require_once __DIR__ . '/configurazione.php';
require_once __DIR__ . '/funzioni/pagina.php';
require_once __DIR__ . '/funzioni/sicurezza.php';
require_once __DIR__ . '/funzioni/utenti.php';
require_once __DIR__ . '/funzioni/sedi.php';
require_once __DIR__ . '/funzioni/catalogo.php';
require_once __DIR__ . '/funzioni/carrello.php';
require_once __DIR__ . '/funzioni/ordini.php';
require_once __DIR__ . '/database.php';
