# Analisi degli utenti e delle ricerche

Documento della fase 1 del piano. Risponde alla richiesta dei vincoli d'esame di
analizzare le caratteristiche degli utenti che il sito vuole raggiungere e le ricerche a
cui deve rispondere. Da qui derivano struttura della navigazione, titoli, descrizioni e
testi delle pagine.

---

## 1. Contesto

Smash Burger è una catena di hamburgerie con quattro sedi: Padova, Treviso, Vicenza e
Udine, aperte tutti i giorni dalle 11:30 alle 22:30. Il catalogo è unico per tutte le
sedi ed è diviso in quattro categorie: burger, contorni, bevande, dessert.

Il servizio offerto dal sito è l'ordine online con ritiro in sede a un orario scelto.
Non esiste consegna a domicilio: è una scelta di perimetro, e i testi non devono
lasciare intendere il contrario.

## 2. Classi di utenza

### 2.1 Persona che si informa e non ha un account

Chi è: cliente potenziale che cerca un posto dove mangiare, spesso dal telefono, spesso
mentre è già in giro per la città.

Cosa vuole: capire che cosa si mangia, quanto costa, dove sono i locali, a che ora sono
aperti, se c'è qualcosa per chi non mangia carne, quali allergeni sono presenti.

Cosa deve trovare senza registrarsi: catalogo completo con prezzi, elenco delle sedi con
indirizzo e orari, allergeni dei prodotti, informazioni sui servizi.

Requisiti che ne derivano: il catalogo e' pubblico, la registrazione si chiede solo al
momento dell'ordine, prezzi e allergeni sono sempre visibili, le pagine funzionano su
schermo piccolo.

### 2.2 Cliente registrato che ordina

Chi è: persona che ha già deciso di ordinare e vuole farlo in poche azioni, spesso
nella fascia di pranzo o di cena, con poco tempo.

Cosa vuole: scegliere la sede, aggiungere prodotti, scegliere l'orario di ritiro, pagare,
ricevere una ricevuta con il numero d'ordine, ritrovare gli ordini passati.

Percorso tipico: catalogo, carrello, pagamento, ricevuta.

Requisiti che ne derivano: il percorso di acquisto è lineare e ha una pagina sola per
ritiro e pagamento; il carrello è legato a una sede; ogni passaggio funziona anche senza
JavaScript; l'ordine è consultabile dall'area personale.

### 2.3 Amministratore

Chi è: personale che gestisce il servizio, da computer, in orario di lavoro.

Cosa vuole: vedere gli ordini in arrivo e cambiarne lo stato, tenere aggiornato il
catalogo, correggere l'anagrafica delle sedi e gli orari, gestire gli account.

Requisiti che ne derivano: pannello con quattro sezioni, ognuna con inserimento, modifica
e cancellazione; elenchi in forma di tabella con intestazioni corrette; nessuna interfaccia
diversa da quella del sito pubblico.

### 2.4 Requisiti di accessibilità trasversali

Il sito deve servire anche:

- chi naviga da tastiera, senza usare il mouse;
- chi usa uno screen reader e ha bisogno di intestazioni ordinate, etichette sui campi e
  testi alternativi sulle immagini;
- chi ha una ridotta percezione dei colori e non deve dipendere dal colore per capire lo
  stato di un ordine o la disponibilità di un prodotto;
- chi ingrandisce il testo fino al 200 per cento senza perdere contenuti;
- chi ha una connessione lenta e un telefono vecchio, e ha bisogno di pagine leggere che
  funzionino anche se il JavaScript non parte.

## 3. Ricerche a cui il sito deve rispondere

| Ricerca | Intento | Pagina che risponde |
| --- | --- | --- |
| smash burger padova | trovare il locale | sedi |
| hamburger padova centro | trovare dove mangiare vicino | sedi |
| hamburger treviso, vicenza, udine | trovare il locale in città | sedi |
| smash burger menu prezzi | valutare prima di andare | prodotti |
| hamburger da asporto padova | ordinare e ritirare | home, prodotti |
| ordinare hamburger online ritiro in sede | ordinare | home, carrello |
| hamburger vegano padova | dieta senza carne | prodotti |
| allergeni hamburger | esigenza alimentare | prodotti |
| orari smash burger | sapere se è aperto | sedi |
| che cos'è lo smash burger | curiosità sul prodotto | chi siamo |
| hamburger per gruppi, eventi | organizzare | servizi |

Ricadute pratiche:

1. Le ricerche più frequenti sono locali, quindi città e indirizzi devono comparire nel
   testo delle pagine, non solo dentro una mappa.
2. Prezzi e allergeni sono motivo di visita: stanno nel markup come testo, mai dentro
   un'immagine.
3. La pagina delle sedi e quella dei prodotti sono le due porte d'ingresso principali e
   vanno raggiunte in un clic dal menu.

## 4. Struttura della navigazione

Menu principale, sempre nello stesso ordine: Home, Prodotti, Servizi, Chi siamo, Sedi, poi
Accedi oppure Area personale e Carrello per chi ha fatto l'accesso, e Controllo per
l'amministratore.

Piede di pagina: contatti, Privacy, Accessibilità, Mappa del sito, badge di validazione.

Ogni pagina tranne la home mostra il breadcrumb, così la posizione è sempre chiara.

## 5. Titoli e descrizioni

Il titolo sta entro 60 caratteri, la descrizione entro 160.

| Pagina | Titolo | Descrizione |
| --- | --- | --- |
| home | Smash Burger: ordina online e ritira in sede | Hamburger smash preparati al momento nelle sedi di Padova, Treviso, Vicenza e Udine. Ordina online e scegli l'orario di ritiro. |
| prodotti | Menu e prezzi - Smash Burger | Burger, contorni, bevande e dessert con prezzi e allergeni. Scegli i prodotti e ritirali nella sede che preferisci. |
| servizi | Servizi: asporto, gruppi, eventi - Smash Burger | Ordine con ritiro in sede, proposte per gruppi ed eventi, informazioni su allergeni e modalità di pagamento. |
| chi-siamo | Chi siamo - Smash Burger | Come lavoriamo la carne, come nasce lo smash e come sono organizzate le nostre quattro sedi. |
| sedi | Sedi e orari - Smash Burger | Indirizzi, orari di apertura e indicazioni per il ritiro nelle sedi di Padova, Treviso, Vicenza e Udine. |
| privacy | Privacy policy - Smash Burger | Quali dati raccogliamo, per quali finalità, per quanto tempo e come esercitare i tuoi diritti. |
| accessibilita | Accessibilità - Smash Burger | Stato di conformità del sito alle linee guida WCAG 2.1 AA e modalita' per segnalare un problema. |
| mappa-sito | Mappa del sito - Smash Burger | Elenco completo delle pagine del sito, ordinate per area. |

Le pagine di accesso, area personale, carrello, pagamento, ricevuta e pannello non hanno
scopo di posizionamento: titolo semplice e nessuna descrizione promozionale.

## 6. Criteri per i testi

Valgono dalla fase 5 in poi, quando si scrive il testo definitivo:

1. Nelle pagine interne si scrive solo ciò che serve a capire la pagina e a compiere
   l'azione. Niente racconti, aneddoti, storie di fondatori, date o numeri non
   verificabili.
2. La home è l'unica pagina dove si lavora sul messaggio, e resta sobria.
3. Ogni pagina dichiara in apertura di che cosa si occupa, con parole che una persona
   userebbe cercando.
4. Prezzi, orari e indirizzi si scrivono per esteso, in forma leggibile.
5. Si evita il gergo interno: si scrive "ritiro in sede", non "pickup".
