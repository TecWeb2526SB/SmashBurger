<?php
/**
 * Elenco unico delle pagine del sito.
 *
 * Da qui derivano il menu, il controllo di accesso di ogni controller, la mappa del sito
 * e l'elenco delle pagine controllate dai test di qualita': la stessa informazione non
 * viene ripetuta altrove.
 *
 * Ogni voce contiene:
 *   etichetta    testo del collegamento nel menu e nel breadcrumb
 *   titolo       contenuto di <title>, entro 60 caratteri
 *   descrizione  contenuto di <meta name="description">, entro 160 caratteri
 *   ruoli        ruoli ammessi; array vuoto significa pagina pubblica
 *   menu         posizione nella navigazione: principale, azioni, controllo, footer
 */
function pagine(): array
{
    return [
        '' => [
            'etichetta' => 'Home',
            'titolo' => 'Smash Burger: ordina online, ritira o fatti consegnare',
            'descrizione' => 'Hamburger smash preparati al momento nelle sedi di Padova, Treviso, Vicenza e Udine. Ordina online, scegli ritiro o consegna.',
            'ruoli' => [],
            'menu' => 'principale',
        ],
        'menu' => [
            'etichetta' => 'Menu',
            'titolo' => 'Menu e prezzi - Smash Burger',
            'descrizione' => 'Burger, contorni, bevande e dessert con prezzi e allergeni. Scegli i prodotti e ritirali o fatteli consegnare dalla sede che preferisci.',
            'ruoli' => [],
            'menu' => 'principale',
        ],
        'prodotto' => [
            'etichetta' => 'Prodotto',
            'titolo' => 'Prodotto - Smash Burger',
            'descrizione' => '',
            'ruoli' => [],
            'menu' => null,
        ],
        'servizi' => [
            'etichetta' => 'Servizi',
            'titolo' => 'Servizi: asporto, domicilio, eventi - Smash Burger',
            'descrizione' => 'Ordine con ritiro o consegna a domicilio, sala eventi prenotabile per ricorrenze private, informazioni su allergeni e pagamento.',
            'ruoli' => [],
            'menu' => 'principale',
        ],
        'chi-siamo' => [
            'etichetta' => 'Chi siamo',
            'titolo' => 'Chi siamo - Smash Burger',
            'descrizione' => 'Come lavoriamo la carne, come nasce lo smash e come sono organizzate le nostre quattro sedi.',
            'ruoli' => [],
            'menu' => 'principale',
        ],
        'sedi' => [
            'etichetta' => 'Sedi',
            'titolo' => 'Sedi e orari - Smash Burger',
            'descrizione' => 'Indirizzi, orari di apertura e sala eventi prenotabile nelle sedi di Padova, Treviso, Vicenza e Udine.',
            'ruoli' => [],
            'menu' => 'principale',
        ],
        'sede' => [
            'etichetta' => 'Sede',
            'titolo' => 'Sede - Smash Burger',
            'descrizione' => '',
            'ruoli' => [],
            'menu' => null,
        ],
        'contatti' => [
            'etichetta' => 'Contatti',
            'titolo' => 'Contatti e assistenza - Smash Burger',
            'descrizione' => 'Scrivici per un ordine, una prenotazione o una segnalazione: rispondiamo dal modulo dedicato.',
            'ruoli' => [],
            'menu' => 'footer',
        ],

        'accedi' => [
            'etichetta' => 'Accedi',
            'titolo' => 'Accedi - Smash Burger',
            'descrizione' => '',
            'ruoli' => [],
            'menu' => null,
        ],
        'registrati' => [
            'etichetta' => 'Registrati',
            'titolo' => 'Registrati - Smash Burger',
            'descrizione' => '',
            'ruoli' => [],
            'menu' => null,
        ],
        'esci' => [
            'etichetta' => 'Esci',
            'titolo' => 'Esci - Smash Burger',
            'descrizione' => '',
            'ruoli' => ['cliente', 'manager', 'amministratore'],
            'menu' => null,
        ],
        // Non è una pagina da guardare: risponde solo in POST e rimanda indietro.
        // Resta elencata qui perchè ogni indirizzo del sito è descritto in un posto solo.
        'tema' => [
            'etichetta' => 'Tema',
            'titolo' => 'Tema - Smash Burger',
            'descrizione' => '',
            'ruoli' => [],
            'menu' => null,
        ],
        'area-personale' => [
            'etichetta' => 'Area personale',
            'titolo' => 'Area personale - Smash Burger',
            'descrizione' => '',
            'ruoli' => ['cliente', 'manager', 'amministratore'],
            'menu' => 'azioni',
        ],
        'profilo' => [
            'etichetta' => 'Profilo',
            'titolo' => 'Il tuo profilo - Smash Burger',
            'descrizione' => '',
            'ruoli' => ['cliente', 'manager', 'amministratore'],
            'menu' => null,
        ],

        'carrello' => [
            'etichetta' => 'Carrello',
            'titolo' => 'Carrello - Smash Burger',
            'descrizione' => '',
            'ruoli' => ['cliente'],
            'menu' => 'azioni',
        ],
        'pagamento' => [
            'etichetta' => 'Pagamento',
            'titolo' => 'Ritiro e pagamento - Smash Burger',
            'descrizione' => '',
            'ruoli' => ['cliente'],
            'menu' => null,
        ],
        'ricevuta' => [
            'etichetta' => 'Ricevuta',
            'titolo' => 'Ricevuta dell\'ordine - Smash Burger',
            'descrizione' => '',
            'ruoli' => ['cliente'],
            'menu' => null,
        ],
        'prenota' => [
            'etichetta' => 'Prenota la sala',
            'titolo' => 'Prenota la sala eventi - Smash Burger',
            'descrizione' => '',
            'ruoli' => ['cliente'],
            'menu' => null,
        ],

        'controllo' => [
            'etichetta' => 'Ordini',
            'titolo' => 'Ordini - Pannello di controllo',
            'descrizione' => '',
            'ruoli' => ['manager', 'amministratore'],
            'menu' => 'controllo',
        ],
        'controllo-ordine' => [
            'etichetta' => 'Dettaglio ordine',
            'titolo' => 'Dettaglio ordine - Pannello di controllo',
            'descrizione' => '',
            'ruoli' => ['manager', 'amministratore'],
            'menu' => null,
        ],
        'controllo-prodotti' => [
            'etichetta' => 'Prodotti',
            'titolo' => 'Prodotti - Pannello di controllo',
            'descrizione' => '',
            'ruoli' => ['manager', 'amministratore'],
            'menu' => 'controllo',
        ],
        'controllo-prodotto' => [
            'etichetta' => 'Scheda prodotto',
            'titolo' => 'Scheda prodotto - Pannello di controllo',
            'descrizione' => '',
            'ruoli' => ['amministratore'],
            'menu' => null,
        ],
        'controllo-categorie' => [
            'etichetta' => 'Categorie',
            'titolo' => 'Categorie - Pannello di controllo',
            'descrizione' => '',
            'ruoli' => ['amministratore'],
            'menu' => 'controllo',
        ],
        'controllo-sedi' => [
            'etichetta' => 'Sedi',
            'titolo' => 'Sedi - Pannello di controllo',
            'descrizione' => '',
            'ruoli' => ['amministratore'],
            'menu' => 'controllo',
        ],
        'controllo-sede' => [
            'etichetta' => 'Scheda sede',
            'titolo' => 'Scheda sede - Pannello di controllo',
            'descrizione' => '',
            'ruoli' => ['manager', 'amministratore'],
            'menu' => null,
        ],
        'controllo-prenotazioni' => [
            'etichetta' => 'Prenotazioni',
            'titolo' => 'Prenotazioni - Pannello di controllo',
            'descrizione' => '',
            'ruoli' => ['manager', 'amministratore'],
            'menu' => 'controllo',
        ],
        'controllo-contatti' => [
            'etichetta' => 'Messaggi',
            'titolo' => 'Messaggi - Pannello di controllo',
            'descrizione' => '',
            'ruoli' => ['amministratore'],
            'menu' => 'controllo',
        ],
        'controllo-utenti' => [
            'etichetta' => 'Utenti',
            'titolo' => 'Utenti - Pannello di controllo',
            'descrizione' => '',
            'ruoli' => ['amministratore'],
            'menu' => 'controllo',
        ],

        'privacy' => [
            'etichetta' => 'Privacy',
            'titolo' => 'Privacy policy - Smash Burger',
            'descrizione' => 'Quali dati raccogliamo, per quali finalita, per quanto tempo e come esercitare i tuoi diritti.',
            'ruoli' => [],
            'menu' => 'footer',
        ],
        'accessibilita' => [
            'etichetta' => 'Accessibilita',
            'titolo' => 'Accessibilita - Smash Burger',
            'descrizione' => 'Stato di conformita del sito alle linee guida WCAG 2.1 AA e modalita per segnalare un problema.',
            'ruoli' => [],
            'menu' => 'footer',
        ],
        'mappa-sito' => [
            'etichetta' => 'Mappa del sito',
            'titolo' => 'Mappa del sito - Smash Burger',
            'descrizione' => 'Elenco completo delle pagine del sito, ordinate per area.',
            'ruoli' => [],
            'menu' => 'footer',
        ],
    ];
}

/**
 * Dati di una pagina, oppure null se lo slug non esiste.
 */
function pagina_dati(string $slug): ?array
{
    return pagine()[$slug] ?? null;
}

/**
 * Voci di una posizione della navigazione, come coppie slug/etichetta.
 *
 * Restituisce solo le pagine che il ruolo indicato puo' aprire, cosi' il menu non
 * mostra mai collegamenti che porterebbero a una pagina di errore.
 */
function pagine_del_menu(string $posizione, ?string $ruolo): array
{
    $voci = [];

    foreach (pagine() as $slug => $dati) {
        if ($dati['menu'] === $posizione && ruolo_ammesso($dati['ruoli'], $ruolo)) {
            $voci[$slug] = $dati['etichetta'];
        }
    }

    return $voci;
}

/**
 * Verifica se un ruolo rientra fra quelli ammessi da una pagina.
 *
 * @param array       $ammessi array vuoto per le pagine pubbliche
 * @param string|null $ruolo   null per chi non ha fatto l'accesso
 */
function ruolo_ammesso(array $ammessi, ?string $ruolo): bool
{
    return $ammessi === [] || ($ruolo !== null && in_array($ruolo, $ammessi, true));
}

/**
 * Slug delle pagine pubbliche che hanno una descrizione, cioè quelle che ha senso
 * indicizzare. Alimenta la mappa del sito e sitemap.xml.
 */
function pagine_pubbliche(): array
{
    $slug = [];

    foreach (pagine() as $chiave => $dati) {
        if ($dati['ruoli'] === [] && $dati['descrizione'] !== '') {
            $slug[] = $chiave;
        }
    }

    return $slug;
}
