<?php
/**
 * Calcolo degli orari prenotabili della sala eventi.
 *
 * Sta in un file separato da prenotazioni.php, che valida e salva: qui c'è solo il
 * ragionamento su apertura, durate e intervalli già occupati.
 */

/**
 * Durate prenotabili, come coppie minuti/etichetta.
 */
function durate_prenotabili(): array
{
    $durate = [];

    for ($minuti = MINUTI_MINIMI_PRENOTAZIONE; $minuti <= MINUTI_MASSIMI_PRENOTAZIONE; $minuti += MINUTI_PASSO_PRENOTAZIONE) {
        $ore = intdiv($minuti, 60);
        $resto = $minuti % 60;

        $etichetta = $ore . ($ore === 1 ? ' ora' : ' ore');
        $durate[$minuti] = $resto === 0 ? $etichetta : $etichetta . ' e ' . $resto . ' minuti';
    }

    return $durate;
}

/**
 * Orari di inizio proposti per una sede in un giorno.
 *
 * Un orario compare quando ci sta almeno la durata minima: la durata la sceglie chi
 * prenota, quindi la combinazione esatta viene controllata al momento dell'invio.
 * L'orario risulta occupato anche se la prenotazione esistente comincia dopo, perche'
 * la durata minima già la invaderebbe.
 *
 * @return array elenco di ['inizio', 'etichetta', 'massimo', 'occupata']
 */
function fasce_prenotabili(PDO $pdo, int $sedeId, string $data): array
{
    $orario = orario_del_giorno($pdo, $sedeId, $data);

    if ($orario === null) {
        return [];
    }

    $attive = prenotazioni_attive_del_giorno($pdo, $sedeId, $data);
    $passo = MINUTI_PASSO_PRENOTAZIONE * 60;
    $minima = MINUTI_MINIMI_PRENOTAZIONE * 60;

    $momento = $orario['apertura'];
    $fasce = [];

    while ($orario['chiusura'] - $momento >= $minima) {
        $inizio = date('H:i:s', $momento);
        $massimo = durata_massima_da($attive, $inizio, $momento, $orario['chiusura']);

        $fasce[] = [
            'inizio' => $inizio,
            'etichetta' => substr($inizio, 0, 5),
            'massimo' => $massimo,
            'occupata' => $massimo < MINUTI_MINIMI_PRENOTAZIONE,
        ];

        $momento += $passo;
    }

    return $fasce;
}

/**
 * Minuti prenotabili a partire da un orario, fermandosi alla prima prenotazione o alla
 * chiusura, senza superare la durata massima.
 */
function durata_massima_da(array $attive, string $inizio, int $momento, int $chiusura): int
{
    $limite = min($momento + MINUTI_MASSIMI_PRENOTAZIONE * 60, $chiusura);

    foreach ($attive as $presa) {
        // Una prenotazione già in corso a quell'ora non lascia nessuno spazio.
        if ($presa['ora_inizio'] <= $inizio && $presa['ora_fine'] > $inizio) {
            return 0;
        }

        if ($presa['ora_inizio'] > $inizio) {
            $inizioPresa = strtotime(date('Y-m-d', $momento) . ' ' . $presa['ora_inizio']);
            $limite = min($limite, $inizioPresa);
        }
    }

    return max(0, intdiv($limite - $momento, 60));
}

/**
 * Prenotazioni che occupano la sala di una sede in un giorno, in ordine di orario.
 */
function prenotazioni_attive_del_giorno(PDO $pdo, int $sedeId, string $data): array
{
    $query = $pdo->prepare(
        'SELECT ora_inizio, ora_fine FROM prenotazioni
          WHERE sede_id = :sede AND data = :data
            AND stato IN (\'in attesa\', \'approvata\')
          ORDER BY ora_inizio'
    );
    $query->execute([':sede' => $sedeId, ':data' => $data]);

    return $query->fetchAll();
}

/**
 * Orario di apertura di una sede in un giorno, come momenti, oppure null se è chiusa.
 */
function orario_del_giorno(PDO $pdo, int $sedeId, string $data): ?array
{
    $orari = orari_sede($pdo, $sedeId);
    $orario = $orari[(int) date('N', strtotime($data))];

    if ((int) $orario['chiuso'] === 1 || $orario['apertura'] === null) {
        return null;
    }

    return [
        'apertura' => strtotime($data . ' ' . $orario['apertura']),
        'chiusura' => strtotime($data . ' ' . $orario['chiusura']),
    ];
}

/**
 * Occupazione della sala in un giorno, come sequenza di intervalli liberi e occupati.
 *
 * Serve a mostrare la disponibilita' senza rivelare nulla di chi ha prenotato: la
 * tabella dice quando la sala è presa, non da chi nè per che cosa.
 *
 * @return array elenco di ['inizio', 'fine', 'occupata']
 */
function occupazione_del_giorno(PDO $pdo, int $sedeId, string $data): array
{
    $orario = orario_del_giorno($pdo, $sedeId, $data);

    if ($orario === null) {
        return [];
    }

    $apertura = date('H:i:s', $orario['apertura']);
    $chiusura = date('H:i:s', $orario['chiusura']);

    $prese = unisci_intervalli(prenotazioni_attive_del_giorno($pdo, $sedeId, $data));
    $intervalli = [];
    $momento = $apertura;

    foreach ($prese as $presa) {
        if ($presa['ora_fine'] <= $apertura || $presa['ora_inizio'] >= $chiusura) {
            continue;
        }

        $inizioPresa = max($presa['ora_inizio'], $apertura);
        $finePresa = min($presa['ora_fine'], $chiusura);

        if ($momento < $inizioPresa) {
            $intervalli[] = ['inizio' => $momento, 'fine' => $inizioPresa, 'occupata' => false];
        }

        $intervalli[] = ['inizio' => $inizioPresa, 'fine' => $finePresa, 'occupata' => true];
        $momento = $finePresa;
    }

    if ($momento < $chiusura) {
        $intervalli[] = ['inizio' => $momento, 'fine' => $chiusura, 'occupata' => false];
    }

    return $intervalli;
}

/**
 * Fonde gli intervalli che si toccano o si sovrappongono, per non ripetere righe nella
 * tabella delle occupazioni.
 */
function unisci_intervalli(array $intervalli): array
{
    $uniti = [];

    foreach ($intervalli as $intervallo) {
        $ultimo = count($uniti) - 1;

        if ($ultimo >= 0 && $intervallo['ora_inizio'] <= $uniti[$ultimo]['ora_fine']) {
            $uniti[$ultimo]['ora_fine'] = max($uniti[$ultimo]['ora_fine'], $intervallo['ora_fine']);

            continue;
        }

        $uniti[] = $intervallo;
    }

    return $uniti;
}

/**
 * Intervallo corrispondente a orario e durata scelti, oppure null se non è prenotabile.
 *
 * Restituisce null anche quando l'orario esiste ma la durata richiesta non ci sta: è il
 * caso di chi sceglie tre ore alle 20:00 con la sede che chiude alle 22:30.
 */
function fascia_scelta(PDO $pdo, int $sedeId, string $data, string $inizio, int $durata): ?array
{
    foreach (fasce_prenotabili($pdo, $sedeId, $data) as $fascia) {
        if ($fascia['inizio'] !== $inizio || $fascia['occupata'] || $fascia['massimo'] < $durata) {
            continue;
        }

        return [
            'inizio' => $inizio,
            'fine' => date('H:i:s', strtotime($data . ' ' . $inizio) + $durata * 60),
        ];
    }

    return null;
}
