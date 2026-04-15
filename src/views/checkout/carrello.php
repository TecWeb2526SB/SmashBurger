<?php
/**
 * carrello: View della pagina carrello.
 *
 * Variabili attese:
 *   $carrello  array
 *   $flash     ?array
 *   $selectedBranch ?array
 *   $csrfToken string
 */
?>

<?php
$hasUnavailableItems = false;
foreach ($carrello['items'] as $cartItemCheck) {
    if ((int) ($cartItemCheck['is_available'] ?? 1) !== 1) {
        $hasUnavailableItems = true;
        break;
    }
}
?>

<section aria-labelledby="titolo-carrello">
    <div class="contenitore">
        <h1 id="titolo-carrello">Il tuo carrello</h1>
        <?php if (!empty($selectedBranch)): ?>
            <p>
                Sede corrente: <strong><?php echo htmlspecialchars($selectedBranch['name'], ENT_QUOTES, 'UTF-8'); ?></strong>
            </p>
        <?php endif; ?>

        <?php if (!empty($flash)): ?>
            <div class="alert <?php echo htmlspecialchars($flash['type'] ?? 'info', ENT_QUOTES, 'UTF-8'); ?>">
                <?php echo htmlspecialchars($flash['message'] ?? '', ENT_QUOTES, 'UTF-8'); ?>
            </div>
        <?php endif; ?>

        <?php if (empty($carrello['items'])): ?>
            <p>Il tuo carrello e vuoto.</p>
            <p><a class="bottone-primario" href="prodotti">Vai al catalogo</a></p>
        <?php else: ?>
            <?php if ($hasUnavailableItems): ?>
                <div class="alert error">
                    Alcuni prodotti non sono piu disponibili o non appartengono piu al catalogo della sede corrente. Aggiorna il carrello prima di procedere al checkout.
                </div>
            <?php endif; ?>

            <div class="tabella-wrapper">
                <table class="tabella-carrello">
                    <caption class="sr-only">Prodotti presenti nel carrello</caption>
                    <thead>
                        <tr>
                            <th scope="col">Prodotto</th>
                            <th scope="col">Prezzo</th>
                            <th scope="col">Quantita</th>
                            <th scope="col">Totale riga</th>
                            <th scope="col">Azioni</th>
                        </tr>
                    </thead>
                    <tbody id="carrello-righe">
                        <?php foreach ($carrello['items'] as $item): ?>
                            <tr data-cart-item-id="<?php echo (int) $item['id']; ?>">
                                <th scope="row">
                                    <?php echo htmlspecialchars($item['product_name'], ENT_QUOTES, 'UTF-8'); ?>
                                    <?php if ((int) ($item['is_available'] ?? 1) !== 1): ?>
                                        <span class="disponibilita-ko">Non disponibile per questa sede.</span>
                                    <?php endif; ?>
                                </th>
                                <td><?php echo money_eur((int) $item['unit_price_cents']); ?></td>
                                <td>
                                    <form class="inline-form" method="POST" action="carrello" data-cart-update-form>
                                        <input type="hidden" name="csrf_token"
                                            value="<?php echo htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8'); ?>">
                                        <input type="hidden" name="action" value="update_item">
                                        <input type="hidden" name="item_id" value="<?php echo (int) $item['id']; ?>">
                                        <input type="hidden" name="redirect_to" value="carrello">
                                        <div class="cart-quantity-stepper" data-cart-stepper>
                                            <button
                                                type="button"
                                                class="cart-quantity-button"
                                                data-quantity-step="-1"
                                                aria-label="Diminuisci quantita"
                                                aria-controls="qty-display-<?php echo (int) $item['id']; ?>">
                                                -
                                            </button>
                                            <span
                                                id="qty-display-<?php echo (int) $item['id']; ?>"
                                                class="cart-quantity-display"
                                                data-cart-quantity-display
                                                role="spinbutton"
                                                tabindex="0"
                                                aria-label="Quantita di <?php echo htmlspecialchars($item['product_name'], ENT_QUOTES, 'UTF-8'); ?>"
                                                aria-valuenow="<?php echo (int) $item['quantity']; ?>"
                                                aria-valuemin="0"
                                                aria-valuemax="100">
                                                <?php echo (int) $item['quantity']; ?>
                                            </span>
                                            <input
                                                type="hidden"
                                                name="quantity"
                                                value="<?php echo (int) $item['quantity']; ?>"
                                                data-cart-quantity-input
                                                data-current-quantity="<?php echo (int) $item['quantity']; ?>"
                                                data-min="0"
                                                data-max="100">
                                            <button
                                                type="button"
                                                class="cart-quantity-button"
                                                data-quantity-step="1"
                                                aria-label="Aumenta quantita"
                                                aria-controls="qty-display-<?php echo (int) $item['id']; ?>">
                                                +
                                            </button>
                                        </div>
                                        <noscript>
                                            <label class="sr-only" for="qty-<?php echo (int) $item['id']; ?>">Quantita</label>
                                            <input id="qty-<?php echo (int) $item['id']; ?>" type="number" name="quantity" min="0" max="100" value="<?php echo (int) $item['quantity']; ?>">
                                            <button type="submit">Aggiorna</button>
                                        </noscript>
                                    </form>
                                </td>
                                <td data-cart-line-total><?php echo money_eur((int) $item['line_total_cents']); ?></td>
                                <td>
                                    <form class="inline-form" method="POST" action="carrello" data-cart-remove-form>
                                        <input type="hidden" name="csrf_token"
                                            value="<?php echo htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8'); ?>">
                                        <input type="hidden" name="action" value="remove_item">
                                        <input type="hidden" name="item_id" value="<?php echo (int) $item['id']; ?>">
                                        <input type="hidden" name="redirect_to" value="carrello">
                                        <button type="submit" class="cart-remove-button" aria-label="Rimuovi prodotto dal carrello">
                                            <svg viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                                                <path d="M9 3h6m-9 4h12m-1 0-.7 11.2a2 2 0 0 1-2 1.8H9.7a2 2 0 0 1-2-1.8L7 7m3 4v5m4-5v5" />
                                            </svg>
                                            <span class="sr-only">Rimuovi</span>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <p class="totale-carrello">
                Totale ordine: <strong id="carrello-totale-valore"><?php echo money_eur((int) $carrello['total_cents']); ?></strong>
            </p>

            <div class="azioni-carrello-footer">
                <form method="POST" action="carrello" class="form-svuota">
                    <input type="hidden" name="csrf_token"
                        value="<?php echo htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8'); ?>">
                    <input type="hidden" name="action" value="clear_cart">
                    <input type="hidden" name="redirect_to" value="carrello">
                    <button type="submit" class="bottone-secondario">Svuota carrello</button>
                </form>
                <?php if ($hasUnavailableItems): ?>
                    <span class="bottone-primario bottone-disabilitato" aria-disabled="true">Procedi al checkout</span>
                <?php else: ?>
                    <a class="bottone-primario" href="checkout">Procedi al checkout</a>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
</section>
