<?php
/**
 * prodotti: View della pagina prodotti.
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
$totaleProdotti = 0;
foreach (($catalogoCategorie ?? []) as $cat) {
    $totaleProdotti += count($cat['products'] ?? []);
}

/**
 * Restituisce una breve descrizione contestuale per categoria, basata sullo slug.
 * Fallback a stringa generica se lo slug non corrisponde a nessuno dei pattern noti.
 */
$descrizioneCategoria = function (string $slug, string $nome): string {
    $slug = strtolower($slug);
    if (str_contains($slug, 'burger') || str_contains($slug, 'smash') || str_contains($slug, 'classic')) {
        return 'Carne 100% italiana smashata sulla piastra rovente, pane brioche e ingredienti scelti.';
    }
    if (str_contains($slug, 'side') || str_contains($slug, 'contor') || str_contains($slug, 'patat')) {
        return 'Da abbinare al tuo burger: croccanti fuori, morbide dentro.';
    }
    if (str_contains($slug, 'drink') || str_contains($slug, 'bevand') || str_contains($slug, 'bibit')) {
        return 'Bibite, birre artigianali e bevande analcoliche selezionate.';
    }
    if (str_contains($slug, 'dessert') || str_contains($slug, 'dolc')) {
        return 'Per chiudere in dolcezza, preparati dalla nostra pasticceria.';
    }
    if (str_contains($slug, 'menu') || str_contains($slug, 'combo')) {
        return 'Combo a prezzo fisso: burger, side e drink in un\'unica scelta.';
    }
    return 'Tutta la selezione ' . $nome . ' del nostro menu.';
};
?>

<section class="prod-hero" aria-labelledby="titolo-prodotti">
    <div class="contenitore">
        <div class="prod-hero-content">
            <p class="prod-hero-eyebrow">Menu completo</p>
            <h1 id="titolo-prodotti" class="prod-hero-titolo">
                I nostri <em>prodotti</em>
            </h1>
            <p class="prod-hero-lead">
                Tutto preparato al momento: smashato a mano, servito in pochi minuti.
                La disponibilit&agrave; varia in base alla sede selezionata.
            </p>

            <?php if (!empty($selectedBranch)): ?>
                <div class="prod-hero-meta">
                    <span class="prod-sede-pill" aria-live="polite">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                             stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                            <circle cx="12" cy="10" r="3"></circle>
                        </svg>
                        Stai vedendo i prodotti disponibili a
                        <strong><?php echo htmlspecialchars($selectedBranch['name'] ?? '', ENT_QUOTES, 'UTF-8'); ?></strong>
                    </span>
                    <a href="sedi" class="prod-sede-cambia">Cambia sede</a>
                </div>
            <?php endif; ?>

            <?php if (!empty($totaleProdotti)): ?>
                <p class="prod-hero-stat">
                    <strong><?php echo (int) $totaleProdotti; ?></strong>
                    prodotti disponibili in
                    <strong><?php echo count($catalogoCategorie); ?></strong>
                    categorie
                </p>
            <?php endif; ?>
        </div>
    </div>
</section>

<?php if (!empty($flash) || !empty($branchWarning) || $internalAccountBrowsing): ?>
    <section class="prod-alerts" aria-label="Avvisi pagina prodotti">
        <div class="contenitore">
            <?php if (!empty($flash)): ?>
                <div class="alert <?php echo htmlspecialchars($flash['type'] ?? 'info', ENT_QUOTES, 'UTF-8'); ?>">
                    <?php echo htmlspecialchars($flash['message'] ?? '', ENT_QUOTES, 'UTF-8'); ?>
                </div>
            <?php endif; ?>

            <?php if (!empty($branchWarning)): ?>
                <div class="alert error">
                    <?php echo htmlspecialchars($branchWarning, ENT_QUOTES, 'UTF-8'); ?>
                </div>
            <?php endif; ?>

            <?php if ($internalAccountBrowsing): ?>
                <div class="alert info">
                    Stai consultando il catalogo con un account interno. Per i profili admin e manager
                    l'ordine diretto non &egrave; disponibile.
                </div>
            <?php endif; ?>
        </div>
    </section>
<?php endif; ?>

<?php if (!empty($catalogoCategorie)): ?>

    <nav class="prod-chips-nav" id="filtro-categorie" aria-label="Filtra per categoria"
         data-prod-chips>
        <div class="contenitore">
            <ul class="prod-chips" role="list">
                <?php foreach ($catalogoCategorie as $i => $categoria): ?>
                    <?php $count = count($categoria['products'] ?? []); ?>
                    <li>
                        <a class="prod-chip"
                           href="#<?php echo htmlspecialchars($categoria['slug'], ENT_QUOTES, 'UTF-8'); ?>"
                           data-prod-chip="<?php echo htmlspecialchars($categoria['slug'], ENT_QUOTES, 'UTF-8'); ?>">
                            <?php echo htmlspecialchars($categoria['name'], ENT_QUOTES, 'UTF-8'); ?>
                            <span class="prod-chip-count" aria-hidden="true"><?php echo (int) $count; ?></span>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </nav>

    <?php foreach ($catalogoCategorie as $i => $categoria): ?>
        <?php
            $catSlug = htmlspecialchars($categoria['slug'], ENT_QUOTES, 'UTF-8');
            $catNome = htmlspecialchars($categoria['name'], ENT_QUOTES, 'UTF-8');
            $catCount = count($categoria['products'] ?? []);
        ?>
        <section class="prod-categoria" id="<?php echo $catSlug; ?>"
                 aria-labelledby="titolo-<?php echo $catSlug; ?>"
                 data-prod-section="<?php echo $catSlug; ?>">
            <div class="contenitore">

                <div class="prod-categoria-header">
                    <span class="prod-categoria-numero" aria-hidden="true">
                        <?php echo str_pad((string) ($i + 1), 2, '0', STR_PAD_LEFT); ?>
                    </span>
                    <div class="prod-categoria-testo">
                        <h2 id="titolo-<?php echo $catSlug; ?>" class="prod-categoria-titolo">
                            <?php echo $catNome; ?>
                            <span class="prod-categoria-count">
                                <?php echo (int) $catCount; ?>
                                <?php echo $catCount === 1 ? 'prodotto' : 'prodotti'; ?>
                            </span>
                        </h2>
                        <p class="prod-categoria-desc">
                            <?php echo htmlspecialchars(
                                $descrizioneCategoria($categoria['slug'] ?? '', $categoria['name'] ?? ''),
                                ENT_QUOTES,
                                'UTF-8'
                            ); ?>
                        </p>
                    </div>
                </div>

                <?php if (!empty($categoria['products'])): ?>
                    <div class="prod-griglia">
                        <?php foreach ($categoria['products'] as $prodotto): ?>
                            <?php
                                $disponibile = (int) ($prodotto['is_available'] ?? 0) === 1;
                                $allergeni = trim((string) ($prodotto['allergens'] ?? ''));
                                $allergeniArr = [];
                                if ($allergeni !== '' && strtolower($allergeni) !== 'non indicati') {
                                    foreach (preg_split('/[,;]\s*/', $allergeni) as $a) {
                                        $a = trim($a);
                                        if ($a !== '') {
                                            $allergeniArr[] = $a;
                                        }
                                    }
                                }
                            ?>
                            <article class="prod-card<?php echo $disponibile ? '' : ' is-esaurito'; ?>"
                                     aria-labelledby="prod-<?php echo (int) $prodotto['id']; ?>">

                                <div class="prod-card-media">
                                    <?php if (!empty($prodotto['image_path'])): ?>
                                        <img src="<?php echo htmlspecialchars($prodotto['image_path'], ENT_QUOTES, 'UTF-8'); ?>"
                                             alt="<?php echo htmlspecialchars($prodotto['name'], ENT_QUOTES, 'UTF-8'); ?>"
                                             loading="lazy"
                                             style="object-position: <?php echo (int) ($prodotto['image_focus_x'] ?? 50); ?>% <?php echo (int) ($prodotto['image_focus_y'] ?? 50); ?>%;">
                                    <?php else: ?>
                                        <div class="prod-card-placeholder" aria-hidden="true">
                                            <svg width="40" height="40" viewBox="0 0 24 24" fill="none"
                                                 stroke="currentColor" stroke-width="1.5"
                                                 stroke-linecap="round" stroke-linejoin="round">
                                                <rect x="3" y="3" width="18" height="18" rx="2"></rect>
                                                <circle cx="9" cy="9" r="2"></circle>
                                                <path d="m21 15-3.086-3.086a2 2 0 0 0-2.828 0L6 21"></path>
                                            </svg>
                                        </div>
                                    <?php endif; ?>

                                    <?php if (!$disponibile): ?>
                                        <div class="prod-card-overlay" aria-hidden="true">
                                            <span class="prod-card-overlay-tag">Esaurito</span>
                                        </div>
                                    <?php endif; ?>
                                </div>

                                <div class="prod-card-corpo">
                                    <h3 id="prod-<?php echo (int) $prodotto['id']; ?>" class="prod-card-titolo">
                                        <?php echo htmlspecialchars($prodotto['name'], ENT_QUOTES, 'UTF-8'); ?>
                                    </h3>

                                    <p class="prod-card-desc">
                                        <?php echo htmlspecialchars($prodotto['description'], ENT_QUOTES, 'UTF-8'); ?>
                                    </p>

                                    <?php if (!empty($allergeniArr)): ?>
                                        <ul class="prod-card-allergeni" aria-label="Allergeni">
                                            <?php foreach ($allergeniArr as $alg): ?>
                                                <li><?php echo htmlspecialchars($alg, ENT_QUOTES, 'UTF-8'); ?></li>
                                            <?php endforeach; ?>
                                        </ul>
                                    <?php else: ?>
                                        <p class="prod-card-allergeni-vuoti">Allergeni non indicati</p>
                                    <?php endif; ?>

                                    <div class="prod-card-footer">
                                        <p class="prod-card-prezzo">
                                            <?php echo money_eur((int) $prodotto['price_cents']); ?>
                                        </p>

                                        <?php if ($disponibile && !$internalAccountBrowsing): ?>
                                            <form method="POST" action="<?php echo e(app_route('carrello')); ?>" class="prod-card-form"
                                                  data-prod-form>
                                                <input type="hidden" name="csrf_token"
                                                    value="<?php echo htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8'); ?>">
                                                <input type="hidden" name="action" value="add_product">
                                                <input type="hidden" name="product_id"
                                                    value="<?php echo (int) $prodotto['id']; ?>">
                                                <input type="hidden" name="branch_id"
                                                    value="<?php echo !empty($selectedBranch) ? (int) $selectedBranch['id'] : 0; ?>">
                                                <input type="hidden" name="redirect_to" value="prodotti">

                                                <div class="prod-stepper" data-prod-stepper>
                                                    <button type="button" class="prod-stepper-btn"
                                                            data-prod-step="-1"
                                                            aria-label="Diminuisci quantit&agrave;">&minus;</button>
                                                    <input type="number" name="quantity" value="1" min="1" max="20"
                                                           class="prod-stepper-input"
                                                           aria-label="Quantit&agrave;"
                                                           data-prod-qty>
                                                    <button type="button" class="prod-stepper-btn"
                                                            data-prod-step="1"
                                                            aria-label="Aumenta quantit&agrave;">+</button>
                                                </div>

                                                <button type="submit" class="bottone-primario prod-card-cta">
                                                    Aggiungi
                                                </button>
                                            </form>
                                        <?php elseif ($internalAccountBrowsing): ?>
                                            <p class="prod-card-stato">Ordine non disponibile per account interni</p>
                                        <?php else: ?>
                                            <p class="prod-card-stato">Temporaneamente non disponibile</p>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </article>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="prod-empty">
                        <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                             stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <circle cx="12" cy="12" r="10"></circle>
                            <line x1="8" y1="12" x2="16" y2="12"></line>
                        </svg>
                        <p><strong>Categoria temporaneamente vuota.</strong></p>
                        <p>Stiamo ricaricando la nostra cucina. Torna a trovarci tra poco.</p>
                    </div>
                <?php endif; ?>
            </div>
        </section>
    <?php endforeach; ?>

<?php else: ?>
    <section class="prod-categoria" aria-label="Catalogo vuoto">
        <div class="contenitore">
            <div class="prod-empty">
                <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                     stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <circle cx="12" cy="12" r="10"></circle>
                    <line x1="12" y1="8" x2="12" y2="12"></line>
                    <line x1="12" y1="16" x2="12.01" y2="16"></line>
                </svg>
                <p><strong>Catalogo non disponibile al momento.</strong></p>
                <p>Riprova tra qualche minuto o contatta l'assistenza se il problema persiste.</p>
            </div>
        </div>
    </section>
<?php endif; ?>
