<?php
/**
 * Incasso del periodo, come numero e come grafico.
 *
 * Il grafico riporta date, scala monetaria e una descrizione testuale accessibile.
 */
?>
<section class="incasso">
    <h2>Incasso degli ultimi <?php echo (int) GIORNI_INCASSO; ?> giorni</h2>

    <p class="totale"><?php echo e(prezzo($incasso)); ?></p>

    <p class="didascalia-grafico">Incasso giornaliero, importi in euro.</p>

    <div class="contenitore-grafico" role="region" tabindex="0"
        aria-label="Grafico dell'incasso giornaliero; scorri orizzontalmente per vedere tutte le date">
        <?php echo grafico_incasso($serie); ?>
    </div>

    <p><small>Gli ordini annullati non contano: la merce è tornata a disposizione.</small></p>
</section>
