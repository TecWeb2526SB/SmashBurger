<?php
/**
 * Pagina informativa: Privacy.
 */

require_once __DIR__ . '/includes/risorse.php';

richiedi_permesso($pdo);

mostra_pagina('pubbliche/privacy.php', [
    'breadcrumb' => [['Home', url()], ['Privacy', null]],
]);
