# Ricognizione del codice precedente

Documento storico: descrive il codice com'era prima della ricostruzione, misurato sul
commit `6113b63` di `main`. Serve a spiegare da dove si è partiti e perché sono state
prese certe decisioni; non descrive il codice attuale, per quello valgono il README e la
guida allo sviluppo.

---

## 1. Rami

| Ramo remoto | Commit non presenti in `main` | Esito |
| --- | --- | --- |
| `origin/develop` | 0 | interamente contenuto in `main` |
| `origin/copilot/refactor-php-code-base` | 0 | interamente contenuto in `main` |
| `origin/restyling/UI` | 1 (`cac29ab`) | quel commit è già dentro `page-creation-with-vercel` |
| `origin/page-creation-with-vercel` | 8 | unico ramo con lavoro non presente in `main` |

I commit esclusivi di `page-creation-with-vercel` riguardano la pagina "Chi siamo",
l'integrazione delle sedi in quella pagina, il tema delle pagine di errore e correzioni
alla selezione categorie della pagina prodotti (circa 4900 righe aggiunte, in prevalenza
markup e CSS).

Il ramo locale `page-creation-with-vercel` punta a un commit di `main` e non corrisponde
all'omonimo ramo remoto.

I rami remoti restano tutti al loro posto: il lavoro procede solo su `rebuild`.

## 2. Dimensioni del codice

Totale sorgenti in `src/`: circa 21500 righe.

| File | Righe |
| --- | --- |
| `styles/css/style.css` | 5227 |
| `includes/functions/admin.php` | 2513 |
| `scripts/main.js` | 1582 |
| `views/controllo/pannello.php` | 1481 |
| `includes/functions/shop.php` | 1398 |
| `database/schema.sql` | 763 |
| `views/controllo/forniture-builder.php` | 609 |
| `views/public/homepage.php` | 599 |
| `views/public/servizi.php` | 548 |

Ripartizione: area di controllo (controller `controllo*.php`, viste `views/controllo/`,
`functions/admin.php`) 5875 righe; resto dell'applicazione 7738 righe; CSS 5539 righe;
JavaScript 1582 righe.

## 3. Frontend

- `style.css` contiene 761 blocchi di regole, 390 classi distinte, 31 id e 41 proprietà
  personalizzate.
- Il markup PHP usa 434 valori di classe distinti e 194 id distinti: più classi di quante
  ne definisca il foglio di stile, quindi esistono classi usate nel markup e mai stilate.
- Attributi `style="..."` presenti in 6 punti (`views/public/prodotti.php`,
  `views/controllo/catalogo-prodotto.php`, `views/controllo/pannello.php`).
- Nessun blocco `<style>` e nessun gestore di evento inline (`onclick` e simili).
- Tre blocchi `<script>` inline: `views/template/header.php` (applicazione del tema prima
  del primo paint), `views/public/chi-siamo.php`, `views/public/sedi.php`.
- `main.js` è un unico file con 28 funzioni di inizializzazione, 67 chiamate a
  `querySelector*` e 4 chiamate `fetch` verso `carrello`/`carrello.php`. Contiene sia
  comportamento pubblico (tema, filtri, menu, mappa) sia comportamento del pannello di
  controllo (catalogo, builder forniture).
- I fogli sono aggregati da `styles/resources.css` con tre `@import` (`style.css` e
  `mobile.css` per `screen`, `print.css` per `print`).
- `src/images/` pesa 13 MB su 46 file: 11 immagini superano i 200 KB e cinque superano
  1 MB, oltre il limite di 1 MB indicato in `REGOLE.md`.

## 4. Architettura PHP

Il pattern è un MVC semplificato coerente con `REGOLE.md`:

- controller nella root di `src/` (27 file) che caricano `includes/resources.php` e poi
  chiamano `render_page()`, `render_basic_page()` o `render_admin_page()`;
- viste in `src/views/` raggruppate per area (`public`, `account`, `checkout`,
  `controllo`, `info`, `template`);
- funzioni applicative in `src/includes/functions/` divise in `security`, `auth`, `shop`,
  `admin`, `ui`;
- `includes/config.php` definisce costanti, avvia la sessione `smashburger_session` e
  fornisce `app_route()`, che rimuove l'estensione `.php` dagli URL in accordo con le
  regole di rewrite in `src/.htaccess`.

Punti che complicano la lettura:

- `includes/resources.php` non si limita a includere: contiene anche il gestore POST del
  cambio sede, con risposta JSON per le richieste XHR. Un file di bootstrap che intercetta
  richieste rende il flusso difficile da seguire.
- `views/template/header.php` esegue query, calcola il conteggio carrello, deduce la
  pagina corrente da `REQUEST_URI` e mantiene una lista hardcoded di pagine di controllo.
- `functions/admin.php` accorpa in un solo file catalogo, inventario, forniture, riordino
  automatico, analitiche, ricevute e gestione del team.

## 5. Modello dati

19 tabelle in `src/database/schema.sql`:

`users`, `brand_contacts`, `branches`, `branch_hours`, `categories`, `products`,
`branch_products`, `branch_inventory`, `auto_reorder_policies`, `carts`, `cart_items`,
`orders`, `order_items`, `payment_transactions`, `supply_templates`,
`supply_template_items`, `supply_orders`, `supply_order_items`, `inventory_movements`.

L'inventario non è isolato nell'area di controllo: la disponibilità di un prodotto nel
catalogo pubblico dipende da `branch_inventory.on_hand_qty`, il carrello rifiuta quantità
superiori alla giacenza e `order_place()` chiama `inventory_consume_for_order()` e
`auto_reorder_evaluate_branch()`. Rimuovere l'inventario semplifica quindi anche catalogo,
carrello e conferma ordine, non solo il pannello.

`payment_transactions` registra una simulazione di pagamento che duplica i campi
`payment_method` e `payment_status` già presenti su `orders`.

## 6. Infrastruttura e qualità

- `docker-compose.develop.yml`: Apache/PHP 8.3 (`php/Dockerfile`), MariaDB 11 con
  `schema.sql` montato come script di init, phpMyAdmin. L'ambiente locale è **più recente
  del server di consegna** (PHP 8.1, MariaDB 10.6): codice che funziona in locale può
  rompersi in fase di correzione. Va allineato.
- `src/.htaccess`: pagine di errore, rewrite degli URL senza estensione, header di
  sicurezza compresa una CSP che ammette `'unsafe-inline'` per script e stili.
- Sei workflow GitHub Actions: Pa11y, Lighthouse, validatori W3C, controlli sulle pagine
  autenticate, pubblicazione della dashboard di qualità su Pages, deploy su TecWeb.
- `src/sitemap.xml` elenca 9 pagine pubbliche ed è la fonte delle pagine controllate dai
  workflow.

## 7. Documentazione presente

- `REGOLE.md`: specifiche del corso di Tecnologie Web (vincoli d'esame, non modificabili).
- `docs/GUIDA_SVILUPPO.md`: guida operativa allineata all'architettura attuale.
- `docs/documentazione.md`: descrizione sintetica del progetto.
- `.github/instructions/.instructions.md`: istruzioni per le code review con focus
  sicurezza.
- `AGENTS.md`: presente nel filesystem ma non tracciato, essendo escluso da `.gitignore`.
