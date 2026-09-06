<?php
/**
 * Durata, numero di persone e nota di una prenotazione.
 *
 * Estratto da prenota.php per tenere ogni vista entro il limite di righe. Usa le stesse
 * variabili: $durate, $valori ed $errori.
 *
 * La nota si ferma a CARATTERI_NOTA_PRENOTAZIONE caratteri sia qui sia nel controllo
 * lato server sia nella colonna del database: la sede la legge per intero dentro una
 * riga della tabella delle prenotazioni.
 */
?>
<fieldset>
    <legend>Dettagli</legend>

    <p>
        <label for="durata">Per quanto tempo</label>
        <select id="durata" name="durata"
            <?php if (isset($errori['durata'])): ?>data-stato="errore"<?php endif; ?>>
            <?php foreach ($durate as $minuti => $etichetta): ?>
                <option value="<?php echo (int) $minuti; ?>"
                    <?php echo (int) $valori['durata'] === (int) $minuti ? 'selected="selected"' : ''; ?>>
                    <?php echo e($etichetta); ?>
                </option>
            <?php endforeach; ?>
        </select>
    </p>

    <p>
        <label for="numero_persone">Quante persone siete</label>
        <input type="number" id="numero_persone" name="numero_persone"
            required="required" min="1" max="80"
            value="<?php echo e($valori['numero_persone']); ?>"
            <?php if (isset($errori['numero_persone'])): ?>data-stato="errore"<?php endif; ?> />
    </p>

    <p>
        <label for="note">Note per la sede</label>
        <textarea id="note" name="note" rows="3"
            maxlength="<?php echo CARATTERI_NOTA_PRENOTAZIONE; ?>"
            aria-describedby="aiuto-note"
            <?php if (isset($errori['note'])): ?>data-stato="errore"<?php endif; ?>><?php echo e($valori['note']); ?></textarea>
        <small id="aiuto-note">
            Al massimo <?php echo CARATTERI_NOTA_PRENOTAZIONE; ?> caratteri: la sede la legge
            nell&apos;elenco delle prenotazioni.
        </small>
    </p>

    <p><button type="submit">Invia la richiesta</button></p>
</fieldset>
