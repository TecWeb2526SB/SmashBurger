<?php
/**
 * Conferma dell'ordine. Riceve $carrello, $righe, $totale, $slot, $valori ed $errori.
 */
?>
<h1>Conferma il tuo ordine</h1>

<?php if ($errori !== []): ?>
    <section class="avviso" role="alert" data-tipo="errore">
        <h2>Controlla questi campi</h2>
        <ul>
            <?php foreach ($errori as $campo => $testo): ?>
                <li><a href="#<?php echo e($campo); ?>"><?php echo e($testo); ?></a></li>
            <?php endforeach; ?>
        </ul>
    </section>
<?php endif; ?>

<section>
    <h2>Che cosa hai ordinato</h2>
    <table>
        <caption>Riepilogo dell ordine da <?php echo e($carrello['citta']); ?></caption>
        <thead>
            <tr>
                <th scope="col">Prodotto</th>
                <th scope="col">Quantita</th>
                <th scope="col">Totale</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($righe as $riga): ?>
                <tr>
                    <th scope="row"><?php echo e($riga['nome']); ?></th>
                    <td><?php echo (int) $riga['quantita']; ?></td>
                    <td><?php echo e(prezzo((int) $riga['totale_riga'])); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
        <tfoot>
            <tr>
                <th scope="row" colspan="2">Totale</th>
                <td><?php echo e(prezzo($totale)); ?></td>
            </tr>
        </tfoot>
    </table>
</section>

<form method="post" action="<?php echo e(url('pagamento')); ?>" data-modulo="sede">
    <?php echo campo_csrf(); ?>

    <fieldset>
        <legend id="modalita">Come vuoi ricevere l'ordine</legend>

        <ul class="scelte">
            <li>
                <label for="modalita-ritiro">
                    <input type="radio" id="modalita-ritiro" name="modalita" value="ritiro"
                        <?php echo $valori['modalita'] === 'ritiro' ? 'checked="checked"' : ''; ?> />
                    <span>Ritiro in sede a <?php echo e($carrello['citta']); ?></span>
                </label>
            </li>
            <li>
                <label for="modalita-domicilio">
                    <input type="radio" id="modalita-domicilio" name="modalita" value="domicilio"
                        <?php echo $valori['modalita'] === 'domicilio' ? 'checked="checked"' : ''; ?> />
                    <span>Consegna a domicilio</span>
                </label>
            </li>
        </ul>

        <p>
            <label for="ritiro_previsto">Orario di ritiro</label>
            <select id="ritiro_previsto" name="ritiro_previsto"
                <?php if (isset($errori['ritiro_previsto'])): ?>aria-describedby="errore-ritiro" data-stato="errore"<?php endif; ?>>
                <?php foreach ($slot as $valore => $etichetta): ?>
                    <option value="<?php echo e($valore); ?>"
                        <?php echo $valore === $valori['ritiro_previsto'] ? 'selected="selected"' : ''; ?>>
                        <?php echo e($etichetta); ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <?php if (isset($errori['ritiro_previsto'])): ?>
                <small id="errore-ritiro"><?php echo e($errori['ritiro_previsto']); ?></small>
            <?php endif; ?>
        </p>
        <p><small>Serve solo per il ritiro in sede. Per la consegna a domicilio l'orario lo decide il corriere.</small></p>
    </fieldset>

    <fieldset>
        <legend>Dove consegniamo</legend>

        <p><small>Compila solo se hai scelto la consegna a domicilio.</small></p>

        <?php
        $campi = [
            'indirizzo' => ['Indirizzo e numero civico', 'address-line1', 160],
            'citta' => ['Citta', 'address-level2', 80],
            'provincia' => ['Provincia', 'address-level1', 2],
            'cap' => ['CAP', 'postal-code', 5],
            'paese' => ['Paese', 'country-name', 60],
            'telefono' => ['Telefono', 'tel', 30],
        ];
        ?>
        <?php foreach ($campi as $campo => $dettagli): ?>
            <p>
                <label for="<?php echo e($campo); ?>"><?php echo e($dettagli[0]); ?></label>
                <input type="text" id="<?php echo e($campo); ?>" name="<?php echo e($campo); ?>"
                    maxlength="<?php echo (int) $dettagli[2]; ?>" autocomplete="<?php echo e($dettagli[1]); ?>"
                    value="<?php echo e($valori[$campo]); ?>"
                    <?php if (isset($errori[$campo])): ?>aria-describedby="errore-<?php echo e($campo); ?>" data-stato="errore"<?php endif; ?> />
                <?php if (isset($errori[$campo])): ?>
                    <small id="errore-<?php echo e($campo); ?>"><?php echo e($errori[$campo]); ?></small>
                <?php endif; ?>
            </p>
        <?php endforeach; ?>
    </fieldset>

    <fieldset>
        <legend id="metodo_pagamento">Come vuoi pagare</legend>

        <ul class="scelte">
            <?php foreach (['carta' => 'Carta', 'contanti' => 'Contanti alla consegna o al ritiro'] as $valore => $etichetta): ?>
                <li>
                    <label for="pagamento-<?php echo e($valore); ?>">
                        <input type="radio" id="pagamento-<?php echo e($valore); ?>" name="metodo_pagamento"
                            value="<?php echo e($valore); ?>"
                            <?php echo $valori['metodo_pagamento'] === $valore ? 'checked="checked"' : ''; ?> />
                        <span><?php echo e($etichetta); ?></span>
                    </label>
                </li>
            <?php endforeach; ?>
        </ul>

        <p><small>Il pagamento e' simulato: non chiediamo e non conserviamo nessun dato della carta.</small></p>
    </fieldset>

    <p class="navigazione-pagina">
        <a class="collegamento-indietro" href="<?php echo e(url('carrello')); ?>"><span class="segno-collegamento" aria-hidden="true">&lt;</span><span>Torna al carrello</span></a>
        <button type="submit">Conferma l'ordine</button>
    </p>
</form>
