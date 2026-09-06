<?php
/**
 * Elenco delle sedi. Il manager non passa da qui: entra direttamente sulla propria.
 */

require_once __DIR__ . '/includes/risorse.php';

richiedi_permesso($pdo);

mostra_pagina('controllo/sedi.php', [
    'breadcrumb' => [['Home', url()], ['Controllo', url('controllo')], ['Sedi', null]],
    'sedi' => sedi_tutte($pdo),
]);
