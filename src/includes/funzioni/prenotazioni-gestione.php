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
 * @param int|null $sedeId sede a cui il chiamante è limitato, null per l'amministratore
 */
function prenotazione_cambia_stato(PDO $pdo, int $id, string $stato, ?int $sedeId): array
{
    if (!in_array($stato, stati_prenotazione(), true)) {
        return ['ok' => false, 'messaggio' => 'Stato non riconosciuto.'];
    }

    $limite = $sedeId === null ? '' : ' AND sede_id = :sede';
    $parametri = [':id' => $id];

    if ($sedeId !== null) {
        $parametri[':sede'] = $sedeId;
    }

    // Come per gli ordini, l'esistenza si verifica con una lettura e non contando le
    // righe toccate: riassegnare lo stato che la prenotazione ha già non ne tocca
    // nessuna, e sarebbe indistinguibile da una prenotazione di un'altra sede.
    $query = $pdo->prepare('SELECT 1 FROM prenotazioni WHERE id = :id' . $limite);
    $query->execute($parametri);

    if ($query->fetchColumn() === false) {
        return ['ok' => false, 'messaggio' => 'La prenotazione non esiste o non è di questa sede.'];
    }

    $aggiorna = $pdo->prepare('UPDATE prenotazioni SET stato = :stato WHERE id = :id' . $limite);
    $aggiorna->execute($parametri + [':stato' => $stato]);

    return ['ok' => true, 'messaggio' => 'Prenotazione aggiornata.'];
}
