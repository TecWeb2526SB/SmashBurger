<?php
/**
 * carrello.php: View della pagina carrello.
 *
 * Variabili attese:
 *   $carrello  array
 *   $flash     ?array
 *   $allBranches array
 *   $selectedBranch ?array
 *   $csrfToken string
 */
?>

<section aria-labelledby="titolo-carrello">
    <div class="contenitore">
        <h1 id="titolo-carrello">Il tuo carrello</h1>
        <?php if (!empty($selectedBranch)): ?>
            <p>
                Sede corrente: <strong><?php echo htmlspecialchars($selectedBranch['name'], ENT_QUOTES, 'UTF-8'); ?></strong>
            </p>
        <?php endif; ?>

        <?php if (!empty($allBranches)): ?>
            <form class="branch-switcher" method="GET" action="carrello.php" aria-label="Cambia sede carrello">
                <label for="sede-carrello">Sede carrello</label>
                <select id="sede-carrello" name="sede">
                    <?php foreach ($allBranches as $branch): ?>
                        <option value="<?php echo htmlspecialchars($branch['slug'], ENT_QUOTES, 'UTF-8'); ?>"
                            <?php echo (!empty($selectedBranch) && (int) $selectedBranch['id'] === (int) $branch['id']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($branch['city'] . ' - ' . $branch['address_line'], ENT_QUOTES, 'UTF-8'); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <button type="submit">Cambia sede</button>
            </form>
        <?php endif; ?>

        <?php if (!empty($flash)): ?>
            <div class="alert <?php echo htmlspecialchars($flash['type'] ?? 'info', ENT_QUOTES, 'UTF-8'); ?>">
                <?php echo htmlspecialchars($flash['message'] ?? '', ENT_QUOTES, 'UTF-8'); ?>
            </div>
        <?php endif; ?>

        <?php if (empty($carrello['items'])): ?>
            <p>Il tuo carrello e vuoto.</p>
            <p><a class="bottone-primario" href="prodotti.php">Vai al catalogo</a></p>
        <?php else: ?>
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
                    <tbody>
                        <?php foreach ($carrello['items'] as $item): ?>
                            <tr>
                                <th scope="row"><?php echo htmlspecialchars($item['product_name'], ENT_QUOTES, 'UTF-8'); ?></th>
                                <td><?php echo money_eur((int) $item['unit_price_cents']); ?></td>
                                <td>
                                    <form class="inline-form" method="POST" action="carrello.php">
                                        <input type="hidden" name="csrf_token"
                                            value="<?php echo htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8'); ?>">
                                        <input type="hidden" name="action" value="update_item">
                                        <input type="hidden" name="item_id" value="<?php echo (int) $item['id']; ?>">
                                        <input type="hidden" name="branch_id"
                                            value="<?php echo !empty($selectedBranch) ? (int) $selectedBranch['id'] : 0; ?>">
                                        <input type="hidden" name="redirect_to" value="carrello.php">
                                        <label class="sr-only" for="qty-<?php echo (int) $item['id']; ?>">Quantita</label>
                                        <input id="qty-<?php echo (int) $item['id']; ?>" type="number" name="quantity" min="0" max="20"
                                            value="<?php echo (int) $item['quantity']; ?>">
                                        <button type="submit">Aggiorna</button>
                                    </form>
                                </td>
                                <td><?php echo money_eur((int) $item['line_total_cents']); ?></td>
                                <td>
                                    <form class="inline-form" method="POST" action="carrello.php">
                                        <input type="hidden" name="csrf_token"
                                            value="<?php echo htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8'); ?>">
                                        <input type="hidden" name="action" value="remove_item">
                                        <input type="hidden" name="item_id" value="<?php echo (int) $item['id']; ?>">
                                        <input type="hidden" name="branch_id"
                                            value="<?php echo !empty($selectedBranch) ? (int) $selectedBranch['id'] : 0; ?>">
                                        <input type="hidden" name="redirect_to" value="carrello.php">
                                        <button type="submit">Rimuovi</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <p class="totale-carrello">
                Totale ordine: <strong><?php echo money_eur((int) $carrello['total_cents']); ?></strong>
            </p>

            <div class="azioni-carrello">
                <form class="inline-form" method="POST" action="carrello.php">
                    <input type="hidden" name="csrf_token"
                        value="<?php echo htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8'); ?>">
                    <input type="hidden" name="action" value="clear_cart">
                    <input type="hidden" name="branch_id"
                        value="<?php echo !empty($selectedBranch) ? (int) $selectedBranch['id'] : 0; ?>">
                    <input type="hidden" name="redirect_to" value="carrello.php">
                    <button type="submit">Svuota carrello</button>
                </form>
                <a class="bottone-primario" href="checkout.php">Procedi al checkout</a>
            </div>
        <?php endif; ?>
    </div>
</section>
