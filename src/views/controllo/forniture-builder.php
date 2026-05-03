<?php
/**
 * admin_forniture_builder.php: View dedicata ai builder forniture.
 *
 * Variabili attese:
 *   $builderKey string
 *   $builderMeta array
 *   $builderUrl string
 *   $hubUrl string
 *   $flash ?array
 *   $csrfToken string
 *   $draft array
 *   $inventoryItems array
 *   $templates array
 *   $supplyOrders array
 *   $policies array
 */

$builderKey = $builderKey ?? 'standard';
$builderMeta = is_array($builderMeta ?? null) ? $builderMeta : admin_supply_builder_meta($builderKey);
$builderUrl = (string) ($builderUrl ?? admin_supply_builder_url($builderKey, null, false));
$hubUrl = (string) ($hubUrl ?? 'controllo-forniture');
$flash = is_array($flash ?? null) ? $flash : null;
$csrfToken = $csrfToken ?? '';
$draft = is_array($draft ?? null) ? $draft : [];
$inventoryItems = $inventoryItems ?? [];
$templates = $templates ?? [];
$supplyOrders = $supplyOrders ?? [];
$policies = $policies ?? [];

$activeTemplatesCount = 0;
foreach ($templates as $templateSummary) {
    if ((int) ($templateSummary['is_active'] ?? 0) === 1) {
        $activeTemplatesCount++;
    }
}

$openSupplyOrdersCount = 0;
$scheduledSupplyOrdersCount = 0;
foreach ($supplyOrders as $supplyOrderSummary) {
    $supplyOrderStatus = (string) ($supplyOrderSummary['status'] ?? '');
    if (in_array($supplyOrderStatus, ['draft', 'scheduled', 'ordered'], true)) {
        $openSupplyOrdersCount++;
    }

    if ($supplyOrderStatus === 'scheduled') {
        $scheduledSupplyOrdersCount++;
    }
}

$activePoliciesCount = 0;
foreach ($policies as $policySummary) {
    if ((int) ($policySummary['is_active'] ?? 0) === 1) {
        $activePoliciesCount++;
    }
}

$productsWithSupplyCostCount = 0;
$productsWithoutSupplyCostCount = 0;
foreach ($inventoryItems as $inventoryItemSummary) {
    if (!empty($inventoryItemSummary['has_supply_unit_cost'])) {
        $productsWithSupplyCostCount++;
    } else {
        $productsWithoutSupplyCostCount++;
    }
}

$frequencyOptions = supply_frequency_options();
?>

<section class="account-page admin-page" aria-labelledby="titolo-builder-forniture">
    <div class="contenitore">
        <div class="account-page-head admin-page-head">
            <div class="admin-builder-page-head">
                <span class="account-panel-kicker"><?php echo e((string) ($builderMeta['kicker'] ?? 'Builder')); ?></span>
                <h1 id="titolo-builder-forniture"><?php echo e((string) ($builderMeta['title'] ?? 'Builder forniture')); ?></h1>
                <p class="checkout-muted"><?php echo e((string) ($builderMeta['description'] ?? '')); ?></p>
            </div>

            <div class="account-action-row">
                <a class="bottone-secondario" href="<?php echo e($hubUrl); ?>">&larr; Torna a forniture</a>
            </div>
        </div>

        <?php echo ui_alert($flash); ?>

        <?php if ($productsWithoutSupplyCostCount > 0 && $builderKey !== 'automatic'): ?>
            <div class="alert info">
                <?php echo (int) $productsWithoutSupplyCostCount; ?>
                prodotti non hanno ancora un costo filiale disponibile e non sono selezionabili in questo builder.
            </div>
        <?php endif; ?>

        <div class="checkout-shell admin-supply-builder-shell">
            <div class="checkout-card checkout-form checkout-main admin-supply-builder-main">
                <div class="account-panel-head">
                    <span class="account-panel-kicker">Canvas</span>
                    <h2>Costruisci il flusso blocco per blocco</h2>
                    <p class="checkout-muted">I costi di approvvigionamento vengono applicati automaticamente dal database della filiale. Qui scegli solo trigger, prodotti, quantità e regole operative.</p>
                </div>

                <?php if ($builderKey === 'standard'): ?>
                    <form method="POST" action="<?php echo e($builderUrl); ?>" class="admin-workflow-form" data-valida novalidate aria-labelledby="titolo-builder-standard">
                        <input type="hidden" name="csrf_token" value="<?php echo e($csrfToken); ?>">
                        <input type="hidden" name="action" value="create_standard_template">

                        <div class="admin-workflow-canvas" aria-label="Canvas routine ricorrente">
                            <details class="admin-workflow-block admin-workflow-block--trigger" open>
                                <summary class="admin-workflow-block-summary">
                                    <span class="admin-workflow-node-type">Quando</span>
                                    <h3 id="titolo-builder-standard">Programma la routine</h3>
                                    <p>Definisci identità del flusso, cadenza e primo avvio del template ricorrente.</p>
                                    <ul class="admin-workflow-chip-list">
                                        <li>Nome template</li>
                                        <li>Frequenza</li>
                                        <li>Prima esecuzione</li>
                                    </ul>
                                </summary>
                                <div class="admin-workflow-block-body">
                                    <?php echo ui_form_group('standard-template-name', 'Nome template', 'text', [
                                        'value' => $draft['template_name'] ?? '',
                                        'extra_attrs' => 'name="template_name" maxlength="120"'
                                    ]); ?>

                                    <div class="admin-inline-grid">
                                        <div class="campo-gruppo">
                                            <label for="standard-frequency">Frequenza</label>
                                            <select id="standard-frequency" name="frequency" required aria-required="true">
                                                <?php foreach ($frequencyOptions as $value => $label): ?>
                                                    <option value="<?php echo e($value); ?>" <?php echo (string) ($draft['frequency'] ?? 'weekly') === $value ? 'selected' : ''; ?>>
                                                        <?php echo e($label); ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>

                                        <?php echo ui_form_group('standard-next-run', 'Prima esecuzione', 'datetime-local', [
                                            'value' => $draft['next_run_at'] ?? '',
                                            'extra_attrs' => 'name="next_run_at"'
                                        ]); ?>
                                    </div>
                                </div>
                            </details>

                            <div class="admin-workflow-link" aria-hidden="true"></div>

                            <details class="admin-workflow-block admin-workflow-block--action" open>
                                <summary class="admin-workflow-block-summary">
                                    <span class="admin-workflow-node-type">Azione</span>
                                    <h3>Componi i prodotti</h3>
                                    <p>Aggiungi o rimuovi righe con i controlli + e x. Il costo della sede viene applicato in automatico sulla singola referenza.</p>
                                    <ul class="admin-workflow-chip-list">
                                        <li>Prodotti illimitati</li>
                                        <li>Quantita per riga</li>
                                        <li>Costo filiale automatico</li>
                                    </ul>
                                </summary>
                                <div class="admin-workflow-block-body">
                                    <div class="admin-repeatable-list" data-repeatable-list="standard-items" data-next-index="<?php echo count((array) ($draft['items'] ?? [])); ?>">
                                        <div class="admin-repeatable-row-list" data-repeatable-rows>
                                            <?php foreach (($draft['items'] ?? admin_supply_item_rows_default()) as $rowIndex => $row): ?>
                                                <article class="admin-repeatable-row" data-repeatable-row>
                                                    <div class="admin-repeatable-row-head">
                                                        <strong class="admin-repeatable-row-index" data-repeatable-label data-label-prefix="Prodotto">Prodotto <?php echo $rowIndex + 1; ?></strong>
                                                        <button class="admin-repeatable-remove" type="button" data-repeatable-remove aria-label="Rimuovi riga prodotto">x</button>
                                                    </div>

                                                    <div class="admin-inline-grid">
                                                        <div class="campo-gruppo">
                                                            <label for="standard-product-<?php echo $rowIndex; ?>">Referenza</label>
                                                            <select id="standard-product-<?php echo $rowIndex; ?>" name="template_product_id[]" data-product-select>
                                                                <option value="">Seleziona un prodotto</option>
                                                                <?php foreach ($inventoryItems as $inventoryItem): ?>
                                                                    <?php
                                                                    $selectedProductId = (string) ($row['product_id'] ?? '');
                                                                    $optionProductId = (string) ((int) $inventoryItem['product_id']);
                                                                    $hasSupplyCost = !empty($inventoryItem['has_supply_unit_cost']);
                                                                    ?>
                                                                    <option
                                                                        value="<?php echo e($optionProductId); ?>"
                                                                        data-unit-cost="<?php echo (int) ($inventoryItem['supply_unit_cost_cents'] ?? 0); ?>"
                                                                        <?php echo $selectedProductId === $optionProductId ? 'selected' : ''; ?>
                                                                        <?php echo !$hasSupplyCost ? 'disabled' : ''; ?>>
                                                                        <?php echo e((string) $inventoryItem['product_name']); ?>
                                                                        <?php echo !$hasSupplyCost ? ' (costo da inizializzare)' : ''; ?>
                                                                    </option>
                                                                <?php endforeach; ?>
                                                            </select>
                                                        </div>

                                                        <?php echo ui_form_group('standard-quantity-' . $rowIndex, 'Quantità', 'number', [
                                                            'value' => $row['quantity'] ?? '',
                                                            'extra_attrs' => 'name="template_quantity[]" min="1" step="1"',
                                                            'required' => false
                                                        ]); ?>
                                                    </div>

                                                    <p
                                                        class="checkout-muted admin-supply-cost-note"
                                                        data-product-cost-output
                                                        data-default-message="Il costo della filiale verrà applicato automaticamente quando scegli la referenza."
                                                        data-missing-message="Questa referenza non ha ancora un costo filiale disponibile.">
                                                        Il costo della filiale verrà applicato automaticamente quando scegli la referenza.
                                                    </p>
                                                </article>
                                            <?php endforeach; ?>
                                        </div>

                                        <template data-repeatable-template>
                                            <article class="admin-repeatable-row" data-repeatable-row>
                                                <div class="admin-repeatable-row-head">
                                                    <strong class="admin-repeatable-row-index" data-repeatable-label data-label-prefix="Prodotto">Prodotto</strong>
                                                    <button class="admin-repeatable-remove" type="button" data-repeatable-remove aria-label="Rimuovi riga prodotto">x</button>
                                                </div>

                                                <div class="admin-inline-grid">
                                                    <div class="campo-gruppo">
                                                        <label for="standard-product-__INDEX__">Referenza</label>
                                                        <select id="standard-product-__INDEX__" name="template_product_id[]" data-product-select>
                                                            <option value="">Seleziona un prodotto</option>
                                                            <?php foreach ($inventoryItems as $inventoryItem): ?>
                                                                <option
                                                                    value="<?php echo (int) $inventoryItem['product_id']; ?>"
                                                                    data-unit-cost="<?php echo (int) ($inventoryItem['supply_unit_cost_cents'] ?? 0); ?>"
                                                                    <?php echo empty($inventoryItem['has_supply_unit_cost']) ? 'disabled' : ''; ?>>
                                                                    <?php echo e((string) $inventoryItem['product_name']); ?>
                                                                    <?php echo empty($inventoryItem['has_supply_unit_cost']) ? ' (costo da inizializzare)' : ''; ?>
                                                                </option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                    </div>

                                                    <?php echo ui_form_group('standard-quantity-__INDEX__', 'Quantità', 'number', [
                                                        'extra_attrs' => 'name="template_quantity[]" min="1" step="1"',
                                                        'required' => false
                                                    ]); ?>
                                                </div>

                                                <p
                                                    class="checkout-muted admin-supply-cost-note"
                                                    data-product-cost-output
                                                    data-default-message="Il costo della filiale verrà applicato automaticamente quando scegli la referenza."
                                                    data-missing-message="Questa referenza non ha ancora un costo filiale disponibile.">
                                                    Il costo della filiale verrà applicato automaticamente quando scegli la referenza.
                                                </p>
                                            </article>
                                        </template>

                                        <button class="bottone-secondario admin-repeatable-add" type="button" data-repeatable-add>+ Aggiungi prodotto</button>
                                    </div>
                                </div>
                            </details>

                            <div class="admin-workflow-link" aria-hidden="true"></div>

                            <details class="admin-workflow-block admin-workflow-block--confirm" open>
                                <summary class="admin-workflow-block-summary">
                                    <span class="admin-workflow-node-type">Output</span>
                                    <h3>Chiudi e salva il template</h3>
                                    <p>Lascia una nota per il team e genera un template riutilizzabile nella dashboard forniture.</p>
                                    <ul class="admin-workflow-chip-list">
                                        <li>Note operative</li>
                                        <li>Salvataggio immediato</li>
                                    </ul>
                                </summary>
                                <div class="admin-workflow-block-body">
                                    <?php echo ui_form_group('standard-notes', 'Note', 'textarea', [
                                        'value' => $draft['notes'] ?? '',
                                        'placeholder' => 'Es. carico del lunedi con buns, salse e packaging base',
                                        'extra_attrs' => 'name="notes" rows="4"',
                                        'required' => false
                                    ]); ?>

                                    <div class="checkout-navigation">
                                        <a class="bottone-secondario" href="<?php echo e($hubUrl); ?>">&larr; Torna alla dashboard</a>
                                        <button class="bottone-primario" type="submit"><?php echo e((string) ($builderMeta['submit_label'] ?? 'Salva')); ?></button>
                                    </div>
                                </div>
                            </details>
                        </div>
                    </form>
                <?php elseif ($builderKey === 'extra'): ?>
                    <form method="POST" action="<?php echo e($builderUrl); ?>" class="admin-workflow-form" data-valida novalidate aria-labelledby="titolo-builder-straordinario">
                        <input type="hidden" name="csrf_token" value="<?php echo e($csrfToken); ?>">
                        <input type="hidden" name="action" value="create_extra_supply">

                        <div class="admin-workflow-canvas" aria-label="Canvas intervento una tantum">
                            <details class="admin-workflow-block admin-workflow-block--trigger" open>
                                <summary class="admin-workflow-block-summary">
                                    <span class="admin-workflow-node-type">Avvio</span>
                                    <h3 id="titolo-builder-straordinario">Apri la fornitura urgente</h3>
                                    <p>Definisci fornitore e consegna prevista solo se servono davvero per questo intervento.</p>
                                    <ul class="admin-workflow-chip-list">
                                        <li>Fornitore</li>
                                        <li>Consegna prevista</li>
                                    </ul>
                                </summary>
                                <div class="admin-workflow-block-body">
                                    <div class="admin-inline-grid">
                                        <?php echo ui_form_group('extra-supplier-name', 'Fornitore', 'text', [
                                            'value' => $draft['supplier_name'] ?? 'Centro forniture SmashBurger',
                                            'extra_attrs' => 'name="supplier_name" maxlength="120"',
                                            'required' => false
                                        ]); ?>

                                        <?php echo ui_form_group('extra-scheduled-for', 'Consegna prevista', 'datetime-local', [
                                            'value' => $draft['scheduled_for'] ?? '',
                                            'extra_attrs' => 'name="scheduled_for"',
                                            'required' => false
                                        ]); ?>
                                    </div>
                                </div>
                            </details>

                            <div class="admin-workflow-link" aria-hidden="true"></div>

                            <details class="admin-workflow-block admin-workflow-block--action" open>
                                <summary class="admin-workflow-block-summary">
                                    <span class="admin-workflow-node-type">Azione</span>
                                    <h3>Compila le righe necessarie</h3>
                                    <p>Per le urgenze non ci sono limiti fissi: aggiungi solo le referenze che servono davvero e lascia al sistema il costo della filiale.</p>
                                    <ul class="admin-workflow-chip-list">
                                        <li>Righe dinamiche</li>
                                        <li>Quantita</li>
                                        <li>Costo automatico</li>
                                    </ul>
                                </summary>
                                <div class="admin-workflow-block-body">
                                    <div class="admin-repeatable-list" data-repeatable-list="extra-items" data-next-index="<?php echo count((array) ($draft['items'] ?? [])); ?>">
                                        <div class="admin-repeatable-row-list" data-repeatable-rows>
                                            <?php foreach (($draft['items'] ?? admin_supply_item_rows_default()) as $rowIndex => $row): ?>
                                                <article class="admin-repeatable-row" data-repeatable-row>
                                                    <div class="admin-repeatable-row-head">
                                                        <strong class="admin-repeatable-row-index" data-repeatable-label data-label-prefix="Prodotto">Prodotto <?php echo $rowIndex + 1; ?></strong>
                                                        <button class="admin-repeatable-remove" type="button" data-repeatable-remove aria-label="Rimuovi riga prodotto">x</button>
                                                    </div>

                                                    <div class="admin-inline-grid">
                                                        <div class="campo-gruppo">
                                                            <label for="extra-product-<?php echo $rowIndex; ?>">Referenza</label>
                                                            <select id="extra-product-<?php echo $rowIndex; ?>" name="extra_product_id[]" data-product-select>
                                                                <option value="">Seleziona un prodotto</option>
                                                                <?php foreach ($inventoryItems as $inventoryItem): ?>
                                                                    <?php
                                                                    $selectedProductId = (string) ($row['product_id'] ?? '');
                                                                    $optionProductId = (string) ((int) $inventoryItem['product_id']);
                                                                    $hasSupplyCost = !empty($inventoryItem['has_supply_unit_cost']);
                                                                    ?>
                                                                    <option
                                                                        value="<?php echo e($optionProductId); ?>"
                                                                        data-unit-cost="<?php echo (int) ($inventoryItem['supply_unit_cost_cents'] ?? 0); ?>"
                                                                        <?php echo $selectedProductId === $optionProductId ? 'selected' : ''; ?>
                                                                        <?php echo !$hasSupplyCost ? 'disabled' : ''; ?>>
                                                                        <?php echo e((string) $inventoryItem['product_name']); ?>
                                                                        <?php echo !$hasSupplyCost ? ' (costo da inizializzare)' : ''; ?>
                                                                    </option>
                                                                <?php endforeach; ?>
                                                            </select>
                                                        </div>

                                                        <?php echo ui_form_group('extra-quantity-' . $rowIndex, 'Quantità', 'number', [
                                                            'value' => $row['quantity'] ?? '',
                                                            'extra_attrs' => 'name="extra_quantity[]" min="1" step="1"',
                                                            'required' => false
                                                        ]); ?>
                                                    </div>

                                                    <p
                                                        class="checkout-muted admin-supply-cost-note"
                                                        data-product-cost-output
                                                        data-default-message="Il costo della filiale verrà applicato automaticamente quando scegli la referenza."
                                                        data-missing-message="Questa referenza non ha ancora un costo filiale disponibile.">
                                                        Il costo della filiale verrà applicato automaticamente quando scegli la referenza.
                                                    </p>
                                                </article>
                                            <?php endforeach; ?>
                                        </div>

                                        <template data-repeatable-template>
                                            <article class="admin-repeatable-row" data-repeatable-row>
                                                <div class="admin-repeatable-row-head">
                                                    <strong class="admin-repeatable-row-index" data-repeatable-label data-label-prefix="Prodotto">Prodotto</strong>
                                                    <button class="admin-repeatable-remove" type="button" data-repeatable-remove aria-label="Rimuovi riga prodotto">x</button>
                                                </div>

                                                <div class="admin-inline-grid">
                                                    <div class="campo-gruppo">
                                                        <label for="extra-product-__INDEX__">Referenza</label>
                                                        <select id="extra-product-__INDEX__" name="extra_product_id[]" data-product-select>
                                                            <option value="">Seleziona un prodotto</option>
                                                            <?php foreach ($inventoryItems as $inventoryItem): ?>
                                                                <option
                                                                    value="<?php echo (int) $inventoryItem['product_id']; ?>"
                                                                    data-unit-cost="<?php echo (int) ($inventoryItem['supply_unit_cost_cents'] ?? 0); ?>"
                                                                    <?php echo empty($inventoryItem['has_supply_unit_cost']) ? 'disabled' : ''; ?>>
                                                                    <?php echo e((string) $inventoryItem['product_name']); ?>
                                                                    <?php echo empty($inventoryItem['has_supply_unit_cost']) ? ' (costo da inizializzare)' : ''; ?>
                                                                </option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                    </div>

                                                    <?php echo ui_form_group('extra-quantity-__INDEX__', 'Quantità', 'number', [
                                                        'extra_attrs' => 'name="extra_quantity[]" min="1" step="1"',
                                                        'required' => false
                                                    ]); ?>
                                                </div>

                                                <p
                                                    class="checkout-muted admin-supply-cost-note"
                                                    data-product-cost-output
                                                    data-default-message="Il costo della filiale verrà applicato automaticamente quando scegli la referenza."
                                                    data-missing-message="Questa referenza non ha ancora un costo filiale disponibile.">
                                                    Il costo della filiale verrà applicato automaticamente quando scegli la referenza.
                                                </p>
                                            </article>
                                        </template>

                                        <button class="bottone-secondario admin-repeatable-add" type="button" data-repeatable-add>+ Aggiungi prodotto</button>
                                    </div>
                                </div>
                            </details>

                            <div class="admin-workflow-link" aria-hidden="true"></div>

                            <details class="admin-workflow-block admin-workflow-block--confirm" open>
                                <summary class="admin-workflow-block-summary">
                                    <span class="admin-workflow-node-type">Output</span>
                                    <h3>Registra l'ordine operativo</h3>
                                    <p>Aggiungi la nota finale e salva la fornitura straordinaria nello storico della filiale.</p>
                                    <ul class="admin-workflow-chip-list">
                                        <li>Nota interna</li>
                                        <li>Storico forniture</li>
                                    </ul>
                                </summary>
                                <div class="admin-workflow-block-body">
                                    <?php echo ui_form_group('extra-notes', 'Note', 'textarea', [
                                        'value' => $draft['notes'] ?? '',
                                        'placeholder' => 'Es. integrazione urgente per weekend o sostituzione merce danneggiata',
                                        'extra_attrs' => 'name="notes" rows="4"',
                                        'required' => false
                                    ]); ?>

                                    <div class="checkout-navigation">
                                        <a class="bottone-secondario" href="<?php echo e($hubUrl); ?>">&larr; Torna alla dashboard</a>
                                        <button class="bottone-primario" type="submit"><?php echo e((string) ($builderMeta['submit_label'] ?? 'Salva')); ?></button>
                                    </div>
                                </div>
                            </details>
                        </div>
                    </form>
                <?php else: ?>
                    <form method="POST" action="<?php echo e($builderUrl); ?>" class="admin-workflow-form" data-valida novalidate aria-labelledby="titolo-builder-automatico">
                        <input type="hidden" name="csrf_token" value="<?php echo e($csrfToken); ?>">
                        <input type="hidden" name="action" value="create_policy">

                        <div class="admin-workflow-canvas" aria-label="Canvas automazione stock">
                            <details class="admin-workflow-block admin-workflow-block--trigger" open>
                                <summary class="admin-workflow-block-summary">
                                    <span class="admin-workflow-node-type">Trigger</span>
                                    <h3 id="titolo-builder-automatico">Sorveglia una referenza</h3>
                                    <p>Seleziona il prodotto e imposta la soglia che attiva il controllo automatico.</p>
                                    <ul class="admin-workflow-chip-list">
                                        <li>Prodotto</li>
                                        <li>Soglia minima</li>
                                    </ul>
                                </summary>
                                <div class="admin-workflow-block-body">
                                    <div class="admin-inline-grid">
                                        <div class="campo-gruppo">
                                            <label for="auto-product-id">Prodotto</label>
                                            <select id="auto-product-id" name="product_id" required aria-required="true">
                                                <option value="">Seleziona una referenza</option>
                                                <?php foreach ($inventoryItems as $inventoryItem): ?>
                                                    <option value="<?php echo (int) $inventoryItem['product_id']; ?>" <?php echo (string) ($draft['product_id'] ?? '') === (string) ((int) $inventoryItem['product_id']) ? 'selected' : ''; ?>>
                                                        <?php echo e((string) $inventoryItem['product_name']); ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>

                                        <?php echo ui_form_group('auto-threshold', 'Soglia minima', 'number', [
                                            'value' => $draft['threshold_qty'] ?? '',
                                            'extra_attrs' => 'name="threshold_qty" min="1" step="1"'
                                        ]); ?>
                                    </div>
                                </div>
                            </details>

                            <div class="admin-workflow-link" aria-hidden="true"></div>

                            <details class="admin-workflow-block admin-workflow-block--condition" open>
                                <summary class="admin-workflow-block-summary">
                                    <span class="admin-workflow-node-type">Controllo</span>
                                    <h3>Evita riordini ridondanti</h3>
                                    <p>Applica finestra di cooldown e limite massimo di merce già in arrivo prima di generare nuove azioni.</p>
                                    <ul class="admin-workflow-chip-list">
                                        <li>Cooldown</li>
                                        <li>Massimo in arrivo</li>
                                    </ul>
                                </summary>
                                <div class="admin-workflow-block-body">
                                    <div class="admin-inline-grid">
                                        <?php echo ui_form_group('auto-cooldown', 'Cooldown ore', 'number', [
                                            'value' => $draft['cooldown_hours'] ?? '6',
                                            'extra_attrs' => 'name="cooldown_hours" min="0" step="1"',
                                            'required' => false
                                        ]); ?>

                                        <?php echo ui_form_group('auto-max-pending', 'Massimo in arrivo', 'number', [
                                            'value' => $draft['max_pending_qty'] ?? '0',
                                            'extra_attrs' => 'name="max_pending_qty" min="0" step="1"',
                                            'required' => false
                                        ]); ?>
                                    </div>
                                </div>
                            </details>

                            <div class="admin-workflow-link" aria-hidden="true"></div>

                            <details class="admin-workflow-block admin-workflow-block--confirm" open>
                                <summary class="admin-workflow-block-summary">
                                    <span class="admin-workflow-node-type">Output</span>
                                    <h3>Definisci l'azione finale</h3>
                                    <p>Decidi quantità di riordino e modalità finale: bozza controllabile o ordine già registrato.</p>
                                    <ul class="admin-workflow-chip-list">
                                        <li>Quantita riordino</li>
                                        <li>Bozza o ordine</li>
                                    </ul>
                                </summary>
                                <div class="admin-workflow-block-body">
                                    <div class="admin-inline-grid">
                                        <?php echo ui_form_group('auto-reorder', 'Quantita riordino', 'number', [
                                            'value' => $draft['reorder_qty'] ?? '',
                                            'extra_attrs' => 'name="reorder_qty" min="1" step="1"'
                                        ]); ?>

                                        <div class="campo-gruppo">
                                            <label for="auto-mode">Modalita</label>
                                            <select id="auto-mode" name="mode">
                                                <option value="draft" <?php echo (string) ($draft['mode'] ?? 'draft') === 'draft' ? 'selected' : ''; ?>>Genera bozza</option>
                                                <option value="auto-order" <?php echo (string) ($draft['mode'] ?? 'draft') === 'auto-order' ? 'selected' : ''; ?>>Registra ordine</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="checkout-navigation">
                                        <a class="bottone-secondario" href="<?php echo e($hubUrl); ?>">&larr; Torna alla dashboard</a>
                                        <button class="bottone-primario" type="submit"><?php echo e((string) ($builderMeta['submit_label'] ?? 'Salva')); ?></button>
                                    </div>
                                </div>
                            </details>
                        </div>
                    </form>
                <?php endif; ?>
            </div>

            <aside class="checkout-card account-side admin-supply-builder-side">
                <div class="account-panel-head">
                    <span class="account-panel-kicker">Inspector</span>
                    <h2>Promemoria operativo</h2>
                    <p class="checkout-muted">La pagina resta focalizzata sul singolo flusso, mentre breadcrumb e pulsante di ritorno ti riportano sempre al quadro generale delle forniture.</p>
                </div>

                <?php if ($builderKey === 'standard'): ?>
                    <ul class="riepilogo-lista">
                        <li><span>Template attivi</span><strong><?php echo (int) $activeTemplatesCount; ?></strong></li>
                        <li><span>Prodotti con costo pronto</span><strong><?php echo (int) $productsWithSupplyCostCount; ?></strong></li>
                        <li><span>Output</span><strong>Template riutilizzabile</strong></li>
                    </ul>
                    <p class="checkout-muted account-note">Usa questa pagina per costruire routine stabili. Il team non deve più indicare il costo: viene ripreso dalla base dati della sede.</p>
                <?php elseif ($builderKey === 'extra'): ?>
                    <ul class="riepilogo-lista">
                        <li><span>Forniture aperte</span><strong><?php echo (int) $openSupplyOrdersCount; ?></strong></li>
                        <li><span>Consegne pianificate</span><strong><?php echo (int) $scheduledSupplyOrdersCount; ?></strong></li>
                        <li><span>Output</span><strong>Ordine operativo</strong></li>
                    </ul>
                    <p class="checkout-muted account-note">Il builder straordinario resta leggero: aggiungi le righe strettamente necessarie, lascia il costo al sistema e registra subito l'intervento nello storico.</p>
                <?php else: ?>
                    <ul class="riepilogo-lista">
                        <li><span>Policy attive</span><strong><?php echo (int) $activePoliciesCount; ?></strong></li>
                        <li><span>Forniture aperte</span><strong><?php echo (int) $openSupplyOrdersCount; ?></strong></li>
                        <li><span>Output</span><strong>Bozza o ordine automatico</strong></li>
                    </ul>

                    <form method="POST" action="<?php echo e($builderUrl); ?>" class="checkout-navigation checkout-navigation--solo-azione">
                        <input type="hidden" name="csrf_token" value="<?php echo e($csrfToken); ?>">
                        <input type="hidden" name="action" value="run_auto_reorder">
                        <button class="bottone-secondario" type="submit">Esegui controllo ora</button>
                    </form>

                    <p class="checkout-muted account-note">Questa automazione usa già il costo della filiale registrato nel sistema quando genera una bozza o un ordine reale.</p>
                <?php endif; ?>

                <div class="admin-builder-side-stack">
                    <article class="admin-builder-side-card">
                        <h3>Pattern UI</h3>
                        <p class="checkout-muted">Trigger in alto, composizione o controllo al centro, output finale in basso. La lettura resta coerente con il flusso checkout della web app.</p>
                    </article>

                    <article class="admin-builder-side-card">
                        <h3>Ritorno rapido</h3>
                        <p class="checkout-muted">Se vuoi cambiare scenario, torna alla pagina forniture e apri un altro builder dal relativo launcher.</p>
                    </article>
                </div>
            </aside>
        </div>
    </div>
</section>
