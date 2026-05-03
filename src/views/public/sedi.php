<?php
/**
 * sedi: View dinamica chi siamo / sedi.
 *
 * Variabili attese:
 *   $allBranches    array
 *   $selectedBranch ?array
 *   $viewedBranch   ?array
 *   $branchesJson   string
 *   $branchWarning  ?string
 */
?>

<section class="chi-siamo-intro" aria-labelledby="titolo-sedi">
    <div class="contenitore">
        <h1 id="titolo-sedi">Chi siamo e le nostre sedi</h1>
        <p class="chi-siamo-lead">
            Smash Burger Original nasce in Veneto con una promessa semplice: burger fatti al momento,
            ingredienti selezionati e qualità costante in ogni sede.
        </p>

        <div class="chi-siamo-grid">
            <article class="chi-siamo-card">
                <h2>La nostra filosofia</h2>
                <p>
                    Lavoriamo con processi chiari, ricette replicabili e attenzione al dettaglio.
                    Ogni ordine viene preparato espresso, con tempi rapidi e standard condivisi.
                </p>
            </article>

            <article class="chi-siamo-card">
                <h2>Qualità e trasparenza</h2>
                <p>
                    Tracciamo disponibilità e menu in modo dinamico per sede: quello che vedi online
                    è allineato al punto vendita selezionato, inclusi orari e note di ritiro.
                </p>
            </article>

            <article class="chi-siamo-card">
                <h2>Esperienza locale</h2>
                <p>
                    Ogni sede è integrata nel proprio territorio con team dedicato e servizio uniforme.
                    Dal ritiro immediato al ritiro programmato, l'esperienza resta semplice e affidabile.
                </p>
            </article>
        </div>
    </div>
</section>

<?php if (!empty($branchWarning)): ?>
    <section aria-label="Avviso sede">
        <div class="contenitore">
            <?php echo ui_alert(['type' => 'error', 'message' => $branchWarning]); ?>
        </div>
    </section>
<?php endif; ?>

<?php if (empty($allBranches) || empty($selectedBranch)): ?>
    <section aria-label="Sedi non disponibili">
        <div class="contenitore">
            <p>Al momento non ci sono sedi disponibili.</p>
        </div>
    </section>
<?php else: ?>
    <section id="sedi-interattive"
        class="sedi-layout"
        data-selected-slug="<?php echo e($viewedBranch['slug']); ?>"
        aria-labelledby="titolo-sedi-interattive">
        <div class="contenitore">
            <h2 id="titolo-sedi-interattive">Seleziona una sede sulla mappa</h2>

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
                                    data-branch-slug="<?php echo e($branch['slug']); ?>"
                                    data-branch-name="<?php echo e($branch['name']); ?>"
                                    data-branch-city="<?php echo e($branch['city']); ?>"
                                    data-branch-province="<?php echo e($branch['province']); ?>"
                                    data-branch-address="<?php echo e($branch['address_line']); ?>"
                                    data-branch-postal="<?php echo e($branch['postal_code']); ?>"
                                    data-branch-phone="<?php echo e($branch['phone']); ?>"
                                    data-branch-email="<?php echo e($branch['email']); ?>"
                                    data-branch-notes="<?php echo e($branch['pickup_notes'] ?? ''); ?>"
                                    data-branch-hours="<?php echo e($branch['hours_compact'] ?? ''); ?>"
                                    data-branch-map="<?php echo e($branch['map_embed_url'] ?? ''); ?>">
                                    <strong><?php echo e($branch['city']); ?></strong>
                                    <span class="sede-indirizzo"><?php echo e($branch['address_line']); ?></span>
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
                        <h3 id="sede-dettaglio-nome"><?php echo e($viewedBranch['name']); ?></h3>
                        <p id="sede-dettaglio-indirizzo">
                            <?php echo e($viewedBranch['address_line']); ?>,
                            <?php echo e($viewedBranch['postal_code']); ?>
                            <?php echo e($viewedBranch['city']); ?>
                            (<?php echo e($viewedBranch['province']); ?>)
                        </p>
                        <p>
                            Telefono sede:
                            <a id="sede-dettaglio-phone-link"
                                href="tel:<?php echo e(preg_replace('/[^0-9+]/', '', (string) $viewedBranch['phone'])); ?>">
                                <span id="sede-dettaglio-phone"><?php echo e($viewedBranch['phone']); ?></span>
                            </a>
                        </p>
                        <p>
                            Email sede:
                            <a id="sede-dettaglio-email-link"
                                href="mailto:<?php echo e($viewedBranch['email']); ?>">
                                <span id="sede-dettaglio-email"><?php echo e($viewedBranch['email']); ?></span>
                            </a>
                        </p>
                        <p id="sede-dettaglio-orari">
                            <strong>Orari:</strong>
                            <span id="sede-dettaglio-orari-valore"><?php echo e($viewedBranch['hours_compact'] ?? 'Orari non disponibili'); ?></span>
                        </p>
                        <p id="sede-dettaglio-note">
                            <strong>Note ritiro:</strong>
                            <span id="sede-dettaglio-note-valore"><?php echo e($viewedBranch['pickup_notes'] ?? ''); ?></span>
                        </p>

                    </article>

                    <div class="sede-mappa-box">
                        <iframe
                            id="sedi-mappa-frame"
                            title="Mappa sede selezionata"
                            src="<?php echo e($viewedBranch['map_embed_url']); ?>">
                        </iframe>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script id="branches-data" type="application/json"><?php echo e($branchesJson ?: '[]'); ?></script>
<?php endif; ?>
