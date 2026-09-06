# Smash Burger

Applicazione web per una catena di hamburgerie con quattro sedi (Padova, Treviso, Vicenza, Udine): catalogo pubblico, ordini con ritiro in sede o consegna a domicilio, prenotazione sale eventi e pannello di controllo multilivello per manager e amministratori.

Progetto per l'insegnamento di Tecnologie Web, anno accademico 2025/2026.

## Stato dei workflow
![Qualità](https://github.com/TecWeb2526SB/SmashBurger/actions/workflows/qualita.yml/badge.svg)
![Deploy su TecWeb](https://github.com/TecWeb2526SB/SmashBurger/actions/workflows/deploy-tecweb.yml/badge.svg)

## Che cosa fa

- **Consultazione pubblica**:
  - Menu completo diviso per quattro categorie (Burger, Contorni, Bevande, Dessert), con prezzi in centesimi, tabelle allergeni ed evidenza degli ingredienti.
  - Pagine di dettaglio per ogni prodotto con foto, descrizione, allergeni e disponibilità per sede (con distinzione tra prodotto esaurito e non presente in carta).
  - Pagine delle sedi con indirizzi, recapiti, orari dettagliati giorno per giorno, indicazioni sul punto di ritiro e presenza della sala eventi.
  - Pagine informative: filosofia e metodo smash ("Schiaccia. Sfrigola. Gira."), servizi offerti, modulo di contatto con categorie, privacy policy, dichiarazione di accessibilità e mappa del sito.
  - Pagine di errore personalizzate (401, 403, 404, 500) integrate con la navigazione per non interrompere il flusso utente.
- **Autenticazione e gestione account**:
  - Registrazione clienti con validazione stringente sia lato client sia lato server.
  - Accesso profilato per tre ruoli: **cliente**, **manager** (legato a una specifica sede) e **amministratore** (globale).
  - Protezione contro attacchi di enumerazione account, rigenerazione dell'identificativo di sessione e uscita via POST con token CSRF.
  - Area personale e modifica credenziali/dati del profilo; per i clienti, storico completo degli ordini passati e delle prenotazioni effettuate.
- **Carrello e ordini**:
  - Carrello multi-prodotto con incremento, decremento e rimozione articoli in tempo reale senza ricaricare la pagina (AJAX con fallback nativo).
  - Scelta della modalità: **ritiro in sede** (con calcolo automatico degli orari disponibili entro le fasce di apertura) oppure **consegna a domicilio** (con indirizzo di recapito).
  - Simulazione del pagamento (carta di credito o contanti) e generazione di ricevuta digitale conforme per la stampa.
- **Prenotazione sale eventi**:
  - Richiesta di prenotazione della sala eventi per le sedi abilitate (Padova, Treviso, Vicenza) con scelta di data, ora e durata.
  - Verifica automatica sul database per impedire sovrapposizioni di orario nella stessa sede.
  - Gestione del ciclo di vita della richiesta (in attesa, approvata, rifiutata, annullata).
- **Pannello di controllo gestionale**:
  - Visibilità profilata: il manager opera sui dati della propria sede; l'amministratore ha controllo completo su tutte le sedi e su tutte le sezioni.
  - **Ordini**: consultazione, filtro per stato/sede, pagina di dettaglio (`controllo-ordine`) e avanzamento dello stato dell'ordine.
  - **Incasso**: calcolo e riepilogo del fatturato complessivo e suddiviso per singola sede.
  - **Prodotti**: creazione, modifica prezzi/descrizioni/allergeni, caricamento nuove immagini e cancellazione con conferma.
  - **Categorie**: aggiunta, modifica e rimozione delle categorie di menu.
  - **Sedi e orari**: modifica informazioni sede, gestione orari di apertura/chiusura per ogni giorno della settimana e abilitazione della sala eventi.
  - **Prenotazioni**: approvazione o rifiuto delle richieste pervenute per le sale eventi.
  - **Messaggi**: lettura delle comunicazioni inviate dagli utenti tramite il modulo contatti e cambio stato (letto/evaso).
  - **Utenti**: gestione account, assegnazione ruoli (cliente, manager, amministratore) e associazione sede.

Le operazioni del carrello e del pannello si compiono senza ricaricare la pagina: lo script JavaScript intercetta l'invio del form, ripete la medesima richiesta e aggiorna la porzione di pagina interessata. Il server PHP rimane l'unico punto in cui le azioni vengono autorizzate, validate ed eseguite nel database.

## Come è fatto

| Ambito | Scelta architetturale |
| --- | --- |
| Linguaggio backend | PHP 8.1 (procedurale puro, 0 classi esterne, compatibilità server TecWeb) |
| Database relazionale | MariaDB 10.6, 12 tabelle in terza forma normale, prepared statement nominati PDO |
| Architettura software | Model-View-Controller (Page Controller, Transaction Script, Template View) |
| Markup | HTML5 conforme ai requisiti di sintassi XML, accessibile (WCAG 2.1 AA) |
| Fogli di stile | 3 file CSS puri (`stile.css`, `mobile.css`, `stampa.css`), nessun framework, nessun preprocessore |
| Comportamento frontend | Un unico file (`scripts/script.js`), unobtrusive, Vanilla JavaScript senza librerie |
| Tipografia e icone | Risorse locali a licenza aperta: font *Archivo* (OFL) e sprite SVG integrato |
| Ambiente di sviluppo | Docker Compose con container Apache-PHP 8.1, MariaDB 10.6 e phpMyAdmin |

Dimensioni del codice: 8.798 righe di PHP (104 file), 3.591 righe di CSS (3 file), 335 righe di JavaScript (1 file).

## Avvio locale

Prerequisiti: Docker e Docker Compose.

1. Crea il file `.env` nella radice del progetto:

```env
HTTP_PORT=8080
PHPMYADMIN_PORT=8081
DB_NAME=esame_web
DB_USER=utente_prova
DB_PASSWORD=password_prova
DB_ROOT_PASSWORD=password_root_prova
```

2. Avvia i container:

```bash
docker compose -f docker-compose.develop.yml up -d --build
```

- Il sito risponde all'indirizzo: `http://localhost:8080`
- phpMyAdmin risponde all'indirizzo: `http://localhost:8081`

Lo schema e i dati di popolamento iniziali vengono applicati in automatico al primo avvio da `src/database/schema.sql`. Per ricreare il database da zero:
```bash
docker compose -f docker-compose.develop.yml down -v && docker compose -f docker-compose.develop.yml up -d --build
```

## Utenze di prova per la correzione

Come richiesto dalle specifiche del corso, sono predisposte tre utenze di test con credenziali identiche per nome utente e password:

| Ruolo | Nome utente | Password | Note operative |
| --- | --- | --- | --- |
| **Amministratore** | `admin` | `admin` | Accesso completo e globale a tutte le sezioni del pannello di controllo e a tutte le sedi |
| **Manager** | `manager` | `manager` | Responsabile della sede di Padova: gestisce ordini, scorte e prenotazioni della propria sede |
| **Cliente** | `user` | `user` | Account registrato: può ordinare, prenotare la sala ed esaminare il proprio storico |

## Struttura delle cartelle

```text
.
|-- docker-compose.develop.yml   Configurazione ambiente multi-container locale
|-- php/Dockerfile               Immagine Apache con PHP 8.1 ed estensioni necessarie
|-- docs/                        Documenti di specifica, analisi requisiti e relazione
|-- .github/                     Workflow CI/CD (validazione W3C, Pa11y, Lighthouse, deploy)
`-- src/                         Codice sorgente pubblicato sul server web
    |-- *.php                    32 Page Controller (uno per ciascun indirizzo/azione)
    |-- includes/                Configurazione, connessione PDO, catalogo pagine e funzioni di dominio
    |   `-- funzioni/            18 file di Transaction Script divisi per area di business
    |-- views/                   44 Template View con solo markup (divise in cartelle per area)
    |   |-- account/             Viste di autenticazione e profilo
    |   |-- controllo/           Viste del pannello gestionale (ordini, prodotti, sedi, ecc.)
    |   |-- errori/              Viste per le risposte HTTP 401, 403, 404, 500
    |   |-- ordine/              Viste per carrello, pagamento e ricevuta
    |   |-- prenotazione/        Viste per la richiesta sala eventi
    |   |-- pubbliche/           Viste delle pagine informative, menu e contatti
    |   `-- template/            Componenti riutilizzati (header, footer, breadcrumb)
    |-- styles/                  Fogli di stile (`stile.css`, `mobile.css`, `stampa.css`) e font
    |-- scripts/                 Unico file JavaScript dell'applicazione (`script.js`)
    |-- images/                  Asset grafici statici di interfaccia (logo, icone, hero, foto sedi)
    |-- uploads/prodotti/        Immagini dinamiche dei prodotti caricate tramite pannello
    `-- database/schema.sql      Schema DDL del database con vincoli e dati dimostrativi
```

### Separazione delle immagini: `src/images/` vs `src/uploads/`

Nel progetto le immagini sono intenzionalmente separate in due cartelle distinte in base alla loro natura e ai requisiti di sicurezza:

1. **`src/images/` (Asset statici di interfaccia)**:
   - Contiene gli elementi visivi immutabili del sito: logo, favicon, icone SVG in sprite, foto delle sedi fisiche, illustrazioni per le pagine di errore e immagini editoriali della home.
   - Fanno parte del codice sorgente del frontend, sono versionati in Git e risiedono sul server con i normali permessi di sola lettura (`644` per i file, `755` per le directory).
2. **`src/uploads/prodotti/` (Caricamenti dinamici / User Generated Content)**:
   - Contiene i file multimediali caricati a runtime dagli amministratori tramite il form di creazione/modifica prodotto (`controllo-prodotto.php`).
   - Richiede permessi di scrittura per il processo web Apache.
   - È protetta da un file [`.htaccess`](src/uploads/.htaccess) dedicato che **disabilita completamente l'esecuzione di script PHP** (`php_flag engine off`, `Options -Indexes -ExecCGI`, rimozione degli handler PHP). Questa misura di sicurezza previene qualsiasi attacco di tipo *Arbitrary File Upload* o *Remote Code Execution (RCE)* sul server.

## Controlli di qualità e validazione

Il workflow di integrazione continua `Qualità` (`.github/workflows/qualita.yml`) viene eseguito a ogni push e verifica automaticamente:

- **Validazione W3C HTML5 e conformità XML**: validatore Nu del W3C e controllo della sintassi XML con `xmllint`.
- **Validazione W3C CSS**: conformità di tutti i fogli di stile tramite il validatore ufficiale del W3C.
- **Accessibilità (WCAG 2.1 AA)**: scansione automatizzata con Pa11y su tutte le pagine pubbliche e su quelle riservate autenticate (simulando l'accesso con cookie reale per cliente, manager e amministratore).
- **Prestazioni e Best Practice**: audit Lighthouse con soglia minima di 90 su Performance, Accessibility, Best Practices e SEO.

## Distribuzione (Deploy)

Il workflow `Deploy su TecWeb` (`.github/workflows/deploy-tecweb.yml`) si attiva ad ogni rilascio sul ramo `main`. Esegue la sincronizzazione sicura via SSH/rsync verso il server `tecweb.studenti.math.unipd.it`, genera la configurazione locale di produzione (`includes/.configurazione-locale.php`) e imposta i corretti permessi Unix.
