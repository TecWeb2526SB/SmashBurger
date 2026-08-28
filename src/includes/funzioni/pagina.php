<?php
/**
 * Funzioni di presentazione: protezione dell'output, formato dei prezzi e
 * composizione della pagina a partire da una vista.
 */

/**
 * Protegge un valore prima di stamparlo nel markup.
 *
 * Converte i caratteri speciali in entità HTML, comprese le virgolette singole, come
 * richiesto da un documento che deve restare valido anche come XML.
 */
function e(?string $valore): string
{
    return htmlspecialchars((string) $valore, ENT_QUOTES | ENT_XML1, 'UTF-8');
}

/**
 * Trasforma un importo in centesimi nella forma usata nelle pagine, ad esempio
 * 1090 diventa "10,90 euro".
 */
function prezzo(int $centesimi): string
{
    return number_format($centesimi / 100, 2, ',', '.') . ' euro';
}

/**
 * Compone una pagina completa: header, vista del contenuto e footer.
 *
 * Le chiavi di $dati diventano variabili locali disponibili nella vista e nei template.
 * Chiavi riconosciute dai template:
 * - titolo: contenuto del tag title, entro 60 caratteri
 * - descrizione: contenuto del meta description
 * - pagina: nome della pagina corrente, usato per segnalare la voce di menu attiva
 * - breadcrumb: elenco di coppie [etichetta, indirizzo], con indirizzo nullo sull'ultima
 *
 * @param string $vista  percorso della vista dentro views/, ad esempio 'pubbliche/home.php'
 */
function mostra_pagina(string $vista, array $dati = []): void
{
    extract($dati, EXTR_SKIP);

    include __DIR__ . '/../../views/template/header.php';
    include __DIR__ . '/../../views/' . $vista;
    include __DIR__ . '/../../views/template/footer.php';
}

/**
 * Restituisce le voci del menu principale come coppie etichetta/indirizzo, nell'ordine
 * di visualizzazione. Le voci cambiano in base allo stato di accesso e al ruolo.
 */
function menu_principale(): array
{
    $voci = [
        'Home' => url(),
        'Prodotti' => url('prodotti'),
        'Servizi' => url('servizi'),
        'Chi siamo' => url('chi-siamo'),
        'Sedi' => url('sedi'),
    ];

    if (!utente_autenticato()) {
        $voci['Accedi'] = url('accedi');

        return $voci;
    }

    $voci['Carrello'] = url('carrello');
    $voci['Area personale'] = url('area-personale');

    if (utente_e_amministratore()) {
        $voci['Controllo'] = url('controllo');
    }

    $voci['Esci'] = url('esci');

    return $voci;
}

/**
 * Salva un messaggio da mostrare dopo un redirect.
 *
 * @param string $tipo  'successo' oppure 'errore'
 */
function messaggio_imposta(string $tipo, string $testo): void
{
    $_SESSION['messaggio'] = ['tipo' => $tipo, 'testo' => $testo];
}

/**
 * Legge il messaggio salvato e lo rimuove dalla sessione, così compare una volta sola.
 */
function messaggio_leggi(): ?array
{
    $messaggio = $_SESSION['messaggio'] ?? null;
    unset($_SESSION['messaggio']);

    return $messaggio;
}

/**
 * Invia il browser a un'altra pagina del sito e interrompe l'esecuzione.
 *
 * Viene usata dopo ogni richiesta POST che modifica dati, così un aggiornamento della
 * pagina non ripete l'operazione.
 */
function vai_a(string $pagina = '', array $parametri = []): void
{
    header('Location: ' . url($pagina, $parametri));
    exit;
}

/**
 * Restituisce le sezioni del pannello di controllo come coppie etichetta/indirizzo.
 */
function menu_controllo(): array
{
    return [
        'Ordini' => url('controllo'),
        'Prodotti' => url('controllo-prodotti'),
        'Sedi' => url('controllo-sedi'),
        'Utenti' => url('controllo-utenti'),
    ];
}
