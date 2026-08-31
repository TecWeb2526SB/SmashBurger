<?php
/**
 * Sedi viste dal pannello: dati del locale, orari settimanali e sala eventi.
 */

/**
 * Controlla i dati di una sede.
 */
function sede_errori(array $dati): array
{
    $errori = [];

    foreach (['nome' => ['Nome della sede', 120], 'citta' => ['Citta', 80], 'indirizzo' => ['Indirizzo', 160]] as $campo => $regole) {
        $valore = trim((string) ($dati[$campo] ?? ''));

        if (mb_strlen($valore) < 2 || mb_strlen($valore) > $regole[1]) {
            $errori[$campo] = 'Scrivi ' . lcfirst($regole[0]) . ', fra 2 e ' . $regole[1] . ' caratteri.';
        }
    }

    if (preg_match('/^[A-Za-z]{2}$/', trim((string) ($dati['provincia'] ?? ''))) !== 1) {
        $errori['provincia'] = 'La provincia si scrive con due lettere, ad esempio PD.';
    }

    if (preg_match('/^[0-9]{5}$/', trim((string) ($dati['cap'] ?? ''))) !== 1) {
        $errori['cap'] = 'Il CAP è composto da cinque cifre.';
    }

    if (preg_match('/^[0-9 +().-]{6,30}$/', trim((string) ($dati['telefono'] ?? ''))) !== 1) {
        $errori['telefono'] = 'Scrivi un numero di telefono valido.';
    }

    if (filter_var(trim((string) ($dati['email'] ?? '')), FILTER_VALIDATE_EMAIL) === false) {
        $errori['email'] = 'Scrivi un indirizzo email valido.';
    }

    if (mb_strlen((string) ($dati['note_ritiro'] ?? '')) > 255) {
        $errori['note_ritiro'] = 'Le note per il ritiro non possono superare i 255 caratteri.';
    }

    return $errori;
}

/**
 * Salva i dati di una sede, rispettando il limite di chi sta modificando.
 */
function sede_salva(PDO $pdo, int $sedeId, array $dati, ?int $limite): array
{
    if ($limite !== null && $limite !== $sedeId) {
        return ['ok' => false, 'messaggio' => 'Questa sede non è la tua.'];
    }

    $pdo->prepare(
        'UPDATE sedi SET nome = :nome, città = :citta, provincia = :provincia,
                indirizzo = :indirizzo, cap = :cap, telefono = :telefono, email = :email,
                note_ritiro = :note
          WHERE id = :id'
    )->execute([
        ':nome' => trim($dati['nome']),
        ':citta' => trim($dati['citta']),
        ':provincia' => strtoupper(trim($dati['provincia'])),
        ':indirizzo' => trim($dati['indirizzo']),
        ':cap' => trim($dati['cap']),
        ':telefono' => trim($dati['telefono']),
        ':email' => trim($dati['email']),
        ':note' => trim((string) ($dati['note_ritiro'] ?? '')) ?: null,
        ':id' => $sedeId,
    ]);

    return ['ok' => true, 'messaggio' => 'Dati della sede aggiornati.'];
}

/**
 * Salva gli orari settimanali di una sede.
 *
 * Un giorno senza orario di apertura o con l'apertura dopo la chiusura viene registrato
 * come chiuso: è l'unica lettura sensata di quei valori.
 */
function orari_salva(PDO $pdo, int $sedeId, array $dati, ?int $limite): array
{
    if ($limite !== null && $limite !== $sedeId) {
        return ['ok' => false, 'messaggio' => 'Questa sede non è la tua.'];
    }

    $query = $pdo->prepare(
        'INSERT INTO orari_sedi (sede_id, giorno, apertura, chiusura, chiuso)
         VALUES (:sede, :giorno, :apertura, :chiusura, :chiuso)
         ON DUPLICATE KEY UPDATE apertura = :nuova_apertura, chiusura = :nuova_chiusura,
                                 chiuso = :nuovo_chiuso'
    );

    foreach (array_keys(giorni_settimana()) as $giorno) {
        $apertura = orario_valido((string) ($dati['apertura'][$giorno] ?? ''));
        $chiusura = orario_valido((string) ($dati['chiusura'][$giorno] ?? ''));
        $chiuso = isset($dati['chiuso'][$giorno]) || $apertura === null || $chiusura === null || $apertura >= $chiusura;

        $query->execute([
            ':sede' => $sedeId,
            ':giorno' => $giorno,
            ':apertura' => $chiuso ? null : $apertura,
            ':chiusura' => $chiuso ? null : $chiusura,
            ':chiuso' => $chiuso ? 1 : 0,
            ':nuova_apertura' => $chiuso ? null : $apertura,
            ':nuova_chiusura' => $chiuso ? null : $chiusura,
            ':nuovo_chiuso' => $chiuso ? 1 : 0,
        ]);
    }

    return ['ok' => true, 'messaggio' => 'Orari aggiornati.'];
}

/**
 * Normalizza un orario scritto come hh:mm, oppure null se non è valido.
 */
function orario_valido(string $valore): ?string
{
    if (preg_match('/^([01][0-9]|2[0-3]):[0-5][0-9]$/', $valore) !== 1) {
        return null;
    }

    return $valore . ':00';
}

/**
 * Apre o chiude la sala eventi di una sede.
 */
function sala_cambia(PDO $pdo, int $sedeId, bool $disponibile, ?int $limite): array
{
    if ($limite !== null && $limite !== $sedeId) {
        return ['ok' => false, 'messaggio' => 'Questa sede non è la tua.'];
    }

    $pdo->prepare('UPDATE sedi SET sala_eventi_disponibile = :valore WHERE id = :id')
        ->execute([':valore' => $disponibile ? 1 : 0, ':id' => $sedeId]);

    return [
        'ok' => true,
        'messaggio' => $disponibile
            ? 'La sala accetta di nuovo prenotazioni.'
            : 'La sala non accetta piu prenotazioni.',
    ];
}
