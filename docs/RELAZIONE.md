# Smash Burger: Relazione di Progetto

**Università degli Studi di Padova**  
*Dipartimento di Matematica "Tullio Levi-Civita"*  
*Corso di Laurea in Informatica (L31)*  
**Corso di Tecnologie Web, Anno Accademico 2025/2026**

---

<p align="center">
  <img src="../src/images/logo.webp" alt="Logo di Smash Burger" width="160" />
</p>

## Piattaforma web accessibile per la ristorazione veloce e la gestione ordini multi-sede

---

### Componenti del gruppo

| Nominativo | Matricola | Email istituzionale | Ruolo |
|---|---|---|---|
| **Leonardo Soligo** | 2111042 | `leonardo.soligo@studenti.unipd.it` | Referente |
| **Dorde Blagojevic** | 2116424 | `dorde.blagojevic@studenti.unipd.it` | Componente |
| **Alessandro Ravenna** | 2111930 | `alessandro.ravenna@studenti.unipd.it` | Componente |
| **Alessandro Zanier** | 2101091 | `alessandro.zanier@studenti.unipd.it` | Componente |

**Indirizzo web del sito**: `https://tecweb.studenti.math.unipd.it/[DA COMPILARE]`

---

### Credenziali di accesso per il collaudo

| Ruolo utente | Sede di competenza | Nome utente | Password |
|---|---|---|---|
| Cliente | Qualsiasi | `user` | `user` |
| Manager | Sede di Padova | `manager` | `manager` |
| Manager | Sede di Treviso | `manager.treviso` | `manager` |
| Manager | Sede di Vicenza | `manager.vicenza` | `manager` |
| Manager | Sede di Udine | `manager.udine` | `manager` |
| Amministratore | Tutte le sedi | `admin` | `admin` |

---

## Indice

1. [Introduzione e Obiettivi del Progetto](#1-introduzione-e-obiettivi-del-progetto)
2. [Analisi dei Requisiti e Strategia SEO](#2-analisi-dei-requisiti-e-strategia-seo)
3. [Architettura e Progettazione del Sistema](#3-architettura-e-progettazione-del-sistema)
4. [Implementazione Tecnica](#4-implementazione-tecnica)
5. [Accessibilità e Conformità agli Standard](#5-accessibilità-e-conformità-agli-standard)
6. [Metodologia di Collaudo e Risultati dei Test](#6-metodologia-di-collaudo-e-risultati-dei-test)
7. [Ambiente di Sviluppo](#7-ambiente-di-sviluppo)
8. [Organizzazione del Gruppo e Suddivisione del Lavoro](#8-organizzazione-del-gruppo-e-suddivisione-del-lavoro)
9. [Considerazioni Conclusive](#9-considerazioni-conclusive)

---

## 1. Introduzione e Obiettivi del Progetto

In questa relazione illustriamo l'architettura, le scelte tecniche e le soluzioni di accessibilità sviluppate per il progetto del corso di Tecnologie Web (Laurea in Informatica, Università degli Studi di Padova, anno accademico 2025/2026).

L'attività presa a riferimento è "Smash Burger", una catena fittizia di hamburgerie con quattro sedi attive tra Veneto e Friuli: Padova, Treviso, Vicenza e Udine. Il sito risponde a due scopi pratici:
1. offrire ai clienti un canale online per esplorare il menu con filtri e ricerca testuale libera, ordinare panini con asporto o consegna a domicilio e richiedere la prenotazione della sala per eventi privati;
2. fornire al personale interno (manager locali e amministratore) un pannello per gestire gli ordini in tempo reale, aggiornare le scorte dei prodotti, modificare gli orari settimanali e verificare l'andamento economico.

### 1.1. Panoramica delle funzionalità per ruolo

Le operazioni permesse variano a seconda del profilo dell'utente:

- **Utente non autenticato (Ospite)**: consulta liberamente il catalogo prodotti, filtrando per categoria o cercando per nome e per ingrediente; visualizza prezzi, ingredienti e allergeni; legge orari settimanali e contatti delle quattro sedi; invia richieste di informazione dal modulo contatti; sceglie il tema grafico (chiaro o scuro); su schermi piccoli naviga tramite il menu a comparsa a tutto schermo.
- **Cliente registrato**: seleziona la propria sede di riferimento; compone il carrello della spesa; conclude l'ordine specificando orario di ritiro o indirizzo di consegna e modalità di pagamento simulata; consulta lo storico personale degli ordini con relative ricevute stampabili; richiede la prenotazione della sala eventi indicando data, orario e durata; aggiorna i dati personali o elimina il proprio account.
- **Manager di sede**: accede all'area riservata circoscritta al punto vendita di propria competenza. Può modificare gli orari di apertura e chiusura per ogni giorno della settimana; abilitare o sospendere la vendita dei singoli prodotti e variarne le quantità a magazzino; valutare le richieste di prenotazione della sala eventi (approvazione o rifiuto); aggiornare lo stato di lavorazione degli ordini; consultare il grafico degli incassi degli ultimi trenta giorni, corredato da tabella dati accessibile.
- **Amministratore generale**: ha privilegi completi su tutta la piattaforma. Oltre a poter operare su qualsiasi sede, gestisce il catalogo generale (creazione, modifica ed eliminazione di prodotti e categorie, con caricamento delle immagini); attiva o disattiva le sedi; legge i messaggi pervenuti dal modulo contatti; gestisce i ruoli e i permessi di tutti gli utenti registrati.

---

## 2. Analisi dei Requisiti e Strategia SEO

### 2.1. Destinatari e scenari d'uso

Il pubblico di riferimento è costituito principalmente da giovani e lavoratori tra i 16 e i 50 anni. L'accesso a piattaforme di ristorazione avviene in larga parte da smartphone, spesso durante gli spostamenti o a ridosso dell'orario dei pasti.

Abbiamo quindi curato con attenzione l'esperienza da schermi piccoli: navigazione compatta con menu a comparsa, passaggi ridotti al minimo per concludere l'ordine e pagine leggere che si caricano velocemente anche con connessioni mobili non ottimali.

I testi sono formulati in modo chiaro e privo di gergo superfluo, con una segnalazione precisa ed evidente degli allergeni alimentari (glutine, latte, uova, frutta a guscio) e delle alternative vegetariane disponibili.

### 2.2. Ottimizzazione per i motori di ricerca (SEO)

L'alberatura del sito e i testi sono stati strutturati per rispondere alle ricerche più frequenti degli utenti:
- ricerche correlate al marchio: "smash burger", "smash burger menu", "prezzi smash burger";
- ricerche geolocalizzate: "hamburger padova", "hamburger treviso", "hamburger vicenza", "hamburger udine";
- ricerche sulle modalità di acquisto: "hamburger da asporto padova", "consegna hamburger domicilio";
- ricerche su diete e intolleranze: "hamburger vegetariano padova", "allergeni hamburger";
- ricerche per eventi: "hamburgeria per eventi", "prenotazione sala feste privati".

Sul piano tecnico, abbiamo adottato questi accorgimenti per migliorare l'indicizzazione:
- elementi `<title>` e meta tag `description` univoci e pertinenti per ogni singola pagina;
- gerarchia ordinata dei titoli (da `h1` a `h3`) e marcatori semantici HTML5;
- mappa del sito in formato XML (`sitemap.xml`) generata per indicare le pagine pubbliche ai motori di ricerca;
- compressione di tutte le immagini fotografiche nel formato WebP con peso inferiore a 300 KB;
- assenza di librerie esterne o fogli di stile remoti che rallenterebbero il primo caricamento del documento.

---

## 3. Architettura e Progettazione del Sistema

### 3.1. Modello architetturale

L'applicazione adotta il pattern Model-View-Controller in stile procedurale, senza framework di terze parti:
- **Page Controller**: ogni indirizzo corrisponde a un file PHP nella radice del progetto. Il controller raccoglie la richiesta HTTP, controlla i permessi dell'utente, invoca le funzioni di dominio necessarie e richiama la vista adatta;
- **Transaction Script**: la logica applicativa è suddivisa in 20 file di funzioni pure posizionati nella cartella `includes/funzioni/` (catalogo, carrello, ordini, prenotazioni, sicurezza). Ciascuna operazione viene eseguita in modo isolato e controllato;
- **Template View**: le viste nella cartella `views/` si occupano solo dell'output HTML. Ricevono dal controller i dati già validati e strutturati, senza eseguire interrogazioni dirette alla base di dati.

La funzione `mostra_pagina()` compone ogni pagina includendo in modo uniforme l'intestazione, il percorso di navigazione (*breadcrumb*), il corpo operativo centrale e il piè di pagina.

### 3.2. Organizzazione delle pagine

La piattaforma conta 31 pagine registrate in modo centralizzato nel file di configurazione `includes/pagine.php`, raggruppate in quattro sezioni:
- **Area pubblica (14 pagine)**: home page, catalogo menu, scheda singolo prodotto, servizi, presentazione aziendale (chi siamo), elenco sedi, scheda singola sede, modulo contatti, accesso, registrazione, privacy policy, dichiarazione di accessibilità, mappa del sito, cambio tema grafico;
- **Area cliente (7 pagine)**: cruscotto personale, modifica profilo, carrello della spesa, cassa e checkout, ricevuta ordine, prenotazione sala eventi, disconnessione;
- **Area manager (5 pagine)**: elenco ordini di sede, dettaglio singolo ordine, scorte e disponibilità prodotti, orari settimanali di apertura, calendario prenotazioni;
- **Area amministratore (5 pagine)**: gestione catalogo prodotti, gestione categorie, anagrafica e stato sedi, messaggi ricevuti dal modulo contatti, gestione ruoli e permessi account.

### 3.3. Gestione dei permessi e delle sessioni

L'accesso alle aree protette è regolato in modo centralizzato. Al momento dell'accesso, dopo aver verificato le credenziali a fronte degli hash salvati nel database, il ruolo dell'utente viene memorizzato nella sessione PHP.

Ogni controller protetto invoca la funzione di controllo dei permessi prima di procedere: se l'utente non è autenticato riceve una risposta HTTP 401, mentre se possiede un ruolo non autorizzato riceve una risposta HTTP 403.

I cookie di sessione sono configurati con direttive di sicurezza restrittive (`HttpOnly`, `SameSite=Lax` e flag `Secure` condizionato ad HTTPS), impedendo l'accesso ai cookie tramite script JavaScript. Durante l'autenticazione viene rigenerato l'identificativo di sessione (`session_regenerate_id(true)`) per azzerare il rischio di attacchi di fissazione della sessione.

### 3.4. Struttura della base di dati

La persistenza dei dati è gestita tramite MariaDB (versione 10.6). Lo schema comprende 12 tabelle relazionali, collegate da 15 vincoli di chiave esterna (*foreign key*). Tutti i prezzi e gli importi economici sono memorizzati come numeri interi espressi in centesimi di euro (tipo `INT UNSIGNED`), evitando gli errori di arrotondamento tipici dei numeri a virgola mobile.

Le tabelle presenti sono:
- **utenti**: anagrafica degli account, credenziali cifrate, ruolo e dati predefiniti di consegna;
- **sedi**: elenco delle quattro sedi con indirizzo, contatti e manager assegnato;
- **orari_sedi**: orari settimanali con vincolo di unicità sulla coppia sede-giorno;
- **categorie**: categorie merceologiche dei prodotti con ordinamento configurabile;
- **prodotti**: catalogo delle preparazioni con denominazione, descrizione, allergeni, prezzo e percorso immagine;
- **disponibilita_prodotti**: disponibilità di ciascun prodotto per sede, con quantità a magazzino e vincolo `CHECK (quantita >= 0)`;
- **carrelli** e **righe_carrello**: carrelli di spesa temporanei associati all'utente e alla sede scelta;
- **ordini** e **righe_ordine**: ordini confermati con stato di lavorazione, modalità (ritiro o consegna), recapito e storicizzazione del prezzo unitario dei prodotti;
- **prenotazioni**: richieste di utilizzo della sala eventi con data, orari e stato di approvazione;
- **messaggi_contatto**: comunicazioni inviate tramite il modulo contatti, con argomento e stato di evasione.

<p align="center">
  <img src="schema_database.png" alt="Rappresentazione dello schema logico/concettuale della base di dati" width="800" />
</p>

### 3.5. Gestione della concorrenza

Abbiamo gestito con attenzione due situazioni critiche di concorrenza sui dati:
1. **Scarico scorte a magazzino**: al momento del checkout l'aggiornamento delle quantità avviene all'interno di una transazione SQL atomica con controllo condizionale sulla disponibilità residua (`UPDATE disponibilita_prodotti SET quantita = quantita - :q WHERE ... AND quantita >= :q`). Se la quantità disponibile non basta anche per una sola riga, la transazione esegue il rollback e l'ordine viene respinto, evitando ordini superiori alla giacenza reale.
2. **Sovrapposizione delle prenotazioni**: prima di inserire una nuova richiesta per la sala eventi, una query verifica all'interno della stessa transazione che non vi siano intervalli orari sovrapposti per la medesima sede e data tra le prenotazioni già approvate o in attesa.

---

## 4. Implementazione Tecnica

### 4.1. Dichiarazione sull'uso di immagini generate

Trattandosi di un'azienda simulata a scopo didattico, le immagini illustrative dei panini, dei contorni, delle bevande e delle sedi sono state **generate tramite strumenti di intelligenza artificiale**, per dare al sito un aspetto coerente e uniforme.

Tutte le immagini sono state ritagliate nelle proporzioni richieste dal layout, compresse nel formato WebP (mantenendo il peso di ciascun file sotto i 300 KB) e corredate da descrizioni testuali alternative (`alt`) che ne descrivono il soggetto in modo fedele.

### 4.2. Struttura e semantica del markup (HTML5)

Le pagine sono scritte in HTML5 rispettando la sintassi XML:
- chiusura esplicita di tutti gli elementi, compresi i tag vuoti (ad esempio `<input />`, `<img />`);
- valori degli attributi sempre racchiusi tra virgolette;
- attributi booleani dichiarati in forma esplicita (`required="required"`);
- dichiarazione dei namespace XML e dell'attributo di lingua nella radice:
  ```html
  <html xmlns="http://www.w3.org/1999/xhtml" lang="it" xml:lang="it">
  ```

Ogni pagina utilizza marcatori semantici per delimitare le sezioni principali (`<header>`, `<nav>`, `<main>`, `<section>`, `<article>`, `<footer>`), supportando la navigazione per landmark.

### 4.3. Organizzazione dei fogli di stile (CSS) e navigazione responsive

La veste grafica è organizzata in tre fogli di stile distinti, collegati tramite l'attributo `media`:
- `stile.css`: foglio principale contenente il reset di base, la definizione delle variabili per colori e spaziature, i layout dei componenti e i due temi cromatici;
- `mobile.css`: foglio dedicato agli schermi fino a 48 em (768 pixel), collegato con `media="screen and (max-width: 48em)"`;
- `stampa.css`: foglio per la stampa cartacea, collegato con `media="print"`.

Nello sviluppo del CSS abbiamo rispettato regole precise:
- nessun attributo di stile inline nel codice HTML;
- proprietà personalizzate CSS (*custom properties*) su `:root` per gestire colori, spaziature, raggi di curvatura e ombreggiature;
- impiego combinato di Flexbox e CSS Grid, mantenendo bassa la specificità dei selettori (massimo due livelli);
- disattivazione di animazioni e transizioni per gli utenti che hanno impostato la preferenza di sistema per la riduzione del movimento (`@media (prefers-reduced-motion: reduce)`).

Per la visualizzazione su dispositivi mobili, sotto la larghezza di 64 em (circa 1024 pixel) la barra di testata non può contenere sulla stessa riga il logo, i collegamenti pubblici e i pulsanti dell'account. In questa fascia l'interfaccia sostituisce la barra con un menu di navigazione a comparsa a tutto schermo: un pulsante con icona a tre barre apre il pannello, mentre un pulsante con icona a croce ne permette la chiusura rapida. Il pannello separa visivamente i collegamenti del sito da quelli dell'account e offre bersagli di tocco ampi per una selezione agevole su touchscreen.

### 4.4. Tavolozze cromatiche e verifica dei contrasti

I colori del sito riprendono i toni tipici dei condimenti di una classica hamburgeria: senape, ketchup e cetriolini sottaceto. Le due tabelle seguenti riportano le variabili cromatiche e i campioni visivi per le modalità chiara e scura:

#### Modalità Chiara

| Ruolo visivo | Variabile CSS | Campione | Codice HEX |
|---|---|:---:|---|
| Sfondo della pagina | `--sfondo` | <span style="display:inline-block;width:14px;height:14px;background-color:#faf7f2;border:1px solid #bbb;vertical-align:middle;"></span> | `#faf7f2` |
| Superficie delle schede | `--superficie` | <span style="display:inline-block;width:14px;height:14px;background-color:#fffdf9;border:1px solid #bbb;vertical-align:middle;"></span> | `#fffdf9` |
| Superficie alternativa | `--superficie-alternativa` | <span style="display:inline-block;width:14px;height:14px;background-color:#eee7dc;border:1px solid #bbb;vertical-align:middle;"></span> | `#eee7dc` |
| Testo principale | `--testo` | <span style="display:inline-block;width:14px;height:14px;background-color:#1a1a1a;border:1px solid #bbb;vertical-align:middle;"></span> | `#1a1a1a` |
| Accento caldo (senape) | `--senape` | <span style="display:inline-block;width:14px;height:14px;background-color:#9c6f00;border:1px solid #bbb;vertical-align:middle;"></span> | `#9c6f00` |
| Colore di azione (ketchup) | `--ketchup` | <span style="display:inline-block;width:14px;height:14px;background-color:#b3181f;border:1px solid #bbb;vertical-align:middle;"></span> | `#b3181f` |
| Colore di successo (cetriolini) | `--cetriolini` | <span style="display:inline-block;width:14px;height:14px;background-color:#3f6b24;border:1px solid #bbb;vertical-align:middle;"></span> | `#3f6b24` |
| Bordi e cornici | `--bordo` | <span style="display:inline-block;width:14px;height:14px;background-color:#d8cfc2;border:1px solid #bbb;vertical-align:middle;"></span> | `#d8cfc2` |
| Collegamenti visitati | `--visitato` | <span style="display:inline-block;width:14px;height:14px;background-color:#61208f;border:1px solid #bbb;vertical-align:middle;"></span> | `#61208f` |

#### Modalità Scura

| Ruolo visivo | Variabile CSS | Campione | Codice HEX |
|---|---|:---:|---|
| Sfondo della pagina | `--sfondo` | <span style="display:inline-block;width:14px;height:14px;background-color:#171614;border:1px solid #555;vertical-align:middle;"></span> | `#171614` |
| Superficie delle schede | `--superficie` | <span style="display:inline-block;width:14px;height:14px;background-color:#22201d;border:1px solid #555;vertical-align:middle;"></span> | `#22201d` |
| Superficie alternativa | `--superficie-alternativa` | <span style="display:inline-block;width:14px;height:14px;background-color:#2d2a26;border:1px solid #555;vertical-align:middle;"></span> | `#2d2a26` |
| Testo principale | `--testo` | <span style="display:inline-block;width:14px;height:14px;background-color:#f2ece4;border:1px solid #555;vertical-align:middle;"></span> | `#f2ece4` |
| Accento caldo (senape) | `--senape` | <span style="display:inline-block;width:14px;height:14px;background-color:#e8a100;border:1px solid #555;vertical-align:middle;"></span> | `#e8a100` |
| Colore di azione (ketchup) | `--ketchup` | <span style="display:inline-block;width:14px;height:14px;background-color:#f0555a;border:1px solid #555;vertical-align:middle;"></span> | `#f0555a` |
| Colore di successo (cetriolini) | `--cetriolini` | <span style="display:inline-block;width:14px;height:14px;background-color:#86bd4d;border:1px solid #555;vertical-align:middle;"></span> | `#86bd4d` |
| Bordi e cornici | `--bordo` | <span style="display:inline-block;width:14px;height:14px;background-color:#413d37;border:1px solid #555;vertical-align:middle;"></span> | `#413d37` |
| Collegamenti visitati | `--visitato` | <span style="display:inline-block;width:14px;height:14px;background-color:#d8adff;border:1px solid #555;vertical-align:middle;"></span> | `#d8adff` |

Abbiamo verificato tutte le combinazioni di testo e sfondo con gli strumenti di controllo del contrasto: il valore minimo registrato è pari a 5.02:1, superiore al limite richiesto dalle linee guida WCAG 2.1 AA (4.5:1 per testo normale e 3:1 per testo grande).

### 4.5. Foglio di stile per la stampa

Il foglio `stampa.css` predispone le pagine per la stampa su carta o l'esportazione in PDF, privilegiando la leggibilità e il risparmio di inchiostro:
- sfondo completamente bianco e testo nero per evitare consumi di inchiostro inutili;
- rimozione dei componenti di navigazione e dei controlli interattivi che non hanno senso su carta: menu principale, menu a comparsa per dispositivi mobili (`.menu-sito`), pulsanti, link di salto e piè di pagina;
- rimozione specifica della barra e del modulo di ricerca del menu (`.barra-menu`, `.ricerca-menu`), così che nella stampa del catalogo rimangano visibili esclusivamente i prodotti con relative descrizioni e prezzi;
- linearizzazione delle griglie e rimozione di sfondi ed elementi decorativi;
- impostazione dei margini a 2 cm mediante la regola `@page`.

### 4.6. Tipografia

Il carattere tipografico scelto è **Archivo**, un sans-serif geometrico moderno ad alta leggibilità, distribuito con licenza Open Font License. I file dei caratteri (`.ttf`) sono inclusi localmente nel repository in tre pesi (normale, grassetto e nero) e dichiarati con `font-display: swap`. In caso di mancato caricamento, la catena di ripiego (*fallback*) prevede caratteri ampiamente diffusi: `"Archivo", "Helvetica Neue", Arial, sans-serif`.

### 4.7. Comportamento dinamico con JavaScript (Progressive Enhancement)

Il comportamento lato client è contenuto in un unico file (`src/scripts/script.js`) in modalità rigorosa (`'use strict'`), scritto in puro JavaScript senza librerie o dipendenze esterne.

L'applicazione segue il principio del **Progressive Enhancement**: ogni funzione del sito (consultazione del catalogo, ricerca prodotti, carrello, conclusione ordini, prenotazione sala e operazioni del pannello di controllo) funziona regolarmente anche disattivando del tutto JavaScript nel browser.

Lo script interviene a migliorare l'interazione su cinque aspetti:
1. **Menu responsive accessibile**: gestisce l'apertura e la chiusura del pannello di navigazione su schermi stretti aggiornando l'attributo `aria-expanded`. Quando il menu è aperto, lo script applica l'attributo `inert` al resto della pagina (`header`, `main`, `footer`), impedendo al cursore e alle tecnologie assistive di interagire con gli elementi retrostanti. La chiusura avviene tramite il pulsante dedicato, cliccando all'esterno o premendo il tasto Escape, con ripristino automatico del focus sul pulsante che aveva aperto il menu.
2. **Pulsante torna su**: diventa visibile dopo aver superato una soglia di scorrimento verso il basso. Al click riporta la vista in cima alla pagina e sposta il focus da tastiera sull'ancora iniziale (`#inizio`).
3. **Invio asincrono dei moduli (AJAX)**: i moduli del carrello e del pannello inviano le richieste tramite l'API nativa `fetch` e aggiornano la sezione corrispondente senza ricaricare l'intera pagina. In caso di errore o assenza di connessione, il modulo esegue un ripiego automatico sottomettendosi nel modo standard.
4. **Focus automatico sugli errori del modulo**: se una pagina viene ricaricata con errori di validazione (come nel modulo di prenotazione eventi), lo script porta automaticamente il focus sul riquadro riassuntivo degli errori, che contiene collegamenti diretti ai singoli campi da correggere.
5. **Campi condizionali per la cassa**: mostra o nasconde i campi dell'indirizzo al checkout a seconda che l'utente scelga il ritiro in sede o la consegna a domicilio, senza inserire stili inline.

### 4.8. Misure di sicurezza applicativa

La sicurezza della piattaforma poggia su controlli applicati sia lato client sia lato server:
- **Protezione da SQL Injection**: uso sistematico di query preparate PDO con parametri nominati per qualsiasi interazione con la base di dati;
- **Sanificazione della ricerca nel menu**: la funzione `testo_richiesto()` pulisce l'input scartando caratteri di controllo ASCII o Unicode e limitando la lunghezza a 60 caratteri; la funzione `like_letterale()` effettua il corretto escape dei caratteri speciali SQL (`%`, `_`, `\`), prevenendo comportamenti anomali nelle clausole `LIKE`;
- **Protezione da Cross-Site Scripting (XSS)**: filtraggio di tutti i dati stampati nell'HTML tramite la funzione di escape `e()`, che invoca `htmlspecialchars()` con codifica UTF-8 e flag `ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML5`;
- **Protezione da Cross-Site Request Forgery (CSRF)**: inserimento in ogni form di modifica di un token generato con `random_bytes()`, convalidato all'arrivo mediante la funzione a tempo costante `hash_equals()`;
- **Cifratura delle password**: memorizzazione degli hash calcolati con l'algoritmo bcrypt (`password_hash()`);
- **Controllo dei caricamenti**: verifica del tipo MIME effettivo dei file tramite `finfo_file()` e inibizione dell'esecuzione di file PHP nella cartella di upload;
- **Intestazioni di sicurezza HTTP**: configurazione del server web con direttive Content Security Policy restrittive (senza unsafe-inline o unsafe-eval), X-Frame-Options (`SAMEORIGIN`) contro il clickjacking e divieto di directory listing (`Options -Indexes`).

### 4.9. Pagine di errore personalizzate

La piattaforma include pagine personalizzate per gli errori HTTP 401, 403, 404 e 500, configurate tramite percorsi relativi in `.htaccess`. Ognuna presenta una veste grafica coerente, un messaggio chiaro e la navigazione completa del sito, permettendo all'utente di riprendere l'esplorazione senza restare bloccato.

---

## 5. Accessibilità e Conformità agli Standard

Lo sviluppo ha seguito i requisiti di conformità alle linee guida WCAG 2.1 livello AA.

### 5.1. Interventi per la navigazione e la struttura

- **Collegamento rapido iniziale**: inserimento di un link "Vai al contenuto" (*skip link*) come primissimo elemento del `<body>`, consentendo a chi usa la tastiera di saltare l'intestazione e raggiungere subito l'area principale;
- **Assenza di link circolari**: la voce di navigazione della pagina corrente non è un collegamento ipertestuale ma un testo semplice, contraddistinto dall'attributo semantico `aria-current="page"`;
- **Percorso di navigazione (*breadcrumb*)**: indicazione visibile e strutturata della gerarchia della pagina rispetto alla home;
- **Etichettatura dei moduli**: associazione univoca tra etichette visibili (`<label for="...">`) e campi di input; raggruppamento tematico con `<fieldset>` e `<legend>`;
- **Segnalazione accessibile degli errori con `aria-describedby`**: nei moduli di inserimento (in particolare nella prenotazione eventi), ogni campo che presenta un errore è collegato direttamente al testo del messaggio tramite l'attributo `aria-describedby`. Lo screen reader legge così la descrizione dell'errore non appena il campo riceve il focus, senza affidare l'informazione al solo colore (criteri WCAG 3.3.1 e 1.4.1);
- **Riepilogo errori navigabile**: l'elenco riassuntivo degli errori a inizio modulo presenta collegamenti diretti ai campi non validi, consentendo una rapida correzione (WCAG 3.3.3);
- **Tabelle accessibili**: impiego dell'elemento `<caption>` per descrivere i contenuti della tabella e attributi `scope="col"` e `scope="row"` per le celle di intestazione;
- **Grafico degli incassi accessibile**: il grafico vettoriale SVG degli incassi è affiancato da una tabella contenente i medesimi dati numerici, rendendo l'informazione fruibile anche a chi non può percepire il grafico;
- **Messaggi di stato chiari**: gli avvisi di conferma o di errore usano l'attributo `role="status"` e iniziano con un prefisso testuale esplicito ("Errore:" o "Fatto:") per non dipendere dal colore.

### 5.2. Adattamenti per screen reader

- **Attributo di lingua per termini stranieri**: applicazione mirata di `lang="en"` sui nomi delle vivande in lingua inglese (come *Cheeseburger*, *Bacon Burger*, *Chicken Wings*, *In-N-Out Style*), evitando errori di pronuncia da parte della sintesi vocale;
- **Marcatura delle sigle**: esplicitazione del significato di acronimi e abbreviazioni tramite il tag `<abbr title="...">` (ad esempio per CAP e WCAG);
- **Icone decorative nascoste**: apposizione di `aria-hidden="true"` su tutte le icone SVG di contorno per escluderle dalla lettura delle tecnologie assistive;
- **Badge di validazione descrittivi**: i collegamenti ai validatori W3C nel piè di pagina contengono testi descrittivi della destinazione (`title="Valida il codice HTML5 sul sito del W3C"` e `title="Valida i fogli di stile CSS sul sito del W3C"`), evitando indicazioni generiche;
- **Isolamento dell'albero di accessibilità**: durante l'apertura del menu a comparsa per dispositivi mobili, l'applicazione dell'attributo `inert` agli elementi retrostanti impedisce che lettori di schermo o navigazione da tastiera escano accidentalmente dal menu.

### 5.3. Fruizione fluida su dispositivi diversi

Il layout del sito è fluido e utilizza unità di misura relative (`em`, `rem`, percentuali). In tutte le pagine e a qualsiasi risoluzione (a partire dalla larghezza minima di 320 pixel) non si genera alcuno scorrimento orizzontale. La disposizione a colonna singola per i dispositivi mobili interviene automaticamente sotto i 48 em (768 pixel), mentre il menu a comparsa si attiva sotto i 64 em (1024 pixel).

---

## 6. Metodologia di Collaudo e Risultati dei Test

Abbiamo verificato la conformità tecnica e l'accessibilità del sito combinando controlli automatici con approfondite prove manuali.

### 6.1. Strumenti di verifica automatica

- **W3C Nu Html Checker**: verifica della validità del markup HTML5 su tutte le pagine pubbliche e riservate del sito, con esito di zero errori;
- **W3C CSS Validation Service**: verifica formale della correttezza dei tre fogli di stile (`stile.css`, `mobile.css`, `stampa.css`), riscontrando zero errori di sintassi;
- **xmllint**: controllo del rispetto della sintassi XML su tutti i documenti HTML5 generati dall'applicazione;
- **Pa11y**: scansione dell'accessibilità secondo le linee guida WCAG 2.1 AA, eseguita su ogni pagina sia in modalità ospite sia autenticandosi con i vari ruoli di prova;
- **Google Lighthouse**: verifica delle metriche su prestazioni, accessibilità, buone pratiche e SEO, con punteggi superiori alla soglia di 90 su tutte le sezioni;
- **WAVE Web Accessibility Evaluation Tool** e **Silktide Accessibility**: estensioni per browser impiegate durante lo sviluppo per controllare contrasti, etichette dei moduli e gerarchia delle intestazioni.

### 6.2. Controlli manuali

- navigazione completa da tastiera con i tasti Tab, Shift+Tab, Invio, Spazio ed Escape, accertando la costante visibilità del cursore di focus e la coerenza dell'ordine di tabulazione;
- test con JavaScript disabilitato su tutte le funzionalità (catalogo, carrello, cassa, prenotazioni e pannello gestionale);
- invio di moduli con campi mancanti, errati o contenenti caratteri speciali, accertando la tempestività e la chiarezza dei messaggi di errore e il mantenimento dei valori validi già inseriti;
- simulazione di stampa fisica e su file PDF di ricevute e pagine informative;
- collaudo visivo a risoluzioni multiple (320px, 375px, 768px, 1024px, 1440px) per verificare l'adattabilità dei layout;
- compatibilità con i principali browser: Google Chrome, Mozilla Firefox, Microsoft Edge, Apple Safari e Opera;
- compatibilità con diversi sistemi operativi: Microsoft Windows, Linux (Ubuntu), Android e iOS.

### 6.3. Automazione dei controlli (Continuous Integration)

Per mantenere costante la qualità del codice durante lo sviluppo abbiamo configurato una procedura di integrazione continua (Continuous Integration). A ogni aggiornamento del repository, la procedura avvia un ambiente containerizzato dedicato ed esegue in successione:
1. la validazione sintattica HTML5 con il validatore del W3C e la verifica della conformità XML;
2. la validazione dei tre fogli di stile CSS tramite le API del W3C;
3. l'audit di accessibilità con Pa11y sulle pagine pubbliche e su quelle riservate (autenticandosi con le credenziali di prova per ciascun ruolo);
4. la misurazione dei punteggi prestazionali e di accessibilità con Google Lighthouse;
5. il caricamento dei sorgenti sul server universitario tramite canale sicuro SSH, con impostazione e verifica dei corretti permessi di lettura ed esecuzione sui file.

### 6.4. Valutazione dei falsi positivi

I controlli automatici non hanno riscontrato errori o violazioni delle specifiche. Le uniche segnalazioni del validatore CSS sono avvisi relativi all'impiego delle proprietà personalizzate (`--nome-variabile`), che sono una normale caratteristica del CSS moderno, perfettamente valida e conforme.

### 6.5. Collaudo con screen reader

Abbiamo collaudato l'accessibilità con lo screen reader open-source **NVDA** sui browser Mozilla Firefox e Google Chrome. Le prove hanno confermato:
- la corretta lettura dello skip link iniziale e lo spostamento effettivo del focus all'area dei contenuti;
- l'isolamento del menu a comparsa per dispositivi mobili tramite `inert`, che impedisce la lettura accidentale dei contenuti retrostanti;
- l'annuncio immediato dell'errore al focus sul campo non valido grazie ad `aria-describedby`;
- la lettura ordinata delle tabelle con annuncio delle relative intestazioni di riga e colonna;
- la corretta pronuncia delle denominazioni in lingua inglese grazie agli attributi `lang="en"`;
- l'enunciazione tempestiva dei messaggi di stato all'invio dei form.

---

## 7. Ambiente di Sviluppo

Per facilitare il lavoro coordinato tra i membri del gruppo e riprodurre fedelmente la configurazione del server universitario, abbiamo allestito un ambiente locale containerizzato con Docker:
- container web con PHP 8.1, server Apache e moduli `rewrite`, `headers` e `deflate`;
- container per la base di dati con MariaDB 10.6, volume dati persistente e script per il popolamento automatico dei dati di test all'avvio.

---

## 8. Organizzazione del Gruppo e Suddivisione del Lavoro

Abbiamo suddiviso il lavoro tenendo conto delle competenze di ciascuno, facendo in modo che ogni componente partecipasse sia alle parti di presentazione (HTML, CSS e accessibilità) sia alla logica applicativa (PHP, database e sicurezza):

### 8.1. Ripartizione dei compiti

- **Leonardo Soligo (referente)**:
  - Coordinamento generale del progetto e impostazione delle convenzioni architetturali;
  - HTML/CSS: pagine istituzionali e catalogo (Home page, Menu con barra di ricerca, Dettaglio prodotto, Servizi);
  - PHP/JavaScript: controller del catalogo, ricerca per nome/ingrediente e menu responsive a comparsa;
  - Base di dati: progettazione concettuale e logica dello schema relazionale;
  - Testing e validazione: verifiche di accessibilità con WAVE e Silktide, test con NVDA;
  - Stesura e revisione della relazione tecnica.

- **Dorde Blagojevic**:
  - HTML/CSS: pagine informative e di supporto (Chi siamo, Sedi, Scheda sede, Modulo contatti, Privacy policy, Dichiarazione di accessibilità);
  - PHP/JavaScript: gestione dell'invio messaggi di contatto, presentazione delle sedi e foglio di stampa;
  - Base di dati: vincoli di integrità referenziale, indici di ricerca e popolamento sedi;
  - Testing e validazione: controlli con il validatore W3C (HTML5 e CSS) e collaudo con NVDA;
  - Stesura e revisione della relazione tecnica.

- **Alessandro Ravenna**:
  - HTML/CSS: flusso carrello, cassa e checkout, ricevuta ordine, area personale, prenotazione sala eventi;
  - PHP/JavaScript: logica transazionale del checkout, gestione concorrente delle scorte a magazzino, controllo anti-sovrapposizione prenotazioni ed error handling nei form con `aria-describedby`;
  - Base di dati: definizione query per carrello e ordini, script di popolamento iniziale;
  - Testing e validazione: prove di usabilità su mobile, test con JavaScript disattivato e verifiche con NVDA;
  - Stesura e revisione della relazione tecnica.

- **Alessandro Zanier**:
  - HTML/CSS: sezioni del pannello gestionale (Gestione ordini, Gestione prodotti e scorte, Gestione categorie, Gestione sedi e orari, Gestione utenti);
  - PHP/JavaScript: autenticazione, gestione delle sessioni protette con cookie restrittivi, controllo accessi per ruoli e generazione del grafico SVG;
  - Infrastruttura e automazione: configurazione della pipeline di Continuous Integration e procedure di deploy;
  - Testing e validazione: audit di accessibilità con Pa11y, verifiche prestazionali con Google Lighthouse e test con NVDA;
  - Stesura e revisione della relazione tecnica.

---

## 9. Considerazioni Conclusive

Nello sviluppo del sito di "Smash Burger" abbiamo scelto di non adoperare framework preconfezionati (come Bootstrap, Tailwind o jQuery), privilegiando l'uso diretto delle tecnologie native del web: HTML5 semantico con sintassi XML, CSS3 con proprietà personalizzate per i temi, JavaScript essenziale non invasivo e PHP 8.1 procedurale con PDO. L'unica risorsa di terze parti utilizzata è il carattere tipografico Archivo, incluso localmente nel progetto con licenza aperta per non dipendere da servizi esterni.

L'adozione rigorosa del principio di Progressive Enhancement assicura che tutte le funzionalità della piattaforma rimangano fruibili indipendentemente dal dispositivo, dalle tecnologie assistive adoperate o dallo stato di attivazione di JavaScript, rendendo il sito veloce, chiaro e utilizzabile da chiunque con o senza tecnologie assistive.
