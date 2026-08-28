<?php
/**
 * Carrello dell'utente.
 *
 * Ogni utente ha un solo carrello, creato al primo inserimento e svuotato quando
 * l'ordine viene confermato. Le righe non copiano il prezzo: viene letto dal prodotto,
 * così il totale mostrato è sempre quello corrente.
 */

// Quantità massima ammessa per una singola riga.
define('QUANTITA_MASSIMA', 20);

/**
 * Restituisce l'identificativo del carrello dell'utente, creandolo se non esiste.
 */
function carrello_id(PDO $pdo, int $utenteId): int
{
    $query = $pdo->prepare('SELECT id FROM carrelli WHERE utente_id = :utente');
    $query->execute(['utente' => $utenteId]);
    $id = $query->fetchColumn();

    if ($id !== false) {
        return (int) $id;
    }

    $inserimento = $pdo->prepare('INSERT INTO carrelli (utente_id) VALUES (:utente)');
    $inserimento->execute(['utente' => $utenteId]);

    return (int) $pdo->lastInsertId();
}

/**
 * Restituisce il riepilogo del carrello: righe con i dati del prodotto, numero di
 * articoli, totale in centesimi e sede scelta.
 *
 * @return array{righe: array, articoli: int, totale_centesimi: int, sede_id: int|null}
 */
function carrello_riepilogo(PDO $pdo, int $utenteId): array
{
    $carrelloId = carrello_id($pdo, $utenteId);

    $query = $pdo->prepare(
        'SELECT r.id, r.quantita, p.id AS prodotto_id, p.nome, p.slug, p.immagine,
                p.prezzo_centesimi, p.disponibile,
                r.quantita * p.prezzo_centesimi AS subtotale_centesimi
         FROM righe_carrello r
         INNER JOIN prodotti p ON p.id = r.prodotto_id
         WHERE r.carrello_id = :carrello
         ORDER BY p.nome'
    );
    $query->execute(['carrello' => $carrelloId]);
    $righe = $query->fetchAll();

    $sede = $pdo->prepare('SELECT sede_id FROM carrelli WHERE id = :carrello');
    $sede->execute(['carrello' => $carrelloId]);
    $sedeId = $sede->fetchColumn();

    $articoli = 0;
    $totale = 0;
    foreach ($righe as $riga) {
        $articoli += (int) $riga['quantita'];
        $totale += (int) $riga['subtotale_centesimi'];
    }

    return [
        'righe' => $righe,
        'articoli' => $articoli,
        'totale_centesimi' => $totale,
        'sede_id' => $sedeId === false || $sedeId === null ? null : (int) $sedeId,
    ];
}

/**
 * Aggiunge un prodotto al carrello o ne aumenta la quantità.
 *
 * @return array{ok: bool, messaggio: string}
 */
function carrello_aggiungi(PDO $pdo, int $utenteId, int $prodottoId, int $quantita): array
{
    if ($quantita < 1 || $quantita > QUANTITA_MASSIMA) {
        return ['ok' => false, 'messaggio' => 'Quantità non valida.'];
    }

    $prodotto = $pdo->prepare('SELECT nome, disponibile FROM prodotti WHERE id = :id');
    $prodotto->execute(['id' => $prodottoId]);
    $dati = $prodotto->fetch();

    if ($dati === false) {
        return ['ok' => false, 'messaggio' => 'Il prodotto richiesto non esiste.'];
    }

    if ((int) $dati['disponibile'] !== 1) {
        return ['ok' => false, 'messaggio' => 'Il prodotto ' . $dati['nome'] . ' non è disponibile.'];
    }

    $carrelloId = carrello_id($pdo, $utenteId);

    // La quantità si somma a quella già presente, entro il limite per riga.
    $inserimento = $pdo->prepare(
        'INSERT INTO righe_carrello (carrello_id, prodotto_id, quantita)
         VALUES (:carrello, :prodotto, :quantita)
         ON DUPLICATE KEY UPDATE quantita = LEAST(quantita + :aggiunta, :massimo)'
    );
    $inserimento->execute([
        'carrello' => $carrelloId,
        'prodotto' => $prodottoId,
        'quantita' => $quantita,
        'aggiunta' => $quantita,
        'massimo' => QUANTITA_MASSIMA,
    ]);

    return ['ok' => true, 'messaggio' => $dati['nome'] . ' aggiunto al carrello.'];
}

/**
 * Applica le quantità inviate dal carrello.
 *
 * Le righe vengono scritte in una transazione: o valgono tutte le nuove quantità o non
 * ne vale nessuna, così un valore sbagliato non lascia il carrello a metà strada.
 *
 * @param array $quantita  quantità indicizzate per identificativo di riga
 * @return array{ok: bool, messaggio: string}
 */
function carrello_aggiorna_righe(PDO $pdo, int $utenteId, array $quantita): array
{
    if ($quantita === []) {
        return ['ok' => false, 'messaggio' => 'Nessuna quantità da aggiornare.'];
    }

    $pdo->beginTransaction();

    foreach ($quantita as $rigaId => $valore) {
        $esito = carrello_aggiorna_quantita($pdo, $utenteId, (int) $rigaId, (int) $valore);

        if (!$esito['ok']) {
            $pdo->rollBack();

            return $esito;
        }
    }

    $pdo->commit();

    return ['ok' => true, 'messaggio' => 'Carrello aggiornato.'];
}

/**
 * Cambia la quantità di una riga, oppure la toglie se la quantità è zero.
 *
 * L'appartenenza della riga al carrello di chi la modifica viene verificata prima della
 * scrittura: il numero di righe toccate dall'aggiornamento non basta, perché vale zero
 * anche quando la quantità inviata è identica a quella già salvata.
 *
 * @return array{ok: bool, messaggio: string}
 */
function carrello_aggiorna_quantita(PDO $pdo, int $utenteId, int $rigaId, int $quantita): array
{
    if ($quantita < 0 || $quantita > QUANTITA_MASSIMA) {
        return ['ok' => false, 'messaggio' => 'Quantità non valida.'];
    }

    $carrelloId = carrello_id($pdo, $utenteId);
    $verifica = $pdo->prepare('SELECT id FROM righe_carrello WHERE id = :riga AND carrello_id = :carrello');
    $verifica->execute(['riga' => $rigaId, 'carrello' => $carrelloId]);

    if ($verifica->fetch() === false) {
        return ['ok' => false, 'messaggio' => 'Riga del carrello non trovata.'];
    }

    if ($quantita === 0) {
        $pdo->prepare('DELETE FROM righe_carrello WHERE id = :riga AND carrello_id = :carrello')
            ->execute(['riga' => $rigaId, 'carrello' => $carrelloId]);

        return ['ok' => true, 'messaggio' => 'Prodotto tolto dal carrello.'];
    }

    $pdo->prepare('UPDATE righe_carrello SET quantita = :quantita WHERE id = :riga AND carrello_id = :carrello')
        ->execute(['quantita' => $quantita, 'riga' => $rigaId, 'carrello' => $carrelloId]);

    return ['ok' => true, 'messaggio' => 'Quantità aggiornata.'];
}

/**
 * Toglie una riga dal carrello dell'utente.
 *
 * @return array{ok: bool, messaggio: string}
 */
function carrello_rimuovi(PDO $pdo, int $utenteId, int $rigaId): array
{
    return carrello_aggiorna_quantita($pdo, $utenteId, $rigaId, 0);
}

/**
 * Svuota il carrello dell'utente.
 */
function carrello_svuota(PDO $pdo, int $utenteId): void
{
    $carrelloId = carrello_id($pdo, $utenteId);
    $pdo->prepare('DELETE FROM righe_carrello WHERE carrello_id = :carrello')
        ->execute(['carrello' => $carrelloId]);
}

/**
 * Salva sul carrello la sede scelta per il ritiro.
 */
function carrello_imposta_sede(PDO $pdo, int $utenteId, int $sedeId): void
{
    $carrelloId = carrello_id($pdo, $utenteId);
    $pdo->prepare('UPDATE carrelli SET sede_id = :sede WHERE id = :carrello')
        ->execute(['sede' => $sedeId, 'carrello' => $carrelloId]);
}
