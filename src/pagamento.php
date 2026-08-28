<?php
/**
 * Controller della conferma d'ordine: scelta della sede, dell'orario di ritiro e del
 * metodo di pagamento.
 *
 * La scelta della sede viene inviata come azione separata, così gli orari proposti
 * corrispondono sempre alla sede selezionata anche senza JavaScript.
 */

require_once __DIR__ . '/includes/risorse.php';

utente_richiedi_accesso();

$utenteId = (int) utente_corrente()['id'];
$riepilogo = carrello_riepilogo($pdo, $utenteId);

if ($riepilogo['righe'] === []) {
    messaggio_imposta('errore', 'Il carrello è vuoto.');
    vai_a('carrello');
}

$sedi = sedi_tutte($pdo);
$sedeId = $riepilogo['sede_id'] ?? (int) ($sedi[0]['id'] ?? 0);
$errore = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_valido($_POST['token_csrf'] ?? null)) {
        $errore = 'La pagina è rimasta aperta troppo a lungo, riprova.';
    } else {
        $sedeRichiesta = (int) ($_POST['sede_id'] ?? 0);

        if (sede_per_id($pdo, $sedeRichiesta) !== null) {
            $sedeId = $sedeRichiesta;
            carrello_imposta_sede($pdo, $utenteId, $sedeId);
        }

        if ((string) ($_POST['azione'] ?? '') === 'conferma') {
            $esito = ordine_crea(
                $pdo,
                $utenteId,
                $sedeId,
                (string) ($_POST['orario'] ?? ''),
                (string) ($_POST['metodo_pagamento'] ?? '')
            );

            if ($esito['ok']) {
                messaggio_imposta('successo', $esito['messaggio']);
                vai_a('ricevuta', ['numero' => $esito['numero']]);
            }

            $errore = $esito['messaggio'];
        }
    }
}

mostra_pagina('ordine/pagamento.php', [
    'titolo' => 'Conferma ordine - Smash Burger',
    'pagina' => 'Carrello',
    'breadcrumb' => [['Home', url()], ['Carrello', url('carrello')], ['Conferma ordine', null]],
    'sedi' => $sedi,
    'sedeId' => $sedeId,
    'orari' => ordine_orari_disponibili($pdo, $sedeId),
    'righe' => $riepilogo['righe'],
    'totale' => $riepilogo['totale_centesimi'],
    'errore' => $errore,
]);
