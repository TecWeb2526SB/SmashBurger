<?php
/**
 * account: View area personale.
 *
 * Variabili attese:
 *   $utente array
 *   $orders array
 *   $flash  ?array
 *   $canAccessAdminPanel bool
 *   $showCustomerOrders bool
 */
?>

<?php
$numeroOrdini = count($orders);

$orderStatusMeta = static function (string $rawStatus): array {
    $label = trim($rawStatus);
    $normalized = mb_strtolower($label);

    if ($label === '') {
        return ['label' => 'In lavorazione', 'class' => 'is-warning'];
    }

    if (
        str_contains($normalized, 'confer')
        || str_contains($normalized, 'confirm')
        || str_contains($normalized, 'pronto')
        || str_contains($normalized, 'ready')
        || str_contains($normalized, 'complet')
        || str_contains($normalized, 'deliver')
    ) {
        return ['label' => $label, 'class' => 'is-success'];
    }

    if (
        str_contains($normalized, 'annull')
        || str_contains($normalized, 'cancel')
        || str_contains($normalized, 'rifiut')
        || str_contains($normalized, 'fallit')
        || str_contains($normalized, 'failed')
    ) {
        return ['label' => $label, 'class' => 'is-danger'];
    }

    if (
        str_contains($normalized, 'attes')
        || str_contains($normalized, 'pending')
        || str_contains($normalized, 'prepar')
        || str_contains($normalized, 'process')
    ) {
        return ['label' => $label, 'class' => 'is-warning'];
    }

    return ['label' => $label, 'class' => 'is-info'];
};

$paymentStatusMeta = static function (string $rawStatus): array {
    $normalized = mb_strtolower(trim($rawStatus));

    if (str_contains($normalized, 'pagat') || str_contains($normalized, 'paid')) {
        return ['label' => 'Pagato', 'class' => 'is-paid'];
    }

    if (str_contains($normalized, 'rimbors') || str_contains($normalized, 'refund')) {
        return ['label' => 'Rimborsato', 'class' => 'is-refunded'];
    }

    if (
        str_contains($normalized, 'non pag')
        || str_contains($normalized, 'unpaid')
        || str_contains($normalized, 'fallit')
        || str_contains($normalized, 'failed')
        || str_contains($normalized, 'rifiut')
        || str_contains($normalized, 'declin')
    ) {
        return ['label' => 'Non pagato', 'class' => 'is-failed'];
    }

    return ['label' => 'In attesa pagamento', 'class' => 'is-pending'];
};

$paymentMethodMeta = static function (string $rawMethod): array {
    $label = trim($rawMethod);
    $normalized = mb_strtolower($label);

    if (str_contains($normalized, 'paypal')) {
        return ['label' => 'PayPal', 'short' => 'PP'];
    }

    if (
        str_contains($normalized, 'carta')
        || str_contains($normalized, 'card')
        || str_contains($normalized, 'visa')
        || str_contains($normalized, 'mastercard')
    ) {
        return ['label' => 'Carta', 'short' => 'CC'];
    }

    if (str_contains($normalized, 'satispay')) {
        return ['label' => 'Satispay', 'short' => 'SP'];
    }

    if (str_contains($normalized, 'bonific') || str_contains($normalized, 'bank')) {
        return ['label' => 'Bonifico', 'short' => 'BN'];
    }

    if (str_contains($normalized, 'contant') || str_contains($normalized, 'cash')) {
        return ['label' => 'Contanti', 'short' => 'CA'];
    }

    $safeLabel = $label !== '' ? $label : 'Metodo non definito';
    $lettersOnly = preg_replace('/[^A-Za-z0-9]/', '', $safeLabel);
    $short = strtoupper(substr((string) $lettersOnly, 0, 2));

    return [
        'label' => $safeLabel,
        'short' => $short !== '' ? $short : 'NA',
    ];
};
?>

<section class="account-page" aria-labelledby="titolo-area-personale">
    <div class="contenitore">
        <?php echo ui_alert($flash); ?>

        <div class="account-hero-card" aria-labelledby="titolo-area-personale">
            <div class="account-hero-copy">
                <h1 id="titolo-area-personale">Area personale</h1>
                <p class="account-hero-text">
                    <?php if (!empty($showCustomerOrders)): ?>
                        Ritrova i tuoi ordini, aggiorna le credenziali e riparti subito dal catalogo con un layout più pulito e immediato.
                    <?php else: ?>
                        Aggiorna le credenziali e accedi rapidamente agli strumenti interni senza mischiare l'area operativa con lo storico ordini cliente.
                    <?php endif; ?>
                </p>
                <div class="account-action-row">
                    <a class="bottone-primario" href="<?php echo e(app_route('account-profilo')); ?>">Gestisci account</a>
                    <?php if (can_place_customer_orders()): ?>
                        <a class="bottone-secondario" href="<?php echo e(app_route('carrello')); ?>">Apri il carrello</a>
                    <?php endif; ?>
                    <?php if (!empty($canAccessAdminPanel)): ?>
                        <a class="bottone-secondario" href="<?php echo e(app_route('controllo')); ?>">Pannello controllo</a>
                    <?php endif; ?>
                </div>
            </div>

            <aside class="account-summary-box" aria-label="Riepilogo account">
                <ul class="account-summary-list">
                    <li><span>Username</span><strong><?php echo e($utente['username']); ?></strong></li>
                    <li><span>Email</span><strong><?php echo e((string) $utente['email']); ?></strong></li>
                    <li><span><?php echo !empty($showCustomerOrders) ? 'Ordini totali' : 'Ruolo'; ?></span><strong><?php echo !empty($showCustomerOrders) ? (int) $numeroOrdini : e(role_label((string) $utente['role'])); ?></strong></li>
                </ul>
            </aside>
        </div>

        <?php if (!empty($showCustomerOrders)): ?>
            <div class="account-section-head">
                <div>
                    <h2>Storico ordini</h2>
                </div>
            </div>
            <p class="checkout-muted">Tieni sotto controllo stato, ritiro e dettagli di ogni ordine in un colpo d'occhio.</p>

        <?php if (empty($orders)): ?>
            <article class="checkout-card account-empty-state">
                <h3>Nessun ordine ancora</h3>
                <p>Quando effettuerai il primo acquisto troverai qui tutti i dettagli, dal riepilogo prodotti allo stato del pagamento.</p>
                <div class="account-empty-actions">
                    <?php if (can_place_customer_orders()): ?>
                        <a class="bottone-primario" href="<?php echo e(app_route('prodotti')); ?>">Inizia dal catalogo</a>
                        <a class="bottone-secondario" href="sedi">Scegli una sede</a>
                    <?php elseif (!empty($canAccessAdminPanel)): ?>
                        <a class="bottone-primario" href="<?php echo e(app_route('controllo')); ?>">Apri il pannello controllo</a>
                    <?php endif; ?>
                </div>
            </article>
        <?php else: ?>
            <div class="account-orders-grid">
                <?php foreach ($orders as $ordine): ?>
                    <?php
                    $statusMeta = $orderStatusMeta((string) ($ordine['order_status'] ?? ''));
                    $paymentMeta = $paymentStatusMeta((string) ($ordine['payment_status'] ?? ''));
                    $methodMeta = $paymentMethodMeta((string) ($ordine['payment_method'] ?? ''));
                    ?>
                    <article class="ordine-card" aria-labelledby="ordine-<?php echo (int) $ordine['id']; ?>">
                        <div class="ordine-card-head">
                            <div>
                                <p class="ordine-card-eyebrow">Ordine</p>
                                <h3 id="ordine-<?php echo (int) $ordine['id']; ?>">
                                    <?php echo e($ordine['order_number']); ?>
                                </h3>
                            </div>
                            <strong class="ordine-card-total"><?php echo money_eur((int) $ordine['total_cents']); ?></strong>
                        </div>

                        <ul class="ordine-pill-list" aria-label="Stato ordine">
                            <li class="ordine-pill ordine-pill-status <?php echo e($statusMeta['class']); ?>">
                                <?php echo e($statusMeta['label']); ?>
                            </li>
                        </ul>

                        <dl class="ordine-card-meta">
                            <div>
                                <dt>Ritiro</dt>
                                <dd><?php echo e($ordine['fulfillment_type']); ?></dd>
                            </div>
                            <div>
                                <dt>Sede</dt>
                                <dd><?php echo e($ordine['branch_name_snapshot']); ?></dd>
                            </div>
                            <div>
                                <dt>Ritiro previsto</dt>
                                <dd>
                                    <?php echo !empty($ordine['pickup_at']) ? e((string) $ordine['pickup_at']) : 'Da definire'; ?>
                                </dd>
                            </div>
                        </dl>

                        <?php if (!empty($ordine['items'])): ?>
                            <div class="ordine-card-items">
                                <h4>Dettaglio prodotti</h4>
                                <ul class="riepilogo-lista">
                                    <?php foreach ($ordine['items'] as $item): ?>
                                        <li>
                                            <span><?php echo e($item['product_name']); ?> x<?php echo (int) $item['quantity']; ?></span>
                                            <strong><?php echo money_eur((int) $item['line_total_cents']); ?></strong>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        <?php endif; ?>

                        <div class="admin-inline-actions ordine-payment-actions">
                            <span class="ordine-payment-pill <?php echo e($paymentMeta['class']); ?>">
                                <?php echo e($paymentMeta['label']); ?>
                            </span>
                            <span class="ordine-method-pill" aria-label="Metodo di pagamento: <?php echo e($methodMeta['label']); ?>">
                                <span class="ordine-method-pill-code" aria-hidden="true"><?php echo e($methodMeta['short']); ?></span>
                                <span class="ordine-method-pill-label"><?php echo e($methodMeta['label']); ?></span>
                            </span>
                            <a class="bottone-secondario" href="ricevuta?tipo=ordine&amp;id=<?php echo (int) $ordine['id']; ?>">Apri ricevuta</a>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    <?php endif; ?>
    </div>
</section>
