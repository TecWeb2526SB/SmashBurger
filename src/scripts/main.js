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
   2. FILTRO CATEGORIE — solo su prodotti
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
        const wrapper = bottone.closest('.campo-password-wrapper');
        if (!wrapper) return;

        const input = wrapper.querySelector('input');
        if (!input) return;

        const iconaChiusa = bottone.querySelector('.icona-password-chiusa');
        const iconaAperta = bottone.querySelector('.icona-password-aperta');
        const labelMostra = bottone.getAttribute('aria-label') || 'Tieni premuto per mostrare la password';
        const labelNascondi = labelMostra.replace('Tieni premuto per mostrare', 'Rilascia per nascondere');

        function aggiornaVisibilita(visibile) {
            input.type = visibile ? 'text' : 'password';
            bottone.setAttribute('aria-pressed', String(visibile));
            bottone.setAttribute('aria-label', visibile ? labelNascondi : labelMostra);
            bottone.classList.toggle('is-active', visibile);

            if (iconaChiusa) {
                iconaChiusa.classList.toggle('is-hidden', visibile);
            }

            if (iconaAperta) {
                iconaAperta.classList.toggle('is-hidden', !visibile);
            }
        }

        function mostraPassword(e) {
            if (e.type === 'pointerdown' && e.button !== 0) return;
            e.preventDefault();
            aggiornaVisibilita(true);
        }

        function nascondiPassword() {
            aggiornaVisibilita(false);
        }

        aggiornaVisibilita(false);

        bottone.addEventListener('pointerdown', mostraPassword);
        bottone.addEventListener('pointerup', nascondiPassword);
        bottone.addEventListener('pointercancel', nascondiPassword);
        bottone.addEventListener('pointerleave', nascondiPassword);
        bottone.addEventListener('click', function (e) {
            e.preventDefault();
        });
        bottone.addEventListener('blur', nascondiPassword);
        bottone.addEventListener('keydown', function (e) {
            if (e.key === ' ' || e.key === 'Enter') {
                e.preventDefault();
                aggiornaVisibilita(true);
            }
        });
        bottone.addEventListener('keyup', function (e) {
            if (e.key === ' ' || e.key === 'Enter') {
                e.preventDefault();
                aggiornaVisibilita(false);
            }
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
    const forms = document.querySelectorAll('form[action="carrello"]');
    if (forms.length === 0) return;
    const body = document.body;
    const totaleCarrello = document.getElementById('carrello-totale-valore');

    function aggiornaBadgeCarrello(cartCount) {
        const badges = document.querySelectorAll('.badge-notifica');
        body.dataset.cartCount = String(cartCount);

        badges.forEach(badge => {
            badge.textContent = cartCount;
            if (parseInt(cartCount, 10) > 0) {
                badge.classList.remove('badge-nascosto');
            } else {
                badge.classList.add('badge-nascosto');
            }
            badge.style.transform = 'scale(1.4)';
            setTimeout(() => {
                badge.style.transform = '';
            }, 300);
        });
    }

    function aggiornaRiepilogoCarrello(data) {
        if (typeof data.cart_count !== 'undefined') {
            aggiornaBadgeCarrello(data.cart_count);
        }

        if (totaleCarrello && data.cart_total_formatted) {
            totaleCarrello.textContent = data.cart_total_formatted;
        }
    }

    forms.forEach(form => {
        const action = form.querySelector('input[name="action"]');
        if (!action) return;

        if (action.value === 'add_product') {
            form.addEventListener('submit', async function (e) {
                e.preventDefault();
                const formData = new FormData(form);
                formData.append('ajax', '1');

                try {
                    const response = await fetch('carrello', {
                        method: 'POST',
                        body: formData,
                        headers: { 'X-Requested-With': 'XMLHttpRequest' }
                    });

                    const data = await response.json();
                    if (data.ok) {
                        mostraNotifica(data.message || 'Prodotto aggiunto al carrello!');
                        aggiornaRiepilogoCarrello(data);
                    } else {
                        mostraNotifica(data.message || 'Errore durante l\'aggiunta.', 'error');
                    }
                } catch (err) {
                    console.error('Errore AJAX:', err);
                    form.submit(); // Fallback
                }
            });
            return;
        }

        if (action.value === 'update_item') {
            const quantityInput = form.querySelector('[data-cart-quantity-input]');
            if (!quantityInput) return;
            const quantityDisplay = form.querySelector('[data-cart-quantity-display]');
            const decrementButton = form.querySelector('[data-quantity-step="-1"]');
            const incrementButton = form.querySelector('[data-quantity-step="1"]');

            function clampQuantity(value) {
                const parsed = Number.parseInt(value, 10);
                const min = Number.parseInt(quantityInput.dataset.min || '0', 10);
                const max = Number.parseInt(quantityInput.dataset.max || '100', 10);

                if (Number.isNaN(parsed)) {
                    return min;
                }

                return Math.min(max, Math.max(min, parsed));
            }

            function aggiornaStepperUi() {
                const currentValue = clampQuantity(quantityInput.value);
                const min = Number.parseInt(quantityInput.dataset.min || '0', 10);
                const max = Number.parseInt(quantityInput.dataset.max || '100', 10);
                quantityInput.value = String(currentValue);

                if (quantityDisplay) {
                    quantityDisplay.textContent = String(currentValue);
                    quantityDisplay.setAttribute('aria-valuenow', String(currentValue));
                    quantityDisplay.setAttribute('aria-valuemin', String(min));
                    quantityDisplay.setAttribute('aria-valuemax', String(max));
                }

                if (decrementButton) {
                    decrementButton.disabled = currentValue <= min;
                }

                if (incrementButton) {
                    incrementButton.disabled = currentValue >= max;
                }
            }

            function setStepperBusy(isBusy) {
                if (quantityDisplay) {
                    quantityDisplay.setAttribute('aria-disabled', isBusy ? 'true' : 'false');
                }

                if (decrementButton) {
                    decrementButton.disabled = isBusy || clampQuantity(quantityInput.value) <= Number.parseInt(quantityInput.dataset.min || '0', 10);
                }

                if (incrementButton) {
                    incrementButton.disabled = isBusy || clampQuantity(quantityInput.value) >= Number.parseInt(quantityInput.dataset.max || '100', 10);
                }
            }

            const inviaAggiornamento = async function () {
                const previousValue = String(clampQuantity(
                    quantityInput.dataset.currentQuantity || quantityInput.defaultValue || '0'
                ));

                quantityInput.value = String(clampQuantity(quantityInput.value));
                if (quantityInput.value === previousValue) {
                    aggiornaStepperUi();
                    return;
                }

                const formData = new FormData(form);
                formData.append('ajax', '1');
                setStepperBusy(true);

                try {
                    const response = await fetch('carrello', {
                        method: 'POST',
                        body: formData,
                        headers: { 'X-Requested-With': 'XMLHttpRequest' }
                    });

                    const data = await response.json();
                    if (!data.ok) {
                        quantityInput.value = previousValue;
                        mostraNotifica(data.message || 'Impossibile aggiornare la quantità.', 'error');
                        return;
                    }

                    if (data.cart_is_empty) {
                        window.location.reload();
                        return;
                    }

                    if (data.item) {
                        quantityInput.value = data.item.quantity;
                        quantityInput.dataset.currentQuantity = String(data.item.quantity);

                        const row = form.closest('tr');
                        if (row) {
                            const lineTotal = row.querySelector('[data-cart-line-total]');
                            if (lineTotal) {
                                lineTotal.textContent = data.item.line_total_formatted;
                            }
                        }
                    } else {
                        const row = form.closest('tr');
                        if (row) {
                            row.remove();
                        }
                    }

                    aggiornaRiepilogoCarrello(data);
                    aggiornaStepperUi();
                    mostraNotifica(data.message || 'Carrello aggiornato.');
                } catch (err) {
                    console.error('Errore AJAX:', err);
                    quantityInput.value = previousValue;
                    aggiornaStepperUi();
                    form.submit();
                } finally {
                    setStepperBusy(false);
                }
            };

            form.addEventListener('submit', function (e) {
                e.preventDefault();
                inviaAggiornamento();
            });

            quantityInput.addEventListener('change', inviaAggiornamento);

            if (decrementButton) {
                decrementButton.addEventListener('click', function () {
                    quantityInput.value = String(clampQuantity(quantityInput.value) - 1);
                    aggiornaStepperUi();
                    inviaAggiornamento();
                });
            }

            if (incrementButton) {
                incrementButton.addEventListener('click', function () {
                    quantityInput.value = String(clampQuantity(quantityInput.value) + 1);
                    aggiornaStepperUi();
                    inviaAggiornamento();
                });
            }

            if (quantityDisplay) {
                quantityDisplay.addEventListener('keydown', function (e) {
                    const min = Number.parseInt(quantityInput.dataset.min || '0', 10);
                    const max = Number.parseInt(quantityInput.dataset.max || '100', 10);
                    let nextValue;

                    if (e.key === 'ArrowLeft' || e.key === 'ArrowDown') {
                        nextValue = clampQuantity(quantityInput.value) - 1;
                    } else if (e.key === 'ArrowRight' || e.key === 'ArrowUp') {
                        nextValue = clampQuantity(quantityInput.value) + 1;
                    } else if (e.key === 'Home') {
                        nextValue = min;
                    } else if (e.key === 'End') {
                        nextValue = max;
                    } else {
                        return;
                    }

                    e.preventDefault();
                    quantityInput.value = String(clampQuantity(nextValue));
                    aggiornaStepperUi();
                    inviaAggiornamento();
                });
            }

            aggiornaStepperUi();
            return;
        }

        if (action.value === 'remove_item' || action.value === 'clear_cart') {
            form.addEventListener('submit', async function (e) {
                e.preventDefault();
                const formData = new FormData(form);
                formData.append('ajax', '1');

                try {
                    const response = await fetch('carrello', {
                        method: 'POST',
                        body: formData,
                        headers: { 'X-Requested-With': 'XMLHttpRequest' }
                    });

                    const data = await response.json();
                    if (!data.ok) {
                        mostraNotifica(data.message || 'Operazione non riuscita.', 'error');
                        return;
                    }

                    if (action.value === 'remove_item') {
                        const row = form.closest('tr');
                        if (row) {
                            row.remove();
                        }
                    }

                    aggiornaRiepilogoCarrello(data);

                    if (data.cart_is_empty || action.value === 'clear_cart') {
                        window.location.reload();
                        return;
                    }

                    mostraNotifica(data.message || 'Carrello aggiornato.');
                } catch (err) {
                    console.error('Errore AJAX:', err);
                    form.submit();
                }
            });
        }
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
   9. HEADER - SWITCHER SEDE DROPDOWN
   ========================================================================== */

function inizializzaHeaderSede() {
    const toggle = document.getElementById('sede-dropdown-toggle');
    const menu = document.getElementById('sede-dropdown-menu');
    const hasBranchDropdown = Boolean(toggle && menu);
    const modal = document.getElementById('modal-cambio-sede');
    const btnAnnulla = document.getElementById('modal-annulla');
    const btnConferma = document.getElementById('modal-conferma');
    const modalTitolo = document.getElementById('modal-titolo');
    const modalMessaggio = document.getElementById('modal-messaggio');
    const logoutLink = document.querySelector('[data-confirm-logout="true"]');
    if (!modal || !btnAnnulla || !btnConferma || !modalTitolo || !modalMessaggio) {
        return;
    }

    const body = document.body;
    let targetOption = null;
    let lastFocusedElement = null;
    let pendingModalAction = null;

    const modalCopy = {
        branch: {
            title: 'Cambiare sede?',
            message: 'Hai già dei prodotti nel carrello per un\'altra sede. Cambiando sede ora, il tuo carrello attuale verrà svuotato. Vuoi procedere?',
            confirm: 'Sì, svuota e cambia'
        },
        logout: {
            title: 'Uscire dall\'account?',
            message: 'Sei sicuro di voler uscire dal tuo account?',
            confirm: 'Sì, esci'
        },
        delete: {
            title: 'Eliminare credenziali?',
            message: 'Sei sicuro di voler eliminare definitivamente queste credenziali? L\'operazione non è reversibile.',
            confirm: 'Sì, elimina'
        }
    };

    function impostaContenutoModale(tipo) {
        const copy = modalCopy[tipo] || modalCopy.branch;
        modalTitolo.textContent = copy.title;
        modalMessaggio.textContent = copy.message;
        btnConferma.textContent = copy.confirm;
    }

    function apriMenu() {
        if (!hasBranchDropdown) return;
        toggle.setAttribute('aria-expanded', 'true');
        menu.hidden = false;
        menu.classList.add('aperto');
    }

    function chiudiMenu(returnFocus = false) {
        if (!hasBranchDropdown) return;
        toggle.setAttribute('aria-expanded', 'false');
        menu.classList.remove('aperto');
        menu.hidden = true;
        if (returnFocus) {
            toggle.focus();
        }
    }

    function apriModal() {
        lastFocusedElement = document.activeElement;
        modal.hidden = false;
        modal.setAttribute('aria-hidden', 'false');
        modal.classList.add('mostra');
        window.setTimeout(function () {
            if (btnAnnulla) {
                btnAnnulla.focus();
            } else if (btnConferma) {
                btnConferma.focus();
            }
        }, 0);
    }

    function chiudiModal(returnFocus = true, clearPendingAction = true) {
        modal.classList.remove('mostra');
        modal.setAttribute('aria-hidden', 'true');
        modal.hidden = true;
        if (clearPendingAction) {
            pendingModalAction = null;
        }
        if (returnFocus && lastFocusedElement && typeof lastFocusedElement.focus === 'function') {
            lastFocusedElement.focus();
        }
    }

    function buildReturnUrl(targetSlug) {
        const returnUrl = new URL(window.location.href);
        const normalizedPath = returnUrl.pathname.replace(/^\/+|\/+$/g, '');
        const pageName = normalizedPath === '' ? './' : normalizedPath.split('/').pop();

        returnUrl.searchParams.delete('ajax');
        returnUrl.searchParams.delete('force');

        if (pageName === 'prodotti' || pageName === 'sedi') {
            returnUrl.searchParams.set('sede', targetSlug);
        } else {
            returnUrl.searchParams.delete('sede');
        }

        return returnUrl.toString();
    }

    async function cambiaSede(option, force = false) {
        const targetSlug = option.dataset.sedeSlug || '';
        const returnUrl = option.dataset.returnUrl || buildReturnUrl(targetSlug);
        const switchUrl = new URL(option.dataset.switchUrl || option.href, window.location.origin);
        switchUrl.searchParams.set('ajax', '1');

        if (force) {
            switchUrl.searchParams.set('force', '1');
        } else {
            switchUrl.searchParams.delete('force');
        }

        try {
            const response = await fetch(switchUrl.toString(), {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            });
            const data = await response.json();

            if (!data.ok) {
                mostraNotifica(data.message || 'Impossibile cambiare sede.', 'error');
                return;
            }

            window.location.href = returnUrl;
        } catch (err) {
            console.error('Errore cambio sede:', err);
            window.location.href = force ? switchUrl.toString() : (returnUrl || option.href);
        }
    }

    if (hasBranchDropdown) {
        toggle.addEventListener('click', function (e) {
            e.stopPropagation();
            const expanded = toggle.getAttribute('aria-expanded') === 'true';
            if (expanded) {
                chiudiMenu();
            } else {
                apriMenu();
            }
        });

        const options = menu.querySelectorAll('.sede-opzione');
        options.forEach(option => {
            option.addEventListener('click', function (e) {
                const isCurrent = option.closest('li').classList.contains('corrente');
                if (isCurrent) {
                    e.preventDefault();
                    chiudiMenu(true);
                    return;
                }

                const cartCount = parseInt(body.dataset.cartCount || '0', 10);
                if (cartCount > 0) {
                    e.preventDefault();
                    targetOption = option;
                    impostaContenutoModale('branch');
                    pendingModalAction = async function () {
                        await cambiaSede(targetOption, true);
                        targetOption = null;
                    };
                    chiudiMenu();
                    apriModal();
                    return;
                }

                e.preventDefault();
                cambiaSede(option);
            });
        });
    }

    if (btnAnnulla) {
        btnAnnulla.addEventListener('click', () => {
            chiudiModal();
            targetOption = null;
        });
    }

    if (btnConferma) {
        btnConferma.addEventListener('click', async () => {
            if (pendingModalAction) {
                const action = pendingModalAction;
                pendingModalAction = null;
                chiudiModal(false, false);
                await action();
            }
        });
    }

    // Chiudi modale se si clicca fuori dal contenuto
    modal.addEventListener('click', (e) => {
        if (e.target === modal) {
            chiudiModal();
            targetOption = null;
        }
    });

    modal.addEventListener('keydown', function (e) {
        if (e.key !== 'Tab' || modal.hidden) return;

        const focusabili = modal.querySelectorAll('button:not([disabled]), [href], input:not([disabled]), select:not([disabled]), textarea:not([disabled]), [tabindex]:not([tabindex="-1"])');
        if (focusabili.length === 0) return;

        const primo = focusabili[0];
        const ultimo = focusabili[focusabili.length - 1];

        if (e.shiftKey && document.activeElement === primo) {
            e.preventDefault();
            ultimo.focus();
        } else if (!e.shiftKey && document.activeElement === ultimo) {
            e.preventDefault();
            primo.focus();
        }
    });

    if (logoutLink) {
        logoutLink.addEventListener('click', function (e) {
            const cartCount = parseInt(body.dataset.cartCount || '0', 10);
            e.preventDefault();
            impostaContenutoModale('logout');

            if (cartCount > 0) {
                modalMessaggio.textContent = 'Hai ancora dei prodotti nel carrello. Uscendo ora potresti interrompere il tuo flusso di acquisto. Vuoi davvero uscire?';
            }

            pendingModalAction = async function () {
                window.location.href = logoutLink.href;
            };
            apriModal();
        });
    }

    // Gestione universale per tasti di cancellazione che richiedono modal
    document.addEventListener('click', function(e) {
        const deleteBtn = e.target.closest('[data-confirm-delete="true"]');
        if (!deleteBtn) return;

        e.preventDefault();
        const form = deleteBtn.closest('form');
        if (!form) return;

        impostaContenutoModale('delete');
        pendingModalAction = async function() {
            form.submit();
        };
        apriModal();
    });

    document.addEventListener('click', function (e) {
        if (hasBranchDropdown && !menu.contains(e.target) && !toggle.contains(e.target)) {
            chiudiMenu();
        }
    });

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            chiudiMenu();
            chiudiModal();
            targetOption = null;
        }
    });
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
   10. RICEVUTE - STAMPA
   ========================================================================== */

function inizializzaStampaRicevuta() {
    const triggers = document.querySelectorAll('[data-print-trigger="true"]');
    if (triggers.length === 0) return;

    triggers.forEach(function (trigger) {
        trigger.addEventListener('click', function () {
            window.print();
        });
    });
}

/* ==========================================================================
   11. CATALOGO ADMIN - PREVIEW IMMAGINE E FUOCO
   ========================================================================== */

function inizializzaAdminCatalogoProdotto() {
    const uploadInput = document.querySelector('[data-image-upload-input="true"]');
    const preview = document.querySelector('[data-image-focus-preview="true"]');
    const emptyState = document.querySelector('[data-image-focus-empty="true"]');
    const focusX = document.querySelector('[data-image-focus-x="true"]');
    const focusY = document.querySelector('[data-image-focus-y="true"]');
    const focusXOutput = document.querySelector('[data-image-focus-x-output="true"]');
    const focusYOutput = document.querySelector('[data-image-focus-y-output="true"]');

    if (!focusX || !focusY) return;

    function aggiornaAnteprima() {
        if (preview) {
            preview.style.objectPosition = `${focusX.value}% ${focusY.value}%`;
        }

        if (focusXOutput) {
            focusXOutput.textContent = focusX.value;
        }

        if (focusYOutput) {
            focusYOutput.textContent = focusY.value;
        }
    }

    if (uploadInput && preview) {
        uploadInput.addEventListener('change', function () {
            const file = uploadInput.files && uploadInput.files[0] ? uploadInput.files[0] : null;
            if (!file || !file.type.startsWith('image/')) {
                return;
            }

            const reader = new FileReader();
            reader.addEventListener('load', function () {
                preview.src = String(reader.result || '');
                preview.removeAttribute('hidden');
                if (emptyState) {
                    emptyState.setAttribute('hidden', '');
                }
                aggiornaAnteprima();
            });
            reader.readAsDataURL(file);
        });
    }

    focusX.addEventListener('input', aggiornaAnteprima);
    focusY.addEventListener('input', aggiornaAnteprima);
    aggiornaAnteprima();
}

/* ==========================================================================
   12. BUILDER FORNITURE - RIGHE DINAMICHE
   ========================================================================== */

function inizializzaAdminSupplyBuilder() {
    const repeatableLists = document.querySelectorAll('[data-repeatable-list]');
    if (repeatableLists.length === 0) return;

    const valutaFormatter = new Intl.NumberFormat('it-IT', {
        style: 'currency',
        currency: 'EUR'
    });

    repeatableLists.forEach(function (list) {
        const rowsContainer = list.querySelector('[data-repeatable-rows]');
        const addButton = list.querySelector('[data-repeatable-add]');
        const template = list.querySelector('template[data-repeatable-template]');
        if (!rowsContainer || !addButton || !template) return;

        let nextIndex = Number.parseInt(list.dataset.nextIndex || '0', 10);
        if (Number.isNaN(nextIndex) || nextIndex < 0) {
            nextIndex = rowsContainer.querySelectorAll('[data-repeatable-row]').length;
        }

        function aggiornaCostoRiga(row) {
            const select = row.querySelector('select[data-product-select]');
            const output = row.querySelector('[data-product-cost-output]');
            if (!select || !output) return;

            const defaultMessage = output.dataset.defaultMessage || '';
            const missingMessage = output.dataset.missingMessage || defaultMessage;
            const selectedOption = select.options[select.selectedIndex];
            const unitCost = Number.parseInt((selectedOption && selectedOption.dataset.unitCost) || '0', 10);

            if (!select.value) {
                output.textContent = defaultMessage;
                return;
            }

            if (Number.isFinite(unitCost) && unitCost > 0) {
                output.textContent = `Costo filiale applicato automaticamente: ${valutaFormatter.format(unitCost / 100)}`;
                return;
            }

            output.textContent = missingMessage;
        }

        function svuotaRiga(row) {
            row.querySelectorAll('input, select, textarea').forEach(function (field) {
                if (field.tagName === 'SELECT') {
                    field.selectedIndex = 0;
                    return;
                }

                if (field.type === 'checkbox' || field.type === 'radio') {
                    field.checked = false;
                    return;
                }

                field.value = '';
            });
        }

        function aggiornaStatoRighe() {
            const rows = Array.from(rowsContainer.querySelectorAll('[data-repeatable-row]'));

            rows.forEach(function (row, index) {
                const label = row.querySelector('[data-repeatable-label]');
                const removeButton = row.querySelector('[data-repeatable-remove]');

                if (label) {
                    const prefix = label.dataset.labelPrefix || 'Prodotto';
                    label.textContent = `${prefix} ${index + 1}`;
                }

                if (removeButton) {
                    const isDisabled = rows.length <= 1;
                    removeButton.disabled = isDisabled;
                    removeButton.setAttribute('aria-disabled', String(isDisabled));
                }

                aggiornaCostoRiga(row);
            });
        }

        function collegaRiga(row) {
            const removeButton = row.querySelector('[data-repeatable-remove]');
            const productSelect = row.querySelector('select[data-product-select]');

            if (removeButton) {
                removeButton.addEventListener('click', function () {
                    const rows = rowsContainer.querySelectorAll('[data-repeatable-row]');

                    if (rows.length <= 1) {
                        svuotaRiga(row);
                        aggiornaStatoRighe();
                        return;
                    }

                    row.remove();
                    aggiornaStatoRighe();
                });
            }

            if (productSelect) {
                productSelect.addEventListener('change', function () {
                    aggiornaCostoRiga(row);
                });
            }
        }

        addButton.addEventListener('click', function () {
            const markup = template.innerHTML.split('__INDEX__').join(String(nextIndex));
            nextIndex += 1;
            list.dataset.nextIndex = String(nextIndex);

            const fragment = document.createRange().createContextualFragment(markup);
            const newRows = Array.from(fragment.querySelectorAll('[data-repeatable-row]'));
            rowsContainer.appendChild(fragment);

            newRows.forEach(function (row) {
                collegaRiga(row);
            });

            aggiornaStatoRighe();

            const firstField = rowsContainer.querySelector('[data-repeatable-row]:last-child select, [data-repeatable-row]:last-child input');
            if (firstField) {
                firstField.focus();
            }
        });

        rowsContainer.querySelectorAll('[data-repeatable-row]').forEach(function (row) {
            collegaRiga(row);
        });

        aggiornaStatoRighe();
    });
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
    inizializzaHeaderSede();
    inizializzaBranchSwitcher();
    inizializzaMappaSedi();
    inizializzaCheckoutRitiro();
    inizializzaCheckoutPagamento();
    inizializzaStampaRicevuta();
    inizializzaAdminCatalogoProdotto();
    inizializzaAdminSupplyBuilder();
});
