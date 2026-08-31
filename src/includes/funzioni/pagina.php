<?php
/**
 * Funzioni di presentazione: protezione dell'output, formato dei prezzi, messaggi fra
 * una richiesta e l'altra e composizione della pagina a partire da una vista.
 */

/**
 * Protegge un valore prima di stamparlo nel markup.
 *
 * Converte i caratteri speciali in entita' HTML, comprese le virgolette singole, come
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
 * Formatta una data e ora del database nella forma letta nelle pagine.
 */
function data_ora(string $valore): string
{
    return date('d/m/Y H:i', strtotime($valore));
}

/**
 * Formatta una data del database nella forma letta nelle pagine.
 */
function data_breve(string $valore): string
{
    return date('d/m/Y', strtotime($valore));
}

/**
 * Descrive la fotografia esterna di una sede in base a cio' che si vede davvero.
 */
function testo_alternativo_sede(string $slug, string $citta): string
{
    $descrizioni = [
        'padova' => 'Esterno della sede Smash Burger di Padova sotto un portico, con insegna rossa e interni colorati visibili dalle vetrate.',
        'treviso' => 'Esterno della sede Smash Burger di Treviso, con ampia insegna rossa e bancone senape visibile dalle vetrate.',
        'vicenza' => 'Esterno della sede Smash Burger di Vicenza, con insegna rossa e interni verdi, rossi e senape visibili dalle vetrate.',
        'udine' => 'Esterno ad angolo della sede Smash Burger di Udine, con insegna rossa, scala interna e punto di ritiro visibili dalle vetrate.',
    ];

    return $descrizioni[$slug]
        ?? 'Esterno della sede Smash Burger di ' . $città . ', visto dalla strada.';
}

/**
 * Tema scelto da chi guarda: 'chiaro', 'scuro', oppure stringa vuota quando non ha
 * ancora scelto e vale la preferenza del sistema.
 *
 * Il valore arriva da un cookie e viene confrontato con un elenco chiuso: un cookie
 * manomesso non finisce mai dentro l'attributo class della pagina.
 */
function tema_corrente(): string
{
    $scelto = $_COOKIE[COOKIE_TEMA] ?? '';

    return in_array($scelto, ['chiaro', 'scuro'], true) ? $scelto : '';
}

/**
 * Salva il tema per un anno. Un valore fuori dall'elenco cancella il cookie e riporta
 * alla preferenza del sistema.
 */
function tema_salva(string $tema): void
{
    if (!in_array($tema, ['chiaro', 'scuro'], true)) {
        setcookie(COOKIE_TEMA, '', ['expires' => time() - 3600, 'path' => '/']);

        return;
    }

    setcookie(COOKIE_TEMA, $tema, [
        'expires' => time() + 31536000,
        'path' => '/',
        'httponly' => true,
        'samesite' => 'Lax',
    ]);
}

/**
 * Restituisce il markup di un'icona presa dalla raccolta in images/icone.svg.
 *
 * L'icona accompagna sempre un testo, quindi è nascosta ai lettori di schermo.
 */
function icona(string $nome): string
{
    return '<svg class="icona" aria-hidden="true" focusable="false">'
        . '<use href="' . e(risorsa('images/icone.svg', true)) . '#' . e($nome) . '" />'
        . '</svg>';
}

/**
 * Salva un messaggio da mostrare dopo un redirect.
 *
 * @param string $tipo 'successo' oppure 'errore'
 */
function messaggio_imposta(string $tipo, string $testo): void
{
    $_SESSION['messaggio'] = ['tipo' => $tipo, 'testo' => $testo];
}

/**
 * Legge il messaggio salvato e lo rimuove dalla sessione, cosi' compare una volta sola.
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
 * Viene usata dopo ogni richiesta POST che modifica dati, cosi' un aggiornamento della
 * pagina non ripete l'operazione. La destinazione è sempre una pagina nostra indicata
 * per nome, mai un valore costruito con dati della richiesta.
 */
function vai_a(string $pagina = '', array $parametri = []): void
{
    header('Location: ' . url($pagina, $parametri));
    exit;
}

/**
 * Mostra una pagina di errore con il proprio codice di stato e interrompe l'esecuzione.
 *
 * @param int $codice 401, 403, 404 oppure 500
 */
function errore(int $codice): void
{
    http_response_code($codice);
    require __DIR__ . '/../../errors/' . $codice . '.php';
    exit;
}

/**
 * Compone una pagina completa: intestazione, vista del contenuto e footer.
 *
 * Titolo e descrizione arrivano da includes/pagine.php e possono essere sostituiti da
 * $dati, come fanno le pagine di dettaglio di prodotto e sede, che li ricavano dai
 * propri contenuti.
 *
 * @param string $vista percorso della vista dentro views/, ad esempio 'pubbliche/home.php'
 * @param array  $dati  variabili disponibili nella vista, piu' titolo, descrizione e breadcrumb
 */
function mostra_pagina(string $vista, array $dati = []): void
{
    // La connessione è una variabile globale creata da database.php: qui serve solo a
    // ricavare il ruolo di chi guarda, che i template usano per il menu. Le viste non
    // interrogano il database da sole.
    global $pdo;

    $slugCorrente = pagina_corrente();
    $definizione = pagina_dati($slugCorrente) ?? [];

    // Quando la connessione non c'e', cioè nella pagina 500, il menu è quello di chi
    // non ha fatto l'accesso.
    $ruoloCorrente = isset($pdo) ? ruolo_corrente($pdo) : null;

    $titolo = $dati['titolo'] ?? ($definizione['titolo'] ?? NOME_SITO);
    $descrizione = $dati['descrizione'] ?? ($definizione['descrizione'] ?? '');
    $breadcrumb = $dati['breadcrumb'] ?? [];

    unset($dati['titolo'], $dati['descrizione'], $dati['breadcrumb']);
    extract($dati, EXTR_SKIP);

    require __DIR__ . '/../../views/template/header.php';
    require __DIR__ . '/../../views/' . $vista;
    require __DIR__ . '/../../views/template/footer.php';
}
