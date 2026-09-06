<?php
/**
 * Pagina informativa: Chi siamo.
 */

require_once __DIR__ . '/includes/risorse.php';

richiedi_permesso($pdo);

mostra_pagina('pubbliche/chi-siamo.php', [
    'breadcrumb' => [['Home', url()], ['Chi siamo', null]],
]);
