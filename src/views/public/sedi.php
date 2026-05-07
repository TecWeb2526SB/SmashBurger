<?php
/**
 * View della pagina Sedi.
 *
 * Variabili attese:
 *   $allBranches      array
 *   $selectedBranch   ?array
 *   $viewedBranch     ?array
 *   $branchWarning    ?string
 *   $branchesJson     string
 */
?>

<section id="chi-sedi" class="chi-sedi sedi-layout" data-selected-slug="<?php echo e((string) ($viewedBranch['slug'] ?? '')); ?>" aria-labelledby="chi-sedi-titolo">
    <div class="contenitore">
        <div class="home-section-head">
            <h1 id="chi-sedi-titolo" class="home-section-title">Le nostre sedi</h1>
            <p class="home-section-sub">
                Siamo presenti in diverse citt&agrave; italiane. Seleziona una sede per vedere
                orari, indirizzo e mappa. Puoi ordinare online e ritirare in sede.
            </p>
        </div>

        <?php if (!empty($branchWarning)): ?>
            <div class="alert error">
                <?php echo e($branchWarning); ?>
            </div>
        <?php endif; ?>

        <?php if (empty($allBranches) || empty($viewedBranch)): ?>
            <p class="home-section-sub">Al momento non ci sono sedi disponibili.</p>
        <?php else: ?>
            <div id="sedi-interattive" class="sedi-grid">
                <nav class="sedi-lista" aria-label="Elenco sedi disponibili">
                    <h2>Sedi disponibili</h2>
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
                                    data-branch-slug="<?php echo e((string) $branch['slug']); ?>"
                                    data-branch-name="<?php echo e((string) $branch['name']); ?>"
                                    data-branch-city="<?php echo e((string) $branch['city']); ?>"
                                    data-branch-province="<?php echo e((string) $branch['province']); ?>"
                                    data-branch-address="<?php echo e((string) $branch['address_line']); ?>"
                                    data-branch-postal="<?php echo e((string) $branch['postal_code']); ?>"
                                    data-branch-phone="<?php echo e((string) $branch['phone']); ?>"
                                    data-branch-email="<?php echo e((string) $branch['email']); ?>"
                                    data-branch-notes="<?php echo e((string) ($branch['pickup_notes'] ?? '')); ?>"
                                    data-branch-hours="<?php echo e((string) ($branch['hours_compact'] ?? '')); ?>"
                                    data-branch-map="<?php echo e((string) ($branch['map_embed_url'] ?? '')); ?>">
                                    <strong><?php echo e((string) $branch['city']); ?></strong>
                                    <span class="sede-indirizzo"><?php echo e((string) $branch['address_line']); ?></span>
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
                        <h2 id="sede-dettaglio-nome"><?php echo e((string) ($viewedBranch['name'] ?? '')); ?></h2>
                        <p id="sede-dettaglio-indirizzo">
                            <?php echo e((string) ($viewedBranch['address_line'] ?? '')); ?>,
                            <?php echo e((string) ($viewedBranch['postal_code'] ?? '')); ?>
                            <?php echo e((string) ($viewedBranch['city'] ?? '')); ?>
                            (<?php echo e((string) ($viewedBranch['province'] ?? '')); ?>)
                        </p>
                        <p>
                            Telefono sede:
                            <a id="sede-dettaglio-phone-link" href="tel:<?php echo e(preg_replace('/[^0-9+]/', '', (string) ($viewedBranch['phone'] ?? ''))); ?>">
                                <span id="sede-dettaglio-phone"><?php echo e((string) ($viewedBranch['phone'] ?? '')); ?></span>
                            </a>
                        </p>
                        <p>
                            Email sede:
                            <a id="sede-dettaglio-email-link" href="mailto:<?php echo e((string) ($viewedBranch['email'] ?? '')); ?>">
                                <span id="sede-dettaglio-email"><?php echo e((string) ($viewedBranch['email'] ?? '')); ?></span>
                            </a>
                        </p>
                        <p id="sede-dettaglio-orari">
                            <strong>Orari:</strong>
                            <span id="sede-dettaglio-orari-valore"><?php echo e((string) ($viewedBranch['hours_compact'] ?? 'Orari non disponibili')); ?></span>
                        </p>
                        <p id="sede-dettaglio-note">
                            <strong>Note ritiro:</strong>
                            <span id="sede-dettaglio-note-valore"><?php echo e((string) ($viewedBranch['pickup_notes'] ?? '')); ?></span>
                        </p>
                    </article>

                    <div class="sede-mappa-box">
                        <iframe
                            id="sedi-mappa-frame"
                            title="Mappa sede selezionata"
                            src="<?php echo e((string) ($viewedBranch['map_embed_url'] ?? '')); ?>">
                        </iframe>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <script id="branches-data" type="application/json"><?php echo htmlspecialchars($branchesJson ?: '[]', ENT_NOQUOTES, 'UTF-8'); ?></script>
    </div>
</section>

<section class="chi-cta" aria-labelledby="chi-cta-titolo">
    <div class="contenitore">
        <div class="chi-cta-box">
            <h2 id="chi-cta-titolo">Pronto a ordinare?</h2>
            <p>
                Scopri il menu e invia il tuo ordine alla sede più vicina.
            </p>
            <div class="chi-cta-azioni">
                <a href="<?php echo e(app_route('prodotti')); ?>" class="bottone-primario">Scopri il menu</a>
            </div>
        </div>
    </div>
</section>
