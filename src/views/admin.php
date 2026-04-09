<?php
/**
 * admin.php: View pannello controllo manager/admin.
 *
 * Variabili attese:
 *   $utente
 *   $isGeneralAdmin
 *   $isBranchManager
 *   $canModifyBranchOperations
 *   $canManageGlobalCatalog
 *   $canManageBranchManagers
 *   $selectedBranch
 *   $allBranches
 *   $selectedBranchSlug
 *   $currentSection
 *   $sectionMeta
 *   $sectionLinks
 *   $sectionUrls
 *   $flash
 *   $backgroundMessages
 *   $csrfToken
 *   $inventoryItems
 *   $kpis
 *   $topProducts
 *   $salesTrend
 *   $categoryMix
 *   $templates
 *   $supplyOrders
 *   $policies
 *   $recentCustomerOrders
 *   $branchComparison
 *   $globalCatalog
 *   $categories
 *   $branchManagers
 */

$csrfToken = $csrfToken ?? '';
$inventoryItems = $inventoryItems ?? [];
$kpis = $kpis ?? [];
$topProducts = $topProducts ?? [];
$salesTrend = $salesTrend ?? [];
$categoryMix = $categoryMix ?? [];
$templates = $templates ?? [];
$supplyOrders = $supplyOrders ?? [];
$policies = $policies ?? [];
$recentCustomerOrders = $recentCustomerOrders ?? [];
$branchComparison = $branchComparison ?? [];
$globalCatalog = $globalCatalog ?? [];
$categories = $categories ?? [];
$branchManagers = $branchManagers ?? [];
$backgroundMessages = $backgroundMessages ?? [];
$catalogSelectedCategoryId = $catalogSelectedCategoryId ?? 0;
$catalogSelectedCategoryLabel = $catalogSelectedCategoryLabel ?? 'Tutte le categorie';
$catalogCategoryLinks = $catalogCategoryLinks ?? [];
$filteredGlobalCatalog = $filteredGlobalCatalog ?? $globalCatalog;
$filteredInventoryItems = $filteredInventoryItems ?? $inventoryItems;
$catalogMetrics = $catalogMetrics ?? [
    'global_total' => count($filteredGlobalCatalog),
    'branch_total' => count($filteredInventoryItems),
    'branch_available' => 0,
];
$catalogListedCount = 0;
$catalogUnavailableCount = 0;
foreach ($filteredInventoryItems as $filteredInventoryItem) {
    if ((int) ($filteredInventoryItem['is_listed'] ?? 0) === 1) {
        $catalogListedCount++;
    }

    if ((int) ($filteredInventoryItem['is_listed'] ?? 0) === 1 && (int) ($filteredInventoryItem['is_available_for_sale'] ?? 0) !== 1) {
        $catalogUnavailableCount++;
    }
}
$catalogHiddenCount = max(0, count($filteredInventoryItems) - $catalogListedCount);

$maxTrendRevenue = 0;
foreach ($salesTrend as $trendItem) {
    $maxTrendRevenue = max($maxTrendRevenue, (int) $trendItem['revenue_cents']);
}

$maxTopProductQty = 0;
foreach ($topProducts as $topProduct) {
    $maxTopProductQty = max($maxTopProductQty, (int) $topProduct['quantity_sold']);
}

$maxCategoryRevenue = 0;
foreach ($categoryMix as $categoryItem) {
    $maxCategoryRevenue = max($maxCategoryRevenue, (int) $categoryItem['revenue_cents']);
}

$inventoryProductCount = count($inventoryItems);
$inventoryAvailableCount = 0;
$inventoryBelowThresholdCount = 0;
$inventoryPendingUnits = 0;
$inventoryPendingProductsCount = 0;
foreach ($inventoryItems as $inventoryItemSummary) {
    $pendingSupplyQty = max(0, (int) ($inventoryItemSummary['pending_supply_qty'] ?? 0));
    $inventoryPendingUnits += $pendingSupplyQty;

    if ($pendingSupplyQty > 0) {
        $inventoryPendingProductsCount++;
    }

    if (!empty($inventoryItemSummary['is_available_for_sale'])) {
        $inventoryAvailableCount++;
    }

    if (!empty($inventoryItemSummary['is_below_threshold'])) {
        $inventoryBelowThresholdCount++;
    }
}

$activeTemplatesCount = 0;
foreach ($templates as $templateSummary) {
    if ((int) ($templateSummary['is_active'] ?? 0) === 1) {
        $activeTemplatesCount++;
    }
}

$openSupplyOrdersCount = 0;
$scheduledSupplyOrdersCount = 0;
$receivedSupplyOrdersCount = 0;
foreach ($supplyOrders as $supplyOrderSummary) {
    $supplyOrderStatus = (string) ($supplyOrderSummary['status'] ?? '');

    if (in_array($supplyOrderStatus, ['draft', 'scheduled', 'ordered'], true)) {
        $openSupplyOrdersCount++;
    }

    if ($supplyOrderStatus === 'scheduled') {
        $scheduledSupplyOrdersCount++;
    }

    if ($supplyOrderStatus === 'received') {
        $receivedSupplyOrdersCount++;
    }
}

$activePoliciesCount = 0;
foreach ($policies as $policySummary) {
    if ((int) ($policySummary['is_active'] ?? 0) === 1) {
        $activePoliciesCount++;
    }
}

$standardSupplyBuilderUrl = admin_supply_builder_url('standard', $selectedBranchSlug, $isGeneralAdmin);
$extraSupplyBuilderUrl = admin_supply_builder_url('extra', $selectedBranchSlug, $isGeneralAdmin);
$automaticSupplyBuilderUrl = admin_supply_builder_url('automatic', $selectedBranchSlug, $isGeneralAdmin);
$inventoryAdjustmentUrl = $inventoryAdjustmentUrl ?? admin_inventory_adjustment_url($selectedBranchSlug, $isGeneralAdmin);
$inventoryAdjustmentModes = function_exists('admin_inventory_adjustment_modes')
    ? admin_inventory_adjustment_modes()
    : [];

$frequencyLabels = supply_frequency_options();
$teamMode = $teamMode ?? 'list';
$editingManager = isset($editingManager) && is_array($editingManager) ? $editingManager : null;
?>

<section class="account-page admin-page" aria-labelledby="titolo-admin">
    <div class="contenitore">
        <div class="account-page-head admin-page-head">
            <h1 id="titolo-admin"><?php echo htmlspecialchars((string) ($sectionMeta['title'] ?? 'Controllo'), ENT_QUOTES, 'UTF-8'); ?></h1>
        </div>

        <?php if (!empty($flash)): ?>
            <div class="alert <?php echo htmlspecialchars($flash['type'] ?? 'info', ENT_QUOTES, 'UTF-8'); ?>">
                <?php echo htmlspecialchars($flash['message'] ?? '', ENT_QUOTES, 'UTF-8'); ?>
            </div>
        <?php endif; ?>

        <?php foreach ($backgroundMessages as $message): ?>
            <div class="alert success">
                <?php echo htmlspecialchars((string) $message, ENT_QUOTES, 'UTF-8'); ?>
            </div>
        <?php endforeach; ?>

        <nav class="admin-section-nav" aria-label="Sezioni pannello controllo">
            <ul>
                <?php foreach ($sectionLinks as $sectionLink): ?>
                    <li>
                        <a class="<?php echo !empty($sectionLink['is_active']) ? 'is-active' : ''; ?>" href="<?php echo htmlspecialchars((string) $sectionLink['href'], ENT_QUOTES, 'UTF-8'); ?>">
                            <?php echo htmlspecialchars((string) $sectionLink['label'], ENT_QUOTES, 'UTF-8'); ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </nav>

        <?php if ($currentSection === 'dashboard'): ?>
        <section id="sezione-dashboard" class="admin-section" aria-labelledby="titolo-dashboard">
            <h2 id="titolo-dashboard" class="sr-only"><?php echo $isGeneralAdmin ? 'Dashboard controllo' : 'Dashboard filiale'; ?></h2>

            <div class="admin-kpi-grid">
                <article class="account-stat">
                    <span>Ricavi ordini</span>
                    <strong><?php echo money_eur((int) $kpis['revenue_cents']); ?></strong>
                </article>
                <article class="account-stat">
                    <span>Margine lordo</span>
                    <strong><?php echo money_eur((int) $kpis['gross_margin_cents']); ?></strong>
                </article>
                <article class="account-stat">
                    <span>Spesa forniture</span>
                    <strong><?php echo money_eur((int) $kpis['supply_spend_cents']); ?></strong>
                </article>
                <article class="account-stat">
                    <span>Valore stock</span>
                    <strong><?php echo money_eur((int) $kpis['inventory_value_cents']); ?></strong>
                </article>
                <article class="account-stat">
                    <span>Forniture aperte</span>
                    <strong><?php echo (int) $kpis['pending_supplies']; ?></strong>
                </article>
                <article class="account-stat">
                    <span>Prodotti sotto soglia</span>
                    <strong><?php echo (int) $kpis['stock_alerts']; ?></strong>
                </article>
            </div>

            <?php if ($isGeneralAdmin && !empty($branchComparison)): ?>
                <div class="checkout-card admin-panel-card">
                    <div class="account-panel-head">
                        <span class="account-panel-kicker">Confronto sedi</span>
                        <h3>Snapshot multi-filiale</h3>
                        <p class="checkout-muted">Confronta rapidamente ricavi, margine e intensità operativa fra le sedi attive.</p>
                    </div>

                    <div class="admin-branch-comparison">
                        <?php foreach ($branchComparison as $comparison): ?>
                            <?php
                            $comparisonBranch = $comparison['branch'];
                            $isComparisonSelected = (int) $comparisonBranch['id'] === (int) $selectedBranch['id'];
                            $comparisonHref = admin_panel_section_url(
                                'dashboard',
                                (string) ($comparisonBranch['slug'] ?? ''),
                                $isGeneralAdmin,
                                $canManageBranchManagers
                            );
                            ?>
                            <article class="admin-branch-card<?php echo $isComparisonSelected ? ' is-selected' : ''; ?>">
                                <a
                                    class="admin-branch-card-link"
                                    href="<?php echo htmlspecialchars($comparisonHref, ENT_QUOTES, 'UTF-8'); ?>"
                                    <?php echo $isComparisonSelected ? 'aria-current="true"' : ''; ?>>
                                    <div class="admin-branch-card-head">
                                        <h4><?php echo htmlspecialchars((string) $comparisonBranch['name'], ENT_QUOTES, 'UTF-8'); ?></h4>
                                        <span class="admin-branch-card-cta"><?php echo $isComparisonSelected ? 'In focus' : 'Apri dashboard'; ?></span>
                                    </div>
                                    <dl class="admin-mini-stats">
                                        <div>
                                            <dt>Ricavi</dt>
                                            <dd><?php echo money_eur((int) $comparison['kpis']['revenue_cents']); ?></dd>
                                        </div>
                                        <div>
                                            <dt>Margine</dt>
                                            <dd><?php echo money_eur((int) $comparison['kpis']['gross_margin_cents']); ?></dd>
                                        </div>
                                        <div>
                                            <dt>Alert stock</dt>
                                            <dd><?php echo (int) $comparison['kpis']['stock_alerts']; ?></dd>
                                        </div>
                                    </dl>
                                </a>
                            </article>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>

            <div class="admin-analytics-grid">
                <article class="checkout-card admin-panel-card">
                    <div class="account-panel-head">
                        <span class="account-panel-kicker">Vendite</span>
                        <h3>Andamento ultimi giorni</h3>
                        <p class="checkout-muted">Andamento ricavi giorno per giorno, utile per individuare picchi e cali.</p>
                    </div>

                    <?php if (empty($salesTrend)): ?>
                        <p class="checkout-muted">Nessun dato vendite disponibile.</p>
                    <?php else: ?>
                        <ul class="admin-bar-list admin-bar-list--trend-grid" aria-label="Ricavi giornalieri">
                            <?php foreach ($salesTrend as $trendItem): ?>
                                <?php
                                $barWidth = $maxTrendRevenue > 0
                                    ? round(((int) $trendItem['revenue_cents'] / $maxTrendRevenue) * 100, 2)
                                    : 0;
                                ?>
                                <li>
                                    <div class="admin-bar-label">
                                        <span class="admin-trend-day">Data</span>
                                        <strong><?php echo htmlspecialchars((string) $trendItem['label'], ENT_QUOTES, 'UTF-8'); ?></strong>
                                    </div>
                                    <strong class="admin-bar-value"><?php echo money_eur((int) $trendItem['revenue_cents']); ?></strong>
                                    <div class="admin-bar-track" aria-hidden="true">
                                        <span class="admin-bar-fill" style="width: <?php echo $barWidth; ?>%;"></span>
                                    </div>
                                    <span class="admin-trend-orders"><?php echo (int) $trendItem['orders_count']; ?> ordini</span>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </article>

                <article class="checkout-card admin-panel-card">
                    <div class="account-panel-head">
                        <span class="account-panel-kicker">Top prodotti</span>
                        <h3>Più venduti e più redditizi</h3>
                        <p class="checkout-muted">Quantità vendute, ricavi e margine lordo per le referenze più forti della sede.</p>
                    </div>

                    <?php if (empty($topProducts)): ?>
                        <p class="checkout-muted">Appena arriveranno ordini pagati, qui troverai i prodotti leader della sede.</p>
                    <?php else: ?>
                        <ul class="admin-bar-list" aria-label="Top prodotti per quantità venduta">
                            <?php foreach ($topProducts as $topProduct): ?>
                                <?php
                                $barWidth = $maxTopProductQty > 0
                                    ? round(((int) $topProduct['quantity_sold'] / $maxTopProductQty) * 100, 2)
                                    : 0;
                                ?>
                                <li>
                                    <div class="admin-bar-label admin-bar-label--stack">
                                        <strong><?php echo htmlspecialchars((string) $topProduct['product_name'], ENT_QUOTES, 'UTF-8'); ?></strong>
                                        <span><?php echo money_eur((int) $topProduct['gross_margin_cents']); ?> margine</span>
                                    </div>
                                    <div class="admin-bar-track" aria-hidden="true">
                                        <span class="admin-bar-fill" style="width: <?php echo $barWidth; ?>%;"></span>
                                    </div>
                                    <strong class="admin-bar-value"><?php echo (int) $topProduct['quantity_sold']; ?> pz</strong>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </article>

                <article class="checkout-card admin-panel-card">
                    <div class="account-panel-head">
                        <span class="account-panel-kicker">Categorie</span>
                        <h3>Mix di vendita</h3>
                        <p class="checkout-muted">Incidenza delle categorie sul fatturato complessivo della filiale.</p>
                    </div>

                    <?php if (empty($categoryMix)): ?>
                        <p class="checkout-muted">Il mix categorie sarà disponibile dopo i primi ordini completati.</p>
                    <?php else: ?>
                        <ul class="admin-bar-list" aria-label="Ripartizione ricavi per categoria">
                            <?php foreach ($categoryMix as $categoryItem): ?>
                                <?php
                                $barWidth = $maxCategoryRevenue > 0
                                    ? round(((int) $categoryItem['revenue_cents'] / $maxCategoryRevenue) * 100, 2)
                                    : 0;
                                ?>
                                <li>
                                    <div class="admin-bar-label">
                                        <strong><?php echo htmlspecialchars((string) $categoryItem['category_name'], ENT_QUOTES, 'UTF-8'); ?></strong>
                                        <span><?php echo (float) $categoryItem['revenue_share']; ?>%</span>
                                    </div>
                                    <div class="admin-bar-track" aria-hidden="true">
                                        <span class="admin-bar-fill" style="width: <?php echo $barWidth; ?>%;"></span>
                                    </div>
                                    <strong class="admin-bar-value"><?php echo money_eur((int) $categoryItem['revenue_cents']); ?></strong>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </article>
            </div>
        </section>
        <?php endif; ?>

        <?php if ($currentSection === 'catalogo'): ?>
        <section id="sezione-catalogo" class="admin-section" aria-labelledby="titolo-catalogo-admin">
            <h2 id="titolo-catalogo-admin" class="sr-only">Catalogo globale e catalogo di filiale</h2>

            <article class="checkout-card admin-panel-card admin-catalog-toolbar">
                <div class="admin-catalog-toolbar-head">
                    <div>
                        <span class="account-panel-kicker">Filtro categoria</span>
                        <h3><?php echo htmlspecialchars((string) $catalogSelectedCategoryLabel, ENT_QUOTES, 'UTF-8'); ?></h3>
                        <p class="checkout-muted">Riduci il rumore visivo e confronta globale e sede sulla stessa categoria senza perdere dettaglio operativo.</p>
                    </div>
                    <div class="admin-catalog-summary" aria-label="Riepilogo catalogo filtrato">
                        <?php if ($canManageGlobalCatalog): ?>
                            <article>
                                <span>Prodotti globali</span>
                                <strong><?php echo (int) ($catalogMetrics['global_total'] ?? 0); ?></strong>
                            </article>
                        <?php endif; ?>
                        <article>
                            <span>Prodotti sede</span>
                            <strong><?php echo (int) ($catalogMetrics['branch_total'] ?? 0); ?></strong>
                        </article>
                        <article>
                            <span>Disponibili ora</span>
                            <strong><?php echo (int) ($catalogMetrics['branch_available'] ?? 0); ?></strong>
                        </article>
                    </div>
                </div>

                <?php if (!empty($catalogCategoryLinks)): ?>
                    <nav class="admin-filter-nav" aria-label="Filtra catalogo per categoria">
                        <ul class="admin-filter-chips">
                            <?php foreach ($catalogCategoryLinks as $categoryLink): ?>
                                <li>
                                    <a
                                        class="<?php echo !empty($categoryLink['is_active']) ? 'is-active' : ''; ?>"
                                        href="<?php echo htmlspecialchars((string) $categoryLink['href'], ENT_QUOTES, 'UTF-8'); ?>">
                                        <span><?php echo htmlspecialchars((string) $categoryLink['label'], ENT_QUOTES, 'UTF-8'); ?></span>
                                        <strong><?php echo (int) ($categoryLink['count'] ?? 0); ?></strong>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </nav>
                <?php endif; ?>
            </article>

            <?php if ($canModifyBranchOperations): ?>
                <article class="checkout-card admin-panel-card admin-catalog-price-panel">
                    <div class="admin-catalog-price-layout">
                        <div class="admin-catalog-price-main">
                            <div class="account-panel-head">
                                <span class="account-panel-kicker">Prezzo cliente</span>
                                <h3>Aggiorna il prezzo finale della filiale</h3>
                                <p class="checkout-muted">Imposta il prezzo locale mostrato al cliente per la referenza selezionata.</p>
                            </div>

                            <form class="checkout-form admin-inventory-price-form" method="POST" action="<?php echo htmlspecialchars((string) ($sectionUrls['catalogo'] ?? 'admin_catalogo.php'), ENT_QUOTES, 'UTF-8'); ?>" data-valida novalidate>
                                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8'); ?>">
                                <input type="hidden" name="action" value="update_branch_pricing">

                                <div class="admin-catalog-price-form-grid">
                                    <div class="campo-gruppo">
                                        <label for="catalog-price-product-id">Prodotto</label>
                                        <select id="catalog-price-product-id" name="product_id" required aria-required="true">
                                            <option value="">Seleziona un prodotto</option>
                                            <?php foreach ($inventoryItems as $inventoryItem): ?>
                                                <option value="<?php echo (int) $inventoryItem['product_id']; ?>">
                                                    <?php echo htmlspecialchars((string) $inventoryItem['product_name'], ENT_QUOTES, 'UTF-8'); ?>
                                                    (attuale <?php echo money_eur((int) $inventoryItem['sale_price_cents']); ?>)
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>

                                    <div class="campo-gruppo">
                                        <label for="catalog-sale-price">Prezzo filiale (EUR)</label>
                                        <input type="text" id="catalog-sale-price" name="sale_price" inputmode="decimal" placeholder="Es. 8,90" required aria-required="true">
                                    </div>

                                    <div class="admin-catalog-price-actions">
                                        <button class="bottone-secondario" type="submit">Salva prezzo filiale</button>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <article class="admin-catalog-price-side" aria-label="Suggerimento prezzo filiale">
                            <span class="account-panel-kicker">Suggerimento</span>
                            <h3>Usa il prezzo finale visto dal cliente</h3>
                            <p class="checkout-muted">Se inserisci lo stesso importo del catalogo base, la sede torna al prezzo globale senza mantenere override locali.</p>
                        </article>
                    </div>
                </article>
            <?php endif; ?>

            <div class="admin-form-grid admin-form-grid--double">
                <?php if ($canManageGlobalCatalog): ?>
                    <article class="checkout-card admin-panel-card">
                        <div class="account-panel-head account-panel-head--split">
                            <div>
                                <span class="account-panel-kicker">Catalogo globale</span>
                                <h3>Prodotti condivisi</h3>
                                <p class="checkout-muted">Ogni nuovo prodotto creato qui diventa disponibile per tutte le filiali, che poi possono decidere se esporlo localmente.</p>
                            </div>
                            <a class="bottone-primario" href="admin_catalogo_prodotto.php">Nuovo prodotto</a>
                        </div>

                        <?php if (empty($filteredGlobalCatalog)): ?>
                            <p class="checkout-muted">Nessun prodotto globale corrisponde al filtro selezionato.</p>
                        <?php else: ?>
                            <div class="admin-stack-list admin-stack-list--catalog">
                                <?php foreach ($filteredGlobalCatalog as $product): ?>
                                    <article class="admin-detail-card admin-catalog-card">
                                        <div class="admin-catalog-card-head">
                                            <div>
                                                <p class="ordine-card-eyebrow"><?php echo htmlspecialchars((string) $product['category_name'], ENT_QUOTES, 'UTF-8'); ?></p>
                                                <h4><?php echo htmlspecialchars((string) $product['name'], ENT_QUOTES, 'UTF-8'); ?></h4>
                                            </div>
                                            <div class="admin-catalog-card-price">
                                                <strong class="ordine-card-total"><?php echo money_eur((int) $product['price_cents']); ?></strong>
                                                <span class="admin-status-pill <?php echo (int) ($product['is_available'] ?? 0) === 1 ? 'is-success' : 'is-muted'; ?>">
                                                    <?php echo (int) ($product['is_available'] ?? 0) === 1 ? 'Attivo' : 'Sospeso'; ?>
                                                </span>
                                            </div>
                                        </div>

                                        <p class="checkout-muted admin-catalog-description"><?php echo htmlspecialchars((string) $product['description'], ENT_QUOTES, 'UTF-8'); ?></p>

                                        <ul class="admin-tag-list admin-tag-list--compact">
                                            <li>Slug: <?php echo htmlspecialchars((string) $product['slug'], ENT_QUOTES, 'UTF-8'); ?></li>
                                            <li>Filiali attive: <?php echo (int) $product['listed_branches_count']; ?></li>
                                            <li>Filiali configurate: <?php echo (int) $product['configured_branches_count']; ?></li>
                                            <li>Allergeni: <?php echo htmlspecialchars((string) ($product['allergens'] !== '' ? $product['allergens'] : 'Nessuno dichiarato'), ENT_QUOTES, 'UTF-8'); ?></li>
                                        </ul>

                                        <div class="admin-inline-actions admin-inline-actions--split">
                                            <a
                                                class="bottone-secondario admin-icon-button"
                                                href="admin_catalogo_prodotto.php?id=<?php echo (int) $product['id']; ?>"
                                                aria-label="Modifica prodotto">
                                                <svg viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                                                    <path d="M4 20h4l10-10a2.1 2.1 0 0 0-4-4L4 16v4" />
                                                    <path d="m13.5 6.5 4 4" />
                                                </svg>
                                                <span class="sr-only">Modifica prodotto</span>
                                            </a>
                                            <form method="POST" action="<?php echo htmlspecialchars((string) ($sectionUrls['catalogo'] ?? 'admin_catalogo.php'), ENT_QUOTES, 'UTF-8'); ?>" class="admin-inline-form">
                                                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8'); ?>">
                                                <input type="hidden" name="action" value="delete_product">
                                                <input type="hidden" name="product_id" value="<?php echo (int) $product['id']; ?>">
                                                <button class="bottone-secondario admin-icon-button" type="submit" aria-label="Elimina prodotto">
                                                    <svg viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                                                        <path d="M9 3h6m-9 4h12m-1 0-.7 11.2a2 2 0 0 1-2 1.8H9.7a2 2 0 0 1-2-1.8L7 7m3 4v5m4-5v5" />
                                                    </svg>
                                                    <span class="sr-only">Elimina prodotto</span>
                                                </button>
                                            </form>
                                        </div>
                                    </article>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </article>
                <?php endif; ?>

                <article class="checkout-card admin-panel-card">
                    <div class="account-panel-head">
                        <span class="account-panel-kicker">Catalogo sede</span>
                        <h3>Presenza prodotto in <?php echo htmlspecialchars((string) $selectedBranch['city'], ENT_QUOTES, 'UTF-8'); ?></h3>
                        <p class="checkout-muted">Qui gestisci solo come il prodotto si presenta al cliente nella filiale: presenza nel menu e possibilità di ordinazione.</p>
                    </div>

                    <div class="admin-catalog-state-grid" aria-label="Riepilogo catalogo locale">
                        <article>
                            <span>Visibili nel menu</span>
                            <strong><?php echo (int) $catalogListedCount; ?></strong>
                        </article>
                        <article>
                            <span>Nascosti</span>
                            <strong><?php echo (int) $catalogHiddenCount; ?></strong>
                        </article>
                        <article>
                            <span>Visibili ma non ordinabili</span>
                            <strong><?php echo (int) $catalogUnavailableCount; ?></strong>
                        </article>
                    </div>

                    <?php if (empty($filteredInventoryItems)): ?>
                        <p class="checkout-muted">Nessun prodotto di sede corrisponde al filtro selezionato.</p>
                    <?php else: ?>
                        <div class="admin-stack-list admin-stack-list--catalog">
                            <?php foreach ($filteredInventoryItems as $inventoryItem): ?>
                                <article class="admin-detail-card admin-catalog-card admin-catalog-card--branch">
                                    <div class="admin-catalog-card-head">
                                        <div>
                                            <p class="ordine-card-eyebrow"><?php echo htmlspecialchars((string) $inventoryItem['category_name'], ENT_QUOTES, 'UTF-8'); ?></p>
                                            <h4><?php echo htmlspecialchars((string) $inventoryItem['product_name'], ENT_QUOTES, 'UTF-8'); ?></h4>
                                            <p class="checkout-muted admin-catalog-description">
                                                <?php echo htmlspecialchars((string) (($inventoryItem['description'] ?? '') !== '' ? $inventoryItem['description'] : 'Scheda cliente senza descrizione estesa.'), ENT_QUOTES, 'UTF-8'); ?>
                                            </p>
                                        </div>
                                    </div>

                                    <ul class="admin-tag-list admin-tag-list--compact admin-tag-list--catalog-meta">
                                        <li>Allergeni: <?php echo htmlspecialchars((string) ($inventoryItem['allergens'] !== '' ? $inventoryItem['allergens'] : 'Nessuno dichiarato'), ENT_QUOTES, 'UTF-8'); ?></li>
                                    </ul>

                                    <?php if ($canModifyBranchOperations): ?>
                                        <form method="POST" action="<?php echo htmlspecialchars((string) ($sectionUrls['catalogo'] ?? 'admin_catalogo.php'), ENT_QUOTES, 'UTF-8'); ?>" class="checkout-form admin-catalog-form">
                                            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8'); ?>">
                                            <input type="hidden" name="action" value="branch_catalog_state">
                                            <input type="hidden" name="product_id" value="<?php echo (int) $inventoryItem['product_id']; ?>">

                                            <fieldset class="checkout-fieldset admin-catalog-fieldset admin-catalog-fieldset--single">
                                                <legend>Stato locale</legend>
                                                <label class="admin-catalog-toggle">
                                                    <input type="checkbox" name="is_listed" value="1" <?php echo (int) $inventoryItem['is_listed'] === 1 ? 'checked' : ''; ?>>
                                                    <span class="admin-catalog-toggle-copy">
                                                        <strong>Visibile nel menu</strong>
                                                        <span>Mostra il prodotto ai clienti della filiale.</span>
                                                    </span>
                                                </label>
                                                <label class="admin-catalog-toggle">
                                                    <input type="checkbox" name="is_available" value="1" <?php echo (int) $inventoryItem['is_listed'] === 1 && (int) $inventoryItem['branch_availability_flag'] === 1 ? 'checked' : ''; ?>>
                                                    <span class="admin-catalog-toggle-copy">
                                                        <strong>Ordinabile</strong>
                                                        <span>Permetti al cliente di completare l'ordine.</span>
                                                    </span>
                                                </label>
                                            </fieldset>

                                            <div class="checkout-navigation checkout-navigation--solo-azione">
                                                <button class="bottone-secondario" type="submit">Aggiorna stato</button>
                                            </div>
                                        </form>
                                    <?php endif; ?>
                                </article>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </article>
            </div>
        </section>
        <?php endif; ?>

        <?php if ($currentSection === 'inventario'): ?>
        <section id="sezione-inventario" class="admin-section" aria-labelledby="titolo-inventario">
            <h2 id="titolo-inventario" class="sr-only">Inventario per unità di prodotto</h2>

            <article class="checkout-card admin-section-hero">
                <div class="admin-section-hero-copy">
                    <span class="account-panel-kicker">Magazzino</span>
                    <h3>Stock, prezzo locale e copertura operativa in un solo quadro</h3>
                    <p class="checkout-muted">Inventario raccoglie i dati di sede che non devono stare in Catalogo: quantità disponibili, merce in arrivo, prezzo locale, valore stock e rettifiche guidate.</p>
                </div>
                <div class="admin-section-hero-stats" aria-label="Indicatori inventario">
                    <article class="admin-section-stat">
                        <span>Referenze monitorate</span>
                        <strong><?php echo (int) $inventoryProductCount; ?></strong>
                    </article>
                    <article class="admin-section-stat">
                        <span>Acquistabili ora</span>
                        <strong><?php echo (int) $inventoryAvailableCount; ?></strong>
                    </article>
                    <article class="admin-section-stat">
                        <span>Unità in arrivo</span>
                        <strong><?php echo (int) $inventoryPendingUnits; ?></strong>
                    </article>
                    <article class="admin-section-stat">
                        <span>Alert soglia</span>
                        <strong><?php echo (int) $inventoryBelowThresholdCount; ?></strong>
                    </article>
                </div>
            </article>

            <div class="checkout-shell admin-action-shell">
                <div class="admin-action-stack">
                    <?php if ($canModifyBranchOperations): ?>
                        <article class="checkout-card admin-panel-card">
                            <div class="account-panel-head">
                                <span class="account-panel-kicker">Rettifiche</span>
                                <h3 id="titolo-rettifica-inventario">Apri il flusso giusto</h3>
                                <p class="checkout-muted">La rettifica non vive più dentro un form generico: scegli il tipo di intervento e continua in una pagina dedicata con breadcrumb, ritorno rapido e blocchi orizzontali più leggibili.</p>
                            </div>

                            <div class="admin-adjustment-launch-grid">
                                <?php foreach ($inventoryAdjustmentModes as $modeKey => $modeItem): ?>
                                    <article class="admin-adjustment-launch-card">
                                        <div class="admin-adjustment-launch-copy">
                                            <span class="ordine-card-eyebrow"><?php echo htmlspecialchars((string) ($modeItem['label'] ?? 'Modalita'), ENT_QUOTES, 'UTF-8'); ?></span>
                                            <h4><?php echo htmlspecialchars((string) ($modeItem['title'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></h4>
                                            <p class="checkout-muted"><?php echo htmlspecialchars((string) ($modeItem['description'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></p>
                                        </div>
                                        <div class="admin-adjustment-launch-actions">
                                            <a class="bottone-primario" href="<?php echo htmlspecialchars(admin_inventory_adjustment_url($selectedBranchSlug, $isGeneralAdmin, (string) $modeKey), ENT_QUOTES, 'UTF-8'); ?>">
                                                Apri flusso
                                            </a>
                                        </div>
                                    </article>
                                <?php endforeach; ?>
                            </div>
                        </article>

                    <?php else: ?>
                        <article class="checkout-card admin-panel-card">
                            <div class="account-panel-head">
                                <span class="account-panel-kicker">Vista centrale</span>
                                <h3>Controllo inventario in sola lettura</h3>
                                <p class="checkout-muted">Come admin centrale puoi monitorare quantità, prezzo locale, valore stock e soglie della filiale selezionata. La rettifica resta riservata al manager della sede.</p>
                            </div>
                            <ul class="riepilogo-lista">
                                <li><span>Referenze monitorate</span><strong><?php echo (int) $inventoryProductCount; ?></strong></li>
                                <li><span>Acquistabili</span><strong><?php echo (int) $inventoryAvailableCount; ?></strong></li>
                                <li><span>In arrivo</span><strong><?php echo (int) $inventoryPendingProductsCount; ?> referenze</strong></li>
                            </ul>
                        </article>
                    <?php endif; ?>
                </div>

                <aside class="checkout-card account-side admin-action-side">
                    <div class="account-panel-head">
                        <span class="account-panel-kicker">Sintesi</span>
                        <h3>Stato stock</h3>
                    </div>
                    <ul class="riepilogo-lista">
                        <li><span>Unità a stock</span><strong><?php echo (int) $kpis['inventory_units']; ?></strong></li>
                        <li><span>Valore inventario</span><strong><?php echo money_eur((int) $kpis['inventory_value_cents']); ?></strong></li>
                        <li><span>Unità in arrivo</span><strong><?php echo (int) $inventoryPendingUnits; ?></strong></li>
                        <li><span>Prodotti sotto soglia</span><strong><?php echo (int) $kpis['stock_alerts']; ?></strong></li>
                    </ul>
                    <p class="checkout-muted account-note">
                        La disponibilità online della sede segue lo stock residuo: quando una referenza finisce o resta senza copertura sufficiente, lo stato qui sotto lo rende subito evidente.
                    </p>
                </aside>
            </div>

            <article class="checkout-card admin-panel-card">
                <div class="account-panel-head account-panel-head--split">
                    <div>
                        <span class="account-panel-kicker">Dettaglio</span>
                        <h3>Disponibilità per prodotto</h3>
                        <p class="checkout-muted">Quantità presenti, merce in arrivo, valore stock e stato rispetto alla soglia automatica.</p>
                    </div>
                    <ul class="admin-tag-list admin-tag-list--compact" aria-label="Sintesi disponibilità inventario">
                        <li><?php echo (int) $inventoryAvailableCount; ?> acquistabili</li>
                        <li><?php echo (int) $inventoryBelowThresholdCount; ?> alert</li>
                        <li><?php echo (int) $inventoryPendingProductsCount; ?> in arrivo</li>
                    </ul>
                </div>

                <?php if (empty($inventoryItems)): ?>
                    <p class="checkout-muted">Nessun prodotto di filiale disponibile per il monitoraggio inventario.</p>
                <?php else: ?>
                    <div class="admin-table-wrap">
                        <table class="admin-table">
                            <caption class="sr-only">Inventario della filiale per prodotto</caption>
                            <thead>
                                <tr>
                                    <th scope="col">Prodotto</th>
                                    <th scope="col">In sede</th>
                                    <th scope="col">In arrivo</th>
                                    <th scope="col">Prezzo filiale</th>
                                    <th scope="col">Costo medio</th>
                                    <th scope="col">Valore stock</th>
                                    <th scope="col">Soglia</th>
                                    <th scope="col">Stato</th>
                                    <?php if ($canModifyBranchOperations): ?>
                                        <th scope="col">Azioni</th>
                                    <?php endif; ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($inventoryItems as $inventoryItem): ?>
                                    <tr>
                                        <th scope="row">
                                            <div class="admin-table-primary">
                                                <strong><?php echo htmlspecialchars((string) $inventoryItem['product_name'], ENT_QUOTES, 'UTF-8'); ?></strong>
                                                <span><?php echo htmlspecialchars((string) $inventoryItem['category_name'], ENT_QUOTES, 'UTF-8'); ?></span>
                                            </div>
                                        </th>
                                        <td><?php echo (int) $inventoryItem['on_hand_qty']; ?></td>
                                        <td><?php echo (int) $inventoryItem['pending_supply_qty']; ?></td>
                                        <td><?php echo money_eur((int) $inventoryItem['sale_price_cents']); ?></td>
                                        <td><?php echo money_eur((int) $inventoryItem['average_unit_cost_cents']); ?></td>
                                        <td><?php echo money_eur((int) $inventoryItem['stock_value_cents']); ?></td>
                                        <td><?php echo (int) $inventoryItem['threshold_qty']; ?></td>
                                        <td>
                                            <span class="admin-status-pill <?php echo !empty($inventoryItem['manual_unavailable']) ? 'is-muted' : (!empty($inventoryItem['is_below_threshold']) ? 'is-warning' : (!empty($inventoryItem['is_available_for_sale']) ? 'is-success' : 'is-muted')); ?>">
                                                <?php if (!empty($inventoryItem['manual_unavailable'])): ?>
                                                    Bloccato
                                                <?php elseif (!empty($inventoryItem['is_below_threshold'])): ?>
                                                    Sotto soglia
                                                <?php elseif (!empty($inventoryItem['is_available_for_sale'])): ?>
                                                    Copertura ok
                                                <?php else: ?>
                                                    Non acquistabile
                                                <?php endif; ?>
                                            </span>
                                        </td>
                                        <?php if ($canModifyBranchOperations): ?>
                                            <td>
                                                <div class="admin-table-actions">
                                                    <a class="bottone-secondario" href="<?php echo htmlspecialchars(admin_inventory_adjustment_url($selectedBranchSlug, $isGeneralAdmin, 'conteggio', (int) $inventoryItem['product_id']), ENT_QUOTES, 'UTF-8'); ?>">
                                                        Rettifica
                                                    </a>
                                                </div>
                                            </td>
                                        <?php endif; ?>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </article>
        </section>
        <?php endif; ?>

        <?php if ($currentSection === 'forniture'): ?>
        <section id="sezione-forniture" class="admin-section" aria-labelledby="titolo-forniture">
            <h2 id="titolo-forniture" class="sr-only">Forniture standard e straordinarie</h2>

            <article class="checkout-card admin-section-hero">
                <div class="admin-section-hero-copy">
                    <span class="account-panel-kicker">Studio flussi</span>
                    <h3>Controllo forniture costruito a blocchi</h3>
                    <p class="checkout-muted">La sezione operativa ora prova a ragionare come un workflow builder: scegli uno scenario, leggi il flusso dall'alto verso il basso e apri i parametri del singolo blocco solo quando devi modificarlo.</p>
                </div>
                <div class="admin-section-hero-stats" aria-label="Indicatori forniture">
                    <article class="admin-section-stat">
                        <span>Template attivi</span>
                        <strong><?php echo (int) $activeTemplatesCount; ?></strong>
                    </article>
                    <article class="admin-section-stat">
                        <span>Forniture aperte</span>
                        <strong><?php echo (int) $openSupplyOrdersCount; ?></strong>
                    </article>
                    <article class="admin-section-stat">
                        <span>Ricezioni completate</span>
                        <strong><?php echo (int) $receivedSupplyOrdersCount; ?></strong>
                    </article>
                    <article class="admin-section-stat">
                        <span>Policy attive</span>
                        <strong><?php echo (int) $activePoliciesCount; ?></strong>
                    </article>
                </div>
            </article>

            <?php if ($canModifyBranchOperations): ?>
                <div class="admin-builder-suite">
                    <section class="admin-builder-suite-intro">
                        <div class="admin-builder-suite-copy admin-builder-suite-copy--full">
                            <span class="account-panel-kicker">Studio flussi</span>
                            <h3>Scegli il builder giusto</h3>
                            <p class="checkout-muted">Questo è un unico blocco di gestione per le forniture della filiale. Parti da qui, apri il builder corretto e lavora poi nella pagina dedicata con breadcrumb, ritorno rapido e linguaggio coerente con il checkout.</p>
                            <p class="checkout-muted account-note">Il costo di approvvigionamento non è più richiesto al manager: il sistema usa automaticamente il valore registrato per la filiale.</p>
                        </div>
                    </section>

                    <div class="admin-builder-hub">
                        <article class="checkout-card admin-builder-card">
                            <div class="admin-builder-card-main">
                                <div class="account-panel-head">
                                    <span class="account-panel-kicker">Scenario 1</span>
                                    <h3>Routine ricorrente</h3>
                                    <p class="checkout-muted">Pagina dedicata per costruire template ricorrenti come un flusso: trigger, righe prodotto dinamiche e conferma finale.</p>
                                </div>

                                <ol class="admin-builder-step-list">
                                    <li>Imposta nome, frequenza e primo avvio del template.</li>
                                    <li>Aggiungi prodotti con +, rimuovili con x e lascia il costo al database della sede.</li>
                                    <li>Salva il flusso e ritrovalo nella lista template qui sotto.</li>
                                </ol>
                            </div>

                            <div class="admin-builder-card-side">
                                <span class="admin-status-pill <?php echo $activeTemplatesCount > 0 ? 'is-success' : 'is-muted'; ?>">
                                    <?php echo $activeTemplatesCount > 0 ? $activeTemplatesCount . ' attivi' : 'Nessun template'; ?>
                                </span>

                                <ul class="admin-builder-meta-list" aria-label="Punti chiave routine ricorrente">
                                    <li>Prodotti illimitati</li>
                                    <li>Costo filiale automatico</li>
                                    <li>Pattern simile al checkout</li>
                                </ul>

                                <div class="checkout-navigation checkout-navigation--solo-azione admin-builder-card-cta">
                                    <a class="bottone-primario" href="<?php echo htmlspecialchars($standardSupplyBuilderUrl, ENT_QUOTES, 'UTF-8'); ?>">Apri builder routine</a>
                                </div>
                            </div>
                        </article>

                        <article class="checkout-card admin-builder-card">
                            <div class="admin-builder-card-main">
                                <div class="account-panel-head">
                                    <span class="account-panel-kicker">Scenario 2</span>
                                    <h3>Intervento una tantum</h3>
                                    <p class="checkout-muted">Percorso separato per urgenze o reintegri fuori programma, con focus solo sulle righe davvero necessarie.</p>
                                </div>

                                <ol class="admin-builder-step-list">
                                    <li>Definisci fornitore e consegna prevista solo se servono.</li>
                                    <li>Compila la lista prodotti con quantità dinamiche, senza toccare i costi.</li>
                                    <li>Registra l ordine e ritrovalo nello storico operativo.</li>
                                </ol>
                            </div>

                            <div class="admin-builder-card-side">
                                <span class="admin-status-pill <?php echo $openSupplyOrdersCount > 0 ? 'is-warning' : 'is-success'; ?>">
                                    <?php echo $openSupplyOrdersCount > 0 ? $openSupplyOrdersCount . ' aperte' : 'Nessuna aperta'; ?>
                                </span>

                                <ul class="admin-builder-meta-list" aria-label="Punti chiave intervento una tantum">
                                    <li>Focus su urgenze</li>
                                    <li>Righe aggiungibili al volo</li>
                                    <li>Storico subito disponibile</li>
                                </ul>

                                <div class="checkout-navigation checkout-navigation--solo-azione admin-builder-card-cta">
                                    <a class="bottone-primario" href="<?php echo htmlspecialchars($extraSupplyBuilderUrl, ENT_QUOTES, 'UTF-8'); ?>">Apri builder urgente</a>
                                </div>
                            </div>
                        </article>

                        <article class="checkout-card admin-builder-card">
                            <div class="admin-builder-card-main">
                                <div class="account-panel-head">
                                    <span class="account-panel-kicker">Scenario 3</span>
                                    <h3>Automazione stock</h3>
                                    <p class="checkout-muted">Builder dedicato alle policy di riordino, con trigger, controlli anti-duplicazione e output finale separati.</p>
                                </div>

                                <ol class="admin-builder-step-list">
                                    <li>Scegli la referenza e la soglia minima di attenzione.</li>
                                    <li>Configura cooldown e massimo in arrivo per evitare sovrapposizioni.</li>
                                    <li>Decidi se produrre una bozza o un ordine automatico.</li>
                                </ol>
                            </div>

                            <div class="admin-builder-card-side">
                                <span class="admin-status-pill <?php echo $activePoliciesCount > 0 ? 'is-success' : 'is-muted'; ?>">
                                    <?php echo $activePoliciesCount > 0 ? $activePoliciesCount . ' attive' : 'Nessuna policy'; ?>
                                </span>

                                <ul class="admin-builder-meta-list" aria-label="Punti chiave automazione stock">
                                    <li>Trigger dedicati</li>
                                    <li>Controllo riordini</li>
                                    <li>Esecuzione manuale disponibile</li>
                                </ul>

                                <div class="checkout-navigation checkout-navigation--solo-azione admin-builder-card-cta">
                                    <a class="bottone-primario" href="<?php echo htmlspecialchars($automaticSupplyBuilderUrl, ENT_QUOTES, 'UTF-8'); ?>">Apri builder automazione</a>
                                </div>
                            </div>
                        </article>
                    </div>
                </div>
            <?php else: ?>
                <article class="checkout-card admin-panel-card">
                    <div class="account-panel-head">
                        <span class="account-panel-kicker">Vista centrale</span>
                        <h3>Approvvigionamento in sola lettura</h3>
                        <p class="checkout-muted">Come admin centrale puoi consultare il nuovo hub forniture e i suoi indicatori, ma i builder operativi restano disponibili solo al manager della sede.</p>
                    </div>
                    <ul class="riepilogo-lista">
                        <li><span>Template attivi</span><strong><?php echo (int) $activeTemplatesCount; ?></strong></li>
                        <li><span>Forniture aperte</span><strong><?php echo (int) $openSupplyOrdersCount; ?></strong></li>
                        <li><span>Policy attive</span><strong><?php echo (int) $activePoliciesCount; ?></strong></li>
                    </ul>
                </article>
            <?php endif; ?>

            <div class="admin-form-grid admin-form-grid--double">
                <article class="checkout-card admin-panel-card">
                    <div class="account-panel-head account-panel-head--split">
                        <div>
                            <span class="account-panel-kicker">Template</span>
                            <h3>Forniture standard attive</h3>
                            <p class="checkout-muted">Ogni template genera automaticamente una nuova fornitura quando arriva la prossima esecuzione.</p>
                        </div>
                        <ul class="admin-tag-list admin-tag-list--compact" aria-label="Sintesi template">
                            <li><?php echo (int) $activeTemplatesCount; ?> attivi</li>
                            <li><?php echo max(0, count($templates) - $activeTemplatesCount); ?> sospesi</li>
                        </ul>
                    </div>

                    <?php if (empty($templates)): ?>
                        <p class="checkout-muted">Nessuna fornitura standard impostata per questa filiale.</p>
                    <?php else: ?>
                        <div class="admin-stack-list">
                            <?php foreach ($templates as $template): ?>
                                <article class="admin-detail-card">
                                    <div class="ordine-card-head">
                                        <div>
                                            <p class="ordine-card-eyebrow"><?php echo htmlspecialchars((string) ($frequencyLabels[(string) $template['frequency']] ?? $template['frequency']), ENT_QUOTES, 'UTF-8'); ?></p>
                                            <h4><?php echo htmlspecialchars((string) $template['template_name'], ENT_QUOTES, 'UTF-8'); ?></h4>
                                        </div>
                                        <span class="admin-status-pill <?php echo (int) $template['is_active'] === 1 ? 'is-success' : 'is-muted'; ?>">
                                            <?php echo (int) $template['is_active'] === 1 ? 'Attivo' : 'Sospeso'; ?>
                                        </span>
                                    </div>

                                    <ul class="riepilogo-lista">
                                        <li><span>Prossima esecuzione</span><strong><?php echo htmlspecialchars(format_datetime_for_ui((string) $template['next_run_at']), ENT_QUOTES, 'UTF-8'); ?></strong></li>
                                        <li><span>Ultima generazione</span><strong><?php echo htmlspecialchars(format_datetime_for_ui((string) $template['last_generated_at'], 'Mai generata'), ENT_QUOTES, 'UTF-8'); ?></strong></li>
                                    </ul>

                                    <?php if (!empty($template['items'])): ?>
                                        <ul class="admin-tag-list" aria-label="Prodotti template">
                                            <?php foreach ($template['items'] as $item): ?>
                                                <li><?php echo htmlspecialchars((string) $item['product_name'], ENT_QUOTES, 'UTF-8'); ?> x<?php echo (int) $item['quantity']; ?></li>
                                            <?php endforeach; ?>
                                        </ul>
                                    <?php endif; ?>

                                    <?php if (!empty($template['notes'])): ?>
                                        <p class="checkout-muted"><?php echo htmlspecialchars((string) $template['notes'], ENT_QUOTES, 'UTF-8'); ?></p>
                                    <?php endif; ?>

                                    <?php if ($canModifyBranchOperations): ?>
                                        <form method="POST" action="<?php echo htmlspecialchars((string) ($sectionUrls['forniture'] ?? 'admin_forniture.php'), ENT_QUOTES, 'UTF-8'); ?>" class="admin-inline-form">
                                            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8'); ?>">
                                            <input type="hidden" name="action" value="toggle_template">
                                            <input type="hidden" name="template_id" value="<?php echo (int) $template['id']; ?>">
                                            <input type="hidden" name="is_active" value="<?php echo (int) $template['is_active'] === 1 ? '0' : '1'; ?>">
                                            <button class="bottone-secondario" type="submit">
                                                <?php echo (int) $template['is_active'] === 1 ? 'Sospendi template' : 'Riattiva template'; ?>
                                            </button>
                                        </form>
                                    <?php endif; ?>
                                </article>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </article>
            </div>

            <div class="admin-form-grid">
                <article class="checkout-card admin-panel-card">
                    <div class="account-panel-head account-panel-head--split">
                        <div>
                            <span class="account-panel-kicker">Ordine automatico</span>
                            <h3>Politiche di riordino</h3>
                            <p class="checkout-muted">Soglie minime, quantità di riordino e cooldown per evitare duplicazioni di approvvigionamento.</p>
                        </div>
                        <ul class="admin-tag-list admin-tag-list--compact" aria-label="Sintesi policy riordino">
                            <li><?php echo (int) $activePoliciesCount; ?> attive</li>
                            <li><?php echo max(0, count($policies) - $activePoliciesCount); ?> sospese</li>
                        </ul>
                    </div>

                    <?php if (empty($policies)): ?>
                        <p class="checkout-muted">Nessuna policy di ordine automatico configurata.</p>
                    <?php else: ?>
                        <div class="admin-table-wrap">
                            <table class="admin-table">
                                <caption class="sr-only">Policy di ordine automatico per prodotto</caption>
                                <thead>
                                    <tr>
                                        <th scope="col">Prodotto</th>
                                        <th scope="col">Stock attuale</th>
                                        <th scope="col">Soglia</th>
                                        <th scope="col">Riordino</th>
                                        <th scope="col">Cooldown</th>
                                        <th scope="col">Modalità</th>
                                        <th scope="col">Stato</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($policies as $policy): ?>
                                        <tr>
                                            <th scope="row"><?php echo htmlspecialchars((string) $policy['product_name'], ENT_QUOTES, 'UTF-8'); ?></th>
                                            <td><?php echo (int) $policy['on_hand_qty']; ?></td>
                                            <td><?php echo (int) $policy['threshold_qty']; ?></td>
                                            <td><?php echo (int) $policy['reorder_qty']; ?></td>
                                            <td><?php echo (int) $policy['cooldown_hours']; ?>h</td>
                                            <td><?php echo htmlspecialchars((string) $policy['mode'], ENT_QUOTES, 'UTF-8'); ?></td>
                                            <td>
                                                <div class="admin-inline-actions">
                                                    <span class="admin-status-pill <?php echo (int) $policy['is_active'] === 1 ? 'is-success' : 'is-muted'; ?>">
                                                        <?php echo (int) $policy['is_active'] === 1 ? 'Attiva' : 'Sospesa'; ?>
                                                    </span>
                                                    <?php if ($canModifyBranchOperations): ?>
                                                        <form method="POST" action="<?php echo htmlspecialchars((string) ($sectionUrls['forniture'] ?? 'admin_forniture.php'), ENT_QUOTES, 'UTF-8'); ?>" class="admin-inline-form">
                                                            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8'); ?>">
                                                            <input type="hidden" name="action" value="toggle_policy">
                                                            <input type="hidden" name="policy_id" value="<?php echo (int) $policy['id']; ?>">
                                                            <input type="hidden" name="is_active" value="<?php echo (int) $policy['is_active'] === 1 ? '0' : '1'; ?>">
                                                            <button class="bottone-secondario" type="submit">
                                                                <?php echo (int) $policy['is_active'] === 1 ? 'Sospendi' : 'Riattiva'; ?>
                                                            </button>
                                                        </form>
                                                    <?php endif; ?>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </article>
            </div>

            <div class="admin-form-grid">
                <article class="checkout-card admin-panel-card">
                    <div class="account-panel-head account-panel-head--split">
                        <div>
                            <span class="account-panel-kicker">Ordini fornitura</span>
                            <h3>Storico operativo</h3>
                            <p class="checkout-muted">Bozze, ordini programmati, ricezioni concluse e documenti disponibili.</p>
                        </div>
                        <ul class="admin-tag-list admin-tag-list--compact" aria-label="Sintesi storico forniture">
                            <li><?php echo (int) $openSupplyOrdersCount; ?> aperte</li>
                            <li><?php echo (int) $receivedSupplyOrdersCount; ?> ricevute</li>
                        </ul>
                    </div>

                    <?php if (empty($supplyOrders)): ?>
                        <p class="checkout-muted">Nessuna fornitura registrata al momento.</p>
                    <?php else: ?>
                        <div class="admin-stack-list">
                            <?php foreach ($supplyOrders as $supplyOrder): ?>
                                <article class="admin-detail-card">
                                    <div class="ordine-card-head">
                                        <div>
                                            <p class="ordine-card-eyebrow"><?php echo htmlspecialchars((string) $supplyOrder['order_type'], ENT_QUOTES, 'UTF-8'); ?></p>
                                            <h4><?php echo htmlspecialchars((string) $supplyOrder['order_code'], ENT_QUOTES, 'UTF-8'); ?></h4>
                                        </div>
                                        <span class="admin-status-pill <?php echo (string) $supplyOrder['status'] === 'received' ? 'is-success' : ((string) $supplyOrder['status'] === 'cancelled' ? 'is-muted' : 'is-warning'); ?>">
                                            <?php echo htmlspecialchars((string) $supplyOrder['status'], ENT_QUOTES, 'UTF-8'); ?>
                                        </span>
                                    </div>

                                    <ul class="riepilogo-lista">
                                        <li><span>Fornitore</span><strong><?php echo htmlspecialchars((string) $supplyOrder['supplier_name'], ENT_QUOTES, 'UTF-8'); ?></strong></li>
                                        <li><span>Totale previsto</span><strong><?php echo money_eur((int) $supplyOrder['total_cents']); ?></strong></li>
                                        <li><span>Consegna prevista</span><strong><?php echo htmlspecialchars(format_datetime_for_ui((string) $supplyOrder['scheduled_for']), ENT_QUOTES, 'UTF-8'); ?></strong></li>
                                    </ul>

                                    <?php if (!empty($supplyOrder['items'])): ?>
                                        <ul class="admin-tag-list" aria-label="Prodotti della fornitura">
                                            <?php foreach ($supplyOrder['items'] as $item): ?>
                                                <li><?php echo htmlspecialchars((string) $item['product_name_snapshot'], ENT_QUOTES, 'UTF-8'); ?> x<?php echo (int) $item['quantity_ordered']; ?></li>
                                            <?php endforeach; ?>
                                        </ul>
                                    <?php endif; ?>

                                    <div class="admin-inline-actions">
                                        <?php if ((string) $supplyOrder['status'] === 'received'): ?>
                                            <a class="bottone-secondario" href="ricevuta.php?tipo=fornitura&amp;id=<?php echo (int) $supplyOrder['id']; ?>">Apri ricevuta</a>
                                        <?php endif; ?>

                                        <?php if ($canModifyBranchOperations && in_array((string) $supplyOrder['status'], ['draft', 'scheduled', 'ordered'], true)): ?>
                                            <form method="POST" action="<?php echo htmlspecialchars((string) ($sectionUrls['forniture'] ?? 'admin_forniture.php'), ENT_QUOTES, 'UTF-8'); ?>" class="admin-inline-form">
                                                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8'); ?>">
                                                <input type="hidden" name="action" value="receive_supply">
                                                <input type="hidden" name="supply_order_id" value="<?php echo (int) $supplyOrder['id']; ?>">
                                                <button class="bottone-primario" type="submit">Conferma ricezione</button>
                                            </form>
                                            <form method="POST" action="<?php echo htmlspecialchars((string) ($sectionUrls['forniture'] ?? 'admin_forniture.php'), ENT_QUOTES, 'UTF-8'); ?>" class="admin-inline-form">
                                                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8'); ?>">
                                                <input type="hidden" name="action" value="cancel_supply">
                                                <input type="hidden" name="supply_order_id" value="<?php echo (int) $supplyOrder['id']; ?>">
                                                <button class="bottone-secondario" type="submit">Annulla</button>
                                            </form>
                                        <?php endif; ?>
                                    </div>
                                </article>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </article>
            </div>
        </section>
        <?php endif; ?>

        <?php if ($currentSection === 'automatico'): ?>
        <section id="sezione-automatico" class="admin-section" aria-labelledby="titolo-automatico">
            <h2 id="titolo-automatico" class="sr-only">Politiche di ordine automatico</h2>

            <div class="admin-form-grid">
                <?php if ($canModifyBranchOperations): ?>
                    <form class="checkout-card checkout-form" method="POST" action="<?php echo htmlspecialchars((string) ($sectionUrls['automatico'] ?? 'admin_automatico.php'), ENT_QUOTES, 'UTF-8'); ?>" data-valida novalidate aria-labelledby="titolo-policy-auto">
                        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8'); ?>">
                        <input type="hidden" name="action" value="create_policy">

                        <div class="account-panel-head">
                            <span class="account-panel-kicker">Nuova regola</span>
                            <h3 id="titolo-policy-auto">Configura o aggiorna una policy</h3>
                            <p class="checkout-muted">Se la stessa referenza è già presente, la regola viene aggiornata con i nuovi valori.</p>
                        </div>

                        <div class="campo-gruppo">
                            <label for="policy-product-id">Prodotto</label>
                            <select id="policy-product-id" name="product_id" required aria-required="true">
                                <option value="">Seleziona un prodotto</option>
                                <?php foreach ($inventoryItems as $inventoryItem): ?>
                                    <option value="<?php echo (int) $inventoryItem['product_id']; ?>">
                                        <?php echo htmlspecialchars((string) $inventoryItem['product_name'], ENT_QUOTES, 'UTF-8'); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="admin-inline-grid admin-inline-grid--triple">
                            <div class="campo-gruppo">
                                <label for="policy-threshold">Soglia minima</label>
                                <input type="number" id="policy-threshold" name="threshold_qty" required aria-required="true" min="1" step="1">
                            </div>
                            <div class="campo-gruppo">
                                <label for="policy-reorder">Quantità riordino</label>
                                <input type="number" id="policy-reorder" name="reorder_qty" required aria-required="true" min="1" step="1">
                            </div>
                            <div class="campo-gruppo">
                                <label for="policy-cooldown">Cooldown ore</label>
                                <input type="number" id="policy-cooldown" name="cooldown_hours" min="0" step="1" value="6">
                            </div>
                        </div>

                        <div class="admin-inline-grid">
                            <div class="campo-gruppo">
                                <label for="policy-max-pending">Massimo in arrivo</label>
                                <input type="number" id="policy-max-pending" name="max_pending_qty" min="0" step="1" value="0">
                            </div>
                            <div class="campo-gruppo">
                                <label for="policy-mode">Modalità</label>
                                <select id="policy-mode" name="mode">
                                    <option value="draft">Genera bozza</option>
                                    <option value="auto-order">Registra ordine</option>
                                </select>
                            </div>
                        </div>

                        <div class="checkout-navigation checkout-navigation--solo-azione">
                            <button class="bottone-primario" type="submit">Salva policy</button>
                        </div>
                    </form>
                <?php endif; ?>

                <aside class="checkout-card account-side">
                    <div class="account-panel-head">
                        <span class="account-panel-kicker">Metodo</span>
                        <h3>Come funziona il riordino</h3>
                    </div>
                    <ul class="riepilogo-lista">
                        <li><span>Trigger</span><strong>Stock proiettato sotto soglia</strong></li>
                        <li><span>Controllo</span><strong>Merce già in arrivo inclusa</strong></li>
                        <li><span>Output</span><strong>Bozza o ordine automatico</strong></li>
                    </ul>
                    <?php if ($canModifyBranchOperations): ?>
                        <form method="POST" action="<?php echo htmlspecialchars((string) ($sectionUrls['automatico'] ?? 'admin_automatico.php'), ENT_QUOTES, 'UTF-8'); ?>" class="admin-inline-form">
                            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8'); ?>">
                            <input type="hidden" name="action" value="run_auto_reorder">
                            <button class="bottone-secondario" type="submit">Esegui controllo ora</button>
                        </form>
                    <?php endif; ?>
                    <p class="checkout-muted account-note">Il motore evita ordini ravvicinati usando il cooldown e il tetto massimo di merce già pendente.</p>
                </aside>
            </div>

            <div class="checkout-card admin-panel-card">
                <div class="account-panel-head">
                    <span class="account-panel-kicker">Regole attive</span>
                    <h3>Policy configurate</h3>
                    <p class="checkout-muted">Ogni riga confronta stock attuale e strategia di approvvigionamento definita per il prodotto.</p>
                </div>

                <?php if (empty($policies)): ?>
                    <p class="checkout-muted">Nessuna policy di ordine automatico configurata.</p>
                <?php else: ?>
                    <div class="admin-table-wrap">
                        <table class="admin-table">
                            <caption class="sr-only">Policy di ordine automatico per prodotto</caption>
                            <thead>
                                <tr>
                                    <th scope="col">Prodotto</th>
                                    <th scope="col">Stock attuale</th>
                                    <th scope="col">Soglia</th>
                                    <th scope="col">Riordino</th>
                                    <th scope="col">Cooldown</th>
                                    <th scope="col">Modalità</th>
                                    <th scope="col">Stato</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($policies as $policy): ?>
                                    <tr>
                                        <th scope="row"><?php echo htmlspecialchars((string) $policy['product_name'], ENT_QUOTES, 'UTF-8'); ?></th>
                                        <td><?php echo (int) $policy['on_hand_qty']; ?></td>
                                        <td><?php echo (int) $policy['threshold_qty']; ?></td>
                                        <td><?php echo (int) $policy['reorder_qty']; ?></td>
                                        <td><?php echo (int) $policy['cooldown_hours']; ?>h</td>
                                        <td><?php echo htmlspecialchars((string) $policy['mode'], ENT_QUOTES, 'UTF-8'); ?></td>
                                        <td>
                                            <div class="admin-inline-actions">
                                                <span class="admin-status-pill <?php echo (int) $policy['is_active'] === 1 ? 'is-success' : 'is-muted'; ?>">
                                                    <?php echo (int) $policy['is_active'] === 1 ? 'Attiva' : 'Sospesa'; ?>
                                                </span>
                                                <?php if ($canModifyBranchOperations): ?>
                                                    <form method="POST" action="<?php echo htmlspecialchars((string) ($sectionUrls['automatico'] ?? 'admin_automatico.php'), ENT_QUOTES, 'UTF-8'); ?>" class="admin-inline-form">
                                                        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8'); ?>">
                                                        <input type="hidden" name="action" value="toggle_policy">
                                                        <input type="hidden" name="policy_id" value="<?php echo (int) $policy['id']; ?>">
                                                        <input type="hidden" name="is_active" value="<?php echo (int) $policy['is_active'] === 1 ? '0' : '1'; ?>">
                                                        <button class="bottone-secondario" type="submit">
                                                            <?php echo (int) $policy['is_active'] === 1 ? 'Sospendi' : 'Riattiva'; ?>
                                                        </button>
                                                    </form>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </section>
        <?php endif; ?>

        <?php if ($currentSection === 'team' && $canManageBranchManagers): ?>
            <section id="sezione-team" class="admin-section" aria-labelledby="titolo-team-admin">
                <h2 id="titolo-team-admin" class="sr-only">Manager di filiale</h2>

                <?php if ($teamMode === 'create_details' || $teamMode === 'edit_details' || $teamMode === 'review'): ?>
                    <div class="checkout-shell">
                        <div class="checkout-main">
                            <?php if ($teamMode === 'create_details' || $teamMode === 'edit_details'): ?>
                                <form class="checkout-card checkout-form" method="POST" action="<?php echo htmlspecialchars((string) ($sectionUrls['team'] ?? 'admin_team.php'), ENT_QUOTES, 'UTF-8'); ?>" data-valida novalidate aria-labelledby="titolo-manager-form">
                                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8'); ?>">
                                    <input type="hidden" name="action" value="save_details">
                                    <input type="hidden" name="manager_id" value="<?php echo (int) ($draft['id'] ?? 0); ?>">

                                    <div class="account-panel-head">
                                        <span class="account-panel-kicker">Passo 1 di 2</span>
                                        <h3 id="titolo-manager-form"><?php echo ($draft['id'] ?? 0) > 0 ? 'Modifica credenziali manager' : 'Crea nuove credenziali manager'; ?></h3>
                                        <p class="checkout-muted">Inserisci i dati principali. Potrai rivedere tutto nel passaggio successivo.</p>
                                    </div>

                                    <div class="admin-form-grid">
                                        <div class="campo-gruppo">
                                            <label for="manager-username">Username</label>
                                            <input type="text" id="manager-username" name="username" required aria-required="true" maxlength="50" value="<?php echo htmlspecialchars((string) ($draft['username'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>">
                                        </div>
                                        <div class="campo-gruppo">
                                            <label for="manager-email">Email</label>
                                            <input type="email" id="manager-email" name="email" required aria-required="true" maxlength="160" value="<?php echo htmlspecialchars((string) ($draft['email'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>">
                                        </div>
                                    </div>

                                    <div class="admin-form-grid">
                                        <div class="campo-gruppo">
                                            <label for="manager-password"><?php echo ($draft['id'] ?? 0) > 0 ? 'Nuova password (opzionale)' : 'Password'; ?> <span class="campo-suggerimento">(minimo 8 caratteri)</span></label>
                                            <input type="password" id="manager-password" name="password" <?php echo ($draft['id'] ?? 0) === 0 ? 'required aria-required="true"' : ''; ?> minlength="8" autocomplete="new-password" aria-describedby="manager-password-suggerimento">
                                            <p id="manager-password-suggerimento" class="campo-aiuto">Caratteri ammessi: lettere, numeri, ! @ # $ % &amp;</p>
                                        </div>
                                        <div class="campo-gruppo">
                                            <label for="manager-branch">Filiale assegnata</label>
                                            <select id="manager-branch" name="managed_branch_id" required aria-required="true">
                                                <option value="">Seleziona la filiale</option>
                                                <?php foreach ($allBranches as $branch): ?>
                                                    <option value="<?php echo (int) $branch['id']; ?>" <?php echo (int) $branch['id'] === (int) ($draft['managed_branch_id'] ?? 0) ? 'selected' : ''; ?>>
                                                        <?php echo htmlspecialchars((string) $branch['name'], ENT_QUOTES, 'UTF-8'); ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="checkout-navigation">
                                        <a class="bottone-secondario" href="admin_team.php?reset=1">&larr; Annulla</a>
                                        <button class="bottone-primario" type="submit">Vai al riepilogo &rarr;</button>
                                    </div>
                                </form>
                            <?php elseif ($teamMode === 'review'): ?>
                                <div class="checkout-card checkout-form">
                                    <div class="account-panel-head">
                                        <span class="account-panel-kicker">Passo 2 di 2</span>
                                        <h3>Riepilogo credenziali</h3>
                                        <p class="checkout-muted">Controlla la correttezza dei dati prima di confermare l'operazione.</p>
                                    </div>

                                    <ul class="riepilogo-lista">
                                        <li><span>Username</span><strong><?php echo htmlspecialchars((string) $draft['username'], ENT_QUOTES, 'UTF-8'); ?></strong></li>
                                        <li><span>Email</span><strong><?php echo htmlspecialchars((string) $draft['email'], ENT_QUOTES, 'UTF-8'); ?></strong></li>
                                        <li><span>Filiale</span><strong><?php echo htmlspecialchars((string) $draft['managed_branch_name'], ENT_QUOTES, 'UTF-8'); ?></strong></li>
                                        <li><span>Password</span><strong><?php echo ($draft['password'] !== '') ? '******** (Modificata)' : 'Invariata'; ?></strong></li>
                                    </ul>

                                    <div class="checkout-navigation">
                                        <a class="bottone-secondario" href="admin_team.php?<?php echo ($draft['id'] > 0) ? 'modifica='.$draft['id'] : 'modalita=nuovo'; ?>">&larr; Torna alla modifica</a>
                                        <form method="POST" action="admin_team.php" class="admin-inline-form">
                                            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8'); ?>">
                                            <input type="hidden" name="action" value="confirm_manager">
                                            <button class="bottone-primario" type="submit">Conferma e salva</button>
                                        </form>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>

                        <aside class="checkout-card account-side" aria-labelledby="titolo-note-team">
                            <h2 id="titolo-note-team">Suggerimenti</h2>
                            <ul class="riepilogo-lista">
                                <li><span>Password</span><strong>8+ caratteri</strong></li>
                                <li><span>Ammessi</span><strong>lettere, numeri, ! @ # $ % &amp;</strong></li>
                            </ul>
                            <p class="checkout-muted account-note">I manager associati a una filiale possono gestire il catalogo, l'inventario e le forniture di quella sede.</p>
                        </aside>
                    </div>
                <?php else: ?>
                    <article class="checkout-card admin-panel-card">
                        <div class="account-panel-head account-panel-head--split">
                            <div>
                                <span class="account-panel-kicker">Manager correnti</span>
                                <h3>Credenziali attive o revocate</h3>
                            </div>
                            <a class="bottone-primario" href="admin_team.php?modalita=nuovo&amp;reset=1">Nuovo manager</a>
                        </div>

                        <?php if (empty($branchManagers)): ?>
                            <p class="checkout-muted">Nessun manager di filiale configurato.</p>
                        <?php else: ?>
                            <div class="admin-stack-list">
                                <?php foreach ($branchManagers as $manager): ?>
                                    <article class="admin-detail-card">
                                        <div class="ordine-card-head">
                                            <div>
                                                <p class="ordine-card-eyebrow"><?php echo htmlspecialchars((string) ($manager['managed_branch_name'] ?? 'Filiale non assegnata'), ENT_QUOTES, 'UTF-8'); ?></p>
                                                <h4><?php echo htmlspecialchars((string) $manager['username'], ENT_QUOTES, 'UTF-8'); ?></h4>
                                            </div>
                                            <span class="admin-status-pill <?php echo (int) $manager['is_active'] === 1 ? 'is-success' : 'is-muted'; ?>">
                                                <?php echo (int) $manager['is_active'] === 1 ? 'Attivo' : 'Revocato'; ?>
                                            </span>
                                        </div>

                                        <ul class="riepilogo-lista">
                                            <li><span>Email</span><strong><?php echo htmlspecialchars((string) $manager['email'], ENT_QUOTES, 'UTF-8'); ?></strong></li>
                                            <li><span>Filiale</span><strong><?php echo htmlspecialchars((string) ($manager['managed_branch_name'] ?? 'Non assegnata'), ENT_QUOTES, 'UTF-8'); ?></strong></li>
                                        </ul>

                                        <div class="admin-inline-actions">
                                            <a class="bottone-secondario" href="admin_team.php?modifica=<?php echo (int) $manager['id']; ?>&amp;reset=1">Modifica credenziali</a>
                                            <form method="POST" action="<?php echo htmlspecialchars((string) ($sectionUrls['team'] ?? 'admin_team.php'), ENT_QUOTES, 'UTF-8'); ?>" class="admin-inline-form">
                                                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8'); ?>">
                                                <input type="hidden" name="action" value="toggle_branch_manager">
                                                <input type="hidden" name="manager_id" value="<?php echo (int) $manager['id']; ?>">
                                                <input type="hidden" name="is_active" value="<?php echo (int) $manager['is_active'] === 1 ? '0' : '1'; ?>">
                                                <button class="bottone-secondario" type="submit">
                                                    <?php echo (int) $manager['is_active'] === 1 ? 'Revoca accesso' : 'Riattiva accesso'; ?>
                                                </button>
                                            </form>
                                            <form method="POST" action="<?php echo htmlspecialchars((string) ($sectionUrls['team'] ?? 'admin_team.php'), ENT_QUOTES, 'UTF-8'); ?>" class="admin-inline-form">
                                                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8'); ?>">
                                                <input type="hidden" name="action" value="delete_branch_manager">
                                                <input type="hidden" name="manager_id" value="<?php echo (int) $manager['id']; ?>">
                                                <button class="bottone-primario" type="submit" data-confirm-delete="true">Elimina credenziali</button>
                                            </form>
                                        </div>
                                    </article>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </article>
                <?php endif; ?>
            </section>
        <?php endif; ?>

        <?php if ($currentSection === 'ricevute'): ?>
        <section id="sezione-ricevute" class="admin-section" aria-labelledby="titolo-ricevute">
            <h2 id="titolo-ricevute" class="sr-only">Ricevute forniture ricevute</h2>

            <div class="admin-form-grid">
                <article class="checkout-card admin-panel-card">
                    <div class="account-panel-head">
                        <span class="account-panel-kicker">Forniture</span>
                        <h3>Ricevute di ricezione</h3>
                    </div>

                    <?php
                    $receivedSupplyOrders = array_values(array_filter(
                        $supplyOrders,
                        static fn(array $order): bool => (string) ($order['status'] ?? '') === 'received'
                    ));
                    ?>

                    <?php if (empty($receivedSupplyOrders)): ?>
                        <p class="checkout-muted">Le ricevute fornitura appariranno qui appena confermerai una ricezione.</p>
                    <?php else: ?>
                        <div class="admin-stack-list">
                            <?php foreach ($receivedSupplyOrders as $supplyOrder): ?>
                                <article class="admin-detail-card">
                                    <div class="ordine-card-head">
                                        <div>
                                            <p class="ordine-card-eyebrow">Ricezione fornitura</p>
                                            <h4><?php echo htmlspecialchars((string) $supplyOrder['order_code'], ENT_QUOTES, 'UTF-8'); ?></h4>
                                        </div>
                                        <strong class="ordine-card-total"><?php echo money_eur((int) $supplyOrder['total_cents']); ?></strong>
                                    </div>
                                    <ul class="riepilogo-lista">
                                        <li><span>Ricevuta il</span><strong><?php echo htmlspecialchars(format_datetime_for_ui((string) $supplyOrder['received_at']), ENT_QUOTES, 'UTF-8'); ?></strong></li>
                                        <li><span>Fornitore</span><strong><?php echo htmlspecialchars((string) $supplyOrder['supplier_name'], ENT_QUOTES, 'UTF-8'); ?></strong></li>
                                    </ul>
                                    <div class="admin-inline-actions">
                                        <a class="bottone-secondario" href="ricevuta.php?tipo=fornitura&amp;id=<?php echo (int) $supplyOrder['id']; ?>">Apri ricevuta</a>
                                    </div>
                                </article>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </article>
            </div>
        </section>
        <?php endif; ?>
    </div>
</section>
