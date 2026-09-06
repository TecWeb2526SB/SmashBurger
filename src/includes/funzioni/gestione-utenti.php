<?php
/**
 * Account visti dal pannello dell'amministratore: ruoli, attivazione, cancellazione.
 *
 * Promuovere qualcuno a manager significa assegnargli una sede, e una sede ha al massimo
 * un manager: il vincolo di unicita' sta nel database, qui si spiega perchè fallisce.
 */

/**
 * Ruoli assegnabili dal pannello.
 */
function ruoli_assegnabili(): array
{
    return ['cliente', 'manager', 'amministratore'];
}

/**
 * Elenco degli account con il conteggio degli ordini e la sede eventualmente gestita.
 */
function utenti_elenco(PDO $pdo): array
{
    return $pdo->query(
        'SELECT u.*, s.citta AS sede_citta, s.id AS sede_id,
                (SELECT COUNT(*) FROM ordini o WHERE o.utente_id = u.id) AS ordini
           FROM utenti u
           LEFT JOIN sedi s ON s.manager_id = u.id
          ORDER BY u.ruolo, u.nome_utente'
    )->fetchAll();
}

/**
 * Cambia il ruolo di un account e la sede affidata.
 *
 * Il ruolo di manager esiste solo insieme a una sede: promuovere qualcuno senza indicarla
 * lascerebbe un manager che non ha niente da gestire, quindi la sede e' obbligatoria. La
 * sede scelta dev'essere libera, perche' ne ha al massimo uno. Chi lascia il ruolo di
 * manager libera la sede che aveva.
 */
function utente_cambia_ruolo(PDO $pdo, int $utenteId, string $ruolo, ?int $sedeId): array
{
    if (!in_array($ruolo, ruoli_assegnabili(), true)) {
        return ['ok' => false, 'messaggio' => 'Ruolo non riconosciuto: ricarica la pagina e scegli un ruolo fra quelli proposti.'];
    }

    if ($ruolo === 'manager' && $sedeId === null) {
        return ['ok' => false, 'messaggio' => 'Scegli la sede da affidare a questo manager.'];
    }

    $pdo->beginTransaction();

    try {
        // Chi lascia il ruolo di manager libera la propria sede.
        $pdo->prepare('UPDATE sedi SET manager_id = NULL WHERE manager_id = :utente')
            ->execute([':utente' => $utenteId]);

        $pdo->prepare('UPDATE utenti SET ruolo = :ruolo WHERE id = :id')
            ->execute([':ruolo' => $ruolo, ':id' => $utenteId]);

        if ($ruolo === 'manager') {
            $assegna = $pdo->prepare(
                'UPDATE sedi SET manager_id = :utente WHERE id = :sede AND manager_id IS NULL'
            );
            $assegna->execute([':utente' => $utenteId, ':sede' => $sedeId]);

            if ($assegna->rowCount() === 0) {
                $pdo->rollBack();

                return ['ok' => false, 'messaggio' => 'Quella sede ha già un manager: liberala prima.'];
            }
        }

        $pdo->commit();

        return ['ok' => true, 'messaggio' => 'Ruolo aggiornato.'];
    } catch (PDOException $errore) {
        $pdo->rollBack();
        error_log('Cambio ruolo non riuscito: ' . $errore->getMessage());

        return ['ok' => false, 'messaggio' => 'Non siamo riusciti a cambiare il ruolo.'];
    }
}

/**
 * Attiva o disattiva un account.
 *
 * Un account disattivato non puo' piu' accedere, e la sessione eventualmente aperta
 * cade alla prima richiesta, perchè il ruolo viene riletto ogni volta.
 */
function utente_cambia_stato(PDO $pdo, int $utenteId, bool $attivo): array
{
    $pdo->prepare('UPDATE utenti SET attivo = :attivo WHERE id = :id')
        ->execute([':attivo' => $attivo ? 1 : 0, ':id' => $utenteId]);

    return [
        'ok' => true,
        'messaggio' => $attivo ? 'Account riattivato.' : 'Account disattivato.',
    ];
}

/**
 * Cancella un account dal pannello.
 *
 * Chi cancella non puo' cancellare se stesso: per il proprio account esiste il profilo,
 * dove la conferma spiega che cosa si perde.
 */
function utente_cancella(PDO $pdo, int $utenteId, int $utenteCorrente): array
{
    if ($utenteId === $utenteCorrente) {
        return ['ok' => false, 'messaggio' => 'Il tuo account si cancella dal profilo.'];
    }

    $pdo->prepare('DELETE FROM utenti WHERE id = :id')->execute([':id' => $utenteId]);

    return ['ok' => true, 'messaggio' => 'Account cancellato.'];
}
