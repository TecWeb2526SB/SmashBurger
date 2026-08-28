<?php
/**
 * Creazione e lettura degli ordini.
 *
 * Il ritiro avviene nella giornata corrente: gli orari proposti partono da adesso più
 * i minuti di preparazione e si fermano alla chiusura della sede.
 */

// Distanza in minuti fra un orario di ritiro e il successivo.
define('PASSO_RITIRO_MINUTI', 15);

/**
 * Restituisce gli orari di ritiro ancora disponibili oggi per una sede, nel formato
 * HH:MM. Se la sede è chiusa o l'orario di chiusura è passato restituisce un elenco
 * vuoto.
 */
function ordine_orari_disponibili(PDO $pdo, int $sedeId): array
{
    $query = $pdo->prepare(
        'SELECT apertura, chiusura, chiuso FROM orari_sedi
         WHERE sede_id = :sede AND giorno = :giorno'
    );
    $query->execute(['sede' => $sedeId, 'giorno' => (int) date('N')]);
    $fascia = $query->fetch();

    if ($fascia === false || (int) $fascia['chiuso'] === 1) {
        return [];
    }

    $apertura = strtotime(date('Y-m-d') . ' ' . $fascia['apertura']);
    $chiusura = strtotime(date('Y-m-d') . ' ' . $fascia['chiusura']);
    $primo = time() + MINUTI_PREPARAZIONE * 60;

    $inizio = max($apertura, $primo);
    $passo = PASSO_RITIRO_MINUTI * 60;
    // L'orario viene portato al multiplo successivo del passo.
    $inizio = (int) (ceil($inizio / $passo) * $passo);

    $orari = [];
    for ($istante = $inizio; $istante <= $chiusura; $istante += $passo) {
        $orari[] = date('H:i', $istante);
    }

    return $orari;
}

/**
 * Genera il numero mostrato al cliente e usato al banco, nella forma AAAAMMGG-XXXX.
 */
function ordine_numero(): string
{
    return date('Ymd') . '-' . strtoupper(bin2hex(random_bytes(2)));
}

/**
 * Conferma l'ordine a partire dal carrello dell'utente.
 *
 * Copia nelle righe dell'ordine nome e prezzo correnti, registra il pagamento simulato e
 * svuota il carrello. Le scritture avvengono in una transazione, così un errore non
 * lascia un ordine senza righe.
 *
 * @param string $orario  orario di ritiro nel formato HH:MM
 * @return array{ok: bool, messaggio: string, numero?: string}
 */
function ordine_crea(PDO $pdo, int $utenteId, int $sedeId, string $orario, string $metodoPagamento): array
{
    if (!in_array($metodoPagamento, ['carta', 'contanti'], true)) {
        return ['ok' => false, 'messaggio' => 'Metodo di pagamento non valido.'];
    }

    if (sede_per_id($pdo, $sedeId) === null) {
        return ['ok' => false, 'messaggio' => 'Sede non valida.'];
    }

    if (!in_array($orario, ordine_orari_disponibili($pdo, $sedeId), true)) {
        return ['ok' => false, 'messaggio' => 'Orario di ritiro non più disponibile.'];
    }

    $riepilogo = carrello_riepilogo($pdo, $utenteId);

    if ($riepilogo['righe'] === []) {
        return ['ok' => false, 'messaggio' => 'Il carrello è vuoto.'];
    }

    foreach ($riepilogo['righe'] as $riga) {
        if ((int) $riga['disponibile'] !== 1) {
            return ['ok' => false, 'messaggio' => 'Il prodotto ' . $riga['nome'] . ' non è più disponibile.'];
        }
    }

    $numero = ordine_numero();

    $pdo->beginTransaction();

    try {
        $inserimento = $pdo->prepare(
            'INSERT INTO ordini (utente_id, sede_id, numero, ritiro_previsto, metodo_pagamento,
                                 stato_pagamento, totale_centesimi)
             VALUES (:utente, :sede, :numero, :ritiro, :metodo, :stato_pagamento, :totale)'
        );
        $inserimento->execute([
            'utente' => $utenteId,
            'sede' => $sedeId,
            'numero' => $numero,
            'ritiro' => date('Y-m-d') . ' ' . $orario . ':00',
            'metodo' => $metodoPagamento,
            'stato_pagamento' => $metodoPagamento === 'carta' ? 'pagato' : 'da pagare',
            'totale' => $riepilogo['totale_centesimi'],
        ]);

        $ordineId = (int) $pdo->lastInsertId();

        $riga = $pdo->prepare(
            'INSERT INTO righe_ordine (ordine_id, prodotto_id, nome_prodotto, quantita, prezzo_centesimi)
             VALUES (:ordine, :prodotto, :nome, :quantita, :prezzo)'
        );

        foreach ($riepilogo['righe'] as $prodotto) {
            $riga->execute([
                'ordine' => $ordineId,
                'prodotto' => (int) $prodotto['prodotto_id'],
                'nome' => $prodotto['nome'],
                'quantita' => (int) $prodotto['quantita'],
                'prezzo' => (int) $prodotto['prezzo_centesimi'],
            ]);
        }

        carrello_svuota($pdo, $utenteId);
        $pdo->commit();
    } catch (PDOException $errore) {
        $pdo->rollBack();
        throw $errore;
    }

    return ['ok' => true, 'messaggio' => 'Ordine confermato.', 'numero' => $numero];
}

/**
 * Restituisce gli ordini di un utente, dal più recente.
 */
function ordini_utente(PDO $pdo, int $utenteId): array
{
    $query = $pdo->prepare(
        'SELECT o.id, o.numero, o.ritiro_previsto, o.stato, o.metodo_pagamento,
                o.stato_pagamento, o.totale_centesimi, o.creato_il, s.citta
         FROM ordini o
         INNER JOIN sedi s ON s.id = o.sede_id
         WHERE o.utente_id = :utente
         ORDER BY o.creato_il DESC'
    );
    $query->execute(['utente' => $utenteId]);

    return $query->fetchAll();
}

/**
 * Restituisce un ordine dell'utente indicato a partire dal numero, con le sue righe.
 * Il vincolo sull'utente impedisce di leggere l'ordine di qualcun altro.
 */
function ordine_per_numero(PDO $pdo, string $numero, int $utenteId): ?array
{
    $query = $pdo->prepare(
        'SELECT o.*, s.nome AS sede_nome, s.citta, s.indirizzo, s.cap, s.provincia, s.note_ritiro
         FROM ordini o
         INNER JOIN sedi s ON s.id = o.sede_id
         WHERE o.numero = :numero AND o.utente_id = :utente'
    );
    $query->execute(['numero' => $numero, 'utente' => $utenteId]);
    $ordine = $query->fetch();

    if ($ordine === false) {
        return null;
    }

    $righe = $pdo->prepare(
        'SELECT nome_prodotto, quantita, prezzo_centesimi,
                quantita * prezzo_centesimi AS subtotale_centesimi
         FROM righe_ordine WHERE ordine_id = :ordine ORDER BY id'
    );
    $righe->execute(['ordine' => (int) $ordine['id']]);
    $ordine['righe'] = $righe->fetchAll();

    return $ordine;
}

/**
 * Elenca gli stati che un ordine può assumere, nell'ordine in cui si susseguono.
 */
function ordine_stati(): array
{
    return ['ricevuto', 'in preparazione', 'pronto', 'ritirato', 'annullato'];
}

/**
 * Restituisce gli ordini per il pannello, con i filtri opzionali su sede e stato.
 */
function ordini_tutti(PDO $pdo, ?int $sedeId = null, ?string $stato = null): array
{
    $sql = 'SELECT o.id, o.numero, o.ritiro_previsto, o.stato, o.metodo_pagamento,
                   o.stato_pagamento, o.totale_centesimi, o.creato_il,
                   s.citta, u.nome_utente
            FROM ordini o
            INNER JOIN sedi s ON s.id = o.sede_id
            INNER JOIN utenti u ON u.id = o.utente_id';
    $condizioni = [];
    $parametri = [];

    if ($sedeId !== null) {
        $condizioni[] = 'o.sede_id = :sede';
        $parametri['sede'] = $sedeId;
    }

    if ($stato !== null) {
        $condizioni[] = 'o.stato = :stato';
        $parametri['stato'] = $stato;
    }

    if ($condizioni !== []) {
        $sql .= ' WHERE ' . implode(' AND ', $condizioni);
    }

    $sql .= ' ORDER BY o.ritiro_previsto DESC';

    $query = $pdo->prepare($sql);
    $query->execute($parametri);

    return $query->fetchAll();
}

/**
 * Elenca gli stati possibili del pagamento.
 */
function ordine_stati_pagamento(): array
{
    return ['da pagare', 'pagato'];
}

/**
 * Aggiorna insieme stato dell'ordine e stato del pagamento.
 *
 * @return array{ok: bool, messaggio: string}
 */
function ordine_aggiorna(PDO $pdo, int $ordineId, string $stato, string $statoPagamento): array
{
    if (!in_array($stato, ordine_stati(), true)) {
        return ['ok' => false, 'messaggio' => 'Stato non valido.'];
    }

    if (!in_array($statoPagamento, ordine_stati_pagamento(), true)) {
        return ['ok' => false, 'messaggio' => 'Stato del pagamento non valido.'];
    }

    $query = $pdo->prepare(
        'UPDATE ordini SET stato = :stato, stato_pagamento = :pagamento WHERE id = :id'
    );
    $query->execute(['stato' => $stato, 'pagamento' => $statoPagamento, 'id' => $ordineId]);

    if ($query->rowCount() === 0) {
        return ['ok' => true, 'messaggio' => 'Nessuna modifica da salvare.'];
    }

    return ['ok' => true, 'messaggio' => 'Ordine aggiornato.'];
}
