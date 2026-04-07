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
                            <article class="admin-branch-card<?php echo (int) $comparison['branch']['id'] === (int) $selectedBranch['id'] ? ' is-selected' : ''; ?>">
                                <h4><?php echo htmlspecialchars((string) $comparison['branch']['name'], ENT_QUOTES, 'UTF-8'); ?></h4>
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
                        <ul class="admin-bar-list" aria-label="Ricavi giornalieri">
                            <?php foreach ($salesTrend as $trendItem): ?>
                                <?php
                                $barWidth = $maxTrendRevenue > 0
                                    ? round(((int) $trendItem['revenue_cents'] / $maxTrendRevenue) * 100, 2)
                                    : 0;
                                ?>
                                <li>
                                    <div class="admin-bar-label">
                                        <strong><?php echo htmlspecialchars((string) $trendItem['label'], ENT_QUOTES, 'UTF-8'); ?></strong>
                                        <span><?php echo (int) $trendItem['orders_count']; ?> ordini</span>
                                    </div>
                                    <div class="admin-bar-track" aria-hidden="true">
                                        <span class="admin-bar-fill" style="width: <?php echo $barWidth; ?>%;"></span>
                                    </div>
                                    <strong class="admin-bar-value"><?php echo money_eur((int) $trendItem['revenue_cents']); ?></strong>
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

            <div class="admin-form-grid admin-form-grid--double">
                <?php if ($canManageGlobalCatalog): ?>
                    <article class="checkout-card admin-panel-card">
                        <div class="account-panel-head">
                            <span class="account-panel-kicker">Catalogo globale</span>
                            <h3>Prodotti condivisi</h3>
                            <p class="checkout-muted">Ogni nuovo prodotto creato qui diventa disponibile per tutte le filiali, che poi possono decidere se esporlo localmente.</p>
                        </div>

                        <div class="admin-inline-actions">
                            <a class="bottone-primario" href="admin_catalogo_prodotto.php">Nuovo prodotto</a>
                        </div>

                        <div class="admin-stack-list">
                            <?php foreach ($globalCatalog as $product): ?>
                                <article class="admin-detail-card">
                                    <div class="ordine-card-head">
                                        <div>
                                            <p class="ordine-card-eyebrow"><?php echo htmlspecialchars((string) $product['category_name'], ENT_QUOTES, 'UTF-8'); ?></p>
                                            <h4><?php echo htmlspecialchars((string) $product['name'], ENT_QUOTES, 'UTF-8'); ?></h4>
                                        </div>
                                        <strong class="ordine-card-total"><?php echo money_eur((int) $product['price_cents']); ?></strong>
                                    </div>

                                    <p class="checkout-muted"><?php echo htmlspecialchars((string) $product['description'], ENT_QUOTES, 'UTF-8'); ?></p>

                                    <ul class="riepilogo-lista">
                                        <li><span>Slug</span><strong><?php echo htmlspecialchars((string) $product['slug'], ENT_QUOTES, 'UTF-8'); ?></strong></li>
                                        <li><span>Filiali che lo espongono</span><strong><?php echo (int) $product['listed_branches_count']; ?></strong></li>
                                    </ul>

                                    <div class="admin-inline-actions">
                                        <a class="bottone-secondario" href="admin_catalogo_prodotto.php?id=<?php echo (int) $product['id']; ?>">Modifica prodotto</a>
                                        <form method="POST" action="<?php echo htmlspecialchars((string) ($sectionUrls['catalogo'] ?? 'admin_catalogo.php'), ENT_QUOTES, 'UTF-8'); ?>" class="admin-inline-form">
                                            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8'); ?>">
                                            <input type="hidden" name="action" value="delete_product">
                                            <input type="hidden" name="product_id" value="<?php echo (int) $product['id']; ?>">
                                            <button class="bottone-secondario" type="submit">Elimina</button>
                                        </form>
                                    </div>
                                </article>
                            <?php endforeach; ?>
                        </div>
                    </article>
                <?php endif; ?>

                <article class="checkout-card admin-panel-card">
                    <div class="account-panel-head">
                        <span class="account-panel-kicker">Catalogo sede</span>
                        <h3>Presenza prodotto in <?php echo htmlspecialchars((string) $selectedBranch['city'], ENT_QUOTES, 'UTF-8'); ?></h3>
                        <p class="checkout-muted">Ogni filiale può aggiungere il prodotto al proprio catalogo oppure lasciarlo nascosto. Se è presente ma finito o sospeso, resta visibile come non disponibile.</p>
                    </div>

                    <div class="admin-stack-list">
                        <?php foreach ($inventoryItems as $inventoryItem): ?>
                            <article class="admin-detail-card">
                                <div class="ordine-card-head">
                                    <div>
                                        <p class="ordine-card-eyebrow"><?php echo htmlspecialchars((string) $inventoryItem['category_name'], ENT_QUOTES, 'UTF-8'); ?></p>
                                        <h4><?php echo htmlspecialchars((string) $inventoryItem['product_name'], ENT_QUOTES, 'UTF-8'); ?></h4>
                                    </div>
                                    <span class="admin-status-pill <?php echo (int) $inventoryItem['is_listed'] === 1 ? ((int) $inventoryItem['is_available_for_sale'] === 1 ? 'is-success' : 'is-warning') : 'is-muted'; ?>">
                                        <?php if ((int) $inventoryItem['is_listed'] !== 1): ?>
                                            Nascosto
                                        <?php elseif ((int) $inventoryItem['is_available_for_sale'] === 1): ?>
                                            Disponibile
                                        <?php else: ?>
                                            Non disponibile
                                        <?php endif; ?>
                                    </span>
                                </div>

                                <ul class="riepilogo-lista">
                                    <li><span>Prezzo sede</span><strong><?php echo money_eur((int) $inventoryItem['sale_price_cents']); ?></strong></li>
                                    <li><span>Stock attuale</span><strong><?php echo (int) $inventoryItem['on_hand_qty']; ?></strong></li>
                                    <li><span>In arrivo</span><strong><?php echo (int) $inventoryItem['pending_supply_qty']; ?></strong></li>
                                </ul>

                                <?php if ($canModifyBranchOperations): ?>
                                    <form method="POST" action="<?php echo htmlspecialchars((string) ($sectionUrls['catalogo'] ?? 'admin_catalogo.php'), ENT_QUOTES, 'UTF-8'); ?>" class="checkout-form">
                                        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8'); ?>">
                                        <input type="hidden" name="action" value="branch_catalog_state">
                                        <input type="hidden" name="product_id" value="<?php echo (int) $inventoryItem['product_id']; ?>">

                                        <fieldset class="checkout-fieldset">
                                            <legend>Stato nel catalogo locale</legend>
                                            <label>
                                                <input type="checkbox" name="is_listed" value="1" <?php echo (int) $inventoryItem['is_listed'] === 1 ? 'checked' : ''; ?>>
                                                Mostra nel catalogo della filiale
                                            </label>
                                            <label>
                                                <input type="checkbox" name="is_available" value="1" <?php echo (int) $inventoryItem['is_listed'] === 1 && (int) $inventoryItem['branch_availability_flag'] === 1 ? 'checked' : ''; ?>>
                                                Segna come disponibile
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
                </article>
            </div>
        </section>
        <?php endif; ?>

        <?php if ($currentSection === 'inventario'): ?>
        <section id="sezione-inventario" class="admin-section" aria-labelledby="titolo-inventario">
            <h2 id="titolo-inventario" class="sr-only">Inventario per unità di prodotto</h2>

            <div class="admin-form-grid">
                <?php if ($canModifyBranchOperations): ?>
                    <form class="checkout-card checkout-form" method="POST" action="<?php echo htmlspecialchars((string) ($sectionUrls['inventario'] ?? 'admin_inventario.php'), ENT_QUOTES, 'UTF-8'); ?>" data-valida novalidate aria-labelledby="titolo-rettifica-inventario">
                        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8'); ?>">
                        <input type="hidden" name="action" value="adjust_inventory">

                        <div class="account-panel-head">
                            <span class="account-panel-kicker">Azione rapida</span>
                            <h3 id="titolo-rettifica-inventario">Rettifica inventario</h3>
                            <p class="checkout-muted">Usa quantità positive per caricare merce, negative per scaricare o correggere manualmente.</p>
                        </div>

                        <div class="campo-gruppo">
                            <label for="inventory-product-id">Prodotto</label>
                            <select id="inventory-product-id" name="product_id" required aria-required="true">
                                <option value="">Seleziona un prodotto</option>
                                <?php foreach ($inventoryItems as $inventoryItem): ?>
                                    <option value="<?php echo (int) $inventoryItem['product_id']; ?>">
                                        <?php echo htmlspecialchars((string) $inventoryItem['product_name'], ENT_QUOTES, 'UTF-8'); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="admin-inline-grid">
                            <div class="campo-gruppo">
                                <label for="inventory-quantity-delta">Variazione quantità</label>
                                <input type="number" id="inventory-quantity-delta" name="quantity_delta" required aria-required="true" step="1">
                            </div>
                            <div class="campo-gruppo">
                                <label for="inventory-unit-cost">Costo unitario centesimi</label>
                                <input type="number" id="inventory-unit-cost" name="unit_cost_cents" min="0" step="1" value="0">
                            </div>
                        </div>

                        <div class="campo-gruppo">
                            <label for="inventory-notes">Nota operativa</label>
                            <textarea id="inventory-notes" name="notes" rows="3"></textarea>
                        </div>

                        <div class="checkout-navigation checkout-navigation--solo-azione">
                            <button class="bottone-primario" type="submit">Salva rettifica</button>
                        </div>
                    </form>
                <?php endif; ?>

                <aside class="checkout-card account-side">
                    <div class="account-panel-head">
                        <span class="account-panel-kicker">Sintesi</span>
                        <h3>Stato stock</h3>
                    </div>
                    <ul class="riepilogo-lista">
                        <li><span>Unità a stock</span><strong><?php echo (int) $kpis['inventory_units']; ?></strong></li>
                        <li><span>Valore inventario</span><strong><?php echo money_eur((int) $kpis['inventory_value_cents']); ?></strong></li>
                        <li><span>Prodotti sotto soglia</span><strong><?php echo (int) $kpis['stock_alerts']; ?></strong></li>
                    </ul>
                    <p class="checkout-muted account-note">
                        La disponibilità online della sede ora dipende anche dallo stock residuo: se un prodotto finisce, non risulta più acquistabile.
                    </p>
                </aside>
            </div>

            <div class="checkout-card admin-panel-card">
                <div class="account-panel-head">
                    <span class="account-panel-kicker">Dettaglio</span>
                    <h3>Disponibilità per prodotto</h3>
                    <p class="checkout-muted">Quantità presenti, merce in arrivo, valore stock e stato rispetto alla soglia automatica.</p>
                </div>

                <div class="admin-table-wrap">
                    <table class="admin-table">
                        <caption class="sr-only">Inventario della filiale per prodotto</caption>
                        <thead>
                            <tr>
                                <th scope="col">Prodotto</th>
                                <th scope="col">Categoria</th>
                                <th scope="col">In sede</th>
                                <th scope="col">In arrivo</th>
                                <th scope="col">Valore stock</th>
                                <th scope="col">Soglia</th>
                                <th scope="col">Stato</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($inventoryItems as $inventoryItem): ?>
                                <tr>
                                    <th scope="row"><?php echo htmlspecialchars((string) $inventoryItem['product_name'], ENT_QUOTES, 'UTF-8'); ?></th>
                                    <td><?php echo htmlspecialchars((string) $inventoryItem['category_name'], ENT_QUOTES, 'UTF-8'); ?></td>
                                    <td><?php echo (int) $inventoryItem['on_hand_qty']; ?></td>
                                    <td><?php echo (int) $inventoryItem['pending_supply_qty']; ?></td>
                                    <td><?php echo money_eur((int) $inventoryItem['stock_value_cents']); ?></td>
                                    <td><?php echo (int) $inventoryItem['threshold_qty']; ?></td>
                                    <td>
                                        <span class="admin-status-pill <?php echo !empty($inventoryItem['is_below_threshold']) ? 'is-warning' : (!empty($inventoryItem['is_available_for_sale']) ? 'is-success' : 'is-muted'); ?>">
                                            <?php if (!empty($inventoryItem['is_below_threshold'])): ?>
                                                Sotto soglia
                                            <?php elseif (!empty($inventoryItem['is_available_for_sale'])): ?>
                                                Disponibile
                                            <?php else: ?>
                                                Non acquistabile
                                            <?php endif; ?>
                                        </span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
        <?php endif; ?>

        <?php if ($currentSection === 'forniture'): ?>
        <section id="sezione-forniture" class="admin-section" aria-labelledby="titolo-forniture">
            <h2 id="titolo-forniture" class="sr-only">Forniture standard e straordinarie</h2>

            <?php if ($canModifyBranchOperations): ?>
                <div class="admin-form-grid admin-form-grid--double">
                    <form class="checkout-card checkout-form" method="POST" action="<?php echo htmlspecialchars((string) ($sectionUrls['forniture'] ?? 'admin_forniture.php'), ENT_QUOTES, 'UTF-8'); ?>" data-valida novalidate aria-labelledby="titolo-template-fornitura">
                        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8'); ?>">
                        <input type="hidden" name="action" value="create_standard_template">

                        <div class="account-panel-head">
                            <span class="account-panel-kicker">Ricorrenza</span>
                            <h3 id="titolo-template-fornitura">Programma una fornitura standard</h3>
                            <p class="checkout-muted">Definisci una cadenza ricorrente e fino a tre prodotti per il template iniziale.</p>
                        </div>

                        <div class="campo-gruppo">
                            <label for="template-name">Nome template</label>
                            <input type="text" id="template-name" name="template_name" required aria-required="true" maxlength="120">
                        </div>

                        <div class="admin-inline-grid">
                            <div class="campo-gruppo">
                                <label for="template-frequency">Frequenza</label>
                                <select id="template-frequency" name="frequency" required aria-required="true">
                                    <?php foreach (supply_frequency_options() as $value => $label): ?>
                                        <option value="<?php echo htmlspecialchars($value, ENT_QUOTES, 'UTF-8'); ?>"><?php echo htmlspecialchars($label, ENT_QUOTES, 'UTF-8'); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="campo-gruppo">
                                <label for="template-next-run">Prima esecuzione</label>
                                <input type="datetime-local" id="template-next-run" name="next_run_at" required aria-required="true">
                            </div>
                        </div>

                        <fieldset class="checkout-fieldset">
                            <legend>Prodotti inclusi</legend>
                            <?php for ($rowIndex = 0; $rowIndex < 3; $rowIndex++): ?>
                                <div class="admin-item-row">
                                    <div class="campo-gruppo">
                                        <label for="template-product-<?php echo $rowIndex; ?>">Prodotto <?php echo $rowIndex + 1; ?></label>
                                        <select id="template-product-<?php echo $rowIndex; ?>" name="template_product_id[]">
                                            <option value="">Seleziona</option>
                                            <?php foreach ($inventoryItems as $inventoryItem): ?>
                                                <option value="<?php echo (int) $inventoryItem['product_id']; ?>">
                                                    <?php echo htmlspecialchars((string) $inventoryItem['product_name'], ENT_QUOTES, 'UTF-8'); ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="campo-gruppo">
                                        <label for="template-quantity-<?php echo $rowIndex; ?>">Quantità</label>
                                        <input type="number" id="template-quantity-<?php echo $rowIndex; ?>" name="template_quantity[]" min="0" step="1">
                                    </div>
                                    <div class="campo-gruppo">
                                        <label for="template-cost-<?php echo $rowIndex; ?>">Costo unitario centesimi</label>
                                        <input type="number" id="template-cost-<?php echo $rowIndex; ?>" name="template_unit_cost_cents[]" min="0" step="1">
                                    </div>
                                </div>
                            <?php endfor; ?>
                        </fieldset>

                        <div class="campo-gruppo">
                            <label for="template-notes">Note</label>
                            <textarea id="template-notes" name="notes" rows="3"></textarea>
                        </div>

                        <div class="checkout-navigation checkout-navigation--solo-azione">
                            <button class="bottone-primario" type="submit">Salva fornitura standard</button>
                        </div>
                    </form>

                    <form class="checkout-card checkout-form" method="POST" action="<?php echo htmlspecialchars((string) ($sectionUrls['forniture'] ?? 'admin_forniture.php'), ENT_QUOTES, 'UTF-8'); ?>" data-valida novalidate aria-labelledby="titolo-extra-fornitura">
                        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8'); ?>">
                        <input type="hidden" name="action" value="create_extra_supply">

                        <div class="account-panel-head">
                            <span class="account-panel-kicker">Una tantum</span>
                            <h3 id="titolo-extra-fornitura">Programma una fornitura straordinaria</h3>
                            <p class="checkout-muted">Crea un ordine manuale fuori template, utile per picchi di domanda o correzioni rapide.</p>
                        </div>

                        <div class="admin-inline-grid">
                            <div class="campo-gruppo">
                                <label for="extra-supplier-name">Fornitore</label>
                                <input type="text" id="extra-supplier-name" name="supplier_name" maxlength="120" value="Centro forniture SmashBurger">
                            </div>
                            <div class="campo-gruppo">
                                <label for="extra-scheduled-for">Consegna prevista</label>
                                <input type="datetime-local" id="extra-scheduled-for" name="scheduled_for">
                            </div>
                        </div>

                        <fieldset class="checkout-fieldset">
                            <legend>Prodotti inclusi</legend>
                            <?php for ($rowIndex = 0; $rowIndex < 3; $rowIndex++): ?>
                                <div class="admin-item-row">
                                    <div class="campo-gruppo">
                                        <label for="extra-product-<?php echo $rowIndex; ?>">Prodotto <?php echo $rowIndex + 1; ?></label>
                                        <select id="extra-product-<?php echo $rowIndex; ?>" name="extra_product_id[]">
                                            <option value="">Seleziona</option>
                                            <?php foreach ($inventoryItems as $inventoryItem): ?>
                                                <option value="<?php echo (int) $inventoryItem['product_id']; ?>">
                                                    <?php echo htmlspecialchars((string) $inventoryItem['product_name'], ENT_QUOTES, 'UTF-8'); ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="campo-gruppo">
                                        <label for="extra-quantity-<?php echo $rowIndex; ?>">Quantità</label>
                                        <input type="number" id="extra-quantity-<?php echo $rowIndex; ?>" name="extra_quantity[]" min="0" step="1">
                                    </div>
                                    <div class="campo-gruppo">
                                        <label for="extra-cost-<?php echo $rowIndex; ?>">Costo unitario centesimi</label>
                                        <input type="number" id="extra-cost-<?php echo $rowIndex; ?>" name="extra_unit_cost_cents[]" min="0" step="1">
                                    </div>
                                </div>
                            <?php endfor; ?>
                        </fieldset>

                        <div class="campo-gruppo">
                            <label for="extra-notes">Note</label>
                            <textarea id="extra-notes" name="notes" rows="3"></textarea>
                        </div>

                        <div class="checkout-navigation checkout-navigation--solo-azione">
                            <button class="bottone-primario" type="submit">Registra fornitura straordinaria</button>
                        </div>
                    </form>
                </div>
            <?php endif; ?>

            <div class="admin-form-grid admin-form-grid--double">
                <article class="checkout-card admin-panel-card">
                    <div class="account-panel-head">
                        <span class="account-panel-kicker">Template</span>
                        <h3>Forniture standard attive</h3>
                        <p class="checkout-muted">Ogni template genera automaticamente una nuova fornitura quando arriva la prossima esecuzione.</p>
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

                <article class="checkout-card admin-panel-card">
                    <div class="account-panel-head">
                        <span class="account-panel-kicker">Ordini fornitura</span>
                        <h3>Storico operativo</h3>
                        <p class="checkout-muted">Bozze, ordini programmati, ricezioni concluse e documenti disponibili.</p>
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

            <div class="admin-form-grid">
                <article class="checkout-card admin-panel-card">
                    <div class="account-panel-head">
                        <span class="account-panel-kicker">Ordine automatico</span>
                        <h3>Politiche di riordino</h3>
                        <p class="checkout-muted">Soglie minime, quantità di riordino e cooldown per evitare duplicazioni di approvvigionamento.</p>
                    </div>

                    <?php if ($canModifyBranchOperations): ?>
                        <form class="checkout-form" method="POST" action="<?php echo htmlspecialchars((string) ($sectionUrls['forniture'] ?? 'admin_forniture.php'), ENT_QUOTES, 'UTF-8'); ?>" data-valida novalidate aria-labelledby="titolo-policy-auto">
                            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8'); ?>">
                            <input type="hidden" name="action" value="create_policy">

                            <div class="account-panel-head">
                                <h4 id="titolo-policy-auto">Configura o aggiorna una policy</h4>
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

                        <form method="POST" action="<?php echo htmlspecialchars((string) ($sectionUrls['forniture'] ?? 'admin_forniture.php'), ENT_QUOTES, 'UTF-8'); ?>" class="admin-inline-form">
                            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8'); ?>">
                            <input type="hidden" name="action" value="run_auto_reorder">
                            <button class="bottone-secondario" type="submit">Esegui controllo ora</button>
                        </form>
                    <?php endif; ?>

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
        <?php if ($canManageBranchManagers): ?>
            <section id="sezione-team" class="admin-section" aria-labelledby="titolo-team-admin">
                <h2 id="titolo-team-admin" class="sr-only">Manager di filiale</h2>

                <div class="checkout-card admin-panel-card">
                    <div class="admin-inline-actions">
                        <a class="bottone-primario" href="admin_team.php?modalita=nuovo">Nuovo manager</a>
                        <?php if ($teamMode !== 'list'): ?>
                            <a class="bottone-secondario" href="admin_team.php">Torna all elenco</a>
                        <?php endif; ?>
                    </div>
                </div>

                <?php if ($teamMode !== 'list'): ?>
                    <form class="checkout-card checkout-form" method="POST" action="<?php echo htmlspecialchars((string) ($sectionUrls['team'] ?? 'admin_team.php'), ENT_QUOTES, 'UTF-8'); ?>" data-valida novalidate aria-labelledby="titolo-manager-form">
                        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8'); ?>">
                        <input type="hidden" name="action" value="save_branch_manager">
                        <?php if ($teamMode === 'edit' && $editingManager !== null): ?>
                            <input type="hidden" name="manager_id" value="<?php echo (int) $editingManager['id']; ?>">
                        <?php endif; ?>

                        <div class="account-panel-head">
                            <span class="account-panel-kicker"><?php echo $teamMode === 'edit' ? 'Modifica manager' : 'Nuovo manager'; ?></span>
                            <h3 id="titolo-manager-form"><?php echo $teamMode === 'edit' ? 'Aggiorna credenziali di filiale' : 'Crea credenziali di filiale'; ?></h3>
                            <p class="checkout-muted">Puoi usare il pattern richiesto: <code>manager.padova</code> / <code>manager_padova</code>.</p>
                        </div>

                        <div class="admin-inline-grid">
                            <div class="campo-gruppo">
                                <label for="manager-username">Username</label>
                                <input type="text" id="manager-username" name="username" required aria-required="true" maxlength="50" value="<?php echo htmlspecialchars((string) ($editingManager['username'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>">
                            </div>
                            <div class="campo-gruppo">
                                <label for="manager-email">Email</label>
                                <input type="email" id="manager-email" name="email" required aria-required="true" maxlength="160" value="<?php echo htmlspecialchars((string) ($editingManager['email'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>">
                            </div>
                        </div>

                        <div class="admin-inline-grid">
                            <div class="campo-gruppo">
                                <label for="manager-password"><?php echo $teamMode === 'edit' ? 'Nuova password' : 'Password'; ?></label>
                                <input type="password" id="manager-password" name="password" <?php echo $teamMode === 'create' ? 'required aria-required="true"' : ''; ?> minlength="8" autocomplete="new-password" placeholder="<?php echo $teamMode === 'edit' ? 'Lascia vuoto per non cambiarla' : 'manager_padova'; ?>">
                            </div>
                            <div class="campo-gruppo">
                                <label for="manager-branch">Filiale</label>
                                <select id="manager-branch" name="managed_branch_id" required aria-required="true">
                                    <option value="">Seleziona la filiale</option>
                                    <?php foreach ($allBranches as $branch): ?>
                                        <option value="<?php echo (int) $branch['id']; ?>" <?php echo (int) $branch['id'] === (int) ($editingManager['managed_branch_id'] ?? 0) ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars((string) $branch['name'], ENT_QUOTES, 'UTF-8'); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <div class="checkout-navigation checkout-navigation--solo-azione">
                            <button class="bottone-primario" type="submit"><?php echo $teamMode === 'edit' ? 'Salva credenziali' : 'Crea manager'; ?></button>
                        </div>
                    </form>
                <?php endif; ?>

                <article class="checkout-card admin-panel-card">
                    <div class="account-panel-head">
                        <span class="account-panel-kicker">Manager correnti</span>
                        <h3>Credenziali attive o revocate</h3>
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
                                        <li><span>Credenziale standard</span><strong><?php echo htmlspecialchars((string) $manager['username'], ENT_QUOTES, 'UTF-8'); ?> / manager_<?php echo htmlspecialchars((string) ($manager['managed_branch_slug'] ?? 'filiale'), ENT_QUOTES, 'UTF-8'); ?></strong></li>
                                    </ul>

                                    <div class="admin-inline-actions">
                                        <a class="bottone-secondario" href="admin_team.php?modifica=<?php echo (int) $manager['id']; ?>">Modifica credenziali</a>
                                        <form method="POST" action="<?php echo htmlspecialchars((string) ($sectionUrls['team'] ?? 'admin_team.php'), ENT_QUOTES, 'UTF-8'); ?>" class="admin-inline-form">
                                            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8'); ?>">
                                            <input type="hidden" name="action" value="toggle_branch_manager">
                                            <input type="hidden" name="manager_id" value="<?php echo (int) $manager['id']; ?>">
                                            <input type="hidden" name="is_active" value="<?php echo (int) $manager['is_active'] === 1 ? '0' : '1'; ?>">
                                            <button class="bottone-secondario" type="submit">
                                                <?php echo (int) $manager['is_active'] === 1 ? 'Revoca accesso' : 'Riattiva accesso'; ?>
                                            </button>
                                        </form>
                                    </div>
                                </article>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </article>
            </section>
        <?php endif; ?>
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
