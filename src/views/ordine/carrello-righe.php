<?php
/**
 * Dettaglio dei prodotti scelti, con i comandi di quantita' e rimozione.
 *
 * Ogni pulsante porta il nome dell'azione e, come proprio valore, l'identificativo del
 * prodotto: il browser invia solo quello premuto, quindi un modulo solo basta per tutta
 * la tabella. La quantita' è testo e non un campo, perchè si cambia con i pulsanti e
 * non c'è niente da riscrivere a mano.
 */
?>
<form method="post" action="<?php echo e(url('carrello')); ?>" data-modulo="carrello">
    <?php echo campo_csrf(); ?>

    <table>
        <caption>Prodotti nel carrello</caption>
        <thead>
            <tr>
                <th scope="col">Prodotto</th>
                <th scope="col">Prezzo</th>
                <th scope="col">Quantità</th>
                <th scope="col">Totale</th>
                <th scope="col" data-colonna="azioni">Togli</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($righe as $riga): ?>
                <tr>
                    <th scope="row"><?php echo e($riga['nome']); ?></th>
                    <td><?php echo e(prezzo((int) $riga['prezzo_centesimi'])); ?></td>
                    <td>
                        <button type="submit" name="diminuisci" value="<?php echo (int) $riga['prodotto_id']; ?>">
                            <span aria-hidden="true">-</span>
                            <span class="solo-lettori">Una unità in meno di <?php echo e($riga['nome']); ?></span>
                        </button>

                        <span class="quantita"><?php echo (int) $riga['quantita']; ?></span>

                        <button type="submit" name="aggiungi" value="<?php echo (int) $riga['prodotto_id']; ?>">
                            <span aria-hidden="true">+</span>
                            <span class="solo-lettori">Una unità in più di <?php echo e($riga['nome']); ?></span>
                        </button>
                    </td>
                    <td><?php echo e(prezzo((int) $riga['totale_riga'])); ?></td>
                    <td data-colonna="azioni">
                        <button type="submit" name="togli" value="<?php echo (int) $riga['prodotto_id']; ?>">
                            Togli
                            <span class="solo-lettori"><?php echo e($riga['nome']); ?> dal carrello</span>
                        </button>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</form>

<form method="post" action="<?php echo e(url('carrello')); ?>" data-modulo="svuota">
    <?php echo campo_csrf(); ?>
    <input type="hidden" name="azione" value="svuota" />
    <p><button class="azione-svuota" type="submit">Svuota il carrello</button></p>
</form>
