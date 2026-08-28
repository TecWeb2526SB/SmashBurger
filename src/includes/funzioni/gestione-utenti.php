<?php
/**
 * Gestione degli account da parte dell'amministratore.
 *
 * Le operazioni riguardano gli account altrui; quelle sul proprio account stanno in
 * utenti.php. Il sistema deve conservare almeno un amministratore attivo, quindi ogni
 * operazione che potrebbe togliere l'ultimo viene rifiutata.
 */

/**
 * Restituisce tutti gli account, con il numero di ordini di ciascuno.
 */
function utenti_tutti(PDO $pdo): array
{
    return $pdo->query(
        'SELECT u.id, u.nome_utente, u.email, u.ruolo, u.attivo, u.creato_il,
                COUNT(o.id) AS ordini
         FROM utenti u
         LEFT JOIN ordini o ON o.utente_id = u.id
         GROUP BY u.id
         ORDER BY u.nome_utente'
    )->fetchAll();
}

/**
 * Cambia il ruolo di un account.
 *
 * Il sistema deve conservare almeno un amministratore attivo: l'ultimo non può essere
 * retrocesso.
 *
 * @return array{ok: bool, messaggio: string}
 */
function utente_cambia_ruolo(PDO $pdo, int $utenteId, string $ruolo): array
{
    if (!in_array($ruolo, ['cliente', 'amministratore'], true)) {
        return ['ok' => false, 'messaggio' => 'Ruolo non valido.'];
    }

    if ($ruolo === 'cliente' && utenti_amministratori_attivi($pdo, $utenteId) === 0) {
        return ['ok' => false, 'messaggio' => 'Deve restare almeno un amministratore attivo.'];
    }

    $pdo->prepare('UPDATE utenti SET ruolo = :ruolo WHERE id = :id')
        ->execute(['ruolo' => $ruolo, 'id' => $utenteId]);

    return ['ok' => true, 'messaggio' => 'Ruolo aggiornato.'];
}

/**
 * Attiva o disattiva un account. Un account disattivato non può accedere.
 *
 * @return array{ok: bool, messaggio: string}
 */
function utente_cambia_stato(PDO $pdo, int $utenteId, bool $attivo): array
{
    if (!$attivo && utenti_amministratori_attivi($pdo, $utenteId) === 0) {
        return ['ok' => false, 'messaggio' => 'Deve restare almeno un amministratore attivo.'];
    }

    $pdo->prepare('UPDATE utenti SET attivo = :attivo WHERE id = :id')
        ->execute(['attivo' => $attivo ? 1 : 0, 'id' => $utenteId]);

    return ['ok' => true, 'messaggio' => $attivo ? 'Account attivato.' : 'Account disattivato.'];
}

/**
 * Conta gli amministratori attivi escludendo un account, per capire se quell'account è
 * l'ultimo rimasto.
 */
function utenti_amministratori_attivi(PDO $pdo, int $esclusoId): int
{
    $query = $pdo->prepare(
        'SELECT COUNT(*) FROM utenti WHERE ruolo = :ruolo AND attivo = 1 AND id <> :id'
    );
    $query->execute(['ruolo' => 'amministratore', 'id' => $esclusoId]);

    return (int) $query->fetchColumn();
}
