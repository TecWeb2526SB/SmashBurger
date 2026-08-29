<?php
/**
 * Controller della home.
 */

require_once __DIR__ . '/includes/risorse.php';

$categorie = catalogo_categorie($pdo);
$categoriaScelta = null;

// La categoria arriva dal collegamento nella home e serve solo a mostrarne la
// descrizione: un valore non previsto viene ignorato.
foreach ($categorie as $categoria) {
    if ($categoria['slug'] === (string) ($_GET['categoria'] ?? '')) {
        $categoriaScelta = $categoria;
    }
}

mostra_pagina('pubbliche/home.php', [
    'titolo' => 'Smash Burger: ordina online e ritira in sede',
    'descrizione' => 'Hamburger smash preparati al momento nelle sedi di Padova, Treviso, Vicenza e Udine. Ordina online e scegli l\'orario di ritiro.',
    'pagina' => 'Home',
    'categorie' => $categorie,
    'categoriaScelta' => $categoriaScelta,
    'sedi' => sedi_tutte($pdo),
]);
