<?php
/**
 * Menu pubblico: elenco dei prodotti, con filtro per categoria.
 *
 * Il menu si consulta senza scegliere una sede e senza fare l'accesso: la disponibilita'
 * per sede riguarda l'ordine, non la consultazione.
 */

require_once __DIR__ . '/includes/risorse.php';

richiedi_permesso($pdo);

$categorie = categorie_tutte($pdo);
$categoriaScelta = null;

if (isset($_GET['categoria'])) {
    $slug = slug_richiesto('categoria');
    $categoriaScelta = $slug === null ? null : categoria_per_slug($pdo, $slug);

    // Una categoria che non esiste non è un elenco vuoto: è un indirizzo sbagliato.
    if ($categoriaScelta === null) {
        errore(404);
    }
}

$breadcrumb = [['Home', url()], ['Menu', $categoriaScelta === null ? null : url('menu')]];

if ($categoriaScelta !== null) {
    $breadcrumb[] = [$categoriaScelta['nome'], null];
}

mostra_pagina('pubbliche/menu.php', [
    'titolo' => $categoriaScelta === null
        ? null
        : $categoriaScelta['nome'] . ' - Menu Smash Burger',
    'descrizione' => $categoriaScelta === null
        ? null
        : $categoriaScelta['descrizione'] . ' Prezzi e allergeni di ogni prodotto.',
    'breadcrumb' => $breadcrumb,
    'categorie' => $categorie,
    'categoriaScelta' => $categoriaScelta,
    'prodotti' => prodotti_catalogo($pdo, $categoriaScelta === null ? null : (int) $categoriaScelta['id']),
]);
