<?php
/**
 * Costanti dell'applicazione, avvio della sessione e costruzione degli indirizzi.
 *
 * Il file non gestisce richieste: viene incluso da risorse.php prima di ogni altra cosa.
 */

const NOME_SITO = 'Smash Burger';
const EMAIL_CONTATTO = 'informazioni@smashburger.it';
const TELEFONO_CONTATTO = '049 1234567';

// Cambia quando cambiano fogli di stile o script, per invalidare la cache del browser.
const VERSIONE_RISORSE = '1';

// Quantita' massima di uno stesso prodotto dentro un ordine.
const QUANTITA_MASSIMA = 20;

// Minuti di inattivita' dopo i quali un carrello viene considerato scaduto.
const MINUTI_CARRELLO = 15;

// Durata di una prenotazione della sala eventi, scelta da chi prenota dentro questi
// limiti. Sotto il minimo non sarebbe un evento, sopra il massimo la sala resterebbe
// bloccata per mezza giornata.
const MINUTI_MINIMI_PRENOTAZIONE = 90;
const MINUTI_MASSIMI_PRENOTAZIONE = 180;

// Passo con cui si propongono orari di inizio e durate.
const MINUTI_PASSO_PRENOTAZIONE = 30;

// Finestra su cui si calcola l'incasso mostrato al manager.
const GIORNI_INCASSO = 30;

/**
 * Restituisce true quando la richiesta corrente viaggia su HTTPS.
 *
 * Il server di consegna espone il sito anche in chiaro, quindi il cookie di sessione
 * diventa Secure solo quando serve davvero, altrimenti il browser lo scarterebbe.
 */
function richiesta_su_https(): bool
{
    return (($_SERVER['HTTPS'] ?? '') !== '' && ($_SERVER['HTTPS'] ?? '') !== 'off')
        || (int) ($_SERVER['SERVER_PORT'] ?? 80) === 443;
}

/**
 * Percorso della cartella che contiene l'applicazione, come lo vede il browser.
 *
 * Si ricava da SCRIPT_NAME togliendo la parte di percorso che il file occupa dentro
 * src/: un controller sta nella radice, una pagina di errore sta in errors/. Serve
 * perche' il sito deve poter stare in una sottocartella qualsiasi.
 */
function radice_applicazione(): string
{
    $script = $_SERVER['SCRIPT_NAME'] ?? '/index.php';
    $cartella = str_replace('\\', '/', dirname($script));
    $cartella = $cartella === '/' ? '' : rtrim($cartella, '/');

    // Le pagine di errore stanno un livello piu' in basso della radice.
    if (substr($cartella, -7) === '/errors') {
        $cartella = substr($cartella, 0, -7);
    }

    return $cartella . '/';
}

/**
 * Prefisso da anteporre ai collegamenti relativi per risalire alla radice del sito.
 *
 * Un indirizzo inesistente come /sito/uno/due viene servito dalla pagina 404, e il
 * browser risolve i collegamenti relativi rispetto a /sito/uno/: senza questo prefisso
 * la pagina di errore perderebbe stile, menu e collegamenti, che invece servono proprio
 * li' per rimettere in strada chi si e' perso.
 */
function risalita(): string
{
    $richiesta = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?? '/';
    $radice = radice_applicazione();

    if (strpos($richiesta, $radice) !== 0) {
        return '';
    }

    $resto = trim(substr($richiesta, strlen($radice)), '/');

    return $resto === '' ? '' : str_repeat('../', substr_count($resto, '/'));
}

/**
 * Costruisce l'indirizzo relativo di una pagina del sito.
 *
 * @param string $pagina     slug della pagina, stringa vuota per la home
 * @param array  $parametri  coppie nome/valore da mettere in query string
 */
function url(string $pagina = '', array $parametri = []): string
{
    $indirizzo = risalita() . $pagina;

    if ($indirizzo === '') {
        $indirizzo = './';
    }

    if ($parametri !== []) {
        $indirizzo .= '?' . http_build_query($parametri);
    }

    return $indirizzo;
}

/**
 * Indirizzo di una risorsa statica, come un'immagine o un foglio di stile.
 */
function risorsa(string $percorso, bool $versionata = false): string
{
    return risalita() . $percorso . ($versionata ? '?v=' . VERSIONE_RISORSE : '');
}

/**
 * Slug della pagina servita in questo momento, ricavato dal nome del file.
 */
function pagina_corrente(): string
{
    $nome = basename($_SERVER['SCRIPT_NAME'] ?? 'index.php', '.php');

    return $nome === 'index' ? '' : $nome;
}

// La sessione ha un nome proprio e accetta l'identificativo solo dal cookie: un
// identificativo scelto da chi attacca non viene adottato.
if (session_status() === PHP_SESSION_NONE) {
    ini_set('session.use_strict_mode', '1');
    ini_set('session.use_only_cookies', '1');

    session_name('smashburger_session');
    session_set_cookie_params([
        'lifetime' => 0,
        'path' => radice_applicazione(),
        'httponly' => true,
        'secure' => richiesta_su_https(),
        'samesite' => 'Lax',
    ]);
    session_start();
}
