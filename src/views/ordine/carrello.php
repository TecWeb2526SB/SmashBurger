<?php
/**
 * Contenuto del carrello: righe con quantità modificabile, totale e passaggio al
 * pagamento. Riceve $righe, $articoli e $totale dal controller.
 */
?>
<h1>Carrello</h1>

<?php if ($righe === []): ?>
    <p>Il carrello è vuoto.</p>
    <p><a href="<?php echo e(url('prodotti')); ?>">Vai al menu</a></p>
<?php else: ?>
    <table>
        <caption>Prodotti nel carrello</caption>
        <thead>
            <tr>
                <th scope="col">Prodotto</th>
                <th scope="col">Prezzo</th>
                <th scope="col">Quantità</th>
                <th scope="col">Subtotale</th>
                <th scope="col">Azioni</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($righe as $riga): ?>
                <tr>
                    <th scope="row"><?php echo e($riga['nome']); ?></th>
                    <td><?php echo e(prezzo((int) $riga['prezzo_centesimi'])); ?></td>
                    <td>
                        <form method="post" action="<?php echo e(url('carrello')); ?>">
                            <?php echo campo_csrf(); ?>
                            <input type="hidden" name="azione" value="aggiorna" />
                            <input type="hidden" name="riga_id" value="<?php echo (int) $riga['id']; ?>" />
                            <label for="quantita-<?php echo (int) $riga['id']; ?>">
                                Quantità di <?php echo e($riga['nome']); ?>
                            </label>
                            <input type="number" id="quantita-<?php echo (int) $riga['id']; ?>"
                                name="quantita" value="<?php echo (int) $riga['quantita']; ?>"
                                min="1" max="<?php echo QUANTITA_MASSIMA; ?>" required="required" />
                            <button type="submit">Aggiorna</button>
                        </form>
                    </td>
                    <td><?php echo e(prezzo((int) $riga['subtotale_centesimi'])); ?></td>
                    <td>
                        <form method="post" action="<?php echo e(url('carrello')); ?>">
                            <?php echo campo_csrf(); ?>
                            <input type="hidden" name="azione" value="rimuovi" />
                            <input type="hidden" name="riga_id" value="<?php echo (int) $riga['id']; ?>" />
                            <button type="submit">Togli <?php echo e($riga['nome']); ?></button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
        <tfoot>
            <tr>
                <th scope="row" colspan="3">Totale, <?php echo (int) $articoli; ?> articoli</th>
                <td colspan="2"><?php echo e(prezzo((int) $totale)); ?></td>
            </tr>
        </tfoot>
    </table>

    <p><a href="<?php echo e(url('pagamento')); ?>">Scegli sede e orario di ritiro</a></p>

    <form method="post" action="<?php echo e(url('carrello')); ?>">
        <?php echo campo_csrf(); ?>
        <input type="hidden" name="azione" value="svuota" />
        <p><button type="submit">Svuota il carrello</button></p>
    </form>
<?php endif; ?>
