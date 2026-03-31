<?php
/**
 * area_personale.php: View area personale.
 *
 * Variabili attese:
 *   $utente array
 *   $orders array
 *   $flash  ?array
 */
?>

<section aria-labelledby="titolo-area-personale">
    <div class="contenitore">
        <h1 id="titolo-area-personale">Area personale</h1>
        <p>Ciao <strong><?php echo htmlspecialchars($utente['username'], ENT_QUOTES, 'UTF-8'); ?></strong>.</p>

        <?php if (!empty($flash)): ?>
            <div class="alert <?php echo htmlspecialchars($flash['type'] ?? 'info', ENT_QUOTES, 'UTF-8'); ?>">
                <?php echo htmlspecialchars($flash['message'] ?? '', ENT_QUOTES, 'UTF-8'); ?>
            </div>
        <?php endif; ?>

        <p>
            <a href="carrello.php">Vai al carrello</a> |
            <a href="prodotti.php">Sfoglia il catalogo</a>
        </p>

        <h2>Storico ordini</h2>

        <?php if (empty($orders)): ?>
            <p>Non hai ancora effettuato ordini.</p>
        <?php else: ?>
            <?php foreach ($orders as $ordine): ?>
                <article class="ordine-card" aria-labelledby="ordine-<?php echo (int) $ordine['id']; ?>">
                    <h3 id="ordine-<?php echo (int) $ordine['id']; ?>">
                        Ordine <?php echo htmlspecialchars($ordine['order_number'], ENT_QUOTES, 'UTF-8'); ?>
                    </h3>
                    <p>
                        Stato: <strong><?php echo htmlspecialchars($ordine['order_status'], ENT_QUOTES, 'UTF-8'); ?></strong> |
                        Pagamento: <strong><?php echo htmlspecialchars($ordine['payment_status'], ENT_QUOTES, 'UTF-8'); ?></strong> |
                        Metodo: <strong><?php echo htmlspecialchars($ordine['payment_method'], ENT_QUOTES, 'UTF-8'); ?></strong>
                    </p>
                    <p>
                        Tipo: <?php echo htmlspecialchars($ordine['fulfillment_type'], ENT_QUOTES, 'UTF-8'); ?> |
                        Sede: <strong><?php echo htmlspecialchars($ordine['branch_name_snapshot'], ENT_QUOTES, 'UTF-8'); ?></strong> |
                        Totale: <strong><?php echo money_eur((int) $ordine['total_cents']); ?></strong>
                    </p>

                    <?php if (!empty($ordine['pickup_at'])): ?>
                        <p>Ritiro previsto: <?php echo htmlspecialchars((string) $ordine['pickup_at'], ENT_QUOTES, 'UTF-8'); ?></p>
                    <?php endif; ?>

                    <?php if (!empty($ordine['items'])): ?>
                        <ul class="riepilogo-lista">
                            <?php foreach ($ordine['items'] as $item): ?>
                                <li>
                                    <span><?php echo htmlspecialchars($item['product_name'], ENT_QUOTES, 'UTF-8'); ?> x<?php echo (int) $item['quantity']; ?></span>
                                    <strong><?php echo money_eur((int) $item['line_total_cents']); ?></strong>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </article>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</section>
