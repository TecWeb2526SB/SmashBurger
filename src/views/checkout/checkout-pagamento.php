<?php
/**
 * checkout-pagamento: Step 3 checkout - pagamento.
 *
 * Variabili attese:
 *   $carrello         array
 *   $selectedBranch   ?array
 *   $fulfillmentType  string
 *   $pickupDisplay    ?string
 *   $form             array
 *   $errori           array
 *   $flash            ?array
 *   $csrfToken        string
 */
?>

<section aria-labelledby="titolo-checkout-pagamento">
    <div class="contenitore">
        <h1 id="titolo-checkout-pagamento">Pagamento</h1>
        <p class="checkout-intro">Scegli il metodo di pagamento e completa l ordine.</p>

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

        <form class="checkout-card checkout-form checkout-main" method="POST" action="checkout-pagamento" data-valida novalidate>
            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8'); ?>">

            <?php if (!empty($selectedBranch)): ?>
                <p class="checkout-muted">
                    Sede ordine: <strong><?php echo htmlspecialchars($selectedBranch['name'], ENT_QUOTES, 'UTF-8'); ?></strong>
                </p>
            <?php endif; ?>

            <p class="checkout-muted">
                <?php if ($fulfillmentType === 'asporto'): ?>
                    Modalita ritiro: <strong>Asporto immediato</strong>. Ordine pronto in pochi minuti.
                <?php else: ?>
                    Modalita ritiro: <strong>Ritiro in sede</strong>.
                    Orario selezionato: <strong><?php echo htmlspecialchars((string) $pickupDisplay, ENT_QUOTES, 'UTF-8'); ?></strong>.
                <?php endif; ?>
            </p>

            <fieldset class="checkout-fieldset">
                <legend>Metodo di pagamento</legend>
                <label>
                    <input type="radio" name="payment_method" value="card"
                        <?php echo ($form['payment_method'] === 'card') ? 'checked' : ''; ?>>
                    Carta
                </label>
                <label>
                    <input type="radio" name="payment_method" value="paypal"
                        <?php echo ($form['payment_method'] === 'paypal') ? 'checked' : ''; ?>>
                    PayPal
                </label>
            </fieldset>

            <div class="checkout-payment-grid">
                <div id="payment-card-fields" class="checkout-payment-box" <?php echo ($form['payment_method'] === 'card') ? '' : 'hidden'; ?>>
                    <h2>Dati carta</h2>
                    <div class="campo-gruppo">
                        <label for="card_number">Numero carta</label>
                        <input
                            type="text"
                            id="card_number"
                            name="card_number"
                            inputmode="numeric"
                            autocomplete="cc-number"
                            value="<?php echo htmlspecialchars($form['card_number'], ENT_QUOTES, 'UTF-8'); ?>"
                            placeholder="4242 4242 4242 4242"
                            aria-describedby="card_number-errore"
                            <?php echo isset($errori['card_number']) ? 'aria-invalid="true"' : ''; ?>>
                        <span id="card_number-errore" class="campo-errore" <?php echo empty($errori['card_number']) ? 'hidden' : ''; ?>>
                            <?php echo htmlspecialchars($errori['card_number'] ?? '', ENT_QUOTES, 'UTF-8'); ?>
                        </span>
                    </div>

                    <div class="campo-gruppo">
                        <label for="card_holder">Intestatario carta</label>
                        <input
                            type="text"
                            id="card_holder"
                            name="card_holder"
                            autocomplete="cc-name"
                            value="<?php echo htmlspecialchars($form['card_holder'], ENT_QUOTES, 'UTF-8'); ?>"
                            placeholder="Nome e cognome"
                            aria-describedby="card_holder-errore"
                            <?php echo isset($errori['card_holder']) ? 'aria-invalid="true"' : ''; ?>>
                        <span id="card_holder-errore" class="campo-errore" <?php echo empty($errori['card_holder']) ? 'hidden' : ''; ?>>
                            <?php echo htmlspecialchars($errori['card_holder'] ?? '', ENT_QUOTES, 'UTF-8'); ?>
                        </span>
                    </div>

                    <div class="checkout-inline-fields">
                        <div class="campo-gruppo">
                            <label for="card_expiry">Scadenza (MM/AA)</label>
                            <input
                                type="text"
                                id="card_expiry"
                                name="card_expiry"
                                inputmode="numeric"
                                autocomplete="cc-exp"
                                value="<?php echo htmlspecialchars($form['card_expiry'], ENT_QUOTES, 'UTF-8'); ?>"
                                placeholder="08/30"
                                aria-describedby="card_expiry-errore"
                                <?php echo isset($errori['card_expiry']) ? 'aria-invalid="true"' : ''; ?>>
                            <span id="card_expiry-errore" class="campo-errore" <?php echo empty($errori['card_expiry']) ? 'hidden' : ''; ?>>
                                <?php echo htmlspecialchars($errori['card_expiry'] ?? '', ENT_QUOTES, 'UTF-8'); ?>
                            </span>
                        </div>

                        <div class="campo-gruppo">
                            <label for="card_cvv">CVV</label>
                            <input
                                type="password"
                                id="card_cvv"
                                name="card_cvv"
                                inputmode="numeric"
                                autocomplete="cc-csc"
                                value="<?php echo htmlspecialchars($form['card_cvv'], ENT_QUOTES, 'UTF-8'); ?>"
                                placeholder="123"
                                aria-describedby="card_cvv-errore"
                                <?php echo isset($errori['card_cvv']) ? 'aria-invalid="true"' : ''; ?>>
                            <span id="card_cvv-errore" class="campo-errore" <?php echo empty($errori['card_cvv']) ? 'hidden' : ''; ?>>
                                <?php echo htmlspecialchars($errori['card_cvv'] ?? '', ENT_QUOTES, 'UTF-8'); ?>
                            </span>
                        </div>
                    </div>
                </div>

                <div id="payment-paypal-fields" class="checkout-payment-box" <?php echo ($form['payment_method'] === 'paypal') ? '' : 'hidden'; ?>>
                    <h2>Dettagli PayPal</h2>
                    <div class="campo-gruppo">
                        <label for="paypal_email">Email PayPal</label>
                        <input
                            type="email"
                            id="paypal_email"
                            name="paypal_email"
                            autocomplete="email"
                            value="<?php echo htmlspecialchars($form['paypal_email'], ENT_QUOTES, 'UTF-8'); ?>"
                            placeholder="nome@example.com"
                            aria-describedby="paypal_email-errore"
                            <?php echo isset($errori['paypal_email']) ? 'aria-invalid="true"' : ''; ?>>
                        <span id="paypal_email-errore" class="campo-errore" <?php echo empty($errori['paypal_email']) ? 'hidden' : ''; ?>>
                            <?php echo htmlspecialchars($errori['paypal_email'] ?? '', ENT_QUOTES, 'UTF-8'); ?>
                        </span>
                    </div>
                </div>
            </div>

            <div class="checkout-navigation">
                <a class="bottone-secondario" href="checkout-ritiro">&larr; Torna al ritiro</a>
                <button class="bottone-primario" type="submit">Conferma ordine &rarr;</button>
            </div>
        </form>
    </div>
</section>
