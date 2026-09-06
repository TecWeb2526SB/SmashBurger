<?php
/**
 * Pagina pubblica di un singolo prodotto, raggiunta con prodotto?slug=nome-prodotto.
 *
 * Titolo e descrizione si ricavano dai dati del prodotto: rispondono alle ricerche su un
 * prodotto preciso meglio del menu generale.
 */

require_once __DIR__ . '/includes/risorse.php';

richiedi_permesso($pdo);

$slug = slug_richiesto('slug');
$prodotto = $slug === null ? null : prodotto_per_slug($pdo, $slug);

// Slug assente, malformato o inesistente: la richiesta si chiude con la nostra 404.
if ($prodotto === null) {
    errore(404);
}

$descrizione = mb_substr($prodotto['descrizione'], 0, 155);

mostra_pagina('pubbliche/prodotto.php', [
    'titolo' => $prodotto['nome'] . ' - Smash Burger',
    'descrizione' => $descrizione,
    'breadcrumb' => [
        ['Home', url()],
        ['Menu', url('menu')],
        [$prodotto['categoria_nome'], url('menu', ['categoria' => $prodotto['categoria_slug']])],
        [$prodotto['nome'], null],
    ],
    'prodotto' => $prodotto,
    'sediDisponibili' => sedi_con_prodotto($pdo, (int) $prodotto['id']),
]);
