<?php
/**
 * sedi: View della pagina sedi.
 *
 * Variabili attese:
 *   $allBranches    array
 *   $selectedBranch ?array
 *   $branchesJson   string
 *   $branchWarning  ?string
 */
?>

<!-- ==========================================================
     HERO — Trova la tua sede
     ========================================================== -->
<section class="sedi-hero" aria-labelledby="titolo-sedi">
    <div class="contenitore">
        <div class="home-section-head">
            <h1 id="titolo-sedi" class="home-section-title">Le nostre sedi</h1>
            <p class="home-section-sub">
                Siamo presenti in diverse citt&agrave; italiane. Seleziona una sede per vedere
                orari, indirizzo e mappa. Puoi ordinare online e ritirare in sede.
            </p>
        </div>
    </div>
</section>

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
    <section aria-label="Sedi non disponibili">
        <div class="contenitore">
            <p>Al momento non ci sono sedi disponibili.</p>
        </div>
    </section>
<?php else: ?>
    <section id="sedi-interattive"
        class="sedi-layout"
        data-selected-slug="<?php echo htmlspecialchars($viewedBranch['slug'], ENT_QUOTES, 'UTF-8'); ?>"
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
