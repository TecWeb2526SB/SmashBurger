<?php
/**
 * Home: apertura del sito e rimandi alle altre pagine pubbliche.
 *
 * Il podio è una scelta della casa, non una classifica calcolata sugli ordini: i tre
 * slug stanno qui e i dati veri (nome, prezzo, immagine) arrivano dal catalogo, cosi'
 * il collegamento e il prezzo non possono divergere da quelli del menu.
 */

require_once __DIR__ . '/includes/risorse.php';

richiedi_permesso($pdo);

mostra_pagina('pubbliche/home.php', [
    'podio' => prodotti_per_slug($pdo, ['italiano', 'bacon-burger', 'vegan-burger']),
    'sedi' => sedi_attive($pdo),
]);
