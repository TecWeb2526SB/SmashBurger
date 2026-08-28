<?php
/**
 * Controller del profilo: modifica dell'email, cambio password e cancellazione
 * dell'account.
 */

require_once __DIR__ . '/includes/risorse.php';

utente_richiedi_accesso();

$utente = utente_corrente();
$utenteId = (int) $utente['id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_valido($_POST['token_csrf'] ?? null)) {
        messaggio_imposta('errore', 'Sessione scaduta, riprova a inviare il modulo.');
        vai_a('profilo');
    }

    switch ((string) ($_POST['azione'] ?? '')) {
        case 'email':
            $esito = utente_aggiorna_email($pdo, $utenteId, trim((string) ($_POST['email'] ?? '')));
            break;

        case 'password':
            $esito = utente_aggiorna_password(
                $pdo,
                $utenteId,
                (string) ($_POST['password_attuale'] ?? ''),
                (string) ($_POST['password_nuova'] ?? '')
            );
            break;

        case 'cancella':
            // Gli account amministratore si gestiscono dal pannello di controllo.
            if (utente_e_amministratore()) {
                $esito = ['ok' => false, 'messaggio' => 'Un account amministratore non può essere cancellato da qui.'];
                break;
            }

            utente_cancella($pdo, $utenteId);
            utente_esci();
            messaggio_imposta('successo', 'Account cancellato.');
            vai_a();

        default:
            $esito = ['ok' => false, 'messaggio' => 'Azione non riconosciuta.'];
    }

    messaggio_imposta($esito['ok'] ? 'successo' : 'errore', $esito['messaggio']);
    vai_a('profilo');
}

mostra_pagina('account/profilo.php', [
    'titolo' => 'Profilo - Smash Burger',
    'pagina' => 'Area personale',
    'breadcrumb' => [['Home', url()], ['Area personale', url('area-personale')], ['Profilo', null]],
    'utente' => $utente,
]);
