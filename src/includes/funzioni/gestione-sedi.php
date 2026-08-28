<?php
/**
 * Gestione delle sedi dal pannello di controllo: anagrafica e orari settimanali.
 *
 * La lettura delle sedi usata dalle pagine pubbliche sta in sedi.php.
 */

/**
 * Controlla i dati di una sede inviati dal pannello.
 *
 * @return array<string, string>  errori indicizzati per nome del campo
 */
function sede_valida(array $dati): array
{
    $errori = [];

    foreach (['nome' => 120, 'citta' => 80, 'indirizzo' => 160, 'telefono' => 30] as $campo => $massimo) {
        if (trim($dati[$campo]) === '' || mb_strlen($dati[$campo]) > $massimo) {
            $errori[$campo] = 'Campo obbligatorio, al massimo ' . $massimo . ' caratteri.';
        }
    }

    if (preg_match('/^[A-Za-z]{2}$/', trim($dati['provincia'])) !== 1) {
        $errori['provincia'] = 'La provincia si scrive con due lettere, ad esempio PD.';
    }

    if (preg_match('/^[0-9]{5}$/', trim($dati['cap'])) !== 1) {
        $errori['cap'] = 'Il CAP è composto da cinque cifre.';
    }

    if (!utente_email_valida(trim($dati['email']))) {
        $errori['email'] = 'Inserisci un indirizzo email valido.';
    }

    return $errori;
}

/**
 * Inserisce una sede.
 *
 * @return array{ok: bool, errori: array<string, string>, id?: int}
 */
function sede_crea(PDO $pdo, array $dati): array
{
    $errori = sede_valida($dati);

    if ($errori !== []) {
        return ['ok' => false, 'errori' => $errori];
    }

    $query = $pdo->prepare(
        'INSERT INTO sedi (slug, nome, citta, provincia, indirizzo, cap, telefono, email,
                           note_ritiro, attiva, ordine)
         VALUES (:slug, :nome, :citta, :provincia, :indirizzo, :cap, :telefono, :email,
                 :note, :attiva, :ordine)'
    );
    $query->execute(sede_parametri($pdo, $dati) + ['slug' => sede_slug_univoco($pdo, catalogo_slug($dati['citta']))]);

    $sedeId = (int) $pdo->lastInsertId();
    sede_orari_predefiniti($pdo, $sedeId);

    return ['ok' => true, 'errori' => [], 'id' => $sedeId];
}

/**
 * Aggiorna i dati anagrafici di una sede.
 *
 * @return array{ok: bool, errori: array<string, string>}
 */
function sede_aggiorna(PDO $pdo, int $sedeId, array $dati): array
{
    $errori = sede_valida($dati);

    if ($errori !== []) {
        return ['ok' => false, 'errori' => $errori];
    }

    $query = $pdo->prepare(
        'UPDATE sedi SET nome = :nome, citta = :citta, provincia = :provincia,
                indirizzo = :indirizzo, cap = :cap, telefono = :telefono, email = :email,
                note_ritiro = :note, attiva = :attiva, ordine = :ordine
         WHERE id = :id'
    );
    $query->execute(sede_parametri($pdo, $dati) + ['id' => $sedeId]);

    return ['ok' => true, 'errori' => []];
}

/**
 * Prepara i parametri comuni a inserimento e modifica di una sede.
 */
function sede_parametri(PDO $pdo, array $dati): array
{
    return [
        'nome' => trim($dati['nome']),
        'citta' => trim($dati['citta']),
        'provincia' => strtoupper(trim($dati['provincia'])),
        'indirizzo' => trim($dati['indirizzo']),
        'cap' => trim($dati['cap']),
        'telefono' => trim($dati['telefono']),
        'email' => trim($dati['email']),
        'note' => trim($dati['note_ritiro']) === '' ? null : trim($dati['note_ritiro']),
        'attiva' => empty($dati['attiva']) ? 0 : 1,
        'ordine' => (int) ($dati['ordine'] ?? 0),
    ];
}

/**
 * Rende univoco lo slug di una sede aggiungendo un numero progressivo.
 */
function sede_slug_univoco(PDO $pdo, string $slug): string
{
    $query = $pdo->prepare('SELECT COUNT(*) FROM sedi WHERE slug = :slug');
    $candidato = $slug;
    $numero = 1;

    while (true) {
        $query->execute(['slug' => $candidato]);

        if ((int) $query->fetchColumn() === 0) {
            return $candidato;
        }

        $numero++;
        $candidato = $slug . '-' . $numero;
    }
}

/**
 * Crea per una sede nuova sette righe di orario con la fascia usata dalle altre sedi.
 */
function sede_orari_predefiniti(PDO $pdo, int $sedeId): void
{
    $query = $pdo->prepare(
        'INSERT INTO orari_sedi (sede_id, giorno, apertura, chiusura, chiuso)
         VALUES (:sede, :giorno, :apertura, :chiusura, 0)'
    );

    for ($giorno = 1; $giorno <= 7; $giorno++) {
        $query->execute([
            'sede' => $sedeId,
            'giorno' => $giorno,
            'apertura' => '11:30:00',
            'chiusura' => '22:30:00',
        ]);
    }
}

/**
 * Salva gli orari settimanali di una sede.
 *
 * @param array $giorni  sette elementi con chiavi apertura, chiusura e chiuso
 * @return array{ok: bool, messaggio: string}
 */
function sede_orari_aggiorna(PDO $pdo, int $sedeId, array $giorni): array
{
    $query = $pdo->prepare(
        'UPDATE orari_sedi SET apertura = :apertura, chiusura = :chiusura, chiuso = :chiuso
         WHERE sede_id = :sede AND giorno = :giorno'
    );

    foreach ($giorni as $giorno => $fascia) {
        $chiuso = !empty($fascia['chiuso']);
        $apertura = (string) ($fascia['apertura'] ?? '');
        $chiusura = (string) ($fascia['chiusura'] ?? '');

        if (!$chiuso && (!sede_orario_valido($apertura) || !sede_orario_valido($chiusura) || $apertura >= $chiusura)) {
            return ['ok' => false, 'messaggio' => 'Gli orari vanno scritti come 11:30 e l\'apertura deve precedere la chiusura.'];
        }

        $query->execute([
            'apertura' => $chiuso ? null : $apertura . ':00',
            'chiusura' => $chiuso ? null : $chiusura . ':00',
            'chiuso' => $chiuso ? 1 : 0,
            'sede' => $sedeId,
            'giorno' => (int) $giorno,
        ]);
    }

    return ['ok' => true, 'messaggio' => 'Orari aggiornati.'];
}

/**
 * Verifica che un orario sia scritto nella forma HH:MM.
 */
function sede_orario_valido(string $ora): bool
{
    return preg_match('/^([01][0-9]|2[0-3]):[0-5][0-9]$/', $ora) === 1;
}

/**
 * Cancella una sede, se non ha ordini collegati.
 *
 * @return array{ok: bool, messaggio: string}
 */
function sede_cancella(PDO $pdo, int $sedeId): array
{
    $query = $pdo->prepare('SELECT COUNT(*) FROM ordini WHERE sede_id = :sede');
    $query->execute(['sede' => $sedeId]);

    if ((int) $query->fetchColumn() > 0) {
        return [
            'ok' => false,
            'messaggio' => 'La sede ha ordini registrati e non può essere cancellata. Puoi disattivarla.',
        ];
    }

    $pdo->prepare('DELETE FROM sedi WHERE id = :id')->execute(['id' => $sedeId]);

    return ['ok' => true, 'messaggio' => 'Sede cancellata.'];
}
