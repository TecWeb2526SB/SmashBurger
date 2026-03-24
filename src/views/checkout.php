<?php
/**
 * checkout.php: View checkout.
 *
 * Variabili attese:
 *   $carrello  array
 *   $form      array
 *   $errori    array
 *   $flash     ?array
 *   $allBranches array
 *   $selectedBranch ?array
 *   $csrfToken string
 */
?>

<section aria-labelledby="titolo-checkout">
    <div class="contenitore">
        <h1 id="titolo-checkout">Completa il tuo ordine</h1>

        <?php if (!empty($allBranches)): ?>
            <form class="branch-switcher" method="GET" action="checkout.php" aria-label="Scegli sede checkout">
                <label for="sede-checkout">Sede ordine</label>
                <select id="sede-checkout" name="sede">
                    <?php foreach ($allBranches as $branch): ?>
                        <option value="<?php echo htmlspecialchars($branch['slug'], ENT_QUOTES, 'UTF-8'); ?>"
                            <?php echo (!empty($selectedBranch) && (int) $selectedBranch['id'] === (int) $branch['id']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($branch['city'] . ' - ' . $branch['address_line'], ENT_QUOTES, 'UTF-8'); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <button type="submit">Aggiorna sede</button>
            </form>
        <?php endif; ?>

        <?php if (!empty($selectedBranch)): ?>
            <p>
                Stai ordinando da <strong><?php echo htmlspecialchars($selectedBranch['name'], ENT_QUOTES, 'UTF-8'); ?></strong>.
                Contatti sede: <a href="tel:<?php echo htmlspecialchars(preg_replace('/[^0-9+]/', '', (string) $selectedBranch['phone']), ENT_QUOTES, 'UTF-8'); ?>">
                    <?php echo htmlspecialchars($selectedBranch['phone'], ENT_QUOTES, 'UTF-8'); ?>
                </a>
                -
                <a href="mailto:<?php echo htmlspecialchars($selectedBranch['email'], ENT_QUOTES, 'UTF-8'); ?>">
                    <?php echo htmlspecialchars($selectedBranch['email'], ENT_QUOTES, 'UTF-8'); ?>
                </a>
            </p>
        <?php endif; ?>

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

        <div class="checkout-layout">
            <form method="POST" action="checkout.php" data-valida novalidate>
                <input type="hidden" name="csrf_token"
                    value="<?php echo htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8'); ?>">

                <fieldset class="checkout-fieldset">
                    <legend>Modalita ordine</legend>
                    <label>
                        <input type="radio" name="fulfillment_type" value="ritiro"
                            <?php echo ($form['fulfillment_type'] === 'ritiro') ? 'checked' : ''; ?>>
                        Ritiro in sede
                    </label>
                    <label>
                        <input type="radio" name="fulfillment_type" value="asporto"
                            <?php echo ($form['fulfillment_type'] === 'asporto') ? 'checked' : ''; ?>>
                        Asporto immediato
                    </label>
                </fieldset>

                <div class="campo-gruppo">
                    <label for="pickup_at">Data e ora ritiro</label>
                    <input
                        type="datetime-local"
                        id="pickup_at"
                        name="pickup_at"
                        value="<?php echo htmlspecialchars($form['pickup_at'], ENT_QUOTES, 'UTF-8'); ?>"
                        aria-describedby="pickup_at-errore"
                        <?php echo isset($errori['pickup_at']) ? 'aria-invalid="true"' : ''; ?>>
                    <span id="pickup_at-errore" class="campo-errore" <?php echo empty($errori['pickup_at']) ? 'hidden' : ''; ?>>
                        <?php echo htmlspecialchars($errori['pickup_at'] ?? '', ENT_QUOTES, 'UTF-8'); ?>
                    </span>
                </div>

                <div class="campo-gruppo">
                    <label for="payment_method">Metodo di pagamento</label>
                    <select id="payment_method" name="payment_method">
                        <option value="card" <?php echo ($form['payment_method'] === 'card') ? 'selected' : ''; ?>>
                            Carta
                        </option>
                        <option value="paypal" <?php echo ($form['payment_method'] === 'paypal') ? 'selected' : ''; ?>>
                            PayPal
                        </option>
                        <option value="cash" <?php echo ($form['payment_method'] === 'cash') ? 'selected' : ''; ?>>
                            Contanti al ritiro
                        </option>
                    </select>
                </div>

                <div class="campo-gruppo">
                    <label for="card_number">Numero carta</label>
                    <input
                        type="text"
                        id="card_number"
                        name="card_number"
                        inputmode="numeric"
                        autocomplete="cc-number"
                        value="<?php echo htmlspecialchars($form['card_number'], ENT_QUOTES, 'UTF-8'); ?>"
                        placeholder="Es. 4242424242424242">
                </div>

                <div class="campo-gruppo">
                    <label for="paypal_email">Email PayPal</label>
                    <input
                        type="email"
                        id="paypal_email"
                        name="paypal_email"
                        autocomplete="email"
                        value="<?php echo htmlspecialchars($form['paypal_email'], ENT_QUOTES, 'UTF-8'); ?>"
                        placeholder="nome@example.com">
                </div>

                <button class="bottone-primario" type="submit">Conferma ordine</button>
            </form>

            <aside aria-label="Riepilogo ordine">
                <h2>Riepilogo</h2>
                <ul class="riepilogo-lista">
                    <?php foreach ($carrello['items'] as $item): ?>
                        <li>
                            <span><?php echo htmlspecialchars($item['product_name'], ENT_QUOTES, 'UTF-8'); ?> x<?php echo (int) $item['quantity']; ?></span>
                            <strong><?php echo money_eur((int) $item['line_total_cents']); ?></strong>
                        </li>
                    <?php endforeach; ?>
                </ul>
                <p class="totale-carrello">
                    Totale da pagare: <strong><?php echo money_eur((int) $carrello['total_cents']); ?></strong>
                </p>
            </aside>
        </div>
    </div>
</section>
