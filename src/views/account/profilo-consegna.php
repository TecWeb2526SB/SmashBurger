<?php
/**
 * Riquadri dell'indirizzo di consegna e del metodo di pagamento preferito.
 *
 * Sono estratti da profilo.php per tenere ogni vista entro il limite di righe. Usano le
 * stesse variabili, comprese $utente e la funzione $erroreDi.
 */

$haIndirizzo = $utente['indirizzo'] !== null && $utente['indirizzo'] !== '';

$campiConsegna = [
    'indirizzo' => ['Indirizzo e numero civico', 'address-line1', 160],
    'citta' => ['Citta', 'address-level2', 80],
    'provincia' => ['Provincia', 'address-level1', 2],
    'cap' => ['CAP', 'postal-code', 5],
    'paese' => ['Paese', 'country-name', 60],
    'telefono' => ['Telefono', 'tel', 30],
];
?>
<section>
    <h2>Indirizzo di consegna</h2>

    <p>
        Serve solo se scegli la consegna a domicilio. Puoi salvarlo qui per non
        riscriverlo a ogni ordine.
    </p>

    <form method="post" action="<?php echo e(url('profilo')); ?>">
        <?php echo campo_csrf(); ?>
        <input type="hidden" name="azione" value="consegna" />

        <fieldset>
            <legend>Dove consegniamo</legend>

            <?php foreach ($campiConsegna as $campo => $dettagli): ?>
                <?php $errore = $erroreDi('consegna', $campo); ?>
                <p>
                    <label for="cons-<?php echo e($campo); ?>"><?php echo e($dettagli[0]); ?></label>
                    <input type="text" id="cons-<?php echo e($campo); ?>" name="<?php echo e($campo); ?>"
                        required="required" maxlength="<?php echo (int) $dettagli[2]; ?>"
                        autocomplete="<?php echo e($dettagli[1]); ?>"
                        value="<?php echo e($utente[$campo] ?? ''); ?>"
                        <?php if ($errore !== null): ?>aria-describedby="errore-cons-<?php echo e($campo); ?>" data-stato="errore"<?php endif; ?> />
                    <?php if ($errore !== null): ?>
                        <small id="errore-cons-<?php echo e($campo); ?>"><?php echo e($errore); ?></small>
                    <?php endif; ?>
                </p>
            <?php endforeach; ?>

            <p><button type="submit">Salva l'indirizzo</button></p>
        </fieldset>
    </form>

    <?php if ($haIndirizzo): ?>
        <form method="post" action="<?php echo e(url('profilo')); ?>">
            <?php echo campo_csrf(); ?>
            <input type="hidden" name="azione" value="rimuovi-consegna" />
            <p><button type="submit">Rimuovi l'indirizzo salvato</button></p>
        </form>
    <?php endif; ?>
</section>

<section>
    <h2>Metodo di pagamento preferito</h2>

    <p>
        Del metodo scegliamo solo l'etichetta: il sito non chiede e non conserva nessun
        dato della tua carta.
    </p>

    <form method="post" action="<?php echo e(url('profilo')); ?>">
        <?php echo campo_csrf(); ?>
        <input type="hidden" name="azione" value="pagamento" />

        <fieldset>
            <legend>Quale preferisci</legend>

            <ul class="scelte">
                <?php foreach (['' => 'Nessuna preferenza', 'carta' => 'Carta', 'contanti' => 'Contanti'] as $valore => $etichetta): ?>
                    <li>
                        <input type="radio" id="metodo-<?php echo e($valore === '' ? 'nessuno' : $valore); ?>"
                            name="metodo" value="<?php echo e($valore); ?>"
                            <?php echo ($utente['metodo_pagamento_preferito'] ?? '') === $valore ? 'checked="checked"' : ''; ?> />
                        <label for="metodo-<?php echo e($valore === '' ? 'nessuno' : $valore); ?>">
                            <?php echo e($etichetta); ?>
                        </label>
                    </li>
                <?php endforeach; ?>
            </ul>

            <p><button type="submit">Salva la preferenza</button></p>
        </fieldset>
    </form>
</section>
