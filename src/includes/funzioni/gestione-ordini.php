<?php
/**
 * Ordini visti dal pannello di controllo.
 *
 * Ogni funzione riceve $sedeId, cioè la sede a cui chi guarda è limitato: per un
 * manager è la propria, per l'amministratore è null e significa tutte. Il vincolo
 * entra nella query, quindi un manager non puo' toccare gli ordini di un'altra sede
 * nemmeno conoscendone l'identificativo.
 */

/**
 * Ordini da gestire, con i filtri della pagina.
 *
 * @param int|null    $sedeId      sede a cui il chiamante è limitato
 * @param int|null    $filtroSede  sede scelta nel filtro, valida solo per l'amministratore
 * @param string      $filtroStato stato scelto nel filtro, stringa vuota per tutti
 */
function ordini_da_gestire(PDO $pdo, ?int $sedeId, ?int $filtroSede, string $filtroStato): array
{
    $condizioni = [];
    $parametri = [];

    if ($sedeId !== null) {
        $condizioni[] = 'o.sede_id = :limite';
        $parametri[':limite'] = $sedeId;
    } elseif ($filtroSede !== null) {
        $condizioni[] = 'o.sede_id = :filtro_sede';
        $parametri[':filtro_sede'] = $filtroSede;
    }

    if (in_array($filtroStato, stati_ordine(), true)) {
        $condizioni[] = 'o.stato = :stato';
        $parametri[':stato'] = $filtroStato;
    }

    $sql = 'SELECT o.*, s.citta, u.nome_utente
              FROM ordini o
              JOIN sedi s ON s.id = o.sede_id
              JOIN utenti u ON u.id = o.utente_id';

    if ($condizioni !== []) {
        $sql .= ' WHERE ' . implode(' AND ', $condizioni);
    }

    $query = $pdo->prepare($sql . ' ORDER BY o.creato_il DESC');
    $query->execute($parametri);

    return $query->fetchAll();
}

/**
 * Un ordine per il pannello, solo se rientra nella sede a cui si è limitati.
 */
function ordine_per_pannello(PDO $pdo, int $ordineId, ?int $sedeId): ?array
{
    $sql = 'SELECT o.*, s.citta, s.indirizzo AS sede_indirizzo,
                   u.nome_utente, u.nome, u.cognome, u.email
              FROM ordini o
              JOIN sedi s ON s.id = o.sede_id
              JOIN utenti u ON u.id = o.utente_id
             WHERE o.id = :ordine';
    $parametri = [':ordine' => $ordineId];

    if ($sedeId !== null) {
        $sql .= ' AND o.sede_id = :sede';
        $parametri[':sede'] = $sedeId;
    }

    $query = $pdo->prepare($sql);
    $query->execute($parametri);
    $ordine = $query->fetch();

    return $ordine === false ? null : $ordine;
}

/**
 * Cambia lo stato di lavorazione di un ordine.
 *
 * L'annullamento non passa da qui: ha una funzione propria, perchè deve chiedere il
 * motivo e rimettere la merce a disposizione.
 */
function ordine_cambia_stato(PDO $pdo, int $ordineId, string $stato, ?int $sedeId): array
{
    if (!in_array($stato, stati_ordine(), true) || $stato === 'annullato') {
        return ['ok' => false, 'messaggio' => 'Stato non riconosciuto.'];
    }

    return ordine_aggiorna_colonna($pdo, $ordineId, 'stato', $stato, $sedeId);
}

/**
 * Cambia lo stato del pagamento di un ordine.
 */
function ordine_cambia_pagamento(PDO $pdo, int $ordineId, string $stato, ?int $sedeId): array
{
    if (!in_array($stato, stati_pagamento(), true)) {
        return ['ok' => false, 'messaggio' => 'Stato del pagamento non riconosciuto.'];
    }

    return ordine_aggiorna_colonna($pdo, $ordineId, 'stato_pagamento', $stato, $sedeId);
}

/**
 * Aggiorna una colonna di stato di un ordine, rispettando il limite di sede.
 *
 * Il nome della colonna non arriva mai da fuori: è uno dei due valori scritti qui.
 *
 * L'ordine viene cercato prima di scriverlo, e non si contano le righe toccate
 * dall'aggiornamento: un UPDATE che assegna a una colonna il valore che ha già non tocca
 * nessuna riga, quindi il conteggio scambierebbe una scelta ripetuta per un ordine
 * inesistente. Il vincolo di sede e quello sullo stato restano comunque nella query di
 * scrittura, perchè fra la lettura e la scrittura l'ordine puo' essere annullato da
 * qualcun altro.
 */
function ordine_aggiorna_colonna(PDO $pdo, int $ordineId, string $colonna, string $valore, ?int $sedeId): array
{
    $colonna = $colonna === 'stato_pagamento' ? 'stato_pagamento' : 'stato';

    $limite = $sedeId === null ? '' : ' AND sede_id = :sede';
    $parametri = [':ordine' => $ordineId];

    if ($sedeId !== null) {
        $parametri[':sede'] = $sedeId;
    }

    $query = $pdo->prepare('SELECT stato FROM ordini WHERE id = :ordine' . $limite);
    $query->execute($parametri);
    $stato = $query->fetchColumn();

    if ($stato === false) {
        return ['ok' => false, 'messaggio' => 'L\'ordine non esiste o non è di questa sede.'];
    }

    if ($stato === 'annullato') {
        return ['ok' => false, 'messaggio' => 'L\'ordine è già stato annullato: non si puo\' piu\' modificare.'];
    }

    $aggiorna = $pdo->prepare(
        "UPDATE ordini SET {$colonna} = :valore WHERE id = :ordine AND stato <> 'annullato'" . $limite
    );
    $aggiorna->execute($parametri + [':valore' => $valore]);

    return ['ok' => true, 'messaggio' => 'Ordine aggiornato.'];
}

/**
 * Incasso degli ultimi giorni, in centesimi, per giorno.
 *
 * Gli ordini annullati non contano: la merce è tornata a disposizione e il pagamento e'
 * stato rimborsato. Restituisce una voce per ogni giorno del periodo, anche quando non
 * c'è stato nessun ordine, cosi' il grafico non salta le giornate vuote.
 *
 * @return array coppie data/centesimi, dal giorno piu' lontano a oggi
 */
function incasso_per_giorno(PDO $pdo, ?int $sedeId, int $giorni = GIORNI_INCASSO): array
{
    $sql = 'SELECT DATE(creato_il) AS giorno, SUM(totale_centesimi) AS totale
              FROM ordini
             WHERE stato <> \'annullato\'
               AND creato_il >= DATE_SUB(CURDATE(), INTERVAL :giorni DAY)';
    $parametri = [':giorni' => $giorni];

    if ($sedeId !== null) {
        $sql .= ' AND sede_id = :sede';
        $parametri[':sede'] = $sedeId;
    }

    $query = $pdo->prepare($sql . ' GROUP BY DATE(creato_il)');
    $query->execute($parametri);

    $totali = [];

    foreach ($query->fetchAll() as $riga) {
        $totali[$riga['giorno']] = (int) $riga['totale'];
    }

    $serie = [];

    for ($scarto = $giorni - 1; $scarto >= 0; $scarto--) {
        $giorno = date('Y-m-d', strtotime('-' . $scarto . ' day'));
        $serie[$giorno] = $totali[$giorno] ?? 0;
    }

    return $serie;
}

/**
 * Somma degli incassi di una serie giornaliera.
 */
function incasso_totale(array $serie): int
{
    return array_sum($serie);
}
