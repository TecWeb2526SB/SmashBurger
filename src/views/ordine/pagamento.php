<?php
/**
 * Conferma dell'ordine: riepilogo, scelta della sede, dell'orario e del pagamento.
 *
 * Riceve $sedi, $sedeId, $orari, $righe, $totale ed $errore dal controller.
 *
 * La scelta della sede sta in un modulo separato: così il modulo di conferma ha un solo
 * pulsante di invio e il tasto Invio conferma l'ordine invece di ricaricare gli orari.
 * L'attributo data-modulo identifica la parte aggiornabile senza ricaricare la pagina.
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
                <td colspan="2">Totale</td>
                <td><?php echo e(prezzo((int) $totale)); ?></td>
            </tr>
        </tfoot>
    </table>
</section>

<section>
    <h2>Sede di ritiro</h2>

    <form method="post" action="<?php echo e(url('pagamento')); ?>" data-modulo="sede">
        <?php echo campo_csrf(); ?>
        <input type="hidden" name="azione" value="cambia-sede" />

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
            <button type="submit">Aggiorna gli orari</button>
        </p>
    </form>
</section>

<section id="conferma">
    <h2>Orario e pagamento</h2>

    <?php if ($orari === []): ?>
        <p>Per oggi non ci sono più orari disponibili in questa sede. Scegli un'altra sede.</p>
    <?php else: ?>
        <form method="post" action="<?php echo e(url('pagamento')); ?>">
            <?php echo campo_csrf(); ?>
            <input type="hidden" name="azione" value="conferma" />
            <input type="hidden" name="sede_id" value="<?php echo (int) $sedeId; ?>" />

            <fieldset>
                <legend>Orario di ritiro</legend>

                <p>
                    <label for="orario">Orario</label>
                    <select id="orario" name="orario" required="required">
                        <?php foreach ($orari as $orarioDisponibile): ?>
                            <option value="<?php echo e($orarioDisponibile); ?>"><?php echo e($orarioDisponibile); ?></option>
                        <?php endforeach; ?>
                    </select>
                </p>
            </fieldset>

            <fieldset>
                <legend>Pagamento</legend>

                <ul class="scelte">
                    <li>
                        <input type="radio" id="pagamento-carta" name="metodo_pagamento" value="carta"
                            checked="checked" required="required" />
                        <label for="pagamento-carta">
                            <?php echo icona('carta'); ?> Con carta, adesso
                        </label>
                    </li>
                    <li>
                        <input type="radio" id="pagamento-contanti" name="metodo_pagamento" value="contanti" />
                        <label for="pagamento-contanti">
                            <?php echo icona('contanti'); ?> In contanti, al ritiro
                        </label>
                    </li>
                </ul>
            </fieldset>

            <p class="navigazione-pagina">
                <a class="pulsante" data-tipo="indietro" href="<?php echo e(url('carrello')); ?>">
                    <?php echo icona('freccia-sinistra'); ?> Torna al carrello
                </a>
                <button type="submit" data-tipo="positivo">
                    Conferma l'ordine <?php echo icona('freccia-destra'); ?>
                </button>
            </p>
        </form>
    <?php endif; ?>
</section>
