# Report di Progetto: Best Practice e Specifiche Tecniche (Tecnologie Web)

Questo documento definisce le specifiche architetturali, semantiche e di accessibilità necessarie per lo sviluppo del progetto di Tecnologie Web, richiedendo la completa separazione tra **Struttura (HTML)**, **Presentazione (CSS)** e **Comportamento (JS/PHP)**.

---

## 1. Architettura e Struttura delle Directory
Il progetto deve seguire un approccio modulare, preferibilmente basato sul pattern **Model-View-Controller (MVC)**, per mantenere il codice pulito e manutenibile. Il sito deve utilizzare esclusivamente **link relativi**.

### Struttura file raccomandata:
*   **`database/`**: Contiene i file `.sql` per la creazione e il popolamento del DB.
*   **`docs/`**: Documentazione del progetto.
*   **`includes/`**: Script PHP di utilità.
    *   `class/`, `functions/`: File con classi e funzioni specifiche.
    *   `config.php`, `variables.php`: Costanti e variabili globali (es. credenziali DB).
    *   `resources.php`: File centrale che include in ordine tutti gli script necessari tramite `require_once`.
*   **`styles/`**: Risorse di frontend.
    *   `css/`: Fogli di stile (es. `general.css`).
    *   `js/`: Script JavaScript (es. controlli form).
    *   `img/`: Immagini del sito (rigorosamente sotto 1 MB).
    *   `resources.css`: File che importa i vari CSS tramite `@import` per avere un unico `<link>` nell'HTML.
*   **`views/`**: File HTML serviti dinamicamente dal server.
    *   `template/`: Contiene frammenti ripetuti come `header.php` e `footer.php`.
*   **Root directory**: File principali (es. `index.php`, `about.php`) che fungono da controller, includendo prima `resources.php`, poi le view e i template.

---

## 2. Struttura HTML5 e Marcatura Semantica
Il codice deve rispettare lo standard **HTML5**, degradare in modo elegante e adottare markup pulito e chiuso correttamente.

*   **Dichiarazione Lingua**: Dichiarare la lingua nel tag `html` (`<html lang="it" xml:lang="it">`) e usare l'attributo `lang` per parole o frasi in lingua diversa da quella principale.
*   **Tag Strutturali**: Usare i tag semantici HTML5: `<header>`, `<nav>`, `<main>`, `<footer>`, `<aside>`, `<section>` per definire le aree della pagina. Non usare `<div>` o `<span>` se esiste un tag semantico appropriato.
*   **Intestazioni (h1 - h6)**: Devono essere usate rispettando un ordine gerarchico logico, per strutturare il documento e non per fini estetici.
*   **Cosmesi del Testo**: Usare `<em>` e `<strong>` per dare enfasi al testo invece di tag deprecati come `<i>` o `<b>`. Utilizzare `<abbr>` per gli acronimi e le abbreviazioni.
*   **Liste per la Navigazione**: Strutturare i menu di navigazione usando elenchi (`<ul>`, `<li>`) all'interno del tag `<nav>`.
*   **Immagini**: Usare il tag `<img>` solo per immagini di contenuto con attributo `alt` descrittivo (75-100 caratteri). Immagini decorative devono avere `alt=""` o essere inserite via CSS.

---

## 3. Requisiti di Accessibilità (WCAG 2.1)
Obiettivo minimo: conformità **WCAG 2.1 - Livello AA**.

*   **Navigazione da Tastiera**: Tutti gli elementi interattivi devono essere accessibili tramite tasto Tab con focus visivo evidente.
*   **Aiuti alla Navigazione**:
    *   Link invisibile "Vai al contenuto" subito dopo il tag `<body>`.
    *   Pulsante "Torna su".
    *   Evitare link circolari.
    *   Implementare **Breadcrumb** (Briciole di pane).
*   **Moduli (Form)**:
    *   Associare ogni input a una `<label>`.
    *   Non usare placeholder come sostituti delle etichette.
    *   Raggruppare voci simili con `<fieldset>` o `<optgroup>`.
    *   Identificare i campi obbligatori (`required` e `aria-required="true"`).
*   **Tabelle**: Usare solo per dati tabellari. Associare intestazioni tramite `scope`, `headers` o `id`.
*   **WAI-ARIA**: Usare ruoli (`role`) e stati (`aria-*`) per arricchire la semantica degli elementi interattivi non nativi.

---

## 4. Fogli di Stile (CSS) e Layout Front-end
La presentazione deve risiedere esclusivamente nei file CSS.

*   **Layout**: Raccomandato l'uso di **Flex** e **Grid**. Preferibili layout fluidi/elastici.
*   **Classi**: Usare le classi per definire il contenuto/ruolo, non il comportamento.
*   **Tipografia**: Dimensioni in unità relative (`em` o `%`). Interlinea di almeno `1.5em`.
*   **Colore e Contrasto**: Contrasto minimo **4.5:1** (testo normale) e **3:1** (testo grande). Non veicolare informazioni usando solo il colore.
*   **Media Queries**: CSS specifici per dispositivi e obbligatorio foglio di stile per la **stampa**.

---

## 5. Back-end (PHP) e Comportamento (JavaScript)
*   **Gestione Dati**: Script per CRUD (Create, Read, Update, Delete) salvati in un database normalizzato.
*   **Sicurezza e Validazione**: Controlli rigorosi sia lato Client (JS) che lato Server (PHP).
*   **Graceful Degradation**: Le funzionalità fondamentali devono funzionare anche senza JavaScript via `<noscript>` o fallback robusti.

---

## 6. Validazione e SEO
*   **Validazione**: Superare i test W3C Validator e Total Validator senza errori.
*   **Test Accessibilità**: Testare con Lighthouse, WAVE o Mauve.
*   **SEO**: Utilizzare tag `<title>` (max 60 car.), `<meta description>` e codice pulito.

---

### Note per lo Sviluppo (Utenti di test)
I dati di accesso obbligatori per i test sono:
*   **Utente Amministratore**: `admin` / `admin`
*   **Utente Semplice**: `user` / `user`
