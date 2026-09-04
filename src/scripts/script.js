/**
 * Comportamento del sito.
 *
 * Un solo file per tutte le pagine: ogni funzione inizializza<Area>() parte da un
 * elemento del DOM e, se non lo trova, esce senza fare nulla.
 *
 * I moduli marcati con data-modulo vengono inviati con la stessa richiesta che
 * manderebbe il browser e ricevono la stessa risposta HTML; quelli che portano anche
 * data-invio="automatico" partono da soli quando cambia un loro controllo, senza
 * pulsante di conferma. In entrambi i casi la pagina non viene ricaricata: dalla
 * risposta si prende il contenuto aggiornato e si sostituisce quello presente.
 */
(function () {
    'use strict';

    /**
     * Torna su, altezza dell'intestazione e distanza dal piede della pagina.
     *
     * Le due misure vengono passate al foglio di stile come proprieta' personalizzate:
     * il colore e la disposizione restano nel CSS, qui si misura soltanto.
     */
    function inizializzaTornaSu() {
        var tornaSu = document.querySelector('.torna-su');
        var areaScorribile = document.querySelector('.pagina-scorribile');
        var footer = document.querySelector('.pagina-scorribile > footer');
        var header = document.querySelector('body > header');

        if (tornaSu === null) {
            return;
        }

        function aggiornaTornaSu() {
            var scorrimento = areaScorribile === null
                ? (window.scrollY || document.documentElement.scrollTop)
                : areaScorribile.scrollTop;
            var altezzaVisibile = areaScorribile === null ? window.innerHeight : areaScorribile.clientHeight;
            var soglia = Math.max(400, altezzaVisibile * 0.65);
            var rialzoFooter = 0;
            var mostra = scorrimento > soglia;

            if (footer !== null) {
                var cimaFooter = footer.getBoundingClientRect().top;
                var fondoHeader = header === null ? 0 : header.getBoundingClientRect().bottom;

                rialzoFooter = Math.max(0, window.innerHeight - cimaFooter);

                if (rialzoFooter > 0 && cimaFooter - fondoHeader < tornaSu.offsetHeight + 32) {
                    mostra = false;
                }
            }

            tornaSu.setAttribute('data-visibile', mostra ? 'true' : 'false');
            tornaSu.style.setProperty('--rialzo-footer', rialzoFooter + 'px');
        }

        function aggiornaAltezzaHeader() {
            if (header !== null) {
                document.documentElement.style.setProperty('--altezza-header', header.offsetHeight + 'px');
            }
        }

        aggiornaAltezzaHeader();
        aggiornaTornaSu();

        (areaScorribile || window).addEventListener('scroll', aggiornaTornaSu, { passive: true });
        window.addEventListener('resize', function () {
            aggiornaAltezzaHeader();
            aggiornaTornaSu();
        });

        if (header !== null && 'ResizeObserver' in window) {
            new ResizeObserver(aggiornaAltezzaHeader).observe(header);
        }

        tornaSu.addEventListener('click', function (evento) {
            evento.preventDefault();

            if (areaScorribile !== null) {
                areaScorribile.scrollTo({ top: 0, behavior: 'smooth' });
            } else {
                window.scrollTo({ top: 0, behavior: 'smooth' });
            }

            var inizio = document.getElementById('inizio');

            if (inizio !== null && typeof inizio.focus === 'function') {
                inizio.focus({ preventScroll: true });
            }
        });

        var salta = document.querySelector('.salta');

        if (salta !== null) {
            salta.addEventListener('click', function (evento) {
                var contenuto = document.getElementById('contenuto');

                if (contenuto !== null) {
                    evento.preventDefault();

                    if (!contenuto.hasAttribute('tabindex')) {
                        contenuto.setAttribute('tabindex', '-1');
                    }

                    if (areaScorribile !== null) {
                        areaScorribile.scrollTop = 0;
                    }

                    contenuto.focus();
                }
            });
        }

        window.addEventListener('scroll', function () {
            if (areaScorribile !== null && (window.scrollY !== 0 || window.scrollX !== 0)) {
                window.scrollTo(0, 0);
            }
        }, { passive: true });
    }

    /**
     * Sostituisce il contenuto della pagina con quello arrivato dal server.
     *
     * Il markup non viene costruito qui: si prende dalla risposta la stessa porzione
     * gia' presente, cioe' il contenuto principale, e la si mette al posto della
     * vecchia. Dentro c'e' anche l'avviso con l'esito dell'operazione, che il server
     * scrive come per una richiesta normale.
     *
     * Il fuoco torna sul controllo che ha avviato l'operazione, riconosciuto dal suo id;
     * quando quel controllo non esiste piu', ad esempio perche' l'azione lo ha fatto
     * sparire, va sull'avviso, cosi' chi naviga da tastiera non riparte dall'inizio.
     *
     * @return {boolean} false quando la risposta non contiene un contenuto sostituibile
     */
    function aggiornaPagina(testo) {
        var contenuto = document.getElementById('contenuto');
        var risposta = new DOMParser().parseFromString(testo, 'text/html');
        var nuovo = risposta === null ? null : risposta.getElementById('contenuto');

        if (contenuto === null || nuovo === null) {
            return false;
        }

        var attivo = document.activeElement === null ? '' : document.activeElement.id;

        contenuto.innerHTML = nuovo.innerHTML;

        var ripreso = attivo === '' ? null : document.getElementById(attivo);

        if (ripreso === null) {
            ripreso = contenuto.querySelector('p.avviso[role="status"]');
        }

        if (ripreso !== null) {
            ripreso.focus();
        }

        inizializzaPagamento();

        return true;
    }

    /**
     * Ripete la richiesta di un modulo senza ricaricare la pagina.
     *
     * I dati inviati sono quelli del modulo, piu' il pulsante premuto quando ne porta
     * uno: e' esattamente cio' che manderebbe il browser. Se la richiesta non arriva a
     * destinazione il modulo viene inviato in modo normale, cosi' l'operazione non si
     * perde.
     *
     * @param {HTMLFormElement} modulo
     * @param {HTMLElement|null} pulsante pulsante che ha avviato l'invio
     */
    function inviaModulo(modulo, pulsante) {
        if (modulo.hasAttribute('aria-busy')) {
            return;
        }

        var dati = new FormData(modulo);
        var destinazione = '';

        if (pulsante && pulsante.name) {
            dati.append(pulsante.name, pulsante.value);
        }

        modulo.setAttribute('aria-busy', 'true');

        fetch(modulo.action, {
            method: 'post',
            body: dati,
            credentials: 'same-origin'
        }).then(function (risposta) {
            if (!risposta.ok) {
                return Promise.reject(risposta.status);
            }

            // Indirizzo su cui il server ha fatto atterrare il redirect: la barra degli
            // indirizzi deve dire la stessa cosa del contenuto appena messo in pagina.
            // Senza redirect la risposta e' la pagina rimandata indietro con gli errori
            // del modulo, e l'indirizzo di partenza resta quello giusto: sovrascriverlo
            // con quello dell'azione perderebbe i parametri con cui la pagina era stata
            // aperta.
            destinazione = risposta.redirected ? risposta.url : '';

            return risposta.text();
        }).catch(function () {
            // La richiesta non e' arrivata a destinazione: il modulo viene inviato come
            // farebbe il browser da solo, cosi' l'operazione non si perde. Il ripiego
            // copre solo questo tratto, non l'aggiornamento della pagina che segue.
            modulo.removeAttribute('aria-busy');

            if (typeof modulo.requestSubmit === 'function') {
                modulo.requestSubmit(pulsante || undefined);
            } else {
                modulo.submit();
            }

            return null;
        }).then(function (testo) {
            if (testo === null) {
                return;
            }

            if (!aggiornaPagina(testo)) {
                window.location.reload();

                return;
            }

            if (destinazione !== '' && typeof window.history.replaceState === 'function') {
                window.history.replaceState(null, '', destinazione);
            }
        });
    }

    /**
     * Moduli che si aggiornano da soli.
     *
     * Gli ascoltatori stanno sul documento e non sui singoli moduli: cosi' continuano a
     * valere sul markup che arriva dopo un aggiornamento, e raggiungono anche i controlli
     * che stanno fuori dal proprio modulo e lo raggiungono con l'attributo form, come
     * quelli dentro le celle delle tabelle del pannello.
     */
    function inizializzaModuli() {
        document.addEventListener('submit', function (evento) {
            var modulo = evento.target;

            // Solo i moduli che modificano dati: quelli in GET portano l'utente su una
            // pagina diversa e non hanno niente da aggiornare al loro posto.
            if (!(modulo instanceof HTMLFormElement)
                || !modulo.hasAttribute('data-modulo')
                || modulo.method.toLowerCase() !== 'post') {
                return;
            }

            evento.preventDefault();
            inviaModulo(modulo, evento.submitter || null);
        });

        document.addEventListener('change', function (evento) {
            var modulo = evento.target.form;

            if (!modulo || modulo.getAttribute('data-invio') !== 'automatico') {
                return;
            }

            if (typeof modulo.reportValidity === 'function' && !modulo.reportValidity()) {
                return;
            }

            inviaModulo(modulo, null);
        });
    }

    /**
     * Modalita' di consegna nella pagina di pagamento.
     *
     * L'attributo data-modalita' sul modulo dice al foglio di stile quale gruppo di campi
     * riguarda la scelta fatta: qui non si scrive nessuno stile.
     */
    function inizializzaPagamento() {
        var modulo = document.querySelector('form[data-modalita]');

        if (modulo === null) {
            return;
        }

        var scelte = modulo.querySelectorAll('input[name="modalita"]');

        if (scelte.length === 0) {
            return;
        }

        function aggiornaModalita() {
            for (var i = 0; i < scelte.length; i++) {
                if (scelte[i].checked) {
                    modulo.setAttribute('data-modalita', scelte[i].value);

                    return;
                }
            }
        }

        scelte.forEach(function (scelta) {
            scelta.addEventListener('change', aggiornaModalita);
        });

        aggiornaModalita();
    }

    document.addEventListener('DOMContentLoaded', function () {
        inizializzaTornaSu();
        inizializzaModuli();
        inizializzaPagamento();
    });
}());
