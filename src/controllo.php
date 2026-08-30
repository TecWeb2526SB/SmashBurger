<?php
/**
 * Pannello di controllo, sezione ordini.
 *
 * Un manager vede solo la propria sede, l'amministratore tutte con un filtro. Il limite
 * di sede viene passato a ogni funzione di dominio e finisce nella query.
 */

require_once __DIR__ . '/includes/risorse.php';

richiedi_permesso($pdo);

$sedeId = sede_limite($pdo);

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
    richiedi_post_valido();

    $ordineId = identificativo($_POST, 'ordine_id');

    if ($ordineId === null) {
        errore(403);
    }

    $esito = ordine_cambia_stato($pdo, $ordineId, (string) ($_POST['stato'] ?? ''), $sedeId);

    if ($esito['ok'] && is_string($_POST['stato_pagamento'] ?? null)) {
        $esito = ordine_cambia_pagamento($pdo, $ordineId, $_POST['stato_pagamento'], $sedeId);
    }

    messaggio_imposta($esito['ok'] ? 'successo' : 'errore', $esito['messaggio']);
    vai_a('controllo');
}

$filtroSede = $sedeId === null ? identificativo($_GET, 'sede') : null;
$filtroStato = is_string($_GET['stato'] ?? null) ? $_GET['stato'] : '';
$serie = incasso_per_giorno($pdo, $sedeId);

mostra_pagina('controllo/ordini.php', [
    'breadcrumb' => [['Home', url()], ['Ordini', null]],
    'ordini' => ordini_da_gestire($pdo, $sedeId, $filtroSede, $filtroStato),
    'sedi' => sedi_tutte($pdo),
    'sedeCorrente' => $sedeId === null ? null : sede_per_id($pdo, $sedeId),
    'filtroSede' => $filtroSede,
    'filtroStato' => $filtroStato,
    'stati' => stati_ordine(),
    'statiPagamento' => stati_pagamento(),
    'serie' => $serie,
    'incasso' => incasso_totale($serie),
]);
