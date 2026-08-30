# Piano di sviluppo

Riscrittura dell'applicazione sul perimetro fissato da `docs/ANALISI_REQUISITI.md`,
secondo le regole di `REGOLE.md`.

Il ramo `rebuild` contiene una riscrittura completa e funzionante, ma costruita su un
perimetro piu' stretto: due ruoli invece di quattro, solo ritiro in sede, nessuna
prenotazione, nessun modulo di contatto, catalogo unico senza disponibilita' per sede, un
solo foglio di stile. Adeguarla significherebbe toccare quasi ogni file portandosi dietro
scelte fatte per quel perimetro. Si riparte da `src/` vuota, su un ramo nuovo, tenendo
solo gli asset che non si possono ricostruire scrivendo codice.

---

## 1. Decisioni di base

| Ambito | Decisione |
| --- | --- |
| Ramo di lavoro | `riscrittura`, creato da `rebuild`, che resta intatto come riferimento |
| Pattern | Page Controller, Transaction Script, Template View (`REGOLE.md` §12) |
| Fogli di stile | tre: `stile.css`, `mobile.css`, `stampa.css` |
| Comportamento | un solo file, `scripts/script.js` |
| Pagine | 31, elencate in `ANALISI_REQUISITI.md` §14 |
| Tabelle | 12 |
| Ambiente | PHP 8.1 e MariaDB 10.6, come il server di consegna |
| CSS e JavaScript | si scrivono alla fine, quando la struttura di tutte le pagine e' completa |

## 2. Asset da conservare

Lo svuotamento di `src/` conserva solo quello che non si ricostruisce scrivendo codice:

- `images/`: `evento.webp`, `favicon.svg`, `icone.svg`, `locale-interno.webp`,
  `w3ccss.png`, `w3chtml.png`;
- `styles/caratteri/`: `cabin-regular.ttf`, `cabin-bold.ttf`, `LICENZA-OFL.txt`;
- `uploads/prodotti/`: le 19 immagini `.webp` dei prodotti.

Tutto il resto viene rimosso, compresi `.htaccess`, `robots.txt`, `sitemap.xml` e
`site.webmanifest`: sono testo che riscriviamo.

In `src/images/sedi/` stanno quattro **segnaposto** generati, uno per sede
(`padova.webp`, `treviso.webp`, `vicenza.webp`, `udine.webp`), che dichiarano di esserlo
in modo visibile. Servono a scrivere il markup e a far girare i controlli automatici
prima di avere le fotografie vere.

La sostituzione e' un semplice cambio di file, senza toccare il codice: i nomi sono gia'
quelli definitivi. Le fotografie devono restare sotto i 300 KB e la loro provenienza va
dichiarata in relazione (`REGOLE.md` §5.4 punto 7). Finche' sono segnaposto, il testo
alternativo non deve descrivere un locale che nella pagina non si vede: si scrive il testo
definitivo insieme alle fotografie vere, nella fase 8. Il controllo prima della consegna
sta in `REGOLE.md` §31.

## 3. Fasi

Ogni fase termina con il repository in uno stato coerente e verificabile.

### Fase 0: ripartenza pulita

1. Creare il ramo `riscrittura` da `rebuild`.
2. Svuotare `src/` secondo la sezione 2.
3. Allineare la documentazione: fatto, con l'eliminazione di `PIANO_RICOSTRUZIONE.md` e
   `RICOGNIZIONE.md` e l'aggiornamento di `ANALISI_REQUISITI.md` e `REGOLE.md`.

Fine fase: `src/` contiene solo asset binari.

### Fase 1: schema del database

`database/schema.sql` da zero: dodici tabelle, importi in centesimi, chiavi esterne
dichiarate, `CHECK (quantita >= 0)` sulla disponibilita'.

| Tabella | Note |
| --- | --- |
| `utenti` | `ruolo` cliente/manager/amministratore; codice fiscale solo per manager e amministratore; indirizzo di consegna e metodo di pagamento nulli finche' non salvati |
| `sedi` | `manager_id` verso `utenti`, nullable e unica (§13.11); `sala_eventi_disponibile` |
| `orari_sedi` | una fascia per giorno della settimana |
| `categorie` | burger, contorni, bevande, dessert |
| `prodotti` | dati comuni a tutte le sedi, `slug` unico |
| `disponibilita_prodotti` | sede piu' prodotto unici, `disponibile`, `quantita` |
| `carrelli` | `aggiornato_il` regge la scadenza a 15 minuti |
| `righe_carrello` | non copiano il prezzo, si legge dal prodotto |
| `ordini` | modalita' ritiro o domicilio, indirizzo congelato, motivo di annullamento |
| `righe_ordine` | congelano nome e prezzo del prodotto |
| `prenotazioni` | orario di inizio e durata scelti da chi prenota, senza sovrapposizioni per sede |
| `messaggi_contatto` | categoria da elenco chiuso |

`utenti` si crea prima di `sedi`, quindi la chiave esterna non e' circolare.

Dati di esempio: le tre utenze richieste, quattro sedi con orari, quattro categorie, i 19
prodotti corrispondenti alle immagini gia' presenti, la disponibilita' per ogni coppia
sede/prodotto, e qualche ordine, prenotazione e messaggio, senza i quali il pannello e il
grafico dell'incasso non avrebbero nulla da mostrare ai controlli automatici.

### Fase 2: fondamenta

1. `includes/configurazione.php`: costanti, contatti, sessione, `url()`.
2. `includes/pagine.php`: elenco unico delle pagine con slug, ruoli ammessi e posizione
   nel menu. Da qui derivano menu, controllo di accesso, `sitemap.xml` e l'elenco delle
   pagine riservate (`REGOLE.md` §12).
3. `includes/database.php`: connessione PDO, emulazione disattivata.
4. `includes/risorse.php`: soli `require_once`.
5. `includes/funzioni/`: `pagina.php`, `sicurezza.php`, `utenti.php`.
6. `views/template/`: `header.php`, `footer.php`, `breadcrumb.php`.
7. `errors/401.php`, `403.php`, `404.php`, `500.php`: ognuna imposta il proprio codice di
   stato e contiene il menu (`REGOLE.md` §5.1).
8. `src/.htaccess` con riscritture relative, piu' `uploads/.htaccess`, `robots.txt`,
   `site.webmanifest`.
9. `index.php`: home con dati reali.

Fine fase: home e quattro pagine di errore validate W3C e conformi alla sintassi XML.

### Fase 3: pagine pubbliche

`menu`, `prodotto`, `sedi`, `sede`, `servizi`, `chi-siamo`, `contatti`, `privacy`,
`accessibilita`, `mappa-sito`. Funzioni: `catalogo.php`, `sedi.php`, `contatti.php`.

Uno slug inesistente o malformato su `prodotto` e `sede` mostra la nostra 404 con stato
HTTP 404 (`REGOLE.md` §5.1 punto 3). Titolo e descrizione della pagina prodotto si
generano dai suoi dati.

### Fase 4: account

`accedi`, `registrati`, `esci`, `area-personale`, `profilo`. Chi non e' autenticato
riceve la 401, chi lo e' ma non ha i permessi riceve la 403.

### Fase 5: ordine

`carrello`, `pagamento`, `ricevuta`, con funzioni in `carrello.php` e `ordini.php`.
I cinque passi e il comportamento della quantita' stanno in `ANALISI_REQUISITI.md` §5 e
§5.1. Punti delicati: barra fissa in fondo con il dettaglio in `<details>`, scarico della
quantita' in transazione con aggiornamento condizionale, scadenza del carrello a 15
minuti.

### Fase 6: prenotazioni

`prenota.php` e `views/prenotazione/`, funzioni in `prenotazioni.php`. Orario di inizio e
durata si scelgono entrambi, fra un'ora e mezza e tre ore; poiche' gli orari proposti si
sovrappongono fra loro, la difesa e' tutta nel controllo di sovrapposizione. La pagina
mostra una tabella delle occupazioni del giorno, senza dati di chi ha prenotato. Rispetto
di `sala_eventi_disponibile`.

### Fase 7: pannello di controllo

Dieci pagine secondo `ANALISI_REQUISITI.md` §6.1, viste condivise da manager e
amministratore e filtrate per sede.

Ordine di lavoro: `controllo` e `controllo-ordine` per primi, perche' fissano il pattern
di azione di riga e di pagina di dettaglio che tutto il resto riusa; poi prodotti e
categorie; poi sedi; infine prenotazioni, contatti e utenti.

Il controllo del ruolo non si scrive a mano in ogni controller: deriva dai ruoli ammessi
dichiarati in `includes/pagine.php`.

### Fase 8: testi definitivi

Criteri di `ANALISI_REQUISITI.md` §11.

### Fase 9: fogli di stile

Da iniziare solo a fasi 3-8 chiuse. Si parte da un censimento del markup prodotto, per non
stilare casi che non esistono. Verifiche specifiche: nessuno scorrimento orizzontale a
nessuna larghezza, con attenzione alle tabelle del pannello; contrasti misurati e annotati
per la relazione; budget di 1600 righe e 60 classi.

### Fase 10: comportamento

Un solo file, massimo 500 righe. Carrello senza ricaricare, stato ordini sulla riga, orari
al cambio sede, validazione dei moduli, tema, menu su schermo piccolo, piu' l'unico
comportamento nuovo, la segnalazione di sovrapposizione degli slot. I moduli di riga del
pannello riusano `inviaModulo()` e `aggiornaPagina()` senza aggiungere funzioni proprie.

### Fase 11: qualita' e consegna

1. Script per i controlli automatici (§13.21): budget quantitativi e lunghezza di titoli e
   descrizioni.
2. `sitemap.xml` e `pagine-riservate.json` generati da `includes/pagine.php`.
3. Nello script di accessibilita', riempire il carrello subito prima delle pagine che lo
   richiedono: con la scadenza a 15 minuti un ciclo lento lo troverebbe vuoto.
4. Relazione, secondo `REGOLE.md` §5.4 e §7.
5. Deploy, dump del database, archivio per Moodle.

## 4. Verifica

A fine di ogni fase, sullo stack locale ricreato da zero:

```bash
docker compose -f docker-compose.develop.yml down -v && docker compose -f docker-compose.develop.yml up -d --build
```

Si applicano i nove punti di `REGOLE.md` §25.

A fine progetto, i percorsi completi provati a mano con tutte e tre le utenze:

1. ordine con ritiro, dalla scelta della sede alla ricevuta aperta dallo storico;
2. ordine a domicilio, poi annullato con motivo e rimborsato dal pannello, verificando che
   la quantita' torni disponibile;
3. due ordini in parallelo sull'ultimo pezzo, verificando che uno solo vada a buon fine;
4. prenotazione della sala eventi, approvata dal manager della sede;
5. messaggio dal modulo di contatto, preso in carico dall'amministratore;
6. un manager che tenta di aprire dati di una sede non sua, e riceve la 403.

## 5. Modo di lavorare

- Un commit per unita' coerente, messaggi di una riga (`REGOLE.md` §26).
- Nessun commit lascia una pagina non validata.
- Le verifiche di `REGOLE.md` §25 si eseguono prima di ogni commit, non a fine fase.
- Le eccezioni ai budget si discutono e, se accettate, si annotano in `REGOLE.md` §22 con
  la motivazione.
