<?php
/**
 * homepage.php: Contenuto della pagina principale di SmashBurger.
 *
 * NOTA: la pagina è scritta in HTML/CSS/JS; il PHP serve solo per il token CSRF
 * usato nei form di aggiunta al carrello (già presenti nel progetto).
 */

$csrfToken = csrf_token();
?>

<!-- =========================================================
     1. HERO
     ========================================================= -->
<section id="hero" class="home-hero" aria-labelledby="titolo-hero">
    <div class="contenitore">
        <div class="home-hero-grid">
            <div class="hero-content">
                <h1 id="titolo-hero" class="hero-titolo">
                    Il vero <span class="hero-titolo-accento">Smash Burger</span>.<br>
                    Croccante fuori, succoso dentro.
                </h1>
                <p class="hero-lead">
                    Carne italiana di prima scelta, schiacciata sulla piastra rovente
                    per creare la <strong>crosta caramellata</strong> che rende
                    unico ogni morso.
                </p>

                <div class="hero-cta">
                    <a href="<?php echo e(app_route('prodotti')); ?>" class="bottone-primario">Inizia l'ordine</a>
                    <a href="sedi" class="bottone-secondario">Trova la sede più vicina</a>
                </div>
            </div>

            <figure class="hero-image">
                <img
                    src="images/Cheeseburger.webp"
                    alt="Smash burger con doppio patty, cheddar fuso e cetriolini su pane brioche"
                    width="640"
                    height="640"
                    loading="eager"
                    decoding="async">
            </figure>
        </div>
    </div>
</section>


<!-- =========================================================
     2. PUNTI DI FORZA
     ========================================================= -->
<section id="punti-forza" aria-labelledby="titolo-punti-forza">
    <div class="contenitore">
        <div class="home-section-head">
            <h2 id="titolo-punti-forza" class="home-section-title">
                Quattro motivi per sceglierci ogni giorno
            </h2>
            <p class="home-section-sub">
                Non vendiamo semplicemente hamburger: serviamo l'autentica
                tecnica dello smash, con materie prime tracciabili e una filiera corta.
            </p>
        </div>

        <ul class="feature-grid" role="list">
            <li class="feature-card">
                <span class="feature-icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24" width="28" height="28" fill="none"
                         stroke="currentColor" stroke-width="2"
                         stroke-linecap="round" stroke-linejoin="round">
                        <path d="M3 12c0-3 3-5 6-5 2 0 3 1 4 1s2-1 4-1c3 0 6 2 6 5"/>
                        <path d="M3 12h18"/>
                        <path d="M5 16h14"/>
                        <path d="M7 20h10"/>
                    </svg>
                </span>
                <h3>Smashato al momento</h3>
                <p>
                    Pressiamo la pallina di carne sulla piastra a 260°C: si forma una
                    crosta caramellata irresistibile, mentre l'interno resta succoso.
                </p>
            </li>

            <li class="feature-card">
                <span class="feature-icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24" width="28" height="28" fill="none"
                         stroke="currentColor" stroke-width="2"
                         stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 2v4"/>
                        <path d="M5 9c0-2 3-3 7-3s7 1 7 3-3 4-7 4-7-2-7-4z"/>
                        <path d="M5 9v8c0 2 3 4 7 4s7-2 7-4V9"/>
                    </svg>
                </span>
                <h3>Carne 100% italiana</h3>
                <p>
                    Manzo allevato in Italia, macinato fresco ogni mattina nei nostri
                    laboratori. Niente conservanti, niente surgelati.
                </p>
            </li>

            <li class="feature-card">
                <span class="feature-icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24" width="28" height="28" fill="none"
                         stroke="currentColor" stroke-width="2"
                         stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 2C8 6 6 9 6 13a6 6 0 0 0 12 0c0-4-2-7-6-11z"/>
                        <path d="M12 22v-4"/>
                    </svg>
                </span>
                <h3>Ingredienti freschi</h3>
                <p>
                    Pane brioche artigianale, verdure di stagione e salse
                    preparate in casa. Selezioniamo fornitori locali per ridurre
                    le distanze e i tempi di consegna.
                </p>
            </li>

            <li class="feature-card">
                <span class="feature-icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24" width="28" height="28" fill="none"
                         stroke="currentColor" stroke-width="2"
                         stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="13" r="8"/>
                        <path d="M12 9v4l3 2"/>
                        <path d="M9 2h6"/>
                    </svg>
                </span>
                <h3>Ordini in 2 minuti</h3>
                <p>
                    Componi il tuo ordine online, scegli l'orario di ritiro
                    e paga in sicurezza. Riceverai una notifica quando il
                    burger sarà pronto.
                </p>
            </li>
        </ul>
    </div>
</section>


<!-- =========================================================
     3. BEST SELLER (con immagini)
     ========================================================= -->
<section id="best-seller" aria-labelledby="titolo-best-seller">
    <div class="contenitore">
        <div class="home-section-head">
            <h2 id="titolo-best-seller" class="home-section-title">
                I nostri Best Seller
            </h2>
            <p class="home-section-sub">
                Tre classici intramontabili, scelti ogni giorno da migliaia di clienti.
            </p>
        </div>

        <div class="menu-preview-grid">
            <article class="menu-preview-card" aria-labelledby="best-1">
                <figure class="menu-preview-figura">
                    <img
                        src="images/Cheeseburger.webp"
                        alt="Cheeseburger con cheddar fuso, cetriolini e ketchup"
                        width="480" height="360" loading="lazy" decoding="async">
                </figure>
                <div class="menu-preview-corpo">
                    <h3 id="best-1">Cheeseburger</h3>
                    <p>Patty di manzo smashato, Cheddar fuso, cetriolini, ketchup della casa.</p>
                    <span class="prezzo">10,90 &euro;</span>

                    <?php if (is_logged_in()): ?>
                        <form action="<?php echo e(app_route('carrello')); ?>" method="POST" class="scheda-prodotto-azioni">
                            <input type="hidden" name="action" value="add_product">
                            <input type="hidden" name="product_id" value="1">
                            <input type="hidden" name="quantity" value="1">
                            <input type="hidden" name="csrf_token" value="<?php echo $csrfToken; ?>">
                            <input type="hidden" name="redirect_to" value="./">
                            <button type="submit" class="bottone-primario">Aggiungi al carrello</button>
                        </form>
                    <?php endif; ?>
                </div>
            </article>

            <article class="menu-preview-card" aria-labelledby="best-2">
                <figure class="menu-preview-figura">
                    <img
                        src="images/Bacon-Burger.webp"
                        alt="Bacon Burger con bacon croccante, cheddar e salsa al bacon"
                        width="480" height="360" loading="lazy" decoding="async">
                </figure>
                <div class="menu-preview-corpo">
                    <h3 id="best-2">Bacon Burger</h3>
                    <p>Patty di manzo, bacon croccante, Cheddar, salsa affumicata al bacon.</p>
                    <span class="prezzo">12,50 &euro;</span>

                    <?php if (is_logged_in()): ?>
                        <form action="<?php echo e(app_route('carrello')); ?>" method="POST" class="scheda-prodotto-azioni">
                            <input type="hidden" name="action" value="add_product">
                            <input type="hidden" name="product_id" value="2">
                            <input type="hidden" name="quantity" value="1">
                            <input type="hidden" name="csrf_token" value="<?php echo $csrfToken; ?>">
                            <input type="hidden" name="redirect_to" value="./">
                            <button type="submit" class="bottone-primario">Aggiungi al carrello</button>
                        </form>
                    <?php endif; ?>
                </div>
            </article>

            <article class="menu-preview-card" aria-labelledby="best-3">
                <figure class="menu-preview-figura">
                    <img
                        src="images/IN-N-OUT.webp"
                        alt="IN-N-OUT con cipolla cruda, insalata, pomodoro e cheddar"
                        width="480" height="360" loading="lazy" decoding="async">
                </figure>
                <div class="menu-preview-corpo">
                    <h3 id="best-3">IN-N-OUT</h3>
                    <p>Patty di manzo, cipolla cruda, insalata iceberg, pomodoro, Cheddar.</p>
                    <span class="prezzo">11,50 &euro;</span>

                    <?php if (is_logged_in()): ?>
                        <form action="<?php echo e(app_route('carrello')); ?>" method="POST" class="scheda-prodotto-azioni">
                            <input type="hidden" name="action" value="add_product">
                            <input type="hidden" name="product_id" value="3">
                            <input type="hidden" name="quantity" value="1">
                            <input type="hidden" name="csrf_token" value="<?php echo $csrfToken; ?>">
                            <input type="hidden" name="redirect_to" value="./">
                            <button type="submit" class="bottone-primario">Aggiungi al carrello</button>
                        </form>
                    <?php endif; ?>
                </div>
            </article>
        </div>

        <p class="home-bottom-cta">
            <a href="<?php echo e(app_route('prodotti')); ?>" class="bottone-secondario">Vedi il catalogo completo</a>
        </p>
    </div>
</section>


<!-- =========================================================
     4. ESPLORA IL MENÙ (categorie con icone SVG)
     ========================================================= -->
<section id="categorie" aria-labelledby="titolo-categorie">
    <div class="contenitore">
        <div class="home-section-head">
            <h2 id="titolo-categorie" class="home-section-title">
                Esplora le categorie
            </h2>
            <p class="home-section-sub">
                Dai burger di manzo ai dolci, c'è qualcosa per ogni momento della giornata.
            </p>
        </div>

        <ul class="category-grid" role="list">
            <li>
                <a href="<?php echo e(app_route('prodotti', ['categoria' => 'burger-manzo'])); ?>" class="category-card">
                    <span class="category-card-icon" aria-hidden="true">
                        <svg viewBox="0 0 48 48" width="40" height="40" fill="none"
                             stroke="currentColor" stroke-width="2.5"
                             stroke-linecap="round" stroke-linejoin="round">
                            <path d="M6 22c0-7 8-12 18-12s18 5 18 12"/>
                            <path d="M6 22h36"/>
                            <path d="M9 28h30"/>
                            <path d="M12 34h24"/>
                            <path d="M10 38c0 2 2 4 5 4h18c3 0 5-2 5-4"/>
                        </svg>
                    </span>
                    <strong>Burger di Manzo</strong>
                    <span>8 ricette</span>
                </a>
            </li>
            <li>
                <a href="<?php echo e(app_route('prodotti', ['categoria' => 'burger-pollo'])); ?>" class="category-card">
                    <span class="category-card-icon" aria-hidden="true">
                        <svg viewBox="0 0 48 48" width="40" height="40" fill="none"
                             stroke="currentColor" stroke-width="2.5"
                             stroke-linecap="round" stroke-linejoin="round">
                            <path d="M14 26c-4 0-7-3-7-7s3-7 7-7c2 0 3 1 4 2 2-4 6-6 10-6 7 0 12 5 12 12 0 5-3 9-7 11"/>
                            <path d="M14 26c0 6 5 12 12 12s12-6 12-12"/>
                            <circle cx="32" cy="14" r="1.5" fill="currentColor"/>
                        </svg>
                    </span>
                    <strong>Burger di Pollo</strong>
                    <span>4 ricette</span>
                </a>
            </li>
            <li>
                <a href="<?php echo e(app_route('prodotti', ['categoria' => 'burger-vegan'])); ?>" class="category-card">
                    <span class="category-card-icon" aria-hidden="true">
                        <svg viewBox="0 0 48 48" width="40" height="40" fill="none"
                             stroke="currentColor" stroke-width="2.5"
                             stroke-linecap="round" stroke-linejoin="round">
                            <path d="M40 8c-12 0-22 8-22 20 0 4 2 8 4 10 2 2 6 4 10 4 12 0 20-10 20-22 0-4-4-12-12-12z"/>
                            <path d="M22 38c-4 4-10 4-14 0"/>
                        </svg>
                    </span>
                    <strong>Burger Vegan</strong>
                    <span>3 ricette</span>
                </a>
            </li>
            <li>
                <a href="<?php echo e(app_route('prodotti', ['categoria' => 'contorni'])); ?>" class="category-card">
                    <span class="category-card-icon" aria-hidden="true">
                        <svg viewBox="0 0 48 48" width="40" height="40" fill="none"
                             stroke="currentColor" stroke-width="2.5"
                             stroke-linecap="round" stroke-linejoin="round">
                            <path d="M14 18l4 22h12l4-22"/>
                            <path d="M12 18h24l-2-4H14z"/>
                            <path d="M20 22v14"/>
                            <path d="M24 22v14"/>
                            <path d="M28 22v14"/>
                        </svg>
                    </span>
                    <strong>Contorni</strong>
                    <span>6 sfizi</span>
                </a>
            </li>
            <li>
                <a href="<?php echo e(app_route('prodotti', ['categoria' => 'bevande'])); ?>" class="category-card">
                    <span class="category-card-icon" aria-hidden="true">
                        <svg viewBox="0 0 48 48" width="40" height="40" fill="none"
                             stroke="currentColor" stroke-width="2.5"
                             stroke-linecap="round" stroke-linejoin="round">
                            <path d="M14 10h20l-2 30c0 2-2 4-4 4H20c-2 0-4-2-4-4z"/>
                            <path d="M15 20h18"/>
                            <path d="M22 6v4"/>
                            <path d="M26 6v4"/>
                        </svg>
                    </span>
                    <strong>Bevande</strong>
                    <span>10 referenze</span>
                </a>
            </li>
            <li>
                <a href="<?php echo e(app_route('prodotti', ['categoria' => 'dolci'])); ?>" class="category-card">
                    <span class="category-card-icon" aria-hidden="true">
                        <svg viewBox="0 0 48 48" width="40" height="40" fill="none"
                             stroke="currentColor" stroke-width="2.5"
                             stroke-linecap="round" stroke-linejoin="round">
                            <path d="M16 18h16l-2 22c0 2-2 3-4 3h-4c-2 0-4-1-4-3z"/>
                            <path d="M14 18h20l-2-4H16z"/>
                            <path d="M24 14V8"/>
                            <circle cx="24" cy="6" r="2"/>
                        </svg>
                    </span>
                    <strong>Milkshake &amp; Dolci</strong>
                    <span>5 dolcezze</span>
                </a>
            </li>
        </ul>
    </div>
</section>


<!-- =========================================================
     5. LA NOSTRA STORIA
     ========================================================= -->
<section id="storia" aria-labelledby="titolo-storia">
    <div class="contenitore">
        <div class="storia-grid">
            <figure class="storia-figura">
                <img
                    src="images/image.png"
                    alt="Interno di un locale Smash Burger con bancone e cucina a vista"
                    width="640" height="540" loading="lazy" decoding="async">
            </figure>
            <div class="storia-corpo">
                <h2 id="titolo-storia">Dal 2018 portiamo lo smash in Italia</h2>
                <p>
                    Smash Burger nasce dall'idea di tre amici tornati da un viaggio
                    sulla costa ovest degli Stati Uniti. La tecnica dello "smash" &mdash;
                    schiacciare una pallina di carne sulla piastra rovente &mdash; li conquista
                    dal primo morso.
                </p>
                <p>
                    Apriamo la prima sede a <strong>Milano</strong> nel 2018 con una
                    promessa semplice: solo carne italiana, pane fatto come si deve,
                    nessun compromesso. Oggi siamo presenti in <strong>cinque città</strong> e
                    serviamo oltre <strong>200.000 burger</strong> ogni anno.
                </p>
                <ul class="storia-stats" aria-label="Numeri di Smash Burger">
                    <li>
                        <strong>5</strong>
                        <span>Sedi in Italia</span>
                    </li>
                    <li>
                        <strong>200k+</strong>
                        <span>Burger serviti ogni anno</span>
                    </li>
                    <li>
                        <strong>4.8/5</strong>
                        <span>Valutazione media clienti</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</section>


<!-- =========================================================
     6. COME ORDINARE
     ========================================================= -->
<section id="come-ordinare" aria-labelledby="titolo-come-ordinare">
    <div class="contenitore">
        <div class="home-section-head">
            <h2 id="titolo-come-ordinare" class="home-section-title">
                Come ordinare in meno di 2 minuti
            </h2>
        </div>
        <ol class="steps-grid">
            <li class="step-card">
                <strong>Step 1</strong>
                <h3>Crea il tuo ordine</h3>
                <p>
                    Scegli burger, contorni e bevande dal catalogo, con prezzi e
                    disponibilità aggiornati in tempo reale per la sede selezionata.
                </p>
            </li>
            <li class="feature-card">
                <strong>Step 2</strong>
                <h3>Conferma il ritiro</h3>
                <p>
                    Indica la fascia oraria di ritiro: vedrai il riepilogo completo
                    dell'ordine con tempi di preparazione stimati.
                </p>
            </li>
            <li class="feature-card">
                <strong>Step 3</strong>
                <h3>Paga e ritira</h3>
                <p>
                    Concludi il pagamento online in sicurezza. Quando l'ordine è pronto
                    ricevi una notifica: ti basterà passare in sede.
                </p>
            </li>
        </ol>
    </div>
</section>


<!-- =========================================================
     7. OFFERTE DEL MESE (1 in primo piano + 2 secondarie)
     ========================================================= -->
<section id="promozioni" aria-labelledby="titolo-promozioni">
    <div class="contenitore">
        <div class="home-section-head">
            <h2 id="titolo-promozioni" class="home-section-title">
                Offerte del mese
            </h2>
            <p class="home-section-sub">
                Tre proposte pensate per ogni occasione: pranzo veloce, cena in famiglia
                o serata con gli amici.
            </p>
        </div>

        <div class="offer-layout">
            <article class="offer-featured" aria-labelledby="titolo-bundle">
                <span class="offer-tag offer-tag-featured">Top del mese</span>
                <h3 id="titolo-bundle">Bundle Serata</h3>
                <p class="offer-desc">
                    Due burger premium a tua scelta, una porzione grande di onion rings
                    e due birre artigianali. Pensato per le serate del weekend con gli amici.
                </p>
                <ul class="offer-included" aria-label="Contenuto del bundle">
                    <li>2 burger premium a scelta</li>
                    <li>1 porzione grande di onion rings</li>
                    <li>2 birre artigianali alla spina</li>
                </ul>
                <div class="offer-cta-row">
                    <div class="offer-prezzo">
                        <span class="prezzo-vecchio" aria-label="Prezzo originale">39,80 &euro;</span>
                        <span class="prezzo-nuovo">29,90 &euro;</span>
                    </div>
                    <a href="<?php echo e(app_route('prodotti', ['promo' => 'bundle-serata'])); ?>" class="bottone-primario">Approfitta ora</a>
                </div>
                <p class="offer-note">Disponibile tutti i giorni dopo le 18:30 fino al 30 novembre.</p>
            </article>

            <div class="offer-secondari">
                <article class="offer-card" aria-labelledby="titolo-studente">
                    <span class="offer-tag">-15%</span>
                    <h3 id="titolo-studente">Menu Studente</h3>
                    <p>
                        Burger classico, patatine medie e bevanda con sconto del 15%
                        mostrando il badge universitario in cassa.
                    </p>
                    <div class="offer-prezzo">
                        <span class="prezzo-nuovo">10,90 &euro;</span>
                    </div>
                    <p class="offer-note">Lun-ven, fascia pranzo 12:00&ndash;15:00.</p>
                </article>

                <article class="offer-card" aria-labelledby="titolo-famiglia">
                    <span class="offer-tag">Combo</span>
                    <h3 id="titolo-famiglia">Combo Famiglia</h3>
                    <p>
                        Quattro Cheeseburger, due porzioni grandi di patatine
                        e quattro bevande a prezzo agevolato.
                    </p>
                    <div class="offer-prezzo">
                        <span class="prezzo-vecchio" aria-label="Prezzo originale">52,00 &euro;</span>
                        <span class="prezzo-nuovo">42,00 &euro;</span>
                    </div>
                    <p class="offer-note">Ideale per 3-4 persone, tutti i giorni.</p>
                </article>
            </div>
        </div>
    </div>
</section>


<!-- =========================================================
     8. FAQ (accordion con JS)
     ========================================================= -->
<section id="faq" aria-labelledby="titolo-faq">
    <div class="contenitore">
        <div class="home-section-head">
            <h2 id="titolo-faq" class="home-section-title">
                Le risposte alle domande più comuni
            </h2>
        </div>

        <div class="faq-list" data-faq>
            <details class="faq-item">
                <summary>
                    <span class="faq-question">Cos'è esattamente uno smash burger?</span>
                    <span class="faq-icon" aria-hidden="true"></span>
                </summary>
                <div class="faq-answer">
                    <p>
                        Una pallina di carne fresca viene "smashata" &mdash; schiacciata &mdash;
                        sulla piastra a 260°C per pochi secondi. Si forma così
                        una crosta caramellata (reazione di Maillard) che dà al burger
                        sapore intenso e bordi croccanti, mentre l'interno resta morbido e succoso.
                    </p>
                </div>
            </details>

            <details class="faq-item">
                <summary>
                    <span class="faq-question">Quanto tempo serve per preparare il mio ordine?</span>
                    <span class="faq-icon" aria-hidden="true"></span>
                </summary>
                <div class="faq-answer">
                    <p>
                        In media servono <strong>12-15 minuti</strong> dal momento in cui
                        confermi il pagamento. Riceverai una notifica via e-mail quando
                        il tuo ordine sarà pronto per il ritiro.
                    </p>
                </div>
            </details>

            <details class="faq-item">
                <summary>
                    <span class="faq-question">Avete opzioni vegetariane o vegane?</span>
                    <span class="faq-icon" aria-hidden="true"></span>
                </summary>
                <div class="faq-answer">
                    <p>
                        Sì. Nel menù trovi tre Vegan Burger con patty vegetale a base di
                        proteine di pisello, oltre a contorni e bevande adatti a una
                        dieta vegetariana o vegana. Tutti gli allergeni sono indicati
                        nella scheda prodotto.
                    </p>
                </div>
            </details>

            <details class="faq-item">
                <summary>
                    <span class="faq-question">Posso ordinare per più persone in un unico checkout?</span>
                    <span class="faq-icon" aria-hidden="true"></span>
                </summary>
                <div class="faq-answer">
                    <p>
                        Certo. Puoi aggiungere quanti prodotti vuoi al carrello &mdash;
                        consigliamo le offerte <a href="#promozioni">Combo Famiglia</a> o
                        <a href="#promozioni">Bundle Serata</a> per gruppi.
                    </p>
                </div>
            </details>

            <details class="faq-item">
                <summary>
                    <span class="faq-question">Fate consegna a domicilio?</span>
                    <span class="faq-icon" aria-hidden="true"></span>
                </summary>
                <div class="faq-answer">
                    <p>
                        Al momento offriamo solo il <strong>ritiro in sede</strong> per
                        garantire la qualità del prodotto: lo smash burger va mangiato
                        appena pronto. Stiamo valutando la consegna in alcune città.
                    </p>
                </div>
            </details>
        </div>
    </div>
</section>


<!-- =========================================================
     9. CLOSING CTA
     ========================================================= -->
<aside class="home-closing-cta" aria-labelledby="titolo-ordini">
    <div class="contenitore">
        <h2 id="titolo-ordini">Pronto a provare il vero Smash Burger?</h2>
        <p>
            Hai già ordinato con noi? Accedi alla tua
            <a href="<?php echo e(app_route('account')); ?>">area personale</a> per ritrovare lo storico ordini,
            ripetere un acquisto o gestire i tuoi indirizzi di ritiro.
        </p>
        <div class="hero-cta">
            <a href="<?php echo e(app_route('prodotti')); ?>" class="bottone-primario">Ordina adesso</a>
            <a href="sedi" class="bottone-secondario">Le nostre sedi</a>
        </div>
    </div>
</aside>
