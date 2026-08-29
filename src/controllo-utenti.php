<?php
/**
 * Controller della gestione degli account nel pannello.
 *
 * Le operazioni riguardano gli altri account; il proprio si modifica dal profilo.
 */

require_once __DIR__ . '/includes/risorse.php';

utente_richiedi_amministratore();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_valido($_POST['token_csrf'] ?? null)) {
        messaggio_imposta('errore', 'La pagina è rimasta aperta troppo a lungo, riprova.');
        vai_a('controllo-utenti');
    }

    $utenteId = (int) ($_POST['utente_id'] ?? 0);

    if ($utenteId === (int) utente_corrente()['id']) {
        messaggio_imposta('errore', 'Il tuo account si modifica dalla pagina profilo.');
        vai_a('controllo-utenti');
    }

    $esito = match ((string) ($_POST['azione'] ?? '')) {
        'ruolo' => utente_cambia_ruolo($pdo, $utenteId, (string) ($_POST['ruolo'] ?? '')),
        'attiva' => utente_cambia_stato($pdo, $utenteId, true),
        'disattiva' => utente_cambia_stato($pdo, $utenteId, false),
        'cancella' => utenti_amministratori_attivi($pdo, $utenteId) === 0
            ? ['ok' => false, 'messaggio' => 'Deve restare almeno un amministratore attivo.']
            : (function () use ($pdo, $utenteId) {
                utente_cancella($pdo, $utenteId);

                return ['ok' => true, 'messaggio' => 'Account cancellato.'];
            })(),
        default => ['ok' => false, 'messaggio' => 'Azione non riconosciuta.'],
    };

    messaggio_imposta($esito['ok'] ? 'successo' : 'errore', $esito['messaggio']);
    vai_a('controllo-utenti');
}

// Il collegamento porta qui in GET e mostra la conferma; la cancellazione avviene in POST.
$daCancellare = null;
if (isset($_GET['cancella'])) {
    $query = $pdo->prepare('SELECT id, nome_utente FROM utenti WHERE id = :id');
    $query->execute(['id' => (int) $_GET['cancella']]);
    $trovato = $query->fetch();
    $daCancellare = $trovato === false ? null : $trovato;
}

mostra_pagina('controllo/utenti.php', [
    'titolo' => 'Utenti - Pannello di controllo',
    'pagina' => 'Controllo',
    'breadcrumb' => [['Home', url()], ['Controllo', url('controllo')], ['Utenti', null]],
    'sezione' => 'Utenti',
    'utenti' => utenti_tutti($pdo),
    'utenteCorrenteId' => (int) utente_corrente()['id'],
    'daCancellare' => $daCancellare,
]);
