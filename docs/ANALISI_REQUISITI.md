# Analisi dei requisiti

Definisce ruoli, funzionalità, dati e criteri di contenuto del prodotto finale. È il
documento che fa fede sulle decisioni di perimetro del progetto: assorbe
`docs/ANALISI_UTENTI.md`, che non esiste più come file separato.

Gerarchia dei documenti: `VINCOLI_ESAME.md` e `REGOLE.md` restano vincolanti, sono la
fonte di verità del corso e non sono in discussione. `CONSEGNA.md` descrive una
procedura operativa e non va contraddetto. Questo documento fa fede su tutti gli altri
documenti di analisi e pianificazione del progetto: dove li contraddice, prevale lui.
`docs/PIANO_RICOSTRUZIONE.md` resta da allineare, a valle delle scelte di questo
documento (sezione 15); `docs/RICOGNIZIONE.md` è dichiaratamente storico e non è
interessato da questa analisi.

---

## 1. Contesto

Smash Burger è una catena di hamburgerie con quattro sedi: Padova, Treviso, Vicenza e
Udine, aperte tutti i giorni dalle 11:30 alle 22:30.

Il catalogo di prodotti, nome, prezzo, descrizione, allergeni, immagine e categoria, è
unico per tutte le sedi ed è diviso in quattro categorie: burger, contorni, bevande,
dessert. La disponibilità e la quantità di ogni prodotto sono invece specifiche per
sede, gestite dal manager di quella sede (sezione 2.3).

Il servizio offerto dal sito è l'ordine online, con ritiro in sede o con consegna a
domicilio tramite una società di consegna simulata, a un orario scelto dentro l'apertura
della sede che evade l'ordine. Ogni sede ha inoltre una sala eventi prenotabile per
ricorrenze private.

---

## 2. Ruoli

| Ruolo | Si autentica | Credenziali di prova |
| --- | --- | --- |
| Ospite | no | nessuna |
| Cliente | si | `user` / `user` |
| Manager di sede | si | `manager` / `manager`, su una sede indicata in relazione |
| Amministratore | si | `admin` / `admin` |

I vincoli d'esame richiedono una coppia di credenziali per ogni classe di utenza
presente nel sito: con quattro ruoli servono tre coppie oltre all'accesso libero
dell'ospite.

### 2.1 Ospite

Chi è: persona che cerca un posto dove mangiare o si informa prima di ordinare, spesso
da telefono, spesso già in giro per la città. Cosa vuole: capire che cosa si mangia,
quanto costa, dove sono i locali, a che ora sono aperti, se c'è qualcosa per chi non
mangia carne, quali allergeni sono presenti, senza dover creare un account.

Consulta il menu, la pagina di dettaglio di ogni prodotto (con le sedi dove è
disponibile) e le pagine pubbliche. Non ordina, non prenota, non accede al pannello.

### 2.2 Cliente

Chi è: persona che ha deciso di ordinare o di prenotare la sala eventi, spesso con poco
tempo, nella fascia di pranzo o di cena. Percorso tipico per un ordine: catalogo,
carrello, pagamento, ricevuta.

1. Si registra con nome, cognome, email univoca sulla piattaforma e password.
2. Per ordinare sceglie prima la sede di ritiro o di consegna, poi i prodotti dal
   catalogo di quella sede, solo durante il suo orario di apertura.
3. Sceglie un metodo di pagamento simulato, carta oppure contanti, al momento
   dell'ordine. Può salvare il metodo preferito sul profilo: si salva soltanto
   un'etichetta, mai un numero di carta o altro dato di pagamento reale.
4. Consulta lo storico dei propri ordini e le relative ricevute.
5. Modifica i propri dati, rimuove indirizzo, telefono e metodo di pagamento salvati,
   elimina il proprio account.
6. Prenota la sala eventi di una sede per uno slot di tre ore, non sovrapponibile con
   altre prenotazioni della stessa sala.
7. Invia un messaggio dal modulo di contatto, scegliendo una categoria tra quelle
   previste.
8. Non accede al pannello di gestione: gli ordini si effettuano solo da un profilo
   cliente, mai da un account manager o amministratore.

**Dati del cliente**

| Campo | Note |
| --- | --- |
| nome, cognome | obbligatori |
| email | obbligatoria, univoca su tutta la piattaforma |
| password | vedi 12.2 |
| indirizzo di consegna | via, città, provincia, CAP, paese, telefono; solo se si usa la consegna a domicilio; salvabile |
| metodo di pagamento preferito | solo un'etichetta, carta oppure contanti |

### 2.3 Manager di sede

Chi è: personale che gestisce il servizio quotidiano di una singola sede. Uno per sede.

1. Gestisce gli orari della propria sede.
2. Gestisce la disponibilità e la quantità dei prodotti nella propria sede: rende un
   prodotto disponibile o non disponibile e ne aggiorna manualmente la quantità
   residua. Non tocca nome, prezzo, descrizione, allergeni o immagine del prodotto, che
   restano di competenza dell'amministratore.
3. Approva o rifiuta le prenotazioni della sala eventi della propria sede; può
   dichiarare la sala non disponibile, sospendendo le nuove prenotazioni.
4. Vede gli ordini della propria sede, con le stesse informazioni visibili al cliente, e
   il totale incassato dagli ordini della propria sede negli ultimi 30 giorni, senza
   filtri per prodotto o altra scomposizione, accompagnato da un grafico (vedi 12.6).
5. Annulla o rimborsa un ordine, di ritiro o di domicilio, della propria sede; il
   rimborso è simulato, come il pagamento. Per un ordine a domicilio l'annullamento
   segue una comunicazione della società di consegna simulata (indirizzo non valido,
   destinazione troppo distante, o altro motivo): il sito non calcola da solo la
   fattibilità della consegna, si limita a offrire il comando di annullamento e
   rimborso.

**Dati del manager**

| Campo | Note |
| --- | --- |
| nome, cognome, email, password | obbligatori |
| codice fiscale | obbligatorio |
| sede di riferimento | obbligatoria, una sola, un manager per sede; vedi 13.11 per come si garantisce |

### 2.4 Amministratore

Chi è: personale che gestisce il servizio a livello di tutta la catena, da computer, in
orario di lavoro.

Tutto quello che può fare un manager, su ogni sede, più:

1. Aggiunge e rimuove prodotti dal catalogo, e ne modifica i dati comuni a tutte le sedi
   (nome, prezzo, descrizione, allergeni, immagine, categoria).
2. Crea, modifica e cancella le categorie del catalogo.
3. Gestisce i messaggi in arrivo dal modulo di contatto. I follow up successivi al
   primo si assumono gestiti via email, fuori dal sito.
4. Gestisce gli account di cliente e manager: attivazione, cambio ruolo, cancellazione.

**Dati dell'amministratore**

| Campo | Note |
| --- | --- |
| nome, cognome, email, password | obbligatori |
| codice fiscale | obbligatorio |

---

## 3. Prodotti e categorie

Un prodotto ha due gruppi di dati.

Il primo è comune a tutte le sedi e lo gestisce solo l'amministratore: nome, prezzo,
descrizione, allergeni, immagine, categoria.

Il secondo è specifico per sede e lo gestisce il manager della sede: disponibile o non
disponibile, quantità residua aggiornata a mano. Vive nella tabella `disponibilita_prodotti`
(`sede_id`, `prodotto_id`, `disponibile`, `quantita`). Non esiste scarico automatico
della quantità alla conferma di un ordine: l'aggiornamento resta una scelta manuale del
manager.

Una categoria si crea, modifica e cancella da un modulo dedicato del pannello
amministratore, non in modo implicito scrivendo un nome nuovo nel modulo prodotto: un
refuso creerebbe una categoria duplicata invece di segnalare un errore.

Ogni prodotto ha una propria pagina di dettaglio pubblica, raggiungibile senza accesso:
nome, prezzo, descrizione, allergeni, immagine, e le sedi dove è disponibile in questo
momento, come informazione ("disponibile a Padova e Treviso"), non come controllo di
acquisto. Il menu, la pagina che elenca tutti i prodotti, rimanda a ogni scheda di
dettaglio. Né il menu né la pagina di un prodotto richiedono una sede scelta in
anticipo: quello succede solo quando si avvia un ordine, vedi sezione 5.

---

## 4. Sedi

Indirizzo (via, città, provincia, CAP), telefono, orario di apertura per ogni giorno
della settimana. Lo schema attuale (`orari_sedi`) copre già questo caso e non richiede
modifiche su questo punto.

Ogni sede ha inoltre esattamente una sala eventi: non è una tabella a parte, ma la
colonna `sala_eventi_disponibile` su `sedi`, gestita dal manager (sezione 6).

---

## 5. Ordini

Ritiro in sede: già presente, resta invariato.

Consegna a domicilio: il cliente può scegliere la consegna invece del ritiro, indicando
l'indirizzo salvato o inserito al momento (sezione 2.2). Una società di consegna
simulata prende in carico l'ordine dopo il pagamento; il sito non calcola distanze o
tempi di consegna reali. Se la consegna non può essere effettuata, manager o
amministratore della sede annullano l'ordine e ne simulano il rimborso, sulla base di
una comunicazione esterna alla società di consegna: il sito offre solo il comando, non
verifica da solo la fattibilità.

Per ordinare, il cliente sceglie prima la sede di ritiro o di consegna, poi i prodotti:
il catalogo mostrato in questo passaggio riflette la disponibilità e la quantità reale di
quella sede (sezione 3), con i controlli per aggiungere al carrello. Cambiare sede
durante un ordine in corso aggiorna che cosa risulta disponibile, e il carrello resta
legato alla sede scelta per quell'ordine. Questo passaggio è distinto dalla consultazione
pubblica del menu e della pagina di ogni prodotto, che restano uguali per tutti e non
richiedono l'accesso.

La scelta dei prodotti avviene sulla pagina del carrello stessa, non su un secondo menu:
in cima resta il riepilogo di quanto già aggiunto, con lo stepper e il pulsante di
rimozione già presenti, e sotto compare la griglia dei prodotti disponibili nella sede
scelta, in forma essenziale (immagine, nome, prezzo), senza aprire la pagina di
dettaglio. Ogni prodotto della griglia è un pulsante che aggiunge una unità al carrello:
un solo modulo, un pulsante per prodotto, lo stesso meccanismo già descritto in
`CONVENZIONI_CODICE.md` §8. Restano cliccabili anche i prodotti già aggiunti, per
aumentarne ancora la quantità.

Il riepilogo, durante la scelta dei prodotti, sta fisso in fondo alla pagina: subtotale a
sinistra, pulsante per procedere a destra. Il dettaglio dei prodotti scelti, con stepper
e rimozione, si apre da una freccia, senza bisogno di JavaScript.

La procedura d'ordine segue cinque passi fissi: scelta della sede, scelta dei prodotti,
scelta tra ritiro e consegna a domicilio, metodo di pagamento, e infine il cliente arriva
allo storico dei propri ordini (sezione 2.2), da cui apre la ricevuta del nuovo ordine.
Ritiro o domicilio e metodo di pagamento restano sulla stessa pagina di conferma, in due
riquadri separati, come già previsto dal piano di ricostruzione.

Il carrello non è persistente: si elimina quando l'ordine viene completato, e scade da
solo se resta inattivo per 15 minuti, per evitare di mostrare un riepilogo con una
disponibilità ormai cambiata. La scadenza si controlla quando il carrello viene
richiesto di nuovo, non con un processo periodico: non serve un'esecuzione ricorrente sul
server.

---

## 6. Prenotazioni della sala eventi

Ogni sede ha esattamente una sala eventi. Il manager, o l'amministratore, può
dichiararla non disponibile: da quel momento la sede non accetta nuove prenotazioni,
finché non viene dichiarata di nuovo disponibile.

Entità separata dagli ordini: una prenotazione non ha righe di prodotto, quindi non
passa per `ordini` e `righe_ordine`, ma per una tabella propria, `prenotazioni`.

Dati previsti: `sede_id`, `utente_id`, `data`, `ora_inizio`, `ora_fine` (durata fissa di
tre ore), `numero_persone`, `stato` (in attesa, approvata, rifiutata, annullata). Due
prenotazioni sulla stessa sala non possono sovrapporsi.

---

## 7. Modulo di contatto

Tabella `messaggi_contatto` (`nome`, `email`, `categoria`, `testo`, `stato`,
`creato_il`). Un elenco chiuso di categorie del messaggio (proposta: ordine,
prenotazione, segnalazione, altro). L'amministratore vede e gestisce solo il primo
messaggio di ogni richiesta.

---

## 8. Ricerche a cui il sito deve rispondere

| Ricerca | Intento | Pagina che risponde |
| --- | --- | --- |
| smash burger padova | trovare il locale | sedi |
| hamburger padova centro | trovare dove mangiare vicino | sedi |
| hamburger treviso, vicenza, udine | trovare il locale in città | sedi |
| smash burger menu prezzi | valutare prima di andare | menu |
| hamburger da asporto padova | ordinare e ritirare | home, menu |
| hamburger a domicilio padova | farsi consegnare a casa | home, menu |
| ordinare hamburger online | ordinare, a ritiro o a domicilio | home, carrello |
| hamburger vegano padova | dieta senza carne | menu |
| allergeni hamburger | esigenza alimentare | menu |
| orari smash burger | sapere se è aperto | sedi |
| che cos'è lo smash burger | curiosità sul prodotto | chi siamo |
| hamburgeria per feste, eventi privati | organizzare una ricorrenza | sedi |

Ricadute pratiche:

1. Le ricerche più frequenti sono locali, quindi città e indirizzi devono comparire nel
   testo delle pagine, non solo dentro una mappa.
2. Prezzi e allergeni sono motivo di visita: stanno nel markup come testo, mai dentro
   un'immagine.
3. La pagina delle sedi e quella dei prodotti sono le due porte d'ingresso principali e
   vanno raggiunte in un clic dal menu.
4. La sala eventi risponde a una ricerca specifica ("hamburgeria per feste private",
   "affittare sala per eventi") e va raggiungibile dalla pagina della sede, non solo
   dall'area personale di chi ha già un account.
5. La pagina di dettaglio di ogni prodotto risponde a ricerche su un prodotto preciso,
   per esempio un burger vegano specifico, meglio del menu generale, ed è consultabile
   senza accesso.

---

## 9. Struttura della navigazione

Menu principale, sempre nello stesso ordine: Home, Menu, Servizi, Chi siamo, Sedi, poi
Accedi oppure Area personale e Carrello per chi ha fatto l'accesso, e Controllo per
manager e amministratore. Il manager vede in Controllo solo la propria sede;
l'amministratore vede tutte le sedi.

La prenotazione della sala eventi si avvia dalla pagina della sede scelta. L'elenco
delle proprie prenotazioni compare in area personale, accanto allo storico degli
ordini.

Piede di pagina: Contatti, Privacy, Accessibilità, Mappa del sito, badge di
validazione.

Ogni pagina tranne la home mostra il breadcrumb, così la posizione è sempre chiara.

---

## 10. Titoli e descrizioni

Il titolo sta entro 60 caratteri, la descrizione entro 160.

| Pagina | Titolo | Descrizione |
| --- | --- | --- |
| home | Smash Burger: ordina online, ritira o fatti consegnare | Hamburger smash preparati al momento nelle sedi di Padova, Treviso, Vicenza e Udine. Ordina online, scegli ritiro o consegna. |
| menu | Menu e prezzi - Smash Burger | Burger, contorni, bevande e dessert con prezzi e allergeni. Scegli i prodotti e ritirali o fatteli consegnare dalla sede che preferisci. |
| servizi | Servizi: asporto, domicilio, eventi - Smash Burger | Ordine con ritiro o consegna a domicilio, sala eventi prenotabile per ricorrenze private, informazioni su allergeni e pagamento. |
| chi-siamo | Chi siamo - Smash Burger | Come lavoriamo la carne, come nasce lo smash e come sono organizzate le nostre quattro sedi. |
| sedi | Sedi e orari - Smash Burger | Indirizzi, orari di apertura e sala eventi prenotabile nelle sedi di Padova, Treviso, Vicenza e Udine. |
| contatti | Contatti e assistenza - Smash Burger | Scrivici per un ordine, una prenotazione o una segnalazione: rispondiamo dal modulo dedicato. |
| privacy | Privacy policy - Smash Burger | Quali dati raccogliamo, per quali finalità, per quanto tempo e come esercitare i tuoi diritti. |
| accessibilita | Accessibilità - Smash Burger | Stato di conformità del sito alle linee guida WCAG 2.1 AA e modalità per segnalare un problema. |
| mappa-sito | Mappa del sito - Smash Burger | Elenco completo delle pagine del sito, ordinate per area. |

Le pagine di accesso, area personale, carrello, pagamento, ricevuta, prenotazione e
pannello non hanno scopo di posizionamento: titolo semplice e nessuna descrizione
promozionale.

La pagina di dettaglio di ogni prodotto genera titolo e descrizione dai propri dati
(nome, categoria, descrizione), non da una riga fissa in questa tabella: risponde a
ricerche sul singolo prodotto, per esempio un burger vegano specifico, meglio del menu
generale.

L'inventario completo delle pagine, con i relativi controller e viste, si decide nella
fase successiva a questa analisi.

---

## 11. Criteri per i testi

Valgono dalla fase di scrittura dei testi definitivi. La distinzione non è più "home
contro tutto il resto", ma pagine pubbliche di presentazione contro pagine tecniche:

1. Le pagine tecniche, cioè quelle di autenticazione e area personale, carrello,
   pagamento, ricevuta, prenotazione, pannello di controllo, pagine di errore, e le
   pagine di utilità privacy, accessibilità e mappa del sito, scrivono solo ciò che
   serve a capire la pagina e a compiere l'azione. Niente racconti, aneddoti, storie di
   fondatori, date o numeri non verificabili. Le pagine di utilità restano fattuali
   anche se pubbliche, per la natura del loro contenuto.
2. Le pagine pubbliche di presentazione, cioè home, menu, servizi, chi siamo, sedi e
   contatti, possono lavorare sul messaggio. Restano comunque sobrie: niente numeri o
   traguardi non verificabili, niente promesse che il servizio non mantiene.
3. Ogni pagina dichiara in apertura di che cosa si occupa, con parole che una persona
   userebbe cercando.
4. Prezzi, orari e indirizzi si scrivono per esteso, in forma leggibile.
5. Si evita il gergo interno: si scrive "ritiro in sede", non "pickup".

---

## 12. Requisiti non funzionali

1. Nessun dato di pagamento reale viene mai raccolto o salvato: solo un'etichetta del
   metodo scelto.
2. La password usa lo stesso insieme di caratteri ammessi definito in
   `CONVENZIONI_CODICE.md` §2.3, lunghezza minima 8 caratteri, nessun limite massimo
   restrittivo.
3. Ambiente PHP 8.1 e MariaDB 10.6, come richiesto da `VINCOLI_ESAME.md`.
4. Accessibilità WCAG 2.1 AA, tre fogli di stile (`stile.css`, `mobile.css`,
   `stampa.css`), un solo file JavaScript, nessuna dipendenza esterna: vedi
   `CONVENZIONI_CODICE.md`. In
   particolare il sito deve restare utilizzabile da chi naviga solo da tastiera, da chi
   usa uno screen reader, da chi ha una ridotta percezione dei colori, da chi ingrandisce
   il testo fino al 200 per cento e da chi ha una connessione lenta o un dispositivo
   datato.
5. Una coppia di credenziali di prova per ogni classe di utenza autenticata: cliente,
   manager, amministratore.
6. Il grafico dell'incasso del manager (2.3) si genera lato server, con SVG incorporato
   nel markup come le icone, senza libreria esterna e senza JavaScript. Il numero a cui
   si riferisce resta in testo semplice e basta da solo a soddisfare l'accessibilità:
   il grafico è decorativo e nascosto ai lettori di schermo, come le icone.

---

## 13. Decisioni prese

Registro delle scelte di perimetro discusse per questo documento. Mantenuto per
tracciabilità: non richiede conferma ulteriore, salvo ripensamenti.

### 13.1 Consegna a domicilio

Confermata, in aggiunta al ritiro in sede. Regola di annullamento fissata: manager e
amministratore annullano e rimborsano un ordine a domicilio su comunicazione esterna
della società di consegna simulata, non su un controllo eseguito dal sito.

### 13.2 Catalogo e disponibilità per sede

Confermata la disponibilità specifica per sede. Reintroduce, in forma più semplice,
quanto rimosso in fase 2 del piano di ricostruzione (`branch_products`,
`branch_inventory`): una sola tabella di raccordo sede/prodotto con disponibilità e
quantità, aggiornata solo a mano dal manager, senza scarico automatico agli ordini.

### 13.3 Incasso visibile al manager

Confermato: totale semplice degli ultimi 30 giorni, nessun filtro per prodotto,
categoria o altra scomposizione.

### 13.4 Sala eventi

Confermato: una sala per sede, con un indicatore di disponibilità che il manager può
attivare o disattivare.

### 13.5 Credenziali del manager

Confermata la coppia condivisa `manager` / `manager`, associata a una sede indicata
nella relazione, invece di una coppia per ogni sede.

### 13.6 Nomi delle tabelle nuove

Confermati senza alternative: `disponibilita_prodotti` (sede, prodotto, disponibilità,
quantità), `prenotazioni` (sala eventi), `messaggi_contatto` (modulo di contatto).

### 13.7 Sala eventi: colonna, non tabella

Confermato: `sala_eventi_disponibile` su `sedi`, nessuna tabella dedicata, perché ogni
sede ha una sola sala e nessun altro attributo oltre alla disponibilità.

### 13.8 Budget delle tabelle

Alzato da 10 a 12 in `CONVENZIONI_CODICE.md` §13: le 9 tabelle attuali più
`disponibilita_prodotti`, `prenotazioni`, `messaggi_contatto`.

### 13.9 Pannello di controllo condiviso

Confermato: il manager usa le stesse viste in `views/controllo/` dell'amministratore,
filtrate per `sede_id`, non un albero di viste separato.

### 13.10 Grafico dell'incasso

Confermato: nessun sedicesimo componente nel catalogo di `CONVENZIONI_CODICE.md` §6. Il
grafico estende il pattern già esistente delle icone, vedi 12.6.

### 13.11 Un manager per sede

Confermato un doppio controllo: vincolo `UNIQUE` a livello di database e verifica anche
lato applicazione, prima di salvare. Il vincolo sta su `sedi.manager_id` (chiave esterna
verso `utenti`, nullabile, unica), non su una colonna `sede_id` in `utenti`: così un
manager può riferirsi ad al più una sede e una sede può avere al più un manager,
garantito direttamente dal database, senza bisogno di un indice parziale che MariaDB
10.6 non offre su una colonna nullable.

### 13.12 Pagina di dettaglio prodotto e scelta dei prodotti nell'ordine

Confermata una pagina di dettaglio pubblica per ogni prodotto, consultabile senza
accesso, con le sedi dove è disponibile mostrate come informazione.

Confermato che la scelta dei prodotti per un ordine non apre un secondo menu: avviene
sulla pagina del carrello, che una volta scelta la sede mostra il riepilogo già esistente
in cima e la griglia dei prodotti di quella sede sotto, con un pulsante di aggiunta per
prodotto invece del collegamento alla pagina di dettaglio. Nessuna pagina nuova, nessun
componente nuovo: la griglia riusa la Scheda, il riepilogo riusa la tabella con lo
stepper già presenti in `carrello.php`.

### 13.13 Passi della procedura d'ordine

Confermato l'ordine fisso: sede, prodotti, ritiro o domicilio, metodo di pagamento,
redirect allo storico. Nessuna pagina di conferma intermedia: la ricevuta si apre dallo
storico, non compare da sola subito dopo il pagamento.

### 13.14 Interfaccia del carrello durante la scelta dei prodotti

Confermata una barra fissa in fondo alla pagina con subtotale e pulsante di conferma; il
dettaglio dei prodotti scelti si apre da una freccia. Corrisponde all'elemento HTML
nativo `<details>`/`<summary>`, che non richiede JavaScript. Non è nel catalogo dei 15
componenti di `CONVENZIONI_CODICE.md` §6: si valuta se aggiungerlo quando si scrive il
CSS.

### 13.15 Carrello non persistente

Confermato: il carrello si elimina al completamento dell'ordine e scade da solo se resta
inattivo per 15 minuti (alzato da 10 per lasciare margine a controlli automatici lenti,
come quello di accessibilità, senza doverlo gestire caso per caso). Nessuna colonna
nuova: si riusa `aggiornato_il`, già prevista dal modello dati del carrello in
`PIANO_RICOSTRUZIONE.md`, aggiornata a ogni modifica e controllata quando il carrello
viene richiesto di nuovo.

### 13.16 Tre fogli di stile

Confermati tre file, come nella richiesta iniziale e non come la versione a due file mai
confermata che era finita in questo documento: `stile.css`, `mobile.css`, `stampa.css`,
nomi italiani coerenti con `stile.css` già esistente. Il budget e il workflow di
validazione sono aggiornati di conseguenza.

### 13.17 Verifica automatica delle soglie

Confermate due verifiche nuove nel workflow `Qualità`, oltre a quelle già esistenti
(validatore Nu, sintassi XML, validatore CSS, Pa11y WCAG2AA, Lighthouse a soglia 90,
invariate):

- un controllo dei budget quantitativi di `CONVENZIONI_CODICE.md` §13 (file e righe di
  CSS e JavaScript, tabelle, peso delle immagini, classi e `id` distinti, questi ultimi
  normalizzati togliendo i suffissi numerici generati per riga), al posto della sola
  verifica manuale prevista oggi;
- un controllo che titolo e descrizione di ogni pagina rispettino i 60 e 160 caratteri
  di §10, sulle pagine già scaricate per il validatore Nu.

Restano manuali i limiti per funzione e per vista (§13), perché contarli bene richiede
di riconoscere i confini di una funzione, non solo le righe. Gli script si scrivono
insieme al codice che controllano, non prima.

---

## 14. Inventario delle pagine

Ventisei controller fissi più due a parametro. Il dettaglio di prodotto e di sede sono
controller a sé, non l'elenco che si apre in un secondo modo: stesso principio già in
uso tra `controllo-prodotti.php`/`controllo-prodotto.php` e
`controllo-sedi.php`/`controllo-sede.php`. L'identificativo passa per query string sullo
`slug` già presente in tabella (`prodotto?slug=...`, `sede?slug=...`), senza bisogno di
una nuova regola in `.htaccess`.

| Area | Pagine | Ruolo minimo |
| --- | --- | --- |
| Pubbliche | home, menu, prodotto, servizi, chi-siamo, sedi, sede, contatti | ospite |
| Account | accedi, registrati, esci, area-personale, profilo | account proprio |
| Ordine | carrello, pagamento, ricevuta | cliente |
| Prenotazione | prenota, avviata dalla pagina della sede scelta | cliente |
| Controllo | controllo (ordini e incasso), controllo-prodotti, controllo-prodotto, controllo-sedi, controllo-sede, controllo-utenti, controllo-prenotazioni, controllo-contatti | manager sulla propria sede, admin su tutte |
| Informazioni | privacy, accessibilità, mappa-sito | ospite |
| Errori | 403, 404, 500 | - |

`prenota` apre una nuova area di viste, `views/prenotazione/`, parallela a `ordine/`.
`controllo-prenotazioni` e `controllo-contatti` entrano nell'area `controllo/` già
esistente, condivisa da manager e amministratore (13.9).

Ventotto pagine in tutto, contro le 22 dell'obiettivo precedente in
`PIANO_RICOSTRUZIONE.md`: coerente con il perimetro esteso deciso in questa analisi, si
aggiorna quel numero quando si rivede il piano (sezione 15).

---

## 15. Ricadute su altri documenti

Effetti delle decisioni di questa analisi su testi già scritti altrove nel progetto, da
sistemare nella sessione di consolidamento della documentazione.

| Documento | Cosa non è più corretto |
| --- | --- |
| `docs/PIANO_RICOSTRUZIONE.md` | prossimo da rivedere: le fasi 2 e 3, segnate completate, coprono un perimetro più stretto di quello deciso qui; il modello dati della sezione 3 non prevede manager, sale eventi, contatti, disponibilità per sede |
| `docs/RICOGNIZIONE.md` | documento storico: non descrive lo stato attuale per definizione, resta com'è finché non si decide altrimenti |
| `docs/CONSEGNA.md` | §3 e §6 parlano di "due utenze" e "nove tabelle": con tre ruoli autenticati e dodici tabelle (sezione 13.6/13.8) i numeri cambiano. Non è una contraddizione procedurale: la procedura di consegna resta valida, sono conteggi da aggiornare quando lo schema è scritto |
| `docs/RELAZIONE.md` | la tabella delle credenziali in prima pagina ha solo due righe, ne serve una terza per il manager |
| `src/sitemap.xml`, `.github/scripts/pagine-riservate.json` | elencano le pagine di oggi, non le ventotto della sezione 14; da rigenerare dall'elenco unico proposto in `CONVENZIONI_CODICE.md` §3 quando esiste |
