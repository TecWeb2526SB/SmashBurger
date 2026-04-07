<?php
/**
 * prodotti.php: View della pagina prodotti.
 *
 * Variabili attese:
 *   $catalogoCategorie array  Categorie con prodotti
 *   $flash             ?array Messaggio flash opzionale
 *   $allBranches       array  Elenco sedi attive
 *   $selectedBranch    ?array Sede corrente
 *   $branchWarning     ?string
 *   $csrfToken         string Token CSRF
 */
$csrfToken = csrf_token();
$internalAccountBrowsing = function_exists('is_logged_in') && is_logged_in() && function_exists('can_place_customer_orders') && !can_place_customer_orders();
?>

<section id="intestazione-prodotti" aria-labelledby="titolo-prodotti">
    <div class="contenitore">
        <h1 id="titolo-prodotti">I nostri prodotti</h1>
        <p>Tutto quello che trovi nel nostro menu e preparato al momento, con disponibilita legata alla sede scelta.</p>
        </div>
        </section>

<?php if (!empty($flash)): ?>
    <section aria-label="Messaggio sistema">
        <div class="contenitore">
            <div class="alert <?php echo htmlspecialchars($flash['type'] ?? 'info', ENT_QUOTES, 'UTF-8'); ?>">
                <?php echo htmlspecialchars($flash['message'] ?? '', ENT_QUOTES, 'UTF-8'); ?>
            </div>
        </div>
    </section>
<?php endif; ?>

<?php if (!empty($branchWarning)): ?>
    <section aria-label="Avviso sede">
        <div class="contenitore">
            <div class="alert error">
                <?php echo htmlspecialchars($branchWarning, ENT_QUOTES, 'UTF-8'); ?>
            </div>
        </div>
    </section>
<?php endif; ?>

<?php if ($internalAccountBrowsing): ?>
    <section aria-label="Avviso account interno">
        <div class="contenitore">
            <div class="alert info">
                Stai consultando il catalogo con un account interno. Per i profili admin e manager l acquisto diretto e disabilitato.
            </div>
        </div>
    </section>
<?php endif; ?>

<?php if (!empty($catalogoCategorie)): ?>
    <nav id="filtro-categorie" aria-label="Filtra per categoria">
        <div class="contenitore">
            <ul>
                <?php foreach ($catalogoCategorie as $categoria): ?>
                    <li>
                        <a href="#<?php echo htmlspecialchars($categoria['slug'], ENT_QUOTES, 'UTF-8'); ?>">
                            <?php echo htmlspecialchars($categoria['name'], ENT_QUOTES, 'UTF-8'); ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </nav>

    <?php foreach ($catalogoCategorie as $categoria): ?>
        <section class="categoria-sezione" id="<?php echo htmlspecialchars($categoria['slug'], ENT_QUOTES, 'UTF-8'); ?>"
            aria-labelledby="titolo-<?php echo htmlspecialchars($categoria['slug'], ENT_QUOTES, 'UTF-8'); ?>">
            <div class="contenitore">
                <h2 id="titolo-<?php echo htmlspecialchars($categoria['slug'], ENT_QUOTES, 'UTF-8'); ?>">
                    <?php echo htmlspecialchars($categoria['name'], ENT_QUOTES, 'UTF-8'); ?>
                </h2>

                <?php if (!empty($categoria['products'])): ?>
                    <div class="griglia-prodotti">
                        <?php foreach ($categoria['products'] as $prodotto): ?>
                            <article class="scheda-prodotto" aria-labelledby="prod-<?php echo (int) $prodotto['id']; ?>">
                                <?php if (!empty($prodotto['image_path'])): ?>
                                    <img src="<?php echo htmlspecialchars($prodotto['image_path'], ENT_QUOTES, 'UTF-8'); ?>"
                                        alt="Immagine di <?php echo htmlspecialchars($prodotto['name'], ENT_QUOTES, 'UTF-8'); ?>"
                                        style="object-position: <?php echo (int) ($prodotto['image_focus_x'] ?? 50); ?>% <?php echo (int) ($prodotto['image_focus_y'] ?? 50); ?>%;">
                                <?php else: ?>
                                    <div class="scheda-prodotto-placeholder" aria-hidden="true">No image</div>
                                <?php endif; ?>

                                <div class="scheda-prodotto-corpo">
                                    <div class="scheda-prodotto-contenuto">
                                        <h3 id="prod-<?php echo (int) $prodotto['id']; ?>">
                                            <?php echo htmlspecialchars($prodotto['name'], ENT_QUOTES, 'UTF-8'); ?>
                                        </h3>

                                        <p class="scheda-prodotto-descrizione">
                                            <?php echo htmlspecialchars($prodotto['description'], ENT_QUOTES, 'UTF-8'); ?>
                                        </p>

                                        <p class="scheda-prodotto-allergeni">
                                            <strong>Allergeni:</strong>
                                            <?php echo htmlspecialchars(!empty($prodotto['allergens']) ? $prodotto['allergens'] : 'non indicati', ENT_QUOTES, 'UTF-8'); ?>
                                        </p>
                                    </div>

                                    <div class="scheda-prodotto-azioni">
                                        <p class="prezzo"><?php echo money_eur((int) $prodotto['price_cents']); ?></p>

                                        <?php if ((int) $prodotto['is_available'] === 1 && !$internalAccountBrowsing): ?>
                                            <form method="POST" action="carrello.php">
                                                <input type="hidden" name="csrf_token"
                                                    value="<?php echo htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8'); ?>">
                                                <input type="hidden" name="action" value="add_product">
                                                <input type="hidden" name="product_id" value="<?php echo (int) $prodotto['id']; ?>">
                                                <input type="hidden" name="quantity" value="1">
                                                <input type="hidden" name="branch_id"
                                                    value="<?php echo !empty($selectedBranch) ? (int) $selectedBranch['id'] : 0; ?>">
                                                <input type="hidden" name="redirect_to" value="prodotti.php">
                                                <button type="submit" class="bottone-primario">Aggiungi al carrello</button>
                                            </form>
                                        <?php elseif ($internalAccountBrowsing): ?>
                                            <p class="disponibilita-ko">Ordine disabilitato per account interni.</p>
                                        <?php else: ?>
                                            <p class="disponibilita-ko">Temporaneamente non disponibile.</p>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </article>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <p>Al momento non ci sono prodotti in questa categoria.</p>
                <?php endif; ?>
            </div>
        </section>
    <?php endforeach; ?>
<?php else: ?>
    <section aria-label="Catalogo vuoto">
        <div class="contenitore">
            <p>Catalogo non disponibile al momento.</p>
        </div>
    </section>
<?php endif; ?>
