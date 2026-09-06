<?php
/**
 * Prenotazioni della sala eventi.
 *
 * Ogni sede ha una sola sala, quindi la prenotazione punta direttamente alla sede.
 *
 * Orario di inizio e durata si scelgono entrambi: l'orario a passi di
 * MINUTI_PASSO_PRENOTAZIONE dentro l'apertura, la durata fra MINUTI_MINIMI_PRENOTAZIONE e
 * MINUTI_MASSIMI_PRENOTAZIONE. Un orario compare quando ci sta almeno la durata minima,
 * quindi la combinazione esatta va ricontrollata all'invio.
 *
 * Poichè gli inizi possibili si sovrappongono fra loro, la difesa contro la doppia
 * prenotazione è interamente nel controllo di sovrapposizione: un orario risulta libero
 * solo se l'intervallo che genera non tocca nessuna prenotazione attiva. Il controllo sta
 * nel codice e non in un vincolo di unicita' perchè due prenotazioni rifiutate o
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
 * Quanti giorni in avanti si puo' prenotare.
 */
function giorni_prenotabili(): int
{
    return 90;
}









/**
 * Verifica se una fascia si sovrappone a una prenotazione che occupa già la sala.
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
        $errori['data'] = 'La data è già passata: scegli oggi o un giorno futuro.';
    } elseif ($data > date('Y-m-d', strtotime('+' . giorni_prenotabili() . ' day'))) {
        $errori['data'] = 'Si prenota fino a ' . giorni_prenotabili() . ' giorni in anticipo.';
    }

    $persone = filter_var($dati['numero_persone'] ?? '', FILTER_VALIDATE_INT);

    if ($persone === false || $persone < 1 || $persone > 80) {
        $errori['numero_persone'] = 'Indica quante persone siete, da 1 a 80.';
    }

    if (mb_strlen(trim((string) ($dati['note'] ?? ''))) > CARATTERI_NOTA_PRENOTAZIONE) {
        $errori['note'] = 'La nota non può superare i ' . CARATTERI_NOTA_PRENOTAZIONE . ' caratteri: accorciala.';
    }

    $durata = filter_var($dati['durata'] ?? '', FILTER_VALIDATE_INT);

    if ($durata === false || !array_key_exists($durata, durate_prenotabili())) {
        $errori['durata'] = 'Scegli una delle durate proposte.';
    }

    if (isset($errori['data']) || isset($errori['durata'])) {
        return $errori;
    }

    $fascia = fascia_scelta($pdo, (int) $sede['id'], $data, (string) ($dati['fascia'] ?? ''), $durata);

    if ($fascia === null) {
        $errori['fascia'] = 'Con questa durata l\'orario scelto non è libero: prova una durata più breve o un altro orario.';
    } elseif (prenotazione_sovrapposta($pdo, (int) $sede['id'], $data, $fascia['inizio'], $fascia['fine'])) {
        $errori['fascia'] = 'Questo orario è stato appena occupato: scegline un altro.';
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
 * Registra una prenotazione già validata.
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

            return ['ok' => false, 'messaggio' => 'Questa fascia è stata appena occupata: scegline un\'altra.'];
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
