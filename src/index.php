<?php
/**
 * Controller della home.
 */

require_once __DIR__ . '/includes/risorse.php';

mostra_pagina('pubbliche/home.php', [
    'titolo' => 'Smash Burger: ordina online e ritira in sede',
    'descrizione' => 'Hamburger smash preparati al momento nelle sedi di Padova, Treviso, Vicenza e Udine. Ordina online e scegli l\'orario di ritiro.',
    'pagina' => 'Home',
    'categorie' => catalogo_categorie($pdo),
    'sedi' => sedi_tutte($pdo),
]);
