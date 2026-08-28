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
