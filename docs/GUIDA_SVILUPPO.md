# Guida allo sviluppo

Come lavorare su questo progetto. Le regole di scrittura del codice stanno in
`CONVENZIONI_CODICE.md`, i requisiti del corso in `VINCOLI_ESAME.md`.

---

## 1. Come è organizzato il codice

Ogni indirizzo del sito corrisponde a un file nella radice di `src/`. Il file è un
controller: carica le risorse comuni, legge e controlla l'input, chiama le funzioni di
dominio e passa i dati già pronti alla vista.

```php
<?php
require_once __DIR__ . '/includes/risorse.php';

mostra_pagina('pubbliche/servizi.php', [
    'titolo' => 'Servizi - Smash Burger',
    'descrizione' => 'Come funziona l\'ordine con ritiro in sede.',
    'pagina' => 'Servizi',
    'breadcrumb' => [['Home', url()], ['Servizi', null]],
]);
```

La vista contiene solo markup, con `if` e `foreach` dove servono e ogni valore stampato
attraverso `e()`. Le query stanno nelle funzioni di dominio, mai nelle viste.

| Cartella | Contenuto |
| --- | --- |
| `includes/` | `configurazione.php`, `database.php`, `risorse.php` |
| `includes/funzioni/` | una coppia di file per area: lettura pubblica e gestione dal pannello |
| `views/template/` | `header.php`, `footer.php`, `breadcrumb.php` |
| `views/<area>/` | viste raggruppate per area: pubbliche, account, ordine, controllo, informazioni |

## 2. Aggiungere una pagina

1. Crea il controller `src/nome-pagina.php` sul modello sopra.
2. Crea la vista in `src/views/<area>/nome-pagina.php`.
3. Se la pagina è pubblica, aggiungila a `src/sitemap.xml`: i controlli di qualità leggono
   da lì l'elenco delle pagine da verificare.
4. Se deve comparire nel menu, aggiungila a `menu_principale()` in
   `includes/funzioni/pagina.php`.
5. Se è riservata, chiama `utente_richiedi_accesso()`, `utente_richiedi_cliente()` oppure
   `utente_richiedi_amministratore()` come prima istruzione dopo le risorse, e aggiungila
   a `.github/scripts/pagine-riservate.json`.

## 3. Aggiungere un'operazione che modifica dati

1. Scrivi la funzione di dominio nel file dell'area, che restituisce
   `['ok' => bool, 'messaggio' => string]` oppure un elenco di errori per campo.
2. Nel controller, verifica `$_SERVER['REQUEST_METHOD'] === 'POST'` e `csrf_valido()`
   prima di qualsiasi effetto.
3. Chiudi con `messaggio_imposta()` e `vai_a()`: dopo una modifica si risponde sempre con
   un redirect.
4. Nel modulo inserisci `campo_csrf()`.
5. Se l'operazione cancella dati, fai passare l'utente da una conferma: il collegamento
   porta alla pagina in `GET` con l'identificativo, la cancellazione avviene in `POST`.

## 4. Interfaccia

I componenti ammessi e le loro varianti sono elencati nelle convenzioni. In pratica:

- le varianti si esprimono con `data-tipo` e `data-stato`, non con classi nuove;
- le icone si richiamano con `icona('nome')` e vivono in `images/icone.svg`;
- le pagine interne si chiudono con `<p class="navigazione-pagina">`, ritorno a sinistra e
  avanzamento a destra;
- i colori si prendono dalle proprietà su `:root`, mai scritti nel corpo del foglio.

Il foglio di stile è uno solo e non ha selettori per `id`. Prima di aggiungere una classe,
controlla se una esistente copre il caso.

## 5. Comportamento

Tutto quello che c'è in `scripts/script.js` migliora qualcosa che già funziona senza. Il
file è diviso in aree separate da un titolo, ogni funzione esce se non trova il suo
elemento, e in caso di errore l'invio torna al browser.

Per aggiornare una parte di pagina senza ricaricarla: metti `data-modulo` sul modulo,
invia con `inviaModulo()` e sostituisci il frammento con `aggiornaPagina()`.

## 6. Database

Lo schema sta in `src/database/schema.sql` e viene caricato al primo avvio del contenitore.
Dopo averlo modificato serve ricreare il volume:

```bash
docker compose -f docker-compose.develop.yml down -v && docker compose -f docker-compose.develop.yml up -d
```

Gli importi sono interi in centesimi. Le date usano `DATETIME`. Le righe degli ordini
congelano nome e prezzo del prodotto al momento dell'acquisto.

## 7. Prima di consegnare una modifica

1. Il markup della pagina passa il validatore W3C ed è ben formato come XML.
2. Pa11y non segnala errori.
3. La pagina funziona con JavaScript disabilitato.
4. La navigazione da tastiera raggiunge ogni comando con il focus visibile.
5. I budget delle convenzioni sono rispettati.

In locale il controllo di accessibilità si esegue con lo stesso script della CI:

```bash
npm install pa11y@8 && node .github/scripts/controlla-accessibilita.mjs
```
