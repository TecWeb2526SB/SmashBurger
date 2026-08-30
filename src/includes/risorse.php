<?php
/**
 * Carica in ordine tutto cio' che serve a una pagina.
 *
 * Ogni controller comincia includendo questo file e nient'altro. Qui non si gestiscono
 * richieste e non si stampa nulla: il file si limita a includere.
 */

require_once __DIR__ . '/configurazione.php';
require_once __DIR__ . '/pagine.php';
require_once __DIR__ . '/funzioni/pagina.php';
require_once __DIR__ . '/funzioni/sicurezza.php';
require_once __DIR__ . '/database.php';
require_once __DIR__ . '/funzioni/utenti.php';
require_once __DIR__ . '/funzioni/sedi.php';
require_once __DIR__ . '/funzioni/catalogo.php';
require_once __DIR__ . '/funzioni/contatti.php';
require_once __DIR__ . '/funzioni/indirizzi.php';
require_once __DIR__ . '/funzioni/profilo.php';
require_once __DIR__ . '/funzioni/ordini.php';
require_once __DIR__ . '/funzioni/carrello.php';
require_once __DIR__ . '/funzioni/ordini-scrittura.php';
require_once __DIR__ . '/funzioni/prenotazioni.php';
