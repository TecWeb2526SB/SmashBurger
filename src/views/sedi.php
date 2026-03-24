<?php
/**
 * sedi.php: View dinamica chi siamo / sedi.
 *
 * Variabili attese:
 *   $allBranches    array
 *   $selectedBranch ?array
 *   $brandContacts  array
 *   $branchesJson   string
 *   $branchWarning  ?string
 */
?>

<section aria-labelledby="titolo-sedi">
    <div class="contenitore">
        <h1 id="titolo-sedi">Chi siamo e le nostre sedi</h1>
        <p>
            Smash Burger Original nasce in Veneto con una promessa semplice: burger fatti al momento,
            processi chiari e qualita costante in ogni sede.
        </p>
        <p>
            Oggi puoi ordinare online scegliendo la sede da cui ritirare: disponibilita e gestione ordini
            sono differenziate per punto vendita.
        </p>
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

<section id="contatti-brand" aria-labelledby="titolo-contatti-brand">
    <div class="contenitore">
        <h2 id="titolo-contatti-brand">Contatti brand</h2>
        <div class="sede-dettaglio-card">
            <p>
                <strong><?php echo htmlspecialchars($brandContacts['brand_name'] ?? 'Smash Burger Original', ENT_QUOTES, 'UTF-8'); ?></strong>
            </p>
            <p>
                Assistenza clienti:
                <a href="mailto:<?php echo htmlspecialchars($brandContacts['support_email'] ?? 'info@smashburger.it', ENT_QUOTES, 'UTF-8'); ?>">
                    <?php echo htmlspecialchars($brandContacts['support_email'] ?? 'info@smashburger.it', ENT_QUOTES, 'UTF-8'); ?>
                </a>
            </p>
            <p>
                Info brand:
                <a href="tel:<?php echo htmlspecialchars(preg_replace('/[^0-9+]/', '', (string) ($brandContacts['info_phone'] ?? '')), ENT_QUOTES, 'UTF-8'); ?>">
                    <?php echo htmlspecialchars($brandContacts['info_phone'] ?? '', ENT_QUOTES, 'UTF-8'); ?>
                </a>
                -
                Ordini:
                <a href="tel:<?php echo htmlspecialchars(preg_replace('/[^0-9+]/', '', (string) ($brandContacts['order_phone'] ?? '')), ENT_QUOTES, 'UTF-8'); ?>">
                    <?php echo htmlspecialchars($brandContacts['order_phone'] ?? '', ENT_QUOTES, 'UTF-8'); ?>
                </a>
            </p>
            <p class="branch-switcher-note"><?php echo count($allBranches); ?> sedi attive tra Veneto e Friuli Venezia Giulia.</p>
        </div>
    </div>
</section>

<?php if (empty($allBranches) || empty($selectedBranch)): ?>
    <section aria-label="Sedi non disponibili">
        <div class="contenitore">
            <p>Al momento non ci sono sedi disponibili.</p>
        </div>
    </section>
<?php else: ?>
    <section id="sedi-interattive"
        class="sedi-layout"
        data-selected-slug="<?php echo htmlspecialchars($selectedBranch['slug'], ENT_QUOTES, 'UTF-8'); ?>"
        aria-labelledby="titolo-sedi-interattive">
        <div class="contenitore">
            <h2 id="titolo-sedi-interattive">Seleziona una sede sulla mappa</h2>

            <div class="sedi-grid">
                <nav class="sedi-lista" aria-label="Elenco sedi disponibili">
                    <h3>Sedi disponibili</h3>
                    <ul>
                        <?php foreach ($allBranches as $branch): ?>
                            <li>
                                <a
                                    class="sede-link<?php echo ((int) $selectedBranch['id'] === (int) $branch['id']) ? ' attiva' : ''; ?>"
                                    href="sedi.php?sede=<?php echo rawurlencode($branch['slug']); ?>"
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
                                    <span><?php echo htmlspecialchars($branch['address_line'], ENT_QUOTES, 'UTF-8'); ?></span>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </nav>

                <div class="sede-dettaglio-wrap">
                    <article class="sede-dettaglio-card" aria-labelledby="sede-dettaglio-nome">
                        <h3 id="sede-dettaglio-nome"><?php echo htmlspecialchars($selectedBranch['name'], ENT_QUOTES, 'UTF-8'); ?></h3>
                        <p id="sede-dettaglio-indirizzo">
                            <?php echo htmlspecialchars($selectedBranch['address_line'], ENT_QUOTES, 'UTF-8'); ?>,
                            <?php echo htmlspecialchars($selectedBranch['postal_code'], ENT_QUOTES, 'UTF-8'); ?>
                            <?php echo htmlspecialchars($selectedBranch['city'], ENT_QUOTES, 'UTF-8'); ?>
                            (<?php echo htmlspecialchars($selectedBranch['province'], ENT_QUOTES, 'UTF-8'); ?>)
                        </p>
                        <p>
                            Telefono sede:
                            <a id="sede-dettaglio-phone-link"
                                href="tel:<?php echo htmlspecialchars(preg_replace('/[^0-9+]/', '', (string) $selectedBranch['phone']), ENT_QUOTES, 'UTF-8'); ?>">
                                <span id="sede-dettaglio-phone"><?php echo htmlspecialchars($selectedBranch['phone'], ENT_QUOTES, 'UTF-8'); ?></span>
                            </a>
                        </p>
                        <p>
                            Email sede:
                            <a id="sede-dettaglio-email-link"
                                href="mailto:<?php echo htmlspecialchars($selectedBranch['email'], ENT_QUOTES, 'UTF-8'); ?>">
                                <span id="sede-dettaglio-email"><?php echo htmlspecialchars($selectedBranch['email'], ENT_QUOTES, 'UTF-8'); ?></span>
                            </a>
                        </p>
                        <p id="sede-dettaglio-orari">
                            <strong>Orari:</strong>
                            <span id="sede-dettaglio-orari-valore"><?php echo htmlspecialchars($selectedBranch['hours_compact'] ?? 'Orari non disponibili', ENT_QUOTES, 'UTF-8'); ?></span>
                        </p>
                        <p id="sede-dettaglio-note">
                            <strong>Note ritiro:</strong>
                            <span id="sede-dettaglio-note-valore"><?php echo htmlspecialchars($selectedBranch['pickup_notes'] ?? '', ENT_QUOTES, 'UTF-8'); ?></span>
                        </p>

                        <div class="sede-dettaglio-azioni">
                            <a id="sede-dettaglio-menu-link"
                                class="bottone-primario"
                                href="prodotti.php?sede=<?php echo rawurlencode($selectedBranch['slug']); ?>">
                                Seleziona questa sede e vai al menu
                            </a>
                        </div>
                    </article>

                    <div class="sede-mappa-box">
                        <iframe
                            id="sedi-mappa-frame"
                            title="Mappa sede selezionata"
                            src="<?php echo htmlspecialchars($selectedBranch['map_embed_url'], ENT_QUOTES, 'UTF-8'); ?>">
                        </iframe>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script id="branches-data" type="application/json"><?php echo htmlspecialchars($branchesJson ?: '[]', ENT_NOQUOTES, 'UTF-8'); ?></script>
<?php endif; ?>
