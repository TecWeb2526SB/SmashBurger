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
   7. TORNA SU
   ========================================================================== */

function inizializzaTornaSu() {
    const bottone = $('#torna-su');
    if (!bottone) return;

    function aggiornaVisibilita() {
        if (window.scrollY > 300) {
            bottone.classList.add('visibile');
        } else {
            bottone.classList.remove('visibile');
        }
    }

    window.addEventListener('scroll', aggiornaVisibilita, { passive: true });
    aggiornaVisibilita();

    bottone.addEventListener('click', function () {
        window.scrollTo({ top: 0, behavior: 'smooth' });
    });
}

/* ==========================================================================
   8. AJAX - AGGIUNTA CARRELLO E SWITCHER SEDE
   ========================================================================== */

function mostraNotifica(messaggio, tipo = 'success') {
    const esistente = $('.notifica-toast');
    if (esistente) esistente.remove();

    const toast = document.createElement('div');
    toast.className = `notifica-toast ${tipo}`;
    toast.setAttribute('role', 'alert');
    toast.textContent = messaggio;

    document.body.appendChild(toast);

    setTimeout(() => {
        toast.classList.add('mostra');
    }, 10);

    setTimeout(() => {
        toast.classList.remove('mostra');
        setTimeout(() => toast.remove(), 300);
    }, 3000);
}

function inizializzaAjaxCart() {
    const forms = document.querySelectorAll('form[action="carrello.php"]');
    if (forms.length === 0) return;

    forms.forEach(form => {
        form.addEventListener('submit', async function (e) {
            const action = form.querySelector('input[name="action"]');
            if (!action || action.value !== 'add_product') return;

            e.preventDefault();
            const formData = new FormData(form);
            formData.append('ajax', '1');

            try {
                const response = await fetch('carrello.php', {
                    method: 'POST',
                    body: formData,
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                });

                const data = await response.json();
                if (data.ok) {
                    mostraNotifica(data.message || 'Prodotto aggiunto al carrello!');
                    
                    // Aggiorna il numeretto nel badge (se presente)
                    const badge = document.querySelector('.badge-notifica');
                    if (badge && typeof data.cart_count !== 'undefined') {
                        badge.textContent = data.cart_count;
                        // Feedback visivo (opzionale: una piccola animazione)
                        badge.style.transform = 'scale(1.3)';
                        setTimeout(() => badge.style.transform = 'scale(1)', 200);
                    }
                } else {
                    mostraNotifica(data.message || 'Errore durante l\'aggiunta.', 'error');
                }
            } catch (err) {
                console.error('Errore AJAX:', err);
                form.submit(); // Fallback
            }
        });
    });
}

function inizializzaBranchSwitcher() {
    const selects = document.querySelectorAll('.branch-switcher select, .hero-branch-selector select');
    if (selects.length === 0) return;

    selects.forEach(function (select) {
        select.addEventListener('change', async function () {
            const form = select.closest('form');
            if (!form) return;

            const formData = new FormData(form);
            const params = new URLSearchParams();
            for (const pair of formData.entries()) {
                params.append(pair[0], pair[1]);
            }
            params.append('ajax', '1');

            try {
                const action = form.getAttribute('action') || window.location.pathname;
                const response = await fetch(action + '?' + params.toString(), {
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                });

                if (response.ok) {
                    const data = await response.json();
                    if (data.ok) {
                        // Aggiorna l'interfaccia senza refresh completo se possibile
                        // Per ora facciamo un refresh pulito o aggiorniamo i link
                        window.location.reload();
                    }
                } else {
                    form.submit();
                }
            } catch (err) {
                form.submit();
            }
        });
    });
}

function inizializzaMappaSedi() {
    const wrapper = document.getElementById('sedi-interattive');
    if (!wrapper) return;

    const links = wrapper.querySelectorAll('.sede-link');
    if (links.length === 0) return;

    const detailName = document.getElementById('sede-dettaglio-nome');
    const detailAddress = document.getElementById('sede-dettaglio-indirizzo');
    const detailPhone = document.getElementById('sede-dettaglio-phone');
    const detailPhoneLink = document.getElementById('sede-dettaglio-phone-link');
    const detailEmail = document.getElementById('sede-dettaglio-email');
    const detailEmailLink = document.getElementById('sede-dettaglio-email-link');
    const detailHours = document.getElementById('sede-dettaglio-orari-valore');
    const detailNotes = document.getElementById('sede-dettaglio-note-valore');
    const mapFrame = document.getElementById('sedi-mappa-frame');

    function normalizzaTelefono(tel) {
        return tel.replace(/[^0-9+]/g, '');
    }

    function render(link) {
        links.forEach(function (l) {
            const attiva = l === link;
            l.classList.toggle('attiva', attiva);
            if (attiva) {
                l.setAttribute('aria-current', 'true');
            } else {
                l.removeAttribute('aria-current');
            }
        });

        const nome = link.dataset.branchName || '';
        const city = link.dataset.branchCity || '';
        const province = link.dataset.branchProvince || '';
        const address = link.dataset.branchAddress || '';
        const postal = link.dataset.branchPostal || '';
        const phone = link.dataset.branchPhone || '';
        const email = link.dataset.branchEmail || '';
        const notes = link.dataset.branchNotes || '';
        const hours = link.dataset.branchHours || '';
        const map = link.dataset.branchMap || '';

        if (detailName) {
            detailName.textContent = nome;
        }
        if (detailAddress) {
            detailAddress.textContent = address + ', ' + postal + ' ' + city + ' (' + province + ')';
        }
        if (detailPhone) {
            detailPhone.textContent = phone;
        }
        if (detailPhoneLink) {
            detailPhoneLink.setAttribute('href', 'tel:' + normalizzaTelefono(phone));
        }
        if (detailEmail) {
            detailEmail.textContent = email;
        }
        if (detailEmailLink) {
            detailEmailLink.setAttribute('href', 'mailto:' + email);
        }
        if (detailHours) {
            detailHours.textContent = hours || 'Orari non disponibili';
        }
        if (detailNotes) {
            detailNotes.textContent = notes || 'Nessuna nota specifica per il ritiro.';
        }
        if (mapFrame && map) {
            mapFrame.setAttribute('src', map);
        }
    }

    links.forEach(function (link) {
        link.addEventListener('click', function (e) {
            if (e.metaKey || e.ctrlKey || e.shiftKey || e.altKey || e.button !== 0) {
                return;
            }

            e.preventDefault();
            render(link);
            window.location.assign(link.href);
        });
    });

    const selectedSlug = wrapper.dataset.selectedSlug;
    const initial = selectedSlug
        ? wrapper.querySelector('.sede-link[data-branch-slug="' + selectedSlug + '"]')
        : links[0];
    if (initial) {
        render(initial);
    }
}

/* ==========================================================================
   9. CHECKOUT - TOGGLE CAMPI DINAMICI
   ========================================================================== */

function inizializzaCheckoutRitiro() {
    const radios = document.querySelectorAll('input[name="pickup_mode"]');
    const timeWrap = document.getElementById('pickup-time-wrap');
    const timeSelect = document.getElementById('pickup_time');
    if (radios.length === 0 || !timeWrap) return;

    function aggiornaStato() {
        const selezionato = document.querySelector('input[name="pickup_mode"]:checked');
        const mostraOrario = selezionato && selezionato.value === 'orario';
        timeWrap.hidden = !mostraOrario;

        if (timeSelect) {
            timeSelect.disabled = !mostraOrario;
            if (mostraOrario && !timeSelect.value && timeSelect.options.length > 0) {
                timeSelect.selectedIndex = 0;
            }
        }
    }

    radios.forEach(function (radio) {
        radio.addEventListener('change', aggiornaStato);
    });
    aggiornaStato();
}

function inizializzaCheckoutPagamento() {
    const radios = document.querySelectorAll('input[name="payment_method"]');
    const cardBox = document.getElementById('payment-card-fields');
    const paypalBox = document.getElementById('payment-paypal-fields');
    if (radios.length === 0 || !cardBox || !paypalBox) return;

    function impostaDisabilitazione(contenitore, disabilita) {
        contenitore.querySelectorAll('input, select, textarea').forEach(function (campo) {
            campo.disabled = disabilita;
        });
    }

    function aggiornaStato() {
        const selezionato = document.querySelector('input[name="payment_method"]:checked');
        const metodo = selezionato ? selezionato.value : 'card';

        const mostraCarta = metodo === 'card';
        cardBox.hidden = !mostraCarta;
        paypalBox.hidden = mostraCarta;

        impostaDisabilitazione(cardBox, !mostraCarta);
        impostaDisabilitazione(paypalBox, mostraCarta);
    }

    radios.forEach(function (radio) {
        radio.addEventListener('change', aggiornaStato);
    });
    aggiornaStato();
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
    inizializzaTornaSu();
    inizializzaAjaxCart();
    inizializzaBranchSwitcher();
    inizializzaMappaSedi();
    inizializzaCheckoutRitiro();
    inizializzaCheckoutPagamento();
});
