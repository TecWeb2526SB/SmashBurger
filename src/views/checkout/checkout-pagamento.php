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

        <?php echo ui_alert($flash); ?>
        <?php echo ui_error_summary($errori); ?>

        <form class="checkout-card checkout-form" method="POST" action="<?php echo e(app_route('checkout-pagamento')); ?>" data-valida="true" novalidate="novalidate">
            <input type="hidden" name="csrf_token" value="<?php echo e($csrfToken); ?>">

            <?php if (!empty($selectedBranch)): ?>
                <p class="checkout-muted">
                    Sede ordine: <strong><?php echo e($selectedBranch['name']); ?></strong>
                </p>
            <?php endif; ?>

            <p class="checkout-muted">
                <?php if ($fulfillmentType === 'asporto'): ?>
                    Modalita ritiro: <strong>Asporto immediato</strong>. Ordine pronto in pochi minuti.
                <?php else: ?>
                    Modalita ritiro: <strong>Ritiro in sede</strong>.
                    Orario selezionato: <strong><?php echo e((string) $pickupDisplay); ?></strong>.
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
                    <?php
                    echo ui_form_group('card_number', 'Numero carta', 'text', [
                        'value' => $form['card_number'],
                        'error' => $errori['card_number'] ?? null,
                        'autocomplete' => 'cc-number',
                        'placeholder' => '4242 4242 4242 4242',
                        'extra_attrs' => 'inputmode="numeric"'
                    ]);

                    echo ui_form_group('card_holder', 'Intestatario carta', 'text', [
                        'value' => $form['card_holder'],
                        'error' => $errori['card_holder'] ?? null,
                        'autocomplete' => 'cc-name',
                        'placeholder' => 'Nome e cognome'
                    ]);
                    ?>

                    <div class="checkout-inline-fields">
                        <?php
                        echo ui_form_group('card_expiry', 'Scadenza (MM/AA)', 'text', [
                            'value' => $form['card_expiry'],
                            'error' => $errori['card_expiry'] ?? null,
                            'autocomplete' => 'cc-exp',
                            'placeholder' => '08/30',
                            'extra_attrs' => 'inputmode="numeric"'
                        ]);

                        echo ui_form_group('card_cvv', 'CVV', 'password', [
                            'value' => $form['card_cvv'],
                            'error' => $errori['card_cvv'] ?? null,
                            'autocomplete' => 'cc-csc',
                            'placeholder' => '123',
                            'extra_attrs' => 'inputmode="numeric"'
                        ]);
                        ?>
                    </div>
                </div>

                <div id="payment-paypal-fields" class="checkout-payment-box" <?php echo ($form['payment_method'] === 'paypal') ? '' : 'hidden'; ?>>
                    <h2>Dettagli PayPal</h2>
                    <?php
                    echo ui_form_group('paypal_email', 'Email PayPal', 'email', [
                        'value' => $form['paypal_email'],
                        'error' => $errori['paypal_email'] ?? null,
                        'autocomplete' => 'email',
                        'placeholder' => 'nome@example.com'
                    ]);
                    ?>
                </div>
            </div>

            <div class="checkout-navigation">
                <a class="bottone-secondario" href="<?php echo e(app_route('checkout-ritiro')); ?>">&larr; Torna al ritiro</a>
                <button class="bottone-primario" type="submit">Conferma ordine &rarr;</button>
            </div>
        </form>
    </div>
</section>
