/**
 * main.js — Smash Burger Original
 * Progressive enhancement: tutto funziona senza questo file.
 */

'use strict';

/* --- UTILITY --- */
function $(selettore) {
    return document.querySelector(selettore);
}

/* ==========================================================================
   1. TEMA — TOGGLE SOLE / LUNA
   ========================================================================== */

const SVG_SOLE = `
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
     width="20" height="20" fill="none" stroke="currentColor"
     stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
     aria-hidden="true" focusable="false">
  <circle cx="12" cy="12" r="5"/>
  <line x1="12" y1="1"  x2="12" y2="3"/>
  <line x1="12" y1="21" x2="12" y2="23"/>
  <line x1="4.22" y1="4.22"   x2="5.64"  y2="5.64"/>
  <line x1="18.36" y1="18.36" x2="19.78" y2="19.78"/>
  <line x1="1"  y1="12" x2="3"  y2="12"/>
  <line x1="21" y1="12" x2="23" y2="12"/>
  <line x1="4.22"  y1="19.78" x2="5.64"  y2="18.36"/>
  <line x1="18.36" y1="5.64"  x2="19.78" y2="4.22"/>
</svg>`;

const SVG_LUNA = `
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
     width="20" height="20" fill="none" stroke="currentColor"
     stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
     aria-hidden="true" focusable="false">
  <path d="M21 12.79A9 9 0 1 1 11.21 3a7 7 0 0 0 9.79 9.79z"/>
</svg>`;

const CHIAVE_TEMA = 'smashburger-tema';

function applicaTema(scuro, bottone) {
    if (scuro) {
        document.documentElement.classList.add('tema-scuro');
        document.documentElement.classList.remove('tema-chiaro');
        bottone.innerHTML = SVG_SOLE;
        bottone.setAttribute('aria-label', 'Attiva modalità chiara');
        bottone.setAttribute('aria-pressed', 'true');
    } else {
        document.documentElement.classList.remove('tema-scuro');
        document.documentElement.classList.add('tema-chiaro');
        bottone.innerHTML = SVG_LUNA;
        bottone.setAttribute('aria-label', 'Attiva modalità scura');
        bottone.setAttribute('aria-pressed', 'false');
    }
}

function temaScuroIniziale() {
    const salvato = localStorage.getItem(CHIAVE_TEMA);
    if (salvato === 'scuro') return true;
    if (salvato === 'chiaro') return false;
    return window.matchMedia('(prefers-color-scheme: dark)').matches;
}

function inizializzaTema() {
    const bottone = $('#theme-toggle');
    if (!bottone) return;

    const scuro = temaScuroIniziale();
    applicaTema(scuro, bottone);

    bottone.addEventListener('click', function () {
        const nuovoTema = !document.documentElement.classList.contains('tema-scuro');
        applicaTema(nuovoTema, bottone);
        localStorage.setItem(CHIAVE_TEMA, nuovoTema ? 'scuro' : 'chiaro');
    });

    /* Segue i cambiamenti del sistema solo se l'utente non ha scelto esplicitamente */
    window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', function (e) {
        if (!localStorage.getItem(CHIAVE_TEMA)) {
            applicaTema(e.matches, bottone);
        }
    });
}

/* ==========================================================================
   2. FILTRO CATEGORIE — solo su prodotti.php
   ========================================================================== */

function inizializzaFiltroProdotti() {
    const navFiltro = $('#filtro-categorie');
    if (!navFiltro) return;

    const linkFiltro = navFiltro.querySelectorAll('a');
    const sezioni = document.querySelectorAll('main > section[id]');
    if (sezioni.length === 0) return;

    function mostraSezione(idAttivo) {
        sezioni.forEach(function (s) {
            if (s.id === 'intestazione-prodotti') return;
            idAttivo === null || s.id === idAttivo
                ? s.removeAttribute('hidden')
                : s.setAttribute('hidden', '');
        });
    }

    linkFiltro.forEach(function (link) {
        link.setAttribute('aria-pressed', 'false');
        link.setAttribute('role', 'button');

        link.addEventListener('click', function (e) {
            e.preventDefault();
            const idTarget = link.getAttribute('href').replace('#', '');
            const giaAttivo = link.classList.contains('filtro-attivo');

            if (giaAttivo) {
                mostraSezione(null);
                linkFiltro.forEach(function (l) {
                    l.setAttribute('aria-pressed', 'false');
                    l.classList.remove('filtro-attivo');
                });
            } else {
                mostraSezione(idTarget);
                linkFiltro.forEach(function (l) {
                    const attivo = l === link;
                    l.setAttribute('aria-pressed', String(attivo));
                    l.classList.toggle('filtro-attivo', attivo);
                });
            }
        });
    });
}

/* ==========================================================================
   3. MENU MOBILE
   ========================================================================== */

function inizializzaMenuMobile() {
    const bottoneMenu = $('#menu-toggle');
    const menuNav = $('#menu-principale');
    if (!bottoneMenu || !menuNav) return;

    bottoneMenu.setAttribute('aria-expanded', 'false');
    bottoneMenu.setAttribute('aria-controls', 'menu-principale');

    bottoneMenu.addEventListener('click', function () {
        const aperto = bottoneMenu.getAttribute('aria-expanded') === 'true';
        bottoneMenu.setAttribute('aria-expanded', String(!aperto));
        menuNav.classList.toggle('menu-aperto', !aperto);
    });

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && bottoneMenu.getAttribute('aria-expanded') === 'true') {
            bottoneMenu.setAttribute('aria-expanded', 'false');
            menuNav.classList.remove('menu-aperto');
            bottoneMenu.focus();
        }
    });

    document.addEventListener('click', function (e) {
        if (!menuNav.contains(e.target) && !bottoneMenu.contains(e.target)) {
            bottoneMenu.setAttribute('aria-expanded', 'false');
            menuNav.classList.remove('menu-aperto');
        }
    });
}

/* ==========================================================================
   4. VALIDAZIONE FORM (aggiuntiva, non sostitutiva di PHP)
   ========================================================================== */

function messaggioErrore(campo) {
    if (campo.validity.valueMissing) return 'Questo campo è obbligatorio.';
    if (campo.validity.typeMismatch) return 'Formato non valido.';
    if (campo.validity.tooShort) return `Minimo ${campo.minLength} caratteri.`;
    if (campo.validity.tooLong) return `Massimo ${campo.maxLength} caratteri.`;
    if (campo.validity.patternMismatch) return campo.dataset.messaggioPattern || 'Formato non valido.';
    return 'Valore non valido.';
}

function inizializzaValidazioneForm() {
    const forms = document.querySelectorAll('form[data-valida]');
    if (forms.length === 0) return;

    forms.forEach(function (form) {
        form.addEventListener('submit', function (e) {
            let valido = true;

            form.querySelectorAll('input[required], textarea[required], select[required]')
                .forEach(function (campo) {
                    const erroreEl = document.getElementById(
                        campo.getAttribute('aria-describedby')
                    );

                    if (!campo.validity.valid) {
                        valido = false;
                        campo.setAttribute('aria-invalid', 'true');
                        if (erroreEl) {
                            erroreEl.textContent = messaggioErrore(campo);
                            erroreEl.removeAttribute('hidden');
                        }
                    } else {
                        campo.setAttribute('aria-invalid', 'false');
                        if (erroreEl) {
                            erroreEl.textContent = '';
                            erroreEl.setAttribute('hidden', '');
                        }
                    }
                });

            if (!valido) {
                e.preventDefault();
                const primoErrore = form.querySelector('[aria-invalid="true"]');
                if (primoErrore) primoErrore.focus();
            }
        });
    });
}

/* ==========================================================================
   5. TOGGLE VISIBILITÀ PASSWORD
   ========================================================================== */

function inizializzaTogglePassword() {
    const bottoni = document.querySelectorAll('.mostra-password');
    if (bottoni.length === 0) return;

    bottoni.forEach(function (bottone) {
        bottone.addEventListener('click', function () {
            const wrapper = bottone.closest('.campo-password-wrapper');
            if (!wrapper) return;

            const input = wrapper.querySelector('input[type="password"], input[type="text"]');
            if (!input) return;

            const visibile = input.type === 'text';
            input.type = visibile ? 'password' : 'text';
            bottone.setAttribute('aria-pressed', String(!visibile));
            bottone.setAttribute('aria-label', visibile ? 'Mostra password' : 'Nascondi password');
        });
    });
}

/* ==========================================================================
   6. VALIDAZIONE REAL-TIME FORM AUTENTICAZIONE
   ========================================================================== */

function inizializzaValidazioneAuth() {
    const campi = document.querySelectorAll(
        '.auth-box input[required], .auth-box input[minlength]'
    );
    if (campi.length === 0) return;

    campi.forEach(function (campo) {
        /* Mostra l'errore quando il campo perde il focus */
        campo.addEventListener('blur', function () {
            aggiornaStatoCampo(campo);
        });

        /* Rimuove l'errore mentre si digita (se il campo diventa valido) */
        campo.addEventListener('input', function () {
            if (campo.validity.valid) {
                pulisciErroreCampo(campo);
            }
        });
    });

    /* Controllo speciale: conferma password deve coincidere con password */
    const conferma = document.getElementById('conferma');
    const password = document.getElementById('password');
    if (conferma && password) {
        conferma.addEventListener('blur', function () {
            if (!conferma.validity.valueMissing && conferma.value !== password.value) {
                mostraErroreCampo(conferma, 'Le password non coincidono.');
            } else {
                aggiornaStatoCampo(conferma);
            }
        });
    }
}

function aggiornaStatoCampo(campo) {
    if (!campo.validity.valid) {
        const msg = messaggioErrore(campo);
        mostraErroreCampo(campo, msg);
    } else {
        pulisciErroreCampo(campo);
    }
}

function mostraErroreCampo(campo, messaggio) {
    campo.setAttribute('aria-invalid', 'true');
    const erroreId = campo.getAttribute('aria-describedby');
    if (erroreId) {
        const erroreEl = document.getElementById(erroreId);
        if (erroreEl) {
            erroreEl.textContent = messaggio;
            erroreEl.removeAttribute('hidden');
        }
    }
}

function pulisciErroreCampo(campo) {
    campo.setAttribute('aria-invalid', 'false');
    const erroreId = campo.getAttribute('aria-describedby');
    if (erroreId) {
        const erroreEl = document.getElementById(erroreId);
        if (erroreEl) {
            erroreEl.textContent = '';
            erroreEl.setAttribute('hidden', '');
        }
    }
}

/* ==========================================================================
   INIT
   ========================================================================== */

document.addEventListener('DOMContentLoaded', function () {
    inizializzaTema();
    inizializzaFiltroProdotti();
    inizializzaMenuMobile();
    inizializzaValidazioneForm();
    inizializzaTogglePassword();
    inizializzaValidazioneAuth();
});