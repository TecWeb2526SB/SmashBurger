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
 * Le chiavi di $dati diventano variabili disponibili nella vista e nei template:
 * titolo, descrizione, pagina attiva nel menu, breadcrumb come coppie
 * [etichetta, indirizzo], e sediFooter, aggiunta qui e non dai controller.
 *
 * @param string $vista  percorso della vista dentro views/, ad esempio 'pubbliche/home.php'
 */
function mostra_pagina(string $vista, array $dati = []): void
{
    // Le sedi servono al footer di ogni pagina: la lettura sta qui e non nelle viste.
    global $pdo;
    $dati['sediFooter'] = sedi_tutte($pdo);

    extract($dati, EXTR_SKIP);

    include __DIR__ . '/../../views/template/header.php';
    include __DIR__ . '/../../views/' . $vista;
    include __DIR__ . '/../../views/template/footer.php';
}

/**
 * Voci del menu principale: la navigazione del sito, uguale per tutti.
 */
function menu_principale(): array
{
    return [
        'Home' => url(),
        'Menu' => url('prodotti'),
        'Servizi' => url('servizi'),
        'Chi siamo' => url('chi-siamo'),
        'Sedi' => url('sedi'),
    ];
}

/**
 * Voci che stanno a destra nell'header: riguardano la persona, non il sito, e
 * cambiano con lo stato di accesso e con il ruolo.
 */
function menu_azioni(): array
{
    if (!utente_autenticato()) {
        return [
            'Registrati' => url('registrati'),
            'Accedi' => url('accedi'),
        ];
    }

    $voci = [];

    if (utente_e_amministratore()) {
        $voci['Controllo'] = url('controllo');
    }

    $voci['Carrello'] = url('carrello');
    $voci['Area personale'] = url('area-personale');
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

/**
 * Restituisce il tema scelto dalla persona: 'chiaro', 'scuro' oppure stringa vuota
 * quando non è stata fatta nessuna scelta e vale l'impostazione del sistema.
 */
function tema_scelto(): string
{
    $tema = (string) ($_COOKIE['tema'] ?? '');

    return in_array($tema, ['chiaro', 'scuro'], true) ? $tema : '';
}

/**
 * Salva la scelta del tema in un cookie che dura un anno.
 *
 * Il cookie non contiene dati personali e serve solo a evitare che la pagina compaia con
 * i colori sbagliati prima che il foglio di stile venga applicato.
 */
function tema_salva(string $tema): void
{
    setcookie('tema', $tema, [
        'expires' => time() + 31536000,
        'path' => '/',
        'secure' => richiesta_su_https(),
        'httponly' => false,
        'samesite' => 'Lax',
    ]);
}
