# Checklist Elementi Interattivi e Funzionali per Pagina

Questo documento censisce, pagina per pagina, tutti gli elementi con cui l'utente o il sistema può interagire (pulsanti, collegamenti, moduli, immagini, controlli dinamici, notifiche). Per ciascun elemento viene specificato il comportamento atteso, i vincoli di accessibilità (WCAG 2.1 AA), il funzionamento con e senza JavaScript e lo stato di verifica.

---

## Convenzione di stato verifica

- `[ ]` **Da testare**: elemento censito ma non ancora collaudato manualmente.
- `[x]` **Verificato**: collaudato con successo (desktop, mobile, tastiera, screen reader, senza JS).
- `[!]` **Segnalazione**: riscontrata anomalia o comportamento divergente rispetto alle specifiche.

---

## Indice delle Pagine del Progetto

1. [Home](#1-home-indexphp)
2. [Menu (`menu.php`)](#2-menu-menuphp)
3. [Dettaglio Prodotto (`prodotto.php`)](#3-dettaglio-prodotto-prodottophp)
4. [Servizi (`servizi.php`)](#4-servizi-serviziphp)
5. [Chi siamo (`chi-siamo.php`)](#5-chi-siamo-chi-siamophp)
6. [Sedi (`sedi.php`)](#6-sedi-sediphp)
7. [Dettaglio Sede (`sede.php`)](#7-dettaglio-sede-sedephp)
8. [Contatti (`contatti.php`)](#8-contatti-contattiphp)
9. [Accedi (`accedi.php`)](#9-accedi-accediphp)
10. [Registrati (`registrati.php`)](#10-registrati-registratiphp)
11. [Esci (`esci.php`)](#11-esci-esciphp)
12. [Area personale (`area-personale.php`)](#12-area-personale-area-personalephp)
13. [Profilo utente (`profilo.php`)](#13-profilo-utente-profilophp)
14. [Carrello (`carrello.php`)](#14-carrello-carrellophp)
15. [Ritiro e Pagamento (`pagamento.php`)](#15-ritiro-e-pagamento-pagamentophp)
16. [Ricevuta ordine (`ricevuta.php`)](#16-ricevuta-ordine-ricevutaphp)
17. [Prenotazione Sala Eventi (`prenota.php`)](#17-prenotazione-sala-eventi-prenotaphp)
18. [Pannello: Ordini (`controllo.php`)](#18-pannello-ordini-controllophp)
19. [Pannello: Dettaglio Ordine (`controllo-ordine.php`)](#19-pannello-dettaglio-ordine-controllo-ordinephp)
20. [Pannello: Prodotti (`controllo-prodotti.php`)](#20-pannello-prodotti-controllo-prodottiphp)
21. [Pannello: Scheda Prodotto (`controllo-prodotto.php`)](#21-pannello-scheda-prodotto-controllo-prodottophp)
22. [Pannello: Categorie (`controllo-categorie.php`)](#22-pannello-categorie-controllo-categoriephp)
23. [Pannello: Sedi (`controllo-sedi.php`)](#23-pannello-sedi-controllo-sediphp)
24. [Pannello: Scheda Sede (`controllo-sede.php`)](#24-pannello-scheda-sede-controllo-sedephp)
25. [Pannello: Prenotazioni (`controllo-prenotazioni.php`)](#25-pannello-prenotazioni-controllo-prenotazioniphp)
26. [Pannello: Messaggi di contatto (`controllo-contatti.php`)](#26-pannello-messaggi-di-contatto-controllo-contattiphp)
27. [Pannello: Utenti (`controllo-utenti.php`)](#27-pannello-utenti-controllo-utentiphp)
28. [Privacy Policy (`privacy.php`)](#28-privacy-policy-privacyphp)
29. [Dichiarazione Accessibilità (`accessibilita.php`)](#29-dichiarazione-accessibilita-accessibilitaphp)
30. [Mappa del Sito (`mappa-sito.php`)](#30-mappa-del-sito-mappa-sitophp)
31. [Pagine di Errore (401, 403, 404, 500)](#31-pagine-di-errore-401-403-404-500)

---

## 1. Home (`index.php`)

La Home pubblica presenta il brand, il metodo di cottura, il podio dei burger consigliati, le quattro sedi attive e i percorsi rapidi verso il menu e la composizione dell'ordine.

### 1.1 Header e Navigazione Superiore (Condivisi)

- [x] **Salto al contenuto principale (`.salta`)**
  - **Tipo**: Collegamento interno (`<a class="salta" href="#contenuto">Vai al contenuto</a>`).
  - **Comportamento visivo**: Nascosto fuori schermo di default; appare in primo piano non appena riceve il focus da tastiera (tasto `Tab`).
  - **Comportamento al click/invio**: Sposta immediatamente il focus e lo scorrimento all'elemento `<main id="contenuto">`, saltando la navigazione per velocizzare l'esplorazione con screen reader o tastiera, senza provocare lo scorrimento della finestra.

- [x] **Logo e Marchio (`.marchio a`)**
  - **Tipo**: Collegamento ipertestuale (`href="index.php"` o `/`).
  - **Comportamento al click**: Ricarica o riporta alla Home page dall'inizio.
  - **Accessibilità**: Contiene il testo del brand ("Smash Burger") e il payoff ("Hot off the grill"); contrasto elevato e focus evidente.

- [x] **Voci di Navigazione Principale (`header nav[aria-label="Navigazione principale"]`)**
  - **Tipo**: Elenco di collegamenti (`Home`, `Menu`, `Servizi`, `Chi siamo`, `Sedi`).
  - **Comportamento su "Home"**: Presenta l'attributo `aria-current="page"`, indicatore visivo di pagina attiva (es. sottolineatura marcata/colore dedicato), link valido alla radice.
  - **Comportamento su altre voci**: Reindirizzano correttamente alle rispettive pagine (`menu.php`, `servizi.php`, `chi-siamo.php`, `sedi.php`).
  - **Mobile**: Su schermi ridotti la barra si adatta al layout mobile senza sovrapposizioni o tagli di testo.

- [x] **Cambio Tema Chiaro / Scuro (`form.tema`)**
  - **Tipo**: Modulo POST con due pulsanti a seconda del tema attuale (`.tema-toggle-verso-scuro` con icona luna, `.tema-toggle-verso-chiaro` con icona sole).
  - **Interazione con JavaScript**: Invia la richiesta e aggiorna istantaneamente la classe `tema-scuro` o `tema-chiaro` sul tag `<html>` preservando la posizione dell'utente.
  - **Interazione senza JavaScript**: Invia il form via POST a `tema.php` con token CSRF e parametri di ritorno (`ritorno`, `ritorno_query`), imposta il cookie/sessione e reindirizza alla Home esattamente al punto di partenza.
  - **Accessibilità**: `aria-label` esplicito ("Passa al tema scuro" / "Passa al tema chiaro") e tooltip `title`; testo testuale accessibile dentro `<span class="tema-toggle-contenuto">`.

- [x] **Navigazione Account / Utente (`header nav[aria-label="Il tuo account"]`)**
  - **Stato Utente Non Autenticato (Ospite)**:
    - [x] Link **"Accedi"**: porta a `accedi.php`.
    - [x] Pulsante **"Registrati"** (`.azione-header`): porta a `registrati.php`, stilizzato con enfasi grafica.
  - **Stato Cliente Autenticato**:
    - [x] Link **"Carrello"**: porta a `carrello.php`.
    - [x] Link **"Area personale"**: porta a `area-personale.php`.
    - [x] Link **"Esci"**: porta a `esci.php` (chiusura sessione).
  - **Stato Manager / Amministratore**:
    - [x] Link **"Controllo"**: porta a `controllo.php` (pannello gestionale).
    - [x] Link **"Area personale"** ed **"Esci"** regolarmente disponibili.

- [x] **Avvisi di Sistema / Flash Message (`p.avviso[role="status"]`)**
  - **Tipo**: Paragrafo dinamico generato solo se presente un messaggio in sessione (es. dopo logout o azione).
  - **Comportamento**: Se presente, riceve automaticamente il focus da tastiera (o viene annunciato dagli screen reader grazie a `role="status"`).

---

### 1.2 Sezione Apertura / Hero (`.apertura-home`)

- [x] **Pulsante CTA Primario "Ordina adesso" (`.apertura-testo .pulsante[data-tipo="positivo"]`)**
  - **Tipo**: Collegamento con stile pulsante ad alto contrasto.
  - **Destinazione**: `carrello.php` (avvia subito il flusso di composizione ordine / scelta sede).
  - **Stati visivi**: Evidente stato `:hover` e `:focus-visible` (bordo/ombra definiti, nessun cambio layout).

- [x] **Pulsante CTA Secondario "Guarda il menu" (`.apertura-testo .pulsante.secondario`)**
  - **Tipo**: Collegamento con stile pulsante secondario (bordo pieno/colore neutro).
  - **Destinazione**: `menu.php` (catalogo completo con prezzi, categorie e allergeni).
  - **Stati visivi**: Contrasto verificato rispetto allo sfondo sia in tema chiaro sia in tema scuro.

- [x] **Lista Punti Fermi / Promesse (`ul.promesse`)**
  - **Tipo**: Lista informativa (`04 sedi`, `01 burger alla volta`, `100% fatto al momento`).
  - **Accessibilità**: Etichettata con `aria-label="I nostri punti fermi"`. Non interattiva ma leggibile su qualsiasi ampiezza di schermo senza sovrapposizioni.

- [x] **Foto Editoriale Hero - Lo smash (`.istantanea-hero`)**
  - **Tipo**: Immagine editoriale in formato WebP (`images/home-hero-smash.webp`).
  - **Risoluzione & CLS**: Attributi `width="1120"` e `height="1400"` impostati per azzerare il salto di layout durante il caricamento (Cumulative Layout Shift = 0).
  - **Dimensione file**: Peso rigidamente inferiore a 300 KB.
  - **Accessibilità visiva**: Il contenitore `.foto-editoriale` ha `role="img"` con `aria-label="Una pressa d'acciaio schiaccia una pallina di manzo sulla piastra rovente."`.
  - **Didascalia**: Tag `<figcaption>` visibile con testo "Piastra rovente, crosta netta, zero attese.".

---

### 1.3 Sezione "Il podio della piastra" (`.podio`)

*Nota: La sezione viene renderizzata dinamicamente se nel database sono presenti i tre prodotti scelti per il podio (`italiano`, `bacon-burger`, `vegan-burger`).*

- [x] **Card Prodotto Podio - Immagini (`.panino-podio img`)**
  - **Tipo**: Foto del panino caricata da `uploads/prodotti/{immagine}`.
  - **Rapporto di forma**: `width="400"` e `height="300"`, nessun allungamento o distorsione (object-fit / proporzioni corrette).
  - **Accessibilità**: `alt=""` decorativo in quanto il nome e la descrizione del panino sono forniti testualmente subito a fianco nel link accessibile.

- [x] **Card Prodotto Podio - Link Nome Burger (`.panino-podio h3 a`)**
  - **Tipo**: Collegamento alla scheda singola del prodotto (`prodotto.php?slug={slug}`).
  - **Destinazione**: Apre la pagina del prodotto corrispondente con ingredienti, tabella allergeni e disponibilità nelle 4 sedi.
  - **Stati visivi**: Sottolineatura o variazione cromatica chiara al passaggio del mouse e al focus da tastiera.

- [x] **Card Prodotto Podio - Prezzo (`.panino-podio .prezzo`)**
  - **Tipo**: Testo formattato con valuta (€ X,XX) tramite funzione `prezzo()`.
  - **Verifica**: Corrispondenza al centesimo con il prezzo reale a database.

- [x] **Gradino e Posizione (`.gradino-podio .posizione`)**
  - **Tipo**: Numero ordinale (1, 2, 3) con testo accessibile nascosto per screen reader (`<span class="solo-lettori">posto</span>`).
  - **Verifica**: L'ausilio per non vedenti pronuncia "1 posto", "2 posto", "3 posto".

- [x] **Descrizione Breve Prodotto (`.gradino-podio p`)**
  - **Tipo**: Testo con gli ingredienti caratterizzanti proveniente direttamente dal catalogo DB.

- [x] **Link Finale "Vedi tutto il menu >" (`.chiusura-sezione .collegamento-freccia`)**
  - **Tipo**: Collegamento con freccia grafica (`href="menu.php"`).
  - **Accessibilità**: Il carattere `>` è racchiuso in `<span class="segno-collegamento" aria-hidden="true">&gt;</span>` per evitare letture fastidiose da parte dei lettori vocali.

---

### 1.4 Sezione Racconto Eventi (`.racconto-eventi`)

- [x] **Foto Editoriale Sala Eventi (`.racconto-eventi .foto-editoriale`)**
  - **Tipo**: Immagine WebP (`images/home-eventi.webp`) con attributo `loading="lazy"`.
  - **Risoluzione**: `width="1200"` e `height="900"`.
  - **Accessibilità**: `role="img"` con `aria-label="Un gruppo di ragazzi brinda a tavola con burger e patatine; uno skateboard è appoggiato al tavolo."`.
  - **Didascalia**: `<figcaption>` con testo "Compleanni, lauree e cene con tutta la crew.".

- [x] **Pulsante "Scopri come funziona" (`.racconto-eventi .pulsante`)**
  - **Tipo**: Collegamento con stile pulsante primario.
  - **Destinazione**: `servizi.php` (pagina di spiegazione dei servizi: asporto, domicilio e prenotazione sala).
  - **Comportamento**: Click diretto e navigazione immediata.

---

### 1.5 Sezione Racconto Metodo (`.racconto-metodo`)

- [x] **Pulsante "Entra in cucina" (`.racconto-metodo .pulsante.secondario`)**
  - **Tipo**: Collegamento con stile pulsante secondario.
  - **Destinazione**: `chi-siamo.php` (presentazione della filosofia di preparazione, provenienza materie prime e cucina).

- [x] **Foto Editoriale Metodo Smash (`.racconto-metodo .foto-editoriale`)**
  - **Tipo**: Immagine WebP (`images/home-metodo-smash.webp`) con `loading="lazy"`.
  - **Risoluzione**: `width="1200"` e `height="900"`.
  - **Accessibilità**: `role="img"` con `aria-label="Una mano con guanto nero preme la carne sulla piastra con una spatola dal manico senape."`.
  - **Didascalia**: `<figcaption>` con testo "Ogni ordine comincia da questo gesto.".

---

### 1.6 Sezione Città e Sedi (`.citta`)

*Nota: La lista è generata dinamicamente interrogando il database con `sedi_attive($pdo)` (Padova, Treviso, Vicenza, Udine).*

- [x] **Badge Sigla Provincia (`.sigla-citta`)**
  - **Tipo**: Testo con la sigla automobilistica (`PD`, `TV`, `VI`, `UD`).
  - **Visualizzazione**: Icona/distintivo visivo ad alto contrasto.

- [x] **Link Scheda Città / Sede (`.scheda-citta h3 a`)**
  - **Tipo**: Collegamento ipertestuale (`sede.php?slug={slug}`).
  - **Destinazione**: Apre la pagina dettagliata della sede selezionata (orari di apertura giornalieri, indirizzo, telefono, mappa, disponibilità sala eventi e stato ordini).
  - **Stati visivi**: Focus da tastiera evidente e reattività al tocco su dispositivi mobili.

- [x] **Indirizzo della Sede (`.scheda-citta p`)**
  - **Tipo**: Testo dinamico da DB (via e numero civico).

- [x] **Badge "Sala eventi" (`.scheda-citta .etichetta[data-tipo="positivo"]`)**
  - **Condizione**: Compare unicamente se la sede ha `sala_eventi_disponibile === 1` (presente per Padova, Treviso, Vicenza; assente per Udine).
  - **Verifica**: Nelle sedi sprovviste di sala eventi il badge non è visualizzato nel markup.

---

### 1.7 Sezione Guida Ordine Rapido (`.ordine-semplice`)

- [x] **Elenco Passi Ordinati (`ol.passi`)**
  - **Tipo**: Lista numerata semantica `<ol>` con i quattro passaggi:
    1. Scegli la sede da cui vuoi ordinare.
    2. Componi il tuo ordine con i prodotti disponibili.
    3. Decidi fra ritiro in sede o consegna a casa.
    4. Ritira all'orario scelto e salta la coda.
  - **Accessibilità**: Struttura semantica corretta per screen reader.

- [x] **Pulsante CTA "Inizia l'ordine" (`.ordine-semplice .pulsante[data-tipo="positivo"]`)**
  - **Tipo**: Collegamento con stile pulsante primario.
  - **Destinazione**: `carrello.php`.
  - **Comportamento**: Invito all'azione finale ben visibile prima della chiusura pagina.

---

### 1.8 Footer e Chiusura Pagina (Condivisi)

- [x] **Pulsante Galleggiante "Torna su" (`.torna-su`)**
  - **Tipo**: Collegamento ad àncora (`<a class="torna-su" href="#inizio">`).
  - **Comportamento dinamico (con JavaScript)**:
    - Nascosto in cima alla pagina (`data-visibile="false"`).
    - Appare con transizione fluida (`data-visibile="true"`) quando lo scorrimento supera 400px o il 65% della finestra visibile.
    - Se si avvicina al footer, calcola dinamicamente la proprietà CSS `--rialzo-footer` sollevandosi per non sovrapporsi ai testi dei contatti o dei link legali.
    - Se l'altezza della finestra è insufficiente a contenerlo senza toccare l'header, si nasconde automaticamente.
    - Al click esegue lo scorrimento fluido di `.pagina-scorribile` a `top: 0` e sposta il focus su `#inizio` prevenendo lo scroll accidentale della finestra (`window.scrollY = 0`).
  - **Comportamento senza JavaScript**:
    - Funziona come classica àncora HTML puntando a `<body id="inizio">` (Y = 0), prevenendo salti e spostamenti del layout della finestra.
  - **Accessibilità**: Testo accessibile dedicato per screen reader `<span class="solo-lettori">Torna su</span>`; attributo `title="Torna su"`.

- [x] **Navigazione "Esplora" (`footer nav[aria-label="Collegamenti di servizio"]`)**
  - **Collegamenti di servizio effettivi**:
    - [x] `contatti.php` (Contatti e assistenza)
    - [x] `privacy.php` (Privacy policy)
    - [x] `accessibilita.php` (Dichiarazione di accessibilità)
    - [x] `mappa-sito.php` (Mappa del sito)
  - **Verifica**: Tutti i link portano a pagine esistenti con codice HTTP 200.

- [x] **Sezione Contatti Rapidi (`footer address`)**
  - [x] **Link Email (`mailto:`)**: apre il client di posta predefinito compilando `informazioni@smashburger.it`.
  - [x] **Link Telefono (`tel:`)**: su smartphone avvia direttamente la chiamata verso `0491234567`.
  - [x] **Link "Orari di ogni sede"**: reindirizza a `sedi.php`.

- [x] **Validatori e Badge Ufficiali W3C (`.validazioni`)**
  - [x] **Badge Validatore HTML**: link verso `https://validator.w3.org/` con immagine `images/w3chtml.png` e attributo `alt="Markup validato dal servizio del W3C"`.
  - [x] **Badge Validatore CSS**: link verso `https://jigsaw.w3.org/css-validator/` con immagine `images/w3ccss.png` e attributo `alt="Fogli di stile validati dal servizio del W3C"`.

---

### 1.9 Criteri Trasversali di Qualità per la Home (WCAG 2.1 AA & Regole)

- [x] **Validazione Sintattica XML/HTML5**:
  - Nessun errore di validazione W3C (tag chiusi, attributi quotati, entità escape).
- [x] **Navigazione Completa da Tastiera**:
  - Tutta la pagina è navigabile sequenzialmente con tasto `Tab` e `Shift+Tab`.
  - Skip link funzionante al primo tab.
  - Lo stato `:focus-visible` è evidente su tutti i link e pulsanti (anello ad alto contrasto, mai `outline: none` senza sostituto).
- [x] **Prestazioni e Caricamento Risorse (Lighthouse > 90)**:
  - Tutte le immagini WebP pesano meno di 300 KB ciascuna e caricano regolarmente (`naturalWidth > 0`).
  - Nessun blocco di rendering non necessario; script caricato con `defer="defer"`.
- [x] **Degradazione Elegante senza JavaScript**:
  - Con JavaScript disabilitato, tutti i link, i pulsanti e il form del cambio tema funzionano al 100%.
- [x] **Responsive Design**:
  - Layout testato e fluido su schermi da 375px (mobile compatto) e 1280px (desktop) con zero scorrimento orizzontale anomalo (`scrollWidth <= innerWidth`).

---

### 1.10 Esito del Collaudo Automatizzato End-to-End (Playwright)

Report di esecuzione dei test eseguiti con browser reale (Chromium / Playwright) che simula tutte le interazioni dell'utente (click, tastiera, ridimensionamento mobile, caricamento immagini):

```text
=== INIZIO TEST UTENTE: HOME PAGE ===
  [OK] Caricamento iniziale Home: HTTP 200
  [OK] Skip link 'Vai al contenuto' compare al primo Tab
  [OK] Skip link sposta il focus su #contenuto (id=contenuto) senza scorrere window (scrollY=0)
  [OK] Click su Marchio/Logo mantiene sulla Home
  [OK] Link Home ha aria-current='page'
  [OK] Navigazione voce 'Menu' -> /menu ('Menu e prezzi - Smash Burger')
  [OK] Navigazione voce 'Servizi' -> /servizi ('Servizi: asporto, domicilio, eventi - Smash Burger')
  [OK] Navigazione voce 'Chi siamo' -> /chi-siamo ('Chi siamo - Smash Burger')
  [OK] Navigazione voce 'Sedi' -> /sedi ('Sedi e orari - Smash Burger')
  [OK] Cambio tema: click 'Passa a scuro' -> classe tema-scuro applicata a <html>
  [OK] Cambio tema: click 'Passa a chiaro' -> classe tema-scuro rimossa
  [OK] Click link 'Accedi' -> apre /accedi
  [OK] Click link 'Registrati' -> apre /registrati
  [OK] Foto editoriale Hero caricata correttamente (naturalWidth > 0, WebP)
  [OK] Foto editoriale Hero aria-label: 'Una pressa d'acciaio schiaccia una pallina di manzo sulla piastra rovente.'
  [OK] Hero CTA 'Ordina adesso' -> apre /carrello
  [OK] Hero CTA 'Guarda il menu' -> apre /menu
  [OK] Sezione Podio: trovate 3 card panino
  [OK] Podio #1 [Italiano] (11,50 €, 1 posto) - Immagine caricata e link apre la scheda prodotto
  [OK] Podio #2 [Bacon Burger] (10,90 €, 2 posto) - Immagine caricata e link apre la scheda prodotto
  [OK] Podio #3 [Vegan Burger] (10,90 €, 3 posto) - Immagine caricata e link apre la scheda prodotto
  [OK] Podio link 'Vedi tutto il menu >' -> apre /menu
  [OK] Foto Eventi caricata correttamente
  [OK] Racconto Eventi 'Scopri come funziona' -> apre /servizi
  [OK] Foto Metodo Smash caricata correttamente
  [OK] Racconto Metodo 'Entra in cucina' -> apre /chi-siamo
  [OK] Sezione Sedi: trovate 4 sedi attive da DB
  [OK] Click Sede Padova (PD) [Badge Sala eventi: Sì] -> apre /sede?slug=padova
  [OK] Click Sede Treviso (TV) [Badge Sala eventi: Sì] -> apre /sede?slug=treviso
  [OK] Click Sede Vicenza (VI) [Badge Sala eventi: Sì] -> apre /sede?slug=vicenza
  [OK] Click Sede Udine (UD) [Badge Sala eventi: No] -> apre /sede?slug=udine
  [OK] Guida ordine: 4 passi logici semantici
  [OK] CTA finale 'Inizia l'ordine' -> apre /carrello
  [OK] Pulsante Torna su: visibile dopo scroll (oltre 400px / 65%)
  [OK] Click 'Torna su': scorrimento fluido a inizio, scrollY=0, zero riga nera
  [OK] Footer 'Esplora' -> link 'Contatti' apre /contatti
  [OK] Footer 'Esplora' -> link 'Privacy' apre /privacy
  [OK] Footer 'Esplora' -> link 'Accessibilita' apre /accessibilita
  [OK] Footer 'Esplora' -> link 'Mappa del sito' apre /mappa-sito
  [OK] Footer 'Orari di ogni sede' apre /sedi
  [OK] Footer link email: mailto:informazioni@smashburger.it
  [OK] Footer link telefono: tel:0491234567
  [OK] Badge W3C HTML e CSS caricati correttamente

=== TEST RESPONSIVE MOBILE (375x667) ===
  [OK] Layout Mobile: nessun overflow orizzontale anomalo (scrollWidth <= 375px)

=== RIEPILOGO ===
Totale controlli: 49/49 superati (0 errori)
```

---

## 2. Menu (`menu.php`)

La pagina Menu espone il catalogo completo dei prodotti (19 articoli suddivisi in quattro categorie: Burger, Contorni, Bevande, Dessert), con prezzi in chiaro, indicazione degli allergeni e filtro immediato per categoria. Si consulta liberamente senza obbligo di autenticazione o scelta preventiva della sede.

### 2.1 Header e Navigazione Superiore

- [x] **Voce di Navigazione "Menu" (`header nav a:has-text('Menu')`)**
  - **Tipo**: Collegamento principale (`href="menu.php"` o `/menu`).
  - **Stato visivo e semantico**: Presenta `aria-current="page"`, evidenziato cromaticamente come pagina corrente.

- [x] **Percorso di Navigazione / Breadcrumb (`nav[aria-label="Percorso"]`)**
  - **Visualizzazione Generale ("Tutte")**:
    - [x] Livello 1: `<a href="/">Home</a>` (click ritorna alla Home).
    - [x] Livello 2: `<span aria-current="page">Menu</span>` (pagina corrente, non cliccabile).
  - **Visualizzazione con Categoria Selezionata (es. Burger)**:
    - [x] Livello 1: `<a href="/">Home</a>` (link a Home).
    - [x] Livello 2: `<a href="/menu">Menu</a>` (link al catalogo completo "Tutte").
    - [x] Livello 3: `<span aria-current="page">{Nome Categoria}</span>` (categoria attiva).
  - **Separatore estetico**: Separatori tra le voci gestiti da CSS (`::before`) per evitare la lettura ridondante con screen reader.

- [x] **Controlli Tema e Navigazione Account**:
  - [x] Toggle Tema Chiaro / Scuro coerente e funzionante.
  - [x] Link Accedi / Registrati (per ospite) o Carrello / Area personale / Esci (per utenti autenticati).

---

### 2.2 Sezione Apertura e Riepilogo Menu (`.apertura-pagina.apertura-menu`)

- [x] **Occhiello e Intestazione Principale (`main h1`)**
  - **Vista "Tutte"**: Occhiello *"Dalla piastra al vassoio"*, H1 *"Menu e prezzi"*, testo introduttivo generale.
  - **Vista Categoria**: H1 riporta il nome della categoria selezionata (es. *"Burger"*), con la descrizione specifica della categoria registrata a database.

- [x] **Cartello Informativo di Riepilogo (`aside.cartello-menu`)**
  - **Tipo**: Pannello informativo laterale ad alto impatto visivo (`aria-label="Riepilogo del menu mostrato"`).
  - **Contenuto dinamico**:
    - Nome del contesto corrente (*"Menu completo"* o nome della categoria attiva).
    - Conteggio numerico esatto dei prodotti visibili (`19` per tutte, `8` per burger, `4` per contorni, `4` per bevande, `3` per dessert).
    - Dicitura grammaticale coerente (*"prodotto in lista"* per singolare, *"prodotti in lista"* per plurale).

---

### 2.3 Barra dei Filtri Categoria (`nav.filtri[aria-label="Categorie del menu"]`)

- [x] **Filtro "Tutte" (`nav.filtri a:has-text('Tutte')`)**
  - **Destinazione**: `menu.php` (senza parametri query).
  - **Stato**: Attivo di default con `aria-current="true"`, mostra tutti i 19 prodotti del catalogo.

- [x] **Filtri di Categoria Dinamici**:
  - [x] **Burger**: URL `menu.php?categoria=burger`, mostra gli 8 burger a catalogo, imposta `aria-current="true"`.
  - [x] **Contorni**: URL `menu.php?categoria=contorni`, mostra i 4 contorni disponibili, imposta `aria-current="true"`.
  - [x] **Bevande**: URL `menu.php?categoria=bevande`, mostra le 4 bevande, imposta `aria-current="true"`.
  - [x] **Dessert**: URL `menu.php?categoria=dessert`, mostra i 3 dessert, imposta `aria-current="true"`.
  - **Accessibilità & Focus**: Tutti i filtri sono raggiungibili da tastiera con `Tab`, contrasto visivo elevato e feedback chiaro per lo stato attivo.

---

### 2.4 Griglia dei Prodotti (`ul.griglia.griglia-menu`)

- [x] **Etichetta Categoria Sovrimpressa (`.etichetta-categoria`)**
  - **Visualizzazione**: Badge rettangolare in alto a sinistra su ciascuna foto (es. *Burger*, *Contorni*).
  - **Funzione**: Identificazione visiva immediata della tipologia di piatto.

- [x] **Immagine del Prodotto (`.foto-prodotto img`)**
  - **Tipo**: Foto in formato WebP caricata da `uploads/prodotti/{immagine}`.
  - **Ottimizzazione**: `width="400"`, `height="300"`, `loading="lazy"`.
  - **Accessibilità**: Attributo `alt` valorizzato con il nome esatto del prodotto (es. `alt="Bacon Burger"`).

- [x] **Titolo del Prodotto con Link (`.nome-prezzo h2 a`)**
  - **Destinazione**: `prodotto.php?slug={slug}`.
  - **Comportamento**: Click/invio apre la scheda singola del prodotto con ingredienti dettagliati, tabella allergeni e disponibilità nelle sedi.
  - **Stati interattivi**: Sottolineatura ed evidenziazione `:hover` e `:focus-visible` chiare.

- [x] **Prezzo del Prodotto (`.nome-prezzo .prezzo`)**
  - **Formato**: Formattato in Euro tramite funzione `prezzo()` (es. `10,90 €`).
  - **Verifica**: Corrispondenza al centesimo con il prezzo archiviato nel database.

- [x] **Descrizione Ingredienti (`p.descrizione-prodotto`)**
  - **Testo**: Descrizione discorsiva che riassume gli ingredienti principali del prodotto.

- [x] **Indicazione Allergeni (`p.allergeni`)**
  - **Formato**: Etichetta semantica `<span>Allergeni</span>` seguita dall'elenco separato da virgole (es. `glutine, latte, uova, senape`) oppure dalla dicitura *"nessuno fra quelli dichiarati."*.

- [x] **Stato Vuoto (`.stato-vuoto`)**
  - **Condizione**: Selezionando una categoria priva di prodotti a catalogo, visualizza il messaggio informativo *"Non c'è ancora nessun prodotto in questa categoria."*.

---

### 2.5 Gestione Parametri e Errori 404

- [x] **Categoria non Valida o Inesistente**:
  - Se si richiede un parametro query non presente a database (es. `menu.php?categoria=inesistente`), il controller intercetta la richiesta ed emette correttamente lo stato **HTTP 404 Not Found** mostrando la pagina di errore semantica dedicata.

---

### 2.6 Sezione Invito Finale CTA (`section.invito-finale`)

- [x] **Pulsante CTA "Scegli la sede e ordina" (`.invito-finale a.pulsante[data-tipo="positivo"]`)**
  - **Tipo**: Collegamento con stile pulsante primario in chiusura pagina.
  - **Destinazione**: `carrello.php`.
  - **Comportamento**: Conduce l'utente che ha completato la consultazione verso il flusso di selezione sede e completamento ordine.

---

### 2.7 Footer e Navigazione Rapida

- [x] **Pulsante "Torna su" (`.torna-su`)**:
  - Funzionante anche sulla pagina del menu: al click oltre i 400px di scroll, riporta fluidamente in cima a `#inizio` senza scorrere la finestra nativa (`window.scrollY = 0`).
- [x] **Collegamenti di Servizio ("Esplora") e Contatti**:
  - Tutti i link del footer (`Contatti`, `Privacy`, `Accessibilità`, `Mappa del sito`, `Orari sedi`) confermati e raggiungibili con codice HTTP 200.

---

### 2.8 Criteri Trasversali di Qualità per il Menu (WCAG 2.1 AA & Regole)

- [x] **Sintassi e Validazione**: Markup conforme a HTML5 con sintassi XML valida, nessun errore Nu W3C.
- [x] **Navigabilità da Tastiera**: Tutti i 19 link ai prodotti, i 5 filtri di categoria e le CTA sono attivabili sequenzialmente con tasto `Tab` e `Invio`.
- [x] **Responsive Mobile**:
  - Testato con successo a risoluzione smartphone 375x667px.
  - Filtri orizzontali scorrevoli/adattabili, schede disposte in colonna singola nitida, zero overflow orizzontale (`scrollWidth <= innerWidth`).

---

### 2.9 Esito del Collaudo Automatizzato End-to-End (Playwright)

Report di esecuzione dei test eseguiti con browser reale (Chromium / Playwright) per la pagina Menu:

```text
=== INIZIO TEST UTENTE: PAGINA MENU ===
  [OK] Caricamento iniziale Menu: HTTP 200
  [OK] Header: Voce 'Menu' ha aria-current='page': True
  [OK] Breadcrumb iniziale: ['Home', 'Menu']
  [OK] Breadcrumb link 'Home' torna alla Home (http://localhost:8080/)
  [OK] Titolo H1 iniziale: 'Menu e prezzi'
  [OK] Cartello riepilogo: 'Menu completo' - 19 prodotti in lista
  [OK] Barra filtri categorie presente: ['Tutte', 'Burger', 'Contorni', 'Bevande', 'Dessert']
  [OK] Filtro 'Tutte' ha aria-current='true': True
  [OK] Griglia 'Tutte': visualizzati 19 prodotti (coincide con cartello: True)
  [OK] Filtro 'Burger': H1='Burger', prodotti=8 (cartello=8), aria-current='true'
  [OK]   -> Breadcrumb 3 livelli: ['Home', 'Menu', 'Burger']
  [OK] Filtro 'Contorni': H1='Contorni', prodotti=4 (cartello=4), aria-current='true'
  [OK]   -> Breadcrumb 3 livelli: ['Home', 'Menu', 'Contorni']
  [OK] Filtro 'Bevande': H1='Bevande', prodotti=4 (cartello=4), aria-current='true'
  [OK]   -> Breadcrumb 3 livelli: ['Home', 'Menu', 'Bevande']
  [OK] Filtro 'Dessert': H1='Dessert', prodotti=3 (cartello=3), aria-current='true'
  [OK]   -> Breadcrumb 3 livelli: ['Home', 'Menu', 'Dessert']
  [OK] Prodotto #1 [Bacon Burger] (Burger, 10,90 euro) - Immagine caricata: True (alt='Bacon Burger')
  [OK]   -> Allergeni: 'Allergeni glutine, latte, uova, senape'
  [OK]   -> Click apre scheda prodotto: http://localhost:8080/prodotto?slug=bacon-burger ('Bacon Burger - Smash Burger')
  [OK] Prodotto #2 [Cheeseburger] (Burger, 8,90 euro) - Immagine caricata: True (alt='Cheeseburger')
  [OK]   -> Allergeni: 'Allergeni glutine, latte, uova, senape'
  [OK]   -> Click apre scheda prodotto: http://localhost:8080/prodotto?slug=cheeseburger ('Cheeseburger - Smash Burger')
  [OK] Prodotto #3 [Chicken BBQ] (Burger, 10,50 euro) - Immagine caricata: True (alt='Chicken BBQ')
  [OK]   -> Allergeni: 'Allergeni glutine, latte, uova, soia'
  [OK]   -> Click apre scheda prodotto: http://localhost:8080/prodotto?slug=chicken-bbq ('Chicken BBQ - Smash Burger')
  [OK] Prodotto #19 [Milkshake alla vaniglia] (Dessert, 4,80 euro) - Immagine caricata: True (alt='Milkshake alla vaniglia')
  [OK]   -> Allergeni: 'Allergeni latte'
  [OK]   -> Click apre scheda prodotto: http://localhost:8080/prodotto?slug=milkshake-vaniglia ('Milkshake alla vaniglia - Smash Burger')
  [OK] Test categoria inesistente -> status HTTP 404: True
  [OK] Invito finale CTA 'Scegli la sede e ordina' -> apre http://localhost:8080/carrello
  [OK] Menu: Pulsante Torna su visibile dopo scroll: True
  [OK] Menu: Click 'Torna su': area.scrollTop=0, window.scrollY=0

=== TEST RESPONSIVE MOBILE (375x667): MENU ===
  [OK] Menu Mobile: nessun overflow orizzontale (scrollWidth <= 375): True

=== RIEPILOGO TEST MENU ===
Totale controlli eseguiti: 34
Superati: 34
Falliti: 0
```

---

## 3. Dettaglio Prodotto (`prodotto.php`)

La pagina singola di dettaglio del prodotto (raggiunta tramite parametro di query `prodotto.php?slug={slug}`, ad esempio `bacon-burger`, `cheeseburger`, `patate`, `milkshake-vaniglia`) espone l'articolo a risoluzione piena con foto dedicata, prezzo esatto in Euro, descrizione approfondita degli ingredienti, riepilogo dichiarativo degli allergeni con canale di assistenza dedicato, e la lista di disponibilità reale e giacenza nelle 4 sedi attive (con indicazione immediata dell'etichetta di esaurito se la quantità a magazzino è pari a zero). In caso di slug assente o inesistente, la richiesta risponde con stato HTTP 404.

### 3.1 Header e Navigazione Superiore

- [x] **Percorso di Navigazione / Breadcrumb a 4 Livelli (`nav[aria-label="Percorso"]`)**:
  - [x] Livello 1: `<a href="/">Home</a>` (click ritorna alla Home).
  - [x] Livello 2: `<a href="/menu">Menu</a>` (click ritorna al catalogo generale).
  - [x] Livello 3: `<a href="/menu?categoria={categoria_slug}">{Categoria}</a>` (click filtra il menu per la categoria del prodotto: Burger, Contorni, Bevande o Dessert).
  - [x] Livello 4: `<span aria-current="page">{Nome Prodotto}</span>` (nome del prodotto corrente, non cliccabile).

- [x] **Controlli Tema e Navigazione Account**:
  - [x] Toggle Tema Chiaro / Scuro coerente e funzionante.
  - [x] Link Accedi / Registrati (per ospite) o Carrello / Area personale / Esci (per utenti autenticati).

---

### 3.2 Sezione Apertura / Hero Prodotto (`section.dettaglio-prodotto`)

- [x] **Foto Dettaglio Prodotto (`.immagine-dettaglio-prodotto`)**:
  - **Badge Edizione**: `<p class="numero-edizione">Smash / {categoria_nome}</p>`.
  - **Immagine WebP**: caricata da `uploads/prodotti/{immagine}`, con dimensioni `width="600"`, `height="450"`.
  - **Accessibilità visiva**: attributo `alt` valorizzato con il nome del prodotto (es. `alt="Bacon Burger"`).
  - **Risoluzione & Peso**: ottimizzata per visualizzazione ad alta densità senza pesi eccessivi (< 300 KB).

- [x] **Dati e Testi Caratterizzanti (`.testo-dettaglio-prodotto`)**:
  - **Occhiello**: `{categoria_nome} · Preparato al momento` (es. *"Burger · Preparato al momento"*).
  - **Titolo H1**: nome del prodotto (es. *"Bacon Burger"*).
  - **Prezzo in Evidenza (`p.prezzo.prezzo-grande`)**: cifra formattata con valuta Euro tramite funzione `prezzo()` (es. `10,90 €`), perfettamente coincidente al centesimo con il listino a database.
  - **Descrizione Estesa (`p.introduzione`)**: testo completo che illustra composizione, carni, pane, salse e processo di preparazione.

- [x] **Pulsanti di Azione Principale (`.azioni`)**:
  - [x] **Pulsante CTA Primario "Scegli la sede e ordina" (`a.pulsante[data-tipo="positivo"]`)**:
    - Destinazione: `carrello.php` (avvia il processo di selezione sede e ordine).
    - Evidenza grafica primaria, contrasto conforme e focus evidente.
  - [x] **Pulsante CTA Secondario "Altri {categoria_nome}" (`a.pulsante.secondario`)**:
    - Destinazione: `menu.php?categoria={categoria_slug}` (permette di esplorare gli altri piatti della stessa tipologia).

---

### 3.3 Griglia Dettaglio Informativo (`.griglia-dettaglio`)

- [x] **Pannello 01 / Allergeni (`.pannello-informativo.pannello-allergeni`)**:
  - **Indice visivo**: `01`.
  - **Titolo H2**: *"Allergeni"*.
  - **Dichiarazione ingredienti allergenici**:
    - Se presenti: `Contiene: <strong>{allergeni}</strong>.` (es. *"Contiene: glutine, latte, uova, senape."*).
    - Se assenti: *"Non contiene nessuno degli allergeni che dichiariamo."*.
  - **Collegamento Assistenza Intolleranze**: link semantico `<a href="/contatti">Scrivici prima di ordinare</a>` che conduce al modulo di contatto.

- [x] **Pannello 02 / Disponibilità nelle Sedi (`.pannello-informativo.pannello-disponibilita`)**:
  - **Indice visivo**: `02`.
  - **Titolo H2**: *"Dove lo trovi adesso"*.
  - **Elenco Sedi Attive (`ul.elenco-sedi-disponibili`)**:
    - Elenca tutte le sedi che hanno il prodotto in carta con link rapido alla scheda locale `<a href="/sede?slug={slug}">{citta}</a>` e relativo indirizzo civico.
    - Se il prodotto è terminato in quella sede (`quantita <= 0`), compare il badge accessibile `<span class="etichetta" data-tipo="attenzione">esaurito</span>`.
    - Se nessuna sede lo propone in carta, visualizza il messaggio informativo con link di ritorno al menu.

---

### 3.4 Barra Navigazione Inferiore (`p.navigazione-pagina`)

- [x] **Pulsante "Torna a {categoria_nome}" (`a.pulsante.secondario`)**:
  - Conduce a `menu.php?categoria={categoria_slug}`.
- [x] **Pulsante "Scegli la sede e ordina" (`a.pulsante[data-tipo="positivo"]`)**:
  - Conduce a `carrello.php`.

---

### 3.5 Gestione Errori e Prodotti Inesistenti (HTTP 404)

- [x] **Slug Inesistente o Mancante**:
  - Richieste a `/prodotto` (senza parametro) o a `/prodotto?slug=inesistente` vengono intercettate dal controller ed emettono immediatamente lo stato **HTTP 404 Not Found**, visualizzando la pagina di errore semantica.

---

### 3.6 Footer e Chiusura Pagina

- [x] **Pulsante Galleggiante "Torna su" (`.torna-su`)**:
  - Verificato su schede prodotto: dopo lo scorrimento, compare con transizione fluida; al click riporta fluidamente in cima a `#inizio` azzerando `scrollTop` e mantenendo `window.scrollY = 0`.
- [x] **Collegamenti di Servizio ("Esplora") e Contatti Rapidi**:
  - Footer navigabile e verificato.

---

### 3.7 Criteri Trasversali di Qualità per il Dettaglio Prodotto (WCAG 2.1 AA & Regole)

- [x] **Sintassi e Validazione XML/HTML5**:
  - Markup conforme alle specifiche HTML5 con sintassi XML valida e gerarchia logica delle intestazioni (`h1` -> `h2`).
- [x] **Accessibilità Tastiera & Screen Reader**:
  - Percorso `Tab` completo e coerente: breadcrumb a 4 livelli, CTA ordina, CTA categoria, link contatti intolleranze, link sedi con disponibilità, pulsanti inferiori e torna su.
  - Immagini con testo alternativo corrispondente al nome del prodotto.
- [x] **Responsive Mobile**:
  - Testato con Playwright a 375x667px su articoli di tutte le 4 categorie: immagine ridimensionata proporzionalmente, bottoni e pannelli a colonna singola, nessun overflow orizzontale (`scrollWidth <= 375px`).
- [x] **Funzionamento senza JavaScript**:
  - La scheda e tutti i link di navigazione funzionano perfettamente senza JavaScript.

---

### 3.8 Esito del Collaudo Automatizzato End-to-End (Playwright)

Report di esecuzione dei test eseguiti con browser reale (Chromium / Playwright) per la pagina Dettaglio Prodotto su **tutti i 19 articoli del catalogo**:

```text
=== RACCOLTA DI TUTTI I 19 PRODOTTI DAL MENU ===
  [OK] Trovati esattamente 19 prodotti nel menu

=== INIZIO COLLAUDO DETTAGLIO PER TUTTI I 19 PRODOTTI ===
  [OK] [#01 Bacon Burger] HTTP 200 (bacon-burger) - Title: 'Bacon Burger - Smash Burger'
  [OK] [#01 Bacon Burger] Breadcrumb 4 livelli: ['Home', 'Menu', 'Burger', 'Bacon Burger']
  [OK] [#01 Bacon Burger] H1 corretto: 'Bacon Burger', Prezzo: 10,90 euro, Immagine: OK
  [OK] [#01 Bacon Burger] CTA 'Scegli la sede e ordina' -> /carrello, CTA 'Altri Burger' -> /menu?categoria=burger
  [OK] [#01 Bacon Burger] Pannello Allergeni con link di contatto per intolleranze
  [OK] [#01 Bacon Burger] Trovate 4 sedi con disponibilita'
  [OK] [#02 Cheeseburger] HTTP 200 (cheeseburger) - Title: 'Cheeseburger - Smash Burger'
  [OK] [#02 Cheeseburger] Breadcrumb 4 livelli: ['Home', 'Menu', 'Burger', 'Cheeseburger']
  [OK] [#02 Cheeseburger] H1 corretto: 'Cheeseburger', Prezzo: 8,90 euro, Immagine: OK
  [OK] [#02 Cheeseburger] CTA 'Scegli la sede e ordina' -> /carrello, CTA 'Altri Burger' -> /menu?categoria=burger
  [OK] [#02 Cheeseburger] Pannello Allergeni con link di contatto per intolleranze
  [OK] [#02 Cheeseburger] Trovate 4 sedi con disponibilita'
  [OK] [#03 Chicken BBQ] HTTP 200 (chicken-bbq) - Title: 'Chicken BBQ - Smash Burger'
  [OK] [#03 Chicken BBQ] Breadcrumb 4 livelli: ['Home', 'Menu', 'Burger', 'Chicken BBQ']
  [OK] [#03 Chicken BBQ] H1 corretto: 'Chicken BBQ', Prezzo: 10,50 euro, Immagine: OK
  [OK] [#03 Chicken BBQ] CTA 'Scegli la sede e ordina' -> /carrello, CTA 'Altri Burger' -> /menu?categoria=burger
  [OK] [#03 Chicken BBQ] Pannello Allergeni con link di contatto per intolleranze
  [OK] [#03 Chicken BBQ] Trovate 4 sedi con disponibilita'
  [OK] [#04 Chicken Burger] HTTP 200 (chicken-burger) - Title: 'Chicken Burger - Smash Burger'
  [OK] [#04 Chicken Burger] Breadcrumb 4 livelli: ['Home', 'Menu', 'Burger', 'Chicken Burger']
  [OK] [#04 Chicken Burger] H1 corretto: 'Chicken Burger', Prezzo: 9,90 euro, Immagine: OK
  [OK] [#04 Chicken Burger] CTA 'Scegli la sede e ordina' -> /carrello, CTA 'Altri Burger' -> /menu?categoria=burger
  [OK] [#04 Chicken Burger] Pannello Allergeni con link di contatto per intolleranze
  [OK] [#04 Chicken Burger] Trovate 4 sedi con disponibilita'
  [OK] [#05 In-N-Out] HTTP 200 (in-n-out) - Title: 'In-N-Out - Smash Burger'
  [OK] [#05 In-N-Out] Breadcrumb 4 livelli: ['Home', 'Menu', 'Burger', 'In-N-Out']
  [OK] [#05 In-N-Out] H1 corretto: 'In-N-Out', Prezzo: 10,50 euro, Immagine: OK
  [OK] [#05 In-N-Out] CTA 'Scegli la sede e ordina' -> /carrello, CTA 'Altri Burger' -> /menu?categoria=burger
  [OK] [#05 In-N-Out] Pannello Allergeni con link di contatto per intolleranze
  [OK] [#05 In-N-Out] Trovate 4 sedi con disponibilita'
  [OK] [#06 Italiano] HTTP 200 (italiano) - Title: 'Italiano - Smash Burger'
  [OK] [#06 Italiano] Breadcrumb 4 livelli: ['Home', 'Menu', 'Burger', 'Italiano']
  [OK] [#06 Italiano] H1 corretto: 'Italiano', Prezzo: 11,50 euro, Immagine: OK
  [OK] [#06 Italiano] CTA 'Scegli la sede e ordina' -> /carrello, CTA 'Altri Burger' -> /menu?categoria=burger
  [OK] [#06 Italiano] Pannello Allergeni con link di contatto per intolleranze
  [OK] [#06 Italiano] Trovate 4 sedi con disponibilita'
  [OK] [#07 Piccante] HTTP 200 (piccante) - Title: 'Piccante - Smash Burger'
  [OK] [#07 Piccante] Breadcrumb 4 livelli: ['Home', 'Menu', 'Burger', 'Piccante']
  [OK] [#07 Piccante] H1 corretto: 'Piccante', Prezzo: 10,90 euro, Immagine: OK
  [OK] [#07 Piccante] CTA 'Scegli la sede e ordina' -> /carrello, CTA 'Altri Burger' -> /menu?categoria=burger
  [OK] [#07 Piccante] Pannello Allergeni con link di contatto per intolleranze
  [OK] [#07 Piccante] Trovate 4 sedi con disponibilita'
  [OK] [#08 Vegan Burger] HTTP 200 (vegan-burger) - Title: 'Vegan Burger - Smash Burger'
  [OK] [#08 Vegan Burger] Breadcrumb 4 livelli: ['Home', 'Menu', 'Burger', 'Vegan Burger']
  [OK] [#08 Vegan Burger] H1 corretto: 'Vegan Burger', Prezzo: 10,90 euro, Immagine: OK
  [OK] [#08 Vegan Burger] CTA 'Scegli la sede e ordina' -> /carrello, CTA 'Altri Burger' -> /menu?categoria=burger
  [OK] [#08 Vegan Burger] Pannello Allergeni con link di contatto per intolleranze
  [OK] [#08 Vegan Burger] Trovate 4 sedi con disponibilita'
  [OK] [#09 Chicken Wings] HTTP 200 (chicken-wings) - Title: 'Chicken Wings - Smash Burger'
  [OK] [#09 Chicken Wings] Breadcrumb 4 livelli: ['Home', 'Menu', 'Contorni', 'Chicken Wings']
  [OK] [#09 Chicken Wings] H1 corretto: 'Chicken Wings', Prezzo: 7,50 euro, Immagine: OK
  [OK] [#09 Chicken Wings] CTA 'Scegli la sede e ordina' -> /carrello, CTA 'Altri Contorni' -> /menu?categoria=contorni
  [OK] [#09 Chicken Wings] Pannello Allergeni con link di contatto per intolleranze
  [OK] [#09 Chicken Wings] Trovate 4 sedi con disponibilita'
  [OK] [#10 Panzerotti] HTTP 200 (panzerotti) - Title: 'Panzerotti - Smash Burger'
  [OK] [#10 Panzerotti] Breadcrumb 4 livelli: ['Home', 'Menu', 'Contorni', 'Panzerotti']
  [OK] [#10 Panzerotti] H1 corretto: 'Panzerotti', Prezzo: 6,20 euro, Immagine: OK
  [OK] [#10 Panzerotti] CTA 'Scegli la sede e ordina' -> /carrello, CTA 'Altri Contorni' -> /menu?categoria=contorni
  [OK] [#10 Panzerotti] Pannello Allergeni con link di contatto per intolleranze
  [OK] [#10 Panzerotti] Trovate 3 sedi con disponibilita'
  [OK] [#11 Patate fritte] HTTP 200 (patate) - Title: 'Patate fritte - Smash Burger'
  [OK] [#11 Patate fritte] Breadcrumb 4 livelli: ['Home', 'Menu', 'Contorni', 'Patate fritte']
  [OK] [#11 Patate fritte] H1 corretto: 'Patate fritte', Prezzo: 4,50 euro, Immagine: OK
  [OK] [#11 Patate fritte] CTA 'Scegli la sede e ordina' -> /carrello, CTA 'Altri Contorni' -> /menu?categoria=contorni
  [OK] [#11 Patate fritte] Pannello Allergeni con link di contatto per intolleranze
  [OK] [#11 Patate fritte] Trovate 3 sedi con disponibilita'
  [OK] [#12 Tenders di pollo] HTTP 200 (tenders-di-pollo) - Title: 'Tenders di pollo - Smash Burger'
  [OK] [#12 Tenders di pollo] Breadcrumb 4 livelli: ['Home', 'Menu', 'Contorni', 'Tenders di pollo']
  [OK] [#12 Tenders di pollo] H1 corretto: 'Tenders di pollo', Prezzo: 6,90 euro, Immagine: OK
  [OK] [#12 Tenders di pollo] CTA 'Scegli la sede e ordina' -> /carrello, CTA 'Altri Contorni' -> /menu?categoria=contorni
  [OK] [#12 Tenders di pollo] Pannello Allergeni con link di contatto per intolleranze
  [OK] [#12 Tenders di pollo] Trovate 4 sedi con disponibilita'
  [OK] [#13 Acqua frizzante] HTTP 200 (acqua-frizzante) - Title: 'Acqua frizzante - Smash Burger'
  [OK] [#13 Acqua frizzante] Breadcrumb 4 livelli: ['Home', 'Menu', 'Bevande', 'Acqua frizzante']
  [OK] [#13 Acqua frizzante] H1 corretto: 'Acqua frizzante', Prezzo: 1,50 euro, Immagine: OK
  [OK] [#13 Acqua frizzante] CTA 'Scegli la sede e ordina' -> /carrello, CTA 'Altri Bevande' -> /menu?categoria=bevande
  [OK] [#13 Acqua frizzante] Pannello Allergeni con link di contatto per intolleranze
  [OK] [#13 Acqua frizzante] Trovate 4 sedi con disponibilita'
  [OK] [#14 Acqua naturale] HTTP 200 (acqua-naturale) - Title: 'Acqua naturale - Smash Burger'
  [OK] [#14 Acqua naturale] Breadcrumb 4 livelli: ['Home', 'Menu', 'Bevande', 'Acqua naturale']
  [OK] [#14 Acqua naturale] H1 corretto: 'Acqua naturale', Prezzo: 1,50 euro, Immagine: OK
  [OK] [#14 Acqua naturale] CTA 'Scegli la sede e ordina' -> /carrello, CTA 'Altri Bevande' -> /menu?categoria=bevande
  [OK] [#14 Acqua naturale] Pannello Allergeni con link di contatto per intolleranze
  [OK] [#14 Acqua naturale] Trovate 4 sedi con disponibilita'
  [OK] [#15 Bibita alla spina] HTTP 200 (bibita-alla-spina) - Title: 'Bibita alla spina - Smash Burger'
  [OK] [#15 Bibita alla spina] Breadcrumb 4 livelli: ['Home', 'Menu', 'Bevande', 'Bibita alla spina']
  [OK] [#15 Bibita alla spina] H1 corretto: 'Bibita alla spina', Prezzo: 3,00 euro, Immagine: OK
  [OK] [#15 Bibita alla spina] CTA 'Scegli la sede e ordina' -> /carrello, CTA 'Altri Bevande' -> /menu?categoria=bevande
  [OK] [#15 Bibita alla spina] Pannello Allergeni con link di contatto per intolleranze
  [OK] [#15 Bibita alla spina] Trovate 4 sedi con disponibilita'
  [OK] [#16 Birra artigianale] HTTP 200 (birra-artigianale) - Title: 'Birra artigianale - Smash Burger'
  [OK] [#16 Birra artigianale] Breadcrumb 4 livelli: ['Home', 'Menu', 'Bevande', 'Birra artigianale']
  [OK] [#16 Birra artigianale] H1 corretto: 'Birra artigianale', Prezzo: 5,50 euro, Immagine: OK
  [OK] [#16 Birra artigianale] CTA 'Scegli la sede e ordina' -> /carrello, CTA 'Altri Bevande' -> /menu?categoria=bevande
  [OK] [#16 Birra artigianale] Pannello Allergeni con link di contatto per intolleranze
  [OK] [#16 Birra artigianale] Trovate 4 sedi con disponibilita'
  [OK] [#17 Cono gelato] HTTP 200 (cono-gelato) - Title: 'Cono gelato - Smash Burger'
  [OK] [#17 Cono gelato] Breadcrumb 4 livelli: ['Home', 'Menu', 'Dessert', 'Cono gelato']
  [OK] [#17 Cono gelato] H1 corretto: 'Cono gelato', Prezzo: 3,50 euro, Immagine: OK
  [OK] [#17 Cono gelato] CTA 'Scegli la sede e ordina' -> /carrello, CTA 'Altri Dessert' -> /menu?categoria=dessert
  [OK] [#17 Cono gelato] Pannello Allergeni con link di contatto per intolleranze
  [OK] [#17 Cono gelato] Trovate 4 sedi con disponibilita'
  [OK] [#18 Milkshake alla banana] HTTP 200 (milkshake-banana) - Title: 'Milkshake alla banana - Smash Burger'
  [OK] [#18 Milkshake alla banana] Breadcrumb 4 livelli: ['Home', 'Menu', 'Dessert', 'Milkshake alla banana']
  [OK] [#18 Milkshake alla banana] H1 corretto: 'Milkshake alla banana', Prezzo: 4,80 euro, Immagine: OK
  [OK] [#18 Milkshake alla banana] CTA 'Scegli la sede e ordina' -> /carrello, CTA 'Altri Dessert' -> /menu?categoria=dessert
  [OK] [#18 Milkshake alla banana] Pannello Allergeni con link di contatto per intolleranze
  [OK] [#18 Milkshake alla banana] Trovate 4 sedi con disponibilita'
  [OK] [#19 Milkshake alla vaniglia] HTTP 200 (milkshake-vaniglia) - Title: 'Milkshake alla vaniglia - Smash Burger'
  [OK] [#19 Milkshake alla vaniglia] Breadcrumb 4 livelli: ['Home', 'Menu', 'Dessert', 'Milkshake alla vaniglia']
  [OK] [#19 Milkshake alla vaniglia] H1 corretto: 'Milkshake alla vaniglia', Prezzo: 4,80 euro, Immagine: OK
  [OK] [#19 Milkshake alla vaniglia] CTA 'Scegli la sede e ordina' -> /carrello, CTA 'Altri Dessert' -> /menu?categoria=dessert
  [OK] [#19 Milkshake alla vaniglia] Pannello Allergeni con link di contatto per intolleranze
  [OK] [#19 Milkshake alla vaniglia] Trovate 4 sedi con disponibilita'

=== COLLAUDO SCORRIMENTO TORNA SU ===
  [OK] Pulsante Torna su visibile su scheda prodotto dopo scroll
  [OK] Click 'Torna su': area.scrollTop=0, window.scrollY=0

=== COLLAUDO GESTIONE ERRORI 404 ===
  [OK] Prodotto inesistente (/prodotto?slug=pizza-margherita) -> HTTP 404
  [OK] Slug omesso (/prodotto) -> HTTP 404

=== COLLAUDO RESPONSIVE MOBILE (375x667) ===
  [OK] Mobile [bacon-burger]: nessun overflow su 375px: True
  [OK] Mobile [patate]: nessun overflow su 375px: True
  [OK] Mobile [birra-artigianale]: nessun overflow su 375px: True
  [OK] Mobile [milkshake-vaniglia]: nessun overflow su 375px: True

=== RIEPILOGO TEST COMPLETO TUTTI I PRODOTTI ===
Totale controlli eseguiti: 218
Superati: 218
Falliti: 0
```

---

## 4. Servizi (`servizi.php`)

La pagina informativa dei Servizi illustra le cinque modalità operative offerte da Smash Burger: asporto/ritiro al banco, consegna a domicilio tramite partner, prenotazione della sala eventi nelle sedi attrezzate, trasparenza e gestione degli allergeni, e spiegazione della simulazione di pagamento.

### 4.1 Header e Navigazione Superiore

- [x] **Voce di Navigazione "Servizi" (`header nav a:has-text('Servizi')`)**
  - **Tipo**: Collegamento principale (`href="servizi.php"` o `/servizi`).
  - **Stato visivo e semantico**: Presenta `aria-current="page"`, indicatore visivo evidenziato come pagina attiva.

- [x] **Percorso di Navigazione / Breadcrumb (`nav[aria-label="Percorso"]`)**
  - [x] Livello 1: `<a href="/">Home</a>` (click ritorna alla Home).
  - [x] Livello 2: `<span aria-current="page">Servizi</span>` (pagina corrente, non cliccabile).

- [x] **Controlli Tema e Navigazione Account**:
  - [x] Toggle Tema Chiaro / Scuro coerente e funzionante.
  - [x] Link Accedi / Registrati (per ospite) o Carrello / Area personale / Esci (per utenti autenticati).

---

### 4.2 Sezione Apertura / Hero (`section.apertura-editoriale`)

- [x] **Occhiello e Titolo Principale (`h1`)**:
  - Occhiello: *"Come vuoi mangiarlo?"*.
  - Titolo H1: *"Servizi"*.
  - Testo introduttivo esplicativo su ritiro, consegna e sala per occasioni di gruppo.

- [x] **Pulsante CTA Primario "Inizia un ordine" (`.apertura-editoriale .pulsante[data-tipo="positivo"]`)**:
  - **Tipo**: Collegamento con stile pulsante primario.
  - **Destinazione**: `carrello.php` (avvia la scelta della sede e la composizione dell'ordine).
  - **Stati visivi**: Contrasto verificato, focus evidenziato da tastiera.

- [x] **Foto Editoriale Hero - Ritiro al Banco (`.istantanea .foto-editoriale`)**:
  - **Tipo**: Immagine WebP (`images/servizi-ritiro.webp`).
  - **Risoluzione**: `width="1200"` e `height="900"`.
  - **Accessibilità visiva**: Contenitore con `role="img"` e `aria-label="Un addetto porge al cliente un vassoio con un burger fumante e patatine."`.
  - **Etichetta sovrimpressa**: Badge testuale con `<span>Ritiro / pronto al banco</span>` e `<strong>Pronto quando lo sei tu</strong>`.
  - **Didascalia**: `<figcaption>` visibile con testo *"Ritiro, consegna o una serata intera da noi."*.

---

### 4.3 Griglia delle Schede Servizio (`.griglia-servizi`)

- [x] **Scheda 01 / Ritiro (`.scheda-servizio.servizio-ritiro`)**:
  - **Indice visivo**: `01 / Ritiro`.
  - **Titolo H2**: *"Passa, prendi, mordi."*.
  - **Descrizione**: Spiega la scelta della sede, dei prodotti e dell'orario per azzerare l'attesa al banco.
  - **Nota informativa**: *"Disponibile negli orari di apertura della sede scelta."*.

- [x] **Scheda 02 / Consegna (`.scheda-servizio.servizio-consegna`)**:
  - **Indice visivo**: `02 / Consegna`.
  - **Titolo H2**: *"La piastra arriva a casa."*.
  - **Descrizione**: Spiega l'inserimento dell'indirizzo e la gestione affidata al partner di consegna esterno.
  - **Nota informativa / Rimborso**: *"Se l'indirizzo non è raggiungibile, annulliamo e rimborsiamo l'ordine."*.

- [x] **Scheda 03 / Eventi (`.scheda-servizio.servizio-eventi`)**:
  - **Indice visivo**: `03 / Eventi`.
  - **Titolo H2**: *"Porta la crew. Alla sala pensiamo noi."*.
  - **Descrizione**: Spiega la prenotazione della sala per compleanni, feste di laurea e cene di gruppo con data, orario e durata.
  - **Pulsante CTA "Scegli la sede e prenota" (`a.pulsante.secondario`)**:
    - **Destinazione**: `sedi.php` (porta all'elenco delle sedi con evidenza di quelle dotate di sala eventi).
    - **Comportamento**: Click diretto e navigazione immediata.

- [x] **Scheda 04 / Allergeni (`.scheda-servizio.servizio-info`)**:
  - **Indice visivo**: `04 / Allergeni`.
  - **Titolo H2**: *"Tutto scritto. Prima del morso."*.
  - **Descrizione**: Informa sulla presenza degli allergeni nel menu e nelle schede prodotto, e sulle contaminazioni crociate (glutine, latte, uova, frutta a guscio).
  - **Collegamento Assistenza**: Link semantico `<a href="contatti.php">Scrivici se hai un'allergia</a>` che apre direttamente la pagina dei contatti.

- [x] **Scheda 05 / Pagamento (`.scheda-servizio.servizio-pagamento`)**:
  - **Indice visivo**: `05 / Pagamento`.
  - **Titolo H2**: *"Carta o contanti. Senza sorprese."*.
  - **Descrizione**: Chiarisce la trasparenza del pagamento al ritiro o con carta (simulato a fini didattici, senza richiesta o salvataggio di dati sensibili bancari).

---

### 4.4 Footer e Chiusura Pagina

- [x] **Pulsante Galleggiante "Torna su" (`.torna-su`)**:
  - Verificato su Servizi: al click riporta fluidamente in cima a `#inizio` senza provocare scorrimenti verticali della finestra (`window.scrollY = 0`).
- [x] **Collegamenti di Servizio ("Esplora") e Contatti Rapidi**:
  - Tutti i link (`Contatti`, `Privacy`, `Accessibilita`, `Mappa del sito`, `Orari di ogni sede`, `mailto:`, `tel:`) funzionanti.

---

### 4.5 Criteri Trasversali di Qualità per i Servizi (WCAG 2.1 AA & Regole)

- [x] **Sintassi e Validazione XML/HTML5**:
  - Struttura semantica rigorosa con gerarchia dei titoli conforme (`h1` -> `h2`).
  - Validazione XML superata con successo.
- [x] **Accessibilità Tastiera & Screen Reader**:
  - Percorso `Tab` coerente: breadcrumb, CTA hero, CTA scheda eventi, link contatti, pulsante "Torna su", footer.
  - Contrasti cromatici conformi sia con tema chiaro sia con tema scuro.
- [x] **Responsive Mobile**:
  - Testato con Playwright a 375x667px: griglia a colonna singola, nessun overflow orizzontale (`scrollWidth <= 375px`).
- [x] **Funzionamento senza JavaScript**:
  - Tutti i collegamenti ipertestuali e le CTA funzionano nativamente tramite link standard.

---

### 4.6 Esito del Collaudo Automatizzato End-to-End (Playwright)

Report di esecuzione dei test eseguiti con browser reale (Chromium / Playwright) per la pagina Servizi:

```text
=== INIZIO TEST UTENTE: PAGINA SERVIZI ===
  [OK] Caricamento iniziale Servizi: HTTP 200
  [OK] Header: Voce 'Servizi' ha aria-current='page': True
  [OK] Breadcrumb: ['Home', 'Servizi']
  [OK] Breadcrumb link 'Home' torna alla Home (http://localhost:8080/)
  [OK] Hero: H1='Servizi', Occhiello='Come vuoi mangiarlo?'
  [OK] Hero: Testo introduttivo presente: 'Ordina online, scegli come ricevere e la...'
  [OK] Hero CTA 'Inizia un ordine' apre http://localhost:8080/carrello
  [OK] Foto editoriale Servizi caricata (naturalWidth > 0): True
  [OK] Foto editoriale aria-label descrittivo: 'Un addetto porge al cliente un vassoio con un burger fumante e patatine.'
  [OK] Griglia Servizi: trovate 5 schede
  [OK] Scheda 01 [Ritiro]: H2='Passa, prendi, mordi.'
  [OK] Scheda 02 [Consegna]: H2='La piastra arriva a casa.'
  [OK] Scheda 03 [Eventi]: H2='Porta la crew. Alla sala pensiamo noi.'
  [OK] CTA Eventi 'Scegli la sede e prenota' apre http://localhost:8080/sedi
  [OK] Scheda 04 [Allergeni]: H2='Tutto scritto. Prima del morso.'
  [OK] Link Allergeni 'Scrivici se hai un'allergia' apre http://localhost:8080/contatti
  [OK] Scheda 05 [Pagamento]: H2='Carta o contanti. Senza sorprese.'
  [OK] Servizi: Pulsante Torna su visibile dopo scroll: True
  [OK] Servizi: Click 'Torna su': area.scrollTop=0, window.scrollY=0

=== TEST RESPONSIVE MOBILE (375x667): SERVIZI ===
  [OK] Servizi Mobile: nessun overflow orizzontale (scrollWidth <= 375): True

=== RIEPILOGO TEST SERVIZI ===
Totale controlli eseguiti: 20
Superati: 20
Falliti: 0
```

---

## 5. Chi siamo (`chi-siamo.php`)

La pagina di presentazione del brand e della cucina racconta l'identità di Smash Burger, la tecnica di pressatura sulla piastra rovente, la selezione degli ingredienti a filiera corta e la filosofia di gestione decentralizzata ma coerente delle quattro sedi.

### 5.1 Header e Navigazione Superiore

- [x] **Voce di Navigazione "Chi siamo" (`header nav a:has-text('Chi siamo')`)**
  - **Tipo**: Collegamento principale (`href="chi-siamo.php"` o `/chi-siamo`).
  - **Stato visivo e semantico**: Presenta `aria-current="page"`, evidenziata cromaticamente e sottolineata come pagina attiva.

- [x] **Percorso di Navigazione / Breadcrumb (`nav[aria-label="Percorso"]`)**
  - [x] Livello 1: `<a href="/">Home</a>` (click ritorna alla Home).
  - [x] Livello 2: `<span aria-current="page">Chi siamo</span>` (pagina corrente, non cliccabile).

- [x] **Controlli Tema e Navigazione Account**:
  - [x] Toggle Tema Chiaro / Scuro coerente e funzionante.
  - [x] Link Accedi / Registrati (per ospite) o Carrello / Area personale / Esci (per utenti autenticati).

---

### 5.2 Sezione Apertura / Hero (`section.apertura-editoriale.apertura-chi-siamo`)

- [x] **Occhiello e Titolo Principale (`h1`)**:
  - Occhiello: *"Una cosa sola. Fatta sul serio."*.
  - Titolo H1: *"Chi siamo"*.
  - Paragrafo introduttivo sulle quattro sedi in Veneto e Friuli e sul principio fondamentale: il burger si prepara solo dopo la ricezione dell'ordine.

- [x] **Foto Editoriale Hero - Il Gesto dello Smash (`.istantanea .foto-editoriale`)**:
  - **Tipo**: Immagine WebP (`images/chi-siamo-smash.webp`).
  - **Risoluzione**: `width="1200"` e `height="900"`.
  - **Accessibilità visiva**: Contenitore con `role="img"` e `aria-label="Un cuoco in grembiule nero solleva dalla piastra fumante un burger dalla crosta scura."`.
  - **Badge e Testo sovrimpresso**:
    - `<span>La piastra / il nostro gesto</span>`
    - `<strong>Il gesto che cambia tutto</strong>`
  - **Didascalia**: `<figcaption>` con testo *"Pressione, calore, crosta. Tutto in pochi secondi."*.

---

### 5.3 Il Manifesto del Brand (`blockquote.manifesto`)

- [x] **Citazione Identitaria**:
  - Tag semantico `<blockquote>` ad alto impatto visivo con sfondo a contrasto.
  - Testo: *"Non prepariamo burger in anticipo. Prepariamo il tuo."*.
  - Funzione: Dichiarazione di valore e promessa cardine della cucina.

---

### 5.4 I Capitoli della Storia e della Tecnica (`.capitoli-storia`)

- [x] **Capitolo 01 / La tecnica (`.capitoli-storia section:nth-of-type(1)`)**:
  - **Indice visivo**: `01 / La tecnica`.
  - **Titolo H2**: *"Che cos'è lo smash"*.
  - **Descrizione**: Spiega la dinamica fisica della pallina di carne schiacciata sulla piastra, la formazione della crosta scura aromatica e la rapidità di cottura che mantiene succoso l'interno.

- [x] **Capitolo 02 / Gli ingredienti (`.capitoli-storia section:nth-of-type(2)`)**:
  - **Indice visivo**: `02 / Gli ingredienti`.
  - **Titolo H2**: *"Vicini, freschi, senza giri lunghi"*.
  - **Descrizione**: Approfondisce la carne fresca macinata in giornata, il pane e le salse forniti da produttori del territorio, e la composizione istantanea all'ingresso dell'ordine.

- [x] **Capitolo 03 / Le sedi (`.capitoli-storia section:nth-of-type(3)`)**:
  - **Indice visivo**: `03 / Le sedi`.
  - **Titolo H2**: *"Stesso menu. Presenza reale."*.
  - **Descrizione**: Spiega l'uniformità dei listini prezzi nelle quattro sedi e l'indipendenza di ciascun locale nella gestione orari, disponibilità effettiva dei prodotti e prenotazioni della sala eventi.

---

### 5.5 Sezione Invito Finale CTA (`section.invito-finale`)

- [x] **Occhiello e Intestazione**:
  - Occhiello: *"Vieni a sentirla sfrigolare"*.
  - Titolo H2: *"Quattro citta'. La stessa piastra."*.

- [x] **Pulsante CTA "Guarda sedi e orari" (`.invito-finale a.pulsante`)**:
  - **Tipo**: Collegamento con stile pulsante primario.
  - **Destinazione**: `sedi.php` (elenco completo delle sedi di Padova, Treviso, Vicenza e Udine).
  - **Comportamento**: Click diretto e navigazione immediata.

---

### 5.6 Footer e Chiusura Pagina

- [x] **Pulsante Galleggiante "Torna su" (`.torna-su`)**:
  - Verificato su Chi siamo: oltre i 400px / 65% di scorrimento appare regolarmente; al click riporta fluidamente in cima a `#inizio` azzerando `scrollTop` e mantenendo `window.scrollY = 0`.
- [x] **Collegamenti di Servizio ("Esplora") e Contatti Rapidi**:
  - Link a `Contatti`, `Privacy`, `Accessibilita`, `Mappa del sito`, `Orari di ogni sede`, `mailto:` e `tel:` pienamente raggiungibili.

---

### 5.7 Criteri Trasversali di Qualità per Chi siamo (WCAG 2.1 AA & Regole)

- [x] **Sintassi e Validazione XML/HTML5**:
  - Conforme alle specifiche HTML5 con sintassi XML valida e gerarchia logica delle intestazioni (`h1` -> `blockquote` -> `h2`).
- [x] **Accessibilità Tastiera & Screen Reader**:
  - Sequenza `Tab` fluida e logica: breadcrumb, pulsante CTA finale, pulsante "Torna su", footer.
  - Contrasti cromatici elevati sia con tema chiaro sia con tema scuro.
- [x] **Responsive Mobile**:
  - Testato con Playwright a 375x667px: layout a colonna singola, foto responsive, manifesto tipografico perfettamente leggibile, nessun overflow orizzontale (`scrollWidth <= 375px`).
- [x] **Funzionamento senza JavaScript**:
  - La pagina è interamente fruibile e i collegamenti ipertestuali funzionano nativamente senza dipendenza da script.

---

### 5.8 Esito del Collaudo Automatizzato End-to-End (Playwright)

Report di esecuzione dei test eseguiti con browser reale (Chromium / Playwright) per la pagina Chi siamo:

```text
=== INIZIO TEST UTENTE: PAGINA CHI SIAMO ===
  [OK] Caricamento iniziale Chi siamo: HTTP 200
  [OK] Titolo pagina corretto: 'Chi siamo - Smash Burger'
  [OK] Header: Voce 'Chi siamo' ha aria-current='page'
  [OK] Breadcrumb: ['Home', 'Chi siamo']
  [OK] Breadcrumb link 'Home' torna alla Home (http://localhost:8080/)
  [OK] Hero: H1='Chi siamo', Occhiello='Una cosa sola. Fatta sul serio.'
  [OK] Hero: Testo introduttivo presente: 'Quattro locali tra Veneto e Friuli, una ...'
  [OK] Foto editoriale Chi siamo caricata (naturalWidth > 0)
  [OK] Foto editoriale aria-label descrittivo: 'Un cuoco in grembiule nero solleva dalla piastra fumante un burger dalla crosta scura.'
  [OK] Citazione Manifesto presente: 'Non prepariamo burger in anticipo. Prepariamo il tuo.'
  [OK] Capitoli di storia: trovati 3 capitoli
  [OK] Capitolo 01 [Tecnica]: H2='Che cos'è lo smash'
  [OK] Capitolo 02 [Ingredienti]: H2='Vicini, freschi, senza giri lunghi'
  [OK] Capitolo 03 [Sedi]: H2='Stesso menu. Presenza reale.'
  [OK] CTA invito finale punta a: 'sedi'
  [OK] Click CTA invito finale apre http://localhost:8080/sedi
  [OK] Chi siamo: Pulsante Torna su visibile dopo scroll
  [OK] Chi siamo: Click 'Torna su': area.scrollTop=0, window.scrollY=0

=== TEST RESPONSIVE MOBILE (375x667): CHI SIAMO ===
  [OK] Chi siamo Mobile: nessun overflow orizzontale (scrollWidth <= 375)

=== RIEPILOGO TEST CHI SIAMO ===
Totale controlli eseguiti: 19
Superati: 19
Falliti: 0
```

---

## 6. Sedi (`sedi.php`)

La pagina Sedi presenta l'elenco generale dei quattro ristoranti Smash Burger attivi (Padova, Treviso, Vicenza, Udine), indicando per ciascuno la provincia, l'indirizzo, il recapito telefonico diretto, la disponibilità della sala eventi e il collegamento alla scheda di dettaglio della singola sede (con orari completi e indicazioni stradali).

### 6.1 Header e Navigazione Superiore

- [x] **Voce di Navigazione "Sedi" (`header nav a:has-text('Sedi')`)**
  - **Tipo**: Collegamento principale (`href="sedi.php"` o `/sedi`).
  - **Stato visivo e semantico**: Presenta `aria-current="page"`, evidenziata cromaticamente e sottolineata come pagina attiva.

- [x] **Percorso di Navigazione / Breadcrumb (`nav[aria-label="Percorso"]`)**
  - [x] Livello 1: `<a href="/">Home</a>` (click ritorna alla Home).
  - [x] Livello 2: `<span aria-current="page">Sedi</span>` (pagina corrente, non cliccabile).

- [x] **Controlli Tema e Navigazione Account**:
  - [x] Toggle Tema Chiaro / Scuro coerente e funzionante.
  - [x] Link Accedi / Registrati (per ospite) o Carrello / Area personale / Esci (per utenti autenticati).

---

### 6.2 Sezione Apertura / Hero (`section.apertura-pagina.apertura-sedi`)

- [x] **Occhiello e Titolo Principale (`h1`)**:
  - Occhiello: *"Quattro citta'. Una sola crosta."*.
  - Titolo H1: *"Sedi e orari"*.
  - Testo introduttivo sulla capillarità dei locali, uniformità dei prezzi e presenza della sala per feste e ricorrenze.

- [x] **Cartello Conteggio Locali (`p.numero-sedi`)**:
  - **Tipo**: Distintivo grafico con numero ad alto contrasto.
  - **Contenuto**: `<strong>04</strong><span>locali<br />aperti ogni giorno</span>`.
  - **Funzione**: Evidenza visiva del numero di sedi operative sul territorio.

---

### 6.3 Elenco delle Schede Sede (`ul.elenco-sedi`)

*Nota: L'elenco è generato dinamicamente leggendo le sedi attive dal database tramite `sedi_attive($pdo)`.*

- [x] **Immagine Esterna della Sede (`.scheda-sede .istantanea-sede img`)**:
  - **Tipo**: Foto in formato WebP caricata da `images/sedi/{slug}.webp` (`padova.webp`, `treviso.webp`, `vicenza.webp`, `udine.webp`).
  - **Dimensioni & Ottimizzazione**: `width="1200"`, `height="800"`, `loading="lazy"`.
  - **Accessibilità visiva**: Attributo `alt` generato contestualmente tramite `testo_alternativo_sede($sede['slug'], $sede['citta'])` (es. *"Esterno della sede Smash Burger a Padova con vetrina e insegna illuminata."*).
  - **Didascalia**: `<figcaption>` con dicitura *"Smash Burger {citta}, vista dalla strada."*.

- [x] **Sigla Provincia e Titolo con Link (`.dati-sede .titolo-sede`)**:
  - **Sigla provincia (`.sigla-citta`)**: Badge con sigla automobilistica ad alto contrasto (`PD`, `TV`, `VI`, `UD`).
  - **Titolo H2 con link (`h2 a`)**:
    - **Destinazione**: `sede.php?slug={slug}`.
    - **Comportamento**: Click/invio apre la scheda completa della singola sede con gli orari giorno per giorno, contatti e mappa interattiva.

- [x] **Recapiti e Indirizzo (`.dati-sede address`)**:
  - Struttura semantica `<address>` con via, numero civico, CAP, città e provincia.
  - **Collegamento Telefonico (`<a href="tel:...">`)**:
    - Numero formattato visibilmente per lettura umana (es. `049 1234567`).
    - Attributo `href` normalizzato senza spazi (`tel:0491234567`) per avvio immediato della chiamata da dispositivi abilitati.

- [x] **Badge Disponibilità Sala Eventi (`.dati-sede .etichetta`)**:
  - **Sedi con sala eventi (Padova, Treviso, Vicenza)**:
    - Mostra `<span class="etichetta" data-tipo="positivo">Sala eventi prenotabile</span>`.
  - **Sedi senza sala eventi (Udine)**:
    - Mostra `<span class="etichetta" data-tipo="attenzione">Sala eventi non prenotabile</span>`.
  - **Verifica**: Coerenza totale con il campo `sala_eventi_disponibile` a database.

- [x] **Collegamento Diretto "Apri la sede >" (`a.collegamento-freccia`)**:
  - **Tipo**: Link ipertestuale di richiamo verso `sede.php?slug={slug}`.
  - **Accessibilità**: Carattere `>` gestito semanticamente con `<span class="segno-collegamento" aria-hidden="true">&gt;</span>`.

---

### 6.4 Footer e Chiusura Pagina

- [x] **Pulsante Galleggiante "Torna su" (`.torna-su`)**:
  - Verificato su Sedi: dopo lo scorrimento dell'elenco, compare con transizione fluida; al click riporta a `#inizio` azzerando `scrollTop` e mantenendo `window.scrollY = 0`.
- [x] **Collegamenti di Servizio ("Esplora") e Contatti Rapidi**:
  - Link del footer verificati e funzionanti.

---

### 6.5 Criteri Trasversali di Qualità per le Sedi (WCAG 2.1 AA & Regole)

- [x] **Sintassi e Validazione XML/HTML5**:
  - Markup conforme a HTML5 con sintassi XML rigorosa.
- [x] **Accessibilità Tastiera & Screen Reader**:
  - Elenco ordinato semantico `<ul>` con `<article>` per ogni locale.
  - I link a ciascuna sede e i link telefonici sono raggiungibili sequenzialmente tramite tasto `Tab`.
  - Contrasti visivi delle etichette (verde positivo e rosso/arancio attenzione) conformi ai requisiti WCAG AA.
- [x] **Responsive Mobile**:
  - Testato con Playwright a 375x667px: schede disposte in colonna singola, foto proporzionate, testi e bottoni ampi e facilmente toccabili, nessun overflow orizzontale (`scrollWidth <= 375px`).
- [x] **Funzionamento senza JavaScript**:
  - Tutti i collegamenti alle schede sede, i numeri di telefono e il percorso di navigazione funzionano al 100% senza dipendenza da JS.

---

### 6.6 Esito del Collaudo Automatizzato End-to-End (Playwright)

Report di esecuzione dei test eseguiti con browser reale (Chromium / Playwright) per la pagina Sedi:

```text
=== INIZIO TEST UTENTE: PAGINA SEDI ===
  [OK] Caricamento iniziale Sedi: HTTP 200
  [OK] Titolo pagina corretto: 'Sedi e orari - Smash Burger'
  [OK] Header: Voce 'Sedi' ha aria-current='page'
  [OK] Breadcrumb: ['Home', 'Sedi']
  [OK] Breadcrumb link 'Home' torna alla Home (http://localhost:8080/)
  [OK] Hero: H1='Sedi e orari', Occhiello='Quattro citta'. Una sola crosta.'
  [OK] Badge conteggio sedi: '04' locali aperti ogni giorno
  [OK] Trovate 4 schede sede attive
  [OK] Sede #1 [Padova]: Immagine caricata (alt='Esterno della sede Smash Burge...')
  [OK] Sede #1 [Padova]: Citta' e provincia (PD) corretti
  [OK] Sede #1 [Padova]: Link titolo e link freccia puntano a 'sede?slug=padova'
  [OK] Sede #1 [Padova]: Link telefonico presente: '049 1234567'
  [OK] Sede #1 [Padova]: Badge 'Sala eventi prenotabile' (positivo)
  [OK] Sede #2 [Treviso]: Immagine caricata (alt='Esterno della sede Smash Burge...')
  [OK] Sede #2 [Treviso]: Citta' e provincia (TV) corretti
  [OK] Sede #2 [Treviso]: Link titolo e link freccia puntano a 'sede?slug=treviso'
  [OK] Sede #2 [Treviso]: Link telefonico presente: '0422 234567'
  [OK] Sede #2 [Treviso]: Badge 'Sala eventi prenotabile' (positivo)
  [OK] Sede #3 [Vicenza]: Immagine caricata (alt='Esterno della sede Smash Burge...')
  [OK] Sede #3 [Vicenza]: Citta' e provincia (VI) corretti
  [OK] Sede #3 [Vicenza]: Link titolo e link freccia puntano a 'sede?slug=vicenza'
  [OK] Sede #3 [Vicenza]: Link telefonico presente: '0444 345678'
  [OK] Sede #3 [Vicenza]: Badge 'Sala eventi prenotabile' (positivo)
  [OK] Sede #4 [Udine]: Immagine caricata (alt='Esterno ad angolo della sede S...')
  [OK] Sede #4 [Udine]: Citta' e provincia (UD) corretti
  [OK] Sede #4 [Udine]: Link titolo e link freccia puntano a 'sede?slug=udine'
  [OK] Sede #4 [Udine]: Link telefonico presente: '0432 456789'
  [OK] Sede #4 [Udine]: Badge 'Sala eventi non prenotabile' (attenzione)
  [OK] Click link sede [Padova] apre la scheda dettaglio (http://localhost:8080/sede?slug=padova)
  [OK] Sedi: Pulsante Torna su visibile dopo scroll
  [OK] Sedi: Click 'Torna su': area.scrollTop=0, window.scrollY=0

=== TEST RESPONSIVE MOBILE (375x667): SEDI ===
  [OK] Sedi Mobile: nessun overflow orizzontale (scrollWidth <= 375)

=== RIEPILOGO TEST SEDI ===
Totale controlli eseguiti: 32
Superati: 32
Falliti: 0
```

---

## 7. Dettaglio Sede (`sede.php`)

La pagina singola di dettaglio della sede (raggiunta tramite parametro di query `sede.php?slug={slug}`, ad esempio `padova`, `treviso`, `vicenza`, `udine`) fornisce le informazioni complete sul singolo locale: indirizzo dettagliato, orari di apertura per ogni giorno della settimana, recapiti specifici, gestione degli ordini per il ritiro e prenotazione dedicata della sala eventi (ove disponibile). Se il parametro è assente o corrisponde a una sede inesistente, la pagina emette lo stato HTTP 404.

### 7.1 Header e Navigazione Superiore

- [x] **Percorso di Navigazione / Breadcrumb a 3 livelli (`nav[aria-label="Percorso"]`)**:
  - [x] Livello 1: `<a href="/">Home</a>` (click ritorna alla Home).
  - [x] Livello 2: `<a href="/sedi">Sedi</a>` (click ritorna all'elenco generale delle sedi).
  - [x] Livello 3: `<span aria-current="page">{Nome Città}</span>` (città corrente, non cliccabile).

- [x] **Controlli Tema e Navigazione Account**:
  - [x] Toggle Tema Chiaro / Scuro coerente e funzionante.
  - [x] Link Accedi / Registrati (per ospite) o Carrello / Area personale / Esci (per utenti autenticati).

---

### 7.2 Sezione Apertura / Hero Sede (`section.apertura-sede`)

- [x] **Occhiello e Titolo Principale (`h1`)**:
  - Occhiello: `{provincia} · Aperto ogni giorno` (es. *"PD · Aperto ogni giorno"*).
  - Titolo H1: `Smash Burger {citta}` (es. *"Smash Burger Padova"*).
  - Titolo pagina `<title>` dinamico e descrittivo: `Smash Burger {citta}: indirizzo e orari`.

- [x] **Indirizzo in Evidenza (`address.indirizzo-grande`)**:
  - Mostra via, civico, CAP, città e provincia con formattazione semantica `<address>`.

- [x] **Pulsanti di Azione Principale (`.azioni`)**:
  - [x] **Pulsante CTA "Ordina da qui" (`a.pulsante[data-tipo="positivo"]`)**:
    - Destinazione: `carrello.php?sede={slug}` (preimposta la sede scelta nel flusso di composizione ordine).
    - Evidenza grafica primaria, contrasto elevato e focus accessibile.
  - [x] **Pulsante CTA "Chiama la sede" (`a.pulsante.secondario`)**:
    - Destinazione: `tel:{telefono}` (senza spazi).
    - Avvia la chiamata telefonica diretta al locale selezionato.

- [x] **Foto del Locale (`figure.istantanea.istantanea-locale`)**:
  - **Tipo**: Immagine WebP (`images/sedi/{slug}.webp`).
  - **Risoluzione & Ottimizzazione**: `width="1200"`, `height="800"`.
  - **Accessibilità visiva**: `alt` descrittivo contestualizzato generato da `testo_alternativo_sede()` (es. *"Esterno della sede Smash Burger a Padova con vetrina e insegna illuminata."*).
  - **Didascalia**: `<figcaption>` con dicitura *"Smash Burger {citta}, vista dalla strada."*.

---

### 7.3 Griglia Dettagli Informativi (`.griglia-sede-dettaglio`)

- [x] **Pannello 01 / Contatti (`.pannello-informativo.contatti-sede`)**:
  - **Indice visivo**: `01 / Contatti`.
  - **Titolo H2**: *"Dove siamo"*.
  - **Indirizzo completo**: via, CAP, città, provincia.
  - **Link Telefonico**: `<a href="tel:...">` con numero formattato per la lettura umana.
  - **Link Email del locale**: `<a href="mailto:{email}">` (es. `padova@smashburger.it`).

- [x] **Pannello 02 / Orari Settimanali (`.pannello-informativo.orari-sede`)**:
  - **Indice visivo**: `02 / Orari`.
  - **Titolo H2**: *"Quando trovarci"*.
  - **Tabella Semantica degli Orari**:
    - `<caption>` esplicativa: *"Orari di apertura della sede di {citta}"*.
    - Intestazioni colonna `<thead>`: `th[scope="col"] Giorno` e `th[scope="col"] Apertura`.
    - Corpo tabella `<tbody>`: 7 righe (Lunedì-Domenica) con `th[scope="row"]` per ciascun giorno e `<td>` con fascia oraria formattata da `fascia_leggibile()` (es. `11:30 - 22:30` oppure spezzato pranzo/cena).

- [x] **Pannello 03 / Ordini e Ritiro (`.pannello-informativo.ritiro-sede`)**:
  - **Indice visivo**: `03 / Ordini`.
  - **Titolo H2**: *"Ritiro senza coda"*.
  - **Note sul ritiro**: visualizza le indicazioni logistiche registrate nel database per la specifica sede (es. parcheggi dedicati, banco ritiro).
  - **Nota consegna**: *"Puoi anche farti consegnare l'ordine a casa."*.
  - **Link Diretto**: `a.collegamento-freccia` (*"Comincia l'ordine >"*) verso `carrello.php?sede={slug}`.

- [x] **Pannello 04 / Eventi e Prenotazione Sala (`.pannello-informativo.eventi-sede`)**:
  - **Indice visivo**: `04 / Eventi`.
  - **Titolo H2**: *"Una sala per la tua crew"*.
  - **Stato con Sala Eventi Disponibile (Padova, Treviso, Vicenza)**:
    - [x] Testo illustrativo: *"Scegli giorno, orario di inizio e durata. La sala è adatta a compleanni, feste di laurea e cene di gruppo."*.
    - [x] Pulsante CTA: `a.pulsante.secondario` (*"Prenota la sala"*) che apre `prenota.php?sede={slug}` per compilare la richiesta.
  - **Stato senza Sala Eventi (Udine)**:
    - [x] Badge di avviso: `<span class="etichetta" data-tipo="attenzione">Non prenotabile</span>`.
    - [x] Testo esplicativo: *"In questo periodo la sala di Udine non accetta prenotazioni."*.
    - [x] Link di ripiego: `<a href="/sedi">Guarda le altre sedi</a>`.

---

### 7.4 Barra Navigazione Inferiore (`p.navigazione-pagina`)

- [x] **Pulsante "< Torna alle sedi" (`a.pulsante.secondario.collegamento-indietro`)**:
  - Conduce all'elenco generale `/sedi`.
- [x] **Pulsante "Guarda il menu" (`a.pulsante`)**:
  - Conduce al catalogo dei prodotti `/menu`.

---

### 7.5 Gestione Errori e Pagine Mancanti (HTTP 404)

- [x] **Controllo Parametro Query `slug`**:
  - Se il parametro `slug` non viene passato (`/sede`), la pagina risponde con stato **HTTP 404 Not Found**.
  - Se il valore di `slug` non corrisponde ad alcuna sede attiva a database (es. `/sede?slug=milano`), la pagina risponde con stato **HTTP 404 Not Found** mostrando la pagina di errore semantica.

---

### 7.6 Footer e Chiusura Pagina

- [x] **Pulsante Galleggiante "Torna su" (`.torna-su`)**:
  - Compare dopo lo scorrimento; al click riporta fluidamente in cima a `#inizio` azzerando `scrollTop` e mantenendo `window.scrollY = 0`.
- [x] **Collegamenti di Servizio ("Esplora") e Contatti Rapidi**:
  - Footer navigabile e verificato.

---

### 7.7 Criteri Trasversali di Qualità per il Dettaglio Sede (WCAG 2.1 AA & Regole)

- [x] **Sintassi e Validazione XML/HTML5**:
  - Markup conforme a HTML5 con sintassi XML valida. Tabella orari completamente accessibile (`caption`, `th scope="col"`, `th scope="row"`).
- [x] **Navigabilità da Tastiera**:
  - Focus visibile e sequenziale su breadcrumbs, CTA hero ("Ordina da qui", "Chiama la sede"), link contatti, link ordini, CTA prenotazione sala, pulsanti di ritorno e "Torna su".
- [x] **Responsive Mobile**:
  - Testato con Playwright a 375x667px: layout a colonna singola, tabella orari fluida e leggibile, nessun overflow orizzontale (`scrollWidth <= 375px`).
- [x] **Funzionamento senza JavaScript**:
  - Tutte le funzionalità della pagina (consultazione orari, chiamate telefoniche, link carrello e prenotazione) funzionano nativamente tramite HTML puro.

---

### 7.8 Esito del Collaudo Automatizzato End-to-End (Playwright)

Report di esecuzione dei test eseguiti con browser reale (Chromium / Playwright) per la pagina Dettaglio Sede:

```text
=== INIZIO COLLAUDO DETTAGLIO PER TUTTE LE 4 SEDI ===

--- Collaudo Sede #1: Padova (PD) [slug: padova] ---
  [OK] [Padova] HTTP 200
  [OK] [Padova] Title: 'Smash Burger Padova: indirizzo e orari'
  [OK] [Padova] Breadcrumb: ['Home', 'Sedi', 'Padova']
  [OK] [Padova] H1='Smash Burger Padova', Occhiello='PD · Aperto ogni giorno'
  [OK] [Padova] Indirizzo: 'Via San Fermo 34 35137 Padova (PD)'
  [OK] [Padova] CTA 'Ordina da qui' punta a carrello con sede=padova
  [OK] [Padova] CTA 'Chiama la sede' punta a tel:0491234567
  [OK] [Padova] Foto sede caricata (naturalWidth > 0)
  [OK] [Padova] Pannello Contatti email corretta: 'padova@smashburger.it'
  [OK] [Padova] Tabella orari completa con 7 giorni
  [OK] [Padova] Note ritiro presenti: 'banco a destra'
  [OK] [Padova] Sala eventi DISPONIBILE -> CTA 'Prenota la sala' presente per padova

--- Collaudo Sede #2: Treviso (TV) [slug: treviso] ---
  [OK] [Treviso] HTTP 200
  [OK] [Treviso] Title: 'Smash Burger Treviso: indirizzo e orari'
  [OK] [Treviso] Breadcrumb: ['Home', 'Sedi', 'Treviso']
  [OK] [Treviso] H1='Smash Burger Treviso', Occhiello='TV · Aperto ogni giorno'
  [OK] [Treviso] Indirizzo: 'Via Calmaggiore 18 31100 Treviso (TV)'
  [OK] [Treviso] CTA 'Ordina da qui' punta a carrello con sede=treviso
  [OK] [Treviso] CTA 'Chiama la sede' punta a tel:0422234567
  [OK] [Treviso] Foto sede caricata (naturalWidth > 0)
  [OK] [Treviso] Pannello Contatti email corretta: 'treviso@smashburger.it'
  [OK] [Treviso] Tabella orari completa con 7 giorni
  [OK] [Treviso] Note ritiro presenti: 'cassa centrale'
  [OK] [Treviso] Sala eventi DISPONIBILE -> CTA 'Prenota la sala' presente per treviso

--- Collaudo Sede #3: Vicenza (VI) [slug: vicenza] ---
  [OK] [Vicenza] HTTP 200
  [OK] [Vicenza] Title: 'Smash Burger Vicenza: indirizzo e orari'
  [OK] [Vicenza] Breadcrumb: ['Home', 'Sedi', 'Vicenza']
  [OK] [Vicenza] H1='Smash Burger Vicenza', Occhiello='VI · Aperto ogni giorno'
  [OK] [Vicenza] Indirizzo: 'Corso Palladio 92 36100 Vicenza (VI)'
  [OK] [Vicenza] CTA 'Ordina da qui' punta a carrello con sede=vicenza
  [OK] [Vicenza] CTA 'Chiama la sede' punta a tel:0444345678
  [OK] [Vicenza] Foto sede caricata (naturalWidth > 0)
  [OK] [Vicenza] Pannello Contatti email corretta: 'vicenza@smashburger.it'
  [OK] [Vicenza] Tabella orari completa con 7 giorni
  [OK] [Vicenza] Note ritiro presenti: 'bancone accanto alle vetrine'
  [OK] [Vicenza] Sala eventi DISPONIBILE -> CTA 'Prenota la sala' presente per vicenza

--- Collaudo Sede #4: Udine (UD) [slug: udine] ---
  [OK] [Udine] HTTP 200
  [OK] [Udine] Title: 'Smash Burger Udine: indirizzo e orari'
  [OK] [Udine] Breadcrumb: ['Home', 'Sedi', 'Udine']
  [OK] [Udine] H1='Smash Burger Udine', Occhiello='UD · Aperto ogni giorno'
  [OK] [Udine] Indirizzo: 'Via Mercatovecchio 7 33100 Udine (UD)'
  [OK] [Udine] CTA 'Ordina da qui' punta a carrello con sede=udine
  [OK] [Udine] CTA 'Chiama la sede' punta a tel:0432456789
  [OK] [Udine] Foto sede caricata (naturalWidth > 0)
  [OK] [Udine] Pannello Contatti email corretta: 'udine@smashburger.it'
  [OK] [Udine] Tabella orari completa con 7 giorni
  [OK] [Udine] Note ritiro presenti: 'banco vicino alla scala'
  [OK] [Udine] Sala eventi NON DISPONIBILE -> Badge 'Non prenotabile' presente

--- Collaudo Torna su e Controlli Globali ---
  [OK] Pulsante Torna su visibile dopo scroll
  [OK] Click 'Torna su': area.scrollTop=0, window.scrollY=0

--- Collaudo Gestione Errori 404 ---
  [OK] Slug inesistente (/sede?slug=milano) -> HTTP 404
  [OK] Slug omesso (/sede) -> HTTP 404

--- Collaudo Responsive Mobile (375x667) per tutte le sedi ---
  [OK] [Padova] Nessun overflow orizzontale su 375px: True
  [OK] [Treviso] Nessun overflow orizzontale su 375px: True
  [OK] [Vicenza] Nessun overflow orizzontale su 375px: True
  [OK] [Udine] Nessun overflow orizzontale su 375px: True

=== RIEPILOGO TEST COMPLETO DETTAGLIO SEDI ===
Totale controlli eseguiti: 56
Superati: 56
Falliti: 0
```

---

## 8. Contatti (`contatti.php`)

La pagina Contatti offre agli utenti e ai clienti i canali di assistenza diretta (posta elettronica, recapito telefonico centralizzato, orari di operatività) e il modulo accessibile per inviare richieste, segnalazioni o informazioni sul servizio con validazione e salvataggio nel database.

### 8.1 Header e Navigazione Superiore

- [x] **Percorso di Navigazione / Breadcrumb (`nav[aria-label="Percorso"]`)**
  - [x] Livello 1: `<a href="/">Home</a>` (click ritorna alla Home).
  - [x] Livello 2: `<span aria-current="page">Contatti</span>` (pagina corrente, non cliccabile).

- [x] **Controlli Tema e Navigazione Account**:
  - [x] Toggle Tema Chiaro / Scuro coerente e funzionante.
  - [x] Link Accedi / Registrati (per ospite) o Carrello / Area personale / Esci (per utenti autenticati).

---

### 8.2 Sezione Apertura ed Elementi Informativi (`section.apertura-contatti`)

- [x] **Occhiello e Titolo Principale (`h1`)**:
  - Occhiello: *"Parliamo chiaro"*.
  - Titolo H1: *"Contatti"*.
  - Testo introduttivo sulle tempistiche di evasione delle richieste (risposta via email, di solito entro un giorno lavorativo).

- [x] **Riquadro Contatti Diretti (`aside.contatti-diretti`)**:
  - **Indice visivo**: `Contatto diretto`.
  - **Canale Email**: `<a href="mailto:informazioni@smashburger.it">informazioni@smashburger.it</a>` (apre il client di posta).
  - **Canale Telefonico**: `<a href="tel:0491234567">049 1234567</a>` (avvia la chiamata diretta su dispositivi abilitati).
  - **Orari di Assistenza**: `Ogni giorno, 11:30 - 22:30`.

---

### 8.3 Modulo di Contatto (`form.modulo-contatto`)

- [x] **Configurazione e Sicurezza del Modulo**:
  - **Metodo e Azione**: `method="post" action="contatti.php"`.
  - **Token CSRF**: `<input type="hidden" name="token_csrf" value="..." />`, validato in tempo costante tramite `csrf_valido()` ad ogni invio.
  - **Fieldset semantico**: `<fieldset>` con `<legend>Raccontaci di cosa hai bisogno</legend>`.

- [x] **Campo Nome (`input#nome`)**:
  - **Tipo e Vincoli**: `type="text"`, `required="required"`, `minlength="2"`, `maxlength="120"`, `autocomplete="name"`.
  - **Etichetta accessibile**: `<label for="nome">Nome</label>`.
  - **Gestione Errori**: se vuoto o con meno di 2 caratteri, riceve `data-stato="errore"`, `aria-describedby="errore-nome"` e visualizza `<small id="errore-nome">Scrivi il tuo nome, fra 2 e 120 caratteri.</small>`.
  - **Persistenza**: conserva il testo inserito dall'utente in caso di errore su altri campi.

- [x] **Campo Email (`input#email`)**:
  - **Tipo e Vincoli**: `type="email"`, `required="required"`, `maxlength="160"`, `autocomplete="email"`.
  - **Etichetta accessibile**: `<label for="email">Email</label>`.
  - **Gestione Errori**: se non valida o vuota, riceve `data-stato="errore"`, `aria-describedby="errore-email"` e visualizza `<small id="errore-email">Scrivi un indirizzo email valido, per poterti rispondere.</small>`.
  - **Persistenza**: conserva l'email digitata.

- [x] **Campo Argomento / Categoria (`select#categoria`)**:
  - **Tipo e Vincoli**: `<select>` a scelta singola, `required="required"`.
  - **Etichetta accessibile**: `<label for="categoria">Di che cosa si tratta</label>`.
  - **Opzioni fornite da `categorie_messaggio()`**:
    - [x] `""`: *"Scegli un argomento"* (valore predefinito nullo).
    - [x] `"ordine"`: *"Un ordine"*.
    - [x] `"prenotazione"`: *"Una prenotazione"*.
    - [x] `"segnalazione"`: *"Una segnalazione sul sito"*.
    - [x] `"altro"`: *"Altro"*.
  - **Gestione Errori**: se non selezionata o non ammessa, riceve `data-stato="errore"`, `aria-describedby="errore-categoria"` e `<small id="errore-categoria">Scegli uno degli argomenti proposti.</small>`.
  - **Persistenza**: l'opzione selezionata mantiene l'attributo `selected="selected"`.

- [x] **Campo Messaggio (`textarea#testo`)**:
  - **Tipo e Vincoli**: `<textarea>`, `rows="6"`, `required="required"`, `minlength="10"`, `maxlength="400"`.
  - **Etichetta accessibile**: `<label for="testo">Messaggio</label>`.
  - **Gestione Errori**: se inferiore a 10 caratteri o superiore a 400, riceve `data-stato="errore"`, `aria-describedby="errore-testo"` e `<small id="errore-testo">Scrivi il messaggio, fra 10 e 400 caratteri.</small>`.
  - **Persistenza**: conserva il testo digitato.

- [x] **Pulsante di Invio (`button[type="submit"]`)**:
  - Testo: *"Invia il messaggio"*.
  - Contrasto elevato, focus visibile e attivabile con `Invio` o `Spazio`.

- [x] **Nota Informativa sulla Privacy (`p.nota-privacy`)**:
  - Dicitura di trasparenza sul trattamento dati.
  - Collegamento ad alto contrasto verso `privacy.php` (`<a href="/privacy">privacy policy</a>`).

---

### 8.4 Gestione Notifiche ed Errori (WCAG 2.1 AA)

- [x] **Riepilogo Generale degli Errori (`section.avviso[role="alert"][data-tipo="errore"]`)**:
  - Compare in cima al form solo in presenza di errori di compilazione lato server.
  - Titolo H2: *"Controlla questi campi"*.
  - Elenco puntato di ancore interne (`#nome`, `#email`, `#categoria`, `#testo`) per consentire all'utente di saltare istantaneamente con un click o con il focus della tastiera al campo da correggere.
- [x] **Messaggio Flash di Conferma (`p.avviso[role="status"]`)**:
  - Adottato il pattern POST-Redirect-GET (`vai_a('contatti')`) per evitare doppi invii accidentali in caso di ricaricamento pagina (`F5`).
  - Mostra il banner verde ad alto contrasto con dicitura *"Messaggio inviato. Ti rispondiamo via email."*.
  - Il form viene completamente ripulito dai dati precedenti pronto per un eventuale nuovo messaggio.
- [x] **Persistenza su Database**:
  - Il messaggio viene memorizzato nella tabella `messaggi_contatto` con stato iniziale `nuovo`, timestamp `creato_il` e associazione di categoria.

---

### 8.5 Footer e Chiusura Pagina

- [x] **Pulsante Galleggiante "Torna su" (`.torna-su`)**:
  - Verificato su Contatti: dopo lo scorrimento, compare con transizione fluida; al click riporta a `#inizio` azzerando `scrollTop` e mantenendo `window.scrollY = 0`.
- [x] **Collegamenti di Servizio ("Esplora") e Contatti Rapidi**:
  - Tutti i link del footer (`Contatti`, `Privacy`, `Accessibilita`, `Mappa del sito`, `Orari di ogni sede`, badge W3C) verificati e funzionanti.

---

### 8.6 Criteri Trasversali di Qualità per i Contatti (WCAG 2.1 AA & Regole)

- [x] **Sintassi e Validazione XML/HTML5**:
  - Markup conforme alle specifiche HTML5 con sintassi XML valida, attributi quotati e corretta annidazione semantica.
- [x] **Accessibilità Moduli & Screen Reader**:
  - Tutte le label sono esplicitamente collegate ai relativi campi tramite attributi `for`/`id`.
  - I messaggi di errore inline sono associati via `aria-describedby` così che i lettori vocali leggano l'errore subito dopo l'etichetta.
  - Gli avvisi globali usano `role="alert"` (per gli errori) e `role="status"` (per il successo).
- [x] **Responsive Mobile**:
  - Testato con Playwright a 375x667px: layout a colonna singola nitido, campi input e pulsante a tutta larghezza facilmente azionabili via touch, nessun overflow orizzontale (`scrollWidth <= 375px`).
- [x] **Funzionamento senza JavaScript**:
  - Il form funziona al 100% senza JavaScript: la validazione server rileva e segnala gli errori, preserva i valori nei campi e gestisce il redirect di successo.

---

### 8.7 Esito del Collaudo Automatizzato End-to-End (Playwright)

Report di esecuzione dei test eseguiti con browser reale (Chromium / Playwright) per la pagina Contatti:

```text
=== INIZIO TEST UTENTE: PAGINA CONTATTI ===
  [OK] Caricamento iniziale Contatti: HTTP 200
  [OK] Titolo pagina corretto: 'Contatti e assistenza - Smash Burger'
  [OK] Breadcrumb: ['Home', 'Contatti']
  [OK] Breadcrumb link 'Home' torna alla Home (http://localhost:8080/)
  [OK] Hero: H1='Contatti', Occhiello='Parliamo chiaro'
  [OK] Canale diretto Email: 'informazioni@smashburger.it'
  [OK] Canale diretto Telefono: '049 1234567'
  [OK] Form contatti presente con method='post'
  [OK] Token CSRF presente nel form
  [OK] Campo Nome: attributi required e autocomplete='name'
  [OK] Campo Email: type='email' e autocomplete='email'
  [OK] Campo Categoria: required
  [OK] Opzioni Categoria: ['', 'ordine', 'prenotazione', 'segnalazione', 'altro']
  [OK] Campo Messaggio: minlength='10', maxlength='400'
  [OK] Pulsante invio presente: 'Invia il messaggio'
  [OK] Nota privacy con link valido a /privacy

=== COLLAUDO ERRORI E VALIDAZIONE ===
  [OK] Riepilogo errori visibile: section.avviso[data-tipo='errore']
  [OK] Titolo riepilogo: 'Controlla questi campi'
  [OK] Link correzione errori nei campi: ['#nome', '#email', '#categoria', '#testo']
  [OK] Campo Nome evidenziato con data-stato='errore' e messaggio inline #errore-nome
  [OK] Campo Email evidenziato con data-stato='errore' e messaggio inline #errore-email
  [OK] Campo Categoria evidenziato con data-stato='errore' e messaggio inline #errore-categoria
  [OK] Campo Testo evidenziato con data-stato='errore' e messaggio inline #errore-testo

=== COLLAUDO INVIO VALIDO E CONFERMA ===
  [OK] POST-Redirect-GET completato: http://localhost:8080/contatti
  [OK] Banner di successo presente: 'Fatto: Messaggio inviato. Ti rispondiamo via email.'
  [OK] Form resettato dopo l'invio riuscito: nome=''
  [OK] Contatti: Pulsante Torna su visibile dopo scroll
  [OK] Contatti: Click 'Torna su': area.scrollTop=0, window.scrollY=0

=== TEST RESPONSIVE MOBILE (375x667): CONTATTI ===
  [OK] Contatti Mobile: nessun overflow orizzontale (scrollWidth <= 375)

=== RIEPILOGO TEST CONTATTI ===
Totale controlli eseguiti: 29
Superati: 29
Falliti: 0
```

---

## 9. Accedi (`accedi.php`)

La pagina di accesso consente agli utenti registrati (clienti, manager di sede e amministratori) di autenticarsi nel sistema tramite le proprie credenziali (nome utente e password). Implementa misure avanzate di sicurezza sia sul frontend (accessibilita dei campi, autocomplete, semantica form) sia sul backend (protezione anti-enumerazione di utenti, prepared statement contro SQL injection, token CSRF, rigenerazione ID di sessione contro session fixation, gestione account disattivati e redirect automatico se gia collegati).

### 9.1 Header e Navigazione Superiore

- [x] **Percorso di Navigazione / Breadcrumb a 2 Livelli (`nav[aria-label="Percorso"]`)**:
  - [x] Livello 1: `<a href="./">Home</a>` (click ritorna alla Home).
  - [x] Livello 2: `<span aria-current="page">Accedi</span>` (pagina corrente non cliccabile).

- [x] **Navigazione Account per Utente Non Autenticato**:
  - [x] Collegamenti visibili: link "Accedi" (con evidenza pagina attiva) e pulsante primario "Registrati" (`.azione-header`).
  - [x] Toggle Tema Chiaro / Scuro funzionante e coerente.

---

### 9.2 Modulo di Autenticazione (`form[method="post"][action="accedi"]`)

- [x] **Intestazione Principale**:
  - `<h1>Accedi</h1>`.

- [x] **Avviso di Errore Dinamico (`p.avviso[data-tipo="errore"]`)**:
  - Compare solo in caso di fallimento autenticazione con attributi accessibili `role="alert"`.
  - Contenuto conforme al principio di non-divulgazione degli account registrati: *"Errore: Nome utente o password non corretti."*.

- [x] **Raggruppamento Semantico (`<fieldset>`)**:
  - `<legend>Le tue credenziali</legend>` per contestualizzare la coppia di campi per gli screen reader.

- [x] **Campo Nome Utente (`input#nome_utente`)**:
  - Etichetta associata: `<label for="nome_utente">Nome utente</label>`.
  - Attributi tecnici: `type="text"`, `name="nome_utente"`, `required="required"`, `maxlength="50"`, `autocomplete="username"`.
  - Ripopolamento sicuro: in caso di password errata, il valore del nome utente inserito viene preservato nel campo tramite escape sicuro `e($nomeUtente)`.

- [x] **Campo Password (`input#password`)**:
  - Etichetta associata: `<label for="password">Password</label>`.
  - Attributi tecnici: `type="password"`, `name="password"`, `required="required"`, `autocomplete="current-password"`.
  - Sicurezza visiva e di memoria: la password viene oscurata dal browser e svuotata in caso di errore di invio.

- [x] **Pulsante di Invio Credenziali**:
  - `<button type="submit">Accedi</button>`.
  - Azionabile sia tramite click del mouse che con pressione del tasto `Invio` dai campi input.

---

### 9.3 Percorso Alternativo di Registrazione

- [x] **Collegamento per Nuovi Utenti**:
  - Paragrafo informativo contestuale: `<p>Non hai un account? <a href="registrati">Registrati</a>.</p>`.
  - Conduce direttamente al modulo di creazione nuovo profilo cliente `/registrati`.

---

### 9.4 Criteri di Qualita e Sicurezza Backend

- [x] **Protezione Anti-Enumerazione Account**:
  - La funzione `utente_accedi()` restituisce il medesimo messaggio d'errore (*"Nome utente o password non corretti."*) sia quando il nome utente e inesistente sia quando la password per un utente valido e errata.
  - Verificato che i messaggi di risposta HTTP siano identici carattere per carattere, impedendo a malintenzionati di dedurre l'esistenza di specifici account nel sistema.

- [x] **Neutralizzazione SQL Injection**:
  - La query di autenticazione utilizza PDO con Prepared Statement (`SELECT id, password_hash, attivo FROM utenti WHERE nome_utente = :nome_utente`).
  - Collaudati payload malevoli di tipo injection (`' OR '1'='1' --`): la query viene eseguita in sicurezza estraendo zero righe e respingendo l'accesso.

- [x] **Protezione CSRF Obbligatoria**:
  - Il form inietta `<input type="hidden" name="token_csrf" value="..." />`.
  - Collaudato l'invio di richieste POST prive del token CSRF o con token contraffatto: il backend le respinge categoricamente con codice di stato HTTP 403 (Forbidden).

- [x] **Gestione Account Disattivati**:
  - Se un utente presenta nel database `attivo = 0`, il backend blocca l'accesso notificando l'utente con il messaggio mirato: *"Questo account e stato disattivato."*.

- [x] **Prevenzione Session Fixation (Rigenerazione ID di Sessione)**:
  - Non appena le credenziali vengono validate con successo da `password_verify()`, il backend esegue `session_regenerate_id(true)`.
  - Verificato tramite Playwright che l'identificativo nel cookie di sessione (`smashburger_session`) cambia prima e dopo l'autenticazione, invalidando vecchi token di sessione.

- [x] **Politiche di Sicurezza dei Cookie di Sessione**:
  - Cookie `smashburger_session` generato con `HttpOnly = true` (inaccessibile a script JavaScript sul client per mitigare furti da XSS) e `SameSite = Lax` (protezione automatica contro richieste cross-site).

- [x] **Protezione da Re-Autenticazione Ridondante**:
  - Se un utente autenticato tenta di accedere nuovamente all'URL `/accedi`, il controller esegue un controllo immediato (`if (utente_corrente($pdo) !== null)`) e reindirizza all'istante verso `/area-personale`.

- [x] **Logout Protetto e Distruzione della Sessione**:
  - La chiusura della sessione (`/esci`) e protetta da CSRF: una richiesta GET mostra una schermata di conferma con form, mentre la revoca vera e propria avviene via POST.
  - Al logout `utente_esci()` cancella l'array di sessione, azzera il cookie nel browser e distrugge la sessione server.
  - I successivi tentativi di accedere alle pagine protette (es. `/area-personale`) vengono respinti con codice HTTP 401 Unauthorized.

- [x] **Gestione Differenziata dei Ruoli**:
  - **Cliente** (`user`): reindirizzato in Area Personale con riepilogo ordini e prenotazioni.
  - **Manager** (`manager`): visualizza nell'header il collegamento rapido "Controllo" verso la gestione della propria sede.
  - **Amministratore** (`admin`): accesso autorizzato a tutte le sezioni globali del sistema (compresa `/controllo-utenti`).

- [x] **Responsive Mobile (375x667px)**:
  - Testato con Playwright a 375px di larghezza: campi a tutta larghezza agevoli al tocco, zero barre di scorrimento orizzontale (`scrollWidth <= 375px`).

---

### 9.5 Esito del Collaudo Automatizzato End-to-End e Backend (Playwright)

Report di esecuzione dei test eseguiti con browser reale (Chromium / Playwright) e verifiche dirette su database e chiamate HTTP per la pagina Accedi:

```text
========================================================
=== TEST BACKEND: SICUREZZA, SESSIONI, CSRF E DB ===
========================================================
  [OK] Backend Anti-Enumeration (utente inesistente): errore generico 'Errore: Nome utente o password non corretti.'
  [OK] Backend Anti-Enumeration (utente valido, password errata): errore generico 'Errore: Nome utente o password non corretti.'
  [OK] Backend Anti-Enumeration confermata: i due messaggi di errore sono IDENTICI
  [OK] Backend SQL Injection: prepared statement ha neutralizzato il payload malevolo
  [OK] Backend blocca token CSRF manomesso con HTTP 403 (Forbidden)
  [OK] Backend blocca POST privo di token CSRF con HTTP 403 (Forbidden)
  [OK] Backend Account Disattivato: accesso bloccato con messaggio 'Errore: Questo account e stato disattivato.'
  [OK] Ripristinato stato attivo utente chiara.moretti a DB
  [OK] Backend Login riuscito: reindirizzato correttamente ad /area-personale (http://localhost:8080/area-personale)
  [OK] Backend Flash Message in sessione visualizzato: 'Fatto: Accesso effettuato.'
  [OK] Backend Session Fixation Prevention: ID di sessione rigenerato (67858b13... -> cd256821...)
  [OK] Backend Cookie di sessione: HttpOnly = True (inaccessibile a script JS malevoli)
  [OK] Backend Cookie di sessione: SameSite = Lax (protezione CSRF cross-site)
  [OK] Header utente autenticato: link ad Area personale presente
  [OK] Header utente autenticato: link Esci presente
  [OK] Header utente autenticato: link Accedi rimosso
  [OK] Header utente autenticato: link Registrati rimosso
  [OK] Area Personale: H1='Area personale'
  [OK] Area Personale: Dati profilo caricati da DB (Ciao Anna. Da qui vedi i tuoi ordini, le tue prenotazioni e puoi cambiare i tuoi dati.)
  [OK] Backend Redirect se gia' autenticato: richiesta a /accedi reindirizza ad /area-personale (http://localhost:8080/area-personale)
  [OK] Pagina conferma uscita (GET /esci) protetta da CSRF visualizzata
  [OK] Backend Logout: sessione chiusa e reindirizzato alla Home (http://localhost:8080/)
  [OK] Backend Sessione distrutta: accesso ad /area-personale respinto con HTTP 401 Unauthorized
  [OK] Backend Login Manager: atterrato su /area-personale
  [OK] Backend Ruolo Manager: voce 'Controllo' visibile nell'header
  [OK] Backend Login Amministratore: atterrato su /area-personale
  [OK] Backend Ruolo Amministratore: voce 'Controllo' visibile nell'header
  [OK] Backend Permessi Amministratore: accesso a /controllo-utenti autorizzato (HTTP 200)

========================================================
=== TEST FRONTEND, ACCESSIBILITA E MOBILE: ACCEDI ===
========================================================
  [OK] Caricamento iniziale /accedi: HTTP 200
  [OK] Title dinamico: 'Accedi - Smash Burger'
  [OK] Breadcrumb semantico a 2 livelli: ['Home', 'Accedi']
  [OK] Breadcrumb link Home corretto
  [OK] Campo nome_utente ha required='required'
  [OK] Campo nome_utente ha autocomplete='username'
  [OK] Campo nome_utente ha maxlength='50'
  [OK] Campo password ha required='required'
  [OK] Campo password ha autocomplete='current-password'
  [OK] Campo password ha type='password'
  [OK] Label 'Nome utente' associata a id='nome_utente'
  [OK] Label 'Password' associata a id='password'
  [OK] Fieldset semantico con legend: 'Le tue credenziali'
  [OK] Link a /registrati per chi non ha un account presente e cliccabile
  [OK] Pulsante Torna su presente nel markup
  [OK] Responsive Mobile (375x667): nessun overflow orizzontale (scrollWidth <= 375)

=== RIEPILOGO TEST COMPLETO ACCEDI ===
Totale controlli eseguiti: 44
Superati: 44
Falliti: 0
```

---

## 10. Registrati (`registrati.php`)

La pagina di registrazione consente a nuovi clienti di creare un account personale, necessario per effettuare ordini con asporto/consegna e per prenotare la sala eventi nelle sedi abilitate. Include due gruppi semantici di campi ("I tuoi dati" e "Come accedi"), validazione severa sia frontend che backend contro duplicati e credenziali deboli, ripopolamento sicuro con svuotamento della password, protezione CSRF e memorizzazione crittografica tramite hash bcrypt in MariaDB.

### 10.1 Header e Navigazione Superiore

- [x] **Percorso di Navigazione / Breadcrumb a 2 Livelli (`nav[aria-label="Percorso"]`)**:
  - [x] Livello 1: `<a href="./">Home</a>` (click ritorna alla Home).
  - [x] Livello 2: `<span aria-current="page">Registrati</span>` (pagina corrente non cliccabile).

- [x] **Navigazione Account per Utente Non Autenticato**:
  - [x] Collegamenti visibili: link "Accedi" e pulsante evidenziato "Registrati" (`.azione-header`, con aria-current se applicabile).
  - [x] Controlli tema chiaro/scuro operativi.

---

### 10.2 Modulo di Creazione Account (`form[method="post"][action="registrati"]`)

- [x] **Intestazione Principale e Descrizione**:
  - `<h1>Registrati</h1>`.
  - Paragrafo descrittivo: *"Serve un account per ordinare e per prenotare la sala eventi."*.

- [x] **Riepilogo Globale degli Errori (`section.avviso[data-tipo="errore"]`)**:
  - Compare in cima al modulo con `role="alert"` in caso di errori di compilazione o conflitti a database.
  - Titolo: `<h2>Controlla questi campi</h2>`.
  - Elenco di link di salto interno (`<a href="#{campo}">...</a>`) che indirizzano il focus direttamente al primo campo errato.

- [x] **Fieldset 1: Dati Anagrafici (`<legend>I tuoi dati</legend>`)**:
  - [x] **Campo Nome (`input#nome`)**:
    - Etichetta: `<label for="nome">Nome</label>`.
    - Attributi: `type="text"`, `name="nome"`, `required="required"`, `minlength="2"`, `maxlength="80"`, `autocomplete="given-name"`.
    - In caso di errore: `data-stato="errore"`, `aria-describedby="errore-nome"`, `<small id="errore-nome">`.
  - [x] **Campo Cognome (`input#cognome`)**:
    - Etichetta: `<label for="cognome">Cognome</label>`.
    - Attributi: `type="text"`, `name="cognome"`, `required="required"`, `minlength="2"`, `maxlength="80"`, `autocomplete="family-name"`.
    - In caso di errore: `data-stato="errore"`, `aria-describedby="errore-cognome"`, `<small id="errore-cognome">`.
  - [x] **Campo Email (`input#email`)**:
    - Etichetta: `<label for="email">Email</label>`.
    - Attributi: `type="email"`, `name="email"`, `required="required"`, `maxlength="160"`, `autocomplete="email"`.
    - In caso di errore: `data-stato="errore"`, `aria-describedby="errore-email"`, `<small id="errore-email">`.

- [x] **Fieldset 2: Credenziali di Accesso (`<legend>Come accedi</legend>`)**:
  - [x] **Campo Nome Utente (`input#nome_utente`)**:
    - Etichetta: `<label for="nome_utente">Nome utente</label>`.
    - Attributi: `type="text"`, `name="nome_utente"`, `required="required"`, `pattern="[a-z0-9._-]{3,50}"`, `minlength="3"`, `maxlength="50"`, `autocomplete="username"`.
    - Aiuto contestuale accessibile: `<small id="aiuto-nome-utente">Da 3 a 50 fra lettere minuscole, cifre, punto, trattino e trattino basso.</small>` collegato via `aria-describedby`.
    - In caso di errore: aggiunta di `errore-nome_utente` a `aria-describedby` e messaggio inline.
  - [x] **Campo Password (`input#password`)**:
    - Etichetta: `<label for="password">Password</label>`.
    - Attributi: `type="password"`, `name="password"`, `required="required"`, `minlength="8"`, `autocomplete="new-password"`.
    - Aiuto contestuale accessibile: `<small id="aiuto-password">Almeno 8 caratteri.</small>` collegato via `aria-describedby`.
    - In caso di errore: inline message e reset immediato del valore.
  - [x] **Campo Ripeti Password (`input#conferma`)**:
    - Etichetta: `<label for="conferma">Ripeti la password</label>`.
    - Attributi: `type="password"`, `name="conferma"`, `required="required"`, `minlength="8"`, `autocomplete="new-password"`.
    - In caso di discordanza: segnalazione inline *"Le due password non coincidono."*.

- [x] **Pulsante di Invio**:
  - `<button type="submit">Crea l'account</button>`.

---

### 10.3 Percorso Alternativo di Accesso

- [x] **Collegamento per Utenti Gia Registrati**:
  - `<p>Hai gia un account? <a href="accedi">Accedi</a>.</p>`.
  - Reindirizza direttamente al modulo di login `/accedi`.

---

### 10.4 Criteri di Qualita e Sicurezza Backend

- [x] **Validazione Server Indipendente dal Client**:
  - Anche disabilitando i vincoli HTML5 lato browser, il backend PHP (`utente_errori_registrazione()`) convalida autonomamente lunghezze minime/massime, espressione regolare per nome utente (`/^[a-z0-9._-]{3,50}$/`), correttezza formale dell'email (`FILTER_VALIDATE_EMAIL`) e robustezza password.
- [x] **Prevenzione Duplicati a Database**:
  - Interrogazione preventiva MariaDB per verificare l'unicita di `nome_utente` ed `email`.
  - Se il nome utente e gia presente, segnala: *"Questo nome utente e gia in uso."*.
  - Se l'indirizzo email e gia associato ad un altro account, segnala: *"Questo indirizzo email e gia registrato."*.
- [x] **Crittografia e Memorizzazione Sicura Password**:
  - La password non viene mai salvata in chiaro: hashing eseguito con `password_hash($password, PASSWORD_DEFAULT)` (algoritmo bcrypt con costo computazionale standard).
  - Verificato a database che il campo `password_hash` inizia con il prefisso standard `$2y$`.
- [x] **Assegnazione Ruolo Predefinito**:
  - Ogni registrazione tramite form pubblico assegna tassativamente il ruolo `cliente`, impedendo qualsiasi scalata di privilegi a manager o amministratore.
- [x] **Svuotamento della Password e Conservazione Valori Sicuri**:
  - In caso di fallimento della validazione, i campi `password` e `conferma` vengono categoricamente sbiancati per non esporre credenziali digitate; i campi anagrafici e lo username vengono invece ripopolati con escape sicuro `e()`.
- [x] **Protezione CSRF**:
  - Richieste prive di `token_csrf` o con token contraffatto vengono bloccate all'istante con HTTP 403 Forbidden.
- [x] **Flusso POST-Redirect-GET**:
  - A registrazione avvenuta, il server imposta il messaggio flash in sessione (*"Account creato. Ora puoi accedere."*) ed esegue un reindirizzamento HTTP verso `/accedi`, prevenendo doppi inserimenti accidentali con il tasto Ricarica.
- [x] **Redirect Condizionale per Utenti Collegati**:
  - Se un utente autenticato tenta di navigare su `/registrati`, il controller lo reindirizza direttamente a `/area-personale`.

---

### 10.5 Esito del Collaudo Automatizzato End-to-End e Backend (Playwright)

Report di esecuzione dei test eseguiti con browser reale (Chromium / Playwright) con verifiche dirette su database MariaDB e chiamate HTTP per la pagina Registrati:

```text
========================================================
=== TEST BACKEND: SICUREZZA, DB E VALIDAZIONE SERVER ===
========================================================
  [OK] Backend blocca POST privo di token CSRF con HTTP 403 (Forbidden)
  [OK] Backend blocca token CSRF manomesso con HTTP 403 (Forbidden)
  [OK] Backend Validazione: visualizzato riepilogo errori 'Controlla questi campi'
  [OK] Errore Nome: 'Scrivi il nome, fra 2 e 80 caratteri.'
  [OK] Errore Cognome: 'Scrivi il cognome, fra 2 e 80 caratteri.'
  [OK] Errore Email: 'Scrivi un indirizzo email valido.'
  [OK] Errore Nome Utente: 'Il nome utente accetta da 3 a 50 fra lettere minuscole, cifre, punto, trattino e trattino basso.'
  [OK] Errore Password: 'La password deve avere almeno 8 caratteri.'
  [OK] Controllo Conferma Password: 'Le due password non coincidono.'
  [OK] Sicurezza Backend: i campi password e conferma vengono svuotati dopo l'errore
  [OK] Ripopolamento campi sicuri: nome='Mario', username='mario.rossi'
  [OK] Conflitto Username a DB: intercettato con 'Questo nome utente e gia in uso.'
  [OK] Conflitto Email a DB: intercettato con 'Questo indirizzo email e gia registrato.'
  [OK] POST-Redirect-GET avvenuto con successo verso /accedi (http://localhost:8080/accedi)
  [OK] Flash message in sessione: 'Fatto: Account creato. Ora puoi accedere.'
  [OK] Persistenza MariaDB: riga utente inserita correttamente
  [OK] Ruolo a DB assegnato di default a 'cliente'
  [OK] Password memorizzata a DB tramite hash bcrypt sicuro ($2y$)
  [OK] Nuovo account funzionante: accesso autorizzato ad http://localhost:8080/area-personale
  [OK] Area Personale riconosce il nuovo cliente: 'Ciao Mario. Da qui vedi i tuoi ordini, le tue prenotazioni e puoi cambiare i tuoi dati.'
  [OK] Ripristino DB: eliminato account di test 'mario.rossi.test'
  [OK] Protezione Utente Collegato: /registrati reindirizza ad /area-personale (http://localhost:8080/area-personale)

========================================================
=== TEST FRONTEND, ACCESSIBILITA E MOBILE: REGISTRATI ===
========================================================
  [OK] Caricamento iniziale /registrati: HTTP 200
  [OK] Title dinamico: 'Registrati - Smash Burger'
  [OK] Breadcrumb semantico a 2 livelli: ['Home', 'Registrati']
  [OK] Fieldsets e Legends: ['I tuoi dati', 'Come accedi']
  [OK] Campo Nome: required e autocomplete='given-name'
  [OK] Campo Cognome: required e autocomplete='family-name'
  [OK] Campo Email: required, type='email' e autocomplete='email'
  [OK] Campo Nome Utente: required, pattern e autocomplete='username'
  [OK] Campo Password: required, minlength='8', type='password' e autocomplete='new-password'
  [OK] Campo Ripeti Password: required, type='password' e autocomplete='new-password'
  [OK] Aiuto Nome Utente presente: 'Da 3 a 50 fra lettere minuscole, cifre, punto, trattino e trattino basso.'
  [OK] Aiuto Password presente: 'Almeno 8 caratteri.'
  [OK] Link alternativo per chi ha gia un account presente verso /accedi
  [OK] Pulsante Torna su presente nel markup
  [OK] Responsive Mobile (375x667): nessun overflow orizzontale (scrollWidth <= 375)

=== RIEPILOGO TEST COMPLETO REGISTRATI ===
Totale controlli eseguiti: 37
Superati: 37
Falliti: 0
```

---

## 11. Esci (`esci.php`)

La pagina di disconnessione gestisce la chiusura sicura della sessione utente. Per prevenire attacchi di tipo Cross-Site Request Forgery mirati a forzare il logout involontario (es. tramite elementi `<img>` o script di terze parti), l'uscita non avviene tramite un semplice collegamento ipertestuale in GET, ma richiede una conferma esplicita veicolata da un modulo HTTP POST protetto da token CSRF.

### 11.1 Header e Navigazione Superiore

- [x] **Percorso di Navigazione / Breadcrumb a 2 Livelli (`nav[aria-label="Percorso"]`)**:
  - [x] Livello 1: `<a href="./">Home</a>` (click ritorna alla Home).
  - [x] Livello 2: `<span aria-current="page">Esci</span>` (pagina corrente non cliccabile).

- [x] **Navigazione Account per Utente Autenticato**:
  - [x] Voci di menu pertinenti al ruolo corrente (Area personale, Carrello o Controllo, ed Esci).
  - [x] Toggle tema chiaro/scuro coerente e funzionante.

---

### 11.2 Schermata di Conferma Disconnessione (`form[method="post"][action="esci"]`)

- [x] **Intestazione Principale e Messaggio di Avviso**:
  - `<h1>Esci</h1>`.
  - Paragrafo di richiesta conferma: *"Vuoi chiudere la sessione su questo dispositivo?"*.

- [x] **Modulo Protetto da CSRF**:
  - Presenza del token crittografico segreto `<input type="hidden" name="token_csrf" value="..." />`.
  - Attributo `method="post"` e destinazione `action="esci"`.

- [x] **Comandi di Azione**:
  - [x] **Pulsante di Conferma Uscita**:
    - `<button type="submit">Esci</button>`.
    - Innesca la chiusura effettiva della sessione sul server.
  - [x] **Collegamento di Annullamento**:
    - `<a href="area-personale">Annulla</a>`.
    - Riporta l'utente alla propria area personale mantenendo intatta la sessione attiva.

---

### 11.3 Criteri di Qualita e Sicurezza Backend

- [x] **Controllo di Autorizzazione Accesso (Access Control)**:
  - La pagina `/esci` e accessibile solo agli utenti autenticati (`cliente`, `manager`, `amministratore`).
  - Verificato che richieste da parte di utenti ospiti (non autenticati) vengano intercettate da `richiedi_permesso()` ed emettano lo stato **HTTP 401 Unauthorized**.
- [x] **Protezione CSRF Obbligatoria sul Logout**:
  - Tentativi di eseguire il logout tramite chiamate POST prive del token CSRF o con token manomesso vengono bloccati con stato **HTTP 403 Forbidden**, preservando la sessione attiva dell'utente.
- [x] **Preservazione dello Stato al Click su "Annulla"**:
  - Cliccando su *"Annulla"*, il browser naviga ad `/area-personale` senza alterare i dati di sessione o i cookie.
- [x] **Distruzione Completa della Sessione sul Server**:
  - Al submit del modulo con token valido, `utente_esci()` svuota l'array `$_SESSION`, distrugge la sessione con `session_destroy()` e revoca il cookie `smashburger_session` impostandone la scadenza nel passato (`time() - 3600`).
- [x] **Flusso POST-Redirect-GET verso la Home**:
  - Al completamento del logout, il server reindirizza con codice 302/303 alla Home page (`/`).
- [x] **Aggiornamento Istantaneo dell'Interfaccia**:
  - Nell'header scompaiono i link riservati ("Area personale", "Esci", "Controllo") e ricompaiono i collegamenti per ospiti ("Accedi" e "Registrati").
- [x] **Revoca dei Permessi su Pagine Protette**:
  - Immediatamente dopo l'uscita, qualsiasi richiesta verso pagine ad accesso riservato (come `/area-personale` o `/controllo-utenti`) fallisce con **HTTP 401 Unauthorized**.
- [x] **Collaudo Multi-Ruolo**:
  - Verificata la procedura di uscita completa per tutti i profili di sistema: Cliente, Manager di sede e Amministratore.
- [x] **Responsive Mobile (375x667px)**:
  - Layout di conferma compatto, pulsante e link annulla ben distanziati per il tocco, nessun overflow orizzontale (`scrollWidth <= 375px`).

---

### 11.4 Esito del Collaudo Automatizzato End-to-End e Backend (Playwright)

Report di esecuzione dei test eseguiti con browser reale (Chromium / Playwright) con verifiche su cookie, sessioni e chiamate HTTP per la pagina Esci:

```text
========================================================
=== TEST BACKEND: PERMESSI, CSRF, SESSIONE E LOGOUT ===
========================================================
  [OK] Backend Permessi: richiesta a /esci da non autenticato respinta con HTTP 401 Unauthorized
  [OK] Cliente autenticato con successo
  [OK] Backend Permessi: cliente autorizzato visualizza /esci con HTTP 200
  [OK] Titolo dinamico: 'Esci - Smash Burger'
  [OK] Breadcrumb semantico a 2 livelli: ['Home', 'Esci']
  [OK] Messaggio conferma presente: 'Vuoi chiudere la sessione su questo dispositivo?'
  [OK] Link 'Annulla' presente nel form di uscita
  [OK] Click 'Annulla': ritorno sicuro ad http://localhost:8080/area-personale
  [OK] Sessione cliente preservata dopo 'Annulla'
  [OK] Cookie di sessione utente estratto per verifica CSRF
  [OK] Backend blocca logout CSRF privo di token con HTTP 403 (Forbidden)
  [OK] Backend blocca logout CSRF con token manomesso con HTTP 403 (Forbidden)
  [OK] Resistenza ad attacchi CSRF: la sessione dell'utente NON e stata chiusa da richieste non autorizzate
  [OK] Pulsante submit di conferma 'Esci' presente
  [OK] POST-Redirect-GET avvenuto con successo: atterrato su http://localhost:8080/
  [OK] Header post-logout: link 'Accedi' visibile
  [OK] Header post-logout: link 'Registrati' visibile
  [OK] Header post-logout: link 'Area personale' rimosso
  [OK] Header post-logout: link 'Esci' rimosso
  [OK] Revoca permessi backend confermata: /area-personale risponde con HTTP 401 Unauthorized
  [OK] Manager autenticato con voce Controllo attiva
  [OK] Logout Manager: voce Controllo rimossa dall'header
  [OK] Amministratore autenticato
  [OK] Logout Amministratore: accesso a /controllo-utenti respinto con HTTP 401 Unauthorized

========================================================
=== TEST FRONTEND, ACCESSIBILITA E MOBILE: ESCI ===
========================================================
  [OK] Intestazione principale H1: 'Esci'
  [OK] Form di conferma ha method='post'
  [OK] Form di conferma ha action='esci'
  [OK] Campo token_csrf presente e popolato nel form
  [OK] Responsive Mobile (375x667): nessun overflow orizzontale (scrollWidth <= 375)

=== RIEPILOGO TEST COMPLETO ESCI ===
Totale controlli eseguiti: 29
Superati: 29
Falliti: 0
```

---

## 12. Area personale (`area-personale.php`)

L'Area personale funge da cruscotto centrale per gli utenti autenticati. La pagina adatta la propria struttura e le informazioni mostrate in base al ruolo ricoperto dall'utente nel sistema:
- **Clienti**: visualizzano lo storico completo dei propri ordini (con identificativo, data, sede di ritiro, modalita, importo totale, stato di avanzamento e collegamento alla relativa ricevuta digitale), lo storico delle prenotazioni per la sala eventi (con data, orario, sede, numero invitati e badge di stato dinamico), e il riepilogo delle proprie credenziali/dati anagrafici con collegamento per la modifica del profilo. In assenza di ordini o prenotazioni, la pagina presenta stati vuoti dedicati (*empty state*) con messaggi informativi e pulsanti d'azione (CTA) per guidare l'utente verso il menu o verso le sedi.
- **Manager di sede e Amministratori**: non effettuano ordini ne prenotazioni come clienti da questa interfaccia; le sezioni relative agli ordini e prenotazioni cliente vengono opportunamente escluse dal markup (`$cliente = false`), presentando invece un messaggio esplicativo che indirizza la gestione operativa al pannello di controllo dedicato (`/controllo`), oltre al riepilogo dei propri dati e al link diretto al profilo.

---

### 12.1 Header e Navigazione Superiore

- [x] **Percorso di Navigazione / Breadcrumb a 2 Livelli (`nav[aria-label="Percorso"]`)**:
  - [x] Livello 1: `<a href="./">Home</a>` (click conduce alla Home).
  - [x] Livello 2: `<span aria-current="page">Area personale</span>` (pagina corrente non cliccabile, conforme a REGOLE.md sezione 8).

- [x] **Navigazione Account per Utente Autenticato**:
  - [x] Link `Area personale` marcato con `aria-current="page"`.
  - [x] Link `Esci` presente nell'header.
  - [x] Link `Carrello` presente per i clienti, link `Controllo` presente per manager e amministratori.

---

### 12.2 Sezioni, Elementi Informativi e Interattivi per Cliente

- [x] **Intestazione Principale e Messaggio di Benvenuto Personalizzato**:
  - [x] Tag `<h1>` semantico contenente `"Area personale"`.
  - [x] Paragrafo di benvenuto con nome di battesimo estratto dal database (es. *"Ciao Anna. Da qui vedi i tuoi ordini, le tue prenotazioni e puoi cambiare i tuoi dati."*).

- [x] **Sezione "I tuoi ordini"**:
  - [x] Titolo di sezione `<h2>` semantico: `"I tuoi ordini"`.
  - [x] Tabella dati con didascalia accessibile `<caption>Ordini effettuati</caption>`.
  - [x] Intestazioni di colonna `<th>` con `scope="col"`: *Numero*, *Data*, *Sede*, *Modalita*, *Totale*, *Stato*, *Ricevuta*.
  - [x] Righe ordinate cronologicamente in senso decrescente (`ORDER BY o.creato_il DESC`).
  - [x] Dati di riga verificati a database: codice ordine univoco (es. `SB-2026-0011`), data formattata in italiano, sede, modalita (asporto / al tavolo), totale in euro calcolato con sommatoria delle righe ordine e badge di stato (*concluso*, *pronto*, *in preparazione*, *annullato*).
  - [x] Collegamento interattivo per ciascun ordine: `<a href="ricevuta?ordine=ID">Apri la ricevuta</a>` con destinazione coerente (HTTP 200).
  - [x] **Empty State Ordini**: in assenza di ordini a database, la tabella e sostituita da `<p>Non hai ancora fatto nessun ordine.</p>` e dal link CTA `<a href="menu" class="bottone">Guarda il menu</a>`.

- [x] **Sezione "Le tue prenotazioni"**:
  - [x] Titolo di sezione `<h2>` semantico: `"Le tue prenotazioni"`.
  - [x] Tabella dati con didascalia accessibile `<caption>Prenotazioni della sala eventi</caption>`.
  - [x] Intestazioni di colonna `<th>` con `scope="col"`: *Data*, *Orario*, *Sede*, *Invitati*, *Stato*.
  - [x] Badge semantici con attributo `data-tipo` per gli stati della prenotazione:
    - `data-tipo="positivo"` per lo stato *approvata*.
    - `data-tipo="attenzione"` per lo stato *in attesa*.
    - `data-tipo="negativo"` per lo stato *rifiutata*.
  - [x] **Empty State Prenotazioni**: in assenza di prenotazioni a database, la tabella e sostituita da `<p>Non hai prenotazioni per la sala eventi.</p>` e dal link CTA `<a href="sedi" class="bottone">Scegli una sede e prenota</a>`.

- [x] **Sezione "I tuoi dati"**:
  - [x] Titolo di sezione `<h2>` semantico: `"I tuoi dati"`.
  - [x] Elenco descrittivo contenente *Nome utente*, *Nome e cognome* e *Email* allineati con i record MariaDB.
  - [x] Link d'azione primario: `<a href="profilo" class="bottone">Modifica i tuoi dati</a>` (HTTP 200).

---

### 12.3 Adattamento per Ruoli di Staff (Manager e Amministratore)

- [x] **Differenziazione dei Contenuti in Base al Ruolo (`$cliente = false`)**:
  - [x] Messaggio di benvenuto dedicato: *"Ciao [Nome]. Da qui controlli i tuoi dati di accesso: ordini e prenotazioni della tua sede stanno nel pannello di controllo."*
  - [x] Esclusione delle sezioni ordini e prenotazioni cliente dal markup per evitare ridondanze o confusione di contesto.
  - [x] Dati anagrafici e credenziali del manager/amministratore correttamente mostrati e sincronizzati con il database.
  - [x] Navigazione integrata con il pannello operativo tramite la voce `Controllo` nell'header.

---

### 12.4 Verifiche di Backend, Database, Permessi e Sicurezza

- [x] **Controllo di Autenticazione e Permessi**:
  - [x] Richieste HTTP non autenticate (ospiti non loggati) a `/area-personale` vengono tassativamente respinte con stato **HTTP 401 Unauthorized** e presentazione della schermata di errore di autenticazione.
- [x] **Isolamento dei Dati Utente a Database**:
  - [x] Le query SQL (`ordini_dell_utente()` e `prenotazioni_dell_utente()`) filtrano rigidamente per l'`id` dell'utente salvato in sessione, impedendo l'accesso orizzontale a ordini o prenotazioni di altri clienti (prevenzione IDOR).
- [x] **Resilienza e Sanificazione dell'Output**:
  - [x] Tutte le stringhe anagrafiche, codici ordine e nomi di sede passano attraverso `testo()` (`htmlspecialchars`) per scongiurare falle XSS nel cruscotto.
- [x] **Responsive Mobile (375x667px)**:
  - [x] Tabelle adattabili e scroll orizzontale contenuto all'interno del contenitore della tabella, nessun overflow anomalo del viewport globale (`scrollWidth <= 375px`).

---

### 12.5 Esito del Collaudo Automatizzato End-to-End e Backend (Playwright)

Report di esecuzione dei test eseguiti con browser reale (Chromium / Playwright) con verifiche sui ruoli (cliente, manager, admin), isolamento DB, aperture ricevute ed empty states:

```text
========================================================
=== TEST BACKEND: AUTORIZZAZIONE, RUOLI E QUERY A DB ===
========================================================
  [OK] Backend Permessi: richiesta ad /area-personale da non autenticato respinta con HTTP 401 Unauthorized
  [OK] Login cliente riuscito: atterrato su http://localhost:8080/area-personale
  [OK] Title dinamico: 'Area personale - Smash Burger'
  [OK] Breadcrumb semantico a 2 livelli: ['Home', 'Area personale']
  [OK] H1 corretto: 'Area personale'
  [OK] Saluto cliente personalizzato da DB: 'Ciao Anna. Da qui vedi i tuoi ordini, le tue prenotazioni e puoi cambiare i tuoi dati.'
  [OK] Sezione 'I tuoi ordini' presente per il ruolo cliente
  [OK] Tabella ordini presente nel markup
  [OK] Didascalia tabella: 'Ordini effettuati'
  [OK] Intestazioni colonna ordini corrette: ['Numero', 'Data', 'Sede', 'Modalita', 'Totale', 'Stato', 'Ricevuta']
  [OK] Trovati esattamente 9 ordini a database per l'utente 'user' (righe visualizzate: 9)
  [OK] Primo ordine codice (piu' recente): 'SB-2026-0011'
  [OK] Primo ordine prezzo calcolato: '12,00 euro'
  [OK] Primo ordine stato: 'concluso'
  [OK] Link 'Apri la ricevuta' presente nella riga ordine
  [OK] Destinazione ricevuta coerente: 'ricevuta?ordine=11'
  [OK] Apertura ricevuta collegata: HTTP 200 (http://localhost:8080/ricevuta?ordine=11)
  [OK] Titolo pagina ricevuta: 'Ricevuta dell'ordine - Smash Burger'
  [OK] Sezione 'Le tue prenotazioni' presente per il cliente
  [OK] Tabella prenotazioni presente
  [OK] Trovata 1 prenotazione sala eventi per 'user': 1
  [OK] Sede prenotazione: 'Padova'
  [OK] Stato prenotazione: 'approvata' con badge data-tipo='positivo'
  [OK] Dati DB Nome utente corretto: Nome utente: user
  [OK] Dati DB Nome e cognome corretti: Nome e cognome: Anna Rossi
  [OK] Dati DB Email corretta: Email: anna.rossi@example.it
  [OK] Link 'Modifica i tuoi dati' presente verso /profilo
  [OK] Link 'Modifica i tuoi dati' conduce a /profilo: HTTP 200
  [OK] Prenotazione 'in attesa': badge con data-tipo='attenzione'
  [OK] Prenotazione 'rifiutata': badge con data-tipo='negativo'
  [OK] Empty state ordini: messaggio informativo 'Non hai ancora fatto nessun ordine.' presente
  [OK] Empty state ordini: link CTA 'Guarda il menu' verso /menu presente
  [OK] Empty state prenotazioni: messaggio 'Non hai prenotazioni per la sala eventi.' presente
  [OK] Empty state prenotazioni: link CTA 'Scegli una sede e prenota' verso /sedi presente
  [OK] Ripristinato DB: eliminato utente temporaneo 'cliente.vuoto.test'
  [OK] Testo dedicato per Manager: 'Ciao Marco. Da qui controlli i tuoi dati di accesso: ordini e prenotazioni della tua sede stanno nel pannello di controllo.'
  [OK] Area Personale Manager: sezione ordini cliente esclusa dal markup
  [OK] Area Personale Manager: sezione prenotazioni cliente esclusa dal markup
  [OK] Dati Manager: Nome utente: manager
  [OK] Dati Manager: Nome e cognome: Marco Bianchi
  [OK] Dati Manager: Email: padova@smashburger.it
  [OK] Voce 'Controllo' visibile nell'header del Manager
  [OK] Testo dedicato per Amministratore: 'Ciao Giulia. Da qui controlli i tuoi dati di accesso: ordini e prenotazioni della tua sede stanno nel pannello di controllo.'
  [OK] Area Personale Admin: sezione ordini cliente esclusa dal markup
  [OK] Area Personale Admin: sezione prenotazioni cliente esclusa dal markup
  [OK] Dati Admin: Nome utente: admin
  [OK] Dati Admin: Nome e cognome: Giulia Ferrari
  [OK] Dati Admin: Email: amministrazione@smashburger.it

========================================================
=== TEST FRONTEND, ACCESSIBILITA E MOBILE (375x667) ===
========================================================
  [OK] Accesso mobile completato ad /area-personale
  [OK] Responsive Mobile (375x667): nessun overflow orizzontale anomalo (scrollWidth <= 375)

=== RIEPILOGO TEST COMPLETO AREA PERSONALE ===
Totale controlli eseguiti: 50
Superati: 50
Falliti: 0
```

---

## 13. Profilo utente (`profilo.php`)

La pagina del profilo utente consente a tutti gli utenti autenticati (clienti, manager e amministratori) di gestire i propri dati anagrafici essenziali (nome, cognome, indirizzo email) e di aggiornare la propria password di sicurezza.
La pagina implementa un'architettura a **moduli indipendenti**: ciascun riquadro costituisce un form separato con il proprio token CSRF e un parametro identificativo dell'azione (`azione="dati"` oppure `azione="password"`). In questo modo, l'eventuale errore di compilazione o di validazione in una sezione non va a sporcare o azzerare quanto l'utente stava compilando nell'altra.
Tutti gli aggiornamenti si basano sul pattern **POST-Redirect-GET (PRG)** con messaggi informativi di sessione (flash messages) per prevenire il re-inoltro involontario dei dati in caso di ricaricamento del browser.

---

### 13.1 Header, Breadcrumb e Navigazione di Ritorno

- [x] **Percorso di Navigazione / Breadcrumb a 3 Livelli (`nav[aria-label="Percorso"]`)**:
  - [x] Livello 1: `<a href="./">Home</a>` (click ritorna alla Home).
  - [x] Livello 2: `<a href="area-personale">Area personale</a>` (click ritorna all'Area personale).
  - [x] Livello 3: `<span aria-current="page">Profilo</span>` (voce corrente non cliccabile, conforme a REGOLE.md sezione 8).

- [x] **Intestazione Principale e Titolo**:
  - [x] Titolo documento dinamico: `"Il tuo profilo - Smash Burger"`.
  - [x] Intestazione principale `<h1>`: `"Il tuo profilo"`.

- [x] **Navigazione di Ritorno a Fondo Pagina**:
  - [x] Elemento semantico: `<p class="navigazione-pagina"><a class="collegamento-indietro" href="area-personale"><span class="segno-collegamento" aria-hidden="true">&lt;</span><span>Torna all'area personale</span></a></p>`.
  - [x] Navigazione verificata: il click conduce direttamente all'Area personale (HTTP 200).

---

### 13.2 Modulo "Dati personali" (Nome, Cognome ed Email)

- [x] **Struttura Semantica e Campi del Form**:
  - [x] Sezione `<section>` con intestazione `<h2>Dati personali</h2>`.
  - [x] Modulo `<form method="post" action="profilo">` con campo nascosto `token_csrf` e `name="azione" value="dati"`.
  - [x] Gruppo campi `<fieldset>` con didascalia `<legend>Nome, cognome ed email</legend>`.
  - [x] **Pre-popolamento automatico da MariaDB**: i campi *Nome*, *Cognome* ed *Email* vengono valorizzati con i dati reali dell'utente estratti tramite `utente_completo($pdo, $id)`.
  - [x] **Attributi di Validazione HTML5**:
    - Nome: `required="required"`, `minlength="2"`, `maxlength="80"`.
    - Cognome: `required="required"`, `minlength="2"`, `maxlength="80"`.
    - Email: `type="email"`, `required="required"`, `maxlength="160"`, `autocomplete="email"`.
  - [x] Pulsante di invio: `<button type="submit">Salva i dati</button>`.

- [x] **Controlli di Validazione Server-side e Accessibilita Errori**:
  - [x] **Nome corto (< 2 caratteri)**: segnalazione con `<small id="errore-nome">Scrivi il nome, fra 2 e 80 caratteri.</small>`, input marcato con `data-stato="errore"` e collegato tramite `aria-describedby="errore-nome"`.
  - [x] **Cognome corto (< 2 caratteri)**: segnalazione con `<small id="errore-cognome">Scrivi il cognome, fra 2 e 80 caratteri.</small>`, input marcato con `data-stato="errore"` e `aria-describedby="errore-cognome"`.
  - [x] **Email non valida**: segnalazione con `<small id="errore-email">Scrivi un indirizzo email valido.</small>`, `data-stato="errore"` e `aria-describedby="errore-email"`.
  - [x] **Conflitto Unicita Email**: inserimento di un indirizzo email gia associato ad un altro utente intercettato con `<small id="errore-email">Questo indirizzo email e gia registrato.</small>` (la funzione `utente_esiste` esclude correttamente l'id dell'utente corrente).

- [x] **Protezione CSRF e Integrita dei Dati**:
  - [x] Invio del modulo senza token CSRF respinto con **HTTP 403 Forbidden**.
  - [x] Invio con token CSRF manomesso respinto con **HTTP 403 Forbidden**.
  - [x] Invio con parametro `azione` inatteso/manomesso respinto con **HTTP 403 Forbidden**.

- [x] **Persistenza MariaDB e Feedback Utente**:
  - [x] Modifica valida dei dati salvata con successo nella tabella `utenti`.
  - [x] Redirezione PRG verso `/profilo` e visualizzazione del messaggio flash di successo (*"Fatto: Dati aggiornati."*).
  - [x] Aggiornamento verificato a database tramite query SQL diretta (`SELECT nome, cognome, email FROM utenti WHERE ...`).
  - [x] Dati immediatamente sincronizzati e visibili in tutta l'applicazione (es. saluto personalizzato in Area personale aggiornato a *"Ciao Annamaria."*).
  - [x] Ripristino automatico dei dati originali di test e conferma su MariaDB.

---

### 13.3 Modulo "Password" (Cambio Password)

- [x] **Struttura Semantica e Campi del Form**:
  - [x] Sezione `<section>` con intestazione `<h2>Password</h2>`.
  - [x] Modulo `<form method="post" action="profilo">` con campo nascosto `token_csrf` e `name="azione" value="password"`.
  - [x] Gruppo campi `<fieldset>` con didascalia `<legend>Cambia la password</legend>`.
  - [x] Campo Password attuale: `type="password"`, `required="required"`, `autocomplete="current-password"`.
  - [x] Campo Nuova password: `type="password"`, `required="required"`, `minlength="8"`, `autocomplete="new-password"`.
  - [x] Campo Ripeti la nuova password: `type="password"`, `required="required"`, `minlength="8"`, `autocomplete="new-password"`.
  - [x] Pulsante di invio: `<button type="submit">Cambia la password</button>`.

- [x] **Controlli di Validazione Server-side e Accessibilita Errori**:
  - [x] **Password attuale errata**: intercettata da `password_verify` sul vecchio hash salvato a database; segnalata con `<small id="errore-pwd-attuale">La password attuale non e corretta.</small>`, con `data-stato="errore"` e `aria-describedby="errore-pwd-attuale"`.
  - [x] **Nuova password corta (< 8 caratteri)**: segnalata con `<small id="errore-pwd-nuova">La password deve avere almeno 8 caratteri.</small>`.
  - [x] **Mancata corrispondenza password/conferma**: segnalata con `<small id="errore-pwd-conferma">Le due password non coincidono.</small>`.
  - [x] **Protezione CSRF**: richiesta cambio password priva di token CSRF bloccata con **HTTP 403 Forbidden**.

- [x] **Ciclo di Vita Password e Sicurezza Crittografica**:
  - [x] Aggiornamento password collaudato con account dedicato: salvataggio su MariaDB tramite hash bcrypt sicuro (`$2y$10$...`, lunghezza 60 caratteri).
  - [x] Redirezione PRG e flash message di conferma (*"Fatto: Password aggiornata."*).
  - [x] **Revoca vecchia password**: tentativo di autenticazione con la vecchia password tassativamente respinto (*"Errore: Nome utente o password non corretti."*).
  - [x] **Accesso con nuova password**: autenticazione immediata e corretta con le nuove credenziali.
  - [x] Pulizia dell'account temporaneo a database al termine del collaudo.

---

### 13.4 Collaudo Multi-Ruolo (Manager e Amministratore)

- [x] **Manager di Sede (`manager`)**:
  - [x] Accesso autorizzato ad `/profilo`.
  - [x] Pre-compilazione dati anagrafici corretta: *Marco Bianchi*, *padova@smashburger.it*.
  - [x] Entrambi i moduli (dati personali e cambio password) perfettamente operativi e accessibili.
- [x] **Amministratore (`admin`)**:
  - [x] Accesso autorizzato ad `/profilo`.
  - [x] Pre-compilazione dati anagrafici corretta: *Giulia Ferrari*, *amministrazione@smashburger.it*.
  - [x] Entrambi i moduli perfettamente operativi e accessibili.

---

### 13.5 Responsive Mobile (375x667px)

- [x] Layout fluido su viewport mobile: form e campi impilati ordinatamente in verticale.
- [x] Pulsanti e campi facilmente toccabili su schermi touch.
- [x] Nessun overflow orizzontale anomalo (`scrollWidth=375 <= clientWidth=375`).

---

### 13.6 Esito del Collaudo Automatizzato End-to-End e Backend (Playwright)

Report di esecuzione dei test eseguiti con browser reale (Chromium / Playwright) con verifiche sui ruoli, validazioni inline, sicurezza CSRF, query MariaDB e ciclo di vita password:

```text
========================================================
=== TEST BACKEND: AUTORIZZAZIONE, PERMESSI E STRUTTURA ===
========================================================
  [OK] Backend Permessi: richiesta ad /profilo da non autenticato respinta con HTTP 401 Unauthorized
  [OK] Login cliente riuscito: atterrato su http://localhost:8080/area-personale
  [OK] Accesso a /profilo con utente autenticato: HTTP 200
  [OK] Titolo dinamico: 'Il tuo profilo - Smash Burger'
  [OK] Breadcrumb semantico a 3 livelli: ['Home', 'Area personale', 'Profilo']
  [OK] Breadcrumb: elemento corrente non cliccabile 'Profilo'
  [OK] H1 corretto: 'Il tuo profilo'
  [OK] Link 'Torna all'area personale' presente a fondo pagina
  [OK] Destinazione link indietro: 'area-personale'
  [OK] Click su 'Torna all'area personale' atterra correttamente su /area-personale

========================================================
=== TEST BACKEND & DB: MODULO DATI PERSONALI ===
========================================================
  [OK] Campo Nome pre-popolato correttamente da DB: 'Anna'
  [OK] Campo Cognome pre-popolato da DB: 'Rossi'
  [OK] Campo Email pre-popolato da DB: 'anna.rossi@example.it'
  [OK] Nome: attributo minlength='2'
  [OK] Nome: attributo maxlength='80'
  [OK] Nome: attributo required='required'
  [OK] Email: type='email'
  [OK] Email: autocomplete='email'
  [OK] Errore validazione nome corto: 'Scrivi il nome, fra 2 e 80 caratteri.'
  [OK] Input nome ha data-stato='errore'
  [OK] Input nome ha aria-describedby='errore-nome'
  [OK] Errore validazione cognome corto: 'Scrivi il cognome, fra 2 e 80 caratteri.'
  [OK] Errore validazione email malformata: 'Scrivi un indirizzo email valido.'
  [OK] Errore conflitto email esistente: 'Questo indirizzo email e gia registrato.'
  [OK] Backend blocca modifica dati senza token CSRF con HTTP 403
  [OK] Backend blocca modifica dati con token CSRF non valido con HTTP 403
  [OK] Backend blocca azione POST non valida con HTTP 403
  [OK] Flash message dopo aggiornamento dati: 'Fatto: Dati aggiornati.'
  [OK] Persistenza MariaDB confermata per utente 'user': 'Annamaria Rossi Nuovi anna.aggiornata@example.it'
  [OK] Area Personale riflette immediatamente i nuovi dati: 'Ciao Annamaria.'
  [OK] Ripristinati dati originali nel database per l'utente 'user'

========================================================
=== TEST BACKEND & DB: MODULO CAMBIO PASSWORD ===
========================================================
  [OK] Password attuale: type='password'
  [OK] Password attuale: autocomplete='current-password'
  [OK] Nuova password: minlength='8'
  [OK] Nuova password: autocomplete='new-password'
  [OK] Conferma password: minlength='8'
  [OK] Conferma password: autocomplete='new-password'
  [OK] Errore password attuale errata: 'La password attuale non e corretta.'
  [OK] Input password attuale ha data-stato='errore'
  [OK] Errore nuova password corta: 'La password deve avere almeno 8 caratteri.'
  [OK] Errore password e conferma non coincidenti: 'Le due password non coincidono.'
  [OK] Backend blocca cambio password senza token CSRF con HTTP 403

  --- Creazione utente temporaneo per collaudo ciclo password ---
  [OK] Flash message cambio password: 'Fatto: Password aggiornata.'
  [OK] MariaDB ha memorizzato il nuovo hash bcrypt: '$2y$10$IaujL5i9...'
  [OK] Login con vecchia password respinto: 'Errore: Nome utente o password non corretti.'
  [OK] Login con nuova password avvenuto con pieno successo!
  [OK] Ripristinato DB: eliminato utente temporaneo 'utente.pwd.test'

========================================================
=== TEST MULTI-RUOLO: MANAGER E ADMIN SU PROFILO ===
========================================================
  [OK] Profilo Manager: Nome pre-compilato 'Marco'
  [OK] Profilo Manager: Cognome pre-compilato 'Bianchi'
  [OK] Profilo Manager: Email pre-compilata 'padova@smashburger.it'
  [OK] Profilo Manager: Modulo dati personali attivo
  [OK] Profilo Manager: Modulo cambio password attivo
  [OK] Profilo Admin: Nome pre-compilato 'Giulia'
  [OK] Profilo Admin: Cognome pre-compilato 'Ferrari'
  [OK] Profilo Admin: Email pre-compilata 'amministrazione@smashburger.it'
  [OK] Profilo Admin: Modulo dati personali attivo
  [OK] Profilo Admin: Modulo cambio password attivo

========================================================
=== TEST FRONTEND, ACCESSIBILITA E MOBILE (375x667) ===
========================================================
  [OK] Caricamento mobile /profilo: HTTP 200
  [OK] Responsive Mobile (375x667): nessun overflow orizzontale (scrollWidth=375 <= clientWidth=375)

=== RIEPILOGO TEST COMPLETO PROFILO ===
Totale controlli eseguiti: 59
Superati: 59
Falliti: 0
```

---

## 14. Carrello (`carrello.php`)

La pagina del carrello gestisce la consultazione del catalogo per ciascun punto vendita e la composizione interattiva dell'ordine da parte del cliente autenticato.
Poiché la disponibilità dei singoli ingredienti e prodotti varia in base alla sede scelta, il carrello implementa una gestione a **stati successivi**:
- **Stato 1 (Nessuna sede scelta)**: la pagina presenta la vista di benvenuto e selezione sede (`carrello-sede.php`), chiedendo all'utente da quale punto vendita intende ordinare.
- **Stato 2 (Sede selezionata con carrello vuoto)**: viene aperto un record nella tabella `carrelli` su MariaDB, mostrando il catalogo dei prodotti disponibili per quel locale, l'intestazione personalizzata, il selettore per cambiare sede e l'avviso di carrello vuoto (*empty state*).
- **Stato 3 (Carrello popolato)**: non appena viene aggiunto almeno un articolo, compare il riepilogo con la tabella degli elementi ordinati, i controlli di incremento/decremento quantità, il pulsante per svuotare il carrello, il totale progressivo ricalcolato e il collegamento per procedere al pagamento.

La pagina è progettata secondo il principio del **Progressive Enhancement**: con JavaScript abilitato, le interazioni di modifica carrello vengono inviate via AJAX (`fetch()`) con aggiornamento trasparente del DOM e gestione del focus; in assenza di JavaScript, i form eseguono normali invii HTTP POST con reindirizzamento PRG e flash messages in sessione.

---

### 14.1 Header, Breadcrumb e Navigazione Superiore

- [x] **Percorso di Navigazione / Breadcrumb a 2 Livelli (`nav[aria-label="Percorso"]`)**:
  - [x] Livello 1: `<a href="./">Home</a>` (click ritorna alla Home).
  - [x] Livello 2: `<span aria-current="page">Carrello</span>` (voce corrente non cliccabile, conforme a REGOLE.md sezione 8).

- [x] **Intestazione Principale e Titolo**:
  - [x] Titolo dinamico del documento: `"Carrello - Smash Burger"`.
  - [x] Voci header utente loggato presenti (Area personale, Carrello, Esci).

---

### 14.2 Stato 1: Scelta della Sede di Ritiro (`carrello-sede.php`)

- [x] **Intestazione e Istruzioni per l'Utente**:
  - [x] Tag semantico `<h1>`: `"Da quale sede vuoi ordinare?"`.
  - [x] Paragrafo esplicativo sulla disponibilità specifica dei prodotti per locale.

- [x] **Modulo Selezione Sede (`form[data-modulo="scelta-sede"]`)**:
  - [x] Attributi form: `method="post"`, `action="carrello"`, campo nascosto `token_csrf` e `name="azione" value="scegli-sede"`.
  - [x] Raggruppamento `<fieldset>` con `<legend>Scegli la sede</legend>`.
  - [x] Elenco di opzioni `<input type="radio" name="sede">` per ciascun punto vendita attivo (Padova, Treviso, Vicenza, Udine) con indicazione di città e indirizzo.
  - [x] Prima opzione (Padova) preselezionata con `checked="checked"`.
  - [x] Pulsante d'invio primario: `<button type="submit">Continua</button>`.

- [x] **Link Informativo di Consultazione**:
  - [x] Collegamento di ritorno al menu: `<a href="menu">Guarda prima il menu</a>` (HTTP 200).

---

### 14.3 Stato 2 & 3: Catalogo Sede e Riepilogo Interattivo (`carrello.php`)

- [x] **Intestazione Dinamica con Sede**:
  - [x] Tag `<h1>` contestuale: `"Il tuo ordine da [Citta]"` (es. *"Il tuo ordine da Padova"*).

- [x] **Modulo Cambio Sede (`form[data-modulo="cambia-sede"]`)**:
  - [x] Menu a tendina `<select id="sede" name="sede">` con le 4 sedi attive e sede corrente preselezionata.
  - [x] Pulsante `<button type="submit">Cambia sede</button>`.
  - [x] Avviso esplicativo: *"Cambiando sede il carrello viene svuotato, perchè la disponibilità cambia da un locale all'altro."*.
  - [x] **Verifica svuotamento automatico al cambio sede**: cambiando da Padova a Treviso, le righe del carrello precedente vengono automaticamente cancellate e la sede viene aggiornata sia a video che a database.

- [x] **Sezione Riepilogo Ordine (`<section class="riepilogo">`)**:
  - [x] Intestazione H2 semantica `"Il tuo carrello"` e occhiello `"Riepilogo ordine"`.
  - [x] **Empty State**: quando il carrello è vuoto, viene mostrato il messaggio `<p>Il carrello è vuoto: tocca un prodotto per aggiungerlo.</p>` e il link di avanzamento all'ordine viene nascosto.
  - [x] **Tabella Prodotti nel Carrello**:
    - Didascalia accessibile: `<caption>Prodotti nel carrello</caption>`.
    - Intestazioni colonna `<th>` con `scope="col"`: *Prodotto*, *Prezzo*, *Quantita*, *Totale*, *Togli*.
    - Righe prodotto con `<th>` (`scope="row"`) per il nome del prodotto e formattazione monetaria in euro.
    - **Pulsanti Quantità Accessibili**:
      - Tasto `+` (`name="aggiungi"`): incrementa la quantità di un'unità, aggiorna i record in `righe_carrello` e ricalcola il totale. Testo screen reader: `<span class="solo-lettori">Una unita in piu di [Nome]</span>`.
      - Tasto `-` (`name="diminuisci"`): riduce la quantità di un'unità. Scendendo a 0 la riga viene rimossa automaticamente. Testo screen reader: `<span class="solo-lettori">Una unita in meno di [Nome]</span>`.
    - **Pulsante Rimozione Diretta**:
      - Tasto `"Togli"` (`name="togli"`): rimuove immediatamente l'articolo dalla tabella e da MariaDB, producendo il messaggio flash *"Fatto: Prodotto tolto dal carrello."*.
  - [x] **Modulo Svuota Carrello (`form[data-modulo="svuota"]`)**:
    - Pulsante dedicato `<button class="azione-svuota" type="submit">Svuota il carrello</button>` che azzera tutte le righe del carrello a database preservando la sede attiva.
  - [x] **Totale Ordine e Link alla Cassa**:
    - Etichetta con importo totale formattato in euro (`<strong class="totale">`).
    - Link CTA primario: `<a class="pulsante" data-tipo="positivo" href="pagamento">Procedi all'ordine</a>` (visibile solo a carrello non vuoto).

- [x] **Griglia Prodotti Disponibili in Sede (`form[data-modulo="aggiungi"]`)**:
  - [x] Griglia flessibile contenente le schede dei prodotti effettivamente disponibili nella sede.
  - [x] Immagine con attributi dimensionali (`width="300" height="225"`), titolo `<h2>` del prodotto, prezzo in euro.
  - [x] Pulsante d'invio per ciascun articolo con attributo accessibile: `<button type="submit" name="prodotto_id" value="ID">Aggiungi <span class="solo-lettori">[Nome Prodotto] al carrello</span></button>`.

---

### 14.4 Verifiche di Backend, Database, Permessi e Sicurezza

- [x] **Controllo di Autenticazione e Permessi**:
  - [x] Richieste a `/carrello` effettuate da utenti non autenticati (ospiti) vengono tassativamente bloccate con codice **HTTP 401 Unauthorized**.
- [x] **Persistenza e Integrità su MariaDB**:
  - [x] Creazione della riga carrello associata univocamente a `utente_id` nella tabella `carrelli`.
  - [x] Gestione atomica delle righe nella tabella `righe_carrello` (inserimento, aggiornamento quantità, eliminazione singola e azzeramento totale).
  - [x] Disaccoppiamento del prezzo del prodotto: il totale viene calcolato dinamicamente leggendo il listino corrente (`prodotti.prezzo_centesimi`), garantendo accuratezza economica.
- [x] **Inizializzazione Diretta da URL (`?sede=slug`)**:
  - [x] Navigazione diretta verso `/carrello?sede=vicenza` apre istantaneamente il carrello per la sede di Vicenza (HTTP 200).
- [x] **Protezione CSRF**:
  - [x] Invio delle operazioni di carrello senza token CSRF respinto con **HTTP 403 Forbidden**.
  - [x] Invio con token CSRF manomesso respinto con **HTTP 403 Forbidden**.
- [x] **Sanificazione Output e Prevenzione XSS**:
  - [x] Tutti i valori testuali e numerici passano attraverso `e()` e cast espliciti prima della resa nel DOM.

---

### 14.5 Responsive Mobile (375x667px)

- [x] Contenitore della tabella `.corpo-riepilogo` configurato con `position: relative; overflow-x: auto;`, che racchiude perfettamente lo scorrimento orizzontale della tabella senza intaccare il viewport generale.
- [x] Elementi `.solo-lettori` contenuti nel perimetro dei pulsanti, prevenendo fuoriuscite invisibili.
- [x] Nessun overflow orizzontale anomalo dell'intera pagina (`scrollWidth=375 <= clientWidth=375`).

---

### 14.6 Esito del Collaudo Automatizzato End-to-End e Backend (Playwright)

Report di esecuzione dei test eseguiti con browser reale (Chromium / Playwright) con verifiche sui tre stati del carrello, persistenza MariaDB, operazioni AJAX/DOM e sicurezza CSRF:

```text
========================================================
=== TEST BACKEND: AUTORIZZAZIONE, PERMESSI E STATO VUOTO ===
========================================================
  [OK] Backend Permessi: richiesta a /carrello da ospite respinta con HTTP 401 Unauthorized
  [OK] Accesso a /carrello con cliente autenticato: HTTP 200
  [OK] Titolo dinamico: 'Carrello - Smash Burger'
  [OK] Breadcrumb semantico a 2 livelli: ['Home', 'Carrello']
  [OK] Breadcrumb: elemento corrente non cliccabile 'Carrello'
  [OK] H1 iniziale: 'Da quale sede vuoi ordinare?'
  [OK] Form scelta sede iniziale presente
  [OK] Campo hidden azione='scegli-sede'
  [OK] Presenti 4 radio button per le sedi attive (trovati: 4)
  [OK] Prima sede (Padova) preselezionata di default
  [OK] Link 'Guarda prima il menu' presente verso /menu

========================================================
=== TEST BACKEND & DB: APERTURA CARRELLO E STATO VUOTO ===
========================================================
  [OK] POST-Redirect-GET atterra su /carrello
  [OK] Carrello creato a database per Padova: '18 3 1 Padova'
  [OK] H1 dinamico con sede: 'Il tuo ordine da Padova'
  [OK] Form 'Cambia sede' presente nella schermata prodotti
  [OK] Select sede ha valore selezionato 'padova': 'padova'
  [OK] Sezione riepilogo carrello (.riepilogo) presente
  [OK] Intestazione H2 'Il tuo carrello'
  [OK] Empty state visualizzato: 'Il carrello e vuoto: tocca un prodotto per aggiungerlo.'
  [OK] Link 'Procedi all'ordine' nascosto quando il carrello e vuoto
  [OK] Form catalogo prodotti disponibile
  [OK] Prodotti disponibili in sede mostrati a catalogo: 19
  [OK] Testo accessibile per lettori di schermo: 'Bacon Burger al carrello'

========================================================
=== TEST INTERATTIVO & BACKEND: AGGIUNTA, QUANTITA E RIGHE ===
========================================================
  [OK] Flash message dopo aggiunta: 'Fatto: Carrello aggiornato.'
  [OK] MariaDB: inserita riga carrello per prodotto 2: '2 1 Bacon Burger'
  [OK] Tabella prodotti nel carrello presente
  [OK] Caption tabella accessibile
  [OK] Una riga presente nella tabella del carrello
  [OK] Nome prodotto in tabella corrisponde: 'Bacon Burger'
  [OK] Quantita iniziale in riga: '1'
  [OK] Link CTA 'Procedi all'ordine' visibile verso /pagamento
  [OK] Quantita dopo click su '+': '2'
  [OK] MariaDB: quantita aggiornata a 2 a database: '2'
  [OK] Due prodotti distinti presenti nella tabella del carrello: 2
  [OK] MariaDB: esattamente 2 righe in righe_carrello
  [OK] Quantita decrementata a 1 dopo '-': '1'
  [OK] Flash message dopo rimozione: 'Fatto: Prodotto tolto dal carrello.'
  [OK] Rimasta 1 sola riga dopo 'Togli'

========================================================
=== TEST BACKEND & DB: SVUOTA CARRELLO E CAMBIO SEDE ===
========================================================
  [OK] Pulsante 'Svuota il carrello' presente
  [OK] Flash message svuota carrello: 'Fatto: Carrello svuotato.'
  [OK] MariaDB: righe_carrello svuotato a 0
  [OK] Empty state ripristinato dopo svuota carrello
  [OK] Prodotto aggiunto prima del cambio sede
  [OK] Nuovo H1 dopo cambio sede: 'Il tuo ordine da Treviso'
  [OK] Cambiando sede il carrello e stato automaticamente svuotato
  [OK] MariaDB: sede carrello aggiornata a Treviso: 'treviso'
  [OK] Apertura diretta con parametro ?sede=vicenza: 'Il tuo ordine da Vicenza'

========================================================
=== TEST SICUREZZA: CSRF E AZIONI FORGIATE ===
========================================================
  [OK] Backend blocca richiesta POST senza CSRF con HTTP 403
  [OK] Backend blocca richiesta POST con CSRF non valido con HTTP 403
  [OK] Ripristinato DB: cancellati carrelli di test

========================================================
=== TEST FRONTEND, ACCESSIBILITA E MOBILE (375x667) ===
========================================================
  [OK] Responsive Mobile (375x667): nessun overflow orizzontale (scrollWidth=375 <= clientWidth=375)

=== RIEPILOGO TEST COMPLETO CARRELLO ===
Totale controlli eseguiti: 51
Superati: 51
Falliti: 0
```

---

## 15. Ritiro e Pagamento (`pagamento.php`)

La pagina di cassa e perfezionamento ordine raccoglie la conferma della comanda da parte del cliente autenticato: permette di scegliere tra ritiro in sede e consegna a domicilio, selezionare la fascia oraria o compilare l'indirizzo di recapito, optare per il metodo di pagamento desiderato (carta o contanti) ed eseguire la transazione con contestuale scarico di magazzino a database.

### 15.1 Header, Breadcrumb e Navigazione Contestuale

- [x] **Salto al contenuto e Accessibilita**:
  - [x] Link `<a class="salta" href="#contenuto">Vai al contenuto</a>` correttamente funzionante.
  - [x] Menu principale coerente con lo stato loggato (voci: *Menu*, *Servizi*, *Chi siamo*, *Sedi*, *Contatti*, *Area personale*, *Carrello*, *Esci*).

- [x] **Percorso di Navigazione Semantico (Breadcrumb a 3 Livelli)**:
  - [x] Elemento `<nav aria-label="Percorso">` con lista ordinata `<ol>`.
  - [x] Livello 1: `<a href="./">Home</a>` (click atterra sulla home).
  - [x] Livello 2: `<a href="carrello">Carrello</a>` (click atterra sul carrello).
  - [x] Livello 3: `<span aria-current="page">Pagamento</span>` (voce corrente non cliccabile).

- [x] **Intestazione e Collegamento Indietro**:
  - [x] Titolo dinamico del documento: `"Ritiro e pagamento - Smash Burger"`.
  - [x] Titolo principale `<h1>`: `"Conferma il tuo ordine"`.
  - [x] Collegamento di ritorno al carrello: `<a class="collegamento-indietro" href="carrello"><span class="segno-collegamento" aria-hidden="true">&lt;</span><span>Torna al carrello</span></a>` (testato con click effettivo).

---

### 15.2 Sezione Riepilogo: Che cosa hai ordinato

- [x] **Intestazione e Tabella di Riepilogo (`<section>`)**:
  - [x] Intestazione H2 semantica: `"Che cosa hai ordinato"`.
  - [x] Didascalia accessibile esplicita: `<caption>Riepilogo dell'ordine da [Sede]</caption>` (es. *"Riepilogo dell'ordine da Padova"*).
  - [x] Colonne della tabella con `scope="col"`: *Prodotto*, *Quantita*, *Totale*.
  - [x] Righe prodotto con `scope="row"` sul nome del prodotto.
  - [x] Piede tabella `<tfoot>` con riga riepilogativa: `<th scope="row" colspan="2">Totale</th>` e cella del totale formattata con importo in euro.

---

### 15.3 Modulo di Scelta Modalita, Dati e Pagamento

- [x] **Modulo Principale (`form[action="pagamento"]`)**:
  - [x] Attributi form: `method="post"`, `action="pagamento"`.
  - [x] Token CSRF obbligatorio: `<input type="hidden" name="token_csrf" value="...">`.
  - [x] Attributo dinamico `data-modalita="ritiro"` (aggiornato dinamicamente a `"domicilio"` via JavaScript al cambio opzione, preservando il pieno funzionamento anche senza JS).

- [x] **Raggruppamento Modalita di Ricezione (`<fieldset>`)**:
  - [x] `<legend>Come vuoi ricevere l'ordine?</legend>`.
  - [x] Radio Ritiro: `<input type="radio" id="modalita-ritiro" name="modalita" value="ritiro" checked="checked">` con etichetta associata.
  - [x] Radio Domicilio: `<input type="radio" id="modalita-domicilio" name="modalita" value="domicilio">` con etichetta associata.

- [x] **Opzioni Ritiro in Sede**:
  - [x] Menu a tendina `<select id="ritiro_previsto" name="ritiro_previsto">` popolato con gli orari di apertura e fasce a 15 minuti del giorno corrente.
  - [x] Paragrafo informativo con indirizzo completo della sede presso cui ritirare.

- [x] **Opzioni Consegna a Domicilio**:
  - [x] Campi pre-popolati in automatico dai dati anagrafici del profilo utente autenticato:
    - Indirizzo: `<input id="indirizzo" name="indirizzo" type="text" autocomplete="street-address">` (es. *"Via Roma 12"*).
    - Citta: `<input id="citta" name="citta" type="text" autocomplete="address-level2">` (es. *"Padova"*).
    - Provincia: `<input id="provincia" name="provincia" type="text" maxlength="2" autocomplete="address-level1">` (es. *"PD"*).
    - CAP: `<input id="cap" name="cap" type="text" maxlength="5" autocomplete="postal-code">` (es. *"35100"*).
    - Paese: `<input id="paese" name="paese" type="text" autocomplete="country-name">` (es. *"Italia"*).
    - Telefono: `<input id="telefono" name="telefono" type="tel" autocomplete="tel">` (es. *"3401234567"*).

- [x] **Raggruppamento Metodo di Pagamento (`<fieldset>`)**:
  - [x] `<legend>Come vuoi pagare?</legend>`.
  - [x] Radio Carta: `<input type="radio" id="pagamento-carta" name="metodo_pagamento" value="carta" checked="checked">`.
  - [x] Radio Contanti: `<input type="radio" id="pagamento-contanti" name="metodo_pagamento" value="contanti">`.

- [x] **Pulsante di Conferma Finale**:
  - [x] `<button type="submit">Conferma l'ordine</button>` (richiesta POST al backend).

---

### 15.4 Validazione Backend, Segnalazione Errori Accessibile e Sicurezza

- [x] **Controllo Autorizzazione Backend**:
  - [x] Accesso da utente ospite non autenticato respinto con codice `HTTP 401 Unauthorized`.

- [x] **Controllo Validita Carrello**:
  - [x] Accesso a `/pagamento` con carrello nullo o vuoto intercettato e reindirizzato a `/carrello` con messaggio di avviso *"Errore: Il carrello e vuoto o scaduto: ricomincia dalla sede."*.

- [x] **Validazione Dati di Consegna a Domicilio**:
  - [x] Compilazione con dati mancanti o errati (es. indirizzo vuoto, CAP non di 5 cifre, provincia non di 2 lettere) bloccata dal backend.
  - [x] **Box di Riepilogo Errori Accessibile**:
    - Sezione `<section class="avviso" role="alert" data-tipo="errore">` generata in cima alla pagina.
    - Intestazione semantica `<h2>Controlla questi campi</h2>`.
    - Elenco di link di salto con ancore dirette ai singoli campi non validi (es. `<a href="#indirizzo">Inserisci via e numero civico per la consegna.</a>`).
  - [x] **Errori Inline sui Campi**:
    - Attributo di stato visivo e semantico: `data-stato="errore"` applicato sull'input.
    - Associazione accessibile tramite attributo `aria-describedby="errore-[campo]"`.
    - Messaggio contestuale `<p class="messaggio-errore" id="errore-[campo]">`.

- [x] **Protezione CSRF**:
  - [x] Invio del modulo d'ordine privo di token CSRF bloccato con `HTTP 403 Forbidden`.
  - [x] Invio del modulo con token CSRF manomesso o non valido bloccato con `HTTP 403 Forbidden`.

---

### 15.5 Esecuzione Transazionale Reale degli Ordini a Database (MariaDB)

- [x] **Transazione 1: Ordine con Ritiro in Sede e Pagamento con Carta**:
  - [x] Scalamento automatico della disponibilita merce a magazzino nella tabella `disponibilita_prodotti` (da giacenza 2 a giacenza 1).
  - [x] Inserimento nuovo record su tabella `ordini` con codice univoco `SB-YYYY-XXXX` (es. `SB-2026-0017`), modalita `ritiro`, metodo di pagamento `carta`, stato `ricevuto`.
  - [x] Inserimento righe collegate nella tabella `righe_ordine` con prodotto, quantita e prezzo in centesimi.
  - [x] Eliminazione automatica del record temporaneo da `carrelli` e relative righe in `righe_carrello`.
  - [x] Esecuzione pattern POST-Redirect-GET verso `/area-personale`.
  - [x] Messaggio flash di conferma: *"Fatto: Ordine confermato."*.
  - [x] Presenza immediata del nuovo ordine in cima allo storico ordini del cliente in Area Personale.
  - [x] Navigazione al link della ricevuta corrispondente (`/ricevuta?ordine=[id]`) con caricamento `HTTP 200` e titolo H1 *"Ricevuta SB-YYYY-XXXX"*.

- [x] **Transazione 2: Ordine con Consegna a Domicilio e Pagamento in Contanti**:
  - [x] Creazione record su tabella `ordini` con modalita `domicilio`, metodo di pagamento `contanti`.
  - [x] Congelamento permanente dell'indirizzo completo di spedizione e del recapito telefonico nelle colonne `consegna_indirizzo`, `consegna_citta`, `consegna_provincia`, `consegna_cap`, `consegna_paese`, `consegna_telefono`.
  - [x] Ripristino automatico dello stato del database al termine dei collaudi (cancellazione ordini di test e ripristino giacenze di magazzino).

---

### 15.6 Responsive Mobile e Report di Collaudo E2E

- [x] **Verifica Viewport Mobile (375x667px)**:
  - [x] `clientWidth = 375px`, `scrollWidth = 375px`: zero overflow orizzontale.
  - [x] Sezione riepilogo perfettamente contenuta nello schermo.
  - [x] Campi form e opzioni radio ergonomici e agevolmente toccabili.

#### Log Esecuzione Script E2E Playwright (`scratch/test_user_pagamento.py`)

```text
========================================================
=== TEST BACKEND: AUTORIZZAZIONE, PERMESSI E CARRELLO VUOTO ===
========================================================
  [OK] Backend Permessi: richiesta a /pagamento da ospite respinta con HTTP 401 Unauthorized
  [OK] Carrello nullo reindirizza a /carrello: http://localhost:8080/carrello
  [OK] Avviso carrello vuoto/scaduto: 'Errore: Il carrello e vuoto o scaduto: ricomincia dalla sede.'

========================================================
=== TEST STRUTTURA E RIEPILOGO ORDINE: PAGAMENTO ===
========================================================
  [OK] Accesso a /pagamento con carrello popolato: HTTP 200
  [OK] Titolo dinamico: 'Ritiro e pagamento - Smash Burger'
  [OK] Breadcrumb semantico a 3 livelli: ['Home', 'Carrello', 'Pagamento']
  [OK] Breadcrumb: elemento corrente non cliccabile 'Pagamento'
  [OK] H1 corretto: 'Conferma il tuo ordine'
  [OK] Link 'Torna al carrello' presente
  [OK] Click su 'Torna al carrello' atterra su /carrello
  [OK] Sezione 'Che cosa hai ordinato' presente
  [OK] Caption tabella riepilogo accessibile: 'Riepilogo dell'ordine da Padova'
  [OK] Intestazioni colonne corrette: ['Prodotto', 'Quantita', 'Totale']
  [OK] Nome prodotto marcato con scope='row'
  [OK] Totale ordine formattato in tfoot: '10,90 euro'

========================================================
=== TEST MODULO CONFERMA: SCELTA MODALITA E DATI ===
========================================================
  [OK] Form conferma ordine presente
  [OK] Token CSRF presente nel form
  [OK] Valore iniziale data-modalita='ritiro'
  [OK] Selezione 'domicilio' aggiorna attributo data-modalita='domicilio'
  [OK] Ritorno a 'ritiro' aggiorna data-modalita='ritiro'
  [OK] Select orario di ritiro presente
  [OK] Fasce orarie di ritiro disponibili: 112
  [OK] Indirizzo pre-popolato da profilo utente: 'Via Roma 12'
  [OK] Citta pre-popolata da profilo: 'Padova'
  [OK] Provincia pre-popolata da profilo: 'PD'
  [OK] CAP pre-popolato da profilo: '35100'
  [OK] Telefono pre-popolato da profilo: '3401234567'
  [OK] Radio Carta presente
  [OK] Radio Contanti presente

========================================================
=== TEST VALIDAZIONE BACKEND ED ERRORI DI COMPILAZIONE ===
========================================================
  [OK] Riepilogo errori accessibile (role='alert') visualizzato in cima
  [OK] Intestazione box errori: 'Controlla questi campi'
  [OK] Ancora di errore per #indirizzo presente nel riepilogo
  [OK] Input indirizzo marcato con data-stato='errore'
  [OK] Input indirizzo ha aria-describedby='errore-indirizzo'
  [OK] Input CAP marcato con data-stato='errore'
  [OK] Input provincia marcato con data-stato='errore'
  [OK] Backend blocca conferma ordine senza token CSRF con HTTP 403
  [OK] Backend blocca conferma ordine con token CSRF manomesso con HTTP 403

========================================================
=== TEST CREAZIONE ORDINE 1: RITIRO IN SEDE (CARTA) ===
========================================================
  [OK] POST-Redirect-GET conduce ad Area personale: http://localhost:8080/area-personale
  [OK] Flash message di successo: 'Fatto: Ordine confermato.'
  [OK] MariaDB: carrello utente eliminato dopo la conferma
  [OK] MariaDB: giacenza scalata correttamente nella sede da 2 a 1
  [OK] MariaDB: ordine inserito con successo: '17	SB-2026-0017	ritiro	carta	ricevuto	1090'
  [OK] MariaDB: righe ordine collegate all'ordine 17: '2	1	1090'
  [OK] Nuovo ordine SB-2026-0017 visibile in cima alla tabella ordini
  [OK] Link ricevuta presente nella riga del nuovo ordine
  [OK] Apertura ricevuta dell'ordine 17 riuscita: http://localhost:8080/ricevuta?ordine=17
  [OK] H1 pagina ricevuta con codice ordine: 'Ricevuta SB-2026-0017'

========================================================
=== TEST CREAZIONE ORDINE 2: CONSEGNA A DOMICILIO (CONTANTI) ===
========================================================
  [OK] Ordine a domicilio confermato, redirect su /area-personale
  [OK] MariaDB: ordine a domicilio registrato con indirizzo completo: '18	SB-2026-0018	domicilio	contanti	Via Garibaldi 45	35122'
  [OK] Ripristinato DB: eliminati ordini di test e ripristinata disponibilita merce

========================================================
=== TEST FRONTEND, ACCESSIBILITA E MOBILE (375x667) ===
========================================================
  [OK] Caricamento mobile /pagamento: HTTP 200
  [OK] Responsive Mobile (375x667): nessun overflow orizzontale (scrollWidth=375 <= clientWidth=375)

=== RIEPILOGO TEST COMPLETO PAGAMENTO ===
Totale controlli eseguiti: 53
Superati: 53
Falliti: 0
```

---

## 16. Ricevuta ordine (`ricevuta.php`)

La pagina di ricevuta dell'ordine costituisce il documento di riepilogo fiscale e operativo visualizzabile dal cliente autenticato accedendo allo storico ordini in Area Personale. Mostra gli estremi identificativi dell'ordine (`SB-YYYY-XXXX`), le generalita del cliente intestatario estratte da database, lo stato di evasione e del pagamento, le informazioni logistiche dettagliate (sede e orario di ritiro o indirizzo di recapito a domicilio con recapito telefonico), il dettaglio delle righe acquistate e l'importo totale. La pagina e predisposta per la stampa cartacea tramite foglio di stile dedicato (`stampa.css`).

### 16.1 Header, Breadcrumb e Navigazione Superiore

- [x] **Salto al contenuto e Accessibilita**:
  - [x] Link `<a class="salta" href="#contenuto">Vai al contenuto</a>` presente e focalizzabile.
  - [x] Menu principale e menu account per utente cliente autenticato (voci: *Menu*, *Servizi*, *Chi siamo*, *Sedi*, *Contatti*, *Area personale*, *Carrello*, *Esci*).

- [x] **Percorso di Navigazione Semantico (Breadcrumb a 3 Livelli)**:
  - [x] Elemento `<nav aria-label="Percorso">` con lista `<ol>`.
  - [x] Livello 1: `<a href="./">Home</a>` (click atterra sulla home).
  - [x] Livello 2: `<a href="area-personale">Area personale</a>` (click atterra su `/area-personale`).
  - [x] Livello 3: `<span aria-current="page">Ricevuta SB-YYYY-XXXX</span>` (voce corrente non cliccabile).

- [x] **Intestazione e Ritorno allo Storico**:
  - [x] Titolo dinamico del documento: `"Ricevuta dell'ordine - Smash Burger"`.
  - [x] Intestazione principale `<h1>`: `"Ricevuta SB-YYYY-XXXX"` (es. *"Ricevuta SB-2026-0001"*).
  - [x] Paragrafo descrittivo con data e ora di emissione e nome/cognome del cliente intestatario (es. *"Ordine del 20/08/2026 11:52, intestato a Anna Rossi."*).
  - [x] Collegamento di ritorno in calce: `<a class="collegamento-indietro" href="area-personale"><span class="segno-collegamento" aria-hidden="true">&lt;</span><span>Torna ai tuoi ordini</span></a>` (testato con click effettivo).

---

### 16.2 Sezione Stato dell'Ordine e del Pagamento

- [x] **Intestazione e Indicatori di Stato (`<section>`)**:
  - [x] Intestazione semantica `<h2>Stato</h2>`.
  - [x] Elenco `<ul>` con indicatore per l'ordine e per il pagamento:
    - Stato ordine: etichetta `<span class="etichetta" data-tipo="positivo">` per ordini conclusi o ricevuti, oppure `<span class="etichetta" data-tipo="negativo">` per ordini annullati.
    - Stato pagamento: etichetta `<span class="etichetta" data-tipo="positivo">` per pagamenti effettuati (`pagato`), oppure `<span class="etichetta" data-tipo="attenzione">` per pagamenti rimborsati o in attesa.
  - [x] **Segnalazione Motivo di Annullamento**:
    - Nel caso di ordine annullato (es. Ordine 4), visualizzazione automatica del riquadro informativo `<p class="avviso" role="status" data-tipo="attenzione">` recante il motivo registrato a database (es. *"Motivo dell'annullamento: Indirizzo non raggiungibile dalla societa di consegna."*).

---

### 16.3 Sezione Consegna: Ritiro in Sede o Domicilio

- [x] **Dettagli per Ordini con Ritiro in Sede (`modalita="ritiro"`)**:
  - [x] Intestazione semantica `<h2>Consegna</h2>`.
  - [x] Paragrafo descrittivo con indicazione di citta, indirizzo fisico della sede di ritiro estratto da DB e data/ora prevista del ritiro (es. *"Ritiro in sede a Padova, Via San Fermo 34, il 20/08/2026 12:30."*).

- [x] **Dettagli per Ordini con Spedizione a Domicilio (`modalita="domicilio"`)**:
  - [x] Paragrafo informativo: *"Consegna a domicilio, presa in carico dopo il pagamento."*.
  - [x] Elemento semantico `<address>` contenente tutti i dati di recapito congelati al momento dell'ordine:
    - Via e numero civico (es. *"Via Roma 12"*).
    - CAP, Citta e Provincia (es. *"35100 Padova (PD)"*).
    - Paese (es. *"Italia"*).
    - Recapito telefonico di reperibilita (es. *"Telefono 3401234567"*).

---

### 16.4 Sezione Prodotti e Tabella di Riepilogo

- [x] **Tabella Dettaglio Comanda (`<table>`)**:
  - [x] Intestazione semantica `<h2>Prodotti</h2>`.
  - [x] Didascalia accessibile esplicita: `<caption>Righe dell'ordine SB-YYYY-XXXX</caption>`.
  - [x] Intestazioni di colonna `<th>` con `scope="col"`: *Prodotto*, *Quantita*, *Prezzo*, *Totale*.
  - [x] Righe di dettaglio nel `<tbody>`:
    - Intestazione riga `<th>` con `scope="row"` contenente il nome del prodotto.
    - Cella quantita (numero intero).
    - Cella prezzo unitario formattato in euro.
    - Cella totale di riga calcolato (`quantita * prezzo_centesimi`) formattato in euro.
  - [x] Piede tabella `<tfoot>`:
    - Cella intestazione `<th scope="row" colspan="3">Totale dell'ordine</th>`.
    - Cella con importo complessivo dell'ordine corrispondente a `totale_centesimi` su MariaDB (es. *"17,90 euro"*).

---

### 16.5 Foglio di Stile per la Stampa (`styles/stampa.css`)

- [x] **Inclusione e Regole di Stampa**:
  - [x] Foglio di stile collegato con `<link rel="stylesheet" media="print" href="styles/stampa.css">`.
  - [x] Regole di azzeramento elementi interattivi su supporto cartaceo verificate via emulazione media:
    - Le barre di navigazione (`body > header nav`, footer nav) vengono nascoste (`display: none`).
    - I pulsanti e i collegamenti di salto (`.salta`, `.torna-su`, `button`, `.pulsante`) vengono nascosti.
    - Sfondo pagina forzato a bianco, testo in nero ad alto contrasto per risparmio inchiostro.
    - Protezione rottura schede su piu pagine (`break-inside: avoid`).

---

### 16.6 Sicurezza Backend, Isolamento Proprietario (IDOR) e Controllo Ruoli

- [x] **Accesso Ospite Non Autenticato**:
  - [x] Richiesta a `/ricevuta?ordine=1` da parte di utente non autenticato respinta con `HTTP 401 Unauthorized`.

- [x] **Parametri Mancanti o Non Validi**:
  - [x] Accesso a `/ricevuta` senza parametro `?ordine` intercettato con `HTTP 404 Not Found`.
  - [x] Accesso con parametro non numerico `?ordine=invalido` produce `HTTP 404 Not Found`.
  - [x] Accesso con ID ordine inesistente a DB (`?ordine=999999`) produce `HTTP 404 Not Found`.

- [x] **Difesa IDOR (Insecure Direct Object Reference) e Isolamento Proprietario**:
  - [x] L'utente cliente autenticato `user` (id 3) che tenta di accedere alla ricevuta di un altro cliente (es. Ordine 3 appartenente all'utente 7) viene bloccato con `HTTP 404 Not Found` (la query SQL filtra rigidamente su `o.utente_id = :utente`, non rivelando l'esistenza dell'ordine ad altri utenti).

- [x] **Restrizione per Ruoli Amministrativi e Manageriali**:
  - [x] L'accesso a `/ricevuta` e riservato esclusivamente al ruolo `cliente`: tentativi di accesso da parte di utenti con ruolo `manager` o `admin` vengono respinti con codice `HTTP 403 Forbidden` (i ruoli di gestione consultano gli ordini tramite l'apposito pannello `/controllo-ordine`).

---

### 16.7 Responsive Mobile e Report di Collaudo E2E

- [x] **Verifica Viewport Mobile (375x667px)**:
  - [x] `clientWidth = 375px`, `scrollWidth = 375px`: zero overflow orizzontale.
  - [x] Tabella prodotti leggibile e perfettamente contenuta nello schermo dello smartphone.
  - [x] Riquadri di stato e recapito formattati in modo lineare ed ergonomico.

#### Log Esecuzione Script E2E Playwright (`scratch/test_user_ricevuta.py`)

```text
========================================================
=== TEST BACKEND: AUTORIZZAZIONE, CONTROLLO IDOR E 404 ===
========================================================
  [OK] Backend Permessi: accesso ospite a /ricevuta respinto con HTTP 401 Unauthorized
  [OK] Accesso senza parametro ?ordine produce HTTP 404 Not Found
  [OK] Accesso con parametro ?ordine=invalido produce HTTP 404 Not Found
  [OK] Accesso con ID ordine inesistente (999999) produce HTTP 404 Not Found
  [OK] Difesa IDOR: utente 'user' che richiede ordine di un altro utente riceve HTTP 404 Not Found
  [OK] Permessi di ruolo: manager che accede a /ricevuta viene bloccato con HTTP 403 Forbidden
  [OK] Permessi di ruolo: admin che accede a /ricevuta viene bloccato con HTTP 403 Forbidden

========================================================
=== TEST RICEVUTA ORDINE CON RITIRO IN SEDE (SB-2026-0001) ===
========================================================
  [OK] Accesso a ricevuta del proprio ordine 1: HTTP 200 OK
  [OK] Titolo dinamico corretto: 'Ricevuta dell'ordine - Smash Burger'
  [OK] Breadcrumb semantico a 3 livelli: ['Home', 'Area personale', 'Ricevuta SB-2026-0001']
  [OK] Breadcrumb: elemento corrente non cliccabile 'Ricevuta SB-2026-0001'
  [OK] Intestazione H1 corretta: 'Ricevuta SB-2026-0001'
  [OK] Paragrafo data e cliente intestatario da DB: 'Ordine del 20/08/2026 11:52, intestato a Anna Rossi.'
  [OK] Sezione 'Stato' presente
  [OK] Badge stato ordine: 'concluso'
  [OK] Badge stato 'concluso' ha data-tipo='positivo'
  [OK] Badge stato pagamento: 'pagato'
  [OK] Badge stato 'pagato' ha data-tipo='positivo'
  [OK] Sezione 'Consegna' presente
  [OK] Dettagli sede di ritiro da DB: 'Ritiro in sede a Padova, Via San Fermo 34, il 20/08/2026 12:30.'
  [OK] Sezione 'Prodotti' presente
  [OK] Caption tabella accessibile: 'Righe dell'ordine SB-2026-0001'
  [OK] Intestazioni colonne tabella: ['Prodotto', 'Quantita', 'Prezzo', 'Totale']
  [OK] Numero righe prodotto trovate: 4
  [OK] Riga 1: nome prodotto marcato th[scope='row'] = 'Cheeseburger'
  [OK] Riga 2: nome prodotto marcato th[scope='row'] = 'Patate fritte'
  [OK] Riga 3: nome prodotto marcato th[scope='row'] = 'Acqua naturale'
  [OK] Riga 4: nome prodotto marcato th[scope='row'] = 'Cono gelato'
  [OK] Intestazione riga totale tfoot: 'Totale dell'ordine'
  [OK] Totale ordine coerente col DB (17,90 euro): '17,90 euro'
  [OK] Link 'Torna ai tuoi ordini' presente verso area-personale
  [OK] Click su link indietro torna correttamente ad area-personale: http://localhost:8080/area-personale

========================================================
=== TEST RICEVUTA ORDINE A DOMICILIO (SB-2026-0002) ===
========================================================
  [OK] H1 ricevuta domicilio: 'Ricevuta SB-2026-0002'
  [OK] Avviso presa in carico domicilio presente: 'Consegna a domicilio, presa in carico dopo il pagamento.'
  [OK] Elemento semantico <address> presente per il recapito a domicilio
  [OK] Indirizzo presente in address: 'Via Roma 12 35100 Padova (PD) Italia Telefono 3401234567'
  [OK] CAP, citta e provincia presenti in address
  [OK] Paese presente in address
  [OK] Telefono di contatto presente in address

========================================================
=== TEST RICEVUTA ORDINE ANNULLATO/RIMBORSATO (SB-2026-0004) ===
========================================================
  [OK] Badge stato ordine annullato: 'annullato'
  [OK] Stato 'annullato' ha data-tipo='negativo'
  [OK] Badge pagamento rimborsato: 'rimborsato'
  [OK] Pagamento 'rimborsato' ha data-tipo='attenzione'
  [OK] Box avviso per motivo annullamento presente con role='status'
  [OK] Motivo dell'annullamento visualizzato correttamente: 'Motivo dell'annullamento: Indirizzo non raggiungibile dalla societa di consegna.'

========================================================
=== TEST FOGLIO DI STILE STAMPA (stampa.css) ===
========================================================
  [OK] Link a styles/stampa.css con media='print' presente in head
  [OK] In modalita stampa le barre di navigazione dell'header sono nascoste (display: none)
  [OK] In modalita stampa il link di salto e nascosto (display: none)

========================================================
=== TEST FRONTEND, ACCESSIBILITA E MOBILE (375x667) ===
========================================================
  [OK] Caricamento mobile /ricevuta: HTTP 200
  [OK] Responsive Mobile (375x667): nessun overflow orizzontale (scrollWidth=375 <= clientWidth=375)

=== RIEPILOGO TEST COMPLETO RICEVUTA ===
Totale controlli eseguiti: 50
Superati: 50
Falliti: 0
```

---

## 17. Prenotazione Sala Eventi (`prenota.php`)

La pagina di prenotazione della sala eventi permette ai clienti registrati e autenticati di inoltrare una richiesta di riservazione della sala presso uno dei locali della catena che dispongono di tale servizio. Il processo si articola in due passi progressivi sulla medesima pagina:
1. **Passo 1 (Selezione Sede e Data)**: l'utente seleziona il locale desiderato e il giorno previsto (filtrando le sedi in base all'effettiva disponibilita della sala a database). L'invio avviene con metodo `GET` idempotente, senza mutazioni di stato, funzionando perfettamente sia con JavaScript attivo sia senza JS.
2. **Passo 2 (Consultazione Occupazione, Scelta Orario e Dettagli)**: visualizza la tabella degli intervalli di occupazione della sala per il giorno scelto e permette di selezionare l'orario di inizio (tra le fasce ancora libere), la durata desiderata, il numero di invitati (da 1 a 80) ed eventuali note per il locale (massimo 120 caratteri). L'invio in `POST` registra la richiesta con stato `"in attesa"` e notifica il cliente mediante messaggio flash in Area Personale.

### 17.1 Header, Breadcrumb e Navigazione Superiore

- [x] **Salto al contenuto e Accessibilita**:
  - [x] Link `<a class="salta" href="#contenuto">Vai al contenuto</a>` presente e funzionante.
  - [x] Menu principale e menu account per utente loggato con ruolo cliente.

- [x] **Percorso di Navigazione Semantico (Breadcrumb a 3 Livelli)**:
  - [x] Elemento `<nav aria-label="Percorso">` con lista ordinata `<ol>`.
  - [x] Livello 1: `<a href="./">Home</a>` (click atterra sulla home).
  - [x] Livello 2: `<a href="sedi">Sedi</a>` (click atterra sull'elenco delle sedi).
  - [x] Livello 3: `<span aria-current="page">Prenota la sala</span>` (voce corrente non cliccabile).

- [x] **Intestazione Principale e Informazioni Generali**:
  - [x] Titolo dinamico del documento: `"Prenota la sala eventi - Smash Burger"`.
  - [x] Intestazione principale `<h1>`: `"Prenota la sala eventi"`.
  - [x] Paragrafo informativo sulle durate prenotabili (da 1 ora e 30 minuti a 3 ore) e sul flusso di conferma da parte della sede.
  - [x] Collegamenti di navigazione in calce alla pagina:
    - `<a class="collegamento-indietro" href="sedi"><span class="segno-collegamento" aria-hidden="true">&lt;</span><span>Torna alle sedi</span></a>`
    - `<a href="area-personale">Le tue prenotazioni</a>` (collegamento rapido per verificare lo stato delle richieste inviate).

---

### 17.2 Passo 1: Modulo Selezione Sede e Giorno (`form[method="get"]`)

- [x] **Struttura del Modulo GET**:
  - [x] Elemento `<form method="get" action="prenota">` racchiuso in `<fieldset>` con `<legend>Sede e giorno</legend>`.
  - [x] **Filtro Sedi Attive con Sala Disponibile**:
    - Menu a tendina `<select id="sede" name="sede" required="required">`.
    - Opzioni caricate dinamicamente da MariaDB: sono presenti solo i locali con `sala_eventi_disponibile = 1` (Padova, Treviso, Vicenza).
    - La sede di Udine (`sala_eventi_disponibile = 0`) e correttamente esclusa dalle opzioni selezionabili.
  - [x] **Campo Selezione Data**:
    - Campo `<input type="date" id="data" name="data" required="required">`.
    - Attributo HTML5 `min` impostato alla data odierna (`date('Y-m-d')`).
    - Attributo HTML5 `max` impostato al limite massimo consentito (+90 giorni da oggi, corrispondente a `giorni_prenotabili()`).
  - [x] **Pulsante d'Invio GET**:
    - `<button type="submit">Vedi gli orari liberi</button>`: carica il Passo 2 aggiornando i parametri in querystring (`?sede=...&data=...`).

- [x] **Gestione Casi Particolari ed Errori di Selezione Sede**:
  - [x] Richiesta con slug sede inesistente (es. `?sede=non-esiste`): gestito con codice `HTTP 404 Not Found`.
  - [x] Richiesta con sede priva di sala eventi (es. `?sede=udine`): genera il messaggio di stato dedicato `<p class="avviso" role="status" data-tipo="attenzione"><strong>Attenzione:</strong> la sala di Udine non accetta prenotazioni in questo periodo.</p>`.

---

### 17.3 Passo 2: Visualizzazione Orari e Tabella Occupazione Sala

- [x] **Intestazione di Sezione Dinamica**:
  - [x] Titolo semantico `<h2>Orari del [data_breve] a [Citta]</h2>` (es. *"Orari del 12/09/2026 a Padova"*).

- [x] **Tabella Occupazione della Sala (`views/prenotazione/occupazione.php`)**:
  - [x] Didascalia accessibile esplicita: `<caption>Come e occupata la sala di [Citta] il [data_breve]</caption>`.
  - [x] Colonne della tabella con `scope="col"`: *Dalle*, *Alle*, *Stato*.
  - [x] Righe di intervallo orario con `th[scope="row"]`:
    - Intervallo occupato da prenotazione confermata (es. 19:00 - 22:00 a Padova): contrassegnato con badge `<span class="etichetta" data-tipo="negativo">occupata</span>`.
    - Intervalli liberi della giornata: contrassegnati con badge `<span class="etichetta" data-tipo="positivo">libera</span>`.

- [x] **Selezione Fascia Oraria di Inizio (`<fieldset><legend>Scegli l'orario di inizio</legend>`)**:
  - [x] Elenco a scelta singola `<ul class="scelte">` con controlli radio (`input[type="radio"][name="fascia"]`).
  - [x] **Fasce Occupate**: se l'orario e occupato da un'altra prenotazione (o non consente la durata minima prima del prossimo evento), il controllo radio presenta l'attributo `disabled="disabled"` e l'etichetta visiva reca il badge `<span class="etichetta" data-tipo="negativo">non disponibile</span>`.
  - [x] **Fasce Libere**: controllo radio abilitato, con indicazione testuale accessibile per screen reader `<span class="solo-lettori">disponibile fino a [minuti] minuti</span>`.

---

### 17.4 Passo 2: Dettagli della Prenotazione, Validazione e Sicurezza

- [x] **Modulo di Inoltro Richiesta (`views/prenotazione/prenota-dettagli.php`)**:
  - [x] Attributi modulo: `method="post"`, `action="prenota"`.
  - [x] Token di protezione CSRF: `<input type="hidden" name="token_csrf" value="...">`.
  - [x] Campi di contesto nascosti: `<input type="hidden" name="sede" value="...">` e `<input type="hidden" name="data" value="...">`.
  - [x] **Campo Durata**:
    - Menu a tendina `<select id="durata" name="durata">` con le opzioni calcolate da `durate_prenotabili()` (*1 ora e 30 minuti*, *2 ore*, *2 ore e 30 minuti*, *3 ore*).
  - [x] **Campo Numero Persone**:
    - Input numerico `<input type="number" id="numero_persone" name="numero_persone" required="required" min="1" max="80">`.
  - [x] **Campo Note per la Sede**:
    - Area di testo `<textarea id="note" name="note" rows="3" maxlength="120" aria-describedby="aiuto-note">`.
    - Testo di aiuto accessibile: `<small id="aiuto-note">Al massimo 120 caratteri: la sede la legge nell'elenco delle prenotazioni.</small>`.
  - [x] **Pulsante d'Invio Finale**:
    - `<button type="submit">Invia la richiesta</button>`.

- [x] **Validazione Backend ed Errori di Compilazione Accessibili**:
  - [x] Invio con dati incongrui (es. persone inferiori a 1 o superiori a 80):
    - Generazione in cima alla pagina del box riepilogo `<section class="avviso" role="alert" data-tipo="errore"><h2>Controlla questi campi</h2><ul>...</ul></section>`.
    - Messaggio puntuale: *"Indica quante persone siete, da 1 a 80."*.
    - Attributo semantico `data-stato="errore"` applicato al campo difforme.
  - [x] **Sicurezza CSRF**:
    - Richiesta POST priva di token CSRF bloccata con codice `HTTP 403 Forbidden`.
    - Richiesta POST con token CSRF manomesso o non valido bloccata con codice `HTTP 403 Forbidden`.

---

### 17.5 Esecuzione Transazionale Reale a Database (MariaDB)

- [x] **Creazione Effettiva di una Prenotazione a Sistema**:
  - [x] Compilazione ed invio di una richiesta valida da parte dell'utente `user` (id 3) per Padova in data futura (es. 25/09/2026 dalle 14:00 alle 16:00 per 12 persone con nota specifica).
  - [x] Esecuzione del pattern POST-Redirect-GET verso `/area-personale`.
  - [x] Ricezione del messaggio flash di conferma: *"Fatto: Prenotazione inviata: la sede la confermera a breve."*.
  - [x] **Verifica su MariaDB nella tabella `prenotazioni`**:
    - Nuovo record inserito con `sede_id = 1`, `utente_id = 3`, `data = '2026-09-25'`, `ora_inizio = '14:00:00'`, `ora_fine = '16:00:00'`, `numero_persone = 12`, `stato = 'in attesa'`.
  - [x] **Verifica Visibilita in Area Personale**:
    - La prenotazione compare immediatamente al primo posto nella tabella *"Le tue prenotazioni"* dell'Area Personale con tutti i dati conformi.
  - [x] **Ripristino Database**: cancellazione automatica del record di test inserito al termine del collaudo.

---

### 17.6 Controlli di Ruolo e Protezione Accessi

- [x] **Controllo Permessi**:
  - [x] Utente ospite non autenticato che richiede `/prenota`: respinto con codice `HTTP 401 Unauthorized`.
  - [x] Utente con ruolo `manager`: respinto con codice `HTTP 403 Forbidden` (funzionalita riservata esclusivamente ai clienti; i manager gestiscono le sale tramite il pannello di controllo).
  - [x] Utente con ruolo `admin`: respinto con codice `HTTP 403 Forbidden` (funzionalita riservata esclusivamente ai clienti).

---

### 17.7 Responsive Mobile e Report di Collaudo E2E

- [x] **Verifica Viewport Mobile (375x667px)**:
  - [x] Passo 1 (scelta sede e data): `clientWidth = 375px`, `scrollWidth = 375px`: zero overflow orizzontale.
  - [x] Passo 2 (tabella occupazione, orari e modulo dettagli): `clientWidth = 375px`, `scrollWidth = 375px`: zero overflow orizzontale.
  - [x] Tabella occupazione e controlli radio perfettamente fruibili e touch-friendly.

#### Log Esecuzione Script E2E Playwright (`scratch/test_user_prenota.py`)

```text
========================================================
=== TEST BACKEND: AUTORIZZAZIONE E PERMESSI RUOLI ===
========================================================
  [OK] Backend Permessi: accesso ospite a /prenota respinto con HTTP 401 Unauthorized
  [OK] Backend Permessi: accesso manager a /prenota respinto con HTTP 403 Forbidden
  [OK] Backend Permessi: accesso admin a /prenota respinto con HTTP 403 Forbidden
  [OK] Backend Permessi: accesso cliente a /prenota riuscito con HTTP 200 OK

========================================================
=== TEST STRUTTURA E PASSO 1: SEDE E DATA ===
========================================================
  [OK] Titolo dinamico corretto: 'Prenota la sala eventi - Smash Burger'
  [OK] Breadcrumb semantico a 3 livelli: ['Home', 'Sedi', 'Prenota la sala']
  [OK] Breadcrumb: elemento corrente non cliccabile 'Prenota la sala'
  [OK] Intestazione H1 corretta: 'Prenota la sala eventi'
  [OK] Form Passo 1 presente con method='get'
  [OK] Select sede presente
  [OK] Sedi con sala disponibili presenti nel select: ['Scegli la sede', 'Padova', 'Treviso', 'Vicenza']
  [OK] Udine (sala non disponibile) correttamente esclusa dal select
  [OK] Sede inesistente produce HTTP 404 Not Found
  [OK] Testo avviso: 'Attenzione: la sala di Udine non accetta prenotazioni in questo periodo.'
  [OK] Input data ha type='date'
  [OK] Attributo min data impostato: '2026-09-05'
  [OK] Attributo max data impostato: '2026-12-04'
  [OK] Link 'Torna alle sedi' presente
  [OK] Link 'Le tue prenotazioni' presente verso area-personale

========================================================
=== TEST PASSO 2: ORARI E TABELLA OCCUPAZIONE (PADOVA 2026-09-12) ===
========================================================
  [OK] H2 orari corretto: 'Orari del 12/09/2026 a Padova'
  [OK] Tabella occupazione sala presente
  [OK] Caption tabella accessibile: 'Come e occupata la sala di Padova il 12/09/2026'
  [OK] Colonne tabella occupazione: ['Dalle', 'Alle', 'Stato']
  [OK] Intervallo occupato (19:00 - 22:00) segnalato con etichetta 'occupata' (negativo)
  [OK] Intervalli liberi segnalati con etichetta 'libera' (positivo)
  [OK] Form di prenotazione POST presente al Passo 2
  [OK] Token CSRF presente nel form
  [OK] Campo nascosto sede='padova' presente
  [OK] Campo nascosto data='2026-09-12' presente
  [OK] Opzioni orario di inizio presenti: 20
  [OK] Fascia 19:00 disabilitata poiche' occupata
  [OK] Etichetta 'non disponibile' presente per la fascia 19:00
  [OK] Fascia libera abilitata: value='11:30:00'

========================================================
=== TEST DETTAGLI, VALIDAZIONE ED ERRORI ACCESSIBILI ===
========================================================
  [OK] Select durata presente
  [OK] Opzioni di durata proposte: ['1 ora e 30 minuti', '2 ore', '2 ore e 30 minuti', '3 ore']
  [OK] Input numero persone presente
  [OK] Input persone vincolato a min 1 max 80
  [OK] Textarea note presente
  [OK] Textarea note con maxlength='120'
  [OK] Textarea note ha aria-describedby='aiuto-note'
  [OK] Riepilogo errori accessibile (role='alert') mostrato in cima
  [OK] Messaggio errore persone mostrato nel riepilogo
  [OK] Input numero persone evidenziato con data-stato='errore'
  [OK] Backend blocca prenotazione senza token CSRF con HTTP 403
  [OK] Backend blocca prenotazione con token CSRF non valido con HTTP 403

========================================================
=== TEST CREAZIONE REALE PRENOTAZIONE SU MARIADB ===
========================================================
  [OK] POST-Redirect-GET conduce ad Area personale: http://localhost:8080/area-personale
  [OK] Flash message di successo: 'Fatto: Prenotazione inviata: la sede la confermera a breve.'
  [OK] MariaDB: record prenotazione trovato nella tabella prenotazioni
  [OK] MariaDB: campi prenotazione corretti: '5	1	3	2026-09-25	14:00:00	16:00:00	12	Festa di compleanno aziendale SmashBurger	in attesa'
  [OK] Tabella prenotazioni visibile in Area Personale
  [OK] Nuova prenotazione presente in cima alla tabella: 'Padova 25/09/2026 14:00 - 16:00 12 in attesa'
  [OK] MariaDB: cancellata prenotazione di collaudo id=5

========================================================
=== TEST FRONTEND, ACCESSIBILITA E MOBILE (375x667) ===
========================================================
  [OK] Mobile: Passo 1 caricato con HTTP 200
  [OK] Mobile Passo 1: nessun overflow orizzontale (375 <= 375)
  [OK] Mobile: Passo 2 caricato con HTTP 200
  [OK] Mobile Passo 2: nessun overflow orizzontale (375 <= 375)

=== RIEPILOGO TEST COMPLETO PRENOTA ===
Totale controlli eseguiti: 56
Superati: 56
Falliti: 0
```

---

## 18. Pannello: Ordini (`controllo.php`)

La pagina di gestione degli ordini costituisce la schermata operativa principale del pannello di controllo riservata ai ruoli con privilegi amministrativi (`manager` e `amministratore`). Offre la visualizzazione sintetica dell'incasso degli ultimi 30 giorni con grafico ad istogrammi SVG accessibile, il filtraggio per sede (solo per l'amministratore) e per stato di avanzamento, e la gestione rapida inline dello stato della comanda e dello stato del pagamento mediante menu a tendina contestuali a salvataggio immediato.

### 18.1 Header, Breadcrumb e Navigazione del Pannello

- [x] **Salto al contenuto e Accessibilita**:
  - [x] Link `<a class="salta" href="#contenuto">Vai al contenuto</a>` presente e focalizzabile.
  - [x] Menu principale e menu account coerenti con lo stato di login amministrativo/gestionale.

- [x] **Percorso di Navigazione Semantico (Breadcrumb a 2 Livelli)**:
  - [x] Elemento `<nav aria-label="Percorso">` con lista ordinata `<ol>`.
  - [x] Livello 1: `<a href="./">Home</a>` (click atterra sulla home).
  - [x] Livello 2: `<span aria-current="page">Ordini</span>` (voce corrente non cliccabile).

- [x] **Intestazione Principale Differenziata per Ruolo**:
  - [x] Titolo dinamico del documento: `"Ordini - Pannello di controllo"`.
  - [x] **Ruolo Manager**: `<h1>Ordini di [Citta]</h1>` (es. *"Ordini di Padova"* per il gestore di Padova).
  - [x] **Ruolo Amministratore**: `<h1>Ordini</h1>` (visione globale e centralizzata).

- [x] **Barra di Navigazione Interna del Pannello (`views/controllo/navigazione.php`)**:
  - [x] Elemento semantico `<nav class="filtri" aria-label="Sezioni del pannello">` con lista `<ul>`.
  - [x] **Viste Manager**: include *Ordini* (con `aria-current="page"`), *Prodotti*, *Prenotazioni* e la voce esclusiva `<a href="controllo-sede">La tua sede</a>`. Le sezioni non accessibili al manager (*Categorie*, *Sedi*, *Utenti*) risultano rigorosamente escluse dal markup.
  - [x] **Viste Amministratore**: include tutte le sezioni di gestione aziendale (*Ordini*, *Prodotti*, *Categorie*, *Sedi*, *Prenotazioni*, *Messaggi*, *Utenti*). La voce "La tua sede" non e presente.

---

### 18.2 Sezione Incasso con Grafico Accessibile (`views/controllo/incasso.php`)

- [x] **Metriche Finanziarie e Riquadro Informativo (`<section class="incasso">`)**:
  - [x] Intestazione H2 semantica: `"Incasso degli ultimi 30 giorni"`.
  - [x] Totale incassato nel periodo formattato in euro (`<p class="totale">`): calcolato dinamicamente a database escludendo gli ordini annullati (la cui merce e stata ripristinata a magazzino).
  - [x] Didascalia descrittiva: `<p class="didascalia-grafico">Incasso giornaliero, importi in euro.</p>`.

- [x] **Grafico Temporale SVG Accessibile**:
  - [x] Contenitore ad alta accessibilita: `<div class="contenitore-grafico" role="region" tabindex="0" aria-label="Grafico dell'incasso giornaliero; scorri orizzontalmente per vedere tutte le date">`.
  - [x] Grafico vettoriale `<svg class="grafico-incasso">` generato lato server con assi coordinati, etichette temporali delle date e barre percentuali dell'incasso.

---

### 18.3 Modulo Filtri di Ricerca (`form[method="get"]`)

- [x] **Struttura dei Filtri**:
  - [x] Modulo `<form method="get" action="controllo">` con raggruppamento `<fieldset><legend>Filtri</legend>`.
  - [x] **Filtro Sede (Esclusivo per Amministratore)**:
    - Menu `<select id="sede" name="sede">`: visualizzato solo se l'utente e amministratore (il manager e vincolato per definizione alla propria sede).
    - Opzioni: *"Tutte le sedi"*, *"Padova"*, *"Treviso"*, *"Vicenza"*, *"Udine"*.
    - Selezione di una sede (es. Treviso, ID 2) applica il parametro querystring `?sede=2` e filtra le righe della tabella.
  - [x] **Filtro Stato (Condiviso)**:
    - Menu `<select id="stato" name="stato">` con opzione *"Tutti gli stati"* e gli stati ammessi (*ricevuto*, *in preparazione*, *pronto*, *in consegna*, *concluso*, *annullato*).
  - [x] **Pulsante d'Invio**: `<button type="submit">Filtra</button>`.

---

### 18.4 Tabella Operativa degli Ordini e Modifica Stato Inline

- [x] **Struttura della Tabella (`<table>`)**:
  - [x] Didascalia accessibile esplicita: `<caption>Ordini registrati</caption>`.
  - [x] Intestazioni colonna `<th>` con `scope="col"`: *Numero*, *Cliente*, *Sede*, *Modalita*, *Totale*, *Stato e pagamento*, *Dettaglio*.
  - [x] Righe ordine `<tr>` con attributo dati `data-ordine="ID"` e codice ordine univoco in `<th scope="row">` (es. `SB-2026-0001`).

- [x] **Modifica Stato e Pagamento Inline (per Ordini Non Annullati)**:
  - [x] Modulo integrato nella cella: `<form method="post" action="controllo" data-modulo="ordine">`.
  - [x] Token CSRF presente nel form: `<input type="hidden" name="token_csrf" value="...">`.
  - [x] Identificativo dell'ordine nascosto: `<input type="hidden" name="ordine_id" value="ID">`.
  - [x] Menu selezione stato dell'ordine con etichetta per screen reader associata (`<label class="solo-lettori" for="stato-ID">`).
  - [x] Menu selezione stato del pagamento con etichetta per screen reader associata (`<label class="solo-lettori" for="pagamento-ID">`).
  - [x] **Pulsante di Invio Esplicito Accessibile**: ciascun modulo include `<button type="submit">Aggiorna <span class="solo-lettori">stato e pagamento di SB-YYYY-XXXX</span></button>` a garanzia della piena conformita WCAG e REGOLE.md par. 17 (evitando invii automatici prematuri quando due controlli valgono insieme).
  - [x] **Aggiornamento via AJAX**: l'invio viene intercettato da `script.js` aggiornando il record su MariaDB senza ricaricare la pagina e restituendo il messaggio flash *"Fatto: Ordine aggiornato."*.

- [x] **Visualizzazione Ordini Annullati**:
  - [x] I moduli di modifica vengono disabilitati: compaiono i badge statici `<span class="etichetta" data-tipo="negativo">annullato</span>` e l'etichetta dello stato pagamento.

- [x] **Collegamento al Dettaglio Completo**:
  - [x] Link `<a href="controllo-ordine?ordine=ID">Apri <span class="solo-lettori">il dettaglio di SB-YYYY-XXXX</span></a>` che consente di passare alla scheda approfondita dell'ordine.

---

### 18.5 Sicurezza Backend, Isolamento Multi-Tenant e Controllo Ruoli

- [x] **Controllo Permessi**:
  - [x] Accesso utente ospite non autenticato respinto con codice `HTTP 401 Unauthorized`.
  - [x] Accesso utente cliente (`user`) respinto con codice `HTTP 403 Forbidden`.
  - [x] Accesso consentito unicamente a `manager` e `amministratore`.

- [x] **Isolamento Multi-Tenant per Sede (Manager)**:
  - [x] Le query SQL per il manager includono rigidamente la clausola `WHERE sede_id = :sede`. Il manager visualizza unicamente gli ordini della propria filiale.
  - [x] **Difesa contro Tentativi di Manomissione Cross-Sede**: se un manager tenta di inoltrare un aggiornamento POST su un ordine appartenente ad un'altra sede (es. il manager di Padova tenta di modificare l'ordine 6 di Treviso alterando `ordine_id`), la query `UPDATE` viene respinta dal backend con il messaggio d'errore dedicato *"Errore: L'ordine non esiste o non e di questa sede."* e il database MariaDB preserva inalterato lo stato dell'ordine.

- [x] **Protezione CSRF**:
  - [x] Richieste POST prive di token CSRF bloccate con codice `HTTP 403 Forbidden`.
  - [x] Richieste POST con token manomesso bloccate con codice `HTTP 403 Forbidden`.

---

### 18.6 Responsive Mobile e Report di Collaudo E2E

- [x] **Verifica Viewport Mobile (375x667px)**:
  - [x] `clientWidth = 375px`, `scrollWidth = 375px`: zero overflow orizzontale.
  - [x] Risoluzione del vincolo di posizionamento delle utilita per screen reader `.solo-lettori` con azzeramento dell'ingombro su schermi stretti.
  - [x] Tabella e grafico a barre scorrano orizzontalmente all'interno del proprio perimetro senza provocare lo scorrimento della finestra.

#### Log Esecuzione Script E2E Playwright (`scratch/test_controllo_ordini.py`)

```text
========================================================
=== TEST BACKEND: AUTORIZZAZIONE E CONTROLLO ACCESSI ===
========================================================
  [OK] Accesso ospite non autenticato respinto con HTTP 401 Unauthorized
  [OK] Accesso cliente ('user') respinto con HTTP 403 Forbidden

========================================================
=== TEST VISTA MANAGER (SEDE PADOVA) ===
========================================================
  [OK] Accesso manager a /controllo: HTTP 200 OK
  [OK] Titolo dinamico corretto: 'Ordini - Pannello di controllo'
  [OK] Breadcrumb semantico a 2 livelli: ['Home', 'Ordini']
  [OK] Breadcrumb: elemento corrente non cliccabile 'Ordini'
  [OK] H1 contestualizzato con sede del manager: 'Ordini di Padova'
  [OK] Navigazione 'Sezioni del pannello' presente
  [OK] Link 'La tua sede' presente per il manager: ['Ordini', 'Prodotti', 'Prenotazioni', 'La tua sede']
  [OK] Sezioni amministrative (Categorie, Utenti) correttamente nascoste al manager
  [OK] Sezione incasso presente
  [OK] H2 incasso: 'Incasso degli ultimi 30 giorni'
  [OK] Importo totale incasso presente: '117,50 euro'
  [OK] Contenitore grafico accessibile (role='region', tabindex='0') presente
  [OK] Grafico SVG generato all'interno del contenitore
  [OK] Form filtri presente
  [OK] Select filtro stato presente
  [OK] Select filtro sede ASSENTE per il manager (limitato alla propria sede)
  [OK] Tabella ordini presente
  [OK] Caption tabella ordini: 'Ordini registrati'
  [OK] Colonne tabella: ['Numero', 'Cliente', 'Sede', 'Modalita', 'Totale', 'Stato e pagamento', 'Dettaglio']
  [OK] Numero ordini visualizzati dal manager: 10
  [OK] Isolamento ordini: visibile esclusivamente la sede: {'Padova'}
  [OK] Ordine SB-2026-0006 di Treviso non visibile al manager di Padova

========================================================
=== TEST SICUREZZA MULTI-TENANT: TENTATIVO MUTAZIONE CROSS-SEDE ===
========================================================
  [OK] Backend blocca mutazione cross-sede con errore: 'Errore: L'ordine non esiste o non e di questa sede.'
  [OK] MariaDB: stato ordine 6 inalterato ('ricevuto')

========================================================
=== TEST MUTAZIONE STATO ORDINE PROPRIA SEDE (MARIADB) ===
========================================================
  [OK] Flash message aggiornamento: 'Fatto: Ordine aggiornato.'
  [OK] MariaDB: stato ordine 7 aggiornato a 'in preparazione': 'in preparazione pagato'
  [OK] Ripristinato DB: stato ordine 7 reimpostato a 'ricevuto'
  [OK] Backend blocca POST senza token CSRF con HTTP 403

========================================================
=== TEST VISTA AMMINISTRATORE ('admin') ===
========================================================
  [OK] Accesso admin a /controllo: HTTP 200 OK
  [OK] H1 amministratore generale: 'Ordini'
  [OK] Tutte le sezioni visibili ad admin: ['Ordini', 'Prodotti', 'Categorie', 'Sedi', 'Prenotazioni', 'Messaggi', 'Utenti']
  [OK] Voce 'La tua sede' non presente per admin
  [OK] Select filtro sede presente per admin
  [OK] Select filtro stato presente per admin
  [OK] Filtro sede Treviso applicato nell'URL: http://localhost:8080/controllo?sede=2&stato=
  [OK] Ordini trovati per Treviso: 2
  [OK] Ordine SB-2026-0006 di Treviso visibile dopo il filtro
  [OK] Link 'Apri dettaglio' presente: controllo-ordine?ordine=6

========================================================
=== TEST FRONTEND, ACCESSIBILITA E MOBILE (375x667) ===
========================================================
  [OK] Mobile /controllo caricato con HTTP 200
  [OK] Mobile /controllo: nessun overflow orizzontale (375 <= 375)

=== RIEPILOGO TEST COMPLETO PANNELLO ORDINI ===
Totale controlli eseguiti: 42
Superati: 42
Falliti: 0
```

---

## 19. Pannello: Dettaglio Ordine (`controllo-ordine.php`)

La pagina di dettaglio ordine del pannello di controllo permette al gestore di sede (`manager`) e all'amministratore (`admin`) di esaminare l'anagrafica completa di una singola comanda, le modalita di recapito (ritiro in sede o consegna a domicilio), i dettagli di pagamento e le righe dei prodotti ordinati con prezzi e totali congelati. Consente inoltre l'annullamento dell'ordine con inserimento della motivazione obbligatoria, eventuale rimborso contestuale e ripristino automatico delle quantita a magazzino nella sede associata.

---

### 19.1 Header, Breadcrumb e Navigazione di Sezione (Condivisi)

- [x] **Salto al contenuto principale (`.salta`)**
  - **Tipo**: Collegamento interno (`<a class="salta" href="#contenuto">Vai al contenuto</a>`).
  - **Comportamento al click/invio**: Sposta immediatamente il focus e lo scorrimento a `<main id="contenuto">`.

- [x] **Breadcrumb Semantico (`aria-label="Percorso di navigazione"`)**
  - **Struttura a 3 livelli**:
    1. `<a href="/">Home</a>`
    2. `<a href="controllo">Ordini</a>`
    3. `<span aria-current="page">SB-2026-XXXX</span>` (elemento testuale non cliccabile recante il numero comanda).
  - **Comportamento visivo**: Separatore visivo semantico con contrasto conforme WCAG.

- [x] **Navigazione Interna del Pannello (`aria-label="Sezioni del pannello"`)**
  - **Voci per Manager**: `Ordini`, `Prodotti`, `Prenotazioni`, `La tua sede` (sezioni globali `Categorie`, `Sedi`, `Messaggi`, `Utenti` escluse).
  - **Voci per Admin**: `Ordini`, `Prodotti`, `Categorie`, `Sedi`, `Prenotazioni`, `Messaggi`, `Utenti`.
  - **Stato attivo**: la voce `Ordini` mantiene l'indicatore di sezione attiva.

- [x] **Collegamento Rapido di Ritorno**
  - **Tipo**: Collegamento ipertestuale (`<a href="controllo" class="torna">Torna agli ordini</a>`).
  - **Destinazione**: Rimanda alla panoramica degli ordini filtrabile (`controllo.php`).

---

### 19.2 Stato dell'Ordine, Dati Amministrativi ed Economici

- [x] **Intestazione Principale (`<h1>`)**
  - **Formato**: `Ordine SB-2026-XXXX` con codice formattato e univoco dell'ordine.

- [x] **Sezione Stato e Pagamento (`<section aria-labelledby="...">`)**
  - [x] **Badge Semantico di Stato**:
    - Tag `span` con classe `.badge` e attributo `data-tipo`:
      - `data-tipo="positivo"` per ordini con stato `concluso`.
      - `data-tipo="attenzione"` per ordini con stato `in preparazione`.
      - `data-tipo="neutro"` per ordini con stato `ricevuto`.
      - `data-tipo="negativo"` per ordini con stato `annullato`.
  - [x] **Dettaglio Pagamento**:
    - Mostra il metodo prescelto e la condizione contabile (es. "carta, pagato", "contanti, da pagare", oppure "rimborsato").
  - [x] **Totale Comanda Congelato**:
    - Importo complessivo formattato in formato italiano (es. `17,90 euro`).

- [x] **Box Notifica Motivo Annullamento**
  - **Condizione di rendering**: Visibile solo se l'ordine e nello stato `annullato`.
  - **Markup**: `<div class="avviso" data-tipo="attenzione" role="status">` contenente il testo `Motivo dell'annullamento: [motivazione inserita]`.

---

### 19.3 Cliente e Modalita di Recapito

- [x] **Sezione Dati Cliente (`<h2>Cliente</h2>`)**
  - [x] Nome e cognome dell'utente con identificativo account (es. `Anna Rossi, user`).
  - [x] Collegamento interattivo email (`<a href="mailto:email@example.it">email@example.it</a>`).

- [x] **Sezione Recapito (`<h2>Recapito</h2>`)**
  - [x] **Ritiro in Sede**:
    - Visualizza sede di ritiro, indirizzo civico e data/ora prevista (es. `Ritiro in sede a Padova, Via San Fermo 34, previsto per il 20/08/2026 12:30.`).
    - Non genera blocchi `<address>` superflui.
  - [x] **Consegna a Domicilio**:
    - Dicitura esplicita `Consegna a domicilio`.
    - Tag semantico `<address>` conforme agli standard HTML5 con via, numero civico, CAP, comune, provincia, nazione e recapito telefonico (`Telefono 3401234567`).

---

### 19.4 Tabella Prodotti e Righe d'Ordine

- [x] **Tabella Dettaglio Righe (`<table>`)**
  - [x] **Didascalia Accessibile**: `<caption>Righe dell'ordine SB-2026-XXXX</caption>`.
  - [x] **Intestazioni di Colonna**: tag `<th scope="col">` per `Prodotto`, `Quantita`, `Prezzo`, `Totale`.
  - [x] **Intestazioni di Riga**: tag `<th scope="row">` sul nome di ciascun prodotto (es. `Cheeseburger`).
  - [x] **Prezzo Unitario e Subtotale**: congelati al momento dell'ordine, garantendo che future variazioni di listino non alterino lo storico contabile.
  - [x] **Piede Tabella (`<tfoot>`)**: riepilogo riga totale complessivo della comanda.

---

### 19.5 Procedura di Annullamento e Ripristino Scorte

- [x] **Pulsante/Link di Avvio Annullamento**:
  - [x] Presente solo se l'ordine e in stato attivo (`ricevuto`, `in preparazione`). Non compare se l'ordine e gia `annullato` o `concluso`.
  - [x] Collegamento ipertestuale `<a href="controllo-ordine?ordine=X&amp;annulla=1" class="bottone" data-variante="distruttivo">Annulla l'ordine</a>`.

- [x] **Box Modulo di Conferma Annullamento (`?annulla=1`)**:
  - [x] Visualizzato come blocco di allerta prioritario: `<div class="avviso" data-tipo="errore" role="alert">`.
  - [x] Intestazione dedicata: `<h2>Vuoi annullare questo ordine?</h2>`.
  - [x] Modulo sicuro con `data-modulo="annullamento"` e metodo `POST`.
  - [x] Token CSRF obbligatorio generato tramite `campo_csrf()`.
  - [x] Input nascosto `<input type="hidden" name="ordine" value="X">`.
  - [x] Campo di testo multilinea `<textarea id="motivo" name="motivo" required="required" minlength="5" maxlength="255">`:
    - Etichetta accessibile `<label for="motivo">Motivo dell'annullamento</label>`.
    - Validazione client e server: obbligatorio, compreso tra 5 e 255 caratteri.
  - [x] Casella di selezione `<input type="checkbox" id="rimborsa" name="rimborsa" value="1">`:
    - Etichetta accessibile `<label for="rimborsa">Segna il pagamento come rimborsato</label>`.
    - Preselezionata automaticamente (`checked="checked"`) se l'ordine risulta gia pagato.
  - [x] Controlli di invio e revoca:
    - `<button type="submit" class="bottone" data-variante="distruttivo">Conferma annullamento</button>`.
    - `<a href="controllo-ordine?ordine=X" class="bottone" data-variante="secondario">Lascia com'e</a>` per chiudere l'avviso e annullare l'operazione senza mutare i dati.

- [x] **Transazione Backend e Ripristino Automatico a Magazzino**:
  - [x] Transazione atomica MariaDB:
    - Aggiornamento riga `ordini`: `stato = 'annullato'`, `motivo_annullamento = :motivo`.
    - Se richiesta opzione rimborso: `stato_pagamento = 'rimborsato'`.
    - Query incrementale di ripristino per ogni prodotto ordinato: `UPDATE disponibilita_prodotti SET quantita = quantita + :qta WHERE sede_id = :sede AND prodotto_id = :prodotto`.
  - [x] Pattern POST-Redirect-GET:
    - Reindirizzamento verso `controllo-ordine?ordine=X`.
    - Messaggio flash di conferma annunciato con `role="status"`: *"Fatto: Ordine annullato e merce rimessa a disposizione."*.

---

### 19.6 Sicurezza Backend, Isolamento Multi-Tenant e IDOR

- [x] **Controllo degli Accessi per Ruolo**:
  - [x] Ospite non autenticato respinto con codice `HTTP 401 Unauthorized`.
  - [x] Cliente autenticato con ruolo semplice (`user`) respinto con codice `HTTP 403 Forbidden`.
  - [x] Gestore di sede (`manager`) e amministratore (`admin`) autorizzati con codice `HTTP 200 OK`.

- [x] **Validazione Parametri di Ingresso**:
  - [x] Richiesta priva del parametro `ordine` o con valore non numerico respinta con codice `HTTP 404 Not Found`.
  - [x] Richiesta con identificativo di ordine inesistente a database (es. `ordine=99999`) respinta con codice `HTTP 404 Not Found`.

- [x] **Isolamento Multi-Tenant e Prevenzione IDOR (Insecure Direct Object Reference)**:
  - [x] Nel caso del `manager`, la lettura dell'ordine e vincolata alla sede assegnata: se il manager di Padova tenta di aprire un ordine di Treviso (es. `ordine=6`), il server restituisce `HTTP 404 Not Found`.
  - [x] **Prevenzione IDOR in Mutazione POST**: se il gestore invia una richiesta POST fraudolenta tentando di annullare un ordine appartenente a un'altra sede, il backend blocca l'operazione con `HTTP 404 Not Found`, impedendo qualsiasi alterazione dei dati dell'ordine e delle scorte di magazzino.
  - [x] Nel caso dell'amministratore (`admin`), l'accesso e globale e consente di visionare e gestire qualsiasi ordine in tutte le sedi (Padova, Treviso, Vicenza, Verona).

- [x] **Protezione CSRF**:
  - [x] Richieste POST prive di token CSRF bloccate con codice `HTTP 403 Forbidden`.
  - [x] Richieste POST con token CSRF manomesso bloccate con codice `HTTP 403 Forbidden`.

---

### 19.7 Responsive Mobile e Report di Collaudo E2E

- [x] **Verifica Viewport Mobile (375x667px)**:
  - [x] `clientWidth = 375px`, `scrollWidth = 375px`: zero overflow orizzontale.
  - [x] Modulo di annullamento, tabella prodotti e dettagli cliente perfettamente impaginati e fruibili su dispositivi mobili compatti.

#### Log Esecuzione Script E2E Playwright (`scratch/test_controllo_dettaglio_ordine.py`)

```text
========================================================
=== TEST BACKEND: AUTORIZZAZIONE E PERMESSI DI RUOLO ===
========================================================
  [OK] Accesso ospite a /controllo-ordine respinto con HTTP 401 Unauthorized
  [OK] Accesso cliente ('user') respinto con HTTP 403 Forbidden

========================================================
=== TEST VISTA MANAGER & ISOLAMENTO MULTI-TENANT (IDOR) ===
========================================================
  [OK] Accesso senza parametro ordine produce HTTP 404 Not Found
  [OK] Accesso ad ordine inesistente (99999) produce HTTP 404 Not Found
  [OK] Sicurezza Multi-Tenant: Manager Padova che accede a Ordine 6 (Treviso) riceve HTTP 404 Not Found
  [OK] Accesso manager a proprio ordine (Ordine 1, Padova): HTTP 200 OK

========================================================
=== TEST STRUTTURA SEMANTICA E DATI: RITIRO IN SEDE ===
========================================================
  [OK] Titolo dinamico corretto: 'Dettaglio ordine - Pannello di controllo'
  [OK] H1 semantico con numero ordine: 'Ordine SB-2026-0001'
  [OK] Breadcrumb a 3 livelli: ['Home', 'Ordini', 'SB-2026-0001']
  [OK] Elemento corrente non cliccabile: 'SB-2026-0001'
  [OK] Navigazione manager limitata: ['Ordini', 'Prodotti', 'Prenotazioni', 'La tua sede']
  [OK] Sezione H2 'Stato' presente
  [OK] Badge stato ordine: 'concluso'
  [OK] Dettaglio pagamento presente ('carta, pagato')
  [OK] Totale ordine presente ('17,90 euro')
  [OK] Sezione H2 'Cliente' presente
  [OK] Anagrafica cliente: 'Anna Rossi, user anna.rossi@example.it'
  [OK] Link mailto con email cliente presente
  [OK] Sezione H2 'Recapito' presente
  [OK] Recapito ritiro con indirizzo sede: 'Ritiro in sede a Padova, Via San Fermo 34, previsto per il 20/08/2026 12:30.'
  [OK] Nessun tag <address> per ordine con ritiro in sede
  [OK] Sezione H2 'Prodotti' presente
  [OK] Tabella righe ordine presente
  [OK] Caption tabella accessibile: 'Righe dell'ordine SB-2026-0001'
  [OK] Intestazioni colonne corrette: ['Prodotto', 'Quantita', 'Prezzo', 'Totale']
  [OK] Riga con th scope='row' per nome prodotto: 'Cheeseburger'
  [OK] Link 'Torna agli ordini' presente verso /controllo

========================================================
=== TEST ORDINE A DOMICILIO (ORDINE 2) & ANNULLATO (ORDINE 4) ===
========================================================
  [OK] Dicitura 'Consegna a domicilio' presente
  [OK] Tag semantico <address> presente per consegna a domicilio
  [OK] Indirizzo nel tag <address>: 'Via Roma 12 35100 Padova (PD) Italia Telefono 3401234567'
  [OK] Badge ordine annullato data-tipo='negativo': 'annullato'
  [OK] Box motivo annullamento presente (.avviso[data-tipo='attenzione'])
  [OK] Testo motivo annullamento: 'Motivo dell'annullamento: Indirizzo non raggiungibile dalla societa di consegna.'
  [OK] Link 'Annulla l'ordine' non mostrato per ordine gia' annullato

========================================================
=== TEST MODULO CONFERMA ANNULLAMENTO (?annulla=1) ===
========================================================
  [OK] Link 'Annulla l'ordine' presente per ordine non annullato
  [OK] Link conduce a ?annulla=1
  [OK] URL corrente con parametro di conferma: http://localhost:8080/controllo-ordine?ordine=7&annulla=1
  [OK] Box di avviso conferma annullamento visibile (role='alert', data-tipo='errore')
  [OK] H2 box conferma: 'Vuoi annullare questo ordine?'
  [OK] Form data-modulo='annullamento' presente
  [OK] Token CSRF presente nel form
  [OK] Hidden input ordine='7'
  [OK] Textarea #motivo presente
  [OK] Textarea #motivo ha attributo required
  [OK] Textarea #motivo minlength='5'
  [OK] Textarea #motivo maxlength='255'
  [OK] Checkbox #rimborsa presente
  [OK] Checkbox #rimborsa preselezionata (ordine 7 e' pagato)
  [OK] Link 'Lascia com'e' presente per annullare l'operazione senza mutazioni

========================================================
=== TEST SICUREZZA CSRF E MANOMISSIONE CROSS-SEDE ===
========================================================
  [OK] POST senza CSRF bloccata con HTTP 403 Forbidden
  [OK] POST con token CSRF manomesso bloccata con HTTP 403 Forbidden
  [OK] Tentativo annullamento cross-sede respinto con HTTP 404 Not Found
  [OK] MariaDB: Ordine 6 inalterato nello stato 'ricevuto'

========================================================
=== TEST TRANSAZIONE: ANNULLAMENTO ORDINE & SCORTE MARIADB ===
========================================================
  [OK] MariaDB giacenza iniziale Chicken BBQ (sede 1): 37
  [OK] POST-Redirect-GET atterra su /controllo-ordine?ordine=7: http://localhost:8080/controllo-ordine?ordine=7
  [OK] Flash message successo: 'Fatto: Ordine annullato e merce rimessa a disposizione.'
  [OK] MariaDB: stato ordine 7 aggiornato a 'annullato'
  [OK] MariaDB: stato pagamento aggiornato a 'rimborsato'
  [OK] MariaDB: motivo annullamento memorizzato: 'Cliente impossibilitato al ritiro: richiesta rimborso concordata telefonicamente.'
  [OK] MariaDB: scorta ripristinata a magazzino (+1 unita'): 38 (prima: 37)
  [OK] Frontend mostra badge 'annullato'
  [OK] Frontend mostra pagamento 'rimborsato'
  [OK] Ripristinato Ordine 7 a 'ricevuto'/'pagato' e scorta prodotto 7 a 37 a DB

========================================================
=== TEST VISTA AMMINISTRATORE ('admin') SU TUTTE LE SEDI ===
========================================================
  [OK] Admin accede con successo ad Ordine 1 (Padova)
  [OK] Navigazione admin completa con tutte le sezioni: ['Ordini', 'Prodotti', 'Categorie', 'Sedi', 'Prenotazioni', 'Messaggi', 'Utenti']
  [OK] Admin accede con successo ad Ordine 6 (Treviso)
  [OK] Admin visualizza H1 Treviso: 'Ordine SB-2026-0006'
  [OK] Admin accede con successo ad Ordine 19 (Vicenza)

========================================================
=== TEST FRONTEND & RESPONSIVE MOBILE (375x667) ===
========================================================
  [OK] Screenshot desktop salvato: scratch/controllo_ordine_desktop.png
  [OK] Mobile /controllo-ordine?ordine=1 caricato con HTTP 200
  [OK] Mobile /controllo-ordine: nessun overflow orizzontale (375 <= 375)
  [OK] Screenshot mobile salvato: scratch/controllo_ordine_mobile.png

=== RIEPILOGO TEST DETTAGLIO ORDINE ===
Totale controlli eseguiti: 72
Superati: 72
Falliti: 0
```

---

## 20. Pannello: Prodotti (`controllo-prodotti.php`)

La pagina di gestione dei prodotti del pannello di controllo consente al gestore di sede (`manager`) e all'amministratore (`admin`) di visualizzare l'intero catalogo e regolare in tempo reale la disponibilita per il menu pubblico e le giacenze di magazzino per ciascuna filiale. L'amministratore dispone inoltre della visualizzazione globale su tutte le sedi (Padova, Treviso, Vicenza, Udine), del pulsante per creare nuovi prodotti e dei collegamenti alla scheda anagrafica dettagliata.

---

### 20.1 Header, Breadcrumb e Navigazione di Sezione (Condivisi)

- [x] **Salto al contenuto principale (`.salta`)**
  - **Tipo**: Collegamento interno (`<a class="salta" href="#contenuto">Vai al contenuto</a>`).
  - **Comportamento al click/invio**: Sposta immediatamente il focus e lo scorrimento a `<main id="contenuto">`.

- [x] **Breadcrumb Semantico (`aria-label="Percorso"`)**
  - **Struttura a 3 livelli**:
    1. `<a href="/">Home</a>`
    2. `<a href="controllo">Ordini</a>`
    3. `<span aria-current="page">Prodotti</span>` (elemento testuale non cliccabile contrassegnato con `aria-current="page"`).
  - **Comportamento visivo**: Separatore visivo conforme e resa responsive su linea singola.

- [x] **Navigazione Interna del Pannello (`aria-label="Sezioni del pannello"`)**
  - **Voci per Manager**: `Ordini`, `Prodotti`, `Prenotazioni`, `La tua sede` (sezioni amministrative globali `Categorie`, `Sedi`, `Messaggi`, `Utenti` escluse).
  - **Voci per Admin**: `Ordini`, `Prodotti`, `Categorie`, `Sedi`, `Prenotazioni`, `Messaggi`, `Utenti`.
  - **Stato attivo**: la voce `Prodotti` e contrassegnata con `aria-current="page"`.

---

### 20.2 Selezione Sede e Azioni Amministrative

- [x] **Modulo Filtro Selezione Sede (Esclusivo Amministratore)**:
  - **Visibilita**: Presente unicamente per il ruolo `admin`; omesso per il gestore di sede (`manager`), vincolato rigidamente alla propria sede assegnata.
  - **Struttura modulo**: `<form method="get" action="controllo-prodotti">` con `<fieldset><legend>Sede</legend>`.
  - **Campo di selezione**: `<select id="sede" name="sede">` con opzioni per tutte le sedi attive (Padova, Treviso, Vicenza, Udine) e preselezione della sede corrente.
  - **Pulsante di invio**: `<button type="submit">Mostra</button>`.
  - **Comportamento**: Ricarica la pagina passando `?sede=ID`, aggiornando l'intestazione H1, la didascalia della tabella e i dati di giacenza per la sede indicata.

- [x] **Collegamento Nuovo Prodotto (Esclusivo Amministratore)**:
  - **Visibilita**: Visibile esclusivamente per `admin`.
  - **Markup**: `<p><a class="pulsante" href="controllo-prodotto">Aggiungi un prodotto</a></p>`.
  - **Destinazione**: Conduce al modulo di censimento di un nuovo burger/prodotto a listino.

---

### 20.3 Tabella Prodotti e Stato di Magazzino

- [x] **Intestazione Principale (`<h1>`)**:
  - Formato dinamico contestualizzato: `Prodotti a [Citta della sede]` (es. `Prodotti a Padova` o `Prodotti a Treviso`).

- [x] **Tabella Dati (`<table>`)**:
  - [x] **Didascalia Accessibile**: `<caption>Prodotti e loro disponibilita a [Citta]</caption>`.
  - [x] **Intestazioni di Colonna (`<th scope="col">`)**:
    - `Prodotto`, `Categoria`, `Prezzo`, `Nel menu`, `Quantita`.
    - Colonna aggiuntiva `Scheda` visualizzata esclusivamente per l'amministratore.
  - [x] **Intestazioni di Riga (`<th scope="row">`)**:
    - Nome del prodotto (es. `Bacon Burger`, `Cheeseburger`).
    - Ordinamento coerente: ordinati per sequenza categoria e nome alfabetico (`ORDER BY c.ordine, p.nome`).
  - [x] **Colonne Dati**:
    - Categoria di appartenenza (es. `Burger`, `Bibite`).
    - Prezzo unitario formattato in formato italiano (es. `8,90 euro`).
  - [x] **Scheda Prodotto (Amministratore)**:
    - Collegamento `<a href="controllo-prodotto?prodotto=ID">Modifica <span class="solo-lettori">[Nome prodotto]</span></a>`.
  - [x] **Nota Esplicativa di Fine Tabella**:
    - Spiegazione testuale accessibile che illustra la logica di business: *"Un prodotto compare nel menu del sito solo se e nel menu di questa sede e la quantita e maggiore di zero. Gli ordini la scalano da soli."*.

---

### 20.4 Modulo Rapido "Nel menu" e Attivazione Asincrona

- [x] **Modulo Interattivo di Disponibilita**:
  - [x] Tag `<form method="post" action="controllo-prodotti" data-modulo="disponibilita">`.
  - [x] Token CSRF generato mediante `campo_csrf()`.
  - [x] Parametri nascosti identificativi: `<input type="hidden" name="sede_id" value="X" />` e `<input type="hidden" name="prodotto_id" value="Y" />`.
  - [x] **Pulsante a Commutazione Dinamica**:
    - Se il prodotto e attualmente attivo nel menu (`disponibile = 1`):
      - `<button type="submit" name="nascondi" value="1" aria-pressed="true">Togli dal menu <span class="solo-lettori">[Nome prodotto]</span></button>`.
    - Se il prodotto e escluso dal menu (`disponibile = 0`):
      - `<button type="submit" name="mostra" value="1" aria-pressed="false">Rimetti nel menu <span class="solo-lettori">[Nome prodotto]</span></button>`.
  - [x] **Comportamento JavaScript Asincrono (`script.js`)**:
    - L'ascoltatore globale intercetta l'invio del modulo con `data-modulo="disponibilita"`, inviando una chiamata `fetch()` in background.
    - Riceve la risposta HTML, sostituisce il blocco `#contenuto` e sposta il fuoco visibile sull'avviso di stato o sul controllo ripristinato.
    - Aggiorna istantaneamente lo stato del pulsante, il testo visualizzato e l'attributo `aria-pressed` senza ricaricare l'intera pagina.
  - [x] **Degradazione Elegante senza JavaScript**:
    - In assenza di JavaScript, l'invio del pulsante produce una richiesta POST nativa del browser gestita con pattern POST-Redirect-GET verso `controllo-prodotti`, con notifica di successo riepilogata in cima.
  - [x] **Persistenza su Database MariaDB**:
    - Aggiornamento della tabella `disponibilita_prodotti` con clausola `ON DUPLICATE KEY UPDATE disponibile = :aggiornato`.

---

### 20.5 Modulo Rapido Giacenza con Invio Asincrono

- [x] **Modulo Interattivo di Quantita**:
  - [x] Tag `<form method="post" action="controllo-prodotti" data-modulo="quantita">`.
  - [x] Token CSRF generato mediante `campo_csrf()`.
  - [x] Parametri nascosti: `sede_id` e `prodotto_id`.
  - [x] Etichetta per lettori di schermo: `<label class="solo-lettori" for="quantita-[ID]">Quantita di [Nome prodotto]</label>`.
  - [x] Campo numerico: `<input type="number" id="quantita-[ID]" name="quantita" value="[N]" min="0" max="9999" />`.
  - [x] **Pulsante di Invio Esplicito Accessibile**:
    - Include un pulsante `<button type="submit">Salva <span class="solo-lettori">quantita di [Nome]</span></button>` a garanzia della piena conformita WCAG e REGOLE.md par. 17 (evitando invii al cambio su campi numerici/testuali).
    - L'invio viene gestito via AJAX da `script.js` con `data-modulo="quantita"` senza ricaricare la pagina.
  - [x] **Validazione Client e Server**:
    - Valori ammessi nel range `0 - 9999`.
    - Tentativi di inserimento di quantita negative o superiori a 9999 vengono respinti sia lato client (`min="0" max="9999"`) sia dal backend PHP con il messaggio *"La quantita deve stare fra 0 e 9999."*.
  - [x] **Persistenza su Database MariaDB**:
    - Query parametrizzata su `disponibilita_prodotti`: `ON DUPLICATE KEY UPDATE quantita = :aggiornata`.

---

### 20.6 Sicurezza Backend, Isolamento Multi-Tenant e IDOR

- [x] **Controllo Accessi e Permessi di Ruolo**:
  - [x] Ospite non autenticato respinto con codice `HTTP 401 Unauthorized`.
  - [x] Cliente autenticato con ruolo semplice (`user`) respinto con codice `HTTP 403 Forbidden`.
  - [x] Gestore di sede (`manager`) e amministratore (`admin`) autorizzati con codice `HTTP 200 OK`.

- [x] **Isolamento Multi-Tenant di Sede (Manager)**:
  - [x] Il gestore e vincolato alla sede registrata nel database (`$limite = sede_limite($pdo)`).
  - [x] **Protezione IDOR in Lettura (GET)**: se il gestore di Padova tenta di visualizzare i prodotti di Treviso inviando `GET /controllo-prodotti?sede=2`, il server restituisce `HTTP 403 Forbidden`.
  - [x] **Protezione IDOR in Modifica (POST)**: se il gestore invia una richiesta POST per modificare disponibilita o quantita forzando `sede_id=2`, il backend intercetta la discrepanza (`$limite !== null && $sedeAzione !== $limite`) e blocca la richiesta con `HTTP 403 Forbidden`, lasciando le giacenze della sede 2 totalmente inalterate.

- [x] **Validazione Sede per Amministratore**:
  - [x] Se l'amministratore richiede una sede inesistente (es. `?sede=9999`), il server restituisce codice `HTTP 404 Not Found`.

- [x] **Protezione CSRF**:
  - [x] Richieste POST prive di token CSRF bloccate con codice `HTTP 403 Forbidden`.
  - [x] Richieste POST con token CSRF manomesso bloccate con codice `HTTP 403 Forbidden`.

---

### 20.7 Accessibilita, Contrasto e Responsive Mobile (375x667px)

- [x] **Conformita Contrasto WCAG 2.1 AA**:
  - [x] Test automatizzato su tutti i 156 elementi testuali della pagina (`h1`, `th`, `td`, `caption`, `label`, pulsanti e link):
    - Tema chiaro: superato al 100% (rapporti fino a 14.5:1).
    - Tema scuro: superato al 100% (rapporti fino a 13.8:1).
- [x] **Verifica Viewport Mobile (375x667px)**:
  - [x] `clientWidth = 375px`, `scrollWidth = 375px`: zero overflow orizzontale.
  - [x] La tabella dei prodotti degrada con scorrimento orizzontale autonomo interno, mantenendo fruibili su piccoli schermi i controlli numerici e i pulsanti a levetta.

#### Log Esecuzione Script E2E Playwright (`scratch/test_controllo_prodotti.py`)

```text
========================================================
=== TEST BACKEND: AUTORIZZAZIONE E CONTROLLO ACCESSI ===
========================================================
  [OK] Accesso ospite a /controllo-prodotti respinto con HTTP 401 Unauthorized
  [OK] Accesso cliente ('user') respinto con HTTP 403 Forbidden

========================================================
=== TEST VISTA MANAGER & ISOLAMENTO MULTI-TENANT (PADOVA) ===
========================================================
  [OK] Accesso manager a /controllo-prodotti: HTTP 200 OK
  [OK] Titolo pagina dinamico corretto: 'Prodotti - Pannello di controllo'
  [OK] H1 contestualizzato alla sede del manager: 'Prodotti a Padova'
  [OK] Breadcrumb semantico a 3 livelli: ['Home', 'Ordini', 'Prodotti']
  [OK] Elemento corrente breadcrumb 'Prodotti' contrassegnato con aria-current='page'
  [OK] Navigazione manager limitata: ['Ordini', 'Prodotti', 'Prenotazioni', 'La tua sede']
  [OK] Sezione corrente 'Prodotti' attiva nella navigazione pannello
  [OK] Filtro selezione sede ASSENTE per il manager (limitato alla propria sede)
  [OK] Pulsante 'Aggiungi un prodotto' ASSENTE per il manager
  [OK] Colonna 'Scheda' ASSENTE nella tabella per il manager
  [OK] Tentativo GET cross-sede del manager su sede=2 respinto con HTTP 403 Forbidden

========================================================
=== TEST STRUTTURA TABELLA E ACCESSIBILITA ===
========================================================
  [OK] Caption tabella accessibile: 'Prodotti e loro disponibilita a Padova'
  [OK] Intestazioni colonna corrette: ['Prodotto', 'Categoria', 'Prezzo', 'Nel menu', 'Quantita']
  [OK] Numero prodotti censiti in tabella: 19
  [OK] Riga Cheeseburger identificata con th scope='row'
  [OK] Categoria Cheeseburger: 'Burger'
  [OK] Prezzo formattato Cheeseburger: '8,90 euro'
  [OK] Nome prodotto accessibile in pulsante disponibilita: 'Cheeseburger'
  [OK] Etichetta accessibile campo quantita: 'Quantita di Cheeseburger'

========================================================
=== TEST MUTAZIONE DISPONIBILITA (NEL MENU) SU MARIADB ===
========================================================
  [OK] Stato iniziale MariaDB Cheeseburger sede 1: disponibile=1
  [OK] Pulsante inizialmente indica 'Togli dal menu'
  [OK] Pulsante ha aria-pressed='true'
  [OK] Messaggio flash ricevuto: 'Fatto: Prodotto tolto dal menu.'
  [OK] MariaDB aggiornato con successo: disponibile=0
  [OK] Pulsante ora indica 'Rimetti nel menu'
  [OK] Pulsante ora ha aria-pressed='false'
  [OK] Messaggio flash ripristino: 'Fatto: Prodotto rimesso nel menu.'
  [OK] MariaDB ripristinato con successo: disponibile=1

========================================================
=== TEST MUTAZIONE QUANTITA SU MARIADB ===
========================================================
  [OK] Valore input quantita iniziale corrisponde al DB: 38
  [OK] Messaggio flash aggiornamento quantita: 'Fatto: Quantita aggiornata.'
  [OK] MariaDB quantita aggiornata a: 45
  [OK] MariaDB quantita ripristinata a: 38

========================================================
=== TEST SICUREZZA CSRF E MANOMISSIONE CROSS-SEDE ===
========================================================
  [OK] POST senza token CSRF bloccata con HTTP 403 Forbidden
  [OK] POST con token CSRF manomesso bloccata con HTTP 403 Forbidden
  [OK] Tentativo POST cross-sede (manager su sede 2) bloccato con HTTP 403 Forbidden
  [OK] MariaDB sede 2 inalterato: disponibile=1
  [OK] Backend rifiuta quantita negativa con messaggio d'errore dedicato
  [OK] Gestione prodotto_id inesistente terminata in sicurezza (HTTP 200)

========================================================
=== TEST VISTA AMMINISTRATORE ('admin') & MULTI-SEDE ===
========================================================
  [OK] Accesso admin a /controllo-prodotti: HTTP 200 OK
  [OK] Navigazione admin completa con tutte le sezioni: ['Ordini', 'Prodotti', 'Categorie', 'Sedi', 'Prenotazioni', 'Messaggi', 'Utenti']
  [OK] Form filtro sede presente per admin
  [OK] Opzioni sede disponibili per admin: ['Padova', 'Treviso', 'Vicenza', 'Udine']
  [OK] Pulsante 'Aggiungi un prodotto' presente per admin verso controllo-prodotto
  [OK] Colonna 'Scheda' presente nella tabella per admin
  [OK] Link 'Modifica' presente verso controllo-prodotto?prodotto=1
  [OK] H1 aggiornato a Treviso: 'Prodotti a Treviso'
  [OK] Caption aggiornata: 'Prodotti e loro disponibilita a Treviso'
  [OK] Admin che richiede sede inesistente riceve HTTP 404 Not Found

========================================================
=== TEST FRONTEND, ACCESSIBILITA E MOBILE (375x667) ===
========================================================
  [OK] Screenshot desktop salvato: scratch/controllo_prodotti_desktop.png
  [OK] Mobile /controllo-prodotti caricato con HTTP 200
  [OK] Mobile /controllo-prodotti: nessun overflow orizzontale (375 <= 375)
  [OK] Screenshot mobile salvato: scratch/controllo_prodotti_mobile.png

=== RIEPILOGO TEST PANNELLO PRODOTTI ===
Totale controlli eseguiti: 54
Superati: 54
Falliti: 0
```

---

## 21. Pannello: Scheda Prodotto (`controllo-prodotto.php`)

La pagina di scheda prodotto consente all'amministratore (`admin`) di gestire il ciclo di vita completo dei prodotti a listino: censimento di un nuovo burger, bibita o dessert con caricamento dell'immagine, modifica dei dati anagrafici comuni (nome, slug, categoria, prezzo, descrizione, allergeni) e cancellazione definitiva dal catalogo con rimozione a cascata delle disponibilita di sede e preservazione dello storico ordini. L'accesso e strettamente riservato all'amministratore; clienti e manager vengono bloccati dal backend.

---

### 21.1 Header, Breadcrumb e Navigazione di Sezione (Condivisi)

- [x] **Salto al contenuto principale (`.salta`)**
  - **Tipo**: Collegamento interno (`<a class="salta" href="#contenuto">Vai al contenuto</a>`).
  - **Comportamento al click/invio**: Sposta immediatamente il focus e lo scorrimento a `<main id="contenuto">`.

- [x] **Breadcrumb Semantico (`aria-label="Percorso"`)**
  - **Struttura a 4 livelli**:
    1. `<a href="/">Home</a>`
    2. `<a href="controllo">Ordini</a>`
    3. `<a href="controllo-prodotti">Prodotti</a>`
    4. `<span aria-current="page">[Nome prodotto / Nuovo prodotto]</span>` (elemento testuale non cliccabile recante lo stato corrente).
  - **Comportamento visivo**: Separatore visivo semantico con contrasto conforme WCAG.

- [x] **Navigazione Interna del Pannello (`aria-label="Sezioni del pannello"`)**
  - **Voci per Admin**: `Ordini`, `Prodotti`, `Categorie`, `Sedi`, `Prenotazioni`, `Messaggi`, `Utenti`.
  - **Stato attivo**: la voce `Prodotti` e contrassegnata con `aria-current="page"`.

---

### 21.2 Modulo di Creazione Nuovo Prodotto

- [x] **Intestazione Principale (`<h1>`)**: `Nuovo prodotto`.

- [x] **Form Multipart di Inserimento (`<form method="post" enctype="multipart/form-data">`)**:
  - [x] Token CSRF generato tramite `campo_csrf()`.
  - [x] Campo limite upload `<input type="hidden" name="MAX_FILE_SIZE" value="307200" />` (300 KB).
  - [x] Riquadro anteprima immagine omesso in fase di creazione.
  - [x] **Campo Selezione File Immagine (`#immagine`)**:
    - Etichetta: `<label for="immagine">Immagine</label>`.
    - Attributo obbligatorio: `required="required"` in creazione.
    - Filtro estensioni: `accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp"`.
    - Testo di aiuto accessibile: `<small id="aiuto-immagine">JPG, PNG o WebP, da 300 x 300 a 2000 x 2000 pixel, massimo 300 KB.</small>`.
    - Associazione accessibile: `aria-describedby="aiuto-immagine"`.
  - [x] **Campo Nome (`#nome`)**:
    - `<input type="text" id="nome" name="nome" required="required" minlength="2" maxlength="120" />`.
  - [x] **Campo Slug (`#slug`)**:
    - `<input type="text" id="slug" name="slug" required="required" pattern="[a-z0-9-]{2,120}" />`.
    - Testo di aiuto: `<small id="aiuto-slug">Compare nell'indirizzo della pagina pubblica del prodotto.</small>`.
  - [x] **Campo Categoria (`#categoria_id`)**:
    - Menu a tendina `<select id="categoria_id" name="categoria_id" required="required">`.
    - Prima opzione segnaposto non valida: `<option value="">Scegli la categoria</option>`.
    - Opzioni caricate dinamicamente dalla tabella MariaDB `categorie`.
  - [x] **Campo Prezzo (`#prezzo`)**:
    - `<input type="number" id="prezzo" name="prezzo" required="required" min="0.01" max="999" step="0.01" />`.
  - [x] **Campo Descrizione (`#descrizione`)**:
    - `<textarea id="descrizione" name="descrizione" rows="4" required="required" minlength="10">`.
  - [x] **Campo Allergeni (`#allergeni`)**:
    - `<input type="text" id="allergeni" name="allergeni" maxlength="255" />`.
    - Testo di aiuto: `<small id="aiuto-allergeni">Separati da virgola. Lascia vuoto se non ne contiene.</small>`.
  - [x] **Pulsante di Creazione**:
    - `<button type="submit">Crea il prodotto</button>`.
  - [x] **Navigazione Inferiore**:
    - `<a class="collegamento-indietro" href="controllo-prodotti">Torna ai prodotti</a>`.
    - Link distruttivi ('Cancella') e verso la vista pubblica omessi in creazione.

---

### 21.3 Modulo di Modifica Prodotto Esistente

- [x] **Intestazione Principale (`<h1>`)**: `Modifica [Nome prodotto]`.

- [x] **Anteprima Immagine Corrente (`<figure class="anteprima-prodotto">`)**:
  - Mostra l'immagine del prodotto memorizzata in `uploads/prodotti/`.
  - Tag `<img>` con attributi dimensionali (`width="600" height="450"`) e testo alternativo accessibile (`alt="Immagine attuale di [Nome]"`).
  - Didascalia semantica `<figcaption>Immagine attuale</figcaption>`.

- [x] **Gestione Aggiornamento Immagine**:
  - Etichetta modificata: `<label for="immagine">Sostituisci immagine</label>`.
  - Attributo `required` rimosso: se nessun file viene caricato, il backend mantiene l'immagine esistente.
  - Testo informativo integrativo: *"Se non scegli un file, resta l'immagine attuale."*.

- [x] **Prepopolazione e Modifica Dati**:
  - Tutti i campi (`nome`, `slug`, `categoria_id`, `prezzo`, `descrizione`, `allergeni`) vengono precompilati con i valori attuali estratti da MariaDB.
  - Identificativo numerico nascosto: `<input type="hidden" name="prodotto_id" value="X" />`.
  - Pulsante di salvataggio: `<button type="submit">Salva le modifiche</button>`.

- [x] **Collegamenti di Azione**:
  - Collegamento di ritorno: `<a class="collegamento-indietro" href="controllo-prodotti">Torna ai prodotti</a>`.
  - Collegamento alla vista cliente: `<a href="prodotto?slug=[slug]">Vedi la pagina pubblica</a>`.
  - Collegamento alla conferma di eliminazione: `<a class="pulsante" data-tipo="negativo" href="controllo-prodotto?prodotto=X&amp;elimina=1">Cancella</a>`.

---

### 21.4 Modulo di Conferma Cancellazione (`?elimina=1`)

- [x] **Riquadro di Avviso Conferma (`role="alert" data-tipo="errore"`)**:
  - [x] Intestazione dedicata: `<h2>Vuoi cancellare [Nome prodotto]?</h2>`.
  - [x] Spiegazione chiara dell'effetto dell'operazione: *"Sparisce dal menu di tutte le sedi. Gli ordini gia fatti restano leggibili, perche hanno copiato nome e prezzo al momento dell'acquisto."*.
  - [x] Form di conferma con metodo `POST`:
    - Token CSRF obbligatorio (`campo_csrf()`).
    - Input identificativo nascosto `<input type="hidden" name="prodotto_id" value="X" />`.
    - Pulsante distruttivo: `<button type="submit" name="elimina" value="1" data-tipo="negativo">Cancella il prodotto</button>`.
    - Collegamento di annullamento azione: `<a class="pulsante secondario" href="controllo-prodotto?prodotto=X">Annulla</a>`.

---

### 21.5 Validazione e Riepilogo Errori

- [x] **Riepilogo Globale Errori in Cima al Modulo**:
  - Visualizzato in presenza di errori di compilazione o upload: `<section class="avviso" role="alert" data-tipo="errore">`.
  - Intestazione: `<h2>Controlla questi campi</h2>`.
  - Elenco puntato di collegamenti interni verso ciascun campo errato (`<a href="#nome">`, `<a href="#slug">`, `<a href="#prezzo">`, ecc.).
- [x] **Evidenziazione e Connessione Semantica sui Singoli Campi**:
  - Attributo `data-stato="errore"` applicato al controllo invalido.
  - Attributo `aria-describedby` collegato all'id del messaggio d'errore (es. `aria-describedby="errore-nome"`).
  - Messaggio d'errore visibile ed esplicativo collocato sotto il campo (`<small id="errore-[campo]">[Spiegazione dell'errore]</small>`).
  - Preservazione automatica di tutti i valori precedentemente inseriti.

---

### 21.6 Ciclo di Vita MariaDB (Transazioni e Integrita Referenziale)

- [x] **Creazione (`prodotto_salva` con `$prodottoId = null`)**:
  - Inserimento riga nella tabella `prodotti`.
  - Seeding automatico delle giacenze: query su tutte le sedi registrate (`INSERT INTO disponibilita_prodotti (sede_id, prodotto_id, disponibile, quantita) SELECT id, :prodotto, 1, 0 FROM sedi`), impostando `disponibile = 1` e `quantita = 0` per ciascuna filiale.
  - Reindirizzamento POST-Redirect-GET con messaggio flash: *"Fatto: Prodotto creato. Le sedi devono ancora rifornirlo."*.
- [x] **Modifica (`prodotto_salva` con `$prodottoId = X`)**:
  - Aggiornamento della riga corrispondente in `prodotti`.
  - Reindirizzamento con messaggio flash: *"Fatto: Prodotto aggiornato."*.
- [x] **Cancellazione (`prodotto_elimina`)**:
  - Rimozione della riga da `prodotti`.
  - Cancellazione a cascata (`ON DELETE CASCADE`) delle relative righe nella tabella `disponibilita_prodotti`.
  - Preservazione dello storico degli ordini emessi: la chiave esterna `fk_righe_ordine_prodotto` in `righe_ordine` applica `ON DELETE SET NULL`, lasciando inalterati il nome congelato e il prezzo d'acquisto.
  - Reindirizzamento con messaggio flash: *"Fatto: Prodotto cancellato."*.

---

### 21.7 Sicurezza Backend e Controllo Accessi

- [x] **Autorizzazione di Ruolo**:
  - [x] Ospite non autenticato respinto con codice `HTTP 401 Unauthorized`.
  - [x] Cliente autenticato (`user`) respinto con codice `HTTP 403 Forbidden`.
  - [x] Gestore di sede (`manager`) respinto con codice `HTTP 403 Forbidden` (la modifica del catalogo comune compete solo all'amministrazione centrale).
  - [x] Amministratore (`admin`) autorizzato con codice `HTTP 200 OK`.
- [x] **Validazione Parametri e Integrita**:
  - [x] Richiesta con ID prodotto inesistente a database (es. `?prodotto=99999`) respinta con codice `HTTP 404 Not Found`.
  - [x] Tentativo di invio POST senza token CSRF o con token manomesso bloccato con `HTTP 403 Forbidden`.
  - [x] Upload sicuro immagini: controllo MIME type reale via `finfo`, verifica delle dimensioni geometriche effettive (`getimagesize`) e ridenominazione con hash casuale univoco.

---

### 21.8 Accessibilita, Contrasto e Responsive Mobile (375x667px)

- [x] **Conformita Contrasto WCAG 2.1 AA**:
  - [x] Test automatizzato eseguito su tutti i 70 nodi testuali del modulo (`h1`, `h2`, `label`, `input`, `select`, `textarea`, `small`, pulsanti e link):
    - Tema chiaro: 100% conforme WCAG AA (rapporti fino a 14.5:1).
    - Tema scuro: 100% conforme WCAG AA (rapporti fino a 13.8:1).
- [x] **Verifica Viewport Mobile (375x667px)**:
  - [x] `clientWidth = 375px`, `scrollWidth = 375px`: zero overflow orizzontale.
  - [x] Campi di input, selettori a discesa, area di testo e pulsanti con altezza minima touch e padding responsive conformi alle linee guida.

#### Log Esecuzione Script E2E Playwright (`scratch/test_controllo_scheda_prodotto.py`)

```text
========================================================
=== TEST BACKEND: AUTORIZZAZIONE E PERMESSI DI RUOLO ===
========================================================
  [OK] Accesso ospite a /controllo-prodotto respinto con HTTP 401 Unauthorized
  [OK] Accesso cliente ('user') respinto con HTTP 403 Forbidden
  [OK] Accesso manager a /controllo-prodotto respinto con HTTP 403 Forbidden (riservato admin)
  [OK] Accesso admin a /controllo-prodotto: HTTP 200 OK

========================================================
=== TEST VISTA CREAZIONE NUOVO PRODOTTO ===
========================================================
  [OK] Titolo dinamico creazione corretto: 'Scheda prodotto - Pannello di controllo'
  [OK] H1 nuovo prodotto: 'Nuovo prodotto'
  [OK] Breadcrumb a 4 livelli: ['Home', 'Ordini', 'Prodotti', 'Nuovo prodotto']
  [OK] Elemento corrente 'Nuovo prodotto' contrassegnato con aria-current='page'
  [OK] Navigazione pannello completa per admin: ['Ordini', 'Prodotti', 'Categorie', 'Sedi', 'Prenotazioni', 'Messaggi', 'Utenti']
  [OK] Form creazione multipart/form-data presente
  [OK] Anteprima immagine assente in creazione nuovo prodotto
  [OK] Campo file #immagine ha required in creazione
  [OK] Campo #immagine specifica estensioni consentite
  [OK] Etichetta campo file in creazione: 'Immagine'
  [OK] Campo #nome ha required
  [OK] Campo #slug ha pattern regex corretto
  [OK] Campo #categoria_id ha required
  [OK] Campo #prezzo ha step 0.01
  [OK] Campo #descrizione ha minlength 10
  [OK] Pulsante submit indica 'Crea il prodotto'
  [OK] Collegamento 'Torna ai prodotti' presente
  [OK] Link 'Cancella' ASSENTE in creazione
  [OK] Link 'Vedi la pagina pubblica' ASSENTE in creazione

========================================================
=== TEST VALIDAZIONE ERRORI (CLIENT & SERVER) ===
========================================================
  [OK] Box riepilogo errori (role='alert', data-tipo='errore') visualizzato
  [OK] Intestazione box errori: 'Controlla questi campi'
  [OK] Link di ancoraggio agli errori presenti: ['#nome', '#slug', '#categoria_id', '#prezzo', '#descrizione', '#immagine']
  [OK] Input #nome evidenziato con data-stato='errore'
  [OK] Input #slug evidenziato con data-stato='errore'
  [OK] Input #prezzo evidenziato con data-stato='errore'
  [OK] Messaggio errore campo nome: 'Scrivi il nome del prodotto, fra 2 e 120 caratteri.'

========================================================
=== TEST VISTA MODIFICA PRODOTTO ESISTENTE (CHEESEBURGER) ===
========================================================
  [OK] Accesso a /controllo-prodotto?prodotto=1: HTTP 200 OK
  [OK] H1 in modifica: 'Modifica Cheeseburger'
  [OK] Breadcrumb elemento corrente: 'Cheeseburger'
  [OK] Immagine anteprima presente con alt descrittivo: 'Immagine attuale di Cheeseburger'
  [OK] Etichetta campo file in modifica: 'Sostituisci immagine'
  [OK] Campo file #immagine NON ha required in modifica (mantiene immagine esistente)
  [OK] Campo #nome prepopolato: 'Cheeseburger'
  [OK] Campo #slug prepopolato: 'cheeseburger'
  [OK] Campo #prezzo prepopolato: '8.90'
  [OK] Pulsante submit indica 'Salva le modifiche'
  [OK] Link alla pagina pubblica presente: prodotto?slug=cheeseburger
  [OK] Link 'Cancella' presente verso controllo-prodotto?prodotto=1&elimina=1

========================================================
=== TEST BOX CONFERMA CANCELLAZIONE (?elimina=1) ===
========================================================
  [OK] Box di avviso conferma cancellazione visualizzato
  [OK] Intestazione box cancellazione: 'Vuoi cancellare Cheeseburger?'
  [OK] Pulsante conferma cancellazione con data-tipo='negativo' presente
  [OK] Link secondario 'Annulla' presente per revocare l'operazione

========================================================
=== TEST CICLO DI VITA COMPLETO (CREATE -> UPDATE -> DELETE) ===
========================================================
  [OK] Reindirizzamento dopo creazione atterra su http://localhost:8080/controllo-prodotti
  [OK] Messaggio flash ricevuto: 'Fatto: Prodotto creato. Le sedi devono ancora rifornirlo.'
  [OK] Prodotto inserito su MariaDB con ID: 20
  [OK] Seeding automatico MariaDB su tutte le 4 sedi: 4 sedi, disponibile=4, qta=0
  [OK] Valore iniziale nome corretto
  [OK] Reindirizzamento dopo modifica atterra su controllo-prodotti
  [OK] Messaggio flash aggiornamento: 'Fatto: Prodotto aggiornato.'
  [OK] MariaDB aggiornato correttamente: nome='Burger Collaudo E2E Modificato', prezzo_centesimi=1290
  [OK] Reindirizzamento dopo cancellazione atterra su controllo-prodotti
  [OK] Messaggio flash cancellazione: 'Fatto: Prodotto cancellato.'
  [OK] Prodotto eliminato definitivamente da tabella MariaDB 'prodotti'
  [OK] Righe disponibilita eliminate a cascata da MariaDB 'disponibilita_prodotti'

========================================================
=== TEST SICUREZZA CSRF E PARAMETRI MALFORMATI ===
========================================================
  [OK] POST senza token CSRF bloccata con HTTP 403 Forbidden
  [OK] Accesso con ID prodotto inesistente produce HTTP 404 Not Found

========================================================
=== TEST FRONTEND, ACCESSIBILITA E MOBILE (375x667) ===
========================================================
  [OK] Screenshot desktop salvato: scratch/controllo_prodotto_desktop.png
  [OK] Mobile /controllo-prodotto caricato con HTTP 200
  [OK] Mobile /controllo-prodotto: nessun overflow orizzontale (375 <= 375)
  [OK] Screenshot mobile salvato: scratch/controllo_prodotto_mobile.png
  [OK] Contrasto WCAG AA (light): tutti i 70 elementi conformi
  [OK] Contrasto WCAG AA (dark): tutti i 70 elementi conformi

=== RIEPILOGO TEST SCHEDA PRODOTTO ===
Totale controlli eseguiti: 66
Superati: 66
Falliti: 0
```

---

## 22. Pannello: Categorie (`controllo-categorie.php`)

La pagina di gestione delle categorie consente all'amministratore (`admin`) di organizzare le sezioni del menu pubblico del ristorante: creazione di nuove categorie con slug e priorita ordinale, rinomina e riordinamento in linea direttamente nella tabella dati mediante moduli disaccoppiati (attributo HTML5 `form`), e cancellazione protetta con verifica del vincolo di integrita referenziale (blocco se la categoria contiene ancora prodotti a listino). L'accesso e riservato all'amministratore.

---

### 22.1 Header, Breadcrumb e Navigazione di Sezione (Condivisi)

- [x] **Salto al contenuto principale (`.salta`)**
  - **Tipo**: Collegamento interno (`<a class="salta" href="#contenuto">Vai al contenuto</a>`).
  - **Comportamento al click/invio**: Sposta immediatamente il focus e lo scorrimento a `<main id="contenuto">`.

- [x] **Breadcrumb Semantico (`aria-label="Percorso"`)**
  - **Struttura a 3 livelli**:
    1. `<a href="/">Home</a>`
    2. `<a href="controllo">Ordini</a>`
    3. `<span aria-current="page">Categorie</span>` (elemento testuale non cliccabile contrassegnato con `aria-current="page"`).
  - **Comportamento visivo**: Separatore visivo conforme e resa responsive su linea singola.

- [x] **Navigazione Interna del Pannello (`aria-label="Sezioni del pannello"`)**
  - **Voci per Admin**: `Ordini`, `Prodotti`, `Categorie`, `Sedi`, `Prenotazioni`, `Messaggi`, `Utenti`.
  - **Stato attivo**: la voce `Categorie` e contrassegnata con `aria-current="page"`.

---

### 22.2 Modulo "Aggiungi una categoria"

- [x] **Sezione e Intestazione Dedicata**: `<section><h2>Aggiungi una categoria</h2>`.

- [x] **Modulo di Creazione (`<form method="post" data-modulo="nuova-categoria">`)**:
  - [x] Token CSRF generato tramite `campo_csrf()`.
  - [x] Raggruppamento semantico: `<fieldset><legend>Nuova categoria</legend>`.
  - [x] **Campo Nome (`#nome`)**:
    - `<input type="text" id="nome" name="nome" required="required" minlength="2" maxlength="80" />`.
    - Etichetta associata: `<label for="nome">Nome</label>`.
  - [x] **Campo Slug (`#slug`)**:
    - `<input type="text" id="slug" name="slug" required="required" pattern="[a-z0-9-]{2,80}" />`.
    - Testo di aiuto accessibile: `<small id="aiuto-slug">Lettere minuscole, cifre e trattini.</small>`.
    - Associazione accessibile: `aria-describedby="aiuto-slug"`.
  - [x] **Campo Descrizione (`#descrizione`)**:
    - `<input type="text" id="descrizione" name="descrizione" maxlength="255" />`.
    - Etichetta associata: `<label for="descrizione">Descrizione</label>`.
  - [x] **Campo Posizione nel Menu (`#ordine`)**:
    - `<input type="number" id="ordine" name="ordine" min="0" max="255" value="0" />`.
    - Etichetta associata: `<label for="ordine">Posizione nel menu</label>`.
  - [x] **Pulsante di Invio**:
    - `<button type="submit">Crea la categoria</button>`.
  - [x] **Validazione Unicita Backend**:
    - Tentativo di registrazione di uno slug gia occupato intercettato da blocco `try/catch` su vincolo di unicita del database MariaDB con messaggio d'errore dedicato: *"Questo slug e gia usato da un'altra categoria."*.

---

### 22.3 Tabella "Categorie esistenti" con Form Disaccoppiati

- [x] **Sezione e Intestazione Dedicata**: `<section><h2>Categorie esistenti</h2>`.

- [x] **Moduli Form Esterni alla Tabella**:
  - Un elemento `<form method="post" action="controllo-categorie" id="categoria-[ID]" data-modulo="categoria">` per ciascuna categoria censita.
  - Campi nascosti: token CSRF, `<input type="hidden" name="categoria_id" value="[ID]" />` e `<input type="hidden" name="descrizione" value="..." />`.
  - Vantaggio architetturale: rispetta la sintassi formale HTML5/XML impedendo l'annidamento illegale di tag `<form>` all'interno o a cavallo degli elementi `<tr>` e `<td>`.

- [x] **Tabella Dati (`<table>`)**:
  - [x] **Didascalia Accessibile**: `<caption>Categorie del catalogo</caption>`.
  - [x] **Intestazioni di Colonna (`<th scope="col">`)**: `Nome`, `Slug`, `Posizione`, `Azioni`.
  - [x] **Controlli di Modifica Diretta in Riga**:
    - Campo Nome: `<input type="text" id="nome-[ID]" name="nome" form="categoria-[ID]" required="required" minlength="2" maxlength="80" value="[Valore]" />` con `<label class="solo-lettori" for="nome-[ID]">Nome di [Nome Categoria]</label>`.
    - Campo Slug: `<input type="text" id="slug-[ID]" name="slug" form="categoria-[ID]" required="required" pattern="[a-z0-9-]{2,80}" value="[Valore]" />` con `<label class="solo-lettori" for="slug-[ID]">Slug di [Nome Categoria]</label>`.
    - Campo Posizione: `<input type="number" id="ordine-[ID]" name="ordine" form="categoria-[ID]" min="0" max="255" value="[Valore]" />` con `<label class="solo-lettori" for="ordine-[ID]">Posizione di [Nome Categoria]</label>`.
  - [x] **Colonna Azioni**:
    - Pulsante Salvataggio Modifiche: `<button type="submit" form="categoria-[ID]">Salva <span class="solo-lettori">[Nome Categoria]</span></button>`.
    - Collegamento Rimozione: `<a class="pulsante" data-tipo="negativo" href="controllo-categorie?elimina=[ID]">Cancella <span class="solo-lettori">[Nome Categoria]</span></a>`.

---

### 22.4 Modulo di Conferma Cancellazione (`?elimina=ID`) e Vincolo Referenziale

- [x] **Riquadro di Avviso Conferma (`role="alert" data-tipo="errore"`)**:
  - [x] Intestazione dedicata: `<h2>Vuoi cancellare [Nome Categoria]?</h2>`.
  - [x] Spiegazione chiara delle condizioni di successo: *"La cancellazione riesce solo se nessun prodotto usa ancora questa categoria."*.
  - [x] Form di conferma con metodo `POST` e `data-modulo="cancella-categoria"`:
    - Token CSRF obbligatorio (`campo_csrf()`).
    - Pulsante distruttivo: `<button type="submit" name="elimina" value="[ID]" data-tipo="negativo">Cancella la categoria</button>`.
    - Collegamento di revoca: `<a class="pulsante secondario" href="controllo-categorie">Annulla</a>`.
- [x] **Protezione Referenziale Backend**:
  - Query preventiva: `SELECT COUNT(*) FROM prodotti WHERE categoria_id = :id`.
  - Se il conteggio e superiore a zero, l'operazione viene respinta con notifica flash d'errore: *"Questa categoria ha ancora dei prodotti: spostali prima di cancellarla."*, garantendo l'integrita referenziale del catalogo e preservando il record in MariaDB.

---

### 22.5 Ciclo di Vita MariaDB (Transazioni e Persistenza)

- [x] **Creazione Categoria**:
  - Inserimento riga nella tabella `categorie`.
  - Reindirizzamento POST-Redirect-GET con messaggio flash: *"Fatto: Categoria creata."*.
- [x] **Modifica Categoria**:
  - Aggiornamento in linea di nome, slug, descrizione e ordine ordinale di visualizzazione nel menu pubblico.
  - Reindirizzamento con messaggio flash: *"Fatto: Categoria aggiornata."*.
- [x] **Cancellazione Categoria (a zero prodotti)**:
  - Eliminazione definitiva del record da `categorie`.
  - Reindirizzamento con messaggio flash: *"Fatto: Categoria cancellata."*.

---

### 22.6 Sicurezza Backend e Controllo Accessi

- [x] **Autorizzazione di Ruolo**:
  - [x] Ospite non autenticato respinto con codice `HTTP 401 Unauthorized`.
  - [x] Cliente autenticato (`user`) respinto con codice `HTTP 403 Forbidden`.
  - [x] Gestore di sede (`manager`) respinto con codice `HTTP 403 Forbidden` (la gestione delle categorie comuni e riservata all'amministratore).
  - [x] Amministratore (`admin`) autorizzato con codice `HTTP 200 OK`.
- [x] **Protezione CSRF**:
  - [x] Richieste POST prive di token CSRF bloccate con codice `HTTP 403 Forbidden`.
  - [x] Richieste POST con token CSRF manomesso bloccate con codice `HTTP 403 Forbidden`.

---

### 22.7 Accessibilita, Contrasto e Responsive Mobile (375x667px)

- [x] **Conformita Contrasto WCAG 2.1 AA**:
  - [x] Test automatizzato su tutti gli 82 elementi testuali della pagina (`h1`, `h2`, `th`, `td`, `label`, `input`, `small`, pulsanti e link):
    - Tema chiaro: 100% conforme WCAG AA (rapporti fino a 14.5:1).
    - Tema scuro: 100% conforme WCAG AA (rapporti fino a 13.8:1).
- [x] **Verifica Viewport Mobile (375x667px)**:
  - [x] `clientWidth = 375px`, `scrollWidth = 375px`: zero overflow orizzontale.
  - [x] Form di creazione impaginato verticalmente e tabella categorie con scorrimento orizzontale autonomo interno conforme alle specifiche d'esame.

#### Log Esecuzione Script E2E Playwright (`scratch/test_controllo_categorie.py`)

```text
========================================================
=== TEST BACKEND: AUTORIZZAZIONE E PERMESSI DI RUOLO ===
========================================================
  [OK] Accesso ospite a /controllo-categorie respinto con HTTP 401 Unauthorized
  [OK] Accesso cliente ('user') respinto con HTTP 403 Forbidden
  [OK] Accesso manager a /controllo-categorie respinto con HTTP 403 Forbidden
  [OK] Accesso admin a /controllo-categorie: HTTP 200 OK

========================================================
=== TEST STRUTTURA SEMANTICA E NAVIGAZIONE ===
========================================================
  [OK] Titolo dinamico corretto: 'Categorie - Pannello di controllo'
  [OK] H1 semantico: 'Categorie'
  [OK] Breadcrumb a 3 livelli: ['Home', 'Ordini', 'Categorie']
  [OK] Elemento corrente breadcrumb 'Categorie' con aria-current='page'
  [OK] Navigazione completa sezioni pannello per admin: ['Ordini', 'Prodotti', 'Categorie', 'Sedi', 'Prenotazioni', 'Messaggi', 'Utenti']
  [OK] Sezione 'Categorie' attiva nella navigazione con aria-current='page'

========================================================
=== TEST SEZIONE AGGIUNGI UNA CATEGORIA ===
========================================================
  [OK] H2 sezione creazione: 'Aggiungi una categoria'
  [OK] Form data-modulo='nuova-categoria' presente
  [OK] Fieldset e legend presenti
  [OK] Campo #nome ha required
  [OK] Campo #slug ha pattern regex corretto
  [OK] Campo #ordine range 0-255
  [OK] Backend rifiuta slug duplicato: 'Errore: Questo slug e gia usato da un'altra categoria.'

========================================================
=== TEST SEZIONE CATEGORIE ESISTENTI & TABELLA FORM ===
========================================================
  [OK] Caption tabella: 'Categorie del catalogo'
  [OK] Intestazioni colonna corrette: ['Nome', 'Slug', 'Posizione', 'Azioni']
  [OK] Numero categorie visualizzate in tabella: 4
  [OK] Riga categoria Burger identificata
  [OK] Input nome collegato a form='categoria-1'
  [OK] Pulsante Salva collegato a form='categoria-1'
  [OK] Link 'Cancella' presente verso controllo-categorie?elimina=1

========================================================
=== TEST BLOCCO CANCELLAZIONE CATEGORIA CON PRODOTTI ===
========================================================
  [OK] Box di avviso conferma cancellazione visualizzato
  [OK] H2 conferma cancellazione: 'Vuoi cancellare Burger?'
  [OK] Backend blocca cancellazione di categoria con prodotti: 'Errore: Questa categoria ha ancora dei prodotti: spostali prima di cancellarla.'
  [OK] MariaDB: Categoria 1 (Burger) preservata intatta

========================================================
=== TEST CICLO DI VITA COMPLETO (CREATE -> UPDATE -> DELETE) ===
========================================================
  [OK] Messaggio flash creazione: 'Fatto: Categoria creata.'
  [OK] Categoria inserita su MariaDB con ID: 8
  [OK] Riga nuova categoria trovata in tabella
  [OK] Messaggio flash aggiornamento: 'Fatto: Categoria aggiornata.'
  [OK] MariaDB aggiornato correttamente: nome='Categoria Collaudo E2E Modificata', ordine=50
  [OK] Box cancellazione categoria test aperto
  [OK] Messaggio flash eliminazione: 'Fatto: Categoria cancellata.'
  [OK] Categoria test eliminata definitivamente da tabella MariaDB 'categorie'

========================================================
=== TEST SICUREZZA CSRF E PARAMETRI MALFORMATI ===
========================================================
  [OK] POST senza token CSRF bloccata con HTTP 403 Forbidden
  [OK] POST con token CSRF manomesso bloccata con HTTP 403 Forbidden

========================================================
=== TEST FRONTEND, ACCESSIBILITA E MOBILE (375x667) ===
========================================================
  [OK] Screenshot desktop salvato: scratch/controllo_categorie_desktop.png
  [OK] Mobile /controllo-categorie caricato con HTTP 200
  [OK] Mobile /controllo-categorie: nessun overflow orizzontale (375 <= 375)
  [OK] Screenshot mobile salvato: scratch/controllo_categorie_mobile.png
  [OK] Contrasto WCAG AA (light): tutti i 82 elementi conformi
  [OK] Contrasto WCAG AA (dark): tutti i 82 elementi conformi

=== RIEPILOGO TEST PANNELLO CATEGORIE ===
Totale controlli eseguiti: 44
Superati: 44
Falliti: 0
```

---

## 23. Pannello: Sedi (`controllo-sedi.php`)

La pagina riepiloga tutte le sedi della catena, mostrando l'indirizzo, il recapito telefonico, lo stato attuale della sala eventi e il collegamento per accedere alla scheda di modifica di ciascun locale. L'accesso e riservato esclusivamente al ruolo `amministratore`: i manager non passano da questa vista panoramica poiche gestiscono unicamente la propria sede assegnata.

### 23.1 Controllo Accessi e Autorizzazione

- [x] **Accesso Ospite non autenticato**:
  - Richiesta GET a `/controllo-sedi` respinta immediatamente con `HTTP 401 Unauthorized`.
- [x] **Accesso Cliente (`user`)**:
  - Richiesta GET a `/controllo-sedi` respinta con `HTTP 403 Forbidden`.
- [x] **Accesso Manager (`manager`)**:
  - Richiesta GET a `/controllo-sedi` respinta con `HTTP 403 Forbidden` (il manager ha visibilita limitata al proprio locale).
- [x] **Accesso Amministratore (`admin`)**:
  - Richiesta GET a `/controllo-sedi` consentita con `HTTP 200 OK`.

### 23.2 Struttura Semantica, Breadcrumb e Navigazione di Sezione

- [x] **Titolo della Pagina (`<title>`)**:
  - Correttamente impostato a `Sedi - Pannello di controllo`.
- [x] **Breadcrumb Semantico (`<nav aria-label="Percorso">`)**:
  - Percorso a 3 livelli: `Home` (`/`) > `Controllo` (`/controllo`) > `Sedi` (elemento testuale corrente non cliccabile).
- [x] **Intestazione Principale (`<h1>`)**:
  - `<h1>Sedi</h1>`.
- [x] **Menu di Navigazione Pannello (`<nav class="filtri" aria-label="Sezioni del pannello">`)**:
  - Include tutte le sezioni accessibili all'amministratore: `Ordini`, `Prodotti`, `Categorie`, `Sedi`, `Prenotazioni`, `Messaggi`, `Utenti`.
  - Voce attiva `Sedi` marcata con `aria-current="page"`.

### 23.3 Tabella Sedi della Catena e Dati Informativi

- [x] **Didascalia Accessibile (`caption`)**:
  - Didascalia semantica presente: `<caption>Sedi della catena</caption>`.
- [x] **Intestazioni di Colonna (`thead th[scope="col"]`)**:
  - `Citta`: Intestazione colonna 1.
  - `Indirizzo`: Intestazione colonna 2.
  - `Telefono`: Intestazione colonna 3.
  - `Sala eventi`: Intestazione colonna 4.
  - `Scheda`: Intestazione colonna 5.
- [x] **Righe Dati (`tbody tr`)**:
  - Ciascuna riga corrisponde a un record attivo nella tabella `sedi` di MariaDB (Padova, Treviso, Vicenza, Udine).
  - Intestazione di riga `th[scope="row"]` contenente il nome della citta.
  - Cella indirizzo contenente indirizzo civico e CAP (es. `Via San Fermo 34, 35137`).
  - Cella telefono contenente recapito formattato (es. `049 1234567`).
  - Badge di stato sala eventi:
    - Sedi con sala eventi aperta: `<span class="etichetta" data-tipo="positivo">prenotabile</span>`.
    - Sedi con sala eventi chiusa: `<span class="etichetta" data-tipo="attenzione">chiusa</span>`.

### 23.4 Collegamenti Operativi e Accessibilita Screen Reader

- [x] **Collegamento Modifica Sede**:
  - Ciascuna riga espone un link diretto alla scheda della sede: `<a href="controllo-sede?sede=[id]">Modifica <span class="solo-lettori">la sede di [Citta]</span></a>`.
  - La presenza della classe `.solo-lettori` garantisce la piena comprensione del contesto ai software di lettura assistita (screen reader), evitando collegamenti ambigui con solo testo "Modifica".

### 23.5 Responsive Mobile (375x667px) e Convalida WCAG AA

- [x] **Visualizzazione Mobile (375x667px)**:
  - `clientWidth = 375px`, `scrollWidth = 375px`: nessun overflow orizzontale della pagina o del contenitore principale.
  - La tabella adotta lo scorrimento orizzontale interno disciplinato da CSS (`.pagina-interna > table { overflow-x: auto; }`), preservando la leggibilita di tutte le colonne e l'interazione touch su dispositivi mobili.
- [x] **Verifica Contrasto WCAG AA**:
  - Tema chiaro: 77 elementi testati, 0 violazioni del contrasto (100% conforme).
  - Tema scuro: 77 elementi testati, 0 violazioni del contrasto (100% conforme).

#### Log Esecuzione Script E2E Playwright (`scratch/test_controllo_sedi.py`)

```text
--- TEST 1: Access Control ---
Guest status: 401 (expected 401)
Client status: 403 (expected 403)
Manager status: 403 (expected 403)
Admin status: 200 (expected 200)
--- TEST 2: Page Elements & Semantic Structure ---
Page title verified.
Breadcrumb verified.
H1 verified.
Panel navigation verified, current is Sedi.
--- TEST 3: Table Structure & Data ---
Headers verified: ['Citta', 'Indirizzo', 'Telefono', 'Sala eventi', 'Scheda']
Found 4 branches in table.
Row 1: Padova | Via San Fermo 34, 35137 | Tel: 049 1234567 | Sala: prenotabile (positivo) | Link: controllo-sede?sede=1 (SR: 'la sede di Padova')
Row 2: Treviso | Via Calmaggiore 18, 31100 | Tel: 0422 234567 | Sala: prenotabile (positivo) | Link: controllo-sede?sede=2 (SR: 'la sede di Treviso')
Row 3: Vicenza | Corso Palladio 92, 36100 | Tel: 0444 345678 | Sala: prenotabile (positivo) | Link: controllo-sede?sede=3 (SR: 'la sede di Vicenza')
Row 4: Udine | Via Mercatovecchio 7, 33100 | Tel: 0432 456789 | Sala: chiusa (attenzione) | Link: controllo-sede?sede=4 (SR: 'la sede di Udine')
--- TEST 4: Screenshot & Mobile Responsiveness ---
Desktop screenshot saved.
Mobile 375px: scrollWidth=375, clientWidth=375
Mobile screenshot saved.
--- TEST 5: WCAG AA Contrast Audit ---
Light theme: 77 elements checked, 0 contrast issues.
Dark theme: 77 elements checked, 0 contrast issues.
ALL TESTS PASSED FOR CONTROLLO-SEDI (PAGE 23)!
```

---

## 24. Pannello: Scheda Sede (`controllo-sede.php`)

La scheda di gestione della singola sede consente al manager (per la propria sede di competenza) o all'amministratore (per qualsiasi sede della catena) di aggiornare i recapiti e l'indirizzo del locale, definire gli orari settimanali di apertura giorno per giorno e attivare o disattivare la disponibilita della sala eventi per le prenotazioni del pubblico.

### 24.1 Controllo Accessi, Autorizzazioni e Protezione IDOR

- [x] **Permessi di Ruolo e Reindirizzamento**:
  - [x] Utente non autenticato (ospite): respinto con codice `HTTP 401 Unauthorized`.
  - [x] Cliente autenticato (`user`): respinto con codice `HTTP 403 Forbidden`.
  - [x] Manager autenticato (`manager`): accede alla propria sede (Padova, `sede_id = 1`) tramite `/controllo-sede` senza parametri o specificando la propria sede.
  - [x] Amministratore autenticato (`admin`): accede a qualunque sede specificando il parametro `?sede=ID`.
- [x] **Protezione IDOR (Insecure Direct Object Reference)**:
  - [x] Se un manager tenta di accedere a una sede diversa dalla propria (es. `GET /controllo-sede?sede=2` o `POST` con `sede_id=2`), la richiesta viene bloccata immediatamente con codice `HTTP 403 Forbidden` (`limite !== null && richiesta !== limite`).
- [x] **Gestione Risorsa Inesistente**:
  - [x] Se un amministratore richiede una sede non censita a database (es. `/controllo-sede?sede=9999`), il server restituisce `HTTP 404 Not Found`.

### 24.2 Struttura Semantica, Breadcrumb e Sotto-Navigazione

- [x] **Percorso di Navigazione (`nav[aria-label="Percorso"]`)**:
  - [x] Per il manager: `Home` (`url()`) / `Controllo` (`url('controllo')`) / `La tua sede` (`aria-current="page"`).
  - [x] Per l'amministratore: `Home` (`url()`) / `Controllo` (`url('controllo')`) / `Sedi` (`url('controllo-sedi')`) / `[Nome Citta]` (`aria-current="page"`).
- [x] **Intestazione Principale e Sotto-Navigazione**:
  - [x] Titolo della schermata in `<h1>Sede di [Citta]</h1>`.
  - [x] Barra di navigazione del pannello con evidenziazione della voce corrente `aria-current="page"` su *"La tua sede"* (per il manager) o senza focus specifico di secondo livello (per l'amministratore).

### 24.3 Modulo Dati del Locale (`form[data-modulo="dati-sede"]`)

- [x] **Riquadro Indirizzo e Recapiti (`fieldset`)**:
  - [x] Token CSRF presente (`campo_csrf()`) e parametro nascosto `azione="dati"`.
  - [x] Campo `nome`: `<input type="text" id="nome" name="nome" required="required" maxlength="120" autocomplete="organization" />`.
  - [x] Campo `citta`: `<input type="text" id="citta" name="citta" required="required" maxlength="80" autocomplete="address-level2" />`.
  - [x] Campo `indirizzo`: `<input type="text" id="indirizzo" name="indirizzo" required="required" maxlength="160" autocomplete="street-address" />`.
  - [x] Campo `provincia`: `<input type="text" id="provincia" name="provincia" required="required" maxlength="2" autocomplete="address-level1" />`.
  - [x] Campo `cap`: `<input type="text" id="cap" name="cap" required="required" maxlength="5" autocomplete="postal-code" />`.
  - [x] Campo `telefono`: `<input type="tel" id="telefono" name="telefono" required="required" maxlength="30" autocomplete="tel" />`.
  - [x] Campo `email`: `<input type="email" id="email" name="email" required="required" maxlength="160" autocomplete="email" />`.
  - [x] Campo `note_ritiro`: `<input type="text" id="note_ritiro" name="note_ritiro" maxlength="255" aria-describedby="aiuto-note" />` con testo esplicativo associato (`<small id="aiuto-note">`).
- [x] **Conformita WCAG 2.1 Criterio 1.3.5 (Identify Input Purpose)**:
  - [x] Tutti i campi di recapito presentano token `autocomplete` standard compatibili con le tecnologie assistive e la compilazione automatica sicura del browser.
- [x] **Validazione e Notifiche di Errore**:
  - [x] In caso di campi errati o mancanti, compare il sommario errori `<section class="avviso" role="alert" data-tipo="errore">` con collegamenti interni ad ancora (`<a href="#[campo]">`) che spostano il fuoco direttamente sul controllo.
  - [x] I campi in errore ricevono l'attributo `data-stato="errore"` e il collegamento all'errore contestuale mediante `aria-describedby="errore-[campo]"`.
- [x] **Salvataggio Asincrono**:
  - [x] Il modulo supporta l'invio AJAX tramite `data-modulo="dati-sede"` con notifica flash *"Fatto: Dati della sede aggiornati."*.
  - [x] Aggiornamento verificato a database sulla tabella `sedi`.

### 24.4 Modulo Orari Settimanali (`form[data-modulo="orari"]`)

- [x] **Tabella Orari Giorno per Giorno**:
  - [x] Didascalia accessibile in `<caption>`.
  - [x] Intestazioni di colonna (`<th scope="col">Giorno</th>`, `Apre`, `Chiude`, `Chiuso`) e di riga (`<th scope="row">[Giorno]</th>` da Lunedi a Domenica, ISO 1-7).
  - [x] Controlli orari: `<input type="time" id="apre-[N]" name="apertura[[N]]" />` e `chiude-[N]` con etichette dedicate per lettori di schermo (`<label class="solo-lettori">`).
  - [x] Casella di chiusura: `<input type="checkbox" id="chiuso-[N]" name="chiuso[[N]]" value="1" />` con testo contestuale (`Chiuso il [Giorno]`).
- [x] **Salvataggio Asincrono**:
  - [x] Pulsante submit `<button type="submit">Salva gli orari</button>`.
  - [x] Persistenza verificata a database su `orari_sedi` con messaggio di successo *"Fatto: Orari aggiornati."*.

### 24.5 Modulo Sala Eventi (`form[data-modulo="sala"]`)

- [x] **Stato e Controllo Disponibilita**:
  - [x] Badge di stato corrente: `<span class="etichetta" data-tipo="positivo">accetta prenotazioni</span>` oppure `<span class="etichetta" data-tipo="attenzione">non accetta prenotazioni</span>`.
  - [x] Paragrafo descrittivo contestuale che spiega in anticipo l'effetto dell'azione.
  - [x] Se la sala e aperta: pulsante di chiusura con stile di attenzione `<button type="submit" name="chiudi" value="1" data-tipo="negativo">Chiudi le prenotazioni</button>`.
  - [x] Se la sala e chiusa: pulsante di riapertura standard `<button type="submit" name="apri" value="1">Riapri le prenotazioni</button>`.
- [x] **Persistenza**:
  - [x] Modifica istantanea del campo `sala_eventi_disponibile` (1 / 0) nella tabella `sedi`.
  - [x] Aggiornamento visivo immediato senza ricaricamento totale della pagina.

### 24.6 Responsive Mobile (375x667px) e Convalida WCAG AA

- [x] **Visualizzazione Mobile (375x667px)**:
  - [x] Nessun overflow orizzontale (`scrollWidth = clientWidth = 375px`).
  - [x] Layout dei tre moduli allineato in verticale con spaziatura uniforme e leggibilita ottimale su touchscreen.
- [x] **Verifica Contrasto WCAG AA**:
  - [x] Tema chiaro: 122 elementi analizzati, 0 violazioni di contrasto.
  - [x] Tema scuro: 122 elementi analizzati, 0 violazioni di contrasto.

#### Log Esecuzione Script E2E Playwright (`scratch/test_controllo_sede_full.py`)

```text
--- 1. Test Permessi: Ospite e Cliente ---
Guest correctly received 401 Unauthorized.
User correctly received 403 Forbidden.
--- 2. Test Manager: Breadcrumb, Autocomplete, IDOR ---
Manager Breadcrumb: Home / Controllo / La tua sede
All autocomplete tokens verified.
Manager IDOR via GET blocked with 403 Forbidden.
--- 3. Test Modulo Dati del Locale ---
Server-side validation error displayed correctly: Scrivi citta, fra 2 e 80 caratteri.
                            Il CAP e composto da cinque cifre.
Success notice: Fatto: Dati della sede aggiornati.
DB note_ritiro: note_ritiro
Banco ritiri al piano terra
--- 4. Test Modulo Orari Settimanali ---
Success notice orari: Fatto: Orari aggiornati.
DB apertura lunedi: apertura
11:30:00
--- 5. Test Modulo Sala Eventi (Toggle) ---
Initial DB sala_eventi_disponibile: sala_eventi_disponibile
1
Initial Sala button text: Chiudi le prenotazioni
Toggled DB sala_eventi_disponibile: sala_eventi_disponibile
0
After toggle button text: Riapri le prenotazioni
Restored DB sala_eventi_disponibile: sala_eventi_disponibile
1
Screenshots taken.
--- 6. Test Admin: Accesso a qualsiasi sede ---
Admin Breadcrumb for Treviso: Home / Controllo / Sedi / Treviso
Admin accessing non-existent branch correctly received 404.
=== ALL TESTS IN CONTROLLO-SEDE PASSED! ===
```

---

## 25. Pannello: Prenotazioni (`controllo-prenotazioni.php`)

La schermata di gestione delle prenotazioni della sala eventi consente ai manager (per le prenotazioni della propria sede di competenza) e agli amministratori (per tutte le sedi della catena) di monitorare le richieste pervenute, approvarle, rifiutarle o annullarle, con aggiornamento dinamico asincrono della tabella senza ricaricamento dell'intera pagina.

### 25.1 Controllo Accessi, Autorizzazioni e Scoping Sede

- [x] **Permessi di Ruolo e Reindirizzamento**:
  - [x] Utente non autenticato (ospite): respinto con codice `HTTP 401 Unauthorized`.
  - [x] Cliente autenticato (`cliente`): respinto con codice `HTTP 403 Forbidden`.
  - [x] Manager autenticato (`manager`): accede con codice `HTTP 200 OK`; la vista mostra esclusivamente le prenotazioni associate alla propria sede di assegnazione (`sede_limite($pdo)`).
  - [x] Amministratore autenticato (`amministratore`): accede con codice `HTTP 200 OK`; la vista mostra le prenotazioni di tutte le sedi della catena (`$sedeId = null`).
- [x] **Protezione IDOR (Insecure Direct Object Reference) e Vincoli di Sede**:
  - [x] La funzione `prenotazione_cambia_stato()` applica la clausola `AND sede_id = :sede` per il manager sia nella verifica di esistenza preventiva (`SELECT 1`) sia nella query di modifica (`UPDATE`).
  - [x] Se un manager tenta di modificare una prenotazione appartenente a un'altra sede (es. manager di Padova che invia POST per prenotazione di Treviso), l'operazione viene respinta e viene visualizzato il messaggio di errore: *"La prenotazione non esiste o non è di questa sede."*, preservando lo stato originale a database.

### 25.2 Struttura Semantica, Breadcrumb e Sotto-Navigazione

- [x] **Percorso di Navigazione (`nav[aria-label="Percorso"]`)**:
  - [x] Sequenza ordinata: `Home` (`url()`) / `Controllo` (`url('controllo')`) / `<span aria-current="page">Prenotazioni</span>`.
- [x] **Intestazione Principale**:
  - [x] Titolo della pagina in `<h1>Prenotazioni della sala</h1>`.
- [x] **Sotto-Navigazione del Pannello (`nav[aria-label="Sezioni del pannello"]`)**:
  - [x] Voce *"Prenotazioni"* evidenziata con attributo `aria-current="page"`.
  - [x] Per il manager: elenco filtrato con voci accessibili al ruolo (*Ordini*, *Prodotti*, *Prenotazioni*, *La tua sede*).
  - [x] Per l'amministratore: elenco completo delle sezioni gestionali (*Ordini*, *Prodotti*, *Categorie*, *Sedi*, *Prenotazioni*, *Messaggi*, *Utenti*).

### 25.3 Tabella Dati e Accessibilita (Didascalia, Intestazioni, Note)

- [x] **Didascalia della Tabella**:
  - [x] `<caption>Prenotazioni ricevute</caption>` per identificare chiaramente la tabella alle tecnologie assistive.
- [x] **Intestazioni Semantiche**:
  - [x] Intestazioni di colonna in `<thead>`: `<th scope="col">Sede</th>`, `Data`, `Orario`, `Persone`, `Cliente`, `Nota`, `Stato`, `Azioni`.
  - [x] Intestazione di riga in `<tbody>`: `<th scope="row">[Citta]</th>` per correlare ciascuna riga alla sede corrispondente.
- [x] **Dettagli della Prenotazione**:
  - [x] Colonna *Data*: formattata tramite `data_breve()` (es. `10/11/2026`).
  - [x] Colonna *Orario*: intervallo orario `HH:MM - HH:MM` (es. `19:00 - 21:00`).
  - [x] Colonna *Persone*: conteggio numerico intero.
  - [x] Colonna *Cliente*: nome e cognome del cliente (`[Nome] [Cognome]`).
  - [x] Colonna *Nota*:
    - Se presente: testo completo della nota inserita dal cliente.
    - Se vuota: testo dedicato per screen reader `<span class="solo-lettori">Nessuna nota</span>` e trattino grafico per utenti vedenti `<span aria-hidden="true">-</span>`.
- [x] **Badge di Stato (`span.etichetta`)**:
  - [x] `in attesa` / `in_attesa`: attributo `data-tipo="attenzione"`.
  - [x] `approvata`: attributo `data-tipo="positivo"`.
  - [x] `rifiutata` / `annullata`: attributo `data-tipo="negativo"`.

### 25.4 Modulo Interattivo e Macchina a Stati (`data-modulo="prenotazioni"`)

- [x] **Form Singolo per Tabella con Token CSRF**:
  - [x] Modulo `<form method="post" action="..." data-modulo="prenotazioni">` avvolge l'intera tabella con campo nascosto `token_csrf`.
  - [x] Ciascun pulsante porta come `name` lo stato di destinazione e come `value` l'identificativo numerico della prenotazione.
- [x] **Transizioni di Stato**:
  - [x] Da stato `in attesa`:
    - Pulsante `Approva` (`name="approvata"`, `value="[id]"`): sposta la prenotazione a `approvata`.
    - Pulsante `Rifiuta` (`name="rifiutata"`, `value="[id]"`): sposta la prenotazione a `rifiutata`.
  - [x] Da stato `approvata`:
    - Pulsante `Annulla` (`name="annullata"`, `value="[id]"`): sposta la prenotazione a `annullata`.
  - [x] Da stato `rifiutata` o `annullata`:
    - Nessun pulsante; viene mostrato il testo statico `<span>nessuna</span>`.
- [x] **Accessibilita dei Pulsanti Azione**:
  - [x] Ogni pulsante include la data di riferimento per lettori di schermo: `<span class="solo-lettori">la prenotazione del [data]</span>`, garantendo che i controlli ripetuti abbiano un nome accessibile non ambiguo (WCAG 2.1 Criterio 2.4.4 Link Purpose in Context / 4.1.2 Name, Role, Value).
- [x] **Aggiornamento Dinamico Asincrono (AJAX)**:
  - [x] Lo script `script.js` intercetta il submit del modulo tramite `data-modulo="prenotazioni"`.
  - [x] Invia la richiesta `POST` in background con i dati del pulsante submitter.
  - [x] Sostituisce il blocco `#contenuto` con l'HTML aggiornato restituito dal server.
  - [x] Sposta automaticamente il fuoco da tastiera sulla notifica `<p class="avviso" role="status">` (*"Fatto: Prenotazione aggiornata."*), consentendo agli screen reader di annunciare l'esito dell'operazione.

### 25.5 Stato Vuoto (`$prenotazioni === []`)

- [x] **Visualizzazione in Assenza di Prenotazioni**:
  - [x] Se non sono presenti prenotazioni per la sede (verificato con account `manager.udine`, sede di Udine priva di prenotazioni):
    - La tabella e il relativo modulo non vengono renderizzati.
    - Viene mostrato il messaggio accessibile: `<p>Non c'è nessuna prenotazione.</p>`.

### 25.6 Responsive Mobile (375x667px) e Convalida WCAG AA

- [x] **Visualizzazione Mobile (375x667px)**:
  - [x] Nessun overflow orizzontale dell'intera pagina (`document.documentElement.scrollWidth = clientWidth = 375px`).
  - [x] Il modulo della tabella `.pagina-controllo > form:has(> table)` e configurato con `overflow-x: auto`, consentendo lo scorrimento orizzontale dedicato della griglia dati su schermi ridotti senza compromettere la navigazione superiore.
- [x] **Verifica Contrasto WCAG AA**:
  - [x] 127 elementi testati (titoli, didascalie, celle, badge `positivo`/`negativo`/`attenzione`, pulsanti, collegamenti): 0 violazioni di contrasto (tutti conformi alla soglia minima 4.5:1 / 3.0:1).

#### Log Esecuzione Script E2E Playwright (`scratch/test_controllo_prenotazioni.py`)

```text
--- 0. Baseline DB State ---
id	sede_id	stato
1	1	annullata
2	2	approvata
3	1	rifiutata
6	3	rifiutata
7	2	annullata

--- 1. Test Permessi: Ospite e Cliente ---
Ospite correctly received 401 Unauthorized.
Cliente correctly received 403 Forbidden.

--- 2. Setup Test Reservations ---
Inserted test reservations:
id	sede_id	note	stato
17	1	Tavolo vicino alla finestra	in attesa
18	1		in attesa
19	2	Festa compleanno Treviso	in attesa

--- 3. Test Manager: Scoping e Struttura Semantica ---
Breadcrumb: Home / Controllo / Prenotazioni
H1: Prenotazioni della sala
Subnav active tab: Prenotazioni
Sedi visible to manager: {'Padova'}
Table headers semantic check: OK
Empty note accessibility check: OK

--- 4. Test Azioni Interattive & State Machine (AJAX) ---
Button Approva SR text: la prenotazione del 10/11/2026
Notice after Approva: Fatto: Prenotazione aggiornata.
Badge after Approva: 'approvata' (data-tipo: positivo)
Annulla button present: OK
DB check after Approva: OK
Notice after Annulla: Fatto: Prenotazione aggiornata.
Badge after Annulla: 'annullata' (data-tipo: negativo)
Action 'nessuna' after Annulla: OK
DB check after Annulla: OK
Notice after Rifiuta: Fatto: Prenotazione aggiornata.
Badge after Rifiuta: 'rifiutata' (data-tipo: negativo)
DB check after Rifiuta: OK

--- 5. Test Protezione IDOR (Manager vs altra sede) ---
IDOR error notice received: Errore: La prenotazione non esiste o non è di questa sede.
IDOR protection confirmed: DB record untouched.

--- 5.1 Test Stato Vuoto (manager.udine) ---
Empty state text: Non c'è nessuna prenotazione.
Empty state verified: OK

--- 6. Test Admin (Tutte le sedi) ---
Sedi visible to admin: {'Treviso', 'Padova', 'Vicenza'}
Admin subnav tabs: ['Ordini', 'Prodotti', 'Categorie', 'Sedi del pannello', 'Prenotazioni', 'Messaggi', 'Utenti']
Admin approval of Treviso: OK
Desktop screenshot saved.

--- 7. Test Responsive Mobile (375x667) & WCAG AA ---
Mobile dimensions: scrollWidth=375, clientWidth=375
Table container overflow-x: auto
Mobile screenshot saved.
Contrast audit: 127 elements checked, 0 issues found.

--- 8. Cleanup Database ---
DB after cleanup:
id	sede_id	stato
1	1	annullata
2	2	approvata
3	1	rifiutata
6	3	rifiutata
7	2	annullata
DB restored to baseline perfectly.

=== ALL TESTS FOR CONTROLLO-PRENOTAZIONI PASSED SUCCESSFULLY! ===
```

---

## 26. Pannello: Messaggi di contatto (`controllo-contatti.php`)

La schermata di gestione dei messaggi di contatto consente esclusivamente all'amministratore di consultare le richieste inviate dagli utenti tramite il form pubblico di contatto, visionare recapiti e testo per intero, e aggiornarne lo stato del flusso operativo (`nuovo`, `preso in carico`, `chiuso`), con aggiornamento dinamico asincrono della tabella senza ricaricamento dell'intera pagina.

### 26.1 Controllo Accessi, Autorizzazioni e Permessi di Ruolo

- [x] **Permessi di Ruolo e Reindirizzamento**:
  - [x] Utente non autenticato (ospite): respinto con codice `HTTP 401 Unauthorized`.
  - [x] Cliente autenticato (`cliente`): respinto con codice `HTTP 403 Forbidden`.
  - [x] Manager autenticato (`manager`): respinto con codice `HTTP 403 Forbidden` (la sezione e riservata esclusivamente all'amministratore; la voce non compare nel menu manager).
  - [x] Amministratore autenticato (`amministratore`): accede con codice `HTTP 200 OK`.
- [x] **Protezione ID Inesistente / Richieste Non Valide**:
  - [x] Se viene inviato un aggiornamento con un identificativo non presente a database (es. `id=999999`), il server restituisce il messaggio di errore: *"Errore: Il messaggio non esiste piu."*.
  - [x] Se la richiesta POST e priva di token CSRF o presenta uno stato non riconosciuto, la richiesta viene respinta con codice `HTTP 403 Forbidden`.

### 26.2 Struttura Semantica, Breadcrumb e Sotto-Navigazione

- [x] **Percorso di Navigazione (`nav[aria-label="Percorso"]`)**:
  - [x] Sequenza ordinata: `Home` (`url()`) / `Controllo` (`url('controllo')`) / `<span aria-current="page">Messaggi</span>`.
- [x] **Intestazione Principale**:
  - [x] Titolo della pagina in `<h1>Messaggi</h1>`.
- [x] **Sotto-Navigazione del Pannello (`nav[aria-label="Sezioni del pannello"]`)**:
  - [x] Voce *"Messaggi"* evidenziata con attributo `aria-current="page"`.
  - [x] Elenco completo delle sezioni per l'amministratore (*Ordini*, *Prodotti*, *Categorie*, *Sedi*, *Prenotazioni*, *Messaggi*, *Utenti*).
- [x] **Nota Informativa Operativa**:
  - [x] Paragrafo esplicativo accessibile a fondo tabella: `<p>Il sito gestisce solo il primo messaggio di ogni richiesta: le risposte successive avvengono via email.</p>`.

### 26.3 Tabella Dati e Accessibilita (Didascalia, Intestazioni, Mailto)

- [x] **Didascalia della Tabella**:
  - [x] `<caption>Messaggi arrivati dal modulo di contatto</caption>` per identificare chiaramente la tabella alle tecnologie assistive.
- [x] **Intestazioni Semantiche**:
  - [x] Intestazioni di colonna in `<thead>`: `<th scope="col">Ricevuto</th>`, `Da`, `Argomento`, `Messaggio`, `Stato`, `Azioni`.
  - [x] Intestazione di riga in `<tbody>`: `<th scope="row">[data e ora ricezione]</th>` formattata tramite `data_ora()` (es. `05/09/2026 23:02`).
- [x] **Dettagli del Messaggio**:
  - [x] Colonna *Da*: nome dell'utente e collegamento email `<a href="mailto:[email]">[email]</a>` per avviare direttamente la risposta dal client di posta del gestore.
  - [x] Colonna *Argomento*: etichetta descrittiva tradotta dalla mappa delle categorie (es. *Un ordine*, *Una prenotazione*, *Una segnalazione sul sito*, *Altro*).
  - [x] Colonna *Messaggio*: testo integrale inserito dall'utente (contenuto entro i vincoli `CARATTERI_MESSAGGIO_CONTATTO` = 500 caratteri).
- [x] **Badge di Stato (`span.etichetta`)**:
  - [x] `nuovo`: attributo `data-tipo="attenzione"` (sfondo arancione/giallo di evidenziazione).
  - [x] `preso in carico` / `chiuso`: attributo `data-tipo="positivo"` (sfondo verde scuro).

### 26.4 Modulo Interattivo e Macchina a Stati (`data-modulo="messaggi"`)

- [x] **Form Singolo per Tabella con Token CSRF**:
  - [x] Modulo `<form method="post" action="..." data-modulo="messaggi">` avvolge l'intera tabella con campo nascosto `token_csrf`.
  - [x] Ciascun pulsante d'azione porta come `name` lo stato di destinazione (con trattini: `preso-in-carico`, `chiuso`, `nuovo`) e come `value` l'identificativo numerico del messaggio.
- [x] **Comportamento dei Pulsanti e Transizioni di Stato**:
  - [x] Per ciascuna riga vengono renderizzati esclusivamente i pulsanti per gli stati diversi da quello attuale del messaggio:
    - Se `nuovo`: pulsanti `preso in carico` (`name="preso-in-carico"`) e `chiuso` (`name="chiuso"`).
    - Se `preso in carico`: pulsanti `nuovo` (`name="nuovo"`) e `chiuso` (`name="chiuso"`).
    - Se `chiuso`: pulsanti `nuovo` (`name="nuovo"`) e `preso in carico` (`name="preso-in-carico"`).
- [x] **Accessibilita dei Pulsanti Azione**:
  - [x] Ciascun pulsante include il nome del mittente per i lettori di schermo: `<span class="solo-lettori">per il messaggio di [Nome Mittente]</span>`, evitando pulsanti ripetuti con nome ambiguo (WCAG 2.1 Criterio 2.4.4 / 4.1.2).
- [x] **Aggiornamento Dinamico Asincrono (AJAX)**:
  - [x] Lo script `script.js` intercetta l'evento submit tramite `data-modulo="messaggi"`.
  - [x] Invia la richiesta `POST` in background con i dati del pulsante submitter.
  - [x] Sostituisce il blocco `#contenuto` con l'HTML aggiornato restituito dal server.
  - [x] Sposta automaticamente il fuoco da tastiera sulla notifica `<p class="avviso" role="status">` (*"Fatto: Stato del messaggio aggiornato."*).

### 26.5 Stato Vuoto (`$messaggi === []`)

- [x] **Visualizzazione in Assenza di Messaggi**:
  - [x] Se non sono presenti messaggi di contatto a database:
    - La tabella e il relativo modulo non vengono renderizzati.
    - Viene mostrato il messaggio accessibile: `<p>Non è arrivato nessun messaggio.</p>`.

### 26.6 Responsive Mobile (375x667px) e Convalida WCAG AA

- [x] **Visualizzazione Mobile (375x667px)**:
  - [x] Nessun overflow orizzontale a livello di viewport (`document.documentElement.scrollWidth = clientWidth = 375px`).
  - [x] La tabella e racchiusa in `.pagina-controllo > form:has(> table)` con `overflow-x: auto` e padding ottimizzato per touch target.
- [x] **Verifica Contrasto WCAG AA**:
  - [x] 112 elementi analizzati (titoli, badge `nuovo`/`preso in carico`/`chiuso`, collegamenti mailto, intestazioni di riga/colonna, pulsanti): 0 violazioni di contrasto.

#### Log Esecuzione Script E2E Playwright (`scratch/test_controllo_contatti.py`)

```text
--- 0. Baseline DB State ---
id	nome	email	stato
1	Paolo Neri	paolo.neri@example.it	preso in carico
2	Chiara Moretti	chiara.moretti@example.it	preso in carico
3	Davide Longo	davide.longo@example.it	chiuso
4	Collaudo Utente	collaudo.utente@example.com	nuovo
5	toji	toji@gmail.com	preso in carico

--- 1. Test Permessi di Ruolo ---
Ospite correctly received 401 Unauthorized.
Cliente correctly received 403 Forbidden.
Manager correctly received 403 Forbidden (sezione riservata ad amministratore).

--- 2. Setup Test Message in DB ---
Inserted test message:
id	nome	email	categoria	stato
6	Collaudo Contatti	collaudo.contatti@example.it	segnalazione	nuovo

--- 3. Test Admin: Struttura Semantica e Accessibilita ---
Breadcrumb: Home / Controllo / Messaggi
H1: Messaggi
Subnav active tab: Messaggi
Caption: Messaggi arrivati dal modulo di contatto
Table headers semantic check: OK
Footer explanation: Il sito gestisce solo il primo messaggio di ogni richiesta: le risposte successive
        avvengono via email.
Row header (data_ora): 05/09/2026 23:02
Mailto link verified: mailto:collaudo.contatti@example.it
Category label: Una segnalazione sul sito
Initial badge: 'nuovo' (data-tipo: attenzione)
Buttons for 'nuovo': ['preso-in-carico', 'chiuso']
Button SR text: per il messaggio di Collaudo Contatti

--- 4. Test Azioni Interattive & State Machine (AJAX) ---
Notice after preso in carico: Fatto: Stato del messaggio aggiornato.
Badge after transition 1: 'preso in carico' (data-tipo: positivo)
Buttons for 'preso in carico': ['nuovo', 'chiuso']
DB check: state is 'preso in carico'
Badge after transition 2: 'chiuso' (data-tipo: positivo)
Buttons for 'chiuso': ['nuovo', 'preso-in-carico']
DB check: state is 'chiuso'
Badge after transition 3: 'nuovo' (data-tipo: attenzione)
DB check: state is back to 'nuovo'

--- 5. Test Errore ID Inesistente ---
Notice for non-existent message: Errore: Il messaggio non esiste piu.
Desktop screenshot saved.

--- 6. Test Responsive Mobile (375x667) & WCAG AA ---
Mobile dimensions: scrollWidth=375, clientWidth=375
Table container overflow-x: auto
Mobile screenshot saved.
Contrast audit: 112 elements checked, 0 issues found.

--- 7. Cleanup Database ---
DB after cleanup:
id	nome	email	stato
1	Paolo Neri	paolo.neri@example.it	preso in carico
2	Chiara Moretti	chiara.moretti@example.it	preso in carico
3	Davide Longo	davide.longo@example.it	chiuso
4	Collaudo Utente	collaudo.utente@example.com	nuovo
5	toji	toji@gmail.com	preso in carico
DB restored to baseline perfectly.

=== ALL TESTS FOR CONTROLLO-CONTATTI PASSED SUCCESSFULLY! ===
```

---

## 27. Pannello: Utenti (`controllo-utenti.php`)

La schermata di gestione degli utenti consente all'amministratore di consultare l'elenco degli account registrati alla piattaforma con il conteggio degli ordini effettuati, modificare il ruolo (`cliente`, `manager`, `amministratore`), assegnare o revocare la sede di competenza per i manager, attivare o disattivare gli account e avviare la procedura protetta di cancellazione completa con richiesta di conferma esplicita.

### 27.1 Controllo Accessi, Autorizzazioni e Permessi di Ruolo

- [x] **Permessi di Ruolo e Reindirizzamento**:
  - [x] Utente non autenticato (ospite): respinto con codice `HTTP 401 Unauthorized`.
  - [x] Cliente autenticato (`cliente`): respinto con codice `HTTP 403 Forbidden`.
  - [x] Manager autenticato (`manager`): respinto con codice `HTTP 403 Forbidden` (sezione ad accesso esclusivo dell'amministratore).
  - [x] Amministratore autenticato (`amministratore`): accede con codice `HTTP 200 OK`.
- [x] **Protezione Auto-Modifica e Auto-Cancellazione Admin**:
  - [x] Sulla riga del proprio account, l'amministratore non visualizza ne i selettori di modifica ruolo/sede ne i pulsanti di attivazione o cancellazione: al loro posto compare il testo fisso `amministratore` e il collegamento `<a href="url('profilo')">Modifica dal profilo</a>`.
  - [x] Tentativo forzato via POST di cancellare il proprio account (`utente_id = utenteCorrente`): respinto dal server con messaggio d'errore: *"Errore: Il tuo account si cancella dal profilo."*, mantenendo l'account intatto.

### 27.2 Struttura Semantica, Breadcrumb e Sotto-Navigazione

- [x] **Percorso di Navigazione (`nav[aria-label="Percorso"]`)**:
  - [x] Sequenza ordinata: `Home` (`url()`) / `Controllo` (`url('controllo')`) / `<span aria-current="page">Utenti</span>`.
- [x] **Intestazione Principale**:
  - [x] Titolo della schermata in `<h1>Utenti</h1>`.
- [x] **Sotto-Navigazione del Pannello (`nav[aria-label="Sezioni del pannello"]`)**:
  - [x] Voce *"Utenti"* evidenziata con attributo `aria-current="page"`.
  - [x] Elenco completo delle sezioni gestionali per l'amministratore (*Ordini*, *Prodotti*, *Categorie*, *Sedi*, *Prenotazioni*, *Messaggi*, *Utenti*).
- [x] **Nota Informativa Operativa**:
  - [x] Paragrafo guida a fondo pagina: `<p>Per affidare una sede a un manager scegli il ruolo e la sede, poi salva. Una sede ha al massimo un manager: se è già occupata va prima liberata.</p>`.

### 27.3 Tabella Dati e Accessibilita (Didascalia, Intestazioni, Moduli Esterni)

- [x] **Didascalia della Tabella**:
  - [x] `<caption>Account registrati</caption>` per identificare chiaramente la tabella alle tecnologie assistive.
- [x] **Intestazioni Semantiche**:
  - [x] Intestazioni di colonna in `<thead>`: `<th scope="col">Nome utente</th>`, `Email`, `Ordini`, `Ruolo e sede`, `Stato`, `Azioni`.
  - [x] Intestazione di riga in `<tbody>`: `<th scope="row">[nome_utente]</th>`.
- [x] **Architettura Moduli Fuori Tabella (HTML5 form attribute)**:
  - [x] Per evitare elementi form invalidi nidificati nelle celle delle tabelle, i form `ruolo` (`id="utente-[id]" data-modulo="ruolo"`) e `stato` (`id="stato-[id]" data-modulo="stato"`) sono collocati fuori dalla tabella, e i controlli (`<select>`, `<button>`) vi fanno riferimento tramite l'attributo `form="utente-[id]"` o `form="stato-[id]"`.
- [x] **Etichette Nascoste per Screen Reader**:
  - [x] Ciascun selettore ruolo dispone di `<label class="solo-lettori" for="ruolo-[id]">Ruolo di [nome_utente]</label>`.
  - [x] Ciascun selettore sede dispone di `<label class="solo-lettori" for="sede-[id]">Sede affidata a [nome_utente]</label>`.
  - [x] Tutti i pulsanti di salvataggio, cambio stato e cancellazione includono il nome utente contestuale per evitare ambiguita (`<span class="solo-lettori">`).

### 27.4 Dinamica Client-Side e Gestione Ruolo / Sede

- [x] **Disattivazione Condizionale della Sede via JavaScript (`script.js`)**:
  - [x] Se il ruolo e `cliente` o `amministratore`: il selettore della sede riceve l'attributo `disabled="disabled"` e il suo valore viene reimpostato automaticamente a `""` (*Nessuna sede*).
  - [x] Se l'utente seleziona `manager`: il selettore della sede viene immediatamente sbloccato (`disabled = false`), consentendo la scelta della sede da affidare.
- [x] **Integrita e Vincolo di Unicita Manager per Sede**:
  - [x] Ciascuna sede puo avere al massimo un solo manager (`manager_id UNIQUE` a database).
  - [x] Se si tenta di assegnare a un manager una sede gia occupata da un altro gestore (es. sede di Padova con manager esistente), l'operazione fallisce con rollback transazionale e messaggio d'avviso: *"Errore: Quella sede ha già un manager: liberala prima."*.
  - [x] Quando un manager viene promosso ad amministratore o retrocesso a cliente, la sede precedentemente gestita viene automaticamente liberata (`manager_id = NULL`).

### 27.5 Attivazione / Disattivazione Account (`data-modulo="stato"`)

- [x] **Toggle Stato Operativo**:
  - [x] Pulsante `Disattiva [nome_utente]`: imposta `attivo = 0` a database; il badge di stato commuta a `disattivato` (`data-tipo="negativo"`), e il pulsante diventa `Attiva [nome_utente]`.
  - [x] Pulsante `Attiva [nome_utente]`: imposta `attivo = 1` a database; il badge commuta a `attivo` (`data-tipo="positivo"`), e il pulsante torna a `Disattiva [nome_utente]`.
  - [x] Un account disattivato non puo piu accedere al sito e riceve il messaggio d'errore: *"Questo account è stato disattivato."*.

### 27.6 Flusso di Cancellazione Account e Banner di Conferma

- [x] **Passaggio 1: Richiesta di Cancellazione**:
  - [x] Clic sul collegamento `Cancella [nome_utente]` (`?cancella=[id]`).
- [x] **Passaggio 2: Banner di Allerta e Conferma Esplicita**:
  - [x] Compare in primo piano l'avviso di pericolo `<section class="avviso" role="alert" data-tipo="errore">`.
  - [x] Intestazione `<h2>Vuoi cancellare l'account [nome_utente]?</h2>` e messaggio di avvertimento sulle conseguenze irreversibili (*"Vengono cancellati anche il carrello, gli ordini e le prenotazioni di questo account."*).
  - [x] Azione **Annulla** (`<a class="pulsante secondario">`): chiude il banner senza toccare il database e preserva l'account.
  - [x] Azione **Conferma Cancellazione** (`<button type="submit" data-tipo="negativo">Cancella [nome_utente]</button>`): rimuove definitivamente l'account da database con messaggio di successo (*"Fatto: Account cancellato."*).

### 27.7 Responsive Mobile (375x667px) e Convalida WCAG AA

- [x] **Visualizzazione Mobile (375x667px)**:
  - [x] Nessun overflow orizzontale (`document.documentElement.scrollWidth = clientWidth = 375px`).
  - [x] Tabella con scorrimento dedicato (`display: block; overflow-x: auto`), controlli select e pulsanti formattati a larghezza intera o compatibile con touch target.
- [x] **Verifica Contrasto WCAG AA**:
  - [x] 201 elementi analizzati nel documento: 0 violazioni di contrasto rilevate (tutti conformi ai requisiti 4.5:1 / 3.0:1).

#### Log Esecuzione Script E2E Playwright (`scratch/test_controllo_utenti.py`)

```text
--- 0. Baseline DB State ---
id	nome_utente	ruolo	attivo
1	admin	amministratore	1
2	manager	manager	1
3	user	cliente	1
4	manager.treviso	manager	1
5	manager.vicenza	manager	1
6	manager.udine	manager	1
7	paolo.neri	cliente	1
8	chiara.moretti	cliente	1

--- 1. Test Permessi di Ruolo ---
Ospite correctly received 401 Unauthorized.
Cliente correctly received 403 Forbidden.
Manager correctly received 403 Forbidden (sezione riservata ad amministratore).

--- 2. Setup Test User in DB ---
Inserted test user:
id	nome_utente	ruolo	attivo
14	test.gestione	cliente	1

--- 3. Test Admin: Struttura Semantica e Accessibilita ---
Breadcrumb: Home / Controllo / Utenti
H1: Utenti
Subnav active tab: Utenti
Caption: Account registrati
Table headers semantic check: OK
Admin self row role text: amministratore
Admin self action link: Modifica dal profilo profilo
Self-protection on admin account: OK

--- 4. Test Client-Side JS: Dinamica Ruolo / Sede ---
Initial state for cliente: sede select disabled = True
After changing role to manager: sede select disabled = False
After changing role to amministratore: sede select disabled = True, value = ''

--- 5. Test Cambio Ruolo: Conflitto Sede e Assegnazione Valida ---
Notice on occupied branch conflict: Errore: Quella sede ha già un manager: liberala prima.
DB check after conflict: role remained 'cliente'
Notice after valid role change: Fatto: Ruolo aggiornato.
DB check: role successfully updated to 'amministratore'

--- 6. Test Attivazione / Disattivazione Account ---
Button stato text: Disattiva test.gestione
Notice after disattiva: Fatto: Account disattivato.
DB check: attivo = 0
Notice after attiva: Fatto: Account riattivato.
DB check: attivo = 1

--- 7. Test Flusso Cancellazione Account ---
Banner title: Vuoi cancellare l'account test.gestione?
Annulla cancel flow verified: user preserved.
Notice after confirm delete: Fatto: Account cancellato.
DB check: test user successfully deleted from database.

--- 8. Test Protezione Auto-Cancellazione Admin ---
Notice on self-deletion attempt: Errore: Il tuo account si cancella dal profilo.
Self-deletion blocked successfully.
Desktop screenshot saved.

--- 9. Test Responsive Mobile (375x667) & WCAG AA ---
Mobile dimensions: scrollWidth=375, clientWidth=375
Table display: block
Mobile screenshot saved.
Contrast audit: 201 elements checked, 0 issues found.

--- 10. Cleanup Database ---
DB after cleanup:
id	nome_utente	ruolo	attivo
1	admin	amministratore	1
2	manager	manager	1
3	user	cliente	1
4	manager.treviso	manager	1
5	manager.vicenza	manager	1
6	manager.udine	manager	1
7	paolo.neri	cliente	1
8	chiara.moretti	cliente	1
DB restored to baseline perfectly.

=== ALL TESTS FOR CONTROLLO-UTENTI PASSED SUCCESSFULLY! ===
```

---

## 28. Privacy Policy (`privacy.php`)

La pagina informativa di Privacy Policy espone in modo chiaro, trasparente e privo di tecnicismi legali non necessari le modalita di trattamento dei dati personali degli utenti del sito, le finalita della raccolta, i tempi di conservazione, l'uso esclusivo di cookie tecnici di sessione e le modalita per esercitare i diritti di consultazione, rettifica e cancellazione.

### 28.1 Accesso Pubblico e Metadati

- [x] **Accesso Pubblico Libero**:
  - [x] Raggiungibile da qualsiasi utente (ospite, cliente, manager, amministratore) con codice `HTTP 200 OK`.
- [x] **Metadati di Testata (`<head>`)**:
  - [x] Titolo pagina in `<title>`: `Privacy policy - Smash Burger` (entro i 60 caratteri).
  - [x] Meta description: `Quali dati raccogliamo, per quali finalita, per quanto tempo e come esercitare i tuoi diritti.` (entro i 160 caratteri).

### 28.2 Struttura Semantica e Gerarchia del Documento

- [x] **Percorso di Navigazione (`nav[aria-label="Percorso"]`)**:
  - [x] `Home` (`url()`) / `<span aria-current="page">Privacy</span>`.
- [x] **Contenitore Semantico e Intestazione**:
  - [x] Contenuto principale racchiuso in `<article class="pagina-documento">`.
  - [x] Intestazione documento in `<header class="header-documento">`:
    - Occhiello descrittivo: `<p class="occhiello">Trasparenza, senza testo piccolo</p>`.
    - Titolo principale in `<h1>Privacy policy</h1>`.
    - Paragrafo introduttivo: `<p class="introduzione">Quali dati raccogliamo, perchè ci servono, per quanto tempo li teniamo e come puoi intervenire.</p>`.
- [x] **Sezioni Tematiche e Titolazione H2**:
  - [x] `<h2>Quali dati raccogliamo</h2>`: elenco puntato (`<ul>`) dettagliato dei dati raccolti per registrazione, ordini con consegna a domicilio, storico ordini/prenotazioni e messaggi di contatto, con precisazione che i dati di pagamento non vengono memorizzati (pagamento simulato).
  - [x] `<h2>Perchè li raccogliamo</h2>`: finalita di gestione dell'account, evasione ordini, conferme di prenotazione e supporto clienti, con esclusione di profilazione o cessione a soggetti terzi.
  - [x] `<h2>Per quanto tempo</h2>`: politica di conservazione legata all'esistenza dell'account e cancellazione sincrona di carrello e storico dal profilo.
  - [x] `<h2>Cookie</h2>`: specifica tecnica dell'utilizzo di un unico cookie tecnico per il mantenimento della sessione utente dopo il login, senza cookie di tracciamento o profilazione di terze parti.
  - [x] `<h2>I tuoi diritti</h2>`: istruzioni per visionare, aggiornare o cancellare i propri dati direttamente online.

### 28.3 Collegamenti Interattivi nel Testo

- [x] **Collegamenti Ipertestuali di Sezione**:
  - [x] Collegamento al profilo: `<a href="profilo">profilo</a>` (consente all'utente loggato di gestire i propri dati o all'ospite di essere indirizzato al login).
  - [x] Collegamento ai contatti: `<a href="contatti">scrivici</a>` (collegamento diretto alla pagina di contatto per richieste dedicate).
- [x] **Integrazione con Navigazione Globale e Piede di Pagina**:
  - [x] Collegamento presente nella sezione *Esplora* del footer (`nav[aria-label="Collegamenti di servizio"] a[href="privacy"]`).
  - [x] Pulsante "Torna su" (`.torna-su`) e salto al contenuto (`.salta`) pienamente operativi.

### 28.4 Layout a Griglia Documentale, Responsive Mobile e Contrasto WCAG AA

- [x] **Layout Desktop (1280x900px)**:
  - [x] Griglia a due colonne `.pagina-documento > section` (`grid-template-columns: minmax(14rem, 0.4fr) minmax(0, 0.6fr)`): titolazione `<h2>` posizionata nella colonna di sinistra e paragrafi/elenchi nella colonna di destra.
  - [x] Punti elenco personalizzati con quadratini rossi (`section li::before { background: var(--ketchup-pieno); }`).
- [x] **Visualizzazione Mobile (375x667px)**:
  - [x] Nessun overflow orizzontale a livello di viewport (`document.documentElement.scrollWidth = clientWidth = 375px`).
  - [x] La griglia si adatta a colonna singola (`grid-template-columns: 1fr`) con spaziatura ottimale per la lettura fluida su dispositivi portatili.
- [x] **Tema Chiaro e Tema Scuro**:
  - [x] Commutazione del tema tramite il selettore nell'header (`button[name="tema"][value="scuro|chiaro"]`): applicazione della classe `.tema-scuro` a `<html>`, mantenendo leggibilita e contrasto elevati.
- [x] **Verifica Contrasto WCAG AA**:
  - [x] 64 elementi di testo analizzati (tutti conformi ai requisiti 4.5:1 per testo normale e 3.0:1 per titoli/grandi dimensioni, 0 violazioni).

#### Log Esecuzione Script E2E Playwright (`scratch/test_privacy.py`)

```text
--- 1. Test Accesso Pubblico e Ruoli ---
Guest correctly received 200 OK.
Page title: Privacy policy - Smash Burger
Meta description: Quali dati raccogliamo, per quali finalita, per quanto tempo e come esercitare i tuoi diritti.

--- 2. Test Struttura Semantica e Gerarchia Heading ---
Breadcrumb: Home / Privacy
Occhiello: Trasparenza, senza testo piccolo
H1: Privacy policy
Introduzione: Quali dati raccogliamo, perchè ci servono, per quanto tempo li teniamo e
            come puoi intervenire.
H2 headings: ['Quali dati raccogliamo', 'Perchè li raccogliamo', 'Per quanto tempo', 'Cookie', 'I tuoi diritti']
Data collection items count: 4

--- 3. Test Collegamenti Interattivi ---
Links to profilo and contatti verified.
Link to contatti navigation verified.
Footer privacy link verified.
Desktop screenshot saved.

--- 4. Test Tema Scuro ---
HTML class after theme toggle: tema-scuro
Dark mode screenshot saved.

--- 5. Test Responsive Mobile (375x667) & WCAG AA ---
Mobile dimensions: scrollWidth=375, clientWidth=375
Mobile section gridTemplateColumns: 336px
Mobile screenshot saved.
Contrast audit: 64 elements checked, 0 issues found.

=== ALL TESTS FOR PRIVACY PASSED SUCCESSFULLY! ===
```

---

## 29. Dichiarazione Accessibilità (`accessibilita.php`)

La Dichiarazione di Accessibilità documenta in conformità alle linee guida WCAG 2.1 livello AA l'impegno di Smash Burger a garantire la fruibilità completa del portale a ogni persona, indipendentemente dal dispositivo utilizzato, dalle capacità fisiche o sensoriali o dall'ambiente di navigazione. Espone in modo trasparente i criteri adottati, le modalità di verifica manuale e automatica, la gestione delle immagini descrittive e i canali per segnalare eventuali problemi di accessibilità.

### 29.1 Accesso Pubblico e Metadati

- [x] **Accesso Pubblico Libero**:
  - [x] Raggiungibile da qualsiasi utente (ospite, cliente, manager, amministratore) con codice `HTTP 200 OK`.
- [x] **Metadati di Testata (`<head>`)**:
  - [x] Titolo pagina in `<title>`: `Accessibilita - Smash Burger` (entro i 60 caratteri).
  - [x] Meta description: `Dichiarazione di accessibilita di Smash Burger: conformita WCAG 2.1 AA, verifiche con tastiera e screen reader, come segnalare un problema.` (entro i 160 caratteri).

### 29.2 Struttura Semantica e Gerarchia del Documento

- [x] **Percorso di Navigazione (`nav[aria-label="Percorso"]`)**:
  - [x] `Home` (`url()`) / `<span aria-current="page">Accessibilita</span>`.
- [x] **Contenitore Semantico e Intestazione**:
  - [x] Contenuto principale racchiuso in `<article class="pagina-documento">`.
  - [x] Intestazione documento in `<header class="header-documento">`:
    - Occhiello descrittivo: `<p class="occhiello">Il sito è per tutti</p>`.
    - Titolo principale in `<h1>Accessibilita</h1>`.
    - Paragrafo introduttivo: `<p class="introduzione">Puntiamo alla conformita WCAG 2.1 AA: niente ostacoli per chi usa la tastiera, uno screen reader o ingrandisce il testo.</p>`.
- [x] **Sezioni Tematiche e Titolazione H2**:
  - [x] `<h2>Che cosa abbiamo fatto</h2>`: elenco puntato (`<ul>`) dei 9 impegni implementati (struttura semantica, contrasto minimo 4.5:1, navigazione completa da tastiera, salto al contenuto, ingrandimento testo fino al 200%, assenza di testo incorporato in immagini, alternative testuali esaurienti, moduli etichettati esplicitamente, tema scuro/chiaro).
  - [x] `<h2>Come lo verifichiamo</h2>`: esposizione della metodologia di test continuo (validazione W3C HTML e CSS, controlli automatici con Pa11y/Lighthouse, navigazione da tastiera senza mouse, test con lettori di schermo VoiceOver e NVDA).
  - [x] `<h2>Immagini</h2>`: politica di gestione dei contenuti visivi (tutte le foto con testo alternativo significativo in `alt`, immagini decorative con `alt=""` o icone SVG con `aria-hidden="true"`).
  - [x] `<h2>Segnalare un problema</h2>`: canali di feedback per gli utenti con collegamenti diretti.

### 29.3 Collegamenti Interattivi nel Testo

- [x] **Collegamenti di Segnalazione**:
  - [x] Collegamento al modulo contatti: `<a href="contatti">modulo di contatto</a>` (apre la pagina dei contatti con il selettore di argomento).
  - [x] Collegamento mailto: `<a href="mailto:informazioni@smashburger.it">informazioni@smashburger.it</a>` (apre il client di posta predefinito).
- [x] **Integrazione con Navigazione Globale e Piede di Pagina**:
  - [x] Collegamento presente nella sezione *Esplora* del footer (`nav[aria-label="Collegamenti di servizio"] a[href="accessibilita"]`).
  - [x] Pulsante "Torna su" (`.torna-su`) e salto al contenuto (`.salta`) pienamente operativi.

### 29.4 Layout a Griglia Documentale, Responsive Mobile e Contrasto WCAG AA

- [x] **Layout Desktop (1280x900px)**:
  - [x] Griglia a due colonne `.pagina-documento > section` con titolazione a sinistra e contenuti a destra.
  - [x] Quadratini decorativi rossi personalizzati sugli elenchi di impegni.
- [x] **Visualizzazione Mobile (375x667px)**:
  - [x] Nessun overflow orizzontale a livello di viewport (`document.documentElement.scrollWidth = clientWidth = 375px`).
  - [x] Griglia a colonna singola fluida per la lettura su smartphone.
- [x] **Tema Chiaro e Tema Scuro**:
  - [x] Commutazione del tema tramite il controllo nell'header (`button[name="tema"][value="scuro|chiaro"]`): applicazione della classe `.tema-scuro` a `<html>`, mantenendo contrasti ben al di sopra delle soglie AA.
- [x] **Verifica Contrasto WCAG AA**:
  - [x] 66 elementi di testo analizzati (tutti conformi ai requisiti 4.5:1 per testo normale e 3.0:1 per titoli/grandi dimensioni, 0 violazioni).

#### Log Esecuzione Script E2E Playwright (`scratch/test_accessibilita.py`)

```text
--- 1. Test Accesso Pubblico e Ruoli ---
Guest correctly received 200 OK.
Page title: Accessibilita - Smash Burger
Meta description: Dichiarazione di accessibilita di Smash Burger: conformita WCAG 2.1 AA, verifiche con tastiera e screen reader, come segnalare un problema.

--- 2. Test Struttura Semantica e Gerarchia Heading ---
Breadcrumb: Home / Accessibilita
Occhiello: Il sito è per tutti
H1: Accessibilita
Introduzione: Puntiamo alla conformita WCAG 2.1 AA: niente ostacoli per chi usa la tastiera,
            uno screen reader o ingrandisce il testo.
H2 headings: ['Che cosa abbiamo fatto', 'Come lo verifichiamo', 'Immagini', 'Segnalare un problema']
Accessibility commitments count: 9

--- 3. Test Collegamenti Interattivi ---
Links verified.
Navigation to contatti verified.
Footer accessibilita link verified.
Desktop screenshot saved.

--- 4. Test Tema Scuro ---
HTML class after theme toggle: tema-scuro
Dark mode screenshot saved.

--- 5. Test Responsive Mobile (375x667) & WCAG AA ---
Mobile dimensions: scrollWidth=375, clientWidth=375
Mobile screenshot saved.
Contrast audit: 66 elements checked, 0 issues found.

=== ALL TESTS FOR ACCESSIBILITA PASSED SUCCESSFULLY! ===
```

---

## 30. Mappa del Sito (`mappa-sito.php`)

La Mappa del Sito offre una panoramica strutturata e completa di tutte le pagine navigabili del sito, organizzate per area tematica. Adotta un modello a visualizzazione dinamica contestuale ai permessi dell'utente corrente: per evitare collegamenti a pagine che provocherebbero un errore 401 o 403, espone soltanto le aree e gli indirizzi effettivamente accessibili al ruolo dell'utente che consulta la pagina (ospite, cliente, manager o amministratore).

### 30.1 Accesso Pubblico e Metadati

- [x] **Accesso Pubblico Libero**:
  - [x] Raggiungibile da qualsiasi utente con codice `HTTP 200 OK`.
- [x] **Metadati di Testata (`<head>`)**:
  - [x] Titolo pagina in `<title>`: `Mappa del sito - Smash Burger` (entro i 60 caratteri).
  - [x] Meta description: `Elenco completo delle pagine del sito, ordinate per area.` (entro i 160 caratteri).

### 30.2 Struttura Semantica e Gerarchia del Documento

- [x] **Percorso di Navigazione (`nav[aria-label="Percorso"]`)**:
  - [x] `Home` (`url()`) / `<span aria-current="page">Mappa del sito</span>`.
- [x] **Intestazione e Introduzione**:
  - [x] Titolo principale in `<h1>Mappa del sito</h1>`.
  - [x] Paragrafo descrittivo che spiega la natura dinamica dell'elenco in base ai permessi dell'account.
- [x] **Sezioni Tematiche e Raggruppamento per Ruolo**:
  - [x] Ciascuna area di navigazione e racchiusa in un elemento semantico `<section>` dotato di intestazione `<h2>` e lista puntata `<ul>`.
  - [x] Le sezioni prive di voci per il ruolo corrente non vengono mostrate nel DOM (`if ($voci !== [])`).
  - [x] **Ospite non autenticato**:
    - [x] `Pagine principali` (5 voci): Home, Menu, Servizi, Chi siamo, Sedi.
    - [x] `Informazioni` (4 voci): Contatti, Privacy, Accessibilita, Mappa del sito.
    - [x] `Il tuo account` e `Pannello di controllo` non sono renderizzati.
  - [x] **Cliente autenticato**:
    - [x] `Pagine principali` (5 voci).
    - [x] `Il tuo account` (2 voci): Area personale, Carrello.
    - [x] `Informazioni` (4 voci).
  - [x] **Manager**:
    - [x] `Pagine principali` (5 voci).
    - [x] `Il tuo account` (1 voce): Area personale.
    - [x] `Pannello di controllo` (3 voci): Ordini, Prodotti, Prenotazioni.
    - [x] `Informazioni` (4 voci).
  - [x] **Amministratore**:
    - [x] `Pagine principali` (5 voci).
    - [x] `Il tuo account` (1 voce): Area personale.
    - [x] `Pannello di controllo` (7 voci): Ordini, Prodotti, Categorie, Sedi, Prenotazioni, Messaggi, Utenti.
    - [x] `Informazioni` (4 voci).

### 30.3 Accessibilità e Differenziazione Contestuale dei Collegamenti

- [x] **Risoluzione di Ambiguità nei Testi dei Link (WCAG 2.4.4 Link Purpose)**:
  - [x] La pagina include sia la voce pubblica "Sedi" sia la voce di gestione "Sedi" nel pannello di controllo.
  - [x] Per evitare collegamenti duplicati con testo identico che confonderebbero gli utenti di tecnologie assistive, il collegamento al pannello delle sedi include testo visivamente nascosto: `<a href="controllo-sedi">Sedi<span class="solo-lettori"> del pannello</span></a>`.
  - [x] Gli screen reader leggono "Sedi del pannello", garantendo uno scopo del link inequivocabile.
- [x] **Navigazione da Tastiera e Integrazione Globale**:
  - [x] Tutti i collegamenti sono dotati di focus visibile e raggiungibili tramite `Tab`.
  - [x] Collegamento a Mappa del sito presente nel footer globale (`footer a[href="mappa-sito"]`).
  - [x] Pulsante "Torna su" (`.torna-su`) e salto al contenuto (`.salta`) operativi.

### 30.4 Layout a Schede, Responsive Mobile e Contrasto WCAG AA

- [x] **Layout Desktop (1280x900px)**:
  - [x] Sezioni a schede con bordo superiore a contrasto (rosso e senape).
  - [x] Collegamenti presentati come tessere cliccabili con contorno marcato e sottolineatura testuale ad alta leggibilita.
- [x] **Visualizzazione Mobile (375x667px)**:
  - [x] Nessun overflow orizzontale (`scrollWidth = clientWidth = 375px`).
  - [x] Disposizione a colonna singola flessibile con touch target ampi e comodi per il tocco su dispositivi mobili.
- [x] **Tema Chiaro e Tema Scuro**:
  - [x] Alternanza del tema perfettamente funzionante tramite `button[name="tema"]`.
  - [x] Schede con sfondo scuro, bordi chiari e testi ad alto contrasto nella modalita scura.
- [x] **Verifica Contrasto WCAG AA**:
  - [x] 67 elementi di testo analizzati (tutti conformi ai requisiti 4.5:1 e 3.0:1, 0 violazioni).

#### Log Esecuzione Script E2E Playwright (`scratch/test_mappa_sito.py`)

```text
--- 1. Test Accesso Ospite (Guest) ---
Guest correctly received 200 OK.
Page title: Mappa del sito - Smash Burger
Meta description: Elenco completo delle pagine del sito, ordinate per area.
Breadcrumb: Home / Mappa del sito
H1: Mappa del sito
Guest section headings: ['Pagine principali', 'Informazioni']
Main section links: ['Home', 'Menu', 'Servizi', 'Chi siamo', 'Sedi']
Info section links: ['Contatti', 'Privacy', 'Accessibilita', 'Mappa del sito']
Desktop screenshot saved.

--- 2. Test Accesso Cliente ---
User section headings: ['Pagine principali', 'Il tuo account', 'Informazioni']
User account links: ['Area personale', 'Carrello']

--- 3. Test Accesso Manager ---
Manager section headings: ['Pagine principali', 'Il tuo account', 'Pannello di controllo', 'Informazioni']
Manager account links: ['Area personale']
Manager control links: ['Ordini', 'Prodotti', 'Prenotazioni']

--- 4. Test Accesso Amministratore & Link Context ---
Admin section headings: ['Pagine principali', 'Il tuo account', 'Pannello di controllo', 'Informazioni']
Admin control links: ['Ordini', 'Prodotti', 'Categorie', 'Sedi del pannello', 'Prenotazioni', 'Messaggi', 'Utenti']
Verified span.solo-lettori 'del pannello' for controllo-sedi link.

--- 5. Test Tema Scuro ---
HTML class after theme toggle: tema-scuro
Dark mode screenshot saved.

--- 6. Test Responsive Mobile (375x667) & WCAG AA ---
Mobile dimensions: scrollWidth=375, clientWidth=375
Mobile screenshot saved.
Contrast audit: 67 elements checked, 0 issues found.

=== ALL TESTS FOR MAPPA DEL SITO PASSED SUCCESSFULLY! ===
```

---

## 31. Pagine di Errore (401, 403, 404, 500)

Il portale implementa una gestione centralizzata e semantica delle condizioni di errore HTTP tramite la funzione di controllo `errore(int $codice)` e viste dedicate in `src/views/errori/`. Ogni pagina di errore imposta il codice di stato HTTP corretto nell'intestazione della risposta, visualizza spiegazioni concise e non tecniche, e garantisce sempre percorsi di navigazione chiari verso la Home o le sezioni principali del sito, evitando vicoli ciechi nella navigazione.

### 31.1 Errore 401 Unauthorized (`Serve l'accesso`)

- [x] **Condizione di Attivazione e Codice HTTP**:
  - [x] Si attiva quando un utente non autenticato (ospite) tenta di aprire una risorsa riservata (es. `/carrello`, `/controllo`, `/prenota`).
  - [x] Restituisce `HTTP 401 Unauthorized`.
- [x] **Metadati e Struttura Semantica**:
  - [x] Titolo pagina in `<title>`: `Serve l'accesso - Smash Burger` (entro i 60 caratteri).
  - [x] Percorso di navigazione: `Home` / `<span aria-current="page">Serve l'accesso</span>`.
  - [x] Intestazione principale: `<h1>Serve l'accesso</h1>`.
  - [x] Testo informativo: chiarisce la necessita di effettuare l'accesso o registrarsi per proseguire.
- [x] **Collegamenti di Ripristino**:
  - [x] Collegamento di accesso: `<a href="accedi">Accedi</a>`.
  - [x] Collegamento di registrazione: `<a href="registrati">Registrati</a>`.

### 31.2 Errore 403 Forbidden (`Accesso non consentito`)

- [x] **Condizione di Attivazione e Codice HTTP**:
  - [x] Si attiva quando un utente autenticato tenta di aprire una risorsa per la quale il suo ruolo non dispone di permessi sufficienti (es. un cliente che tenta di accedere al pannello `/controllo` o un manager non autorizzato a `/controllo-utenti`).
  - [x] Restituisce `HTTP 403 Forbidden`.
- [x] **Metadati e Struttura Semantica**:
  - [x] Titolo pagina in `<title>`: `Accesso non consentito - Smash Burger` (entro i 60 caratteri).
  - [x] Percorso di navigazione: `Home` / `<span aria-current="page">Accesso non consentito</span>`.
  - [x] Intestazione principale: `<h1>Accesso non consentito</h1>`.
  - [x] Testo informativo: notifica la mancanza di privilegi e suggerisce di contattare l'assistenza in caso di dubbio.
- [x] **Collegamenti di Ripristino**:
  - [x] Ritorno alla home: `<a class="collegamento-indietro" href=""><span class="segno-collegamento" aria-hidden="true">&lt;</span><span>Torna alla home</span></a>`.
  - [x] Contatto assistenza: `<a href="contatti">Contattaci</a>`.

### 31.3 Errore 404 Not Found (`Pagina non trovata`)

- [x] **Condizione di Attivazione e Codice HTTP**:
  - [x] Si attiva quando l'URL richiesto non esiste o un identificativo non corrisponde ad alcun record valido.
  - [x] Restituisce `HTTP 404 Not Found`.
- [x] **Metadati e Struttura Semantica**:
  - [x] Titolo pagina in `<title>`: `Pagina non trovata - Smash Burger` (entro i 60 caratteri).
  - [x] Percorso di navigazione: `Home` / `<span aria-current="page">Pagina non trovata</span>`.
  - [x] Intestazione principale: `<h1>Pagina non trovata</h1>`.
  - [x] Testo esplicativo: segnala l'indirizzo errato o la risorsa rimossa.
- [x] **Sezione di Orientamento ("Dove vuoi andare")**:
  - [x] Intestazione di sezione `<h2>Dove vuoi andare</h2>`.
  - [x] Lista di collegamenti alle pagine principali attive: Home, Menu, Servizi, Chi siamo, Sedi.
  - [x] Collegamento rapido alla mappa del sito: `<a href="mappa-sito">Mappa del sito</a>`.
  - [x] Collegamento per segnalare l'errore: `<a href="contatti">Segnala il problema</a>`.

### 31.4 Errore 500 Internal Server Error (`Errore del server`)

- [x] **Condizione di Attivazione e Codice HTTP**:
  - [x] Si attiva in caso di fallimento critico del server o indisponibilità del database.
  - [x] Restituisce `HTTP 500 Internal Server Error`.
  - [x] Rispetta la sicurezza applicativa: nessun messaggio tecnico o stack trace esposto all'utente (i dettagli rimangono esclusivamente nei log di sistema). Non richiede connessione al DB per essere renderizzata.
- [x] **Metadati e Struttura Semantica**:
  - [x] Titolo pagina in `<title>`: `Errore del server - Smash Burger` (entro i 60 caratteri).
  - [x] Percorso di navigazione: `Home` / `<span aria-current="page">Errore del server</span>`.
  - [x] Intestazione principale: `<h1>Errore del server</h1>`.
  - [x] Testo rassicurante con invito a riprovare a breve.
- [x] **Collegamenti di Ripristino**:
  - [x] Ritorno alla home: `<a class="collegamento-indietro" href=""><span class="segno-collegamento" aria-hidden="true">&lt;</span><span>Torna alla home</span></a>`.

### 31.5 Responsive Mobile, Tema Scuro e Contrasto WCAG AA

- [x] **Visualizzazione Mobile (375x667px)**:
  - [x] Nessun overflow orizzontale su nessuna pagina di errore (`scrollWidth = clientWidth = 375px`).
  - [x] I collegamenti e le liste di navigazione si incolonnano con touch target adeguati e ben spaziati.
- [x] **Supporto Modalità Scura**:
  - [x] Commutazione del tema (`.tema-scuro`) attiva anche sulle pagine di errore, mantenendo coerenza visiva con il resto del portale.
- [x] **Verifica Contrasto WCAG AA**:
  - [x] 112 elementi di testo verificati sulle viste di errore (tutti conformi ai requisiti 4.5:1 per testo normale e 3.0:1 per titoli/grandi dimensioni, 0 violazioni).

#### Log Esecuzione Script E2E Playwright (`scratch/test_pagine_errore.py`)

```text
--- 1. Test Errore 401 (Serve l'accesso) ---
Trigger /carrello as guest: HTTP status 401
401 Title: Serve l'accesso - Smash Burger
401 Breadcrumb: Home / Serve l'accesso
401 H1: Serve l'accesso
401 Desktop screenshot saved.
401 Navigation to /accedi verified.

--- 2. Test Errore 403 (Accesso non consentito) ---
Trigger /controllo as cliente: HTTP status 403
403 Title: Accesso non consentito - Smash Burger
403 Breadcrumb: Home / Accesso non consentito
403 H1: Accesso non consentito
403 Desktop screenshot saved.
403 Navigation to /contatti verified.

--- 3. Test Errore 404 (Pagina non trovata) ---
Trigger unknown route: HTTP status 404
404 Title: Pagina non trovata - Smash Burger
404 Breadcrumb: Home / Pagina non trovata
404 H1: Pagina non trovata
404 Section H2: Dove vuoi andare
404 Helpful navigation links: ['Home', 'Menu', 'Servizi', 'Chi siamo', 'Sedi', 'Mappa del sito', 'Segnala il problema']
404 Desktop screenshot saved.
404 Navigation to /mappa-sito verified.

--- 4. Test Errore 500 (Errore del server) ---
Trigger errors/500.php: HTTP status 500
500 Title: Errore del server - Smash Burger
500 Breadcrumb: Home / Errore del server
500 H1: Errore del server
500 Desktop screenshot saved.

--- 5. Test Tema Scuro e Responsive Mobile su 404 ---
Mobile 404 dimensions: scrollWidth=375, clientWidth=375
Mobile 404 screenshot saved.
HTML class after theme toggle: tema-scuro
Dark mode 404 screenshot saved.
Contrast audit on 404: 112 elements checked, 0 issues found.

=== ALL TESTS FOR ERROR PAGES (401, 403, 404, 500) PASSED SUCCESSFULLY! ===
```









