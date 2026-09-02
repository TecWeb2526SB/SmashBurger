<?php
/**
 * Creazione e annullamento degli ordini.
 *
 * Qui vive la regola piu' delicata dell'applicazione: la quantita' disponibile viene
 * scalata alla conferma dell'ordine, dentro una transazione, con un aggiornamento che
 * riesce solo se la merce basta davvero.
 */

/**
 * Crea un ordine a partire dal carrello.
 *
 * Ogni riga scala la disponibilita' con un aggiornamento condizionale: se tocca zero
 * righe, la merce è finita mentre la persona stava pagando, e l'intero ordine viene
 * annullato senza crearne uno parziale.
 *
 * @param array $dati modalita', ritiro_previsto, metodo_pagamento e, per il domicilio,
 *                    l'indirizzo già validato
 * @return array ['ok' => bool, 'messaggio' => string, 'ordine_id' => int|null]
 */
function ordine_crea(PDO $pdo, array $carrello, array $righe, array $dati): array
{
    if ($righe === []) {
        return ['ok' => false, 'messaggio' => 'Il carrello è vuoto.', 'ordine_id' => null];
    }

    $sedeId = (int) $carrello['sede_id'];
    $totale = carrello_totale($righe);

    $pdo->beginTransaction();

    try {
        foreach ($righe as $riga) {
            if (!disponibilita_scala($pdo, $sedeId, (int) $riga['prodotto_id'], (int) $riga['quantita'])) {
                $pdo->rollBack();

                $rimasti = quantita_disponibile($pdo, $sedeId, (int) $riga['prodotto_id']);

                return [
                    'ok' => false,
                    'messaggio' => sprintf(
                        'Nel frattempo %s non è piu disponibile nella quantità richiesta: ne restano %d.',
                        $riga['nome'],
                        $rimasti
                    ),
                    'ordine_id' => null,
                ];
            }
        }

        $ordineId = ordine_inserisci($pdo, $carrello, $dati, $totale);
        righe_ordine_inserisci($pdo, $ordineId, $righe);

        // Il numero d'ordine si ricava dall'identificativo, quindi non puo' ripetersi.
        $pdo->prepare('UPDATE ordini SET numero_ordine = :numero WHERE id = :id')->execute([
            ':numero' => sprintf('SB-%s-%04d', date('Y'), $ordineId),
            ':id' => $ordineId,
        ]);

        carrello_elimina($pdo, (int) $carrello['id']);

        $pdo->commit();

        return ['ok' => true, 'messaggio' => 'Ordine confermato.', 'ordine_id' => $ordineId];
    } catch (PDOException $errore) {
        $pdo->rollBack();
        error_log('Creazione ordine non riuscita: ' . $errore->getMessage());

        return ['ok' => false, 'messaggio' => 'Non siamo riusciti a registrare l\'ordine.', 'ordine_id' => null];
    }
}

/**
 * Scala la disponibilita' di un prodotto in una sede.
 *
 * L'aggiornamento riesce solo se la quantita' basta e il prodotto è ancora nel menu:
 * il database decide, non un controllo letto prima e diventato vecchio.
 *
 * @return bool false quando la merce non basta piu'
 */
function disponibilita_scala(PDO $pdo, int $sedeId, int $prodottoId, int $quantita): bool
{
    $query = $pdo->prepare(
        'UPDATE disponibilita_prodotti
            SET quantita = quantita - :quantita
          WHERE sede_id = :sede AND prodotto_id = :prodotto
            AND disponibile = 1 AND quantita >= :richiesta'
    );
    $query->execute([
        ':quantita' => $quantita,
        ':sede' => $sedeId,
        ':prodotto' => $prodottoId,
        ':richiesta' => $quantita,
    ]);

    return $query->rowCount() === 1;
}

/**
 * Riporta indietro la disponibilita' di un prodotto.
 */
function disponibilita_ripristina(PDO $pdo, int $sedeId, int $prodottoId, int $quantita): void
{
    $pdo->prepare(
        'UPDATE disponibilita_prodotti SET quantita = quantita + :quantita
          WHERE sede_id = :sede AND prodotto_id = :prodotto'
    )->execute([':quantita' => $quantita, ':sede' => $sedeId, ':prodotto' => $prodottoId]);
}

/**
 * Inserisce la header dell'ordine e restituisce il suo identificativo.
 *
 * L'indirizzo di consegna viene copiato qui e non letto dal profilo: un ordine deve
 * restare leggibile anche se la persona cambia i propri dati.
 */
function ordine_inserisci(PDO $pdo, array $carrello, array $dati, int $totale): int
{
    $domicilio = $dati['modalita'] === 'domicilio';

    $pdo->prepare(
        'INSERT INTO ordini (utente_id, sede_id, numero_ordine, modalita, ritiro_previsto,
                             consegna_indirizzo, consegna_citta, consegna_provincia,
                             consegna_cap, consegna_paese, consegna_telefono,
                             metodo_pagamento, stato_pagamento, totale_centesimi)
         VALUES (:utente, :sede, :numero, :modalita, :ritiro,
                 :indirizzo, :citta, :provincia, :cap, :paese, :telefono,
                 :metodo, :stato_pagamento, :totale)'
    )->execute([
        ':utente' => (int) $carrello['utente_id'],
        ':sede' => (int) $carrello['sede_id'],
        // Valore provvisorio unico, sostituito subito dopo con il numero definitivo.
        ':numero' => 'temporaneo-' . bin2hex(random_bytes(4)),
        ':modalita' => $dati['modalita'],
        ':ritiro' => $domicilio ? null : $dati['ritiro_previsto'],
        ':indirizzo' => $domicilio ? trim($dati['indirizzo']) : null,
        ':citta' => $domicilio ? trim($dati['citta']) : null,
        ':provincia' => $domicilio ? strtoupper(trim($dati['provincia'])) : null,
        ':cap' => $domicilio ? trim($dati['cap']) : null,
        ':paese' => $domicilio ? trim($dati['paese']) : null,
        ':telefono' => $domicilio ? trim($dati['telefono']) : null,
        ':metodo' => $dati['metodo_pagamento'],
        // Il pagamento è simulato: la carta risulta pagata subito, i contanti alla consegna.
        ':stato_pagamento' => $dati['metodo_pagamento'] === 'carta' ? 'pagato' : 'in attesa',
        ':totale' => $totale,
    ]);

    return (int) $pdo->lastInsertId();
}

/**
 * Copia le righe del carrello dentro l'ordine, congelando nome e prezzo.
 */
function righe_ordine_inserisci(PDO $pdo, int $ordineId, array $righe): void
{
    $query = $pdo->prepare(
        'INSERT INTO righe_ordine (ordine_id, prodotto_id, nome_prodotto, quantita, prezzo_centesimi)
         VALUES (:ordine, :prodotto, :nome, :quantita, :prezzo)'
    );

    foreach ($righe as $riga) {
        $query->execute([
            ':ordine' => $ordineId,
            ':prodotto' => (int) $riga['prodotto_id'],
            ':nome' => $riga['nome'],
            ':quantita' => (int) $riga['quantita'],
            ':prezzo' => (int) $riga['prezzo_centesimi'],
        ]);
    }
}

/**
 * Annulla un ordine e riporta indietro la merce.
 *
 * Il ripristino avviene solo nella transizione verso lo stato annullato: un ordine gia'
 * annullato non restituisce la merce una seconda volta.
 *
 * @return array ['ok' => bool, 'messaggio' => string]
 */
function ordine_annulla(PDO $pdo, int $ordineId, string $motivo, bool $rimborsa): array
{
    $motivo = trim($motivo);

    if (mb_strlen($motivo) < 5 || mb_strlen($motivo) > 255) {
        return ['ok' => false, 'messaggio' => 'Scrivi il motivo dell\'annullamento, fra 5 e 255 caratteri.'];
    }

    $pdo->beginTransaction();

    try {
        $query = $pdo->prepare('SELECT * FROM ordini WHERE id = :id FOR UPDATE');
        $query->execute([':id' => $ordineId]);
        $ordine = $query->fetch();

        if ($ordine === false) {
            $pdo->rollBack();

            return ['ok' => false, 'messaggio' => 'L\'ordine non esiste.'];
        }

        if ($ordine['stato'] === 'annullato') {
            $pdo->rollBack();

            return ['ok' => false, 'messaggio' => 'Questo ordine era già annullato.'];
        }

        foreach (righe_dell_ordine($pdo, $ordineId) as $riga) {
            if ($riga['prodotto_id'] !== null) {
                disponibilita_ripristina(
                    $pdo,
                    (int) $ordine['sede_id'],
                    (int) $riga['prodotto_id'],
                    (int) $riga['quantita']
                );
            }
        }

        $pdo->prepare(
            'UPDATE ordini SET stato = :stato, motivo_annullamento = :motivo,
                    stato_pagamento = :pagamento
              WHERE id = :id'
        )->execute([
            ':stato' => 'annullato',
            ':motivo' => $motivo,
            ':pagamento' => $rimborsa ? 'rimborsato' : $ordine['stato_pagamento'],
            ':id' => $ordineId,
        ]);

        $pdo->commit();

        return ['ok' => true, 'messaggio' => 'Ordine annullato e merce rimessa a disposizione.'];
    } catch (PDOException $errore) {
        $pdo->rollBack();
        error_log('Annullamento ordine non riuscito: ' . $errore->getMessage());

        return ['ok' => false, 'messaggio' => 'Non siamo riusciti ad annullare l\'ordine.'];
    }
}
