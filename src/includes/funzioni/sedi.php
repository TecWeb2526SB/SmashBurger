<?php
/**
 * Lettura delle sedi e dei loro orari di apertura.
 *
 * I giorni seguono la numerazione ISO 8601 usata nel database: 1 è lunedi', 7 domenica.
 */

/**
 * Nomi dei giorni della settimana, indicizzati come nel database.
 */
function giorni_settimana(): array
{
    return [
        1 => 'lunedi',
        2 => 'martedi',
        3 => 'mercoledi',
        4 => 'giovedi',
        5 => 'venerdi',
        6 => 'sabato',
        7 => 'domenica',
    ];
}

/**
 * Tutte le sedi attive, nell'ordine deciso dalla colonna ordine.
 */
function sedi_attive(PDO $pdo): array
{
    return $pdo->query(
        'SELECT * FROM sedi WHERE attiva = 1 ORDER BY ordine, citta'
    )->fetchAll();
}

/**
 * Tutte le sedi, comprese quelle disattivate. Serve al pannello di controllo.
 */
function sedi_tutte(PDO $pdo): array
{
    return $pdo->query('SELECT * FROM sedi ORDER BY ordine, citta')->fetchAll();
}

/**
 * Una sede a partire dal suo slug, oppure null se non esiste o non è attiva.
 */
function sede_per_slug(PDO $pdo, string $slug): ?array
{
    $query = $pdo->prepare('SELECT * FROM sedi WHERE slug = :slug AND attiva = 1');
    $query->execute([':slug' => $slug]);
    $sede = $query->fetch();

    return $sede === false ? null : $sede;
}

/**
 * Una sede a partire dal suo identificativo, oppure null.
 */
function sede_per_id(PDO $pdo, int $id): ?array
{
    $query = $pdo->prepare('SELECT * FROM sedi WHERE id = :id');
    $query->execute([':id' => $id]);
    $sede = $query->fetch();

    return $sede === false ? null : $sede;
}

/**
 * Orari settimanali di una sede, indicizzati per giorno.
 *
 * Restituisce sempre sette voci: i giorni senza riga nel database risultano chiusi.
 */
function orari_sede(PDO $pdo, int $sedeId): array
{
    $query = $pdo->prepare(
        'SELECT giorno, apertura, chiusura, chiuso FROM orari_sedi
          WHERE sede_id = :sede ORDER BY giorno'
    );
    $query->execute([':sede' => $sedeId]);

    $orari = [];

    foreach (array_keys(giorni_settimana()) as $giorno) {
        $orari[$giorno] = ['giorno' => $giorno, 'apertura' => null, 'chiusura' => null, 'chiuso' => 1];
    }

    foreach ($query->fetchAll() as $riga) {
        $orari[(int) $riga['giorno']] = $riga;
    }

    return $orari;
}

/**
 * Orari di tutte le sedi in una sola query, indicizzati per sede e poi per giorno.
 *
 * Evita di interrogare il database una volta per sede nella pagina che le elenca tutte.
 */
function orari_di_tutte_le_sedi(PDO $pdo): array
{
    $righe = $pdo->query(
        'SELECT sede_id, giorno, apertura, chiusura, chiuso FROM orari_sedi ORDER BY sede_id, giorno'
    )->fetchAll();

    $orari = [];

    foreach ($righe as $riga) {
        $orari[(int) $riga['sede_id']][(int) $riga['giorno']] = $riga;
    }

    return $orari;
}

/**
 * Descrive in una riga la fascia di apertura di un giorno.
 */
function fascia_leggibile(array $orario): string
{
    if ((int) $orario['chiuso'] === 1 || $orario['apertura'] === null) {
        return 'chiuso';
    }

    return substr($orario['apertura'], 0, 5) . ' - ' . substr($orario['chiusura'], 0, 5);
}

/**
 * Orari di ritiro selezionabili per una sede.
 *
 * Restituisce fasce di quindici minuti dentro l'apertura, a partire da mezz'ora dopo
 * adesso, per i prossimi giorni. Le coppie sono valore per il modulo ed etichetta
 * leggibile.
 *
 * @param int $giorni quanti giorni considerare, a partire da oggi
 */
function orari_ritiro_disponibili(PDO $pdo, int $sedeId, int $giorni = 3): array
{
    $orari = orari_sede($pdo, $sedeId);
    $primoUtile = time() + 30 * 60;
    $slot = [];

    for ($scarto = 0; $scarto < $giorni; $scarto++) {
        $giorno = strtotime('+' . $scarto . ' day');
        $orario = $orari[(int) date('N', $giorno)];

        if ((int) $orario['chiuso'] === 1 || $orario['apertura'] === null) {
            continue;
        }

        $data = date('Y-m-d', $giorno);
        $momento = strtotime($data . ' ' . $orario['apertura']);
        $chiusura = strtotime($data . ' ' . $orario['chiusura']);

        while ($momento <= $chiusura) {
            if ($momento >= $primoUtile) {
                $slot[date('Y-m-d H:i:s', $momento)] = date('d/m/Y H:i', $momento);
            }

            $momento += 15 * 60;
        }
    }

    return $slot;
}
