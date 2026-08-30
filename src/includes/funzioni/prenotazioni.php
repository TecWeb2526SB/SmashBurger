<?php
/**
 * Prenotazioni della sala eventi.
 *
 * Ogni sede ha una sola sala, quindi la prenotazione punta direttamente alla sede.
 *
 * L'orario di inizio si sceglie liberamente, a passi di MINUTI_PASSO_PRENOTAZIONE dentro
 * l'orario di apertura; la prenotazione dura ORE_PRENOTAZIONE ore, oppure meno se la
 * sede chiude prima, e comunque mai meno di MINUTI_MINIMI_PRENOTAZIONE.
 *
 * Poiche' gli inizi possibili si sovrappongono fra loro, la difesa contro la doppia
 * prenotazione e' interamente nel controllo di sovrapposizione: un orario risulta libero
 * solo se l'intervallo che genera non tocca nessuna prenotazione attiva. Il controllo sta
 * nel codice e non in un vincolo di unicita' perche' due prenotazioni rifiutate o
 * annullate possono legittimamente avere lo stesso orario, e MariaDB 10.6 non permette un
 * indice unico limitato alle sole righe attive.
 */

/**
 * Stati in cui puo' trovarsi una prenotazione.
 */
function stati_prenotazione(): array
{
    return ['in attesa', 'approvata', 'rifiutata', 'annullata'];
}

/**
 * Stati che occupano davvero la sala.
 */
function stati_che_occupano(): array
{
    return ['in attesa', 'approvata'];
}

/**
 * Quanti giorni in avanti si puo' prenotare.
 */
function giorni_prenotabili(): int
{
    return 90;
}

/**
 * Orari di inizio proposti per una sede in un giorno.
 *
 * Ogni voce porta l'intervallo che genera e se quell'intervallo e' libero. Un orario
 * risulta occupato anche quando la prenotazione esistente comincia dopo: prenotare alle
 * 14:00 con una prenotazione gia' fissata alle 15:00 significherebbe sovrapporsi.
 *
 * @return array elenco di ['inizio', 'fine', 'etichetta', 'occupata']
 */
function fasce_prenotabili(PDO $pdo, int $sedeId, string $data): array
{
    $orari = orari_sede($pdo, $sedeId);
    $orario = $orari[(int) date('N', strtotime($data))];

    if ((int) $orario['chiuso'] === 1 || $orario['apertura'] === null) {
        return [];
    }

    $attive = prenotazioni_attive_del_giorno($pdo, $sedeId, $data);

    $momento = strtotime($data . ' ' . $orario['apertura']);
    $chiusura = strtotime($data . ' ' . $orario['chiusura']);
    $passo = MINUTI_PASSO_PRENOTAZIONE * 60;
    $fasce = [];

    while ($chiusura - $momento >= MINUTI_MINIMI_PRENOTAZIONE * 60) {
        // La durata piena si accorcia solo per l'ultima parte della giornata.
        $termine = min($momento + ORE_PRENOTAZIONE * 3600, $chiusura);
        $inizio = date('H:i:s', $momento);
        $fine = date('H:i:s', $termine);

        $etichetta = substr($inizio, 0, 5) . ' - ' . substr($fine, 0, 5);

        if ($termine === $chiusura && $chiusura - $momento < ORE_PRENOTAZIONE * 3600) {
            $etichetta .= ', fino alla chiusura';
        }

        $fasce[] = [
            'inizio' => $inizio,
            'fine' => $fine,
            'etichetta' => $etichetta,
            'occupata' => intervallo_occupato($attive, $inizio, $fine),
        ];

        $momento += $passo;
    }

    return $fasce;
}

/**
 * Prenotazioni che occupano la sala di una sede in un giorno.
 */
function prenotazioni_attive_del_giorno(PDO $pdo, int $sedeId, string $data): array
{
    $query = $pdo->prepare(
        'SELECT ora_inizio, ora_fine FROM prenotazioni
          WHERE sede_id = :sede AND data = :data
            AND stato IN (\'in attesa\', \'approvata\')'
    );
    $query->execute([':sede' => $sedeId, ':data' => $data]);

    return $query->fetchAll();
}

/**
 * Verifica se un intervallo tocca una delle prenotazioni gia' fissate.
 *
 * Due intervalli si sovrappongono quando ognuno comincia prima che l'altro finisca.
 */
function intervallo_occupato(array $attive, string $inizio, string $fine): bool
{
    foreach ($attive as $presa) {
        if ($presa['ora_inizio'] < $fine && $presa['ora_fine'] > $inizio) {
            return true;
        }
    }

    return false;
}

/**
 * Verifica se una fascia si sovrappone a una prenotazione che occupa gia' la sala.
 *
 * Due intervalli si sovrappongono quando ognuno comincia prima che l'altro finisca.
 */
function prenotazione_sovrapposta(PDO $pdo, int $sedeId, string $data, string $inizio, string $fine): bool
{
    $query = $pdo->prepare(
        'SELECT 1 FROM prenotazioni
          WHERE sede_id = :sede AND data = :data
            AND stato IN (\'in attesa\', \'approvata\')
            AND ora_inizio < :fine AND ora_fine > :inizio
          LIMIT 1'
    );
    $query->execute([
        ':sede' => $sedeId,
        ':data' => $data,
        ':fine' => $fine,
        ':inizio' => $inizio,
    ]);

    return $query->fetchColumn() !== false;
}

/**
 * Controlla i dati di una prenotazione.
 *
 * @return array errori indicizzati per campo
 */
function prenotazione_errori(PDO $pdo, array $sede, array $dati): array
{
    $errori = [];

    if ((int) $sede['sala_eventi_disponibile'] !== 1) {
        $errori['sede'] = 'La sala di questa sede non accetta prenotazioni in questo periodo.';

        return $errori;
    }

    $data = (string) ($dati['data'] ?? '');

    if (!data_valida($data)) {
        $errori['data'] = 'Scegli una data valida.';
    } elseif ($data < date('Y-m-d')) {
        $errori['data'] = 'La data e gia passata.';
    } elseif ($data > date('Y-m-d', strtotime('+' . giorni_prenotabili() . ' day'))) {
        $errori['data'] = 'Si prenota fino a ' . giorni_prenotabili() . ' giorni in anticipo.';
    }

    $persone = filter_var($dati['numero_persone'] ?? '', FILTER_VALIDATE_INT);

    if ($persone === false || $persone < 1 || $persone > 80) {
        $errori['numero_persone'] = 'Indica quante persone siete, da 1 a 80.';
    }

    if (mb_strlen((string) ($dati['note'] ?? '')) > 255) {
        $errori['note'] = 'Le note non possono superare i 255 caratteri.';
    }

    if (isset($errori['data'])) {
        return $errori;
    }

    $fascia = fascia_scelta($pdo, (int) $sede['id'], $data, (string) ($dati['fascia'] ?? ''));

    if ($fascia === null) {
        $errori['fascia'] = 'Scegli uno degli orari liberi di quel giorno.';
    } elseif (prenotazione_sovrapposta($pdo, (int) $sede['id'], $data, $fascia['inizio'], $fascia['fine'])) {
        $errori['fascia'] = 'Questa fascia e stata appena occupata: scegline un altra.';
    }

    return $errori;
}

/**
 * Verifica che una data sia scritta come attesa e che esista davvero.
 */
function data_valida(string $data): bool
{
    $parti = date_parse_from_format('Y-m-d', $data);

    return $parti['error_count'] === 0
        && $parti['warning_count'] === 0
        && checkdate((int) $parti['month'], (int) $parti['day'], (int) $parti['year']);
}

/**
 * Restituisce la fascia libera corrispondente all'orario di inizio scelto, oppure null.
 */
function fascia_scelta(PDO $pdo, int $sedeId, string $data, string $inizio): ?array
{
    foreach (fasce_prenotabili($pdo, $sedeId, $data) as $fascia) {
        if ($fascia['inizio'] === $inizio && !$fascia['occupata']) {
            return $fascia;
        }
    }

    return null;
}

/**
 * Registra una prenotazione gia' validata.
 *
 * La sovrapposizione viene controllata di nuovo dentro la transazione: fra il controllo
 * e il salvataggio qualcun altro puo' avere prenotato la stessa fascia.
 *
 * @return array ['ok' => bool, 'messaggio' => string]
 */
function prenotazione_crea(PDO $pdo, int $sedeId, int $utenteId, array $dati, array $fascia): array
{
    $pdo->beginTransaction();

    try {
        if (prenotazione_sovrapposta($pdo, $sedeId, $dati['data'], $fascia['inizio'], $fascia['fine'])) {
            $pdo->rollBack();

            return ['ok' => false, 'messaggio' => 'Questa fascia e stata appena occupata: scegline un altra.'];
        }

        $pdo->prepare(
            'INSERT INTO prenotazioni (sede_id, utente_id, data, ora_inizio, ora_fine, numero_persone, note)
             VALUES (:sede, :utente, :data, :inizio, :fine, :persone, :note)'
        )->execute([
            ':sede' => $sedeId,
            ':utente' => $utenteId,
            ':data' => $dati['data'],
            ':inizio' => $fascia['inizio'],
            ':fine' => $fascia['fine'],
            ':persone' => (int) $dati['numero_persone'],
            ':note' => trim((string) ($dati['note'] ?? '')) ?: null,
        ]);

        $pdo->commit();

        return ['ok' => true, 'messaggio' => 'Prenotazione inviata: la sede la confermera a breve.'];
    } catch (PDOException $errore) {
        $pdo->rollBack();
        error_log('Prenotazione non riuscita: ' . $errore->getMessage());

        return ['ok' => false, 'messaggio' => 'Non siamo riusciti a registrare la prenotazione.'];
    }
}

/**
 * Prenotazioni di una persona, dalla data piu' recente.
 */
function prenotazioni_dell_utente(PDO $pdo, int $utenteId): array
{
    $query = $pdo->prepare(
        'SELECT p.*, s.citta, s.slug AS sede_slug
           FROM prenotazioni p
           JOIN sedi s ON s.id = p.sede_id
          WHERE p.utente_id = :utente
          ORDER BY p.data DESC, p.ora_inizio DESC'
    );
    $query->execute([':utente' => $utenteId]);

    return $query->fetchAll();
}
