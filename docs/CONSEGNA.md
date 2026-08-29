# Consegna

Passi da seguire quando si consegna il progetto. I requisiti completi stanno in
`VINCOLI_ESAME.md`.

---

## 1. Che cosa va consegnato

1. Il sito installato su `tecweb.studenti.math.unipd.it`, nella home di un componente del
   gruppo.
2. Su Moodle, un archivio zip con tutto il codice sorgente, il dump del database e la
   relazione.

## 2. Installazione sul server

Il workflow `Deploy su TecWeb` fa il lavoro a ogni push su `main`, oppure si avvia a mano
da GitHub. Copia il contenuto di `src/`, scrive
`includes/.configurazione-locale.php` con le credenziali del database e imposta i
permessi: 755 sulle cartelle, 644 sui file, `uploads/` scrivibile.

Servono questi segreti nel repository: `TECWEB_SSH_PRIVATE_KEY`, `TECWEB_SSH_USERNAME` e
gli altri già elencati nel workflow.

Dopo il deploy si controlla che il sito risponda su
`http://tecweb.studenti.math.unipd.it/<utente>` e che l'accesso funzioni con le due utenze
di prova.

## 3. Dump del database

Con lo stack locale avviato:

```bash
docker exec webapp_mariadb mariadb-dump -u root -p"$DB_ROOT_PASSWORD" --default-character-set=utf8mb4 esame_web > smashburger.sql
```

Il file contiene le nove tabelle e i dati di esempio, comprese le due utenze richieste.

## 4. Archivio per Moodle

L'archivio contiene il contenuto di `src/`, il dump del database e la relazione in PDF.
Vanno esclusi `.git`, `.env` e i file di sistema.

```bash
zip -r smashburger.zip src smashburger.sql relazione.pdf -x '*.DS_Store'
```

## 5. Prima pagina della relazione

Deve riportare:

- indirizzo web del sito;
- una coppia di credenziali per ogni classe di utenza: `admin` / `admin` per
  l'amministratore, `user` / `user` per il cliente;
- indirizzo email del referente del gruppo.

## 6. Controlli prima di consegnare

- [ ] Il workflow `Qualità` passa: markup, accessibilità, prestazioni.
- [ ] Il sito sul server risponde e l'accesso funziona con entrambe le utenze.
- [ ] Un ordine completo va a buon fine sul server, dal menu alla ricevuta.
- [ ] Il pannello permette inserimento, modifica e cancellazione nelle quattro sezioni.
- [ ] Le pagine funzionano con JavaScript disabilitato.
- [ ] Il dump del database è aggiornato allo schema in `src/database/schema.sql`.
- [ ] La relazione contiene la prima pagina richiesta, l'analisi degli utenti e i ruoli dei
      componenti del gruppo.
