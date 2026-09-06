# Regole del progetto

File unico delle regole: vincoli imposti dal corso, convenzioni di scrittura del codice e
procedura di consegna.

Il documento è diviso in tre parti. La **parte I** raccoglie quello che il corso impone e
non è negoziabile: il mancato rispetto anche di una sola specifica tecnica comporta la non
sufficienza. La **parte II** raccoglie le convenzioni scelte dal gruppo, che si possono
cambiare per decisione condivisa purché restino compatibili con la parte I. La **parte
III** descrive come si lavora e come si consegna.

Il perimetro funzionale del prodotto, cioè ruoli, funzionalità, dati e pagine, sta in
`docs/ANALISI_REQUISITI.md`. Dove i due documenti si toccano, questo prevale sulle
convenzioni ma mai sui vincoli d'esame.

---

# Parte I. Vincoli d'esame

## 1. Specifiche tecniche obbligatorie

1. Markup in XHTML Strict oppure HTML5. Le pagine HTML5 devono degradare in modo elegante
   e **rispettare la sintassi XML**.
2. Layout realizzato con CSS puri (CSS2 o CSS3). L'uso corretto e ragionevole di Flexbox e
   Grid è valutato positivamente.
3. Separazione completa fra contenuto, presentazione e comportamento.
4. Accessibilità per tutte le categorie di utenti.
5. Contenuti organizzati in modo che ogni utente li reperisca facilmente.
6. Pagine con script PHP che raccolgono e pubblicano dati inseriti dagli utenti,
   **comprese modifica e cancellazione** degli stessi.
7. Controllo dell'input **sia lato client sia lato server**.
8. Dati degli utenti salvati su database, preferibilmente in forma normale.
9. Pagine accessibili indipendentemente dal browser e dalle dimensioni dello schermo; le
   considerazioni sui diversi dispositivi sono valutate positivamente.

## 2. Struttura del progetto richiesta dal corso

Il progetto segue il pattern **Model-View-Controller**. Il sito usa esclusivamente **link
relativi**, così da poter essere installato su server o cartelle diverse.

Struttura di directory raccomandata:

- `database/`: file `.sql` per creazione e popolamento del database;
- `docs/`: documentazione del progetto;
- `includes/`: script PHP di utilità, costanti e variabili globali, più un file centrale
  che include in ordine tutti gli script necessari;
- `styles/`: risorse di frontend, cioè fogli di stile, script e immagini;
- `views/`: file serviti dinamicamente dal server, con una sottocartella `template/` per i
  frammenti ripetuti come header e footer;
- radice: i file principali che fungono da controller.

## 3. Marcatura semantica e accessibilità

Obiettivo minimo: conformità **WCAG 2.1 livello AA**.

- lingua dichiarata sul tag `html` e attributo `lang` sulle parole in lingua diversa;
- tag strutturali semantici (`header`, `nav`, `main`, `footer`, `aside`, `section`), mai
  `div` o `span` dove esiste un elemento adatto;
- intestazioni `h1`-`h6` in ordine gerarchico logico, non per fini estetici;
- `em` e `strong` per l'enfasi, `abbr` per acronimi e abbreviazioni;
- menu di navigazione come liste dentro `nav`;
- navigazione da tastiera completa, con focus visibile;
- link "Vai al contenuto" subito dopo `body`, pulsante "Torna su", breadcrumb, nessun link
  circolare;
- moduli con `label` associata a ogni controllo, nessun placeholder al posto
  dell'etichetta, voci simili raggruppate con `fieldset` o `optgroup`, campi obbligatori
  identificati;
- tabelle solo per dati tabellari, con intestazioni associate tramite `scope`, `headers` o
  `id`;
- ruoli e stati WAI-ARIA per arricchire la semantica degli elementi interattivi non
  nativi;
- immagini di contenuto con `alt` descrittivo, immagini decorative con `alt=""` o inserite
  via CSS.

## 4. Presentazione e comportamento

- la presentazione risiede esclusivamente nei fogli di stile;
- layout con Flex e Grid, preferibilmente fluidi o elastici;
- tipografia in unità relative, interlinea di almeno `1.5`;
- contrasto minimo **4.5:1** per il testo normale e **3:1** per il testo grande;
  l'informazione non è mai veicolata dal solo colore;
- media query per i diversi dispositivi e foglio di stile per la **stampa**, obbligatorio;
- il comportamento risiede esclusivamente nel file di script.

Il vincolo che imponeva a ogni funzionalità di funzionare anche senza JavaScript non e'
piu' in vigore. Le operazioni del pannello e del carrello si compiono con lo script, che
ripete la stessa richiesta del modulo e aggiorna la porzione di pagina interessata; il
percorso lato server resta comunque completo, perche' e' quello che lo script richiama e
perche' e' li' che stanno i controlli (sezione 17).

## 5. Regole aggiuntive comunicate a lezione

Prescrizioni date direttamente dalla docente. Hanno lo stesso peso dei punti precedenti.

### 5.1 Pagine di errore

1. **404**: la pagina di errore deve essere quella del sito, non quella predefinita del
   browser o del motore di ricerca, e deve **aiutare la persona a ripartire**, quindi
   contiene il menu di navigazione.
2. Le pagine di errore **non si dichiarano con percorsi assoluti**. Un percorso assoluto
   rompe l'installazione in sottocartella e fa comparire la pagina di errore generica.
   Nel progetto le pagine di errore sono raggiunte con una regola di riscrittura relativa
   e impostano da sole il proprio codice di stato.
3. Un parametro non valido deve produrre la **nostra** pagina 404, non un errore di PHP e
   non una pagina vuota. Vale per ogni indirizzo che accetta un identificativo: se il
   valore non esiste, oppure non ha nemmeno il formato giusto, ad esempio una lettera dove
   è atteso un numero, la richiesta si chiude con la pagina 404 del sito e con lo stato
   HTTP 404.
4. **401**: chi tenta di aprire una risorsa che richiede l'accesso riceve una pagina
   dedicata che lo spiega e porta al modulo di accesso. Si distingue dal 403, che riguarda
   chi ha fatto l'accesso ma non ha i permessi per quella risorsa.
5. **500**: gli errori del server hanno una pagina propria. Il dettaglio tecnico non
   compare mai nella pagina, finisce nel log del server.

### 5.2 Immagini e testi alternativi

1. Le immagini decorative usano `alt=""`, **mai `alt=" "` con uno spazio**: lo spazio non
   è una stringa vuota, il lettore di schermo lo annuncia e sembra che la descrizione sia
   stata dimenticata.
2. Se il logo porta alla home, il suo testo alternativo dice **Home**: descrive la
   destinazione del collegamento, non l'aspetto dell'immagine.
3. Le immagini di contenuto si descrivono per intero. Se l'immagine contiene un elemento
   ironico o un gioco di parole, il testo alternativo lo rende esplicito, altrimenti
   l'informazione va persa per chi non vede l'immagine. Esempio: la foto di una carrozza
   con due cavalli in cui il pannello dei cavalli motore riporta il numero due va
   descritta comprendendo anche quel dettaglio, perché è il senso dell'immagine.

### 5.3 Schermi piccoli

Il sito si usa dal telefono. **Non devono esserci scorrimenti orizzontali** su nessuna
pagina e a nessuna larghezza. Vale anche per gli elementi che tendono a sfondare, cioè
tabelle, blocchi di codice, immagini a larghezza fissa e parole lunghe.

### 5.4 Contenuto della relazione

La relazione deve dichiarare, oltre a quanto elencato al punto 7:

1. chi sono gli utenti del sito;
2. le parole chiave scelte e il motivo;
3. se i colori sono stati **verificati** oppure scelti a caso, riportando le misure di
   contrasto;
4. quali test sono stati eseguiti, compresa la prova con un lettore di schermo, e con
   quali strumenti;
5. la modalità di lavoro del gruppo e la divisione dei compiti;
6. la struttura del sito;
7. se immagini e testi sono stati prodotti con strumenti di intelligenza artificiale.

I test si eseguono e si descrivono **anche quando gli strumenti automatici danno esito
pieno**: un punteggio pieno senza prove manuali a supporto non dimostra che il sito sia
davvero utilizzabile.

## 6. Utenze di prova

Obbligatorie, con login e password identici. Serve una coppia per **ogni** classe di
utenza presente nel sito.

| Classe di utenza | Login | Password |
| --- | --- | --- |
| Amministratore | `admin` | `admin` |
| Manager di sede | `manager` | `manager` |
| Utente semplice | `user` | `user` |

## 7. Relazione

Documento che accompagna il progetto. Deve contenere, in prima pagina:

- indirizzo web del sito;
- una coppia login/password per ogni classe di utenza;
- indirizzo email del referente del gruppo.

Nel corpo deve illustrare l'analisi iniziale delle caratteristiche degli utenti, le
possibili ricerche sui motori di ricerca a cui il sito deve rispondere, le fasi di
progettazione, realizzazione e test, e il ruolo svolto da ciascun componente del gruppo,
oltre ai punti della sezione 5.4.

## 8. Ambiente del server di consegna

| Componente | Versione |
| --- | --- |
| Sistema operativo | Ubuntu 22.04 |
| PHP | 8.1 |
| Database | MariaDB 10.6 |

Nessuna funzionalità introdotta dopo PHP 8.1 o MariaDB 10.6 può essere usata. L'ambiente
di sviluppo locale si allinea a queste versioni, altrimenti il codice può funzionare in
locale e rompersi sul server di consegna.

## 9. Validazione richiesta

- markup e fogli di stile passano i validatori del W3C senza errori;
- l'accessibilità si verifica con strumenti automatici e con prove manuali.

---

# Parte II. Convenzioni di codice

## 10. Principi

1. **Una cosa sola per file.** Un controller instrada, una vista stampa markup, una
   funzione risolve un problema di dominio. Un file di avvio non gestisce richieste.
2. **Struttura prima della presentazione.** Il markup di una pagina si scrive e si valida
   senza foglio di stile; il CSS si scrive quando la struttura è definitiva.
3. **Il markup semantico è il selettore.** Si stila l'elemento dentro il suo contesto; una
   classe si aggiunge solo quando la semantica non basta a distinguere il caso.
4. **Il server decide.** Ogni azione ha un percorso completo lato server, ed e' quello
   che lo script richiama: il JavaScript cambia il modo in cui si arriva all'operazione,
   mai quello in cui viene autorizzata, validata o eseguita.
5. **Meno superficie possibile.** Prima di aggiungere un componente, una classe, una
   tabella o una funzione si verifica se una esistente copre già il caso.

## 11. Lingua del progetto

Tutto è in italiano: nomi di file, funzioni, variabili, costanti, tabelle, colonne, classi
CSS, commenti, messaggi e contenuti.

Restano in inglese solo i termini che in italiano non hanno un equivalente corrente in
ambito tecnico, e i nomi imposti da standard, linguaggi o dalla specifica del corso.

### 11.1 Termini che restano in inglese

| Termine | Motivo |
| --- | --- |
| `id`, `slug`, `hash`, `token`, `cookie`, `log` | senza equivalente italiano d'uso |
| `email`, `password`, `username` (come `nome_utente` quando è un campo nostro) | uso corrente consolidato |
| `csrf`, `http`, `url`, `pdo`, `sql`, `json` | sigle e nomi propri di tecnologie |
| `header`, `footer`, `nav`, `main`, `section`, `article`, `form`, `label`, `button` | nomi di elementi HTML: mai tradotti, nel markup e nei nomi dei file dei template |
| `flex`, `grid`, `media query`, `viewport` | nomi di funzionalità CSS |
| `breadcrumb` | "briciole di pane" non è d'uso corrente nel codice |
| `container`, `wrapper` | nome consolidato per l'elemento che delimita il contenuto |
| `toggle` | nessun equivalente italiano conciso per il controllo a due stati |
| `includes/`, `views/`, `styles/`, `scripts/`, `database/`, `images/` | struttura di directory raccomandata dal corso |
| `e()` | funzione di escaping, nome breve usato in ogni riga di markup |
| `manager` | ruolo del responsabile di sede: un traducente esiste ("responsabile"), il termine inglese resta per scelta esplicita del gruppo |

### 11.2 Termini che si traducono sempre

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
aggiunto alla tabella 11.1 con la motivazione.

### 11.3 Caratteri ammessi

Si scrivono solo caratteri normali da tastiera italiana: lettere ASCII, cifre,
punteggiatura semplice e le lettere accentate dell'italiano. Sono ammessi anche i simboli
presenti sulla tastiera italiana, come euro e grado.

Vietati ovunque, cioè nel codice, nel markup, nei contenuti, nei commenti, nei messaggi di
commit e nella documentazione:

| Carattere | Da usare al suo posto |
| --- | --- |
| trattino lungo, trattino medio, segno meno tipografico | trattino semplice, oppure due punti o virgola |
| virgolette curve e apostrofo tipografico | virgolette dritte e apostrofo dritto |
| pallino, freccia, simbolo di paragrafo, puntini di sospensione in un solo carattere | parola corrispondente, oppure punteggiatura semplice |
| spazio non separabile e altri spazi speciali | spazio normale |
| emoji e simboli decorativi | nulla |

Gli elenchi si scrivono con il trattino nei file Markdown e con gli elementi di lista nel
markup HTML. Le lettere accentate si scrivono direttamente, non come entità HTML: il
documento dichiara la codifica UTF-8 e il database usa `utf8mb4`.

## 12. Architettura e struttura delle directory

Il pattern è **Page Controller**: un file per URL nella radice di `src/` fa da controller.
La logica di dominio in `includes/funzioni/` è **Transaction Script**, funzioni per
operazione, non oggetti di dominio. Le viste in `views/` sono **Template View**, PHP
semplice dentro il markup, senza motore di template. Nessun front controller, nessun
router, nessuna classe con autoload: alla scala di questo progetto, circa 30 pagine,
l'indirezione in più non semplifica nulla e va contro il principio 5.

```text
src/
|-- *.php                controller di pagina (uno per URL)
|-- includes/
|   |-- risorse.php      soli require_once, in ordine
|   |-- configurazione.php  costanti, contatti, sessione
|   |-- pagine.php       elenco unico delle pagine
|   |-- database.php     connessione PDO
|   `-- funzioni/        funzioni di dominio, un file per area
|-- views/
|   |-- template/        header.php, footer.php, breadcrumb.php
|   `-- <area>/          viste raggruppate per area
|-- styles/              stile.css, mobile.css, stampa.css
|-- scripts/script.js    unico file di comportamento
|-- images/
|-- errors/
`-- database/schema.sql
```

Regole:

- ogni URL corrisponde a un controller nella radice di `src/`; niente controller annidati,
  niente instradamento interno;
- tutti i percorsi nel markup sono **relativi** (vincolo di consegna);
- gli URL non contengono l'estensione `.php` (riscrittura in `src/.htaccess`);
- il dettaglio di un'entità sta su un controller al singolare, distinto da quello
  dell'elenco, e riceve l'identificativo per query string sullo `slug`
  (`prodotto?slug=...`, `sede?slug=...`);
- ogni pagina è descritta una sola volta, in `includes/pagine.php`: slug, ruoli ammessi,
  posizione nel menu o nel piede di pagina. Da questo elenco derivano il menu, il
  controllo di accesso di ogni controller, `sitemap.xml` e l'elenco delle pagine riservate
  dei controlli di qualità, invece di essere riscritti in quattro punti diversi.

## 13. PHP

- Forma di ogni controller: `require_once includes/risorse.php`, lettura e validazione
  dell'input, chiamata alle funzioni di dominio, `mostra_pagina()` con i dati già pronti.
- Le viste non aprono connessioni, non eseguono query, non chiamano funzioni di dominio.
  Ricevono variabili già calcolate e le stampano. Sono ammessi solo `if`, `foreach` e la
  stampa con escaping.
- Ogni valore stampato nel markup passa da `e()` (`htmlspecialchars` con `ENT_QUOTES`).
- Tutte le query usano prepared statement con parametri nominati.
- Ogni richiesta `POST` verifica il token CSRF prima di produrre effetti.
- Ogni azione che modifica dati risponde con un redirect (`POST` seguito da `GET`).
- Ogni azione che cancella dati passa da una richiesta di conferma: il collegamento porta
  alla pagina in `GET` con il dato da cancellare, la pagina mostra che cosa sta per
  succedere e la cancellazione avviene con il modulo in `POST`.
- **Ogni validazione fatta in JavaScript esiste identica in PHP** (vincolo d'esame): il
  controllo lato client è un aiuto, quello lato server è la difesa.
- Un identificativo che arriva dall'esterno si valida nel formato e nell'esistenza. Se non
  supera il controllo, il controller mostra la pagina 404 del sito con lo stato HTTP 404
  (parte I, sezione 5.1).
- Le funzioni restituiscono dati oppure un array `['ok' => bool, 'messaggio' => string]`;
  non stampano nulla e non chiamano `exit`.
- Il codice deve girare su **PHP 8.1**: niente funzionalità introdotte dopo quella
  versione.
- Nomi: funzioni e variabili in `minuscolo_con_trattini_bassi`, costanti in `MAIUSCOLO`.
  Le funzioni hanno il prefisso dell'area (`utente_`, `carrello_`, `ordine_`, `sede_`,
  `catalogo_`, `sicurezza_`).
- Limiti: un file di funzioni non supera 300 righe, una vista 150 righe, una funzione 40
  righe. Superare un limite significa dividere.

## 14. HTML

Il markup è HTML5 **conforme anche alla sintassi XML**. In pratica:

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

## 15. Catalogo dei componenti

Base di partenza: questi quindici componenti coprono il funzionale. In fase di stile
(§22) il catalogo può crescere, ma non in silenzio: un componente nuovo si aggiunge qui
con una riga, nello stesso momento in cui si scrive il suo CSS, non dopo.

| Componente | Markup | Uso |
| --- | --- | --- |
| Header | `<header>` con `<nav>` e lista di link | uguale su tutte le pagine |
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
| Filtri | `<nav class="filtri">` con lista di collegamenti | restringere un elenco |
| Elenco di scelte | `<ul class="scelte">` con controllo ed etichetta per riga | opzioni alternative dentro un modulo |
| Navigazione di pagina | `<p class="navigazione-pagina">` | chiude le pagine interne: ritorno a sinistra, avanzamento a destra |
| Footer | `<footer>` con liste di link | uguale su tutte le pagine |
| Richiamo | `<section class="richiamo">` con titolo, testo e un pulsante | blocco largo che manda a un'altra pagina raccontando un motivo per andarci (la sala eventi verso Servizi, il metodo di cottura verso Chi siamo), invece di un elenco di collegamenti |
| Comando di tabella | `<p class="azioni azioni-tabella">` con un pulsante | apre una scheda nuova sopra un elenco tabellare, allineato al bordo destro della tabella |
| Comandi di riga | `<p class="azioni-riga">` con i pulsanti della riga | azioni di una singola riga di tabella, sempre nello stesso ordine |
| Apertura di errore | `<section class="apertura-errore">` con `<p class="codice-errore">`, titolo, testo e diapositiva | le quattro pagine di errore: codice di stato, che cosa è successo e come ripartire |
| Ripartenza | `<ul class="ripartenza">` con collegamenti-pulsante | pagine da cui riprendere la navigazione dopo un 404 |

Il pannello di controllo usa gli stessi componenti delle pagine pubbliche: non ha un
proprio insieme di stili. Vale sia per il manager sia per l'amministratore, che
condividono le stesse viste filtrate per sede.

Le pagine interne, cioè quelle che fanno parte di un percorso come carrello, conferma
d'ordine, ricevuta e profilo, si chiudono sempre con la navigazione di pagina: a sinistra
il ritorno al passo precedente, a destra l'avanzamento. Il pulsante che porta avanti usa
il colore delle conferme, quello che torna indietro resta neutro.

Le icone stanno tutte in `images/icone.svg` e si richiamano dal markup con la funzione
`icona()`. Sono decorative e nascoste ai lettori di schermo: accompagnano sempre un testo,
e quando il testo non è visibile viene messo in un elemento con la classe `solo-lettori`.
Nessuna libreria di icone esterna: il sito non carica risorse da altri domini.

Le varianti si esprimono con attributi e non con classi nuove: `data-tipo` distingue
avvisi ed etichette (`attenzione`, `errore`, `positivo`, `negativo`), `data-stato`
distingue i campi (`ok`, `attenzione`, `errore`).

Nelle tabelle, `data-colonna="azioni"` marca l'intestazione e ogni cella di una colonna
che contiene soltanto comandi. A video non cambia niente; il foglio di stampa la nasconde
per intero, perché su carta resterebbe una colonna vuota con il suo titolo. Il marcatore
va messo sul `th` **e** su tutti i `td` della colonna: se ne manca uno le celle non sono
più in numero pari e la tabella si scompone.

## 16. CSS

I fogli di stile si scrivono **solo quando la struttura HTML di tutte le pagine è completa
e validata**. Fino ad allora le pagine restano senza stile.

- **Tre file**: `src/styles/stile.css` (layout e componenti), `src/styles/mobile.css`
  (schermo piccolo), `src/styles/stampa.css` (stampa), collegati con tre `<link>` e
  l'attributo `media` (`all`, `screen and (max-width: ...)`, `print`). Nessun
  `@import`, nessun foglio in più, nessuno stile altrove.
- Il foglio base vale anche su carta, quindi il suo `media` è `all` e non `screen`: con
  `screen` la stampa non lo caricherebbe affatto e uscirebbe priva di stile, perché
  `stampa.css` contiene i soli scostamenti. Le regole valide solo a video, come
  l'impalcatura a schermata fissa, vengono sciolte da `stampa.css`.
- `stampa.css` dichiara le proprie proprietà personalizzate anche su
  `:root:not(.tema-chiaro)`, per pareggiare la specificità della tavolozza scura
  dichiarata sotto `prefers-color-scheme`, che vale anche in stampa: senza quel selettore
  chi ha il sistema in tema scuro stamperebbe testo chiaro su carta bianca.
- `stile.css` ha l'ordine interno: proprietà personalizzate su `:root`, reset minimo,
  elementi base, layout, i quindici componenti nell'ordine della tabella. `mobile.css` e
  `stampa.css` contengono solo gli scostamenti dal foglio base, non lo ripetono.
- Colori, spaziature, raggi e ombre solo tramite proprietà personalizzate dichiarate su
  `:root`; nessun colore ripetuto nel corpo del foglio.
- Layout con Flexbox e Grid; misure in unità relative (`rem`, `em`, `%`, `ch`); interlinea
  minima `1.5`.
- Contrasto minimo 4.5:1 per il testo normale, 3:1 per il testo grande; l'informazione non
  è mai veicolata dal solo colore. I valori si misurano e si riportano in relazione
  (parte I, sezione 5.4).
- **Nessuno scorrimento orizzontale a nessuna larghezza** (parte I, sezione 5.3). Gli
  elementi che tendono a sfondare si gestiscono esplicitamente: le tabelle larghe
  scorrono dentro un contenitore proprio, le immagini hanno `max-width: 100%`, le parole
  lunghe vanno a capo.
- Specificità: al massimo due livelli di selettore. Nessun `!important`, nessun selettore
  per `id`.
- Il tema scuro si ottiene con `prefers-color-scheme` e con una classe sulla radice decisa
  lato server in base a un cookie; lo script non scrive stili, al massimo passa una
  misura come proprietà personalizzata (sezione 17).
- Solo CSS2/CSS3 validi secondo il validatore W3C.

## 17. JavaScript

- **Un solo file**: `src/scripts/script.js`, caricato con `defer` dall'intestazione
  comune. Nessun altro file, nessuno script inline, nessuna dipendenza esterna. Nessuna
  minificazione o passo di build: la compressione e la cache di lunga durata già attive in
  `.htaccess` rendono il risparmio trascurabile rispetto al costo di un passo di
  compilazione.
- Prima di scrivere una funzione nuova, si verifica se `inviaModulo()` più
  `aggiornaPagina()` coprono già il caso: quasi ogni operazione del sito e' un modulo che
  va inviato e una porzione di pagina da rimettere a posto.
- Organizzazione interna: una funzione `inizializza<Area>()` per ogni comportamento, tutte
  richiamate da un unico ascoltatore di `DOMContentLoaded`.
- Ogni funzione parte da un elemento del DOM: se il selettore non trova nulla, esce subito
  senza errori. Così lo stesso file serve tutte le pagine.
- Il JavaScript non genera il markup della pagina: intercetta un invio, ripete la stessa
  richiesta e mette al posto della porzione presente quella arrivata dal server, oppure
  mostra e nasconde elementi (tramite classi o attributi `data-*`) in base alle scelte
  dell'utente.
- Le richieste inviate dallo script sono le stesse dei moduli e ricevono la stessa
  risposta HTML: il server non espone una seconda rappresentazione dei dati. Quando il
  server risponde con un redirect, la barra degli indirizzi viene allineata all'indirizzo
  di arrivo, cosi' indirizzo e contenuto dicono la stessa cosa; quando invece rimanda
  indietro la pagina con gli errori del modulo l'indirizzo non si tocca, altrimenti si
  perderebbero i parametri con cui la pagina era stata aperta.
- I punti di aggancio nel markup sono due attributi sul modulo: `data-modulo` con il nome
  dell'operazione, che dice allo script di inviarlo senza ricaricare la pagina, e
  `data-invio="automatico"`, che aggiunge l'invio al cambio di un controllo. Un modulo
  automatico non ha pulsante di conferma: lo si mette solo dove il cambio di un controllo
  e' già l'intenzione completa, quindi non sui campi di testo, che si finiscono di
  scrivere, e non dove due controlli valgono solo insieme.
- Quando più pulsanti agiscono su righe diverse dentro lo stesso modulo, ognuno porta
  l'identificativo della riga come valore: il browser invia solo il pulsante premuto e lo
  script gli aggiunge il pulsante che ha avviato l'invio, quindi il modulo resta uno.
- Dopo un aggiornamento il fuoco torna sul controllo che lo ha avviato, riconosciuto dal
  suo `id`; se quel controllo non esiste più va sull'avviso dell'esito, che ha
  `tabindex="-1"` e `role="status"`.
- Gli ascoltatori stanno sul documento e non sui singoli moduli, cosi' continuano a valere
  sul markup arrivato con un aggiornamento e raggiungono anche i controlli che stanno
  fuori dal proprio modulo e lo indicano con l'attributo `form`.
- Se la richiesta non arriva a destinazione, lo script invia il modulo nel modo normale:
  l'operazione non si perde e l'esito resta quello del server.
- Nessuna scrittura di stili da codice: si aggiunge o si toglie una classe o un attributo.
  Unica eccezione, le misure che solo il browser conosce (altezza dell'intestazione,
  distanza dal piede della pagina), passate al foglio di stile come proprietà
  personalizzate: il valore lo misura lo script, che cosa farne lo decide il CSS.
- Nomi di funzioni e variabili in `camelCaseItaliano`.

## 18. Database

- Nomi in italiano: tabelle al plurale (`prodotti`, `ordini`), colonne in
  `minuscolo_con_trattini_bassi`, chiavi esterne `<entita>_id`.
- Importi in centesimi, come interi (`prezzo_centesimi`), mai in virgola mobile.
- Date e orari in colonne `DATETIME`; i campi di tracciamento si chiamano `creato_il` e
  `aggiornato_il`.
- Schema in forma normale, con chiavi esterne dichiarate e vincoli di unicità espliciti.
- Un vincolo che deve valere sempre si dichiara nel database, oltre a essere verificato
  nel codice.
- Compatibilità **MariaDB 10.6**.
- Le righe di un ordine congelano nome e prezzo del prodotto al momento dell'acquisto.

## 19. Commenti

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
- storia delle modifiche: quella è la cronologia Git;
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

## 20. Accessibilità

Obiettivo: WCAG 2.1 livello AA, verificato con Pa11y e Lighthouse e con prove manuali.

- link "Vai al contenuto" come primo elemento del `<body>`, pulsante "Torna su" nelle
  pagine lunghe;
- ogni controllo raggiungibile da tastiera con focus visibile (`:focus-visible`);
- ogni campo ha una `<label>` associata; il testo segnaposto non sostituisce l'etichetta;
- campi obbligatori con `required="required"`; errori associati con `aria-describedby` e
  riepilogati in cima al modulo; i valori inseriti non si perdono quando il modulo torna
  con un errore;
- immagini di contenuto con `alt` descrittivo che comprende anche gli elementi ironici o
  non letterali; immagini decorative con `alt=""`, mai `alt=" "`; il logo che porta alla
  home ha `alt` che nomina la destinazione (parte I, sezione 5.2);
- tabelle con `<caption>` e intestazioni con `scope`;
- aggiornamenti dinamici annunciati con `role="status"`;
- attributo `lang` sulle parole in lingua diversa dall'italiano;
- attributi `autocomplete` sui campi che li prevedono.

## 21. Sicurezza

Le difese stanno nel codice, non nella fiducia verso chi invia la richiesta. Ogni dato che
arriva da fuori, cioè da `$_GET`, `$_POST`, `$_COOKIE`, dagli header e dalla sessione, è
considerato ostile finché non viene validato.

### 21.1 Database

- Tutte le query usano prepared statement con parametri nominati. Nessun valore viene
  concatenato dentro una stringa SQL, nemmeno se sembra un numero.
- Le parti di query costruite dal codice contengono solo testo fisso scritto da noi.
- L'emulazione dei prepared statement resta disattivata.
- Gli identificativi che arrivano dall'esterno si usano sempre insieme a un vincolo di
  proprietà, ad esempio la riga del carrello si cerca dentro il carrello di chi la chiede.

### 21.2 Output

- Ogni valore stampato nel markup passa da `e()`. Non esistono eccezioni per i dati letti
  dal database.
- I valori numerici si stampano con un cast esplicito, ad esempio `(int) $riga['id']`.
- Non si stampa mai contenuto dell'utente dentro attributi di evento, dentro URL non
  validati o dentro blocchi di script, che comunque non esistono.

### 21.3 Sessione e accesso

- Il cookie di sessione è `HttpOnly`, `SameSite=Lax` e diventa `Secure` su HTTPS.
- La sessione usa la modalità stretta e accetta l'identificativo solo dal cookie.
- Dopo un accesso riuscito l'identificativo di sessione viene rigenerato.
- L'uscita svuota la sessione, la distrugge e cancella il cookie dal browser.
- Il messaggio di errore dell'accesso è sempre lo stesso, sia che il nome utente non
  esista sia che la password sia errata.
- Nessun dato di pagamento reale viene raccolto o salvato: solo l'etichetta del metodo
  scelto.

### 21.4 Richieste che modificano dati

- Ogni azione che cambia stato viaggia in `POST` e verifica il token CSRF prima di
  produrre effetti. Nessuna modifica avviene su una richiesta `GET`, compresa l'uscita.
- Dopo l'azione si risponde con un redirect verso una pagina del sito, indicata per nome:
  non si costruiscono mai destinazioni a partire da dati della richiesta.
- Quando un modulo deve indicare la pagina di ritorno, il valore viene confrontato con un
  elenco chiuso di pagine ammesse.

### 21.5 File e configurazione

- Le cartelle `includes/`, `views/` e `database/` non sono raggiungibili dal web.
- L'elenco automatico del contenuto delle cartelle è disattivato.
- I file il cui nome inizia con un punto non sono serviti.
- Gli errori non compaiono nella pagina: finiscono nel log del server e la richiesta si
  chiude con la pagina 500.
- Le credenziali del database non stanno nel codice versionato: in sviluppo arrivano
  dall'ambiente, in produzione da un file privato fuori dal repository.
- Gli allegati caricati dal pannello si accettano solo con estensione e tipo in elenco
  chiuso, con un nome generato dal server e mai quello inviato dal browser.

### 21.6 Header di risposta

Il sito invia `Content-Security-Policy` senza `unsafe-inline`, `X-Content-Type-Options`,
`X-Frame-Options`, `Referrer-Policy` e `Permissions-Policy`. La politica dei contenuti
resta severa perché non esistono stili né script inline da autorizzare.

## 22. Budget quantitativi

Valori massimi per l'applicazione completa. Superarli richiede una semplificazione, non
un'eccezione.

Gli `id` si contano dopo aver tolto i suffissi generati per riga: `quantita-12` e
`quantita-45` sono lo stesso `id` ai fini del budget, perche' il loro numero cresce con i
dati e non con il codice.

Il limite degli `id` serve a impedirne l'uso come appiglio per lo stile, non a
scoraggiare l'accessibilita': ogni campo di un modulo ha bisogno del proprio `id` per la
`<label>`, e ogni messaggio di errore o testo di aiuto ne ha bisogno per
`aria-describedby`. Il conto resta legittimo finche' ogni `id` rientra nei tre motivi
ammessi alla sezione 14 e nessuno compare nei fogli di stile.

| Metrica | Limite |
| --- | --- |
| File CSS | 3 |
| File JavaScript | 1 |
| Attributi `style`, blocchi `<style>`, script inline, gestori inline | 0 |
| Classi CSS distinte | 110 |
| `id` presenti nel markup | 90 |
| Righe di CSS, totale sui tre file | 2400 |
| Righe di JavaScript | 800 |
| Righe di un file di funzioni | 300 |
| Righe di una vista | 150 |
| Tabelle del database | 12 |
| Peso di una singola immagine | 300 KB |

Righe, classi e `id` sono stati rialzati una terza volta (`ANALISI_REQUISITI.md` §13.22)
quando il lavoro è passato dal funzionale allo stile: i valori precedenti erano tarati
per impedire che il markup si riempisse di appigli inutili durante la fase in cui si
scriveva la logica, non per limitare quanti componenti visivi può avere un'identità
grafica compiuta. Il principio del budget non cambia, cambia la fase a cui si applica: le
classi descrivono ancora il ruolo del contenuto, non l'aspetto, e un componente nuovo si
aggiunge ancora solo quando la semantica esistente non basta, aggiornando la sezione 15.
Il catalogo dei quindici componenti stesso non è più un tetto rigido per lo stesso
motivo, ma resta un riferimento da tenere aggiornato: un componente nuovo si aggiunge alla
tabella, non si introduce in silenzio.

---

# Parte III. Lavoro e consegna

## 23. Come si aggiunge una pagina

1. Registra la pagina in `includes/pagine.php`: slug, ruoli ammessi, posizione nel menu.
2. Crea il controller `src/nome-pagina.php`: carica le risorse, legge e valida l'input,
   chiama le funzioni di dominio, passa i dati pronti a `mostra_pagina()`.
3. Crea la vista in `src/views/<area>/nome-pagina.php`, con solo markup.
4. Se la pagina riceve un identificativo, gestisci il valore non valido con la pagina 404
   del sito (parte I, sezione 5.1).

## 24. Come si aggiunge un'operazione che modifica dati

1. Scrivi la funzione di dominio nel file dell'area, che restituisce
   `['ok' => bool, 'messaggio' => string]` oppure un elenco di errori per campo.
2. Nel controller, verifica `$_SERVER['REQUEST_METHOD'] === 'POST'` e `csrf_valido()`
   prima di qualsiasi effetto.
3. Chiudi con `messaggio_imposta()` e `vai_a()`: dopo una modifica si risponde sempre con
   un redirect.
4. Nel modulo inserisci `campo_csrf()`.
5. Se l'operazione cancella dati, fai passare da una conferma.
6. Replica in PHP ogni controllo che hai messo in JavaScript.

## 25. Verifica prima di ogni commit

1. Il markup di ogni pagina toccata passa il validatore W3C senza errori ed è
   sintatticamente XML.
2. I fogli di stile passano il validatore W3C senza errori.
3. Pa11y non segnala errori sulle pagine toccate.
4. Ogni validazione lato client ha la gemella lato server, e le operazioni che passano
   dallo script arrivano allo stesso codice PHP dei moduli.
5. La navigazione da tastiera raggiunge ogni controllo con focus visibile.
6. La pagina non produce scorrimento orizzontale su schermo stretto.
7. I budget della sezione 22 sono rispettati.
8. Il file non contiene caratteri fuori dall'elenco ammesso alla sezione 11.3.
9. Il messaggio di commit è di una riga sola, senza corpo, senza firme di co-autori e
   senza riferimenti a strumenti.

## 26. Modo di lavorare

- Un commit per unità coerente: una pagina, una funzione di dominio, una sezione di CSS.
- Messaggi di commit di una riga sola, in italiano, con prefisso `feat:`, `fix:`,
  `docs:` o `chore:`.
- Commit e push avvengono solo su richiesta esplicita.
- Nessun commit lascia una pagina non validata.

## 27. Che cosa va consegnato

1. Il sito installato su `tecweb.studenti.math.unipd.it`, nella home di un componente del
   gruppo.
2. Su Moodle, un archivio zip con tutto il codice sorgente, il dump del database e la
   relazione.

La consegna entro il primo appello della sessione di febbraio vale due punti bonus,
sommati al voto solo se il progetto è valutato almeno 18.

## 28. Installazione sul server

Il workflow `Deploy su TecWeb` gira a ogni push su `main`, oppure si avvia a mano da
GitHub. Copia il contenuto di `src/`, scrive `includes/.configurazione-locale.php` con le
credenziali del database e imposta i permessi: 755 sulle cartelle, 644 sui file,
`uploads/` scrivibile.

Servono questi segreti nel repository: `TECWEB_SSH_PRIVATE_KEY`, `TECWEB_SSH_USERNAME` e
gli altri elencati nel workflow.

Dopo il deploy si controlla che il sito risponda su
`http://tecweb.studenti.math.unipd.it/<utente>` e che l'accesso funzioni con tutte le
utenze di prova.

## 29. Dump del database

Con lo stack locale avviato:

```bash
docker exec webapp_mariadb mariadb-dump -u root -p"$DB_ROOT_PASSWORD" --default-character-set=utf8mb4 esame_web > smashburger.sql
```

## 30. Archivio per Moodle

L'archivio contiene il contenuto di `src/`, il dump del database e la relazione in PDF.
Vanno esclusi `.git`, `.env` e i file di sistema.

```bash
zip -r smashburger.zip src smashburger.sql relazione.pdf -x '*.DS_Store'
```

## 31. Controlli prima di consegnare

- [ ] Il workflow `Qualità` passa: markup, accessibilità, prestazioni.
- [ ] Il sito sul server risponde e l'accesso funziona con tutte e tre le utenze di prova.
- [ ] Un ordine completo va a buon fine sul server, dal menu alla ricevuta.
- [ ] Il pannello permette inserimento, modifica e cancellazione in ogni sezione.
- [ ] Le operazioni del pannello e del carrello si compiono senza ricaricare la pagina,
      e l'avviso con l'esito compare a ogni operazione.
- [ ] Le pagine 401, 403, 404 e 500 compaiono nei casi giusti, comprese le richieste con
      un identificativo inesistente o malformato.
- [ ] Nessuna pagina produce scorrimento orizzontale sul telefono.
- [x] Le immagini in `src/images/sedi/` sono le immagini definitive nello stile scelto
      (`docs/IDENTITA.md`, Piano immagini) e non piu' il segnaposto grigio. La catena non
      esiste davvero: restano immagini prodotte con intelligenza artificiale, dichiarate
      come tali in relazione (parte I, sezione 5.4 punto 7), non fotografie di un luogo
      reale.
- [ ] Il dump del database è aggiornato allo schema in `src/database/schema.sql`.
- [ ] La relazione contiene la prima pagina richiesta e tutti i punti della sezione 5.4.
