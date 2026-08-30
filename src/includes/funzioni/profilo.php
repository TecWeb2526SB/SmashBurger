<?php
/**
 * Registrazione e gestione dei propri dati.
 *
 * Sta in un file separato da utenti.php, che si occupa solo di accesso e permessi: qui
 * ci sono i dati che una persona inserisce su di se'.
 *
 * Ogni controllo di questo file ha la sua gemella lato client negli attributi del
 * markup, ma quella che decide e' questa.
 */

/**
 * Controlla una password.
 *
 * Minimo otto caratteri e nessun tetto basso: un limite massimo stretto scarterebbe le
 * password generate dai gestori di password senza aggiungere sicurezza.
 */
function password_errore(string $password): ?string
{
    if (mb_strlen($password) < 8) {
        return 'La password deve avere almeno 8 caratteri.';
    }

    if (mb_strlen($password) > 200) {
        return 'La password non puo superare i 200 caratteri.';
    }

    // I caratteri di controllo non arrivano da una tastiera e non hanno motivo di stare
    // in una password: escluderli evita valori che nessuno potrebbe riscrivere.
    if (preg_match('/[\x00-\x1F\x7F]/u', $password) === 1) {
        return 'La password contiene caratteri non ammessi.';
    }

    return null;
}

/**
 * Controlla i dati di una registrazione.
 *
 * @return array errori indicizzati per campo
 */
function utente_errori_registrazione(PDO $pdo, array $dati): array
{
    $errori = [];

    foreach (['nome' => 'il nome', 'cognome' => 'il cognome'] as $campo => $etichetta) {
        $valore = trim((string) ($dati[$campo] ?? ''));
        if (mb_strlen($valore) < 2 || mb_strlen($valore) > 80) {
            $errori[$campo] = 'Scrivi ' . $etichetta . ', fra 2 e 80 caratteri.';
        }
    }

    $nomeUtente = trim((string) ($dati['nome_utente'] ?? ''));
    if (preg_match('/^[a-z0-9._-]{3,50}$/', $nomeUtente) !== 1) {
        $errori['nome_utente'] = 'Il nome utente accetta da 3 a 50 fra lettere minuscole, cifre, punto, trattino e trattino basso.';
    } elseif (utente_esiste($pdo, 'nome_utente', $nomeUtente)) {
        $errori['nome_utente'] = 'Questo nome utente e gia in uso.';
    }

    $email = trim((string) ($dati['email'] ?? ''));
    if (filter_var($email, FILTER_VALIDATE_EMAIL) === false || mb_strlen($email) > 160) {
        $errori['email'] = 'Scrivi un indirizzo email valido.';
    } elseif (utente_esiste($pdo, 'email', $email)) {
        $errori['email'] = 'Questo indirizzo email e gia registrato.';
    }

    $password = (string) ($dati['password'] ?? '');
    $errorePassword = password_errore($password);

    if ($errorePassword !== null) {
        $errori['password'] = $errorePassword;
    } elseif ($password !== (string) ($dati['conferma'] ?? '')) {
        $errori['conferma'] = 'Le due password non coincidono.';
    }

    return $errori;
}

/**
 * Verifica se un valore e' gia' usato da un altro account.
 *
 * Il nome della colonna non arriva mai da fuori: e' uno dei due valori scritti qui.
 */
function utente_esiste(PDO $pdo, string $colonna, string $valore, ?int $escludiId = null): bool
{
    $colonna = $colonna === 'email' ? 'email' : 'nome_utente';

    $sql = "SELECT 1 FROM utenti WHERE {$colonna} = :valore";
    $parametri = [':valore' => $valore];

    if ($escludiId !== null) {
        $sql .= ' AND id <> :id';
        $parametri[':id'] = $escludiId;
    }

    $query = $pdo->prepare($sql);
    $query->execute($parametri);

    return $query->fetchColumn() !== false;
}

/**
 * Crea un account cliente con dati gia' validati.
 */
function utente_registra(PDO $pdo, array $dati): void
{
    $query = $pdo->prepare(
        'INSERT INTO utenti (nome_utente, nome, cognome, email, password_hash, ruolo)
         VALUES (:nome_utente, :nome, :cognome, :email, :password_hash, :ruolo)'
    );
    $query->execute([
        ':nome_utente' => trim($dati['nome_utente']),
        ':nome' => trim($dati['nome']),
        ':cognome' => trim($dati['cognome']),
        ':email' => trim($dati['email']),
        ':password_hash' => password_hash($dati['password'], PASSWORD_DEFAULT),
        ':ruolo' => 'cliente',
    ]);
}

/**
 * Aggiorna nome, cognome ed email di un account.
 *
 * @return array ['ok' => bool, 'errori' => array]
 */
function utente_aggiorna_dati(PDO $pdo, int $id, array $dati): array
{
    $errori = [];

    foreach (['nome' => 'il nome', 'cognome' => 'il cognome'] as $campo => $etichetta) {
        $valore = trim((string) ($dati[$campo] ?? ''));
        if (mb_strlen($valore) < 2 || mb_strlen($valore) > 80) {
            $errori[$campo] = 'Scrivi ' . $etichetta . ', fra 2 e 80 caratteri.';
        }
    }

    $email = trim((string) ($dati['email'] ?? ''));
    if (filter_var($email, FILTER_VALIDATE_EMAIL) === false || mb_strlen($email) > 160) {
        $errori['email'] = 'Scrivi un indirizzo email valido.';
    } elseif (utente_esiste($pdo, 'email', $email, $id)) {
        $errori['email'] = 'Questo indirizzo email e gia registrato.';
    }

    if ($errori !== []) {
        return ['ok' => false, 'errori' => $errori];
    }

    $query = $pdo->prepare(
        'UPDATE utenti SET nome = :nome, cognome = :cognome, email = :email WHERE id = :id'
    );
    $query->execute([
        ':nome' => trim($dati['nome']),
        ':cognome' => trim($dati['cognome']),
        ':email' => $email,
        ':id' => $id,
    ]);

    return ['ok' => true, 'errori' => []];
}

/**
 * Cambia la password, dopo avere verificato quella attuale.
 */
function utente_cambia_password(PDO $pdo, int $id, string $attuale, string $nuova, string $conferma): array
{
    $query = $pdo->prepare('SELECT password_hash FROM utenti WHERE id = :id');
    $query->execute([':id' => $id]);
    $hash = $query->fetchColumn();

    if ($hash === false || !password_verify($attuale, $hash)) {
        return ['ok' => false, 'errori' => ['attuale' => 'La password attuale non e corretta.']];
    }

    $errore = password_errore($nuova);

    if ($errore !== null) {
        return ['ok' => false, 'errori' => ['nuova' => $errore]];
    }

    if ($nuova !== $conferma) {
        return ['ok' => false, 'errori' => ['conferma' => 'Le due password non coincidono.']];
    }

    $aggiorna = $pdo->prepare('UPDATE utenti SET password_hash = :hash WHERE id = :id');
    $aggiorna->execute([':hash' => password_hash($nuova, PASSWORD_DEFAULT), ':id' => $id]);

    return ['ok' => true, 'errori' => []];
}

/**
 * Salva l'indirizzo usato per le consegne a domicilio.
 */
function utente_salva_consegna(PDO $pdo, int $id, array $dati): array
{
    $errori = indirizzo_errori($dati);

    if ($errori !== []) {
        return ['ok' => false, 'errori' => $errori];
    }

    $query = $pdo->prepare(
        'UPDATE utenti SET indirizzo = :indirizzo, citta = :citta, provincia = :provincia,
                cap = :cap, paese = :paese, telefono = :telefono
          WHERE id = :id'
    );
    $query->execute([
        ':indirizzo' => trim($dati['indirizzo']),
        ':citta' => trim($dati['citta']),
        ':provincia' => strtoupper(trim($dati['provincia'])),
        ':cap' => trim($dati['cap']),
        ':paese' => trim($dati['paese']),
        ':telefono' => trim($dati['telefono']),
        ':id' => $id,
    ]);

    return ['ok' => true, 'errori' => []];
}

/**
 * Cancella l'indirizzo di consegna salvato.
 */
function utente_rimuovi_consegna(PDO $pdo, int $id): void
{
    $pdo->prepare(
        'UPDATE utenti SET indirizzo = NULL, citta = NULL, provincia = NULL,
                cap = NULL, paese = NULL, telefono = NULL
          WHERE id = :id'
    )->execute([':id' => $id]);
}

/**
 * Salva o rimuove il metodo di pagamento preferito.
 *
 * Del metodo si conserva soltanto l'etichetta: nessun dato di pagamento reale entra mai
 * nel database.
 */
function utente_salva_pagamento(PDO $pdo, int $id, ?string $metodo): void
{
    $metodo = in_array($metodo, ['carta', 'contanti'], true) ? $metodo : null;

    $pdo->prepare('UPDATE utenti SET metodo_pagamento_preferito = :metodo WHERE id = :id')
        ->execute([':metodo' => $metodo, ':id' => $id]);
}

/**
 * Cancella un account, insieme a carrello, ordini e prenotazioni collegati.
 *
 * Le chiavi esterne dello schema si occupano delle righe dipendenti.
 */
function utente_elimina(PDO $pdo, int $id): void
{
    $pdo->prepare('DELETE FROM utenti WHERE id = :id')->execute([':id' => $id]);
}

/**
 * Dati completi di un account, compresi quelli che utente_corrente() non porta con se'.
 */
function utente_completo(PDO $pdo, int $id): ?array
{
    $query = $pdo->prepare('SELECT * FROM utenti WHERE id = :id');
    $query->execute([':id' => $id]);
    $riga = $query->fetch();

    return $riga === false ? null : $riga;
}
