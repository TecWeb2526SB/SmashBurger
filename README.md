# Smash Burger

Applicazione web per una catena di hamburgerie con quattro sedi: catalogo pubblico, ordini
con ritiro in sede e pannello di controllo.

Progetto del corso di Tecnologie Web, anno 2025/2026.

## Stato dei workflow
![Qualità](https://github.com/TecWeb2526SB/SmashBurger/actions/workflows/qualita.yml/badge.svg)
![Deploy su TecWeb](https://github.com/TecWeb2526SB/SmashBurger/actions/workflows/deploy-tecweb.yml/badge.svg)

## Che cosa fa

- Pagine pubbliche con menu, prezzi, allergeni, sedi e orari.
- Registrazione, accesso e area personale: lo storico degli ordini e delle prenotazioni
  per i clienti, i propri dati di accesso per tutti.
- Carrello, scelta di sede e orario di ritiro, pagamento simulato con carta o contanti,
  ricevuta.
- Pannello di controllo con sette sezioni: ordini, prodotti, categorie, sedi,
  prenotazioni, messaggi e utenti, ognuna con inserimento, modifica e cancellazione.

Le operazioni del pannello e del carrello si compiono senza ricaricare la pagina: lo
script ripete la stessa richiesta del modulo e rimette in pagina la risposta del server,
che resta l'unico posto in cui i dati vengono validati e scritti. Dove il cambio di un
controllo e' già l'intenzione completa, come lo stato di un ordine o la quantita' di un
prodotto, il modulo parte da solo e non ha pulsante di conferma.

## Come è fatto

| Ambito | Scelta |
| --- | --- |
| Linguaggio | PHP 8.1, la versione del server di consegna |
| Database | MariaDB 10.6, nove tabelle |
| Frontend | HTML5 conforme alla sintassi XML, un solo foglio di stile, un solo file di script |
| Dipendenze | nessuna: niente framework, niente CDN, carattere e icone nel progetto |
| Ambiente locale | Docker Compose con Apache, MariaDB e phpMyAdmin |

Dimensioni: 8476 righe di PHP, 5578 di CSS, 258 di JavaScript.

## Avvio locale

Serve Docker. Crea un file `.env` nella radice:

```env
HTTP_PORT=8080
PHPMYADMIN_PORT=8081
DB_NAME=esame_web
DB_USER=utente_prova
DB_PASSWORD=password_prova
DB_ROOT_PASSWORD=password_root_prova
```

Avvia lo stack:

```bash
docker compose -f docker-compose.develop.yml up -d --build
```

Il sito risponde su `http://localhost:8080`, phpMyAdmin su `http://localhost:8081`.
Lo schema e i dati di esempio vengono caricati al primo avvio; per ricaricarli da zero
serve rimuovere il volume con `docker compose -f docker-compose.develop.yml down -v`.

## Utenze di prova

| Ruolo | Nome utente | Password |
| --- | --- | --- |
| Amministratore | `admin` | `admin` |
| Cliente | `user` | `user` |

## Struttura

```text
.
|-- docker-compose.develop.yml   ambiente locale
|-- php/Dockerfile               immagine Apache con PHP 8.1
|-- src/                         quello che viene pubblicato sul server
|   |-- *.php                    un controller per ogni pagina
|   |-- includes/                configurazione, connessione, funzioni di dominio
|   |-- views/                   markup, diviso per area
|   |-- styles/                  foglio di stile e carattere
|   |-- scripts/                 file di comportamento
|   |-- images/                  immagini del sito e raccolta di icone
|   |-- uploads/prodotti/        immagini dei prodotti
|   `-- database/schema.sql      struttura e dati di esempio
|-- docs/                        documenti di progetto
`-- .github/                     controlli di qualità e deploy
```

## Controlli di qualità

Il workflow `Qualità` gira a ogni push e a ogni pull request, con tre job:

- **markup**: validatore Nu del W3C, verifica della sintassi XML con `xmllint`, validatore
  CSS del W3C;
- **accessibilità**: Pa11y su pagine pubbliche e riservate, standard WCAG 2.1 AA, con
  accesso reale per cliente e amministratore;
- **prestazioni**: Lighthouse sulle pagine pubbliche, soglia 90 sulle quattro categorie.

Le pagine pubbliche controllate arrivano dalla sitemap, che
[`src/sitemap.php`](src/sitemap.php) costruisce dall'elenco unico delle pagine e serve
all'indirizzo `sitemap.xml`; quelle riservate da
[`.github/scripts/pagine-riservate.json`](.github/scripts/pagine-riservate.json). Aggiungere
una pagina pubblica con una descrizione la mette sotto controllo senza toccare la pipeline.

## Documenti

| Documento | Contenuto |
| --- | --- |
| [Regole](REGOLE.md) | vincoli d'esame, convenzioni di codice, procedura di consegna |
| [Analisi dei requisiti](docs/ANALISI_REQUISITI.md) | ruoli, funzionalità, dati, classi di utenza, ricerche, pagine |
| [Piano di sviluppo](docs/PIANO_SVILUPPO.md) | fasi del lavoro e verifica |
| [Relazione](docs/RELAZIONE.md) | scheletro del documento da consegnare |

## Deploy

Il workflow `Deploy su TecWeb` pubblica `src/` nella home dell'account indicato,
scrive la configurazione del database in `includes/.configurazione-locale.php` e sistema i
permessi. Le credenziali arrivano dai segreti del repository.
