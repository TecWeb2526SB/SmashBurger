<?php
/**
 * ricevuta: View ricevuta ordine cliente o fornitura.
 *
 * Variabili attese:
 *   $receiptType
 *   $receipt
 *   $backHref
 *   $backLabel
 */
?>

<section class="account-page receipt-page" aria-labelledby="titolo-ricevuta">
    <div class="contenitore">
        <div class="account-hero-card account-hero-card--compact">
            <div class="account-hero-copy">
                <span class="home-eyebrow"><?php echo $receiptType === 'fornitura' ? 'Ricevuta fornitura' : 'Ricevuta ordine'; ?></span>
                <h1 id="titolo-ricevuta">Ricevuta stampabile</h1>
                <p class="account-hero-text">
                    Documento operativo pronto per la stampa o per il salvataggio in PDF dal browser.
                </p>
                <div class="account-action-row">
                    <a class="bottone-secondario" href="<?php echo e($backHref); ?>">&larr; <?php echo e($backLabel); ?></a>
                    <button class="bottone-primario" type="button" data-print-trigger="true">Stampa o salva PDF</button>
                </div>
            </div>

            <aside class="account-summary-box" aria-labelledby="titolo-riepilogo-ricevuta">
                <h2 id="titolo-riepilogo-ricevuta">Riferimenti</h2>
                <ul class="account-summary-list">
                    <li><span>Documento</span><strong><?php echo e((string) $receipt['receipt_code']); ?></strong></li>
                    <li><span>Data</span><strong><?php echo e(format_datetime_for_ui((string) ($receipt['created_at'] ?? $receipt['received_at'] ?? ''))); ?></strong></li>
                    <li><span>Totale</span><strong><?php echo money_eur((int) ($receipt['total_cents'] ?? 0)); ?></strong></li>
                </ul>
            </aside>
        </div>

        <article class="checkout-card receipt-card">
            <header class="receipt-header">
                <div>
                    <p class="ordine-card-eyebrow">Smash Burger Original</p>
                    <h2><?php echo $receiptType === 'fornitura' ? 'Ricevuta ricezione fornitura' : 'Ricevuta ordine cliente'; ?></h2>
                </div>
                <strong class="ordine-card-total"><?php echo money_eur((int) ($receipt['total_cents'] ?? 0)); ?></strong>
            </header>

            <?php if ($receiptType === 'fornitura'): ?>
                <dl class="ordine-card-meta receipt-meta-grid">
                    <div>
                        <dt>Codice fornitura</dt>
                        <dd><?php echo e((string) $receipt['order_code']); ?></dd>
                    </div>
                    <div>
                        <dt>Filiale</dt>
                        <dd><?php echo e((string) $receipt['branch_name']); ?></dd>
                    </div>
                    <div>
                        <dt>Fornitore</dt>
                        <dd><?php echo e((string) $receipt['supplier_name']); ?></dd>
                    </div>
                    <div>
                        <dt>Ricevuta il</dt>
                        <dd><?php echo e(format_datetime_for_ui((string) ($receipt['received_at'] ?? ''))); ?></dd>
                    </div>
                    <div>
                        <dt>Stato</dt>
                        <dd><?php echo e((string) $receipt['status']); ?></dd>
                    </div>
                    <div>
                        <dt>Tipo</dt>
                        <dd><?php echo e((string) $receipt['order_type']); ?></dd>
                    </div>
                </dl>
            <?php else: ?>
                <dl class="ordine-card-meta receipt-meta-grid">
                    <div>
                        <dt>Numero ordine</dt>
                        <dd><?php echo e((string) $receipt['order_number']); ?></dd>
                    </div>
                    <div>
                        <dt>Cliente</dt>
                        <dd><?php echo e((string) $receipt['username']); ?></dd>
                    </div>
                    <div>
                        <dt>Email</dt>
                        <dd><?php echo e((string) $receipt['email']); ?></dd>
                    </div>
                    <div>
                        <dt>Filiale</dt>
                        <dd><?php echo e((string) $receipt['branch_name_snapshot']); ?></dd>
                    </div>
                    <div>
                        <dt>Ritiro</dt>
                        <dd><?php echo e((string) $receipt['fulfillment_type']); ?></dd>
                    </div>
                    <div>
                        <dt>Pagamento</dt>
                        <dd><?php echo e((string) $receipt['payment_status']); ?></dd>
                    </div>
                </dl>
            <?php endif; ?>

            <div class="admin-table-wrap">
                <table class="admin-table receipt-table">
                    <caption class="sr-only">Dettaglio righe della ricevuta</caption>
                    <thead>
                        <tr>
                            <th scope="col">Voce</th>
                            <th scope="col">Quantità</th>
                            <th scope="col">Costo / Prezzo unitario</th>
                            <th scope="col">Totale riga</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ((array) ($receipt['items'] ?? []) as $item): ?>
                            <tr>
                                <th scope="row">
                                    <?php echo e((string) ($item['product_name'] ?? $item['product_name_snapshot'] ?? '')); ?>
                                </th>
                                <td>
                                    <?php echo $receiptType === 'fornitura'
                                        ? (int) ($item['quantity_received'] ?? $item['quantity_ordered'] ?? 0)
                                        : (int) ($item['quantity'] ?? 0); ?>
                                </td>
                                <td>
                                    <?php echo money_eur((int) ($item['unit_price_cents'] ?? $item['unit_cost_cents'] ?? 0)); ?>
                                </td>
                                <td>
                                    <?php echo money_eur((int) ($item['line_total_cents'] ?? 0)); ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th scope="row" colspan="3">Totale documento</th>
                            <td><?php echo money_eur((int) ($receipt['total_cents'] ?? 0)); ?></td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <?php if (!empty($receipt['notes'])): ?>
                <section class="receipt-notes" aria-labelledby="titolo-note-ricevuta">
                    <h3 id="titolo-note-ricevuta">Note</h3>
                    <p><?php echo e((string) $receipt['notes']); ?></p>
                </section>
            <?php endif; ?>
        </article>
    </div>
</section>
