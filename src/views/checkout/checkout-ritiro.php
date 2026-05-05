<?php
/**
 * checkout-ritiro: Step 2 checkout - metodo di ritiro.
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

        <?php echo ui_alert($flash); ?>
        <?php echo ui_error_summary($errori); ?>

        <form class="checkout-card checkout-form" method="POST" action="<?php echo e(app_route('checkout-ritiro')); ?>" data-valida="true" novalidate="novalidate">
            <input type="hidden" name="csrf_token" value="<?php echo e($csrfToken); ?>">

            <?php if (!empty($selectedBranch)): ?>
                <p class="checkout-muted">
                    Sede ordine: <strong><?php echo e($selectedBranch['name']); ?></strong>
                </p>
            <?php endif; ?>

            <fieldset class="checkout-fieldset">
                <legend>Seleziona il ritiro</legend>
                <label>
                    <input type="radio" name="pickup_mode" value="immediato"
                        <?php echo ($form['pickup_mode'] === 'immediato') ? 'checked' : ''; ?>>
                    Ritiro immediato
                </label>
                <p class="checkout-option-note">Ritiro immediato: l ordine sara pronto in pochi minuti.</p>

                <label>
                    <input type="radio" name="pickup_mode" value="orario"
                        <?php echo empty($todaySlots) ? 'disabled' : ''; ?>
                        <?php echo ($form['pickup_mode'] === 'orario') ? 'checked' : ''; ?>>
                    Scegli orario
                </label>
                <p class="checkout-option-note">
                    <?php if (!empty($todaySlots)): ?>
                        Orari disponibili oggi, in base all apertura della sede.
                    <?php else: ?>
                        Al momento non ci sono slot orari disponibili per oggi.
                    <?php endif; ?>
                </p>
            </fieldset>

            <div id="pickup-time-wrap" class="campo-gruppo" <?php echo ($form['pickup_mode'] === 'orario' && !empty($todaySlots)) ? '' : 'hidden'; ?>>
                <label for="pickup_time">Orario ritiro (oggi)</label>
                <select
                    id="pickup_time"
                    name="pickup_time"
                    aria-describedby="pickup_at-help pickup_at-errore"
                    <?php echo isset($errori['pickup_at']) ? 'aria-invalid="true"' : ''; ?>>
                    <?php foreach ($todaySlots as $slot): ?>
                        <option value="<?php echo e($slot['time']); ?>"
                            <?php echo ($form['pickup_time'] === $slot['time']) ? 'selected' : ''; ?>>
                            <?php echo e($slot['display']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <span id="pickup_at-help" class="campo-suggerimento">
                    <?php if (!empty($todaySlots)): ?>
                        Primo orario utile: <?php echo e($todaySlots[0]['display']); ?>.
                    <?php else: ?>
                        Oggi non ci sono slot disponibili per il ritiro programmato.
                    <?php endif; ?>
                </span>
                <span id="pickup_at-errore" class="campo-errore" <?php echo empty($errori['pickup_at']) ? 'hidden' : ''; ?>>
                    <?php echo e($errori['pickup_at'] ?? ''); ?>
                </span>
            </div>

            <div class="checkout-navigation">
                <a class="bottone-secondario" href="<?php echo e(app_route('checkout')); ?>">&larr; Torna al checkout</a>
                <button class="bottone-primario" type="submit">Vai al pagamento &rarr;</button>
            </div>
        </form>
    </div>
</section>
