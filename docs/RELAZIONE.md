# Relazione di progetto "Smash Burger"

**Corso di Tecnologie Web A.A. 2025-26**

---

**Autori**

- [DA COMPILARE: Nome Cognome], matricola [MATRICOLA], [email]@studenti.unipd.it
- [DA COMPILARE: Nome Cognome], matricola [MATRICOLA], [email]@studenti.unipd.it
- [DA COMPILARE: Nome Cognome], matricola [MATRICOLA], [email]@studenti.unipd.it
- [DA COMPILARE: Nome Cognome], matricola [MATRICOLA], [email]@studenti.unipd.it (referente)

**Sito web**: https://tecweb.studenti.math.unipd.it/[DA COMPILARE]

**Repository GitHub**: https://github.com/TecWeb2526SB/SmashBurger

**Credenziali di prova**

| Ruolo | Nome utente | Password |
|---|---|---|
| Cliente | user | user |
| Manager | manager | manager |
| Amministratore | admin | admin |

---

## Indice

1. [Introduzione](#1-introduzione)
2. [Analisi dei requisiti](#2-analisi-dei-requisiti)
3. [Progettazione](#3-progettazione)
4. [Realizzazione](#4-realizzazione)
5. [Test effettuati](#5-test-effettuati)
6. [Ambiente di sviluppo](#6-ambiente-di-sviluppo)
7. [Organizzazione del gruppo](#7-organizzazione-del-gruppo)
8. [Note conclusive](#8-note-conclusive)

---

## 1. Introduzione

La presente relazione ha come scopo quello di descrivere le scelte progettuali, le tecnologie e le metodologie adottate per la realizzazione del progetto del corso di Tecnologie Web (corso di Laurea in Informatica, L31) per l'anno accademico 2025-2026.

Il progetto consiste nella realizzazione di un sito web accessibile e usabile dedicato alla catena di hamburgerie "Smash Burger", un'attività commerciale simulata a scopo didattico con quattro punti vendita dislocati nel territorio del Triveneto (Padova, Treviso, Vicenza e Udine). La piattaforma consente agli utenti di esplorare l'offerta gastronomica, effettuare ordinazioni online (sia da asporto sia con consegna a domicilio), prenotare la sala eventi presente nelle sedi e gestire le attività aziendali mediante un pannello gestionale riservato.

La piattaforma offre funzionalità differenziate in base al tipo di utente.

Agli utenti non registrati (ospiti) il sito consente di:
- consultare il menu completo dei prodotti con descrizioni, prezzi, ingredienti e indicazione esplicita degli allergeni;
- visualizzare l'elenco delle sedi con i relativi indirizzi, orari di apertura settimanali e contatti;
- consultare informazioni descrittive sulla tecnica di cottura "smash" e sulla filosofia aziendale;
- inviare richieste o comunicazioni attraverso il modulo di contatto;
- selezionare il tema visivo preferito (chiaro o scuro).

Agli utenti clienti registrati sono riservate le seguenti funzioni aggiuntive:
- selezione del punto vendita di riferimento e gestione del carrello della spesa;
- finalizzazione dell'ordine con scelta della modalità (ritiro sul posto o consegna a domicilio) e metodo di pagamento simulato;
- visualizzazione dello storico degli ordini effettuati con dettaglio delle ricevute;
- prenotazione della sala per eventi privati, specificando data, orario, durata e numero di partecipanti;
- gestione del proprio profilo personale, inclusa la modifica dei dati di recapito, il cambio password e la cancellazione dell'account.

Per il personale dipendente (manager di sede) la piattaforma mette a disposizione un'area di gestione con cui:
- modificare gli orari di apertura della propria sede per ciascun giorno della settimana;
- attivare o disattivare la disponibilità dei prodotti nel punto vendita e aggiornare le quantità a magazzino;
- gestire le richieste di prenotazione della sala eventi, con facoltà di approvarle o rifiutarle;
- consultare e aggiornare lo stato di avanzamento degli ordini ricevuti dalla sede;
- consultare il riepilogo degli incassi degli ultimi 30 giorni tramite grafico e tabella riassuntiva.

Infine, l'amministratore generale ha il controllo completo del sistema e può:
- gestire l'intero catalogo prodotti (inserimento, modifica, cancellazione e caricamento immagini);
- gestire le categorie merceologiche del menu;
- inserire, aggiornare o disattivare le sedi della catena e assegnare i rispettivi manager;
- prendere visione dei messaggi ricevuti tramite il modulo contatti e aggiornarne lo stato;
- gestire gli account degli utenti registrati (modifica del ruolo, abilitazione o disattivazione).

L'obiettivo principale è stato sviluppare un'esperienza di navigazione chiara, veloce e pienamente accessibile da qualsiasi dispositivo, prestando attenzione alle persone che utilizzano tecnologie assistive o che navigano con JavaScript disabilitato.

---

## 2. Analisi dei requisiti

Nella fase preliminare abbiamo analizzato diversi siti web esistenti nel settore della ristorazione veloce e delle catene di hamburgerie, con l'obiettivo di individuare la struttura informativa più efficace e le funzionalità attese da chi ordina cibo online. Da questa indagine abbiamo ricavato i requisiti funzionali della piattaforma, definendo la gerarchia delle pagine e l'organizzazione dei contenuti.

### 2.1. Analisi utente

Il target di riferimento è rappresentato principalmente da una clientela giovane e adulta (fascia indicativa tra i 16 e i 50 anni), abituata a consultare menu e ordinare da smartphone, spesso in mobilità o nelle fasce orarie dei pasti. Per questa ragione l'interfaccia è stata progettata con un'impostazione orientata ai dispositivi mobili, riducendo al minimo i passaggi necessari per completare un'ordinazione o reperire un orario.

Il linguaggio impiegato nei testi è colloquiale ma rigoroso sulle informazioni sensibili, come la composizione dei piatti e la presenza di allergeni alimentari (glutine, lattosio, frutta a guscio).

Sono state distinte quattro categorie di fruitori:
- **Ospite**: utente non autenticato che visita il sito per consultare prodotti, prezzi o recapiti delle sedi;
- **Cliente** (utenza di prova: `user`): utente registrato che inserisce articoli nel carrello, conclude ordini e prenota la sala eventi;
- **Manager** (utenza di prova: `manager`): responsabile operativo di un singolo punto vendita con compiti di gestione locale;
- **Amministratore** (utenza di prova: `admin`): gestore dell'intera catena con facoltà di intervento su catalogo, sedi e utenze.

### 2.2. SEO

La struttura delle pagine e i contenuti testuali sono stati curati per rispondere a specifiche ricerche degli utenti sui motori di ricerca, tra cui:
- ricerche sul marchio ("smash burger", "smash burger menu");
- ricerche locali correlate alla presenza dei locali nelle singole città ("hamburger padova", "hamburger treviso", "hamburger vicenza", "hamburger udine");
- ricerche legate all'asporto o alla consegna a domicilio ("hamburger da asporto padova", "consegna hamburger a domicilio");
- ricerche mirate a particolari esigenze alimentari ("hamburger vegetariano", "allergeni hamburger");
- ricerche informative sul metodo gastronomico ("come si prepara lo smash burger", "carne smash");
- ricerche per feste ed eventi privati ("hamburgeria per eventi", "prenotazione sala feste").

Per favorire una corretta indicizzazione:
- ogni pagina presenta elementi `<title>` e meta tag `description` univoci e pertinenti;
- i contenuti sono strutturati mediante marcatori semantici HTML5 rispettando una gerarchia lineare;
- è stata generata una mappa del sito in formato XML (`sitemap.xml`) consultabile dai motori di ricerca;
- le immagini sono compresse in formato moderno WebP per limitare i tempi di scaricamento;
- l'assenza di dipendenze esterne riduce le richieste di rete e accelera il caricamento iniziale.

---

## 3. Progettazione

### 3.1. Schema organizzativo

La piattaforma adotta un'architettura modulare basata sui pattern **Page Controller**, **Transaction Script** e **Template View**:
- a ogni pagina del sito corrisponde un file PHP principale (controller) che riceve la richiesta HTTP, controlla i permessi di accesso e invoca le funzioni di logica applicativa;
- la logica di business e le operazioni sui dati sono raggruppate in funzioni procedurali pure, suddivise per ambito tematico (gestione carrello, elaborazione ordini, catalogo, prenotazioni, controllo accessi);
- le viste si occupano esclusivamente della generazione del codice HTML a partire dai dati ricevuti dal controller, senza eseguire query dirette sulla base di dati.

L'intero sito comprende 31 pagine registrate in modo centralizzato all'interno di un unico file di configurazione, suddivise in quattro ambiti di utilizzo:
- **Area pubblica** (14 pagine): home page, menu, scheda singolo prodotto, pagina servizi, presentazione dell'azienda (chi siamo), elenco sedi, scheda singola sede, modulo contatti, accesso, registrazione, policy di privacy, dichiarazione di accessibilità, mappa del sito, endpoint per il cambio tema;
- **Area utente** (7 pagine): area personale, modifica profilo, carrello, cassa e pagamento, ricevuta di conferma ordine, prenotazione della sala eventi, disconnessione;
- **Area controllo manager** (5 pagine): elenco ordini di sede, dettaglio singolo ordine, gestione prodotti di sede, gestione orari di sede, calendario prenotazioni;
- **Area controllo amministratore** (5 pagine): modifica scheda prodotto, gestione categorie, anagrafica sedi, consultazione messaggi di contatto, gestione permessi utenti.

### 3.2. Tipi di utente e permessi

L'accesso alle aree riservate avviene mediante autenticazione su sessione lato server. Al momento dell'accesso le credenziali fornite vengono verificate a fronte degli hash memorizzati nel database. Se l'accesso ha esito positivo, nella sessione dell'utente viene salvato il ruolo assegnato (`cliente`, `manager` oppure `amministratore`). Ogni pagina protetta invoca una funzione di verifica dei permessi che restituisce una risposta HTTP 401 (in caso di utente non autenticato) o HTTP 403 (in caso di autorizzazioni insufficienti per la pagina richiesta).

### 3.3. Funzionalità

Le principali funzionalità del sistema comprendono:
- navigazione del catalogo prodotti suddiviso per categorie merceologiche;
- consultazione della scheda di dettaglio di ciascun prodotto con allergeni e opzioni disponibili;
- registrazione di un nuovo profilo utente con validazione dei dati anagrafici;
- autenticazione e disconnessione sicura per le tre categorie di utenza;
- composizione del carrello con selezione della sede di ritiro o consegna;
- completamento dell'ordine con scelta della modalità (ritiro programmato in sede o consegna al domicilio indicato) e del metodo di pagamento;
- gestione della concorrenza sulle scorte: lo scarico della merce a magazzino avviene all'interno di una transazione atomica SQL, impedendo vendite superiori alla reale disponibilità;
- prenotazione della sala eventi con selezione di data, fascia oraria e durata, protetta da controlli transazionali anti-sovrapposizione;
- visualizzazione e modifica dello stato degli ordini da parte del personale;
- consultazione del grafico temporale degli incassi a 30 giorni per il monitoraggio economico;
- commutazione del tema grafico tra modalità chiara e scura, funzionante sia con JavaScript attivo sia tramite richiesta POST standard.

### 3.4. Convenzioni interne

Durante la progettazione dell'interfaccia sono state stabilite alcune convenzioni comuni:
- la parte superiore di ogni pagina presenta una struttura coerente composta da intestazione con logo, navigazione principale, indicatore dell'utenza e scorciatoia per il cambio del tema visivo;
- la voce del menu corrispondente alla pagina corrente non è implementata come link cliccabile (evitando link circolari), bensì come elemento testuale distinto marcato semanticamente con l'attributo `aria-current="page"`;
- è presente un percorso di navigazione (*breadcrumb*) che permette all'utente di comprendere costantemente la propria posizione all'interno della gerarchia del sito;
- i blocchi informativi e i prodotti sono presentati tramite schede visive (*card*), facilitando la scansione rapida dei contenuti;
- tutti i moduli di inserimento dati mantengono i valori digitati dall'utente in caso di errore di validazione, mostrando messaggi descrittivi posizionati in prossimità dei campi interessati;
- lo stato dei collegamenti ipertestuali già visitati viene differenziato cromaticamente rispetto ai collegamenti non ancora aperti.

### 3.5. Schema database

Il database è stato implementato su MariaDB versione 10.6. La struttura comprende 12 tabelle relazionali collegate da 15 vincoli di integrità referenziale (*foreign keys*). Tutti gli importi monetari sono memorizzati come numeri interi espressi in centesimi di euro, evitando così errori di arrotondamento legati ai tipi a virgola mobile.

Le tabelle sono le seguenti:
- **utenti**: anagrafica degli utenti registrati, credenziali cifrate, ruolo (`cliente`, `manager`, `amministratore`) e dati di consegna;
- **sedi**: elenco delle quattro sedi con indirizzo, recapiti telefonici e identificativo del manager incaricato;
- **orari_sedi**: fasce orarie di apertura e chiusura per ciascun giorno della settimana, con vincolo di unicità su sede e giorno;
- **categorie**: classificazione dei prodotti (hamburger, contorni, bevande, dessert) con ordine di visualizzazione personalizzabile;
- **prodotti**: catalogo delle vivande con denominazione, descrizione, allergeni, prezzo e percorso dell'immagine associata;
- **disponibilita_prodotti**: associazione tra sedi e prodotti, con quantità presente a magazzino e vincolo di controllo sulle quantità non negative;
- **carrelli** e **righe_carrello**: memorizzazione delle sessioni di acquisto temporanee collegate all'utente e alla sede selezionata;
- **ordini** e **righe_ordine**: registrazione degli ordini confermati, con tracciamento dello stato di lavorazione, modalità di ritiro o consegna, indirizzo, totale e storicizzazione puntuale dei prezzi dei prodotti;
- **prenotazioni**: richieste di utilizzo della sala eventi, con data, orari di inizio e fine, numero di partecipanti e stato di approvazione;
- **messaggi_contatto**: richieste inviate dagli utenti tramite il modulo contatti, suddivise per categoria e stato di gestione.

---

## 4. Realizzazione

L'attività commerciale "Smash Burger" è un'entità simulata a fini accademici. Le immagini illustrative dei prodotti, delle facciate dei locali e delle sezioni editoriali sono state **generate tramite strumenti di intelligenza artificiale**, al fine di garantire un'estetica omogenea e coordinata con l'interfaccia. Tutte le immagini sono state successivamente ritagliate nelle proporzioni opportune, convertite nel formato compresso WebP (con peso inferiore a 300 KB) e corredate da descrizioni testuali alternative (`alt`) accurate per la fruizione tramite screen reader.

### 4.1. Struttura e contenuto

#### 4.1.1. HTML

Il codice sorgente delle pagine è scritto in HTML5 nel rispetto dei vincoli di conformità XML:
- chiusura esplicita di tutti gli elementi, compresi i tag vuoti;
- attributi scritti interamente in minuscolo con valori racchiusi tra virgolette;
- esplicitazione completa degli attributi booleani (ad esempio `required="required"`);
- dichiarazione dei namespace XML e dell'attributo di lingua nella radice: `<html xmlns="http://www.w3.org/1999/xhtml" lang="it" xml:lang="it">`.

L'interfaccia è composta da 44 file di vista organizzati per area funzionale all'interno della cartella delle viste. La presenza di un unico punto di ingresso per l'assemblaggio della pagina (`mostra_pagina()`) garantisce l'inclusione sistematica dell'intestazione comune, del percorso di navigazione, dell'area dei contenuti e del piè di pagina.

#### 4.1.2. Popolamento database

La creazione e il popolamento iniziale della base di dati avvengono mediante l'esecuzione dello script SQL predisposto (`schema.sql`), contenente le istruzioni DDL per la generazione delle 12 tabelle e i dati dimostrativi: quattro sedi reali del territorio veneto e friulano, cinque categorie di menu, diciannove prodotti alimentari con ingredienti e allergeni, e tre account di prova con password cifrate mediante algoritmo di hashing sicuro.

### 4.2. Presentazione

#### 4.2.1. CSS

L'aspetto visivo è controllato da tre fogli di stile separati, collegati nell'intestazione HTML mediante l'attributo `media` per ottimizzarne l'applicazione in base al contesto d'uso:
- `stile.css`: foglio principale contenente il reset di base, la definizione delle variabili cromatiche e tipografiche, le regole di impaginazione e i componenti grafici per la visualizzazione standard;
- `mobile.css`: foglio dedicato ai dispositivi con larghezza dello schermo limitata, applicato mediante la regola `media="screen and (max-width: 48em)"` (corrispondente a 768 pixel con dimensione del font predefinita);
- `stampa.css`: foglio per la riproduzione cartacea, associato a `media="print"`.

Nello sviluppo dei fogli di stile abbiamo seguito questi principi:
- totale assenza di stili incorporati direttamente nel codice HTML tramite attributi `style`;
- centralizzazione dei parametri visivi (colori primari, superfici, spaziature, ombre) tramite custom properties CSS all'interno della pseudo-classe `:root`;
- utilizzo combinato di Flexbox e CSS Grid per la distribuzione degli elementi, limitando l'annidamento delle griglie per mantenere buone prestazioni di rendering;
- supporto automatico alle preferenze di movimento ridotto espresse dall'utente (`@media (prefers-reduced-motion: reduce)`), azzerando le animazioni decorative per prevenire disturbi visivi.

#### 4.2.2. Gestione del tema visivo

La piattaforma supporta due modalità grafiche distinte: chiara e scura.
La modalità scura viene attivata in due modi complementari:
1. in via automatica, intercettando la preferenza di sistema del sistema operativo o del browser tramite la media query `@media (prefers-color-scheme: dark)`;
2. in via manuale, mediante appositi pulsanti presenti nell'intestazione che consentono all'utente di forzare la modalità desiderata.

La scelta manuale viene memorizzata all'interno di un cookie sul browser dell'utente mediante una richiesta POST standard gestita lato server. In questo modo l'applicazione applica la classe del tema direttamente sul tag `<body>` durante la generazione della risposta HTML, assicurando il corretto funzionamento dell'interfaccia anche per gli utenti che navigano con JavaScript disattivato.

#### 4.2.3. CSS per la stampa

Il foglio di stile per la stampa (`stampa.css`) è stato progettato per garantire la leggibilità delle informazioni su supporto cartaceo con un occhio di riguardo al consumo di inchiostro (*PrintFriendly*):
- l'intera grafica è convertita in modalità monocromatica ad alto contrasto, impostando sfondo bianco e testo nero;
- tutti gli elementi di navigazione (menu di testata, percorso di navigazione, piè di pagina, pulsante torna su) e i controlli interattivi (pulsanti di azione, moduli di filtro) vengono nascosti;
- le ombreggiature e gli elementi decorativi sono azzerati;
- il layout viene linearizzato su colonna singola per adattarsi al foglio verticale;
- i margini di pagina sono impostati a 2 centimetri mediante la direttiva `@page`.

#### 4.2.4. Immagini e icone

La gestione dei file multimediali è suddivisa per tipologia:
- **Immagini del layout e delle sedi**: salvate nel formato compresso WebP all'interno della cartella delle immagini, con un peso per singolo file inferiore alla soglia prestazionale di 300 KB;
- **Immagini dei prodotti**: caricate dagli amministratori e collocate nella cartella dedicata ai caricamenti; la procedura di caricamento verifica l'effettivo tipo MIME del file tramite la funzione PHP `finfo_file()` e controlla che le dimensioni siano comprese tra 300 e 2000 pixel;
- **Icone dell'interfaccia**: raggruppate all'interno di un unico file SVG incorporato mediante una funzione helper PHP, evitando richieste di rete superflue;
- **Sicurezza della cartella di caricamento**: la cartella contenente le immagini caricate dall'amministratore è protetta da un file di configurazione server dedicato che inibisce l'esecuzione di qualsiasi script PHP, neutralizzando possibili tentativi di upload malevolo.

#### 4.2.5. Tipografia

Come carattere tipografico principale è stato scelto il font **Archivo**, un sans-serif geometrico a forte impatto visivo e di ottima leggibilità, distribuito con licenza libera Open Font License. I file del font sono archiviati localmente nel progetto in tre pesi distinti (regolare, grassetto e nero) e caricati via CSS con la direttiva `font-display: swap`. Lo stack di caratteri di riserva comprende font di sistema ampiamente diffusi: `"Archivo", "Helvetica Neue", Arial, sans-serif`.

#### 4.2.6. Tavolozza colori e contrasti

La selezione dei colori si ispira ai toni caratteristici dei condimenti tipici delle hamburgerie. Le tabelle seguenti riportano i codici esadecimali utilizzati per i due temi:

| Elemento visivo | Codice HEX (Tema chiaro) |
|---|---|
| Sfondo della pagina | `#faf7f2` |
| Superficie schede informative | `#fffdf9` |
| Testo principale | `#1a1a1a` |
| Accento caldo (senape) | `#9c6f00` |
| Colore di azione / avviso (ketchup) | `#b3181f` |
| Colore di successo (cetriolini) | `#3f6b24` |
| Bordi e divisori | `#d8cfc2` |
| Collegamenti visitati | `#61208f` |

| Elemento visivo | Codice HEX (Tema scuro) |
|---|---|
| Sfondo della pagina | `#171614` |
| Superficie schede informative | `#22201d` |
| Testo principale | `#f2ece4` |
| Accento caldo (senape) | `#e8a100` |
| Colore di azione / avviso (ketchup) | `#f0555a` |
| Colore di successo (cetriolini) | `#86bd4d` |
| Bordi e divisori | `#413d37` |
| Collegamenti visitati | `#d8adff` |

Tutte le combinazioni tra colore del testo e relativo sfondo garantiscono un rapporto di contrasto cromatico pari o superiore a 5.02:1, superando la soglia minima prevista dal livello AA delle linee guida WCAG 2.1 (pari a 4.5:1 per il testo ordinario e a 3:1 per il testo a grandi dimensioni).

### 4.3. Comportamento

#### 4.3.1. PHP

Il codice lato server è sviluppato in linguaggio PHP versione 8.1 secondo un paradigma procedurale pulito, privo di classi e composto da 131 funzioni distribuite in 20 file tematici.

I file cardine dell'infrastruttura comprendono:
- il file di configurazione generale, contenente la gestione dei parametri di sessione e le funzioni per la generazione dinamica di percorsi puramente relativi, consentendo al sito di funzionare correttamente a prescindere dal percorso di installazione sul server universitario;
- il modulo di connessione alla base di dati, basato sull'estensione PDO con l'impiego costante di istruzioni preparate (*prepared statements*) e parametri nominati;
- il file di registro delle pagine, che definisce in un unico punto i titoli, le descrizioni, i ruoli ammessi e la collocazione nei menu di ciascuna risorsa del sito.

Nei moduli di inserimento dati, in caso di sottomissione con errori, il sistema ripopola automaticamente i campi con i valori inseriti in precedenza ed espone messaggi diagnostici comprensivi di indicazioni puntuali per correggere il dato.

#### 4.3.2. JavaScript

Il comportamento lato client è racchiuso in un unico file (`script.js`) privo di librerie esterne e strutturato con direttiva `'use strict'`. Gli interventi si basano sulla filosofia del **Progressive Enhancement**: tutte le operazioni essenziali rimangono funzionanti anche qualora l'utente abbia disattivato JavaScript nel browser.

Lo script assolve a tre compiti principali:
1. **Pulsante torna su**: compare quando lo scorrimento supera una soglia determinata e riporta la pagina all'inizio riposizionando contestualmente il focus da tastiera sull'ancora iniziale, a beneficio di chi naviga senza mouse;
2. **Invio asincrono dei moduli (AJAX)**: i moduli provvisti dell'attributo dedicato inviano la richiesta tramite le API `fetch` native del browser, aggiornando la sola porzione centrale della pagina ed evitando il ricaricamento completo; in caso di guasto o disconnessione di rete, il modulo esegue un ripiego immediato (*fallback*) sottomettendosi nel modo convenzionale;
3. **Gestione del modulo di pagamento**: commuta la visibilità dei campi relativi all'indirizzo di spedizione in base alla modalità selezionata (ritiro in sede o consegna al domicilio) mediante attributi dati e regole CSS, senza alterare stili inline.

#### 4.3.3. Validazione dell'input

Tutti i dati inviati dagli utenti sono sottoposti a una doppia verifica:
- lato client, sfruttando i controlli nativi dei form HTML5 (attributi `required`, `pattern`, `maxlength`, tipi di campo specifici come `email` e `tel`);
- lato server, tramite funzioni PHP dedicate che convalidano il formato delle stringhe mediante espressioni regolari e filtri nativi prima di ogni scrittura nel database.

La presenza del controllo lato server garantisce che nessun dato non conforme possa raggiungere la base di dati anche aggirando le verifiche del browser.

#### 4.3.4. Sicurezza

In merito alla sicurezza applicativa sono state predisposte le seguenti difese:
- **Iniezioni SQL**: tutte le interrogazioni al database adoperano istruzioni preparate con parametri nominati PDO; nessuna variabile di input viene concatenata direttamente nella stringa SQL;
- **Cross-Site Scripting (XSS)**: ogni dato proveniente dall'utente viene sanificato prima dell'inserimento nell'HTML mediante una funzione dedicata che richiama `htmlspecialchars()` con i flag `ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML5` e codifica UTF-8;
- **Cross-Site Request Forgery (CSRF)**: ciascun modulo di modifica include un token casuale crittograficamente sicuro generato da `random_bytes()`, la cui corrispondenza viene verificata lato server mediante comparazione a tempo costante con `hash_equals()`;
- **Fissazione di sessione**: al momento dell'autenticazione viene rigenerato l'identificativo di sessione con `session_regenerate_id(true)`;
- **Impostazioni dei cookie**: il cookie di sessione è configurato con direttive `HttpOnly`, `SameSite=Lax` e flag `Secure` condizionato alla presenza del protocollo HTTPS;
- **Memorizzazione password**: le password non sono mai salvate in chiaro ma protette con algoritmo bcrypt mediante le funzioni native `password_hash()` e `password_verify()`;
- **Intestazioni di protezione e permessi**: il file di configurazione del server web imposta intestazioni di sicurezza (Content Security Policy priva di direttive permissive come unsafe-inline, X-Content-Type-Options nosniff, X-Frame-Options per contrastare il clickjacking) e vieta l'esplorazione delle cartelle di sistema (`Options -Indexes`).

#### 4.3.5. Pagine di errore personalizzate

In caso di problemi di navigazione o eccezioni del server, l'applicazione intercetta le richieste mediante direttive relative nel file `.htaccess` e mostra pagine di errore personalizzate per i codici HTTP 401, 403, 404 e 500. Ciascuna pagina di errore mantiene l'intestazione e la navigazione complessiva del sito, informando chiaramente l'utente sull'accaduto e offrendo collegamenti per proseguire la navigazione senza rimanere bloccati.

### 4.4. Accessibilità

L'obiettivo perseguito è la rispondenza alle linee guida WCAG 2.1 al livello di conformità AA, come previsto dalla normativa vigente:
- **Collegamento rapido**: il primo elemento del corpo pagina è il link "Vai al contenuto" (*skip link*), che consente agli utenti da tastiera di saltare l'intestazione e raggiungere direttamente la zona centrale;
- **Landmark semantici**: impiego ragionato degli elementi `<header>`, `<nav>`, `<main>`, `<section>`, `<article>` e `<footer>` per delineare le macro-aree del documento;
- **Titoli**: gerarchia rigorosa degli elementi di intestazione da `<h1>` a `<h4>`, senza salti di livello immotivati;
- **Moduli accessibili**: ogni campo di immissione dati è associato alla rispettiva etichetta visibile mediante attributi `for` e `id`; i gruppi di campi correlati sono racchiusi in elementi `<fieldset>` con descrizione in `<legend>`;
- **Tabelle**: fornite di didascalia semantica (`<caption>`) e celle di intestazione con attributi `scope="col"` e `scope="row"`;
- **Rappresentazione grafica degli incassi**: il grafico mensile generato in SVG è affiancato da una tabella riassuntiva dei medesimi dati, garantendo piena fruibilità anche in caso di mancata visualizzazione del disegno vettoriale;
- **Segnalazione di stato**: gli avvisi di conferma o di errore recano l'attributo `role="status"` e un prefisso testuale esplicito (ad esempio "Errore:" o "Fatto:") affinché l'informazione non sia veicolata dal solo colore.

#### 4.4.1. Accorgimenti per tecnologie assistive

- Applicazione dell'attributo `lang="en"` sui nomi in lingua inglese dei panini (come *Cheeseburger*, *Bacon Burger*, *Chicken Wings*), al fine di evitare pronunce scorrette da parte dei sintetizzatori vocali configurati per la lingua italiana;
- Utilizzo del marcatore `<abbr>` per esplicitare il significato di sigle e acronimi presenti nel testo (come WCAG e CAP);
- Inserimento dell'attributo `aria-hidden="true"` su tutte le icone decorative di contorno per non sovraccaricare la lettura audio;
- Assenza di collegamenti circolari, disattivando il link sulla voce di menu corrispondente alla pagina corrente.

#### 4.4.2. Comportamento su schermi diversi

L'impaginazione è interamente fluida e dimensionata tramite unità di misura relative (`em`, `rem`, percentuali). In nessuna pagina e con nessuna risoluzione (a partire da una larghezza minima di 320 pixel) si verifica alcuno scorrimento orizzontale non pianificato. La visualizzazione per dispositivi mobili entra in funzione alla larghezza di 48 em (768 pixel), riorganizzando i menu di testata e disponendo i contenuti a colonna singola per favorire l'interazione con una sola mano.

---

## 5. Test effettuati

### 5.1. Accessibilità e conformità agli standard

La verifica della qualità del sito è stata condotta integrando strumenti automatici di analisi e prove manuali approfondite.

Tra gli strumenti automatici impiegati si segnalano:
- **W3C Nu Html Checker**: utilizzato per la validazione della sintassi HTML5 di tutte le pagine, sia pubbliche che ad accesso riservato, con esito finale di zero errori;
- **W3C CSS Validation Service**: adoperato per verificare la conformità formale dei tre fogli di stile (`stile.css`, `mobile.css`, `stampa.css`), riscontrando zero errori di sintassi;
- **xmllint**: impiegato per accertare il rispetto rigoroso della corretta strutturazione XML su tutti i documenti renderizzati;
- **Pa11y**: strumento di analisi dell'accessibilità configurato sulle linee guida WCAG 2.1 AA, eseguito sistematicamente sulle pagine pubbliche e su quelle protette da autenticazione;
- **Google Lighthouse**: utilizzato per il collaudo complessivo su prestazioni, accessibilità, best practice e ottimizzazione SEO, con punteggi ampiamente superiori alla soglia di 90 punti in tutte le aree;
- **WAVE Web Accessibility Evaluation Tool** e **Silktide Accessibility**: estensioni del browser usate durante la stesura del codice per il controllo visuale dei contrasti cromatici, delle etichette e della gerarchia delle intestazioni.

I controlli manuali hanno compreso:
- navigazione completa da tastiera dell'intero sito tramite i tasti Tab, Invio e Spazio, verificando la visibilità costante dell'indicatore di focus e l'ordine logico degli spostamenti;
- sottomissione di tutti i form con campi vuoti, parziali o contenenti valori non conformi, verificando la chiarezza dei messaggi diagnostici e la conservazione dei dati validi;
- navigazione dell'intera applicazione con JavaScript completamente disabilitato, verificando il corretto funzionamento di menu, ordinazioni, cambio tema e pannello di controllo;
- collaudo visivo delle pagine su dispositivi reali e tramite strumenti di emulazione a varie risoluzioni (320px, 375px, 768px, 1024px, 1440px);
- prova di stampa fisica e su file PDF di ricevute, schede prodotto e pagine informative;
- test di compatibilità incrociata sui principali browser moderni: Google Chrome, Mozilla Firefox, Microsoft Edge, Apple Safari e Opera;
- test sui sistemi operativi più diffusi: Windows, Linux (Ubuntu), Android e iOS.

### 5.2. Automazione delle verifiche

Durante l'intero ciclo di sviluppo le verifiche automatiche sono state organizzate all'interno di una pipeline di Continuous Integration tramite GitHub Actions. Ad ogni aggiornamento del codice la procedura avvia un ambiente di prova ed esegue in successione:
1. la validazione del markup HTML5 con il validatore ufficiale del W3C e la verifica della correttezza XML;
2. il controllo dei tre fogli di stile CSS con le API del W3C;
3. l'audit di accessibilità con Pa11y, autenticandosi in modo automatico con le tre diverse utenze dimostrative per testare anche le viste private;
4. l'analisi prestazionale e qualitativa con Google Lighthouse;
5. la sincronizzazione automatica dei file sorgente sul server universitario attraverso un canale cifrato SSH con verifica dei corretti permessi sui file.

### 5.3. Analisi dei falsi positivi

L'applicazione dei validatori automatici ha restituito esito pulito su tutte le pagine. Gli unici avvisi non bloccanti segnalati dal validatore CSS riguardano la sintassi delle custom properties (`--nome-variabile`), che rappresentano funzionalità standard del CSS moderno pienamente supportate e conformi alle specifiche.

### 5.4. Verifiche con screen reader

L'accessibilità è stata testata manualmente tramite lo screen reader open-source **NVDA** su browser Mozilla Firefox e Google Chrome. Le sessioni di test hanno confermato:
- la corretta lettura del testo associato al collegamento rapido iniziale e la funzionalità di salto diretto al contenuto;
- l'annuncio dei ruoli e degli stati per gli elementi interattivi;
- la corretta lettura delle tabelle con le relative intestazioni di riga e colonna;
- la corretta pronuncia dei termini in lingua inglese grazie agli appositi attributi di lingua;
- la chiara comprensione dei messaggi di esito dei moduli, annunciati correttamente come messaggi di stato.

---

## 6. Ambiente di sviluppo

Per lo sviluppo locale e il collaudo preliminare dell'applicazione è stato predisposto un ambiente containerizzato tramite Docker. Questo ambiente riproduce fedelmente la configurazione del server universitario d'esame, impiegando un container web con PHP 8.1 ed estensioni Apache (`rewrite`, `headers`, `deflate`) e un container per la base di dati MariaDB 10.6, garantendo che il comportamento del codice in locale rispecchi fedelmente quello di produzione.

---

## 7. Organizzazione del gruppo

Il lavoro di progettazione, stesura del codice e verifica è stato distribuito in modo equilibrato tra tutti i componenti del gruppo, garantendo che ciascun membro partecipasse sia alla componente di presentazione sia alla logica applicativa e ai controlli di accessibilità.

### 7.1. Divisione dei compiti

- **[DA COMPILARE: Nome Cognome]**:
  - HTML/CSS: home page, catalogo menu, scheda dettaglio prodotto, pagina servizi;
  - PHP/JavaScript: funzionalità e controlli relativi alle pagine assegnate;
  - Base di dati: progettazione concettuale e logica dello schema relazionale;
  - Testing e validazione: verifiche con WAVE, Silktide, validatori W3C e test con NVDA;
  - Documentazione e relazione tecnica.

- **[DA COMPILARE: Nome Cognome]**:
  - HTML/CSS: pagine chi siamo, sedi, contatti, informativa privacy, dichiarazione di accessibilità;
  - PHP/JavaScript: gestione dell'invio messaggi di contatto e logica delle pagine assegnate;
  - Base di dati: definizione dei vincoli di integrità e indicizzazione;
  - Testing e validazione: verifiche con WAVE, Silktide, validatori W3C e test con NVDA;
  - Documentazione e relazione tecnica.

- **[DA COMPILARE: Nome Cognome]**:
  - HTML/CSS: flusso carrello, checkout e pagamento, ricevuta, storico ordini, prenotazione sala;
  - PHP/JavaScript: logica transazionale degli ordini, gestione scorte a magazzino e prenotazioni;
  - Base di dati: popolamento iniziale con dati di prova;
  - Testing e validazione: verifiche con WAVE, Silktide, validatori W3C e test con NVDA;
  - Documentazione e relazione tecnica.

- **[DA COMPILARE: Nome Cognome]**:
  - HTML/CSS: sezioni del pannello di controllo (prodotti, categorie, sedi, utenti, messaggi);
  - PHP/JavaScript: autenticazione, gestione delle sessioni, permessi e generazione del grafico SVG;
  - Pipeline e automazione: predisposizione dei controlli automatici di qualità e deploy;
  - Testing e validazione: verifiche con WAVE, Silktide, validatori W3C e test con NVDA;
  - Documentazione e relazione tecnica.

---

## 8. Note conclusive

Il sito web è stato sviluppato senza l'impiego di framework applicativi o librerie esterne (sia per la parte CSS che per quella JavaScript), attenendosi strettamente agli standard raccomandati. L'unica risorsa non prodotta direttamente dal gruppo è il font Archivo, distribuito sotto licenza Open Font License e incluso localmente nella distribuzione del progetto per escludere qualsiasi dipendenza da reti terze.

L'intera applicazione rispetta rigorosamente il principio del miglioramento progressivo: ogni operazione funzionale (navigazione del catalogo, emissione ordini, prenotazioni e operazioni amministrative) può essere portata a termine con successo anche in contesti d'uso privi di supporto JavaScript.
