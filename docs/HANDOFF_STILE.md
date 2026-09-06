# Prompt di handoff: identità visiva e fogli di stile

Da incollare come primo messaggio in una sessione nuova, dedicata solo allo stile.

---

## Contesto

Lavori su **SmashBurger**, sito di una catena di hamburgerie con quattro sedi, progetto
del corso di Tecnologie Web. Il repository sta in `C:\Users\SOS\Desktop\git_projects\SmashBurger`,
ramo **`riscrittura`**.

**L'applicazione è funzionalmente completa e completamente priva di stile.** Trentuno
pagine, dodici tabelle, tre ruoli autenticati. Il markup è stato scritto e validato prima
della presentazione, esattamente perché lo stile potesse lavorare su una struttura
definitiva.

Il tuo compito è **dare un'identità visiva al sito e realizzarla in tre fogli di stile**.
Non tocchi PHP se non per aggiungere markup puramente presentazionale, e in quel caso lo
dichiari prima.

Leggi per primi, in quest'ordine:

1. `REGOLE.md` : vincoli d'esame (parte I, non negoziabili), convenzioni, consegna
2. `docs/ANALISI_REQUISITI.md` : ruoli, funzionalità, tono dei testi, inventario pagine
3. `docs/PIANO_SVILUPPO.md` : fasi già chiuse e cosa resta

## Ambiente

```bash
docker compose -f docker-compose.develop.yml up -d --build
```

Sito su `http://localhost:8080`, phpMyAdmin su `http://localhost:8081`.
Utenze: `admin`/`admin`, `manager`/`manager`, `user`/`user`.

Per ricaricare lo schema da zero serve rimuovere il volume:

```bash
docker compose -f docker-compose.develop.yml down -v && docker compose -f docker-compose.develop.yml up -d --build
```

Verifiche prima di ogni commit:

```bash
bash verifiche.sh
```

Esce con codice diverso da zero al primo controllo fallito. **Non committare mai su un
esito negativo.**

Il validatore W3C via HTTP applica un limite: dopo qualche decina di richieste risponde
con una sfida Cloudflare invece che con JSON. Per validare in blocco conviene usare il
validatore in locale, come fa la CI:

```bash
docker run --rm -v "<cartella con i .html>:/lavoro" eclipse-temurin:17-jre java -jar /lavoro/vnu.jar --errors-only /lavoro/pagine/
```

---

## Come devi lavorare

**Sei un designer professionista, non un esecutore.** Le prime fasi non producono una
riga di CSS: producono decisioni argomentate. Un colore scelto a caso e un colore scelto
per un motivo si distinguono, e in questo progetto la differenza va anche scritta in
relazione, perché la docente chiede esplicitamente se i colori sono stati **testati o
messi a caso**.

Proponi, argomenta, e metti in discussione le richieste quando hai una ragione tecnica o
di merito: è comportamento atteso, non insubordinazione. Chiedi conferma prima di
decisioni che cambiano l'identità del sito.

**Non dare mai per buono un risultato che non hai verificato.** Il progetto ha già
prodotto un bug rimasto invisibile per sei fasi perché tutti i controlli automatici
passavano su una pagina sbagliata ma valida: un menu che non mostrava mai lo stato di
accesso. Le pagine renderizzate vanno guardate, non solo validate.

---

## Fase A: identità del sito

Nessun CSS. Si chiude con un documento, `docs/IDENTITA.md`, e con la tua approvazione
esplicita punto per punto.

**A1. Posizionamento e personalità.** Chi è questo locale e come parla. Il tono dei testi
è già stato scritto con criteri precisi (`ANALISI_REQUISITI.md` §11): pagine di
presentazione che lavorano sul messaggio restando sobrie, pagine tecniche fattuali. La
veste deve dire la stessa cosa. Tre o quattro aggettivi che la definiscono, e altrettanti
che la escludono.

**A2. Riferimenti visivi.** Cosa guardiamo e cosa evitiamo, con motivazioni. Non
scopiazzare: individuare il registro.

**A3. Colore.** Una palette costruita, non raccolta:

- ruolo di ogni colore (identità, azione, conferma, errore, attenzione, neutri);
- **contrasti calcolati** su ogni combinazione testo/sfondo effettivamente usata, con i
  valori riportati in tabella, minimo 4.5:1 per il testo normale e 3:1 per quello grande;
- tema chiaro e tema scuro, ottenuti cambiando i neutri (il tema si applica lato server
  da un cookie, non da script);
- nessuna informazione affidata al solo colore: ogni stato ha già una parola nel markup,
  verifica che sia vero e che resti leggibile.

**A4. Tipografia.** In `src/styles/caratteri/` c'è già **Cabin** (regular e bold, TTF, con
licenza OFL) portato dentro il progetto. Decidi se tenerlo o sostituirlo, sapendo che il
sito non può caricare risorse da altri domini: niente Google Fonts, niente CDN, la CSP lo
vieta. Definisci scala, interlinea (minimo 1.5), misure in unità relative, lunghezza di
riga.

**A5. Spazio, forma, profondità.** Scala delle spaziature, raggi, bordi, ombre, griglia,
punti di rottura. Tutto come proprietà personalizzate su `:root`.

**A6. Forma dei componenti.** Per ognuno dei quindici componenti del catalogo
(`REGOLE.md` §15), come si presenta e come cambia negli stati: riposo, passaggio del
mouse, focus da tastiera, attivo, disabilitato, errore. Il focus visibile è obbligatorio.

**A7. Movimento.** Se e dove. Rispetta `prefers-reduced-motion`.

## Fase B: censimento del markup

Prima di scrivere CSS, guarda cosa c'è davvero. Questo è l'inventario reale, misurato,
non quello dichiarato dai documenti:

**Diciannove classi in uso**, in ordine di frequenza:

| Classe | Usi | Che cos'è |
| --- | --- | --- |
| `solo-lettori` | 33 | testo per i lettori di schermo, tolto dalla vista |
| `etichetta` | 18 | stato di ordine, prenotazione, disponibilità |
| `avviso` | 16 | esito di un'operazione, anche come `<section role="alert">` |
| `navigazione-pagina` | 9 | chiude le pagine interne: indietro a sinistra, avanti a destra |
| `scelte` | 5 | elenco di opzioni con controllo ed etichetta per riga |
| `griglia` | 5 | elenchi di schede |
| `scheda` | 5 | prodotto, sede, ordine |
| `prezzo` | 3 | importo in evidenza |
| `filtri` | 2 | navigazione che restringe un elenco |
| `totale` | 2 | somma in evidenza |
| `incasso`, `quantita`, `scelta-sede`, `riepilogo`, `pulsante`, `salta`, `marchio`, `grafico`, `icona` | 1 | uso singolo |

**Attributi di stato** già nel markup, da usare come selettori invece di aggiungere
classi: `data-tipo` (34 usi: `attenzione`, `errore`, `positivo`, `negativo`),
`data-stato` (24 usi: `errore` sui campi), `data-modulo` e `data-ordine` (punti di
aggancio per il JavaScript della fase 10).

**Elementi strutturali**: 63 `section`, 20 `fieldset`/`legend`, 18 `table`, 6 `nav`,
5 `article`, 4 `details`/`summary`, 4 `address`, 2 `svg`.

**Icone disponibili** in `src/images/icone.svg`, richiamate con `icona('nome')`: `luna`,
`sole`, `menu`, `croce`, `cestino`, `carta`, `contanti`, `matita`, `piu`,
`freccia-sinistra`, `freccia-destra`. Aggiungerne è legittimo.

**Le pagine con più lavoro di progettazione**, da guardare per prime:

- `carrello` con la sede scelta: griglia dei prodotti da toccare per aggiungere, più
  riepilogo in fondo con `<details>` e barra con subtotale e comando di avanzamento;
- `controllo`: tabella degli ordini con moduli dentro le celle, più il riquadro
  dell'incasso con grafico SVG generato dal server;
- `prenota`: tabella delle occupazioni della sala, elenco di orari con voci disabilitate;
- `menu` e `prodotto`: le due pagine su cui si gioca il posizionamento;
- `controllo-categorie` e `controllo-utenti`: moduli collegati ai campi con l'attributo
  `form`, perché un `form` non può attraversare celle di tabella.

## Fase C: fogli di stile

Tre file, tre `<link>` con attributo `media`, già presenti in `views/template/header.php`:

| File | Media | Contenuto |
| --- | --- | --- |
| `src/styles/stile.css` | `screen` | proprietà su `:root`, reset, elementi base, layout, i componenti |
| `src/styles/mobile.css` | `screen and (max-width: 48em)` | solo gli scostamenti |
| `src/styles/stampa.css` | `print` | solo gli scostamenti |

Nessun `@import`, nessun `!important`, nessun selettore per `id`, massimo due livelli di
selettore. Colori e misure solo da proprietà personalizzate.

Ordine di lavoro suggerito: proprietà e base, poi header e footer (compaiono ovunque),
poi i componenti nell'ordine della tabella di `REGOLE.md` §15, poi le pagine con
esigenze proprie, infine mobile e stampa.

## Vincoli che non si negoziano

Vengono dai vincoli d'esame e dalle prescrizioni della docente, riportati per esteso in
`REGOLE.md` parte I:

1. **Nessuno scorrimento orizzontale** a nessuna larghezza. Le tabelle del pannello sono
   il punto più a rischio: sono diciotto e alcune hanno sette colonne con moduli dentro.
2. **Contrasto** minimo 4.5:1 e 3:1, misurato e riportato.
3. **Focus sempre visibile** da tastiera, su ogni controllo.
4. **Lo stile non presuppone script**: il JavaScript arriva nella fase 10 e riusa il
   markup che il server produce già, senza aggiungerne di proprio.
5. **Nessuna risorsa da altri domini**: la CSP non ammette eccezioni.
6. **Solo CSS2 e CSS3 validi** secondo il validatore W3C.
7. **Nessun `style` inline, nessun blocco `<style>`.**
8. Il tema scuro si applica con `prefers-color-scheme` e con una classe sulla radice
   decisa lato server: nessuno script scrive stili.

## Budget

`REGOLE.md` §22 fissa 1600 righe sui tre file e 60 classi distinte. Stato attuale: 0
righe e 19 classi.

**Il budget è indicativo e va discusso, non subìto.** È stato pensato per il codice
funzionale, per impedire che il markup si riempisse di appigli inutili, e quel lavoro è
finito. Componenti puramente presentazionali possono richiedere classi e righe in più:
se serve, si alza il numero e si annota il motivo in `REGOLE.md` §22, come già fatto due
volte durante lo sviluppo. Quello che non cambia è il principio: le classi descrivono il
ruolo del contenuto, mai l'aspetto, e gli stati si esprimono con attributi nativi.

Se aggiungi markup presentazionale alle viste, dichiaralo prima e tieni il codice
funzionale leggibile: chi legge una vista deve continuare a capire che cosa fa la pagina.

## Convenzioni

Italiano ovunque, compresi nomi delle classi e commenti. Caratteri ammessi solo quelli da
tastiera italiana: niente trattini lunghi, virgolette curve, emoji (`REGOLE.md` §11.3;
`verifiche.sh` lo controlla). Commenti oggettivi, che spiegano il funzionamento e non
citano conversazioni o decisioni di gruppo.

Commit in italiano, una riga sola, prefisso `feat:`, `fix:`, `docs:` o `chore:`.
**Solo commit locali: il push lo decide chi ti sta seguendo.** Chiedi conferma sui
messaggi finché non ricevi autorizzazione a scriverli in autonomia.

## Cosa serve alla relazione

Mentre lavori, raccogli il materiale che la docente chiede esplicitamente
(`REGOLE.md` §5.4): perché quei colori e come sono stati verificati, quali strumenti hai
usato, quali test hai fatto oltre a quelli automatici, e se immagini o testi sono stati
prodotti con strumenti di intelligenza artificiale.

## Immagini definitive

Le immagini editoriali e delle quattro sedi sono integrate come WebP sotto 300 KB. Sono
state prodotte con intelligenza artificiale e non rappresentano locali reali; la
provenienza e' dichiarata nella relazione. I testi alternativi descrivono i file
definitivi e il componente `foto-editoriale` mantiene leggibile il testo sovrapposto.

---

## Primo passo

Avvia l'ambiente, apri il sito e **guardalo davvero**, in tutti e quattro gli stati di
accesso: ospite, cliente, manager, amministratore. È privo di stile, quindi vedrai la
struttura nuda: è esattamente il materiale su cui devi lavorare. Poi apri la discussione
sulla fase A1, senza scrivere CSS.
