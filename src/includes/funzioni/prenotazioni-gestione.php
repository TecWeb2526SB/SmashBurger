<?php
/**
 * Prenotazioni viste dal pannello di controllo.
 *
 * Sta in un file separato da prenotazioni.php, che serve a chi prenota: qui ci sono
 * le operazioni riservate a manager e amministratore.
 */

/**
 * Prenotazioni di una sede, o di tutte le sedi per l'amministratore.
 *
 * @param int|null $sedeId null per vedere ogni sede
 */
function prenotazioni_da_gestire(PDO $pdo, ?int $sedeId): array
{
    $sql = 'SELECT p.*, s.citta, u.nome, u.cognome, u.email
              FROM prenotazioni p
              JOIN sedi s ON s.id = p.sede_id
              JOIN utenti u ON u.id = p.utente_id';

    if ($sedeId !== null) {
        $sql .= ' WHERE p.sede_id = :sede';
    }

    $sql .= ' ORDER BY p.data DESC, p.ora_inizio DESC';

    $query = $pdo->prepare($sql);
    $query->execute($sedeId === null ? [] : [':sede' => $sedeId]);

    return $query->fetchAll();
}

/**
 * Cambia lo stato di una prenotazione.
 *
 * Il vincolo di sede sta nella query: un manager non puo' toccare le prenotazioni di
 * un'altra sede nemmeno conoscendone l'identificativo.
 *
 * @param int|null $sedeId sede a cui il chiamante e' limitato, null per l'amministratore
 */
function prenotazione_cambia_stato(PDO $pdo, int $id, string $stato, ?int $sedeId): array
{
    if (!in_array($stato, stati_prenotazione(), true)) {
        return ['ok' => false, 'messaggio' => 'Stato non riconosciuto.'];
    }

    $sql = 'UPDATE prenotazioni SET stato = :stato WHERE id = :id';
    $parametri = [':stato' => $stato, ':id' => $id];

    if ($sedeId !== null) {
        $sql .= ' AND sede_id = :sede';
        $parametri[':sede'] = $sedeId;
    }

    $query = $pdo->prepare($sql);
    $query->execute($parametri);

    if ($query->rowCount() === 0) {
        return ['ok' => false, 'messaggio' => 'La prenotazione non esiste o non e di questa sede.'];
    }

    return ['ok' => true, 'messaggio' => 'Prenotazione aggiornata.'];
}
