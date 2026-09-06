# Relazione di progetto

Da completare nei punti segnati con `[da compilare]` e da esportare in PDF per la consegna.

---

## Prima pagina

**Indirizzo del sito**: `[da compilare: http://tecweb.studenti.math.unipd.it/<utente>]`

**Credenziali per la correzione**

| Classe di utenza | Login | Password |
| --- | --- | --- |
| Amministratore | `admin` | `admin` |
| Cliente | `user` | `user` |

**Referente del gruppo**: `[da compilare: nome, cognome, indirizzo email]`

**Componenti del gruppo**: `[da compilare]`

---

## 1. Analisi degli utenti e delle ricerche

Il contenuto di questo capitolo sta in `ANALISI_REQUISITI.md`: classi di utenza con obiettivi
e requisiti, elenco delle ricerche a cui il sito risponde con la pagina che le soddisfa,
struttura della navigazione, titoli e descrizioni.

In sintesi, il sito serve tre classi di utenza: chi si informa senza avere un account, chi
ordina, e chi gestisce il servizio. Le ricerche individuate sono in prevalenza locali, per
esempio "smash burger padova", e questo ha determinato tre scelte: le città compaiono nel
testo delle pagine e non solo dentro una mappa, prezzi e allergeni sono testo e non
immagini, menu e sedi si raggiungono in un clic dalla barra di navigazione.

## 2. Progettazione

### 2.1 Struttura dell'applicazione

Pattern Model-View-Controller semplificato. Ogni indirizzo corrisponde a un controller
nella radice di `src/`, che legge e controlla l'input, chiama le funzioni di dominio e
passa i dati alla vista. Le viste contengono solo markup. Le funzioni di dominio sono
divise per area e distinguono la lettura pubblica dalla gestione riservata al pannello.

### 2.2 Base di dati

Nove tabelle in forma normale, con chiavi esterne dichiarate: utenti, sedi, orari delle
sedi, categorie, prodotti, carrelli, righe del carrello, ordini, righe dell'ordine.

Due scelte da motivare: le righe del carrello non copiano il prezzo, che viene letto dal
prodotto, così il totale mostrato è sempre quello corrente; le righe dell'ordine invece
congelano nome e prezzo al momento della conferma, perché un ordine deve restare leggibile
anche se il prodotto cambia o viene cancellato. Gli importi sono interi in centesimi, per
non introdurre errori di arrotondamento.

### 2.3 Interfaccia

Un catalogo chiuso di componenti riusati in tutte le pagine, comprese quelle del pannello.
Le varianti si esprimono con attributi e non con classi nuove. Tre colori di identità con
un significato ciascuno, un colore neutro per l'azione principale, e la coppia chiaro e
scuro ottenuta cambiando solo i neutri. Tutte le misure sono in unità relative.

Le nove immagini editoriali e delle sedi sono state prodotte con intelligenza artificiale:
non rappresentano locali reali della catena. Gli originali sono stati ritagliati nel
rapporto richiesto dai componenti, convertiti in WebP, privati dei metadati e mantenuti
sotto 300 KB. Il testo alternativo e' stato scritto osservando i file definitivi.

`[da compilare: eventuali schizzi o schermate]`

## 3. Realizzazione

### 3.1 Accessibilità

Obiettivo WCAG 2.1 AA. Markup semantico con gerarchia di intestazioni corretta, link per
saltare al contenuto, etichette associate a ogni campo, tabelle con didascalia e
intestazioni di riga e colonna, focus sempre visibile, testo alternativo sulle immagini di
contenuto. Le informazioni non sono mai affidate al solo colore: gli avvisi hanno una
parola iniziale, gli stati hanno un'etichetta testuale.

I contrasti sono stati calcolati su tutte le combinazioni usate: il valore più basso è
5.02 contro un minimo richiesto di 4.5, in entrambi i temi.

### 3.2 Sicurezza

Tutte le query usano prepared statement con parametri nominati. Ogni valore stampato passa
da una funzione di escaping. Ogni richiesta che modifica dati viaggia in POST con token
CSRF e risponde con un redirect. Gli identificativi che arrivano dall'esterno si usano
sempre insieme a un vincolo di proprietà. Le cartelle di codice non sono raggiungibili dal
web. I caricamenti accettano solo JPG, PNG e WebP entro 300 KB e fra 300 e 2000 pixel per
lato; estensione, tipo MIME e dimensioni vengono verificati sul server, il nome e' generato
casualmente e nella cartella dei caricamenti l'esecuzione di script e' disattivata.

### 3.3 Aggiornamenti senza ricaricare la pagina

Le operazioni del pannello e del carrello passano da un unico file di script, che
intercetta l'invio di un modulo, ripete la stessa richiesta che manderebbe il browser e
mette al posto del contenuto presente quello arrivato dal server, avviso di esito
compreso. Il server non espone una seconda rappresentazione dei dati: risponde con la
stessa pagina HTML di sempre, ed e' li' che l'operazione viene autorizzata, validata ed
eseguita.

Dove il cambio di un controllo e' già l'intenzione completa il modulo parte da solo e non
ha pulsante di conferma. Il fuoco torna sul controllo che ha avviato l'operazione, o
sull'avviso quando quel controllo non esiste più; se la richiesta non arriva a
destinazione il modulo viene inviato nel modo normale.

### 3.4 Dimensioni

| Ambito | Righe |
| --- | --- |
| PHP | 8476 |
| CSS | 5578 |
| JavaScript | 258 |

## 4. Test

### 4.1 Controlli automatici

Il repository esegue a ogni modifica tre gruppi di controlli: validatore Nu del W3C e
verifica della sintassi XML sul markup renderizzato di tutte le pagine pubbliche,
validatore CSS del W3C sul foglio di stile, Pa11y sulle pagine pubbliche e su quelle
riservate con accesso reale come cliente e come amministratore, Lighthouse sulle pagine
pubbliche con soglia 90 sulle quattro categorie.

Il controllo di accessibilità copre 21 pagine e prima di verificare carrello e conferma
d'ordine riempie il carrello, così le due pagine vengono controllate nel loro stato utile.

### 4.2 Prove manuali

`[da compilare con gli esiti delle prove finali sul server]`

Percorsi provati durante lo sviluppo: registrazione e accesso, aggiunta al carrello,
modifica delle quantità, conferma dell'ordine con scelta di sede e orario, ricevuta,
modifica del profilo, gestione di ordini, prodotti, sedi, prenotazioni e utenti dal
pannello, aggiornamento delle righe senza ricaricare la pagina, tentativi di accesso a
pagine riservate e a dati di altri utenti.

## 5. Ruoli dei componenti del gruppo

`[da compilare: chi ha fatto cosa]`

## 6. Note per l'installazione

Il sito usa esclusivamente link relativi e può essere installato in una sottocartella
qualsiasi. Servono PHP 8.1 e MariaDB 10.6. Le credenziali del database si scrivono in
`includes/.configurazione-locale.php`, che non fa parte del codice versionato. Lo schema e
i dati di esempio si caricano con il file `database/schema.sql`.
