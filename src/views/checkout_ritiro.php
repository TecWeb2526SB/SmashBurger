<?php
/**
 * checkout_ritiro.php: Step 2 checkout - metodo di ritiro.
 *
 * Variabili attese:
 *   $carrello           array
 *   $selectedBranch     ?array
 *   $todaySlots         array
 *   $form               array
 *   $errori             array
 *   $flash              ?array
 *   $csrfToken          string
 */
?>

<section aria-labelledby="titolo-checkout-ritiro">
    <div class="contenitore">
        <h1 id="titolo-checkout-ritiro">Metodo di ritiro</h1>
        <p class="checkout-intro">Scegli se ritirare subito o prenotare un orario per oggi.</p>

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

        <form class="checkout-card checkout-form checkout-main" method="POST" action="checkout_ritiro.php" data-valida novalidate>
            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8'); ?>">

            <?php if (!empty($selectedBranch)): ?>
                <p class="checkout-muted">
                    Sede ordine: <strong><?php echo htmlspecialchars($selectedBranch['name'], ENT_QUOTES, 'UTF-8'); ?></strong>
                </p>
            <?php endif; ?>

            <fieldset class="checkout-fieldset" <?php echo empty($todaySlots) ? 'disabled aria-disabled="true"' : ''; ?>>
                <legend>Seleziona il ritiro</legend>
                <label>
                    <input type="radio" name="pickup_mode" value="immediato"
                        <?php echo ($form['pickup_mode'] === 'immediato') ? 'checked' : ''; ?>>
                    Ritiro immediato
                </label>
                <p class="checkout-option-note">Ritiro immediato: l ordine sara pronto in pochi minuti.</p>

                <label>
                    <input type="radio" name="pickup_mode" value="orario"
                        <?php echo ($form['pickup_mode'] === 'orario') ? 'checked' : ''; ?>>
                    Scegli orario
                </label>
                <p class="checkout-option-note">Orari disponibili oggi, in base all apertura della sede.</p>
            </fieldset>

            <div id="pickup-time-wrap" class="campo-gruppo" <?php echo ($form['pickup_mode'] === 'orario' && !empty($todaySlots)) ? '' : 'hidden'; ?>>
                <label for="pickup_time">Orario ritiro (oggi)</label>
                <select
                    id="pickup_time"
                    name="pickup_time"
                    aria-describedby="pickup_at-help pickup_at-errore"
                    <?php echo isset($errori['pickup_at']) ? 'aria-invalid="true"' : ''; ?>>
                    <?php foreach ($todaySlots as $slot): ?>
                        <option value="<?php echo htmlspecialchars($slot['time'], ENT_QUOTES, 'UTF-8'); ?>"
                            <?php echo ($form['pickup_time'] === $slot['time']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($slot['display'], ENT_QUOTES, 'UTF-8'); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <span id="pickup_at-help" class="campo-suggerimento">
                    <?php if (!empty($todaySlots)): ?>
                        Primo orario utile: <?php echo htmlspecialchars($todaySlots[0]['display'], ENT_QUOTES, 'UTF-8'); ?>.
                    <?php else: ?>
                        Oggi non ci sono slot disponibili per il ritiro programmato.
                    <?php endif; ?>
                </span>
                <span id="pickup_at-errore" class="campo-errore" <?php echo empty($errori['pickup_at']) ? 'hidden' : ''; ?>>
                    <?php echo htmlspecialchars($errori['pickup_at'] ?? '', ENT_QUOTES, 'UTF-8'); ?>
                </span>
            </div>

            <div class="checkout-navigation">
                <a class="bottone-secondario" href="checkout.php">&larr; Torna al checkout</a>
                <button class="bottone-primario" type="submit" <?php echo empty($todaySlots) ? 'disabled' : ''; ?>>Vai al pagamento &rarr;</button>
            </div>
        </form>
    </div>
</section>
