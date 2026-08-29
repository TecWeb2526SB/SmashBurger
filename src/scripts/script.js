/*
 * Comportamento del sito.
 *
 * Il file è unico e viene caricato con defer da tutte le pagine. Ogni funzione parte da
 * un elemento del DOM e, se non lo trova, esce subito: così lo stesso file serve pagine
 * diverse senza controlli aggiuntivi.
 *
 * Tutto quello che c'è qui è un miglioramento di qualcosa che funziona già senza
 * JavaScript. Se una richiesta fallisce, il modulo viene inviato dal browser nel modo
 * consueto.
 */

/* =============================================================================
   Strumenti comuni
   Invio di un modulo senza ricaricare la pagina e sostituzione di un frammento.
   ============================================================================= */

/**
 * Invia un modulo con fetch e restituisce la pagina di risposta già analizzata.
 *
 * Il pulsante premuto viene aggiunto ai dati, perché il browser lo include solo quando
 * l'invio parte da lui.
 */
async function inviaModulo(modulo, pulsante) {
    const dati = new FormData(modulo);

    if (pulsante && pulsante.name) {
        dati.append(pulsante.name, pulsante.value);
    }

    const risposta = await fetch(modulo.action, { method: 'POST', body: dati });

    if (!risposta.ok) {
        throw new Error(`Risposta del server: ${risposta.status}`);
    }

    return new DOMParser().parseFromString(await risposta.text(), 'text/html');
}

/**
 * Sostituisce in pagina il primo elemento che corrisponde al selettore con quello della
 * pagina ricevuta. Restituisce false se uno dei due manca.
 *
 * Viene inserita una copia: spostare il nodo lo toglierebbe dalla risposta, che serve
 * ancora per le sostituzioni successive.
 */
function sostituisci(selettore, pagina) {
    const nuovo = pagina.querySelector(selettore);
    const attuale = document.querySelector(selettore);

    if (!nuovo || !attuale) {
        return false;
    }

    attuale.replaceWith(nuovo.cloneNode(true));

    return true;
}

/**
 * Porta in pagina la risposta del server: prima il frammento richiesto, poi il messaggio
 * di esito.
 *
 * Se il frammento non esiste più nella risposta viene sostituito l'intero contenuto:
 * succede quando l'ultima riga di un elenco viene tolta.
 */
function aggiornaPagina(selettore, pagina) {
    if (!sostituisci(selettore, pagina)) {
        sostituisci('main', pagina);
    }

    aggiornaAvviso(pagina);
    collega();
}

/**
 * Segna un elemento come già collegato a un certo comportamento e dice se lo era di già.
 *
 * Dopo una sostituzione le funzioni vengono richiamate su tutta la pagina: questo
 * controllo evita di collegare due volte lo stesso elemento. La chiave tiene separati i
 * comportamenti, perché sullo stesso modulo ne convivono più di uno.
 */
function giaCollegato(elemento, chiave) {
    if (elemento.dataset[chiave] === 'si') {
        return true;
    }

    elemento.dataset[chiave] = 'si';

    return false;
}

/**
 * Riporta in pagina il messaggio di esito che il server ha messo nella risposta.
 *
 * Se la risposta non porta nessun messaggio, quello eventualmente in pagina viene tolto.
 */
function aggiornaAvviso(pagina) {
    const nuovo = pagina.querySelector('.avviso');
    const attuale = document.querySelector('.avviso');

    if (!nuovo) {
        attuale?.remove();

        return;
    }

    if (attuale) {
        attuale.replaceWith(nuovo.cloneNode(true));
    } else {
        document.querySelector('main').prepend(nuovo.cloneNode(true));
    }
}

/* =============================================================================
   Carrello
   I pulsanti di quantità e di rimozione aggiornano la tabella senza ricaricare.
   ============================================================================= */

function inizializzaCarrello() {
    const modulo = document.querySelector('[data-modulo="carrello"]');

    if (!modulo || giaCollegato(modulo, 'collegatoCarrello')) {
        return;
    }

    modulo.addEventListener('submit', async (evento) => {
        evento.preventDefault();

        try {
            const pagina = await inviaModulo(modulo, evento.submitter);
            aggiornaPagina('[data-modulo="carrello"]', pagina);
        } catch (errore) {
            console.error('Aggiornamento del carrello non riuscito:', errore);
            modulo.submit();
        }
    });
}

/* =============================================================================
   Ordini del pannello
   Lo stato di un ordine si aggiorna sulla sua riga, senza toccare il resto.
   ============================================================================= */

function inizializzaOrdini() {
    document.querySelectorAll('[data-modulo="ordine"]').forEach((modulo) => {
        if (giaCollegato(modulo, 'collegatoOrdine')) {
            return;
        }

        modulo.addEventListener('submit', async (evento) => {
            evento.preventDefault();

            const riga = modulo.closest('[data-ordine]');

            try {
                const pagina = await inviaModulo(modulo, evento.submitter);
                aggiornaPagina(`[data-ordine="${riga.dataset.ordine}"]`, pagina);
            } catch (errore) {
                console.error('Aggiornamento dell\'ordine non riuscito:', errore);
                modulo.submit();
            }
        });
    });
}

/* =============================================================================
   Orari di ritiro
   Cambiando sede gli orari si aggiornano da soli.
   ============================================================================= */

function inizializzaOrariRitiro() {
    const modulo = document.querySelector('[data-modulo="sede"]');
    const sede = document.querySelector('#sede_id');

    if (!modulo || !sede || giaCollegato(modulo, 'collegatoSede')) {
        return;
    }

    // Il pulsante serve solo a chi non ha JavaScript.
    const pulsante = modulo.querySelector('button');
    if (pulsante) {
        pulsante.hidden = true;
    }

    sede.addEventListener('change', async () => {
        try {
            const pagina = await inviaModulo(modulo, null);
            aggiornaPagina('#conferma', pagina);
        } catch (errore) {
            console.error('Aggiornamento degli orari non riuscito:', errore);
            modulo.submit();
        }
    });
}

/* =============================================================================
   Moduli
   I vincoli scritti nel markup diventano messaggi visibili accanto ai campi.
   ============================================================================= */

/**
 * Mostra o toglie il messaggio di errore di un campo.
 *
 * Il messaggio viene collegato al campo con aria-describedby, così chi usa uno screen
 * reader lo sente quando arriva sul campo.
 */
function segnalaCampo(campo, messaggio) {
    const identificativo = `errore-js-${campo.name}`;
    let riga = document.getElementById(identificativo);

    if (!messaggio) {
        campo.removeAttribute('aria-invalid');
        riga?.remove();

        return;
    }

    if (!riga) {
        riga = document.createElement('small');
        riga.id = identificativo;
        campo.insertAdjacentElement('afterend', riga);
    }

    riga.textContent = messaggio;
    campo.setAttribute('aria-invalid', 'true');
    campo.setAttribute('aria-describedby', identificativo);
}

/**
 * Traduce in italiano il motivo per cui un campo non è valido.
 *
 * I vincoli sono quelli dichiarati nel markup, che sono gli stessi controlli eseguiti
 * dal PHP: qui non si aggiunge nessuna regola nuova.
 */
function messaggioErrore(campo) {
    const stato = campo.validity;

    if (stato.valueMissing) {
        return 'Questo campo è obbligatorio.';
    }

    if (stato.typeMismatch) {
        return 'Controlla il formato, ad esempio nome@dominio.it per le email.';
    }

    if (stato.tooShort) {
        return `Servono almeno ${campo.minLength} caratteri.`;
    }

    if (stato.tooLong) {
        return `Al massimo ${campo.maxLength} caratteri.`;
    }

    if (stato.patternMismatch) {
        return campo.title || 'Il valore contiene caratteri non ammessi.';
    }

    if (stato.rangeUnderflow || stato.rangeOverflow) {
        return `Indica un numero fra ${campo.min} e ${campo.max}.`;
    }

    return 'Valore non valido.';
}

function inizializzaModuli() {
    document.querySelectorAll('form').forEach((modulo) => {
        if (giaCollegato(modulo, 'collegatoValidazione')) {
            return;
        }

        modulo.setAttribute('novalidate', 'novalidate');

        modulo.addEventListener('submit', (evento) => {
            let primoErrore = null;

            modulo.querySelectorAll('input, select, textarea').forEach((campo) => {
                const valido = campo.checkValidity();
                segnalaCampo(campo, valido ? '' : messaggioErrore(campo));

                if (!valido && !primoErrore) {
                    primoErrore = campo;
                }
            });

            if (primoErrore) {
                evento.preventDefault();
                evento.stopImmediatePropagation();
                primoErrore.focus();
            }
        });

        modulo.addEventListener('input', (evento) => {
            if (evento.target.checkValidity()) {
                segnalaCampo(evento.target, '');
            }
        });
    });
}

/* =============================================================================
   Avvio
   Unico punto in cui le funzioni vengono collegate alla pagina.
   ============================================================================= */

function collega() {
    inizializzaModuli();
    inizializzaCarrello();
    inizializzaOrdini();
    inizializzaOrariRitiro();
}

document.addEventListener('DOMContentLoaded', collega);
