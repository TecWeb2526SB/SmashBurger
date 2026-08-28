<?php
/**
 * Controller del catalogo.
 *
 * Il parametro categoria filtra l'elenco; se non corrisponde a nessuna categoria viene
 * ignorato e si mostra il catalogo completo.
 */

require_once __DIR__ . '/includes/risorse.php';

$categorie = catalogo_categorie($pdo);
$categoriaRichiesta = isset($_GET['categoria']) ? trim((string) $_GET['categoria']) : '';
$categoriaAttiva = null;

foreach ($categorie as $categoria) {
    if ($categoria['slug'] === $categoriaRichiesta) {
        $categoriaAttiva = $categoria;
        break;
    }
}

mostra_pagina('pubbliche/prodotti.php', [
    'titolo' => 'Menu e prezzi - Smash Burger',
    'descrizione' => 'Burger, contorni, bevande e dessert con prezzi e allergeni. Scegli i prodotti e ritirali nella sede che preferisci.',
    'pagina' => 'Prodotti',
    'breadcrumb' => [['Home', url()], ['Prodotti', null]],
    'categorie' => $categorie,
    'categoriaAttiva' => $categoriaAttiva,
    'prodotti' => catalogo_prodotti($pdo, $categoriaAttiva['slug'] ?? null),
]);
