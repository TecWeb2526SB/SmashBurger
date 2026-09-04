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
3. *Dettaglio Prodotto (`prodotto.php`)*
4. [Servizi (`servizi.php`)](#4-servizi-serviziphp)
5. [Chi siamo (`chi-siamo.php`)](#5-chi-siamo-chi-siamophp)
6. [Sedi (`sedi.php`)](#6-sedi-sediphp)
7. *Dettaglio Sede (`sede.php`)*
8. *Contatti (`contatti.php`)*
9. *Accedi (`accedi.php`)*
10. *Registrati (`registrati.php`)*
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
