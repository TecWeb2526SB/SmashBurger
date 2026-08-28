<?php
/**
 * Conferma dell'ordine: riepilogo, scelta della sede, dell'orario e del pagamento.
 *
 * Riceve $sedi, $sedeId, $orari, $righe, $totale ed $errore dal controller.
 */
?>
<h1>Conferma ordine</h1>

<?php if ($errore !== null): ?>
    <p class="avviso" role="alert"><?php echo e($errore); ?></p>
<?php endif; ?>

<section>
    <h2>Riepilogo</h2>

    <table>
        <caption>Prodotti dell'ordine</caption>
        <thead>
            <tr>
                <th scope="col">Prodotto</th>
                <th scope="col">Quantità</th>
                <th scope="col">Subtotale</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($righe as $riga): ?>
                <tr>
                    <th scope="row"><?php echo e($riga['nome']); ?></th>
                    <td><?php echo (int) $riga['quantita']; ?></td>
                    <td><?php echo e(prezzo((int) $riga['subtotale_centesimi'])); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
        <tfoot>
            <tr>
                <th scope="row" colspan="2">Totale</th>
                <td><?php echo e(prezzo((int) $totale)); ?></td>
            </tr>
        </tfoot>
    </table>
</section>

<form method="post" action="<?php echo e(url('pagamento')); ?>">
    <?php echo campo_csrf(); ?>

    <fieldset>
        <legend>Ritiro</legend>

        <p>
            <label for="sede_id">Sede</label>
            <select id="sede_id" name="sede_id" required="required">
                <?php foreach ($sedi as $sede): ?>
                    <option value="<?php echo (int) $sede['id']; ?>"
                        <?php echo (int) $sede['id'] === (int) $sedeId ? 'selected="selected"' : ''; ?>>
                        <?php echo e($sede['citta']); ?>, <?php echo e($sede['indirizzo']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <button type="submit" name="azione" value="cambia-sede">Aggiorna orari</button>
        </p>

        <?php if ($orari === []): ?>
            <p>Per oggi non ci sono più orari disponibili in questa sede.</p>
        <?php else: ?>
            <p>
                <label for="orario">Orario di ritiro</label>
                <select id="orario" name="orario" required="required">
                    <?php foreach ($orari as $orarioDisponibile): ?>
                        <option value="<?php echo e($orarioDisponibile); ?>"><?php echo e($orarioDisponibile); ?></option>
                    <?php endforeach; ?>
                </select>
            </p>
        <?php endif; ?>
    </fieldset>

    <fieldset>
        <legend>Pagamento</legend>

        <p>
            <input type="radio" id="pagamento-carta" name="metodo_pagamento" value="carta"
                checked="checked" required="required" />
            <label for="pagamento-carta">Con carta, adesso</label>
        </p>

        <p>
            <input type="radio" id="pagamento-contanti" name="metodo_pagamento" value="contanti" />
            <label for="pagamento-contanti">In contanti, al ritiro</label>
        </p>
    </fieldset>

    <?php if ($orari !== []): ?>
        <p><button type="submit" name="azione" value="conferma">Conferma l'ordine</button></p>
    <?php endif; ?>
</form>

<p><a href="<?php echo e(url('carrello')); ?>">Torna al carrello</a></p>
