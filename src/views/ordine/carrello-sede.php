<?php
/**
 * Primo passo dell'ordine: scelta della sede.
 *
 * La disponibilita' dei prodotti dipende dalla sede, quindi il catalogo dell'ordine non
 * puo' essere mostrato prima di sapere da dove si ordina.
 */
?>
<h1>Da quale sede vuoi ordinare?</h1>

<p>
    La disponibilita' cambia da un locale all'altro: scegli la sede e ti mostriamo solo
    quello che c'è davvero.
</p>

<form method="post" action="<?php echo e(url('carrello')); ?>" data-modulo="scelta-sede">
    <?php echo campo_csrf(); ?>
    <input type="hidden" name="azione" value="scegli-sede" />

    <fieldset>
        <legend>Scegli la sede</legend>

        <ul class="scelte">
            <?php foreach ($sedi as $indice => $sede): ?>
                <li>
                    <label for="sede-<?php echo e($sede['slug']); ?>">
                        <input type="radio" id="sede-<?php echo e($sede['slug']); ?>" name="sede"
                            value="<?php echo e($sede['slug']); ?>" required="required"
                            <?php echo $indice === 0 ? 'checked="checked"' : ''; ?> />
                        <span><?php echo e($sede['citta']); ?>, <?php echo e($sede['indirizzo']); ?></span>
                    </label>
                </li>
            <?php endforeach; ?>
        </ul>

        <p><button type="submit">Continua</button></p>
    </fieldset>
</form>

<p><a href="<?php echo e(url('menu')); ?>">Guarda prima il menu</a></p>
