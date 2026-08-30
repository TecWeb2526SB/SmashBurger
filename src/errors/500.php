<?php
/**
 * Pagina mostrata quando il server non riesce a completare la richiesta.
 *
 * Il dettaglio tecnico non compare mai qui: finisce nel log del server. Viene inclusa
 * anche quando la connessione al database non riesce, quindi non puo' dipendere da dati
 * letti dal database.
 */

require_once __DIR__ . '/../includes/risorse.php';

http_response_code(500);

mostra_pagina('errori/500.php', [
    'titolo' => 'Errore del server - Smash Burger',
    'breadcrumb' => [['Home', url()], ['Errore del server', null]],
]);
