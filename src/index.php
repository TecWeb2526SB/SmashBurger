<?php
/**
 * Home: presenta il servizio, le categorie del menu e le sedi.
 */

require_once __DIR__ . '/includes/risorse.php';

richiedi_permesso($pdo);

mostra_pagina('pubbliche/home.php', [
    'categorie' => categorie_tutte($pdo),
    'sedi' => sedi_attive($pdo),
]);
