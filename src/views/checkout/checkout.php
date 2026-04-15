<?php
/**
 * checkout: Step 1 checkout - conferma ordine.
 *
 * Variabili attese:
 *   $carrello  array
 *   $errori    array
 *   $flash     ?array
 *   $selectedBranch ?array
 *   $csrfToken string
 */
?>

<section aria-labelledby="titolo-checkout">
    <div class="contenitore">
        <h1 id="titolo-checkout">Checkout</h1>
        <p class="checkout-intro">Verifica ordine e sede prima di passare al metodo di ritiro.</p>

        <?php if (!empty($flash)): ?>
            <div class="alert <?php echo htmlspecialchars($flash['type'] ?? 'info', ENT_QUOTES, 'UTF-8'); ?>">
                <?php echo htmlspecialchars($flash['message'] ?? '', ENT_QUOTES, 'UTF-8'); ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($errori['generale'])): ?>
            <div role="alert" class="errore-sommario">
                <p><?php echo htmlspecialchars($errori['generale'], ENT_QUOTES, 'UTF-8'); ?></p>
            </div>
        <?php endif; ?>

        <form class="checkout-card checkout-form checkout-main" method="POST" action="checkout" aria-label="Conferma ordine">
            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8'); ?>">
            <h2>Conferma ordine</h2>
            <?php if (!empty($selectedBranch)): ?>
                <p>
                    Sede selezionata: <strong><?php echo htmlspecialchars($selectedBranch['name'], ENT_QUOTES, 'UTF-8'); ?></strong><br>
                    <?php echo htmlspecialchars($selectedBranch['address_line'], ENT_QUOTES, 'UTF-8'); ?> -
                    <?php echo htmlspecialchars($selectedBranch['city'], ENT_QUOTES, 'UTF-8'); ?>
                </p>
            <?php endif; ?>
            <p>Controlla il riepilogo e poi continua con il metodo di ritiro.</p>

            <section class="checkout-summary" aria-label="Riepilogo ordine">
                <h2>Riepilogo</h2>
                <ul class="riepilogo-lista">
                    <?php foreach ($carrello['items'] as $item): ?>
                        <li>
                            <span><?php echo htmlspecialchars($item['product_name'], ENT_QUOTES, 'UTF-8'); ?> x<?php echo (int) $item['quantity']; ?></span>
                            <strong><?php echo money_eur((int) $item['line_total_cents']); ?></strong>
                        </li>
                    <?php endforeach; ?>
                </ul>
                <p class="totale-carrello">Totale da pagare: <strong><?php echo money_eur((int) $carrello['total_cents']); ?></strong></p>
            </section>

            <div class="checkout-navigation">
                <a class="bottone-secondario" href="carrello">&larr; Torna al carrello</a>
                <button class="bottone-primario" type="submit">Vai al ritiro &rarr;</button>
            </div>
        </form>
    </div>
</section>
