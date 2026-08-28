# Convenzioni di codice

Regole operative per tutto il codice scritto durante la ricostruzione dell'applicazione.

Gerarchia dei documenti: `docs/VINCOLI_ESAME.md` e `REGOLE.md` sono vincolanti e
prevalgono su questo file; questo file prevale sulle preferenze personali di chi scrive.

---

## 1. Principi

1. **Una cosa sola per file.** Un controller instrada, una vista stampa markup, una
   funzione risolve un problema di dominio. Un file di avvio non gestisce richieste.
2. **Struttura prima della presentazione.** Il markup di una pagina si scrive e si valida
   senza foglio di stile; il CSS si scrive quando la struttura è definitiva.
3. **Il markup semantico è il selettore.** Si stila l'elemento dentro il suo contesto; una
   classe si aggiunge solo quando la semantica non basta a distinguere il caso.
4. **Funziona senza JavaScript.** Ogni azione ha un percorso completo lato server; il
   JavaScript aggiunge comodità, mai capacità.
5. **Meno superficie possibile.** Prima di aggiungere un componente, una classe, una
   tabella o una funzione si verifica se una esistente copre già il caso.

## 2. Lingua del progetto

Tutto è in italiano: nomi di file, funzioni, variabili, costanti, tabelle, colonne,
classi CSS, commenti, messaggi e contenuti.

Restano in inglese solo i termini che in italiano non hanno un equivalente corrente in
ambito tecnico, e i nomi imposti da standard, linguaggi o dalla specifica del corso.

### 2.1 Termini che restano in inglese

| Termine | Motivo |
| --- | --- |
| `id`, `slug`, `hash`, `token`, `cookie`, `log` | senza equivalente italiano d'uso |
| `email`, `password`, `username` (come `nome_utente` quando è un campo nostro) | uso corrente consolidato |
| `csrf`, `http`, `url`, `pdo`, `sql`, `json` | sigle e nomi propri di tecnologie |
| `header`, `footer`, `nav`, `main`, `section`, `article`, `form`, `label`, `button` | nomi di elementi HTML: mai tradotti nel markup |
| `flex`, `grid`, `media query`, `viewport` | nomi di funzionalità CSS |
| `breadcrumb` | "briciole di pane" non è d'uso corrente nel codice |
| `container`, `wrapper` | nome consolidato per l'elemento che delimita il contenuto |
| `toggle` | nessun equivalente italiano conciso per il controllo a due stati |
| `includes/`, `views/`, `styles/`, `scripts/`, `database/`, `images/` | struttura di directory raccomandata da `REGOLE.md` |
| `e()` | funzione di escaping, nome breve usato in ogni riga di markup |

### 2.2 Termini che si traducono sempre

| Inglese | Italiano da usare |
| --- | --- |
| card | scheda |
| grid (componente, non la funzione CSS) | griglia |
| badge | etichetta |
| alert / notice | avviso |
| checkout | pagamento |
| cart | carrello |
| branch / store | sede |
| product | prodotto |
| order | ordine |
| user | utente |
| role | ruolo |
| status | stato |
| available | disponibile |
| price | prezzo |
| quantity | quantità |

Nel dubbio si sceglie l'italiano. Un termine inglese nuovo si usa solo dopo averlo
aggiunto alla tabella 2.1 con la motivazione.

### 2.3 Caratteri ammessi

Si scrivono solo caratteri normali da tastiera italiana: lettere ASCII, cifre,
punteggiatura semplice e le lettere accentate dell'italiano (a con accento grave, e con
accento grave e acuto, i, o, u con accento grave). Sono ammessi anche i simboli presenti
sulla tastiera italiana, come euro e grado.

Vietati ovunque, cioè nel codice, nel markup, nei contenuti, nei commenti, nei messaggi
di commit e nella documentazione:

| Carattere | Da usare al suo posto |
| --- | --- |
| trattino lungo, trattino medio, segno meno tipografico | trattino semplice, oppure due punti o virgola |
| virgolette curve e apostrofo tipografico | virgolette dritte e apostrofo dritto |
| pallino, freccia, simbolo di paragrafo, puntini di sospensione in un solo carattere | parola corrispondente, oppure punteggiatura semplice |
| spazio non separabile e altri spazi speciali | spazio normale |
| emoji e simboli decorativi | nulla |

Gli elenchi si scrivono con il trattino nei file Markdown e con gli elementi di lista nel
markup HTML.

Le lettere accentate si scrivono direttamente, non come entità HTML: il documento
dichiara la codifica UTF-8 e il database usa `utf8mb4`.

## 3. Struttura delle directory

```text
src/
|-- *.php                controller di pagina (uno per URL)
|-- includes/
|   |-- risorse.php      soli require_once, in ordine
|   |-- configurazione.php  costanti, contatti, sessione
|   |-- database.php     connessione PDO
|   `-- funzioni/        funzioni di dominio, un file per area
|-- views/
|   |-- template/        intestazione.php, pie-pagina.php, breadcrumb.php
|   `-- <area>/          viste raggruppate per area
|-- styles/stile.css     unico foglio di stile
|-- scripts/script.js    unico file di comportamento
|-- images/
|-- errors/
`-- database/schema.sql
```

Regole:

- ogni URL corrisponde a un controller nella root di `src/`; niente controller annidati,
  niente instradamento interno;
- tutti i percorsi nel markup sono **relativi** (vincolo di consegna);
- gli URL non contengono l'estensione `.php` (riscrittura in `src/.htaccess`).

## 4. PHP

- Forma di ogni controller: `require_once includes/risorse.php`, lettura e validazione
  dell'input, chiamata alle funzioni di dominio, `mostra_pagina()` con i dati già pronti.
- Le viste non aprono connessioni, non eseguono query, non chiamano funzioni di dominio.
  Ricevono variabili già calcolate e le stampano. Sono ammessi solo `if`, `foreach` e la
  stampa con escaping.
- Ogni valore stampato nel markup passa da `e()` (`htmlspecialchars` con `ENT_QUOTES`).
- Tutte le query usano prepared statement con parametri nominati.
- Ogni richiesta `POST` verifica il token CSRF prima di produrre effetti.
- Ogni azione che modifica dati risponde con un redirect (`POST` seguito da `GET`).
- **Ogni validazione fatta in JavaScript esiste identica in PHP** (vincolo d'esame): il
  controllo lato client è un aiuto, quello lato server è la difesa.
- Le funzioni restituiscono dati oppure un array `['ok' => bool, 'messaggio' => string]`;
  non stampano nulla e non chiamano `exit`.
- Il codice deve girare su **PHP 8.1**: niente funzionalità introdotte dopo quella
  versione.
- Nomi: funzioni e variabili in `minuscolo_con_trattini_bassi`, costanti in `MAIUSCOLO`.
  Le funzioni hanno il prefisso dell'area (`utente_`, `carrello_`, `ordine_`, `sede_`,
  `catalogo_`, `sicurezza_`).
- Limiti: un file di funzioni non supera 300 righe, una vista 150 righe, una funzione
  40 righe. Superare un limite significa dividere.

## 5. HTML

Il markup è HTML5 **conforme anche alla sintassi XML**, come richiesto dai vincoli
d'esame. In pratica:

- ogni elemento è chiuso; gli elementi vuoti sono autochiusi (`<br />`, `<img ... />`,
  `<meta ... />`);
- nomi di elementi e attributi in minuscolo, valori sempre fra virgolette doppie;
- attributi booleani in forma estesa (`required="required"`, `hidden="hidden"`,
  `disabled="disabled"`);
- radice `<html xmlns="http://www.w3.org/1999/xhtml" lang="it" xml:lang="it">`;
- gerarchia di intestazioni `h1`-`h6` corretta, un solo `h1` per pagina;
- niente `<div>` o `<span>` dove esiste un elemento semantico adatto;
- **nessun attributo `style`, nessun blocco `<style>`, nessun blocco `<script>` inline,
  nessun gestore di evento inline**;
- le classi descrivono il ruolo del contenuto, mai l'aspetto: `scheda` sì, `rosso-grande`
  no;
- una classe si scrive sulla radice del componente; i figli si stilano per elemento
  (`.scheda h3`, `.griglia > li`);
- gli `id` esistono per tre motivi soltanto: destinazione di `<label for="...">`,
  riferimento `aria-*`, ancora di navigazione. Gli `id` non si stilano;
- gli stati si esprimono con attributi nativi (`aria-current`, `aria-expanded`,
  `disabled`, `hidden`, `open`), non con classi;
- i dati destinati al JavaScript passano da attributi `data-*` con nome italiano
  (`data-sede`, `data-prodotto`, `data-quantita`).

## 6. Catalogo dei componenti

Sono ammessi questi dodici componenti. Non se ne introducono altri senza aggiornare la
tabella.

| Componente | Markup | Uso |
| --- | --- | --- |
| Intestazione | `<header>` con `<nav>` e lista di link | uguale su tutte le pagine |
| Breadcrumb | `<nav aria-label="Percorso">` con `<ol>` | tutte le pagine tranne la home |
| Sezione | `<section>` con intestazione propria | blocco di contenuto |
| Scheda | `<article class="scheda">` | prodotto, sede, ordine |
| Griglia | `<ul class="griglia">` con `<li>` | elenchi di schede |
| Tabella | `<table>` con `<caption>` e `<th scope="...">` | elenchi tabellari del pannello |
| Modulo | `<form>` con `<fieldset>` e `<legend>` | ogni inserimento di dati |
| Campo | `<label>` + controllo + `<small>` per l'errore | dentro i moduli |
| Pulsante | `<button>`, variante `class="secondario"` | azioni |
| Avviso | `<p class="avviso" role="status">` | esito di un'operazione |
| Etichetta | `<span class="etichetta">` | stato ordine, disponibilità |
| Piè di pagina | `<footer>` con liste di link | uguale su tutte le pagine |

Il pannello di controllo usa gli stessi dodici componenti delle pagine pubbliche: non ha
un proprio insieme di stili.

## 7. CSS

Il foglio di stile si scrive **solo quando la struttura HTML di tutte le pagine è
completa e validata**. Fino ad allora le pagine restano senza stile.

- **Un solo file**: `src/styles/stile.css`, collegato con un solo `<link>`. Nessun
  `@import`, nessun secondo foglio, nessuno stile altrove.
- Lo stile per la stampa è una `@media print` dentro lo stesso file, in fondo.
- Ordine interno: proprietà personalizzate su `:root`, reset minimo, elementi base,
  layout, i dodici componenti nell'ordine della tabella, media query per schermo piccolo,
  media query di stampa.
- Colori, spaziature, raggi e ombre solo tramite proprietà personalizzate dichiarate su
  `:root`; nessun colore ripetuto nel corpo del foglio.
- Layout con Flexbox e Grid (valutati positivamente dai vincoli d'esame); misure in unità
  relative (`rem`, `em`, `%`, `ch`); interlinea minima `1.5`.
- Contrasto minimo 4.5:1 per il testo normale, 3:1 per il testo grande; l'informazione non
  è mai veicolata dal solo colore.
- Specificità: al massimo due livelli di selettore (`.scheda h3` sì,
  `.griglia .scheda .titolo a` no). Nessun `!important`, nessun selettore per `id`.
- Il tema scuro si ottiene con `prefers-color-scheme` e con una classe sulla radice
  decisa lato server in base a un cookie; nessuno script scrive stili.
- Solo CSS2/CSS3 validi secondo il validatore W3C.

## 8. JavaScript

- **Un solo file**: `src/scripts/script.js`, caricato con `defer` dall'intestazione
  comune. Nessun altro file, nessuno script inline, nessuna dipendenza esterna.
- Organizzazione interna: una funzione `inizializza<Area>()` per ogni comportamento, tutte
  richiamate da un unico ascoltatore di `DOMContentLoaded`.
- Ogni funzione parte da un elemento del DOM: se il selettore non trova nulla, esce
  subito senza errori. Così lo stesso file serve tutte le pagine.
- Il JavaScript non genera il markup della pagina e non sostituisce moduli funzionanti: al
  massimo intercetta un invio, ripete la stessa richiesta e aggiorna una porzione già
  presente.
- Nessuna scrittura di stili da codice: si aggiunge o si toglie una classe o un attributo.
- Nomi di funzioni e variabili in `camelCaseItaliano` (`inizializzaTema`,
  `aggiornaCarrello`, `quantitaCorrente`).

## 9. Database

- Nomi in italiano: tabelle al plurale (`prodotti`, `ordini`), colonne in
  `minuscolo_con_trattini_bassi`, chiavi esterne `<entita>_id` (`sede_id`, `prodotto_id`).
- Importi in centesimi, come interi (`prezzo_centesimi`), mai in virgola mobile.
- Date e orari in colonne `DATETIME`; i campi di tracciamento si chiamano `creato_il` e
  `aggiornato_il`.
- Schema in forma normale, con chiavi esterne dichiarate e vincoli di unicità espliciti.
- Compatibilità **MariaDB 10.6**.
- Le righe di un ordine congelano nome e prezzo del prodotto al momento dell'acquisto.

## 10. Commenti

I commenti descrivono **come funziona il codice**. Sono in italiano, al presente, in forma
impersonale.

Un commento può contenere:

- la funzione svolta da un file, da una funzione o da un blocco non ovvio;
- il formato dei dati attesi e restituiti;
- il motivo tecnico di una scelta non evidente (limite del linguaggio, comportamento del
  browser, vincolo di Apache o di MariaDB);
- riferimenti a fonti esterne stabili: standard W3C, WCAG, RFC, manuale PHP, MDN.

Un commento non contiene:

- riferimenti a conversazioni, decisioni di gruppo, richieste, revisioni o strumenti usati
  per scrivere il codice;
- storia delle modifiche ("prima era così", "aggiunto per il fix di..."): quella è la
  cronologia Git;
- codice commentato o segnaposto;
- ripetizioni di ciò che il codice già dice.

Ammesso:

```php
/**
 * Calcola il primo orario di ritiro disponibile per una sede.
 *
 * L'orario viene arrotondato al successivo intervallo di 15 minuti dentro la fascia di
 * apertura del giorno richiesto; se la sede è chiusa restituisce null.
 */
```

Non ammesso:

```php
// Semplificata perché la versione precedente era troppo complessa
```

## 11. Accessibilità

Obiettivo: WCAG 2.1 livello AA, verificato con Pa11y e Lighthouse.

- link "Vai al contenuto" come primo elemento del `<body>`;
- ogni controllo raggiungibile da tastiera con focus visibile (`:focus-visible`);
- ogni campo ha una `<label>` associata; il testo segnaposto non sostituisce l'etichetta;
- campi obbligatori con `required="required"`; errori associati con `aria-describedby` e
  riepilogati in cima al modulo;
- immagini di contenuto con `alt` descrittivo, immagini decorative con `alt=""`;
- tabelle con `<caption>` e intestazioni con `scope`;
- aggiornamenti dinamici annunciati con `role="status"`.

## 12. Budget quantitativi

Valori massimi per l'applicazione completa. Superarli richiede una semplificazione, non
un'eccezione.

| Metrica | Limite |
| --- | --- |
| File CSS | 1 |
| File JavaScript | 1 |
| Attributi `style`, blocchi `<style>`, script inline, gestori inline | 0 |
| Classi CSS distinte | 50 |
| `id` presenti nel markup | 30 |
| Righe di CSS | 1200 |
| Righe di JavaScript | 400 |
| Righe di un file di funzioni | 300 |
| Righe di una vista | 150 |
| Tabelle del database | 10 |
| Peso di una singola immagine | 300 KB |

## 13. Verifica prima di ogni commit

1. Il markup di ogni pagina toccata passa il validatore W3C senza errori ed è
   sintatticamente XML.
2. Il CSS passa il validatore W3C senza errori.
3. Pa11y non segnala errori sulle pagine toccate.
4. La pagina funziona con JavaScript disabilitato, e ogni validazione lato client ha la
   gemella lato server.
5. La navigazione da tastiera raggiunge ogni controllo con focus visibile.
6. I budget del punto 12 sono rispettati.
7. Il file non contiene caratteri fuori dall'elenco ammesso al punto 2.3.
8. Il messaggio di commit è di una riga sola, senza corpo, senza firme di co-autori
   e senza riferimenti a strumenti.
