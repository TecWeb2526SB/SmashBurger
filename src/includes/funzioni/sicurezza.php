<?php
/**
 * Protezione delle richieste che modificano dati.
 *
 * Ogni modulo in POST porta un token legato alla sessione: una pagina ospitata su un
 * altro sito non puo' leggerlo, quindi non puo' costruire una richiesta valida.
 */

/**
 * Restituisce il token della sessione, generandolo alla prima richiesta.
 */
function csrf_token(): string
{
    if (!isset($_SESSION['token_csrf'])) {
        $_SESSION['token_csrf'] = bin2hex(random_bytes(32));
    }

    return $_SESSION['token_csrf'];
}

/**
 * Campo nascosto da inserire in ogni modulo che invia dati in POST.
 */
function campo_csrf(): string
{
    return '<input type="hidden" name="token_csrf" value="' . e(csrf_token()) . '" />';
}

/**
 * Verifica il token arrivato con la richiesta.
 *
 * Il confronto avviene in tempo costante, cosi' non si possono ricavare informazioni
 * misurando quanto impiega a fallire.
 */
function csrf_valido(): bool
{
    $inviato = $_POST['token_csrf'] ?? '';

    return is_string($inviato)
        && isset($_SESSION['token_csrf'])
        && hash_equals($_SESSION['token_csrf'], $inviato);
}

/**
 * Interrompe la richiesta se non è un POST con token valido.
 *
 * Va chiamata dal controller prima di qualsiasi effetto sui dati.
 */
function richiedi_post_valido(): void
{
    if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST' || !csrf_valido()) {
        errore(403);
    }
}

/**
 * Legge un identificativo numerico dalla richiesta.
 *
 * Restituisce null quando il valore manca, non è numerico o non è positivo, cosi' il
 * controller puo' rispondere con la pagina 404 invece di interrogare il database con un
 * valore senza senso.
 */
function identificativo(array $sorgente, string $nome): ?int
{
    $valore = $sorgente[$nome] ?? null;

    if (!is_string($valore) && !is_int($valore)) {
        return null;
    }

    if (filter_var($valore, FILTER_VALIDATE_INT) === false || (int) $valore < 1) {
        return null;
    }

    return (int) $valore;
}

/**
 * Legge uno slug dalla richiesta.
 *
 * Ammette solo lettere minuscole, cifre e trattini: un valore di altra forma non puo'
 * esistere nel database, quindi la richiesta si chiude senza nemmeno interrogarlo.
 *
 * @param array|null $sorgente da dove leggere; per difetto la query string
 */
function slug_richiesto(string $nome, ?array $sorgente = null): ?string
{
    $valore = ($sorgente ?? $_GET)[$nome] ?? null;

    if (!is_string($valore) || preg_match('/^[a-z0-9-]{1,120}$/', $valore) !== 1) {
        return null;
    }

    return $valore;
}
