<?php
/**
 * Stato di accesso dell'utente.
 *
 * I dati dell'account restano in sessione dopo l'accesso; le funzioni di lettura sono
 * usate dai controller e dai template per decidere che cosa mostrare.
 */

/**
 * Indica se la sessione corrente appartiene a un utente che ha fatto l'accesso.
 */
function utente_autenticato(): bool
{
    return isset($_SESSION['utente']['id']);
}

/**
 * Restituisce i dati dell'utente in sessione, oppure null se nessuno ha fatto l'accesso.
 */
function utente_corrente(): ?array
{
    return $_SESSION['utente'] ?? null;
}

/**
 * Indica se l'utente in sessione può usare il pannello di controllo.
 */
function utente_e_amministratore(): bool
{
    return (($_SESSION['utente']['ruolo'] ?? '') === 'amministratore');
}

/**
 * Verifica il formato del nome utente: da 3 a 32 caratteri fra lettere, cifre, punto,
 * trattino e trattino basso.
 */
function utente_nome_valido(string $nome): bool
{
    return preg_match('/^[A-Za-z0-9._-]{3,32}$/', $nome) === 1;
}

/**
 * Verifica il formato dell'indirizzo email.
 */
function utente_email_valida(string $email): bool
{
    return strlen($email) <= 160 && filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

/**
 * Verifica la lunghezza minima della password scelta in fase di registrazione.
 */
function utente_password_valida(string $password): bool
{
    return strlen($password) >= 8 && strlen($password) <= 128;
}

/**
 * Restituisce l'utente con il nome indicato, oppure null se non esiste.
 */
function utente_per_nome(PDO $pdo, string $nome): ?array
{
    $query = $pdo->prepare('SELECT * FROM utenti WHERE nome_utente = :nome');
    $query->execute(['nome' => $nome]);
    $utente = $query->fetch();

    return $utente === false ? null : $utente;
}

/**
 * Controlla le credenziali e apre la sessione dell'utente.
 *
 * L'identificativo di sessione viene rigenerato dopo l'accesso per evitare che una
 * sessione nota prima dell'autenticazione resti valida dopo.
 *
 * @return array{ok: bool, messaggio: string}
 */
function utente_accedi(PDO $pdo, string $nome, string $password): array
{
    $utente = utente_per_nome($pdo, $nome);

    // Il messaggio non distingue fra nome inesistente e password errata, così non
    // rivela quali account esistono.
    if ($utente === null || !password_verify($password, $utente['password_hash'])) {
        return ['ok' => false, 'messaggio' => 'Nome utente o password non corretti.'];
    }

    if ((int) $utente['attivo'] !== 1) {
        return ['ok' => false, 'messaggio' => 'Questo account non è attivo.'];
    }

    session_regenerate_id(true);
    $_SESSION['utente'] = [
        'id' => (int) $utente['id'],
        'nome_utente' => $utente['nome_utente'],
        'email' => $utente['email'],
        'ruolo' => $utente['ruolo'],
    ];

    return ['ok' => true, 'messaggio' => 'Accesso effettuato.'];
}

/**
 * Chiude la sessione dell'utente e la ricrea vuota.
 */
function utente_esci(): void
{
    $_SESSION = [];

    $parametri = session_get_cookie_params();
    setcookie(session_name(), '', [
        'expires' => time() - 3600,
        'path' => $parametri['path'],
        'secure' => $parametri['secure'],
        'httponly' => true,
        'samesite' => 'Lax',
    ]);

    session_destroy();
    session_start();
    session_regenerate_id(true);
}

/**
 * Crea un nuovo account con ruolo cliente.
 *
 * @return array{ok: bool, errori: array<string, string>}
 */
function utente_registra(PDO $pdo, string $nome, string $email, string $password): array
{
    $errori = [];

    if (!utente_nome_valido($nome)) {
        $errori['nome_utente'] = 'Il nome utente deve avere da 3 a 32 caratteri fra lettere, cifre, punto, trattino e trattino basso.';
    }

    if (!utente_email_valida($email)) {
        $errori['email'] = 'Inserisci un indirizzo email valido.';
    }

    if (!utente_password_valida($password)) {
        $errori['password'] = 'La password deve avere almeno 8 caratteri.';
    }

    if ($errori !== []) {
        return ['ok' => false, 'errori' => $errori];
    }

    $query = $pdo->prepare('SELECT nome_utente, email FROM utenti WHERE nome_utente = :nome OR email = :email');
    $query->execute(['nome' => $nome, 'email' => $email]);

    foreach ($query->fetchAll() as $esistente) {
        if ($esistente['nome_utente'] === $nome) {
            $errori['nome_utente'] = 'Questo nome utente è già registrato.';
        }
        if ($esistente['email'] === $email) {
            $errori['email'] = 'Questa email è già registrata.';
        }
    }

    if ($errori !== []) {
        return ['ok' => false, 'errori' => $errori];
    }

    $inserimento = $pdo->prepare(
        'INSERT INTO utenti (nome_utente, email, password_hash, ruolo)
         VALUES (:nome, :email, :password_hash, :ruolo)'
    );
    $inserimento->execute([
        'nome' => $nome,
        'email' => $email,
        'password_hash' => password_hash($password, PASSWORD_DEFAULT),
        'ruolo' => 'cliente',
    ]);

    return ['ok' => true, 'errori' => []];
}

/**
 * Interrompe la pagina e manda all'accesso chi non ha una sessione aperta.
 */
function utente_richiedi_accesso(): void
{
    if (!utente_autenticato()) {
        messaggio_imposta('errore', 'Per continuare devi accedere.');
        vai_a('accedi');
    }
}

/**
 * Cambia l'indirizzo email dell'account, verificando che non sia già usato.
 *
 * @return array{ok: bool, messaggio: string}
 */
function utente_aggiorna_email(PDO $pdo, int $utenteId, string $email): array
{
    if (!utente_email_valida($email)) {
        return ['ok' => false, 'messaggio' => 'Inserisci un indirizzo email valido.'];
    }

    $query = $pdo->prepare('SELECT id FROM utenti WHERE email = :email AND id <> :id');
    $query->execute(['email' => $email, 'id' => $utenteId]);

    if ($query->fetch() !== false) {
        return ['ok' => false, 'messaggio' => 'Questa email è già registrata.'];
    }

    $pdo->prepare('UPDATE utenti SET email = :email WHERE id = :id')
        ->execute(['email' => $email, 'id' => $utenteId]);

    $_SESSION['utente']['email'] = $email;

    return ['ok' => true, 'messaggio' => 'Email aggiornata.'];
}

/**
 * Cambia la password dell'account dopo aver verificato quella attuale.
 *
 * @return array{ok: bool, messaggio: string}
 */
function utente_aggiorna_password(PDO $pdo, int $utenteId, string $attuale, string $nuova): array
{
    $query = $pdo->prepare('SELECT password_hash FROM utenti WHERE id = :id');
    $query->execute(['id' => $utenteId]);
    $hash = $query->fetchColumn();

    if ($hash === false || !password_verify($attuale, (string) $hash)) {
        return ['ok' => false, 'messaggio' => 'La password attuale non è corretta.'];
    }

    if (!utente_password_valida($nuova)) {
        return ['ok' => false, 'messaggio' => 'La nuova password deve avere almeno 8 caratteri.'];
    }

    $pdo->prepare('UPDATE utenti SET password_hash = :hash WHERE id = :id')
        ->execute(['hash' => password_hash($nuova, PASSWORD_DEFAULT), 'id' => $utenteId]);

    return ['ok' => true, 'messaggio' => 'Password aggiornata.'];
}

/**
 * Cancella l'account e, per effetto dei vincoli, i suoi ordini e il suo carrello.
 */
function utente_cancella(PDO $pdo, int $utenteId): void
{
    $pdo->prepare('DELETE FROM utenti WHERE id = :id')->execute(['id' => $utenteId]);
}


/**
 * Interrompe la pagina se chi la richiede non è un amministratore.
 */
function utente_richiedi_amministratore(): void
{
    utente_richiedi_accesso();

    if (!utente_e_amministratore()) {
        http_response_code(403);
        messaggio_imposta('errore', 'Questa pagina è riservata agli amministratori.');
        vai_a('area-personale');
    }
}
