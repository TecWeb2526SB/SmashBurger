<?php
/**
 * Controller della sezione ordini del pannello.
 *
 * I filtri arrivano in GET e vengono accettati solo se corrispondono a una sede
 * esistente e a uno stato previsto.
 */

require_once __DIR__ . '/includes/risorse.php';

utente_richiedi_amministratore();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_valido($_POST['token_csrf'] ?? null)) {
        messaggio_imposta('errore', 'Sessione scaduta, riprova a inviare il modulo.');
        vai_a('controllo');
    }

    $ordineId = (int) ($_POST['ordine_id'] ?? 0);

    $esito = match ((string) ($_POST['azione'] ?? '')) {
        'stato' => ordine_cambia_stato($pdo, $ordineId, (string) ($_POST['stato'] ?? '')),
        'pagato' => ordine_segna_pagato($pdo, $ordineId),
        default => ['ok' => false, 'messaggio' => 'Azione non riconosciuta.'],
    };

    messaggio_imposta($esito['ok'] ? 'successo' : 'errore', $esito['messaggio']);
    vai_a('controllo');
}

$sedi = sedi_tutte($pdo);
$sedeFiltro = null;
$statoFiltro = null;

foreach ($sedi as $sede) {
    if ((int) $sede['id'] === (int) ($_GET['sede'] ?? 0)) {
        $sedeFiltro = (int) $sede['id'];
    }
}

if (in_array((string) ($_GET['stato'] ?? ''), ordine_stati(), true)) {
    $statoFiltro = (string) $_GET['stato'];
}

mostra_pagina('controllo/ordini.php', [
    'titolo' => 'Ordini - Pannello di controllo',
    'pagina' => 'Controllo',
    'breadcrumb' => [['Home', url()], ['Controllo', null]],
    'sezione' => 'Ordini',
    'sedi' => $sedi,
    'stati' => ordine_stati(),
    'sedeFiltro' => $sedeFiltro,
    'statoFiltro' => $statoFiltro,
    'ordini' => ordini_tutti($pdo, $sedeFiltro, $statoFiltro),
]);
