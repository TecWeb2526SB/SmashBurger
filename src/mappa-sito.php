<?php
/**
 * Mappa del sito: elenco delle pagine, raggruppate per area.
 *
 * Le voci derivano da includes/pagine.php e sono filtrate per il ruolo di chi guarda,
 * cosi' la mappa non propone pagine che porterebbero a un errore.
 */

require_once __DIR__ . '/includes/risorse.php';

richiedi_permesso($pdo);

$ruolo = ruolo_corrente($pdo);

mostra_pagina('informazioni/mappa-sito.php', [
    'breadcrumb' => [['Home', url()], ['Mappa del sito', null]],
    'aree' => [
        'Pagine principali' => pagine_del_menu('principale', $ruolo),
        'Il tuo account' => pagine_del_menu('azioni', $ruolo),
        'Pannello di controllo' => pagine_del_menu('controllo', $ruolo),
        'Informazioni' => pagine_del_menu('piede', $ruolo),
    ],
]);
