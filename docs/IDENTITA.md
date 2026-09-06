# Identita' visiva

Documento di chiusura della fase A del lavoro di stile (`docs/HANDOFF_STILE.md`). Non
contiene CSS: fissa le decisioni su cui i tre fogli di stile della fase C si baseranno.
Ogni punto e' numerato come nell'handoff, per poter essere confermato o corretto uno per
uno.

---

## A1. Posizionamento e personalita'

Smash Burger si presenta come un'insegna streetwear applicata a una hamburgeria: energica,
grafica, diretta. La scelta nasce da una decisione esplicita del gruppo, non da un'analisi
del tono dei testi (che restano quelli gia' scritti, sobri e senza numeri inventati): lo
stile aggiunge energia visiva sopra un contenuto che resta fattuale.

| Include | Esclude |
| --- | --- |
| **Grafica** - colore pieno, forme squadrate, tipografia come elemento visivo | **Delicata** - niente pastello, niente linee sottili da editoria raffinata |
| **Diretta** - un'azione, un colore, nessun ornamento fra la persona e il compito | **Nostalgica** - niente diner anni '50, niente citazioni vintage |
| **Concreta** - la palette viene dal prodotto stesso (senape, ketchup, cetriolini) | **Decorativa** - niente illustrazione d'autore, niente texture da poster |
| **Ad alto contrasto** - nero pieno, colori vividi, zero grigi intermedi superflui | **Anonima da catena** - resta riconoscibile, non diventa un tema grafico generico |

## A2. Riferimenti visivi

Registro precisato: non streetwear generico da moda, ma cultura skate e graffiti,
concreta e urbana. Parole chiave del gruppo: muretti, skatepark, colori vividi, giovane,
titoli forti e marcati con qualche sbavatura. Due riferimenti visivi discussi, entrambi
solo come registro stilistico, non come fonte di componenti o immagini:

- [obeyclothing.com](https://obeyclothing.com/): tipografia e blocchi di colore;
- [thrashermagazine.com](https://www.thrashermagazine.com/): nero pieno, logo enorme in
  slab bold, e sotto il nome una riga tracciata a mano invece che una regola dritta. E'
  il riferimento diretto per la "sbavatura": un segno unico e voluto, non una texture
  sparsa.

**Cosa prendiamo:**

- inchiostro quasi nero come colore strutturale, colore vivido riservato a blocchi pieni;
- titoli in maiuscolo, peso pieno (black), tracking stretto sui titoli grandi;
- testo di interfaccia (nav, pulsanti) in maiuscolo, peso bold, tracking leggermente
  aperto;
- badge/pillole a blocco pieno: coincide gia' con il componente **etichetta** del
  catalogo, va solo disegnato piu' grafico invece che reinventato;
- **un segno tracciato a mano**, unico, non ripetuto come texture: un tratto sotto il
  marchio o un titolo di apertura, imperfetto invece che geometrico. Si realizza come SVG
  disegnato a mano, con lo stesso meccanismo gia' in uso per le icone (`icona()`) e per il
  grafico dell'incasso (`grafico_incasso()`): nessuna libreria, nessun font di icone,
  nessuna dipendenza nuova.

**Cosa non prendiamo, e perche':**

- illustrazione d'autore e fotografia da campagna: il progetto non ha budget di
  produzione per questo genere di immagini;
- impaginazione da e-commerce con griglie dense e navigazione a mega-menu: il pannello ha
  tabelle con moduli dentro le celle, che non reggono una grafica pesante;
- texture o pattern ripetuti sullo sfondo delle pagine: costerebbero peso di pagina e
  righe CSS senza un contenuto informativo dietro. Il segno a mano sopra resta
  un'eccezione dichiarata: e' uno solo, ricorre come firma, non riempie lo sfondo.

## Piano immagini

Il progetto e' didattico: la catena non esiste, quindi le immagini delle sedi non
dichiarano un luogo reale e possono restare nel registro fotografico scelto invece che
essere sostituite da fotografie vere. Restano pero' due vincoli fermi: vanno dichiarate
come prodotte con intelligenza artificiale in relazione (`REGOLE.md` §5.4 punto 7), e
ogni file segue i limiti gia' fissati (peso singolo sotto i 300 KB, `alt` descrittivo
scritto quando il file arriva, non prima).

**Trattamento comune** per le immagini nuove (non per le foto prodotto esistenti, gia'
coerenti: cutout isolato su sfondo pieno, colori naturali): fotografia processata ad alto
contrasto, gamma ridotta a nero identita' e crema/bianco piu' **un solo colore
d'accento** della palette per immagine, non una foto a colori pieni. E' il trattamento da
adesivo/serigrafia gia' descritto in A5 per le ombre, applicato alle immagini.

**Soggetto**: dove la scena lo permette (home, sedi), lo sfondo o il contesto della foto
e' urbano nel registro skate/graffiti del gruppo (muretti, asfalto, cemento, un accenno
di skatepark), non una cucina o una sala da pranzo generica. Il prodotto o il gesto dello
smash restano il soggetto principale: lo sfondo urbano da' il mood, non lo sostituisce.

| Dove | Cosa | Accento | Note |
| --- | --- | --- | --- |
| Home, apertura | La pallina di smash nell'istante in cui tocca la piastra rovente: crosta che si forma, vapore. | Ketchup | Immagine grande, il primo elemento visivo della pagina. |
| Home, griglia categorie (4) | Burger, contorni, bevande, dessert: stesso trattamento cutout delle foto prodotto attuali, non il duotono ad alto contrasto. | Sfondo pieno a colore diverso per categoria | Lega la griglia della home a quella del menu, che usa gia' questo linguaggio. |
| Chi siamo | Stesso soggetto dell'hero della home, momento diverso della stessa azione (piastra, fumo). | Senape | Illustra il paragrafo "Che cos'e' lo smash burger". |
| Servizi, apertura | Un'immagine sola in cima alla pagina, rappresentativa del servizio in generale (es. il ritiro pronto sul bancone), non una per ciascuna delle cinque sotto-sezioni. | Cetriolini | Le sotto-sezioni restano affidate a testo e icone: un'immagine per sezione affollerebbe la pagina. |
| Sede, una per sede (4) | Esterno del locale visto dalla strada, con edificio leggibile nella sua interezza e insegna Smash Burger rossa. | Ketchup, senape e cetriolini compaiono negli interni visibili di ogni sede. | Le quattro immagini distinguono le citta' tramite architettura e contesto, mantenendo la stessa identita' cromatica. |
| Pagine di errore | Nessuna fotografia. | - | Un componente grafico SVG, disegnato in fase C insieme al resto: una pagina di errore deve restare leggera e concentrata sul percorso per ripartire (`REGOLE.md` §5.1). |

I file definitivi sono stati portati nel repository, ritagliati nei rapporti dei
componenti e convertiti in WebP sotto 300 KB. Il testo alternativo di ciascuno e' stato
scritto guardando il file effettivo, non anticipandone il contenuto.

## A3. Colore

Quattro colori funzionali, coerenti col concetto: senape, ketchup e cetriolini coprono i
tre stati semantici (rispettivamente attenzione, errore, conferma), il nero copre
identita' e azione. Nessuno dei tre condimenti raddoppia come colore di marchio: un
pulsante di conferma ordine identico nel colore a un avviso di errore avrebbe creato
un'ambiguita' reale, non solo estetica.

I contrasti sono stati calcolati (luminanza relativa WCAG, non un simulatore
approssimativo), non scelti a occhio. La misura decide anche il *ruolo* del colore, non
solo se "va bene": la senape, per esempio, non regge mai come testo, solo come sfondo con
testo scuro sopra.

### Neutri

| Ruolo | Tema chiaro | Tema scuro |
| --- | --- | --- |
| Sfondo | `#FAF7F2` (crema) | `#1A1918` |
| Testo | `#1A1A1A` | `#F2ECE4` |

Contrasto testo/sfondo: 16.3:1 in chiaro, 15.0:1 in scuro. Il tema si ottiene cambiando
solo questi due valori (piu' le varianti di errore/conferma sotto), non i colori
semantici in se'.

### Colori funzionali

| Ruolo | Colore | Uso verificato | Contrasto |
| --- | --- | --- | --- |
| Identita' / azione | `#1A1A1A` (tema chiaro), `#F2ECE4` (tema scuro) | Testo principale, marchio, link, pulsanti primari. Nel tema scuro coincide col neutro di testo: il nero puro sparirebbe sullo sfondo scuro (contrasto 1.05:1), quindi il ruolo passa al chiaro. | 16.3:1 / 15.0:1 su sfondo |
| Attenzione (senape) | `#E8A100` | Solo come sfondo pieno di etichetta/avviso, con testo `#1A1A1A` sopra. Mai come testo diretto (2.1:1, insufficiente) ne' con testo chiaro sopra (1.9:1). | 7.9:1 (sfondo + testo scuro) |
| Errore (ketchup) | `#B3181F` (chiaro) / `#F0555A` (scuro) | Testo diretto (link, `<small>` di errore nei campi) e sfondo pieno con testo chiaro. In tema scuro il rosso si schiarisce: la versione scura come testo diretto sul fondo scuro cadrebbe a 2.6:1. Come sfondo pieno di un'etichetta resta invariato in entrambi i temi, perche' quel contrasto non dipende dallo sfondo di pagina. | 6.4:1 testo chiaro, 5.8:1 sfondo+testo chiaro, 5.1:1 il rosso scurito su fondo scuro |
| Conferma (cetriolini) | `#3F6B24` (chiaro) / `#7CB342` (scuro) | Stessa logica del ketchup: doppio valore fra testo diretto e sfondo pieno, seconda variante per il testo diretto in tema scuro. | 5.9:1 testo chiaro, 5.4:1 sfondo+testo chiaro, 7.0:1 il verde chiarito su fondo scuro |

Nessuno stato e' affidato al solo colore: `etichetta` e `avviso` hanno gia' la parola
scritta nel markup (`attenzione`, `errore`, `positivo`, `negativo`, o il testo
dell'avviso stesso); il colore rinforza, non sostituisce.

## A4. Tipografia

Cabin (regular/bold, arrotondato e amichevole) e' stato sostituito con **Archivo**
(Omnibus-Type, licenza OFL), gia' portato nel progetto in `src/styles/caratteri/`:
`archivo-regular.ttf` (400), `archivo-bold.ttf` (700), `archivo-black.ttf` (900).
Verificata la copertura di lettere accentate italiane, euro e grado sul file reale.
Nessuna dipendenza esterna: tre file statici, nessun font variabile (il descrittore
`font-weight: 100 900` dei font variabili rischia di non validare come CSS3 stretto).

- **Interlinea**: minimo 1.5 ovunque, titoli compresi (vincolo d'esame, `REGOLE.md` §4).
  Il pugno grafico del registro streetwear non viene dall'interlinea stretta ma dal peso
  black, dal maiuscolo e dal tracking: su un titolo di una riga sola l'interlinea larga
  non si nota, su un titolo lungo evita che le righe si tocchino.
- **Maiuscolo**: marchio, le due `<nav>` dell'header, pulsanti, etichette, filtri. Non i
  titoli di contenuto (`h1`-`h6`): i nomi dei prodotti e delle sedi sono dati dinamici,
  anche lunghi, e il maiuscolo diffuso su testo variabile perde leggibilita' invece di
  guadagnarla. Il maiuscolo via `text-transform` non toglie nulla alla lettura per chi
  usa un lettore di schermo: il testo sotto resta quello scritto nel markup.
- **Tracking**: leggermente positivo sul maiuscolo (marchio, nav, pulsanti), leggermente
  negativo sui titoli in peso black.
- **Scala**, in proprieta' personalizzate, riusata sui livelli di intestazione che non
  hanno bisogno di una dimensione propria (`h4`/`h5`/`h6` condividono il gradino di
  `h3` dove compaiono): piccola 0.875rem, testo 1rem, introduttiva 1.25rem, titolo-3
  1.75rem, titolo-2 2.25rem, titolo-1 3rem. `mobile.css` riduce titolo-1 e titolo-2, come
  scostamento dal foglio principale.

## A5. Spazio, forma, profondita'

- **Spaziatura**, scala fissa in `rem`: 0.25, 0.5, 0.75, 1, 1.5, 2.5, 4.
- **Raggi**: quasi squadrato ovunque, coerente col registro grafico. 0.125rem sui
  controlli piccoli (campi, pulsanti, etichette), 0.25rem sulle schede. Niente pillole
  arrotondate: un'etichetta e' un blocco, non una capsula.
- **Bordo**: 0.125rem pieno, piu' presente di una linea sottile da editoria, per dare
  peso grafico a schede, campi e pulsanti secondari.
- **Ombra**: offset netto senza sfocatura (`0.25rem 0.25rem 0 <colore>`), non l'ombra
  morbida da interfaccia software. E' l'ombra da adesivo/serigrafia, coerente con A2, e
  si usa con misura: schede al passaggio del mouse, pulsante primario. Il colore
  dell'ombra segue il colore di identita' del tema attivo.
- **Punto di rottura**: uno solo, gia' fissato nel markup esistente
  (`screen and (max-width: 48em)` in `header.php`), non e' una decisione nuova di questa
  fase.

## A6. Forma dei componenti

Principi comuni a tutti e quindici i componenti del catalogo, prima del dettaglio per
componente che si definisce scrivendo il CSS:

- **Riposo**: bordo visibile, superficie a contrasto con lo sfondo.
- **Passaggio del mouse**: inversione o rinforzo di fondo/testo, mai il solo aggiungere
  un'ombra (chi ingrandisce il testo al 200% o naviga da tastiera non passa mai il mouse).
- **Focus da tastiera**: contorno spesso (0.1875rem), scostato (0.125rem), nel colore di
  identita' del tema attivo. Mai il contorno del browser rimosso senza sostituto, mai piu'
  sottile del contorno di un pulsante primario.
- **Attivo**: piccola traslazione (2px) verso il basso a scatto breve, coerente con il
  gesto fisico dello smash: un feedback che si "sente".
- **Disabilitato**: opacita' ridotta, cursore coerente, mai il solo colore: le voci gia'
  disabilitate nel markup (fasce orarie occupate in `prenota`) hanno gia' la parola
  scritta accanto.
- **Errore**: bordo nel colore di errore piu' testo, mai il colore da solo; il campo che
  arriva con `data-stato="errore"` lo mostra sia nel bordo sia nel testo di aiuto gia'
  associato con `aria-describedby`.

Pulsante primario: sfondo pieno colore di identita', testo chiaro, maiuscolo, peso bold.
Pulsante secondario: bordo pieno colore di identita', sfondo trasparente. Etichetta:
blocco squadrato piccolo, maiuscolo, sfondo pieno del colore di stato. Tabella: bordo
esterno pieno, righe alternate su un neutro leggerissimo per la lettura, contenitore con
scorrimento orizzontale proprio quando la larghezza lo richiede, mai la pagina intera
(`REGOLE.md` §5.3): le tabelle del pannello, fino a sette colonne con moduli dentro, sono
il punto piu' a rischio e vanno verificate una per una in fase C, non solo validate.

## A7. Movimento

Minimo, veloce, mai decorativo: transizioni di 150ms su cambi di sfondo/testo/bordo negli
stati interattivi, piu' la piccola traslazione del pulsante attivo (A6). Nessuna
animazione di ingresso, nessun effetto legato allo scorrimento della pagina: il sito serve
a ordinare, non a scorrere un poster. `prefers-reduced-motion: reduce` azzera tutte le
transizioni, sostituite da cambi di stato istantanei.

---

## Punti aperti, non bloccanti per la fase C

- Le nove immagini effettivamente previste dal layout (tre per la home, chi siamo,
  servizi e quattro sedi) sono integrate e ottimizzate. Il repository conserva solo
  le versioni WebP destinate al sito, non i pesanti file originali di lavorazione.
- Il set di icone attuale (`luna`, `sole`, `menu`, `croce`, `cestino`, `carta`,
  `contanti`, `matita`, `piu`, le due frecce) e' a tratto sottile: in fase C va deciso se
  ridisegnarle piu' piene/grafiche per coerenza con il registro, o se il tratto sottile
  funziona comunque essendo le icone sempre accompagnate da testo.
