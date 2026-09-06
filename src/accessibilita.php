<?php
/**
 * Pagina informativa: Accessibilita.
 */

require_once __DIR__ . '/includes/risorse.php';

richiedi_permesso($pdo);

mostra_pagina('pubbliche/accessibilita.php', [
    'breadcrumb' => [['Home', url()], ['Accessibilità', null]],
]);
