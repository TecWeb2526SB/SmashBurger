# Smash Burger

Applicazione web PHP/MariaDB per la gestione del sito Smash Burger, del catalogo prodotti, degli ordini e del pannello di controllo multi-filiale.

## Stato dei workflow
![Lighthouse CI](https://github.com/TecWeb2526SB/SmashBurger/actions/workflows/lighthouse.yml/badge.svg)
![Accessibility Check](https://github.com/TecWeb2526SB/SmashBurger/actions/workflows/accessibility.yml/badge.svg)
![W3C Validators](https://github.com/TecWeb2526SB/SmashBurger/actions/workflows/w3c-validators.yml/badge.svg)
![Authenticated Internal Quality](https://github.com/TecWeb2526SB/SmashBurger/actions/workflows/authenticated-quality.yml/badge.svg)
![Quality Dashboard Pages](https://github.com/TecWeb2526SB/SmashBurger/actions/workflows/quality-dashboard-pages.yml/badge.svg)

## Panoramica
Il progetto separa controller PHP, view, risorse frontend e funzioni applicative per mantenere il codice modulare e coerente con le linee guida del corso di Tecnologie Web.

Funzionalita principali:
- navigazione pubblica con pagine informative, sitemap e policy
- autenticazione con ruoli `user`, `branch_manager` e `admin`
- catalogo prodotti con gestione multi-filiale
- pannello di controllo per inventario, forniture, team e snapshot di sede
- controlli di qualita automatici su pagine pubbliche e aree interne

## Stack
- PHP con Apache
- MariaDB 11
- phpMyAdmin per sviluppo locale
- HTML, CSS e JavaScript vanilla
- Docker Compose per ambiente di sviluppo
- GitHub Actions per quality gates e pubblicazione dashboard

## Avvio locale
Prerequisiti:
- Docker e Docker Compose

Passi rapidi:
1. Crea un file `.env` nella root del repository.
2. Imposta almeno queste variabili:

```env
HTTP_PORT=8080
PHPMYADMIN_PORT=8081
DB_NAME=esame_web
DB_USER=utente_fallback
DB_PASSWORD=password_fallback_sicura_123
DB_ROOT_PASSWORD=root_password_sicura_123
```

3. Avvia lo stack:

```bash
docker compose -f docker-compose.develop.yml up -d --build
```

Endpoint locali:
- applicazione: `http://localhost:8080`
- phpMyAdmin: `http://localhost:8081`

## Account di test
- `admin` / `admin`
- `user` / `user`
- `manager.padova` / `manager_padova`

## Quality e CI
La repository usa workflow separati per coprire sia le pagine pubbliche sia le aree autenticate:

- `Lighthouse CI`: performance, accessibilita, best practices e SEO sulle pagine pubbliche scoperte dalla sitemap
- `Accessibility checks (Pa11y)`: controlli WCAG 2AA sulle pagine pubbliche
- `W3C Validators (HTML + CSS)`: validazione del markup renderizzato
- `Authenticated internal quality`: login reale per `user`, `branch_manager` e `admin`, con controlli sulle pagine interne
- `Quality dashboard (Pages)`: pubblicazione di un cruscotto statico su GitHub Pages a partire dagli artifact dei workflow

Il dettaglio consolidato dei risultati non viene piu scritto nel `README`: viene pubblicato su GitHub Pages, cosi la history del repository resta pulita.

## Struttura del repository
```text
.
|-- docker-compose.develop.yml
|-- php/
|-- src/
|   |-- admin*.php
|   |-- includes/
|   |-- styles/
|   |-- views/
|   `-- sitemap.xml
|-- docs/
`-- .github/workflows/
```

## Note utili
- Le pagine pubbliche monitorate dai workflow derivano da [`src/sitemap.xml`](src/sitemap.xml).
- Il nome della sessione applicativa e `smashburger_session`, quindi gli smoke test autenticati non dipendono da `PHPSESSID`.
- Il deploy su TecWeb scrive la configurazione DB in `~/.smashburger-config.php`, leggendo le credenziali da `~/pwd_db_2526.txt`; l'app resta raggiungibile su `http://tecweb.studenti.math.unipd.it/<utente>`.
- Se GitHub CodeQL o code scanning sono attivati lato repository, i relativi risultati restano visibili nella sezione Security/Actions di GitHub e si affiancano ai workflow versionati qui.
