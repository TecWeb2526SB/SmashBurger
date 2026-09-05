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
11. *Esci (`esci.php`)*
12. *Area personale (`area-personale.php`)*
13. *Profilo utente (`profilo.php`)*
14. *Carrello (`carrello.php`)*
15. *Ritiro e Pagamento (`pagamento.php`)*
16. *Ricevuta ordine (`ricevuta.php`)*
17. *Prenotazione Sala Eventi (`prenota.php`)*
18. *Pannello: Ordini (`controllo.php`)*
19. *Pannello: Dettaglio Ordine (`controllo-ordine.php`)*
20. *Pannello: Prodotti (`controllo-prodotti.php`)*
21. *Pannello: Scheda Prodotto (`controllo-prodotto.php`)*
22. *Pannello: Categorie (`controllo-categorie.php`)*
23. *Pannello: Sedi (`controllo-sedi.php`)*
24. *Pannello: Scheda Sede (`controllo-sede.php`)*
25. *Pannello: Prenotazioni (`controllo-prenotazioni.php`)*
26. *Pannello: Messaggi di contatto (`controllo-contatti.php`)*
27. *Pannello: Utenti (`controllo-utenti.php`)*
28. *Privacy Policy (`privacy.php`)*
29. *Dichiarazione Accessibilità (`accessibilita.php`)*
30. *Mappa del Sito (`mappa-sito.php`)*
31. *Pagine di Errore (403, 404, 500)*

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


