<?php
/**
 * chi-siamo.php: View della pagina "Chi siamo" di SmashBurger.
 *
 * Utilizza lo stesso linguaggio visivo delle pagine homepage e servizi:
 * - Hero scuro con immagine e titolo d'impatto
 * - Sezione storia con statistiche
 * - Valori aziendali in grid con icone
 * - Timeline/filosofia numerata
 * - CTA finale
 *
 * Variabili attese dal controller:
 *   $selectedBranch  ?array  Sede attiva
 *   $allBranches     array   Elenco sedi
 */
?>

<!-- ==========================================================
     1. HERO — sfondo scuro con immagine a sinistra (stile servizi)
     ========================================================== -->
<section class="chi-hero" aria-labelledby="chi-hero-titolo">
    <div class="contenitore">
        <div class="chi-hero-grid">

            <figure class="chi-hero-image">
                <img
                    src="images/image.png"
                    alt="Interno di un locale Smash Burger con bancone e cucina a vista"
                    loading="eager"
                >
            </figure>

            <div class="chi-hero-content">
                <h1 id="chi-hero-titolo" class="chi-hero-titolo">
                    Dal 2018, portiamo<br>
                    <em>lo smash in Italia</em>
                </h1>

                <p class="chi-hero-lead">
                    Smash Burger nasce dall'idea di tre amici tornati da un viaggio
                    sulla costa ovest degli Stati Uniti. La tecnica dello "smash" &mdash;
                    schiacciare una pallina di carne sulla piastra rovente &mdash; li conquista
                    dal primo morso.
                </p>

                <ul class="chi-hero-quicknav" aria-label="Vai alla sezione">
                    <li><a href="#chi-storia">La nostra storia</a></li>
                    <li><a href="#chi-valori">I nostri valori</a></li>
                    <li><a href="#chi-filosofia">Filosofia</a></li>
                    <li><a href="#chi-numeri">Numeri</a></li>
                    <li><a href="#chi-sedi">Le nostre sedi</a></li>
                </ul>
            </div>

        </div>
    </div>
</section>


<!-- ==========================================================
     2. LA NOSTRA STORIA — timeline con immagine
     ========================================================== -->
<section id="chi-storia" class="chi-storia" aria-labelledby="chi-storia-titolo">
    <div class="contenitore">
        <div class="home-section-head">
            <h2 id="chi-storia-titolo" class="home-section-title">Come tutto &egrave; cominciato</h2>
            <p class="home-section-sub">
                Una storia fatta di passione per la cucina, viaggi oltreoceano e la voglia
                di portare in Italia qualcosa di autentico e diverso.
            </p>
        </div>

        <ol class="chi-timeline">
            <li class="chi-timeline-item">
                <div class="chi-timeline-anno" aria-hidden="true">2017</div>
                <div class="chi-timeline-corpo">
                    <h3>Il viaggio che cambia tutto</h3>
                    <p>
                        Tre amici &mdash; Marco, Luca e Andrea &mdash; partono per un road trip
                        lungo la costa ovest americana. A Los Angeles scoprono lo smash burger:
                        carne schiacciata sulla piastra rovente, crosta caramellata, succhi che
                        restano all'interno. &Egrave; amore al primo morso.
                    </p>
                </div>
            </li>

            <li class="chi-timeline-item">
                <div class="chi-timeline-anno" aria-hidden="true">2018</div>
                <div class="chi-timeline-corpo">
                    <h3>Nasce Smash Burger Original</h3>
                    <p>
                        Tornati in Italia, aprono il primo locale a Milano con una promessa
                        semplice: solo carne italiana di prima scelta, pane brioche artigianale,
                        nessun compromesso sulla qualit&agrave;. Il locale diventa subito un punto
                        di riferimento per gli amanti del burger.
                    </p>
                </div>
            </li>

            <li class="chi-timeline-item">
                <div class="chi-timeline-anno" aria-hidden="true">2020</div>
                <div class="chi-timeline-corpo">
                    <h3>Espansione nonostante le difficolt&agrave;</h3>
                    <p>
                        Anche durante la pandemia, l'azienda non si ferma. Viene lanciato il
                        sistema di ordini online con ritiro in sede, che permette di continuare
                        a servire i clienti in sicurezza. Apre la seconda sede a Bologna.
                    </p>
                </div>
            </li>

            <li class="chi-timeline-item">
                <div class="chi-timeline-anno" aria-hidden="true">2023</div>
                <div class="chi-timeline-corpo">
                    <h3>Cinque sedi in Italia</h3>
                    <p>
                        Oggi Smash Burger &egrave; presente in cinque citt&agrave; italiane,
                        con oltre 50 dipendenti e pi&ugrave; di 200.000 burger serviti ogni anno.
                        La missione resta la stessa: portare il vero smash burger americano
                        con ingredienti 100% italiani.
                    </p>
                </div>
            </li>
        </ol>
    </div>
</section>


<!-- ==========================================================
     3. I NOSTRI VALORI — grid con icone (stile feature-grid)
     ========================================================== -->
<section id="chi-valori" class="chi-valori" aria-labelledby="chi-valori-titolo">
    <div class="contenitore">
        <div class="home-section-head">
            <h2 id="chi-valori-titolo" class="home-section-title">I valori che ci guidano</h2>
            <p class="home-section-sub">
                Ogni decisione che prendiamo parte da questi principi fondamentali.
                Sono la bussola che orienta il nostro lavoro quotidiano.
            </p>
        </div>

        <ul class="chi-valori-grid" role="list">
            <li class="chi-valore-card">
                <span class="chi-valore-icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24" width="28" height="28" fill="none"
                         stroke="currentColor" stroke-width="2"
                         stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 2v4"/>
                        <path d="M5 9c0-2 3-3 7-3s7 1 7 3-3 4-7 4-7-2-7-4z"/>
                        <path d="M5 9v8c0 2 3 4 7 4s7-2 7-4V9"/>
                    </svg>
                </span>
                <h3>Qualit&agrave; senza compromessi</h3>
                <p>
                    Carne 100% italiana, macinata fresca ogni mattina. Pane brioche
                    artigianale, verdure di stagione. Non scendiamo a compromessi
                    sulla qualit&agrave; degli ingredienti.
                </p>
            </li>

            <li class="chi-valore-card">
                <span class="chi-valore-icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24" width="28" height="28" fill="none"
                         stroke="currentColor" stroke-width="2"
                         stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                        <path d="m9 12 2 2 4-4"/>
                    </svg>
                </span>
                <h3>Trasparenza totale</h3>
                <p>
                    Tracciamo ogni ingrediente dalla fonte al piatto. Allergeni sempre
                    indicati, prezzi chiari, nessun costo nascosto. Quello che vedi
                    &egrave; quello che paghi.
                </p>
            </li>

            <li class="chi-valore-card">
                <span class="chi-valore-icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24" width="28" height="28" fill="none"
                         stroke="currentColor" stroke-width="2"
                         stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"/>
                        <path d="M12 6v6l4 2"/>
                    </svg>
                </span>
                <h3>Rispetto del tempo</h3>
                <p>
                    Ordini pronti quando promesso. Sistema di prenotazione che funziona.
                    Niente attese inutili: il tuo tempo vale quanto il nostro.
                </p>
            </li>

            <li class="chi-valore-card">
                <span class="chi-valore-icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24" width="28" height="28" fill="none"
                         stroke="currentColor" stroke-width="2"
                         stroke-linecap="round" stroke-linejoin="round">
                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                        <circle cx="9" cy="7" r="4"/>
                        <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
                        <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                    </svg>
                </span>
                <h3>Team locale</h3>
                <p>
                    Ogni sede ha un team dedicato, formato internamente. Investiamo
                    nelle persone perch&eacute; crediamo che la qualit&agrave; parta
                    da chi lavora con noi.
                </p>
            </li>
        </ul>
    </div>
</section>


<!-- ==========================================================
     4. FILOSOFIA — sezioni numerate (stile servizi)
     ========================================================== -->
<section id="chi-filosofia" class="chi-filosofia" aria-labelledby="chi-filosofia-titolo">
    <div class="contenitore">
        <div class="home-section-head">
            <h2 id="chi-filosofia-titolo" class="home-section-title">La nostra filosofia</h2>
            <p class="home-section-sub">
                Tre pilastri su cui costruiamo ogni giorno l'esperienza Smash Burger.
            </p>
        </div>

        <ol class="chi-filosofia-list">
            <li class="chi-filosofia-item">
                <div class="chi-filosofia-numero" aria-hidden="true">01</div>
                <div class="chi-filosofia-corpo">
                    <div>
                        <h3 class="chi-filosofia-titolo">Tecnica autentica</h3>
                        <p class="chi-filosofia-desc">
                            Lo smash non &egrave; solo un nome: &egrave; una tecnica precisa.
                            Schiacciamo la pallina di carne sulla piastra a 260&deg;C nei primi
                            10 secondi di cottura. Si forma una crosta caramellata che sigilla
                            i succhi all'interno.
                        </p>
                    </div>
                    <ul class="chi-filosofia-highlights">
                        <li>Piastra a 260&deg;C, sempre</li>
                        <li>Carne schiacciata nei primi 10 secondi</li>
                        <li>Crosta caramellata, interno succoso</li>
                    </ul>
                </div>
            </li>

            <li class="chi-filosofia-item">
                <div class="chi-filosofia-numero" aria-hidden="true">02</div>
                <div class="chi-filosofia-corpo">
                    <div>
                        <h3 class="chi-filosofia-titolo">Ingredienti tracciabili</h3>
                        <p class="chi-filosofia-desc">
                            Conosciamo ogni fornitore personalmente. La carne arriva da allevamenti
                            italiani certificati, il pane viene sfornato ogni mattina da panifici
                            locali, le verdure sono di stagione e a km zero quando possibile.
                        </p>
                    </div>
                    <ul class="chi-filosofia-highlights">
                        <li>Carne 100% bovino italiano</li>
                        <li>Pane brioche fresco ogni giorno</li>
                        <li>Verdure di stagione locali</li>
                    </ul>
                </div>
            </li>

            <li class="chi-filosofia-item">
                <div class="chi-filosofia-numero" aria-hidden="true">03</div>
                <div class="chi-filosofia-corpo">
                    <div>
                        <h3 class="chi-filosofia-titolo">Esperienza replicabile</h3>
                        <p class="chi-filosofia-desc">
                            Che tu sia a Milano, Bologna o Padova, l'esperienza &egrave; la stessa.
                            Processi standardizzati, formazione continua del personale, controlli
                            qualit&agrave; costanti. Nessuna sorpresa, solo certezze.
                        </p>
                    </div>
                    <ul class="chi-filosofia-highlights">
                        <li>Standard identici in ogni sede</li>
                        <li>Formazione continua del team</li>
                        <li>Controlli qualit&agrave; quotidiani</li>
                    </ul>
                </div>
            </li>
        </ol>
    </div>
</section>


<!-- ==========================================================
     5. I NUMERI — statistiche d'impatto
     ========================================================== -->
<section id="chi-numeri" class="chi-numeri" aria-labelledby="chi-numeri-titolo">
    <div class="contenitore">
        <div class="home-section-head">
            <h2 id="chi-numeri-titolo" class="home-section-title">I numeri che parlano</h2>
            <p class="home-section-sub">
                Dietro ogni burger c'&egrave; un team che lavora con passione.
                Ecco cosa abbiamo costruito in questi anni.
            </p>
        </div>

        <ul class="chi-stats-grid" aria-label="Statistiche Smash Burger">
            <li class="chi-stat">
                <strong>5</strong>
                <span>Sedi in Italia</span>
            </li>
            <li class="chi-stat">
                <strong>200k+</strong>
                <span>Burger serviti ogni anno</span>
            </li>
            <li class="chi-stat">
                <strong>50+</strong>
                <span>Dipendenti</span>
            </li>
            <li class="chi-stat">
                <strong>4.8/5</strong>
                <span>Valutazione media clienti</span>
            </li>
            <li class="chi-stat">
                <strong>100%</strong>
                <span>Carne italiana</span>
            </li>
            <li class="chi-stat">
                <strong>2018</strong>
                <span>Anno di fondazione</span>
            </li>
        </ul>
    </div>
</section>


<!-- ==========================================================
     6. LE NOSTRE SEDI — mappa interattiva
     ========================================================== -->
<?php if (!empty($branchWarning)): ?>
    <section aria-label="Avviso sede">
        <div class="contenitore">
            <div class="alert error">
                <?php echo htmlspecialchars($branchWarning, ENT_QUOTES, 'UTF-8'); ?>
            </div>
        </div>
    </section>
<?php endif; ?>

<?php if (empty($allBranches) || empty($selectedBranch)): ?>
    <section id="chi-sedi" class="chi-sedi" aria-labelledby="chi-sedi-titolo">
        <div class="contenitore">
            <div class="home-section-head">
                <h2 id="chi-sedi-titolo" class="home-section-title">Le nostre sedi</h2>
                <p class="home-section-sub">Al momento non ci sono sedi disponibili.</p>
            </div>
        </div>
    </section>
<?php else: ?>
    <section id="chi-sedi"
        class="chi-sedi sedi-layout"
        data-selected-slug="<?php echo htmlspecialchars($viewedBranch['slug'], ENT_QUOTES, 'UTF-8'); ?>"
        aria-labelledby="chi-sedi-titolo">
        <div class="contenitore">
            <div class="home-section-head">
                <h2 id="chi-sedi-titolo" class="home-section-title">Le nostre sedi</h2>
                <p class="home-section-sub">
                    Siamo presenti in diverse citt&agrave; italiane. Seleziona una sede per vedere
                    orari, indirizzo e mappa. Puoi ordinare online e ritirare in sede.
                </p>
            </div>

            <div class="sedi-grid">
                <nav class="sedi-lista" aria-label="Elenco sedi disponibili">
                    <h3>Sedi disponibili</h3>
                    <ul>
                        <?php foreach ($allBranches as $branch): 
                            $isViewed = ((int) $viewedBranch['id'] === (int) $branch['id']);
                            $isActive = ((int) $selectedBranch['id'] === (int) $branch['id']);
                        ?>
                            <li>
                                <button
                                    type="button"
                                    class="sede-link<?php echo $isViewed ? ' attiva' : ''; ?>"
                                    data-branch-id="<?php echo (int) $branch['id']; ?>"
                                    data-branch-slug="<?php echo htmlspecialchars($branch['slug'], ENT_QUOTES, 'UTF-8'); ?>"
                                    data-branch-name="<?php echo htmlspecialchars($branch['name'], ENT_QUOTES, 'UTF-8'); ?>"
                                    data-branch-city="<?php echo htmlspecialchars($branch['city'], ENT_QUOTES, 'UTF-8'); ?>"
                                    data-branch-province="<?php echo htmlspecialchars($branch['province'], ENT_QUOTES, 'UTF-8'); ?>"
                                    data-branch-address="<?php echo htmlspecialchars($branch['address_line'], ENT_QUOTES, 'UTF-8'); ?>"
                                    data-branch-postal="<?php echo htmlspecialchars($branch['postal_code'], ENT_QUOTES, 'UTF-8'); ?>"
                                    data-branch-phone="<?php echo htmlspecialchars($branch['phone'], ENT_QUOTES, 'UTF-8'); ?>"
                                    data-branch-email="<?php echo htmlspecialchars($branch['email'], ENT_QUOTES, 'UTF-8'); ?>"
                                    data-branch-notes="<?php echo htmlspecialchars($branch['pickup_notes'] ?? '', ENT_QUOTES, 'UTF-8'); ?>"
                                    data-branch-hours="<?php echo htmlspecialchars($branch['hours_compact'] ?? '', ENT_QUOTES, 'UTF-8'); ?>"
                                    data-branch-map="<?php echo htmlspecialchars($branch['map_embed_url'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                                    <strong><?php echo htmlspecialchars($branch['city'], ENT_QUOTES, 'UTF-8'); ?></strong>
                                    <span class="sede-indirizzo"><?php echo htmlspecialchars($branch['address_line'], ENT_QUOTES, 'UTF-8'); ?></span>
                                    <?php if ($isActive): ?>
                                        <span class="badge-corrente">Attiva</span>
                                    <?php endif; ?>
                                </button>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </nav>

                <div class="sede-dettaglio-wrap">
                    <article class="sede-dettaglio-card" aria-labelledby="sede-dettaglio-nome">
                        <h3 id="sede-dettaglio-nome"><?php echo htmlspecialchars($viewedBranch['name'], ENT_QUOTES, 'UTF-8'); ?></h3>
                        <p id="sede-dettaglio-indirizzo">
                            <?php echo htmlspecialchars($viewedBranch['address_line'], ENT_QUOTES, 'UTF-8'); ?>,
                            <?php echo htmlspecialchars($viewedBranch['postal_code'], ENT_QUOTES, 'UTF-8'); ?>
                            <?php echo htmlspecialchars($viewedBranch['city'], ENT_QUOTES, 'UTF-8'); ?>
                            (<?php echo htmlspecialchars($viewedBranch['province'], ENT_QUOTES, 'UTF-8'); ?>)
                        </p>
                        <p>
                            Telefono sede:
                            <a id="sede-dettaglio-phone-link"
                                href="tel:<?php echo htmlspecialchars(preg_replace('/[^0-9+]/', '', (string) $viewedBranch['phone']), ENT_QUOTES, 'UTF-8'); ?>">
                                <span id="sede-dettaglio-phone"><?php echo htmlspecialchars($viewedBranch['phone'], ENT_QUOTES, 'UTF-8'); ?></span>
                            </a>
                        </p>
                        <p>
                            Email sede:
                            <a id="sede-dettaglio-email-link"
                                href="mailto:<?php echo htmlspecialchars($viewedBranch['email'], ENT_QUOTES, 'UTF-8'); ?>">
                                <span id="sede-dettaglio-email"><?php echo htmlspecialchars($viewedBranch['email'], ENT_QUOTES, 'UTF-8'); ?></span>
                            </a>
                        </p>
                        <p id="sede-dettaglio-orari">
                            <strong>Orari:</strong>
                            <span id="sede-dettaglio-orari-valore"><?php echo htmlspecialchars($viewedBranch['hours_compact'] ?? 'Orari non disponibili', ENT_QUOTES, 'UTF-8'); ?></span>
                        </p>
                        <p id="sede-dettaglio-note">
                            <strong>Note ritiro:</strong>
                            <span id="sede-dettaglio-note-valore"><?php echo htmlspecialchars($viewedBranch['pickup_notes'] ?? '', ENT_QUOTES, 'UTF-8'); ?></span>
                        </p>

                    </article>

                    <div class="sede-mappa-box">
                        <iframe
                            id="sedi-mappa-frame"
                            title="Mappa sede selezionata"
                            src="<?php echo htmlspecialchars($viewedBranch['map_embed_url'], ENT_QUOTES, 'UTF-8'); ?>">
                        </iframe>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script id="branches-data" type="application/json"><?php echo htmlspecialchars($branchesJson ?: '[]', ENT_NOQUOTES, 'UTF-8'); ?></script>
<?php endif; ?>


<!-- ==========================================================
     7. CTA FINALE — invito all'azione
     ========================================================== -->
<section class="chi-cta" aria-labelledby="chi-cta-titolo">
    <div class="contenitore">
        <div class="chi-cta-box">
            <h2 id="chi-cta-titolo">Pronto a provare?</h2>
            <p>
                Scopri il nostro menu e ordina online per ritirare nella sede pi&ugrave; vicina a te.
            </p>
            <div class="chi-cta-azioni">
                <a href="<?php echo e(app_route('prodotti')); ?>" class="bottone-primario">Scopri il menu</a>
            </div>
        </div>
    </div>
</section>
