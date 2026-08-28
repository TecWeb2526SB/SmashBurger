# Vincoli d'esame

Requisiti stabiliti dal corso di Tecnologie Web. Sono la fonte di verità: in caso di
conflitto prevalgono su `docs/CONVENZIONI_CODICE.md` e su `docs/PIANO_RICOSTRUZIONE.md`.
Il mancato rispetto anche di una sola specifica tecnica comporta la non sufficienza.

---

## 1. Specifiche tecniche obbligatorie

1. Markup in XHTML Strict oppure HTML5. Le pagine HTML5 devono degradare in modo elegante
   e **rispettare la sintassi XML**.
2. Layout realizzato con CSS puri (CSS2 o CSS3). L'uso corretto e ragionevole di Flexbox e
   Grid è valutato positivamente.
3. Separazione completa fra contenuto, presentazione e comportamento.
4. Accessibilità per tutte le categorie di utenti.
5. Contenuti organizzati in modo che ogni utente li reperisca facilmente.
6. Pagine con script PHP che raccolgono e pubblicano dati inseriti dagli utenti,
   **comprese modifica e cancellazione** degli stessi.
7. Controllo dell'input **sia lato client sia lato server**.
8. Dati degli utenti salvati su database, preferibilmente in forma normale.
9. Pagine accessibili indipendentemente dal browser e dalle dimensioni dello schermo; le
   considerazioni sui diversi dispositivi sono valutate positivamente.

## 2. Utenze di prova

Obbligatorie, con login e password identici:

| Classe di utenza | Login | Password |
| --- | --- | --- |
| Amministratore | `admin` | `admin` |
| Utente semplice | `user` | `user` |

Serve una coppia login/password per **ogni** classe di utenza presente nel sito.

## 3. Ambiente del server di consegna

| Componente | Versione sul server |
| --- | --- |
| Sistema operativo | Ubuntu 22.04 |
| PHP | 8.1 |
| Database | MariaDB 10.6 |

Nessuna funzionalità introdotta dopo PHP 8.1 o MariaDB 10.6 può essere usata. L'ambiente
di sviluppo locale deve allinearsi a queste versioni, altrimenti il codice può funzionare
in locale e rompersi sul server di consegna.

## 4. Consegna

- Il sito deve usare **link relativi**, così da poter essere installato su server o
  cartelle diverse. Eventuali operazioni particolari di installazione vanno indicate in
  relazione.
- I file PHP devono avere i permessi corretti.
- Doppia consegna: installazione su `tecweb.studenti.math.unipd.it` nella home di un
  componente del gruppo, e caricamento su Moodle di un archivio zip contenente tutto il
  codice sorgente, il **dump del database** e la relazione.
- La consegna entro il primo appello della sessione di Febbraio vale due punti bonus,
  sommati al voto solo se il progetto è valutato almeno 18.

## 5. Relazione

Documento che accompagna il progetto. Deve contenere, in prima pagina:

- indirizzo web del sito;
- una coppia login/password per ogni classe di utenza;
- indirizzo email del referente del gruppo.

Nel corpo deve illustrare:

- l'analisi iniziale delle caratteristiche degli utenti che il sito intende raggiungere;
- le possibili ricerche sui motori di ricerca a cui il sito deve rispondere;
- le fasi di progettazione, realizzazione e test;
- il ruolo svolto da ciascun componente del gruppo.

## 6. Conseguenze sul progetto

Vincoli che ricadono direttamente sul codice, ripresi nelle convenzioni:

- il markup deve essere sintatticamente XML: elementi vuoti autochiusi, attributi sempre
  quotati, attributi booleani in forma estesa, elementi chiusi nell'ordine corretto;
- ogni modulo di inserimento dati deve avere la controparte di modifica e di
  cancellazione;
- ogni validazione presente lato client deve esistere identica lato server;
- il progetto deve funzionare su PHP 8.1;
- l'analisi degli utenti e delle ricerche va scritta prima dei contenuti, perché
  determina testi, titoli e struttura delle pagine.
