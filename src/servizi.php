<?php
/**
 * Pagina informativa: Servizi.
 */

require_once __DIR__ . '/includes/risorse.php';

richiedi_permesso($pdo);

mostra_pagina('pubbliche/servizi.php', [
    'breadcrumb' => [['Home', url()], ['Servizi', null]],
]);
