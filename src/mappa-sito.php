<?php
/**
 * Controller della mappa del sito.
 *
 * L'elenco cambia in base allo stato di accesso: le pagine riservate compaiono solo a
 * chi può raggiungerle.
 */

require_once __DIR__ . '/includes/risorse.php';

$sezioni = [
    'Pagine pubbliche' => [
        'Home' => url(),
        'Menu e prezzi' => url('prodotti'),
        'Servizi' => url('servizi'),
        'Chi siamo' => url('chi-siamo'),
        'Sedi e orari' => url('sedi'),
    ],
    'Informazioni' => [
        'Privacy policy' => url('privacy'),
        'Accessibilità' => url('accessibilita'),
        'Mappa del sito' => url('mappa-sito'),
    ],
];

if (utente_autenticato()) {
    $sezioni['Il tuo account'] = [
        'Area personale' => url('area-personale'),
        'Profilo' => url('profilo'),
        'Carrello' => url('carrello'),
        'Esci' => url('esci'),
    ];

    if (utente_e_amministratore()) {
        $sezioni['Controllo'] = ['Pannello di controllo' => url('controllo')];
    }
} else {
    $sezioni['Il tuo account'] = [
        'Accedi' => url('accedi'),
        'Registrati' => url('registrati'),
    ];
}

mostra_pagina('informazioni/mappa-sito.php', [
    'titolo' => 'Mappa del sito - Smash Burger',
    'descrizione' => 'Elenco completo delle pagine del sito, ordinate per area.',
    'pagina' => 'Mappa del sito',
    'breadcrumb' => [['Home', url()], ['Mappa del sito', null]],
    'sezioni' => $sezioni,
]);
