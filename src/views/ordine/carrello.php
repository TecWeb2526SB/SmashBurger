<?php
/**
 * Contenuto del carrello: un modulo unico per tutte le quantità e la richiesta di
 * conferma prima di svuotare.
 *
 * Riceve $righe, $articoli, $totale e $confermaSvuota dal controller. L'attributo
 * data-modulo identifica il modulo aggiornato senza ricaricare la pagina.
 */
?>
<h1>Carrello</h1>

<?php if ($confermaSvuota): ?>
    <section role="alert">
        <h2>Vuoi svuotare il carrello?</h2>
        <p>I prodotti scelti verranno tolti tutti insieme.</p>

        <form method="post" action="<?php echo e(url('carrello')); ?>">
            <?php echo campo_csrf(); ?>
            <input type="hidden" name="azione" value="svuota" />
            <p>
                <button type="submit">Svuota il carrello</button>
                <a href="<?php echo e(url('carrello')); ?>">Annulla</a>
            </p>
        </form>
    </section>
<?php endif; ?>

<?php if ($righe === []): ?>
    <p>Il carrello è vuoto.</p>
    <p><a href="<?php echo e(url('prodotti')); ?>">Vai al menu</a></p>
<?php else: ?>
    <form method="post" action="<?php echo e(url('carrello')); ?>" data-modulo="carrello">
        <?php echo campo_csrf(); ?>
        <input type="hidden" name="azione" value="aggiorna" />

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
                            <button type="submit" name="diminuisci" value="<?php echo (int) $riga['id']; ?>"
                                aria-label="Togli una unità di <?php echo e($riga['nome']); ?>">-</button>

                            <label class="solo-lettori" for="quantita-<?php echo (int) $riga['id']; ?>">
                                Quantità di <?php echo e($riga['nome']); ?>
                            </label>
                            <input type="number" id="quantita-<?php echo (int) $riga['id']; ?>"
                                name="quantita[<?php echo (int) $riga['id']; ?>]"
                                value="<?php echo (int) $riga['quantita']; ?>"
                                min="0" max="<?php echo QUANTITA_MASSIMA; ?>" required="required" />

                            <button type="submit" name="aumenta" value="<?php echo (int) $riga['id']; ?>"
                                aria-label="Aggiungi una unità di <?php echo e($riga['nome']); ?>">+</button>
                        </td>
                        <td><?php echo e(prezzo((int) $riga['subtotale_centesimi'])); ?></td>
                        <td>
                            <button type="submit" name="togli" value="<?php echo (int) $riga['id']; ?>">
                                Togli <?php echo e($riga['nome']); ?>
                            </button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="2">Totale, <?php echo (int) $articoli; ?> articoli</td>
                    <td colspan="3"><?php echo e(prezzo((int) $totale)); ?></td>
                </tr>
            </tfoot>
        </table>

        <p>
            <button type="submit">Aggiorna il carrello</button>
            <small id="aiuto-quantita">Cambia più quantità insieme e premi Aggiorna, oppure usa i pulsanti sulla riga.</small>
        </p>
    </form>

    <p><a href="<?php echo e(url('pagamento')); ?>">Scegli sede e orario di ritiro</a></p>
    <p><a href="<?php echo e(url('carrello', ['svuota' => 1])); ?>">Svuota il carrello</a></p>
<?php endif; ?>
