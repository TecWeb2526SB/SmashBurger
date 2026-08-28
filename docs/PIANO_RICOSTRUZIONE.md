# Piano di ricostruzione

Riscrittura di PHP, HTML, CSS e JavaScript dell'applicazione.

Documenti collegati: `docs/VINCOLI_ESAME.md` (requisiti obbligatori), `REGOLE.md`
(specifiche del corso), `docs/RICOGNIZIONE.md` (stato di partenza),
`docs/CONVENZIONI_CODICE.md` (regole di scrittura del codice).

---

## 1. Decisioni di base

| Ambito | Decisione |
| --- | --- |
| Ramo di lavoro | `rebuild`, creato da `main`; `main` resta l'ultima versione funzionante fino al merge finale |
| Lingua | italiano ovunque, con le eccezioni elencate nelle convenzioni |
| Foglio di stile | un solo file, `src/styles/stile.css` |
| Comportamento | un solo file, `src/scripts/script.js` |
| Codice inline | nessuno: né stili, né script, né gestori di evento |
| Perimetro del pannello | ordini, prodotti, utenti, sedi |
| Sedi | mantenute con orari e ritiro; catalogo, prezzi e disponibilità unici per tutte le sedi |
| Ruoli | `admin` e `user` (login e password richiesti dai vincoli d'esame) |
| Ingredienti, inventario, forniture, riordino automatico | rimossi dall'applicazione e dal database |
| Ambiente | PHP 8.1 e MariaDB 10.6, come il server di consegna |
| CSS | scritto in un unico passaggio, quando la struttura HTML è completa |

## 2. Perimetro funzionale

### 2.1 Cosa resta

- Pagine informative: home, prodotti, servizi, chi siamo, sedi, privacy, accessibilità,
  mappa del sito, pagine di errore.
- Registrazione, accesso, uscita, profilo modificabile, elenco dei propri ordini.
- Catalogo con categorie, carrello, pagamento con scelta della sede e dell'orario di
  ritiro, pagamento simulato, ricevuta.
- Pannello di controllo con quattro sezioni: ordini, prodotti, sedi, utenti.

### 2.2 Cosa viene rimosso

| Rimosso | Motivo |
| --- | --- |
| Giacenze e movimenti di magazzino | la disponibilità diventa un flag sul prodotto |
| Modelli e ordini di fornitura, relative ricevute | fuori dal dominio dell'applicazione |
| Politiche di riordino automatico | dipendono dall'inventario |
| Catalogo, prezzi e disponibilità per sede | il catalogo è unico |
| Ruolo `branch_manager` e permessi per sede | restano `admin` e `user` |
| Analitiche e indicatori del pannello | non richiesti dalle specifiche |
| Tabella dedicata alle transazioni di pagamento | i due campi restano sull'ordine |

### 2.3 Pagine finali

| Area | File |
| --- | --- |
| Pubblica | `index.php`, `prodotti.php`, `servizi.php`, `chi-siamo.php`, `sedi.php`, `privacy.php`, `accessibilita.php`, `mappa-sito.php` |
| Account | `accedi.php`, `registrati.php`, `esci.php`, `area-personale.php`, `profilo.php` |
| Acquisto | `carrello.php`, `pagamento.php`, `ricevuta.php` |
| Controllo | `controllo.php` (ordini), `controllo-prodotti.php`, `controllo-prodotto.php`, `controllo-sedi.php`, `controllo-utenti.php` |
| Errori | `errors/403.php`, `errors/404.php`, `errors/500.php` |

Il pagamento oggi è diviso in tre pagine (`checkout`, `checkout-ritiro`,
`checkout-pagamento`): diventa una pagina sola con due `fieldset`.

## 3. Modello dati di destinazione

Nove tabelle, nomi in italiano, importi in centesimi:

| Tabella | Contenuto |
| --- | --- |
| `utenti` | nome utente, email, password_hash, ruolo, attivo, creato_il |
| `sedi` | slug, nome, città, indirizzo, telefono, attiva |
| `orari_sedi` | sede_id, giorno_settimana, apertura, chiusura, chiuso |
| `categorie` | nome, slug, ordine |
| `prodotti` | categoria_id, nome, slug, descrizione, allergeni, immagine, prezzo_centesimi, disponibile |
| `carrelli` | utente_id, sede_id, stato, aggiornato_il |
| `righe_carrello` | carrello_id, prodotto_id, quantita, prezzo_centesimi |
| `ordini` | utente_id, sede_id, numero_ordine, ritiro_previsto, stato, metodo_pagamento, stato_pagamento, totale_centesimi, creato_il |
| `righe_ordine` | ordine_id, prodotto_id, nome_prodotto, quantita, prezzo_centesimi |

Scompaiono `brand_contacts` (i contatti diventano costanti di configurazione),
`branch_products`, `branch_inventory`, `auto_reorder_policies`, `payment_transactions`,
`supply_templates`, `supply_template_items`, `supply_orders`, `supply_order_items`,
`inventory_movements`.

`src/database/schema.sql` viene riscritto da zero: struttura, vincoli, chiavi esterne e
dati di esempio (due o tre sedi con orari, tre categorie, una decina di prodotti, gli
utenti `admin` e `user` richiesti dai vincoli d'esame).

## 4. Fasi

Ogni fase termina con il repository in uno stato coerente e verificabile. Il CSS non si
tocca prima della fase 5, il JavaScript prima della fase 6.

### Fase 0: igiene del repository e ambiente (completata)

1. Allineare `main` al remoto e creare il ramo `rebuild` (fatto).
2. Estrarre i testi delle pagine in `docs/contenuti/` come materiale provvisorio. Il
   confronto con `origin/page-creation-with-vercel` non ha prodotto testo esclusivo: quel
   ramo differisce solo per markup e stile.
3. Lasciare invariati i rami remoti: nessuna cancellazione, il lavoro procede solo su
   `rebuild`.
4. Allineare l'ambiente locale al server di consegna: `php/Dockerfile` su PHP 8.1,
   `docker-compose.develop.yml` su MariaDB 10.6.
5. Ricomprimere le immagini sopra i 300 KB e rinominarle in minuscolo con trattini.
6. Svuotare `src/` di tutto ciò che verrà riscritto, lasciando `images/`, `uploads/`,
   `.htaccess`, `robots.txt`, `site.webmanifest`. Il codice rimosso resta nella cronologia
   di `main`.

Fine fase: ambiente allineato al server di consegna e `src/` ridotto agli asset.

### Fase 1: analisi degli utenti e delle ricerche

Richiesta esplicita dei vincoli d'esame e presupposto dei contenuti.

1. Descrizione delle classi di utenza previste (cliente che ordina, cliente che si
   informa, personale di sede) con obiettivi e contesto d'uso.
2. Elenco delle ricerche a cui il sito deve rispondere, associate alle pagine che le
   soddisfano.
3. Da qui derivano: titoli, descrizioni, intestazioni e struttura di navigazione.

Fine fase: capitolo dell'analisi scritto in `docs/` e riusabile nella relazione.

### Fase 2: fondamenta

1. `database/schema.sql` nuovo secondo il punto 3, con dati di esempio.
2. `includes/configurazione.php`: costanti, contatti, sessione, funzione degli URL.
   Nessuna gestione di richieste al suo interno.
3. `includes/database.php`: connessione PDO.
4. `includes/funzioni/`: `sicurezza.php` (CSRF, escaping), `utenti.php`, `catalogo.php`,
   `carrello.php`, `ordini.php`, `sedi.php`.
5. `views/template/`: `intestazione.php`, `pie-pagina.php`, `breadcrumb.php`, senza query
   e senza logica di pagina.
6. Home funzionante, priva di stile, validata W3C e conforme alla sintassi XML.

Fine fase: la home mostra dati reali dal database ed è validata.

### Fase 3: struttura delle pagine pubbliche e utente

Nell'ordine: prodotti, sedi, servizi, chi siamo, pagine informative, accesso e
registrazione, area personale e profilo, carrello, pagamento, ricevuta, pagine di errore.

Per ogni pagina: controller, vista, funzioni di dominio necessarie, validazione W3C,
controllo Pa11y, prova con JavaScript disabilitato.

Fine fase: tutti i percorsi pubblici e utente completi, senza stile e senza JavaScript.

### Fase 4: pannello di controllo

Ogni sezione espone inserimento, modifica e cancellazione, come richiesto dai vincoli
d'esame.

1. `controllo.php`: elenco ordini con filtro per sede e per stato, cambio stato.
2. `controllo-prodotti.php` e `controllo-prodotto.php`: creazione, modifica,
   cancellazione, caricamento immagine con lista consentita di tipi.
3. `controllo-sedi.php`: anagrafica e orari.
4. `controllo-utenti.php`: elenco, attivazione, cambio ruolo, cancellazione.

Ogni azione verifica ruolo e token CSRF lato server e risponde con un redirect.

Fine fase: pannello completo, costruito con gli stessi dodici componenti delle pagine
pubbliche.

### Fase 5: testi definitivi

Durante le fasi 3 e 4 il markup usa il materiale in `docs/contenuti/`, che viene dal sito
attuale ed è provvisorio. Qui si riscrive tutto il testo, prima di passare al foglio di
stile, così lo stile lavora su lunghezze reali.

Criteri:

1. Pagine interne (prodotti, servizi, sedi, pagine informative, area personale, pannello):
   testo essenziale e fattuale. Si dice cosa contiene la pagina e cosa può fare la
   persona che la legge, niente altro.
2. Niente racconti, aneddoti, storie di fondatori, date inventate, numeri di clienti,
   premi o traguardi non verificabili.
3. La home è l'unica pagina dove si lavora sul messaggio, e resta comunque sobria.
4. Titoli e descrizioni delle pagine rispondono alle ricerche individuate nella fase 1.
5. Ogni testo rispetta la sezione 2.3 delle convenzioni sui caratteri ammessi.

Fine fase: nessun testo segnaposto residuo, `docs/contenuti/` non serve più al codice e
resta solo come materiale di partenza.

### Fase 6: foglio di stile

Da iniziare solo a fasi 3, 4 e 5 chiuse.

1. Censimento del markup prodotto: elenco degli elementi e dei componenti realmente usati.
2. `:root` con le proprietà personalizzate (colori chiari e scuri, spaziature, tipografia).
3. Stile degli elementi base, poi dei dodici componenti nell'ordine della tabella delle
   convenzioni.
4. Media query per schermo piccolo e `@media print` in fondo allo stesso file.
5. Verifica dei budget: un solo file, 50 classi, 1200 righe, nessun `id` stilato.

Fine fase: `src/styles/stile.css` unico, un solo `<link>`, validazione CSS W3C senza
errori.

### Fase 7: comportamento

Solo miglioramenti su funzionalità già complete, tutti dentro `src/scripts/script.js`:

1. tema chiaro e scuro (la preferenza si salva in un cookie e viene applicata lato server,
   così non serve script nell'intestazione);
2. aggiornamento delle quantità del carrello senza ricaricare la pagina;
3. apertura del menu su schermo piccolo;
4. validazione dei moduli prima dell'invio, gemella di quella PHP.

Fine fase: un solo file, massimo 400 righe, ogni funzione degrada correttamente.

### Fase 8: qualità, consegna, relazione

1. Aggiornare `src/sitemap.xml` e l'elenco delle pagine controllate dai workflow.
2. Togliere `'unsafe-inline'` dalla CSP in `.htaccess`, ora superflua.
3. Eseguire i controlli: validatori W3C, Pa11y, Lighthouse, pagine autenticate.
4. Verificare i permessi dei file PHP e il deploy su `tecweb.studenti.math.unipd.it`.
5. Produrre il dump del database per la consegna su Moodle.
6. Scrivere la relazione: prima pagina con indirizzo del sito, credenziali per ogni classe
   di utenza ed email del referente; corpo con analisi utenti e ricerche (fase 1),
   progettazione, realizzazione, test e ruoli dei componenti del gruppo.
7. Riscrivere `README.md`, `docs/GUIDA_SVILUPPO.md` e `docs/documentazione.md`
   sull'architettura nuova; archiviare `docs/RICOGNIZIONE.md`.

Fine fase: `rebuild` unito in `main`, progetto installato e consegnato.

## 5. Copertura dei vincoli d'esame

| Vincolo | Dove viene soddisfatto |
| --- | --- |
| HTML5 con sintassi XML | convenzioni sezione 5, verifica a ogni commit |
| Layout con CSS puri, Flex e Grid | fase 6, convenzioni sezione 7 |
| Separazione contenuto/presentazione/comportamento | un solo CSS, un solo JS, nessun inline |
| Accessibilità | convenzioni sezione 11, controlli Pa11y e Lighthouse in ogni fase |
| Reperibilità dei contenuti | fasi 1 e 5, mappa del sito, sitemap, breadcrumb |
| CRUD con modifica e cancellazione | fase 4 su prodotti, sedi, utenti; profilo utente in fase 3 |
| Validazione client e server | convenzioni, sezioni 4 e 8, fase 7 |
| Dati su database in forma normale | fase 2, nove tabelle con chiavi esterne |
| Indipendenza da browser e dimensione schermo | fase 6, media query e unità relative |
| Link relativi | convenzioni sezione 3 |
| Utenze `admin` e `user` | dati di esempio dello schema |
| Relazione con analisi utenti e ricerche | fasi 1 e 8 |
| PHP 8.1 e MariaDB 10.6 | fase 0 |

## 6. Ordine di grandezza atteso

| Ambito | Oggi | Obiettivo |
| --- | --- | --- |
| Righe PHP | ~13600 | 3500 |
| Righe CSS | 5539 su 3 file | 1200 su 1 file |
| Righe JavaScript | 1582 su 1 file | 400 su 1 file |
| Classi CSS distinte | 390 | 50 |
| `id` nel markup | 194 | 30 |
| Tabelle | 19 | 9 |
| Pagine | 27 | 21 |

## 7. Modo di lavorare

- Un commit per unità coerente (una pagina, una funzione di dominio, una sezione di CSS).
- Messaggi di commit in italiano, senza firme di co-autori e senza riferimenti a
  strumenti.
- Commit e push avvengono solo su richiesta esplicita.
- Nessun commit lascia una pagina non validata.
- Le verifiche della sezione 13 delle convenzioni si eseguono prima di ogni commit, non a fine fase.
- Le eccezioni ai budget si discutono e, se accettate, si annotano nelle convenzioni con
  la motivazione tecnica.
