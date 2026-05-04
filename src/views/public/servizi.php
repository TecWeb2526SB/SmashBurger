<?php
/**
 * Pagina pubblica "Servizi" — linguaggio visivo dedicato (sezione 19.2 dello style.css):
 * hero scuro con immagine a sinistra, servizi numerati 01-04 alternati,
 * tre formule catering in stile listino, impegni a strisce verticali,
 * allergeni in pill grid e contatti con callout asimmetrico.
 *
 * Variabili attese dal controller:
 *   $selectedBranch  ?array  Sede attiva (chiavi: name, phone, email)
 *   $brandContacts    array  (chiavi usate: support_email, info_phone, order_phone)
 *   $allBranches      array
 */

$supportEmail  = $brandContacts['support_email'] ?? 'info@smashburger.it';
$infoPhone     = $brandContacts['info_phone']    ?? '';
$orderPhone    = $brandContacts['order_phone']   ?? '';
$cateringEmail = 'eventi@smashburger.it';
?>

<!-- ==========================================================
     1. HERO — sfondo scuro con accento, immagine a sinistra
     ========================================================== -->
<section class="srv-hero" aria-labelledby="srv-hero-titolo">
    <div class="contenitore">
        <div class="srv-hero-grid">

            <figure class="srv-hero-image">
                <img
                    src="images/evento.png"
                    alt="Allestimento catering Smash Burger con vassoi di mini burger e patatine fresche"
                    loading="eager"
                >
            </figure>

            <div class="srv-hero-content">
                <h1 id="srv-hero-titolo" class="srv-hero-titolo">
                    Tutto quello che facciamo<br>
                    <em>oltre il bancone</em>
                </h1>

                <p class="srv-hero-lead">
                    Ordine online con ritiro veloce, catering aziendale e privatizzazione del locale
                    per eventi privati. Stessa cucina, stessa qualità, su qualunque scala.
                </p>

                <ul class="srv-hero-quicknav" aria-label="Vai alla sezione">
                    <li><a href="#srv-servizi">Servizi</a></li>
                    <li><a href="#srv-catering">Catering</a></li>
                    <li><a href="#srv-impegni">Impegni</a></li>
                    <li><a href="#srv-allergeni">Allergeni</a></li>
                    <li><a href="#srv-contatti">Contatti</a></li>
                </ul>
            </div>

        </div>
    </div>
</section>


<!-- ==========================================================
     2. SERVIZI numerati 01-04 alternati
     ========================================================== -->
<section id="srv-servizi" class="srv-numerati" aria-labelledby="srv-servizi-titolo">
    <div class="contenitore">

        <div class="home-section-head">
            <h2 id="srv-servizi-titolo" class="home-section-title">Quattro modi di mangiare da Smash Burger</h2>
            <p class="home-section-sub">
                Dal pasto rapido al pranzo pianificato per tutto l'ufficio: ogni servizio nasce dalla
                stessa cucina, con la stessa carne 100% bovino italiano battuta sulla piastra.
            </p>
        </div>

        <ol class="srv-num-list">

            <li class="srv-num-item">
                <div class="srv-num-numero" aria-hidden="true">01</div>
                <div class="srv-num-corpo">
                    <div>
                        <h3 class="srv-num-titolo">Ordine online &amp; ritiro in sede</h3>
                        <p class="srv-num-desc">
                            Costruisci il tuo menu dal sito, paghi al tavolo o al ritiro e passi a
                            prendere l'ordine quando vuoi. Niente coda, niente attese: te lo prepariamo
                            solo quando arrivi sul posto, così lo trovi caldo e appena fatto.
                        </p>
                    </div>
                    <ul class="srv-num-highlights">
                        <li>Pronto in circa 12 minuti dall'arrivo</li>
                        <li>Pagamento in cassa, online o in contanti</li>
                        <li>Storico ordini e riordino con un clic</li>
                    </ul>
                </div>
            </li>

            <li class="srv-num-item">
                <div class="srv-num-numero" aria-hidden="true">02</div>
                <div class="srv-num-corpo">
                    <div>
                        <h3 class="srv-num-titolo">Pranzo in sede, anche al volo</h3>
                        <p class="srv-num-desc">
                            Tavoli all'interno e dehor esterno nelle sedi che lo prevedono. Servizio
                            rapido a banco per la pausa pranzo, formule complete con bevanda e
                            contorno per chi vuole sedersi senza pensieri.
                        </p>
                    </div>
                    <ul class="srv-num-highlights">
                        <li>Cucina a vista in tutte le sedi</li>
                        <li>Menu pranzo dal lunedì al venerdì</li>
                        <li>Wi-Fi gratuito e prese al tavolo</li>
                    </ul>
                </div>
            </li>

            <li class="srv-num-item">
                <div class="srv-num-numero" aria-hidden="true">03</div>
                <div class="srv-num-corpo">
                    <div>
                        <h3 class="srv-num-titolo">Catering aziendale</h3>
                        <p class="srv-num-desc">
                            Riunioni, lanci di prodotto, formazione, team building: portiamo lo smash
                            in ufficio. Tre formule modulari da 10 a 200 persone, con un referente
                            dedicato dalla conferma fino al ritiro dei materiali.
                        </p>
                    </div>
                    <ul class="srv-num-highlights">
                        <li>Da 10 a 200 persone, con preavviso di 48h</li>
                        <li>Allestimento completo: vassoi, salse, tovaglioli</li>
                        <li>Fattura aziendale e referente unico</li>
                    </ul>
                </div>
            </li>

            <li class="srv-num-item">
                <div class="srv-num-numero" aria-hidden="true">04</div>
                <div class="srv-num-corpo">
                    <div>
                        <h3 class="srv-num-titolo">Eventi privati &amp; privatizzazione</h3>
                        <p class="srv-num-desc">
                            Compleanni, lauree, feste aziendali in formato più informale: privatizzi
                            una sezione del locale o l'intero spazio, scegli la formula e ci pensiamo
                            noi al resto. Decorazioni neutre incluse, personalizzazioni su richiesta.
                        </p>
                    </div>
                    <ul class="srv-num-highlights">
                        <li>Privatizzazione parziale o totale del locale</li>
                        <li>Menu fisso o menù libero alla carta</li>
                        <li>Possibilità di torta personalizzata</li>
                    </ul>
                </div>
            </li>

        </ol>
    </div>
</section>


<!-- ==========================================================
     3. CATERING — listino con 3 formule
     ========================================================== -->
<section id="srv-catering" class="srv-catering" aria-labelledby="srv-catering-titolo">
    <div class="contenitore">

        <div class="home-section-head">
            <h2 id="srv-catering-titolo" class="home-section-title">Tre formule, un solo standard</h2>
            <p class="home-section-sub">
                Scegli la formula più adatta alla tua occasione. Tutte includono carne 100% bovino
                italiano, pane brioche fresco e salse di nostra produzione.
            </p>
        </div>

        <ul class="srv-formule">

            <li class="srv-formula">
                <h3>Box &amp; Go</h3>
                <div class="srv-formula-meta">
                    <span>10&minus;30 persone</span>
                    <span>Da consegnare</span>
                </div>
                <p class="srv-formula-desc">
                    La soluzione veloce: box monoporzione richiudibile con burger, patatine e salsa.
                    Pronti per essere distribuiti in sala riunioni o sul cantiere.
                </p>
                <ul class="srv-formula-include">
                    <li>1 smash burger a scelta tra 4 ricette</li>
                    <li>Patatine fresche tagliate a mano</li>
                    <li>Salse fatte in casa</li>
                    <li>Bevanda in lattina</li>
                </ul>
                <a class="bottone-secondario"
                   href="mailto:<?php echo htmlspecialchars($cateringEmail, ENT_QUOTES, 'UTF-8'); ?>?subject=Richiesta%20preventivo%20Box%20%26%20Go">
                    Richiedi preventivo
                </a>
            </li>

            <li class="srv-formula srv-formula-consigliata">
                <span class="srv-formula-tag">La più richiesta</span>
                <h3>Burger Bar</h3>
                <div class="srv-formula-meta">
                    <span>30&minus;100 persone</span>
                    <span>In sede o ufficio</span>
                </div>
                <p class="srv-formula-desc">
                    Allestiamo una vera postazione di smash burger live: piastra a vista, due cuochi,
                    costruzione su ordinazione. L'esperienza ristorante portata da te.
                </p>
                <ul class="srv-formula-include">
                    <li>Postazione con piastra e cuochi dedicati</li>
                    <li>Burger costruito al momento, 4 ricette</li>
                    <li>Patatine fresche, onion rings, insalata</li>
                    <li>Bevande analcoliche illimitate</li>
                    <li>Servizio cameriere e gestione tavolo</li>
                </ul>
                <a class="bottone-primario"
                   href="mailto:<?php echo htmlspecialchars($cateringEmail, ENT_QUOTES, 'UTF-8'); ?>?subject=Richiesta%20preventivo%20Burger%20Bar">
                    Richiedi preventivo
                </a>
            </li>

            <li class="srv-formula">
                <h3>Privatizzazione</h3>
                <div class="srv-formula-meta">
                    <span>50&minus;200 persone</span>
                    <span>Locale dedicato</span>
                </div>
                <p class="srv-formula-desc">
                    Hai il locale tutto per te per la serata: menù fisso o alla carta, possibilità di
                    personalizzare allestimento, musica e timing del servizio.
                </p>
                <ul class="srv-formula-include">
                    <li>Locale parziale o totale per 3&minus;5 ore</li>
                    <li>Menù concordato in fase di preventivo</li>
                    <li>Bar dedicato e selezione drink</li>
                    <li>Allestimento neutro incluso</li>
                    <li>Personalizzazioni e torta su richiesta</li>
                </ul>
                <a class="bottone-secondario"
                   href="mailto:<?php echo htmlspecialchars($cateringEmail, ENT_QUOTES, 'UTF-8'); ?>?subject=Richiesta%20preventivo%20Privatizzazione">
                    Richiedi preventivo
                </a>
            </li>

        </ul>

        <p class="srv-catering-nota">
            Per richieste speciali (intolleranze, vegetariano, halal, kosher) scrivici a
            <a href="mailto:<?php echo htmlspecialchars($cateringEmail, ENT_QUOTES, 'UTF-8'); ?>">
                <?php echo htmlspecialchars($cateringEmail, ENT_QUOTES, 'UTF-8'); ?>
            </a>:
            ti rispondiamo entro 24 ore lavorative.
        </p>
    </div>
</section>


<!-- ==========================================================
     4. IMPEGNI — strisce verticali con accento
     ========================================================== -->
<section id="srv-impegni" class="srv-impegni" aria-labelledby="srv-impegni-titolo">
    <div class="contenitore">

        <div class="home-section-head">
            <h2 id="srv-impegni-titolo" class="home-section-title">I nostri impegni operativi</h2>
            <p class="home-section-sub">
                Regole semplici che ci diamo per evitare brutte sorprese, sia che tu ordini un singolo
                burger o un catering da 200 persone.
            </p>
        </div>

        <ul class="srv-impegni-grid">

            <li class="srv-impegno">
                <div>
                    <h3>Un ordine, una sede</h3>
                    <p>
                        Ogni ordine viene preparato esclusivamente nella sede che hai scelto: niente
                        trasferimenti, niente ricalcoli all'ultimo minuto.
                    </p>
                </div>
            </li>

            <li class="srv-impegno">
                <div>
                    <h3>Prezzo congelato al checkout</h3>
                    <p>
                        Il totale che vedi in fase di conferma è il totale che paghi: nessun costo
                        nascosto, nessun supplemento aggiunto in cassa.
                    </p>
                </div>
            </li>

            <li class="srv-impegno">
                <div>
                    <h3>Stato dell'ordine in chiaro</h3>
                    <p>
                        Dalla ricezione al ritiro vedi sempre a che punto siamo: ricevuto, in
                        preparazione, pronto. Nessuna attesa al buio.
                    </p>
                </div>
            </li>

            <li class="srv-impegno">
                <div>
                    <h3>Storico sempre disponibile</h3>
                    <p>
                        Tutti i tuoi ordini restano nell'area personale: utili per riordinare al volo
                        o per chiedere fattura a posteriori.
                    </p>
                </div>
            </li>

            <li class="srv-impegno">
                <div>
                    <h3>Cancellazione gratuita</h3>
                    <p>
                        Puoi cancellare l'ordine senza costi finché non è entrato in preparazione.
                        Per i catering vale il preavviso di 24 ore.
                    </p>
                </div>
            </li>

            <li class="srv-impegno">
                <div>
                    <h3>Materie prime tracciate</h3>
                    <p>
                        Carne italiana certificata, verdure dai mercati locali, salse di nostra
                        produzione: ogni fornitore è censito nel sistema interno.
                    </p>
                </div>
            </li>

        </ul>
    </div>
</section>


<!-- ==========================================================
     5. ALLERGENI — pill grid + nota informativa
     ========================================================== -->
<section id="srv-allergeni" class="srv-allergeni" aria-labelledby="srv-allergeni-titolo">
    <div class="contenitore">

        <div class="home-section-head">
            <h2 id="srv-allergeni-titolo" class="home-section-title">Allergeni &amp; opzioni alimentari</h2>
            <p class="home-section-sub">
                Etichettiamo ogni piatto con i 14 allergeni regolamentati dall'UE. Su richiesta, al
                momento dell'ordine, possiamo adattare la maggior parte delle ricette.
            </p>
        </div>

        <ul class="srv-opzioni" aria-label="Opzioni alimentari dedicate">
            <li class="srv-opzione">
                <span class="srv-opzione-badge" aria-hidden="true">GF</span>
                <div class="srv-opzione-corpo">
                    <h3>Senza glutine</h3>
                    <p>Pane GF in sostituzione su tutti i burger.</p>
                </div>
            </li>
            <li class="srv-opzione">
                <span class="srv-opzione-badge" aria-hidden="true">VEG</span>
                <div class="srv-opzione-corpo">
                    <h3>Vegetariano</h3>
                    <p>Patty 100% vegetale su qualunque ricetta.</p>
                </div>
            </li>
            <li class="srv-opzione">
                <span class="srv-opzione-badge" aria-hidden="true">L-FREE</span>
                <div class="srv-opzione-corpo">
                    <h3>Senza lattosio</h3>
                    <p>Formaggi e salse rimossi o sostituiti.</p>
                </div>
            </li>
        </ul>

        <div class="srv-allergeni-banner">

            <div class="srv-allergeni-intro">
                <h3>Allergeni segnalati</h3>
                <p>I principali allergeni regolamentati dall'UE presenti nel nostro menu.</p>
            </div>

            <ul class="srv-pillole" aria-label="Allergeni segnalati nel menu">
                <li class="srv-pill">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                         stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <polyline points="20 6 9 17 4 12"></polyline>
                    </svg>
                    Glutine
                </li>
                <li class="srv-pill">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                         stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <polyline points="20 6 9 17 4 12"></polyline>
                    </svg>
                    Lattosio
                </li>
                <li class="srv-pill">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                         stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <polyline points="20 6 9 17 4 12"></polyline>
                    </svg>
                    Uova
                </li>
                <li class="srv-pill">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                         stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <polyline points="20 6 9 17 4 12"></polyline>
                    </svg>
                    Soia
                </li>
                <li class="srv-pill">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                         stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <polyline points="20 6 9 17 4 12"></polyline>
                    </svg>
                    Sesamo
                </li>
                <li class="srv-pill">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                         stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <polyline points="20 6 9 17 4 12"></polyline>
                    </svg>
                    Senape
                </li>
                <li class="srv-pill">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                         stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <polyline points="20 6 9 17 4 12"></polyline>
                    </svg>
                    Solfiti
                </li>
                <li class="srv-pill">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                         stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <polyline points="20 6 9 17 4 12"></polyline>
                    </svg>
                    Frutta a guscio
                </li>
            </ul>

            <p class="srv-allergeni-nota">
                Schede tecniche complete disponibili in cassa o via email a
                <a href="mailto:<?php echo htmlspecialchars($supportEmail, ENT_QUOTES, 'UTF-8'); ?>">
                    <?php echo htmlspecialchars($supportEmail, ENT_QUOTES, 'UTF-8'); ?>
                </a>.
            </p>

        </div>
    </div>
</section>


<!-- ==========================================================
     6. CONTATTI — callout asimmetrico
     ========================================================== -->
<section id="srv-contatti" class="srv-contatti" aria-labelledby="srv-contatti-titolo">
    <div class="contenitore">

        <div class="home-section-head">
            <h2 id="srv-contatti-titolo" class="home-section-title">Parla con noi</h2>
            <p class="home-section-sub">
                Per ordini singoli usa il sito o la cassa in sede. Per tutto il resto &mdash; catering,
                fatturazione, eventi &mdash; trovi qui i canali giusti.
            </p>
        </div>

        <div class="srv-contatti-grid">

            <article class="srv-contatti-card">
                <h3>Canali ufficiali</h3>
                <ul class="srv-contatti-list">
                    <li>
                        <span class="srv-contatti-label">Email assistenza</span>
                        <a class="srv-contatti-value"
                           href="mailto:<?php echo htmlspecialchars($supportEmail, ENT_QUOTES, 'UTF-8'); ?>">
                            <?php echo htmlspecialchars($supportEmail, ENT_QUOTES, 'UTF-8'); ?>
                        </a>
                    </li>

                    <?php if (!empty($infoPhone)): ?>
                        <li>
                            <span class="srv-contatti-label">Centralino info</span>
                            <a class="srv-contatti-value"
                               href="tel:<?php echo htmlspecialchars(preg_replace('/[^0-9+]/', '', (string) $infoPhone), ENT_QUOTES, 'UTF-8'); ?>">
                                <?php echo htmlspecialchars($infoPhone, ENT_QUOTES, 'UTF-8'); ?>
                            </a>
                        </li>
                    <?php endif; ?>

                    <?php if (!empty($orderPhone)): ?>
                        <li>
                            <span class="srv-contatti-label">Ordini telefonici</span>
                            <a class="srv-contatti-value"
                               href="tel:<?php echo htmlspecialchars(preg_replace('/[^0-9+]/', '', (string) $orderPhone), ENT_QUOTES, 'UTF-8'); ?>">
                                <?php echo htmlspecialchars($orderPhone, ENT_QUOTES, 'UTF-8'); ?>
                            </a>
                        </li>
                    <?php endif; ?>

                    <li>
                        <span class="srv-contatti-label">Catering &amp; eventi</span>
                        <a class="srv-contatti-value"
                           href="mailto:<?php echo htmlspecialchars($cateringEmail, ENT_QUOTES, 'UTF-8'); ?>">
                            <?php echo htmlspecialchars($cateringEmail, ENT_QUOTES, 'UTF-8'); ?>
                        </a>
                    </li>

                    <?php if (!empty($selectedBranch)): ?>
                        <li>
                            <span class="srv-contatti-label">Sede attiva</span>
                            <span class="srv-contatti-value">
                                <?php echo htmlspecialchars((string) ($selectedBranch['name'] ?? 'Sede'), ENT_QUOTES, 'UTF-8'); ?>
                            </span>
                        </li>

                        <?php if (!empty($selectedBranch['phone'])): ?>
                            <li>
                                <span class="srv-contatti-label">Telefono sede</span>
                                <a class="srv-contatti-value"
                                   href="tel:<?php echo htmlspecialchars(preg_replace('/[^0-9+]/', '', (string) $selectedBranch['phone']), ENT_QUOTES, 'UTF-8'); ?>">
                                    <?php echo htmlspecialchars((string) $selectedBranch['phone'], ENT_QUOTES, 'UTF-8'); ?>
                                </a>
                            </li>
                        <?php endif; ?>
                    <?php endif; ?>
                </ul>
            </article>

            <aside class="srv-contatti-callout">
                <h3>Vuoi un preventivo<br>per un evento?</h3>
                <p>
                    Scrivici dimensione del gruppo, data e luogo: ti rispondiamo con una proposta
                    dettagliata entro 24 ore lavorative.
                </p>
                <a class="bottone-primario"
                   href="mailto:<?php echo htmlspecialchars($cateringEmail, ENT_QUOTES, 'UTF-8'); ?>?subject=Richiesta%20preventivo%20catering">
                    Richiedi preventivo
                </a>
            </aside>

        </div>
    </div>
</section>
