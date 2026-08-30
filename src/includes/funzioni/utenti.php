<?php
/**
 * Accesso, uscita e controllo dei permessi.
 *
 * In sessione resta solo l'identificativo dell'utente: nome e ruolo si rileggono a ogni
 * richiesta, cosi' una disattivazione o un cambio di ruolo hanno effetto subito e non
 * alla prossima uscita.
 */

/**
 * Restituisce l'utente collegato, oppure null.
 *
 * Il risultato viene tenuto per la durata della richiesta, per non ripetere la query a
 * ogni chiamata.
 */
function utente_corrente(PDO $pdo): ?array
{
    static $utente = false;

    if ($utente !== false) {
        return $utente;
    }

    $id = $_SESSION['utente_id'] ?? null;

    if (!is_int($id)) {
        return $utente = null;
    }

    $query = $pdo->prepare(
        'SELECT id, nome_utente, nome, cognome, email, ruolo, attivo
           FROM utenti WHERE id = :id'
    );
    $query->execute([':id' => $id]);
    $riga = $query->fetch();

    if ($riga === false || (int) $riga['attivo'] !== 1) {
        utente_esci();

        return $utente = null;
    }

    return $utente = $riga;
}

/**
 * Ruolo dell'utente collegato, oppure null per chi non ha fatto l'accesso.
 */
function ruolo_corrente(PDO $pdo): ?string
{
    $utente = utente_corrente($pdo);

    return $utente === null ? null : $utente['ruolo'];
}

/**
 * Verifica le credenziali e apre la sessione.
 *
 * Il messaggio di errore e' sempre lo stesso, sia che il nome utente non esista sia che
 * la password sia sbagliata: cosi' non rivela quali account esistono.
 *
 * @return array ['ok' => bool, 'messaggio' => string]
 */
function utente_accedi(PDO $pdo, string $nomeUtente, string $password): array
{
    $generico = ['ok' => false, 'messaggio' => 'Nome utente o password non corretti.'];

    $query = $pdo->prepare(
        'SELECT id, password_hash, attivo FROM utenti WHERE nome_utente = :nome_utente'
    );
    $query->execute([':nome_utente' => $nomeUtente]);
    $riga = $query->fetch();

    if ($riga === false || !password_verify($password, $riga['password_hash'])) {
        return $generico;
    }

    if ((int) $riga['attivo'] !== 1) {
        return ['ok' => false, 'messaggio' => 'Questo account e stato disattivato.'];
    }

    // L'identificativo di sessione cambia dopo l'accesso, cosi' un identificativo noto
    // prima dell'autenticazione non vale piu' nulla.
    session_regenerate_id(true);
    $_SESSION['utente_id'] = (int) $riga['id'];

    return ['ok' => true, 'messaggio' => 'Accesso effettuato.'];
}

/**
 * Chiude la sessione e cancella il cookie dal browser.
 */
function utente_esci(): void
{
    $_SESSION = [];

    if (ini_get('session.use_cookies')) {
        $parametri = session_get_cookie_params();
        setcookie(session_name(), '', [
            'expires' => time() - 3600,
            'path' => $parametri['path'],
            'secure' => $parametri['secure'],
            'httponly' => $parametri['httponly'],
            'samesite' => $parametri['samesite'],
        ]);
    }

    session_destroy();
}

/**
 * Applica alla pagina corrente i ruoli dichiarati in includes/pagine.php.
 *
 * Chi non ha fatto l'accesso riceve la pagina 401, che spiega e porta al modulo di
 * accesso; chi lo ha fatto ma non ha i permessi riceve la 403. Il controllo sta qui e
 * non ripetuto in ogni controller, cosi' non puo' essere dimenticato.
 */
function richiedi_permesso(PDO $pdo): void
{
    $definizione = pagina_dati(pagina_corrente());

    if ($definizione === null || $definizione['ruoli'] === []) {
        return;
    }

    $utente = utente_corrente($pdo);

    if ($utente === null) {
        errore(401);
    }

    if (!ruolo_ammesso($definizione['ruoli'], $utente['ruolo'])) {
        errore(403);
    }
}

/**
 * Sede gestita dal manager collegato, oppure null per gli altri ruoli.
 *
 * Le pagine del pannello usano questo valore per restringere ogni query alla sede del
 * manager, mentre l'amministratore vede tutte le sedi.
 */
function sede_del_manager(PDO $pdo): ?array
{
    $utente = utente_corrente($pdo);

    if ($utente === null || $utente['ruolo'] !== 'manager') {
        return null;
    }

    $query = $pdo->prepare('SELECT * FROM sedi WHERE manager_id = :manager');
    $query->execute([':manager' => (int) $utente['id']]);
    $sede = $query->fetch();

    return $sede === false ? null : $sede;
}
