<?php
/**
 * Elenco dei prodotti con la loro disponibilita' in una sede.
 *
 * Il manager vede e modifica solo la propria sede. L'amministratore sceglie la sede da
 * un filtro e ha in piu' i collegamenti alla scheda dei dati comuni.
 */

require_once __DIR__ . '/includes/risorse.php';

richiedi_permesso($pdo);

$limite = sede_limite($pdo);
$sedi = sedi_tutte($pdo);
$richiesta = identificativo($_GET, 'sede');

// Come in controllo-sede: al manager che chiede un'altra sede si risponde di no.
if ($limite !== null && $richiesta !== null && $richiesta !== $limite) {
    errore(403);
}

$sedeScelta = $limite ?? ($richiesta ?? (int) ($sedi[0]['id'] ?? 0));

// Un amministratore che chiede una sede inesistente riceve la pagina 404.
$sede = sede_per_id($pdo, $sedeScelta);

if ($sede === null) {
    errore(404);
}

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
    richiedi_post_valido();

    $sedeAzione = identificativo($_POST, 'sede_id');
    $prodottoId = identificativo($_POST, 'prodotto_id');

    if ($limite !== null && $sedeAzione !== $limite) {
        errore(403);
    }

    if ($sedeAzione === null || $prodottoId === null || sede_per_id($pdo, $sedeAzione) === null) {
        errore(403);
    }

    if (isset($_POST['mostra'])) {
        $esito = disponibilita_cambia($pdo, $sedeAzione, $prodottoId, true);
    } elseif (isset($_POST['nascondi'])) {
        $esito = disponibilita_cambia($pdo, $sedeAzione, $prodottoId, false);
    } elseif (isset($_POST['quantita'])) {
        $esito = quantita_imposta($pdo, $sedeAzione, $prodottoId, (int) $_POST['quantita']);
    } else {
        errore(403);
    }

    messaggio_imposta($esito['ok'] ? 'successo' : 'errore', $esito['messaggio']);
    vai_a('controllo-prodotti', $limite === null ? ['sede' => $sedeAzione] : []);
}

mostra_pagina('controllo/prodotti.php', [
    'breadcrumb' => [['Home', url()], ['Ordini', url('controllo')], ['Prodotti', null]],
    'prodotti' => prodotti_in_sede($pdo, (int) $sede['id'], false),
    'sede' => $sede,
    'sedi' => $sedi,
    'limitato' => $limite !== null,
    'amministratore' => ruolo_corrente($pdo) === 'amministratore',
]);
