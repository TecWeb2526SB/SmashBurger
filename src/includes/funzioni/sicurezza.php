<?php
/**
 * Protezione delle richieste che modificano dati.
 *
 * Ogni modulo che invia dati in POST porta con sé un token legato alla sessione; il
 * controller lo verifica prima di eseguire qualsiasi operazione. Il meccanismo è
 * descritto in OWASP, Cross-Site Request Forgery Prevention Cheat Sheet.
 */

/**
 * Restituisce il token della sessione, generandolo al primo utilizzo.
 */
function token_csrf(): string
{
    if (empty($_SESSION['token_csrf'])) {
        $_SESSION['token_csrf'] = bin2hex(random_bytes(32));
    }

    return $_SESSION['token_csrf'];
}

/**
 * Confronta il token ricevuto con quello della sessione, senza dipendere dalla
 * durata del confronto.
 */
function csrf_valido(?string $token): bool
{
    return is_string($token)
        && !empty($_SESSION['token_csrf'])
        && hash_equals($_SESSION['token_csrf'], $token);
}

/**
 * Restituisce il campo nascosto da inserire dentro ogni modulo in POST.
 */
function campo_csrf(): string
{
    return '<input type="hidden" name="token_csrf" value="' . e(token_csrf()) . '" />';
}
