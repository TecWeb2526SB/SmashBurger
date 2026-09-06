<?php
/**
 * Conferma dell'ordine: ritiro o consegna, poi metodo di pagamento.
 *
 * Le due scelte stanno in due riquadri della stessa pagina. Al termine il cliente viene
 * mandato allo storico dei propri ordini, da cui apre la ricevuta.
 */

require_once __DIR__ . '/includes/risorse.php';

richiedi_permesso($pdo);

$utente = utente_corrente($pdo);
$utenteId = (int) $utente['id'];
$carrello = carrello_corrente($pdo, $utenteId);

if ($carrello === null) {
    messaggio_imposta('errore', 'Il carrello è vuoto o scaduto: ricomincia dalla sede.');
    vai_a('carrello');
}

$righe = carrello_righe($pdo, (int) $carrello['id'], (int) $carrello['sede_id']);

if ($righe === []) {
    messaggio_imposta('errore', 'Aggiungi almeno un prodotto prima di continuare.');
    vai_a('carrello');
}

$profilo = utente_completo($pdo, $utenteId);
$slot = orari_ritiro_disponibili($pdo, (int) $carrello['sede_id']);
$errori = [];

$valori = [
    'modalita' => $slot === [] ? 'domicilio' : 'ritiro',
    'ritiro_previsto' => (string) array_key_first($slot),
    'metodo_pagamento' => (string) ($profilo['metodo_pagamento_preferito'] ?? 'carta'),
    'indirizzo' => (string) ($profilo['indirizzo'] ?? ''),
    'citta' => (string) ($profilo['citta'] ?? ''),
    'provincia' => (string) ($profilo['provincia'] ?? ''),
    'cap' => (string) ($profilo['cap'] ?? ''),
    'paese' => (string) ($profilo['paese'] ?? 'Italia'),
    'telefono' => (string) ($profilo['telefono'] ?? ''),
];

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
    richiedi_post_valido();

    foreach (array_keys($valori) as $campo) {
        if (is_string($_POST[$campo] ?? null)) {
            $valori[$campo] = $_POST[$campo];
        }
    }

    if (!in_array($valori['modalita'], ['ritiro', 'domicilio'], true)) {
        $errori['modalita'] = 'Scegli fra ritiro in sede e consegna a domicilio.';
    }

    if (!in_array($valori['metodo_pagamento'], ['carta', 'contanti'], true)) {
        $errori['metodo_pagamento'] = 'Scegli un metodo di pagamento.';
    }

    if ($valori['modalita'] === 'ritiro' && !array_key_exists($valori['ritiro_previsto'], $slot)) {
        $errori['ritiro_previsto'] = 'Scegli un orario di ritiro fra quelli proposti.';
    }

    if ($valori['modalita'] === 'domicilio') {
        $errori += indirizzo_errori($valori);
    }

    if ($errori === []) {
        $esito = ordine_crea($pdo, $carrello, $righe, $valori);

        messaggio_imposta($esito['ok'] ? 'successo' : 'errore', $esito['messaggio']);
        vai_a($esito['ok'] ? 'area-personale' : 'carrello');
    }
}

mostra_pagina('ordine/pagamento.php', [
    'breadcrumb' => [['Home', url()], ['Carrello', url('carrello')], ['Pagamento', null]],
    'carrello' => $carrello,
    'righe' => $righe,
    'totale' => carrello_totale($righe),
    'slot' => $slot,
    'valori' => $valori,
    'errori' => $errori,
]);
