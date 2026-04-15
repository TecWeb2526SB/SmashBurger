<?php
/**
 * controllo-inventario-rettifica: View dedicata alla rettifica inventario.
 *
 * Variabili attese:
 *   $mode string
 *   $modeMeta array
 *   $modes array
 *   $draft array
 *   $inventoryItems array
 *   $selectedProduct ?array
 *   $inventoryUrl string
 *   $selectedBranchSlug ?string
 *   $isGeneralAdmin bool
 *   $flash ?array
 *   $csrfToken string
 *   $selectedBranch array
 */

$mode = (string) ($mode ?? 'carico');
$modeMeta = is_array($modeMeta ?? null) ? $modeMeta : [];
$modes = is_array($modes ?? null) ? $modes : [];
$draft = is_array($draft ?? null) ? $draft : [];
$inventoryItems = is_array($inventoryItems ?? null) ? $inventoryItems : [];
$selectedProduct = is_array($selectedProduct ?? null) ? $selectedProduct : null;
$inventoryUrl = (string) ($inventoryUrl ?? 'controllo-inventario');
$selectedProductId = max(0, (int) ($draft['product_id'] ?? 0));
$flash = is_array($flash ?? null) ? $flash : null;
$csrfToken = (string) ($csrfToken ?? '');

$currentQty = (int) ($selectedProduct['on_hand_qty'] ?? 0);
$pendingQty = (int) ($selectedProduct['pending_supply_qty'] ?? 0);
$quantityInput = max(0, (int) ($draft['quantity'] ?? 0));
$countedQtyInput = max(0, (int) ($draft['counted_qty'] ?? 0));
$previewDelta = null;
$previewFinalQty = null;
$previewFinalLabel = 'Da calcolare';
$selectedProductDescription = trim((string) ($selectedProduct['description'] ?? ''));

if ($selectedProduct !== null) {
    if ($mode === 'carico' && $quantityInput > 0) {
        $previewDelta = $quantityInput;
        $previewFinalQty = $currentQty + $quantityInput;
        $previewFinalLabel = (string) $previewFinalQty;
    } elseif ($mode === 'scarico' && $quantityInput > 0) {
        $previewDelta = -$quantityInput;
        $previewFinalQty = $currentQty - $quantityInput;
        $previewFinalLabel = $previewFinalQty < 0 ? 'Scorta insufficiente' : (string) $previewFinalQty;
    } elseif ($mode === 'conteggio' && (string) ($draft['counted_qty'] ?? '') !== '') {
        $previewDelta = $countedQtyInput - $currentQty;
        $previewFinalQty = $countedQtyInput;
        $previewFinalLabel = (string) $previewFinalQty;
    }
}
?>

<section class="account-page admin-page" aria-labelledby="titolo-rettifica-inventario-page">
    <div class="contenitore">
        <div class="account-page-head admin-page-head">
            <div class="admin-builder-page-head">
                <span class="account-panel-kicker">Rettifica inventario</span>
                <h1 id="titolo-rettifica-inventario-page"><?php echo htmlspecialchars((string) ($modeMeta['title'] ?? 'Rettifica inventario'), ENT_QUOTES, 'UTF-8'); ?></h1>
                <p class="checkout-muted"><?php echo htmlspecialchars((string) ($modeMeta['description'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></p>
            </div>

            <div class="account-action-row">
                <a class="bottone-secondario" href="<?php echo htmlspecialchars($inventoryUrl, ENT_QUOTES, 'UTF-8'); ?>">&larr; Torna a inventario</a>
            </div>
        </div>

        <?php if (!empty($flash)): ?>
            <div class="alert <?php echo htmlspecialchars((string) ($flash['type'] ?? 'info'), ENT_QUOTES, 'UTF-8'); ?>">
                <?php echo htmlspecialchars((string) ($flash['message'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>
            </div>
        <?php endif; ?>

        <div class="checkout-shell admin-inventory-adjustment-shell">
            <div class="checkout-card checkout-form checkout-main admin-inventory-adjustment-main">
                <div class="account-panel-head">
                    <span class="account-panel-kicker">Workflow</span>
                    <h2>Scegli il tipo di intervento</h2>
                    <p class="checkout-muted">Ogni modalità apre solo i campi necessari. Il sistema applica automaticamente i costi interni quando il movimento aumenta lo stock.</p>
                </div>

                <nav class="admin-adjustment-mode-nav" aria-label="Modalità rettifica inventario">
                    <?php foreach ($modes as $modeKey => $modeItem): ?>
                        <a
                            class="<?php echo $modeKey === $mode ? 'is-active' : ''; ?>"
                            href="<?php echo htmlspecialchars(admin_inventory_adjustment_url($selectedBranchSlug, $isGeneralAdmin, (string) $modeKey, $selectedProductId > 0 ? $selectedProductId : null), ENT_QUOTES, 'UTF-8'); ?>">
                            <span><?php echo htmlspecialchars((string) ($modeItem['label'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></span>
                            <strong><?php echo htmlspecialchars((string) ($modeItem['title'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></strong>
                        </a>
                    <?php endforeach; ?>
                </nav>

                <form method="POST" action="<?php echo htmlspecialchars(admin_inventory_adjustment_url($selectedBranchSlug, $isGeneralAdmin, $mode, $selectedProductId > 0 ? $selectedProductId : null), ENT_QUOTES, 'UTF-8'); ?>" class="admin-workflow-form" data-valida novalidate aria-labelledby="titolo-rettifica-inventario-page">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8'); ?>">
                    <input type="hidden" name="action" value="apply_inventory_adjustment">
                    <input type="hidden" name="mode" value="<?php echo htmlspecialchars($mode, ENT_QUOTES, 'UTF-8'); ?>">

                    <div class="admin-workflow-canvas" aria-label="Canvas rettifica inventario">
                        <details class="admin-workflow-block admin-workflow-block--trigger" open>
                            <summary class="admin-workflow-block-summary">
                                <span class="admin-workflow-node-type">Prodotto</span>
                                <h3>Seleziona la referenza di sede</h3>
                                <p>Lavora su una sola referenza per volta e tieni il riepilogo operativo sempre visibile nella colonna laterale.</p>
                                <ul class="admin-workflow-chip-list">
                                    <li>Prodotto</li>
                                    <li>Scorta attuale</li>
                                    <li>Merce in arrivo</li>
                                </ul>
                            </summary>
                            <div class="admin-workflow-block-body">
                                <div class="campo-gruppo">
                                    <label for="inventory-adjustment-product-id">Prodotto</label>
                                    <select id="inventory-adjustment-product-id" name="product_id" required aria-required="true">
                                        <option value="">Seleziona un prodotto</option>
                                        <?php foreach ($inventoryItems as $inventoryItem): ?>
                                            <option value="<?php echo (int) $inventoryItem['product_id']; ?>" <?php echo (int) ($inventoryItem['product_id'] ?? 0) === $selectedProductId ? 'selected' : ''; ?>>
                                                <?php echo htmlspecialchars((string) $inventoryItem['product_name'], ENT_QUOTES, 'UTF-8'); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <?php if ($selectedProduct !== null): ?>
                                    <div class="admin-adjustment-inline-summary" aria-label="Sintesi prodotto selezionato">
                                        <article>
                                            <span>In sede ora</span>
                                            <strong><?php echo $currentQty; ?></strong>
                                        </article>
                                        <article>
                                            <span>In arrivo</span>
                                            <strong><?php echo $pendingQty; ?></strong>
                                        </article>
                                        <article>
                                            <span>Soglia</span>
                                            <strong><?php echo (int) ($selectedProduct['threshold_qty'] ?? 0); ?></strong>
                                        </article>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </details>

                        <div class="admin-workflow-link" aria-hidden="true"></div>

                        <details class="admin-workflow-block admin-workflow-block--action" open>
                            <summary class="admin-workflow-block-summary">
                                <span class="admin-workflow-node-type"><?php echo $mode === 'conteggio' ? 'Conteggio' : 'Quantità'; ?></span>
                                <h3>Inserisci il dato operativo</h3>
                                <p>
                                    <?php if ($mode === 'carico'): ?>
                                        Registra quante unità entrano in magazzino. Il costo medio viene risolto automaticamente dal sistema.
                                    <?php elseif ($mode === 'scarico'): ?>
                                        Registra quante unità escono dal magazzino per scarto, reso o rettifica negativa.
                                    <?php else: ?>
                                        Inserisci la quantità reale rilevata e lascia al sistema il calcolo della differenza da applicare.
                                    <?php endif; ?>
                                </p>
                                <ul class="admin-workflow-chip-list">
                                    <?php if ($mode === 'conteggio'): ?>
                                        <li>Quantità reale finale</li>
                                        <li>Delta calcolato dal sistema</li>
                                    <?php else: ?>
                                        <li>Quantità positiva</li>
                                        <li>Nota opzionale</li>
                                    <?php endif; ?>
                                </ul>
                            </summary>
                            <div class="admin-workflow-block-body">
                                <?php if ($mode === 'conteggio'): ?>
                                    <div class="campo-gruppo">
                                        <label for="inventory-counted-qty">Quantità trovata in sede</label>
                                        <input type="number" id="inventory-counted-qty" name="counted_qty" min="0" step="1" value="<?php echo htmlspecialchars((string) ($draft['counted_qty'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>" required aria-required="true">
                                    </div>
                                <?php else: ?>
                                    <div class="campo-gruppo">
                                        <label for="inventory-adjustment-quantity"><?php echo $mode === 'carico' ? 'Unità da aggiungere' : 'Unità da rimuovere'; ?></label>
                                        <input type="number" id="inventory-adjustment-quantity" name="quantity" min="1" step="1" value="<?php echo htmlspecialchars((string) ($draft['quantity'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>" required aria-required="true">
                                    </div>
                                <?php endif; ?>

                                <div class="campo-gruppo">
                                    <label for="inventory-adjustment-notes">Nota operativa</label>
                                    <textarea id="inventory-adjustment-notes" name="notes" rows="3" placeholder="Es. ricezione fornitura, merce deteriorata, conteggio fine turno"><?php echo htmlspecialchars((string) ($draft['notes'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></textarea>
                                </div>
                            </div>
                        </details>

                        <div class="admin-workflow-link" aria-hidden="true"></div>

                        <details class="admin-workflow-block admin-workflow-block--confirm" open>
                            <summary class="admin-workflow-block-summary">
                                <span class="admin-workflow-node-type">Conferma</span>
                                <h3>Verifica l'effetto finale</h3>
                                <p>Prima di salvare, controlla come cambia la giacenza locale e completa l'azione con un solo invio.</p>
                                <ul class="admin-workflow-chip-list">
                                    <li>Delta previsto</li>
                                    <li>Nuova giacenza</li>
                                    <li>Invio finale</li>
                                </ul>
                            </summary>
                            <div class="admin-workflow-block-body">
                                <div class="admin-adjustment-preview-grid" aria-label="Anteprima rettifica">
                                    <article>
                                        <span>Modalità</span>
                                        <strong><?php echo htmlspecialchars((string) ($modeMeta['label'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></strong>
                                    </article>
                                    <article>
                                        <span>Delta previsto</span>
                                        <strong>
                                            <?php if ($previewDelta === null): ?>
                                                In attesa di dati
                                            <?php else: ?>
                                                <?php echo $previewDelta > 0 ? '+' : ''; ?><?php echo $previewDelta; ?>
                                            <?php endif; ?>
                                        </strong>
                                    </article>
                                    <article>
                                        <span>Giacenza finale</span>
                                        <strong><?php echo htmlspecialchars($previewFinalLabel, ENT_QUOTES, 'UTF-8'); ?></strong>
                                    </article>
                                </div>

                                <div class="checkout-navigation checkout-navigation--solo-azione">
                                    <button class="bottone-primario" type="submit"><?php echo htmlspecialchars((string) ($modeMeta['submit_label'] ?? 'Conferma'), ENT_QUOTES, 'UTF-8'); ?></button>
                                </div>
                            </div>
                        </details>
                    </div>
                </form>
            </div>

            <aside class="checkout-card account-side admin-inventory-adjustment-side">
                <div class="account-panel-head">
                    <span class="account-panel-kicker">Contesto</span>
                    <h3>Stato operativo della sede</h3>
                    <p class="checkout-muted">Il flusso resta concentrato su una sola referenza, ma qui hai sempre il contesto di prezzo, costi e copertura.</p>
                </div>

                <div class="admin-builder-side-stack">
                    <?php if ($selectedProduct !== null): ?>
                        <article class="admin-builder-side-card">
                            <span class="account-panel-kicker">Prodotto selezionato</span>
                            <h3><?php echo htmlspecialchars((string) ($selectedProduct['product_name'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></h3>
                            <p class="checkout-muted"><?php echo htmlspecialchars($selectedProductDescription !== '' ? $selectedProductDescription : 'Scheda cliente senza descrizione estesa.', ENT_QUOTES, 'UTF-8'); ?></p>
                        </article>

                        <article class="admin-builder-side-card">
                            <ul class="riepilogo-lista">
                                <li><span>In sede</span><strong><?php echo $currentQty; ?></strong></li>
                                <li><span>In arrivo</span><strong><?php echo $pendingQty; ?></strong></li>
                                <li><span>Prezzo filiale</span><strong><?php echo money_eur((int) ($selectedProduct['sale_price_cents'] ?? 0)); ?></strong></li>
                                <li><span>Costo medio</span><strong><?php echo money_eur((int) ($selectedProduct['average_unit_cost_cents'] ?? 0)); ?></strong></li>
                                <li><span>Valore stock</span><strong><?php echo money_eur((int) ($selectedProduct['stock_value_cents'] ?? 0)); ?></strong></li>
                            </ul>
                        </article>
                    <?php else: ?>
                        <article class="admin-builder-side-card">
                            <span class="account-panel-kicker">Nessuna referenza</span>
                            <h3>Scegli prima un prodotto</h3>
                            <p class="checkout-muted">Il riepilogo laterale si popola appena selezioni una referenza della filiale dal primo blocco del workflow.</p>
                        </article>
                    <?php endif; ?>

                    <article class="admin-builder-side-card">
                        <span class="account-panel-kicker">Regola operativa</span>
                        <h3>Il manager non inserisce il costo</h3>
                        <p class="checkout-muted">Quando il movimento aumenta lo stock, il sistema usa automaticamente il costo medio o il costo di approvvigionamento già definito per la filiale.</p>
                    </article>
                </div>
            </aside>
        </div>
    </div>
</section>
