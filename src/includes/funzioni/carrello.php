<?php
/**
 * Carrello in corso.
 *
 * Il carrello non è persistente: si elimina quando l'ordine si conclude e scade da solo
 * dopo MINUTI_CARRELLO minuti di inattivita'. La scadenza si controlla quando il
 * carrello viene richiesto, non con un processo periodico.
 *
 * Il prezzo non viene copiato nelle righe: si legge dal prodotto, cosi' il totale
 * mostrato è sempre quello corrente.
 */

/**
 * Carrello aperto di una persona, oppure null.
 *
 * Un carrello scaduto viene eliminato qui e conta come inesistente.
 */
function carrello_corrente(PDO $pdo, int $utenteId): ?array
{
    $query = $pdo->prepare(
        'SELECT c.*, s.slug AS sede_slug, s.citta, s.nome AS sede_nome
           FROM carrelli c
           JOIN sedi s ON s.id = c.sede_id
          WHERE c.utente_id = :utente'
    );
    $query->execute([':utente' => $utenteId]);
    $carrello = $query->fetch();

    if ($carrello === false) {
        return null;
    }

    if (strtotime($carrello['aggiornato_il']) < time() - MINUTI_CARRELLO * 60) {
        carrello_elimina($pdo, (int) $carrello['id']);

        return null;
    }

    return $carrello;
}

/**
 * Apre un carrello sulla sede indicata, o ne cambia la sede.
 *
 * Cambiando sede le righe vengono svuotate: la disponibilita' è specifica per sede,
 * quindi un carrello riempito altrove non avrebbe piu' significato.
 */
function carrello_apri(PDO $pdo, int $utenteId, int $sedeId): int
{
    $esistente = carrello_corrente($pdo, $utenteId);

    if ($esistente !== null && (int) $esistente['sede_id'] === $sedeId) {
        carrello_tocca($pdo, (int) $esistente['id']);

        return (int) $esistente['id'];
    }

    if ($esistente !== null) {
        carrello_elimina($pdo, (int) $esistente['id']);
    }

    $pdo->prepare('INSERT INTO carrelli (utente_id, sede_id) VALUES (:utente, :sede)')
        ->execute([':utente' => $utenteId, ':sede' => $sedeId]);

    return (int) $pdo->lastInsertId();
}

/**
 * Aggiorna il momento dell'ultima attivita'.
 *
 * Le modifiche alle righe non toccano la riga del carrello, quindi la scadenza va
 * rinviata esplicitamente a ogni operazione.
 */
function carrello_tocca(PDO $pdo, int $carrelloId): void
{
    $pdo->prepare('UPDATE carrelli SET aggiornato_il = CURRENT_TIMESTAMP WHERE id = :id')
        ->execute([':id' => $carrelloId]);
}

/**
 * Elimina un carrello e, per chiave esterna, le sue righe.
 */
function carrello_elimina(PDO $pdo, int $carrelloId): void
{
    $pdo->prepare('DELETE FROM carrelli WHERE id = :id')->execute([':id' => $carrelloId]);
}

/**
 * Righe del carrello con nome, prezzo e disponibilita' attuale del prodotto.
 */
function carrello_righe(PDO $pdo, int $carrelloId, int $sedeId): array
{
    $query = $pdo->prepare(
        'SELECT r.id, r.prodotto_id, r.quantita, p.nome, p.slug, p.immagine,
                p.prezzo_centesimi,
                (p.prezzo_centesimi * r.quantita) AS totale_riga,
                COALESCE(d.quantita, 0) AS disponibili,
                COALESCE(d.disponibile, 0) AS in_menu
           FROM righe_carrello r
           JOIN prodotti p ON p.id = r.prodotto_id
           LEFT JOIN disponibilita_prodotti d
             ON d.prodotto_id = r.prodotto_id AND d.sede_id = :sede
          WHERE r.carrello_id = :carrello
          ORDER BY p.nome'
    );
    $query->execute([':carrello' => $carrelloId, ':sede' => $sedeId]);

    return $query->fetchAll();
}

/**
 * Somma degli importi delle righe, in centesimi.
 */
function carrello_totale(array $righe): int
{
    $totale = 0;

    foreach ($righe as $riga) {
        $totale += (int) $riga['totale_riga'];
    }

    return $totale;
}

/**
 * Numero di articoli, contando le quantita'.
 */
function carrello_articoli(array $righe): int
{
    $articoli = 0;

    foreach ($righe as $riga) {
        $articoli += (int) $riga['quantita'];
    }

    return $articoli;
}

/**
 * Aggiunge una unita' di un prodotto, o ne aumenta la quantita'.
 *
 * Il controllo sulla disponibilita' è una cortesia verso chi ordina: quello che decide
 * davvero avviene alla conferma dell'ordine, in transazione.
 *
 * @return array ['ok' => bool, 'messaggio' => string]
 */
function carrello_aggiungi(PDO $pdo, int $carrelloId, int $sedeId, int $prodottoId, int $quantita = 1): array
{
    $disponibili = quantita_disponibile($pdo, $sedeId, $prodottoId);

    if ($disponibili === 0) {
        return ['ok' => false, 'messaggio' => 'Questo prodotto non è disponibile in questa sede.'];
    }

    $query = $pdo->prepare(
        'SELECT quantita FROM righe_carrello WHERE carrello_id = :carrello AND prodotto_id = :prodotto'
    );
    $query->execute([':carrello' => $carrelloId, ':prodotto' => $prodottoId]);
    $attuale = (int) ($query->fetchColumn() ?: 0);

    $nuova = min($attuale + $quantita, $disponibili, QUANTITA_MASSIMA);

    if ($nuova === $attuale) {
        return ['ok' => false, 'messaggio' => 'Hai già raggiunto la quantità massima per questo prodotto.'];
    }

    return carrello_imposta_quantita($pdo, $carrelloId, $sedeId, $prodottoId, $nuova);
}

/**
 * Imposta la quantita' di una riga; a zero la riga viene tolta.
 */
function carrello_imposta_quantita(PDO $pdo, int $carrelloId, int $sedeId, int $prodottoId, int $quantita): array
{
    if ($quantita <= 0) {
        return carrello_togli($pdo, $carrelloId, $prodottoId);
    }

    $disponibili = quantita_disponibile($pdo, $sedeId, $prodottoId);

    if ($disponibili === 0) {
        return ['ok' => false, 'messaggio' => 'Questo prodotto non è disponibile in questa sede.'];
    }

    $quantita = min($quantita, $disponibili, QUANTITA_MASSIMA);

    $pdo->prepare(
        'INSERT INTO righe_carrello (carrello_id, prodotto_id, quantita)
         VALUES (:carrello, :prodotto, :quantita)
         ON DUPLICATE KEY UPDATE quantita = :quantita_aggiornata'
    )->execute([
        ':carrello' => $carrelloId,
        ':prodotto' => $prodottoId,
        ':quantita' => $quantita,
        ':quantita_aggiornata' => $quantita,
    ]);

    carrello_tocca($pdo, $carrelloId);

    return ['ok' => true, 'messaggio' => 'Carrello aggiornato.'];
}

/**
 * Toglie un prodotto dal carrello.
 */
function carrello_togli(PDO $pdo, int $carrelloId, int $prodottoId): array
{
    $pdo->prepare('DELETE FROM righe_carrello WHERE carrello_id = :carrello AND prodotto_id = :prodotto')
        ->execute([':carrello' => $carrelloId, ':prodotto' => $prodottoId]);

    carrello_tocca($pdo, $carrelloId);

    return ['ok' => true, 'messaggio' => 'Prodotto tolto dal carrello.'];
}

/**
 * Toglie una unita' di un prodotto; a zero la riga sparisce.
 */
function carrello_diminuisci(PDO $pdo, int $carrelloId, int $sedeId, int $prodottoId): array
{
    $query = $pdo->prepare(
        'SELECT quantita FROM righe_carrello WHERE carrello_id = :carrello AND prodotto_id = :prodotto'
    );
    $query->execute([':carrello' => $carrelloId, ':prodotto' => $prodottoId]);
    $attuale = (int) ($query->fetchColumn() ?: 0);

    if ($attuale === 0) {
        return ['ok' => false, 'messaggio' => 'Questo prodotto non è nel carrello: aggiorna la pagina e riprova.'];
    }

    return carrello_imposta_quantita($pdo, $carrelloId, $sedeId, $prodottoId, $attuale - 1);
}

/**
 * Svuota il carrello lasciando aperta la sede scelta.
 */
function carrello_svuota(PDO $pdo, int $carrelloId): array
{
    $pdo->prepare('DELETE FROM righe_carrello WHERE carrello_id = :carrello')
        ->execute([':carrello' => $carrelloId]);

    carrello_tocca($pdo, $carrelloId);

    return ['ok' => true, 'messaggio' => 'Carrello svuotato.'];
}
