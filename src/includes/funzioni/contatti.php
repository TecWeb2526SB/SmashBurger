<?php
/**
 * Messaggi inviati dal modulo di contatto.
 *
 * La validazione sta qui e non nel controller, cosi' è la stessa per il modulo
 * pubblico e per qualsiasi altro punto che dovesse salvare un messaggio. Ogni controllo
 * ha la sua gemella lato client, ma questa è quella che decide.
 */

/**
 * Categorie ammesse per un messaggio, come coppie valore/etichetta.
 */
function categorie_messaggio(): array
{
    return [
        'ordine' => 'Un ordine',
        'prenotazione' => 'Una prenotazione',
        'segnalazione' => 'Una segnalazione sul sito',
        'altro' => 'Altro',
    ];
}

/**
 * Stati in cui puo' trovarsi un messaggio.
 */
function stati_messaggio(): array
{
    return ['nuovo', 'preso in carico', 'chiuso'];
}

/**
 * Controlla i dati di un messaggio.
 *
 * @return array errori indicizzati per nome del campo; vuoto se i dati vanno bene
 */
function contatto_errori(array $dati): array
{
    $errori = [];

    $nome = trim((string) ($dati['nome'] ?? ''));
    if (mb_strlen($nome) < 2 || mb_strlen($nome) > 120) {
        $errori['nome'] = 'Scrivi il tuo nome, fra 2 e 120 caratteri.';
    }

    $email = trim((string) ($dati['email'] ?? ''));
    if (filter_var($email, FILTER_VALIDATE_EMAIL) === false || mb_strlen($email) > 160) {
        $errori['email'] = 'Scrivi un indirizzo email valido, per poterti rispondere.';
    }

    if (!array_key_exists((string) ($dati['categoria'] ?? ''), categorie_messaggio())) {
        $errori['categoria'] = 'Scegli uno degli argomenti proposti.';
    }

    $testo = trim((string) ($dati['testo'] ?? ''));
    if (mb_strlen($testo) < 10 || mb_strlen($testo) > CARATTERI_MESSAGGIO_CONTATTO) {
        $errori['testo'] = 'Scrivi il messaggio, fra 10 e ' . CARATTERI_MESSAGGIO_CONTATTO . ' caratteri.';
    }

    return $errori;
}

/**
 * Salva un messaggio già validato.
 */
function contatto_salva(PDO $pdo, array $dati): void
{
    $query = $pdo->prepare(
        'INSERT INTO messaggi_contatto (nome, email, categoria, testo)
         VALUES (:nome, :email, :categoria, :testo)'
    );
    $query->execute([
        ':nome' => trim($dati['nome']),
        ':email' => trim($dati['email']),
        ':categoria' => $dati['categoria'],
        ':testo' => trim($dati['testo']),
    ]);
}

/**
 * Messaggi ricevuti, dal piu' recente. Alimenta la sezione del pannello.
 */
function contatti_elenco(PDO $pdo): array
{
    return $pdo->query(
        'SELECT * FROM messaggi_contatto ORDER BY creato_il DESC'
    )->fetchAll();
}

/**
 * Cambia lo stato di un messaggio.
 *
 * @return array ['ok' => bool, 'messaggio' => string]
 */
function contatto_cambia_stato(PDO $pdo, int $id, string $stato): array
{
    if (!in_array($stato, stati_messaggio(), true)) {
        return ['ok' => false, 'messaggio' => 'Stato non riconosciuto.'];
    }

    $query = $pdo->prepare('UPDATE messaggi_contatto SET stato = :stato WHERE id = :id');
    $query->execute([':stato' => $stato, ':id' => $id]);

    if ($query->rowCount() === 0) {
        return ['ok' => false, 'messaggio' => 'Il messaggio non esiste piu.'];
    }

    return ['ok' => true, 'messaggio' => 'Stato del messaggio aggiornato.'];
}
