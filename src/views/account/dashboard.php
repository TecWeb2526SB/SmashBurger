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
$ultimoOrdine = $orders[0] ?? null;
?>

<section class="account-page" aria-labelledby="titolo-area-personale">
    <div class="contenitore">
        <div class="account-hero-card" aria-labelledby="titolo-area-personale">
            <div class="account-hero-copy">
                <span class="home-eyebrow">Il tuo spazio SmashBurger</span>
                <h1 id="titolo-area-personale">Area personale</h1>
                <p class="account-hero-text">
                    <?php if (!empty($showCustomerOrders)): ?>
                        Ritrova i tuoi ordini, aggiorna le credenziali e riparti subito dal catalogo con un layout più pulito e immediato.
                    <?php else: ?>
                        Aggiorna le credenziali e accedi rapidamente agli strumenti interni senza mischiare l'area operativa con lo storico ordini cliente.
                    <?php endif; ?>
                </p>
                <div class="account-action-row">
                    <a class="bottone-primario" href="account-profilo">Gestisci account</a>
                    <?php if (can_place_customer_orders()): ?>
                        <a class="bottone-secondario" href="carrello">Apri il carrello</a>
                    <?php endif; ?>
                    <?php if (!empty($canAccessAdminPanel)): ?>
                        <a class="bottone-secondario" href="controllo">Pannello controllo</a>
                    <?php endif; ?>
                </div>
            </div>

            <aside class="account-summary-box" aria-labelledby="titolo-riepilogo-personale">
                <h2 id="titolo-riepilogo-personale">In breve</h2>
                <ul class="account-summary-list">
                    <li><span>Username</span><strong><?php echo e($utente['username']); ?></strong></li>
                    <li><span>Email</span><strong><?php echo e((string) $utente['email']); ?></strong></li>
                    <li><span><?php echo !empty($showCustomerOrders) ? 'Ordini totali' : 'Ruolo'; ?></span><strong><?php echo !empty($showCustomerOrders) ? (int) $numeroOrdini : e(role_label((string) $utente['role'])); ?></strong></li>
                    <li>
                        <span><?php echo !empty($showCustomerOrders) && $ultimoOrdine !== null ? 'Ultimo ordine' : 'Prossimo passo'; ?></span>
                        <strong>
                            <?php if (!empty($showCustomerOrders) && $ultimoOrdine !== null): ?>
                                <?php echo e((string) $ultimoOrdine['order_number']); ?>
                            <?php elseif (!empty($canAccessAdminPanel)): ?>
                                Apri il controllo
                            <?php else: ?>
                                Scegli il tuo primo menu
                            <?php endif; ?>
                        </strong>
                    </li>
                </ul>
            </aside>
        </div>

        <?php echo ui_alert($flash); ?>

        <?php if (!empty($showCustomerOrders)): ?>
            <div class="account-section-head">
                <div>
                    <span class="account-panel-kicker">Ordini</span>
                    <h2>Storico ordini</h2>
                </div>
                <p class="checkout-muted">Tieni sotto controllo stato, ritiro e dettagli di ogni ordine in un colpo d'occhio.</p>
            </div>

        <?php if (empty($orders)): ?>
            <article class="checkout-card account-empty-state">
                <h3>Nessun ordine ancora</h3>
                <p>Quando effettuerai il primo acquisto troverai qui tutti i dettagli, dal riepilogo prodotti allo stato del pagamento.</p>
                <div class="account-empty-actions">
                    <?php if (can_place_customer_orders()): ?>
                        <a class="bottone-primario" href="prodotti">Inizia dal catalogo</a>
                        <a class="bottone-secondario" href="sedi">Scegli una sede</a>
                    <?php elseif (!empty($canAccessAdminPanel)): ?>
                        <a class="bottone-primario" href="controllo">Apri il pannello controllo</a>
                    <?php endif; ?>
                </div>
            </article>
        <?php else: ?>
            <div class="account-orders-grid">
                <?php foreach ($orders as $ordine): ?>
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
                            <li>Stato: <strong><?php echo e($ordine['order_status']); ?></strong></li>
                            <li>Pagamento: <strong><?php echo e($ordine['payment_status']); ?></strong></li>
                            <li>Metodo: <strong><?php echo e($ordine['payment_method']); ?></strong></li>
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

                        <div class="admin-inline-actions">
                            <a class="bottone-secondario" href="ricevuta?tipo=ordine&amp;id=<?php echo (int) $ordine['id']; ?>">Apri ricevuta</a>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    <?php endif; ?>
    </div>
</section>
