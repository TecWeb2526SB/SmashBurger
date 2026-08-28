<?php
/**
 * Modulo di una sede: anagrafica e, per le sedi esistenti, orari settimanali.
 *
 * Riceve $sede, che vale null in inserimento, insieme a $valori, $errori, $orari e
 * $giorni.
 */
?>
<h1>Pannello di controllo</h1>

<?php include __DIR__ . '/navigazione.php'; ?>

<h2><?php echo $sede === null ? 'Nuova sede' : 'Modifica la sede di ' . e($sede['citta']); ?></h2>

<?php if ($errori !== []): ?>
    <div role="alert">
        <h3>Controlla i dati inseriti</h3>
        <ul>
            <?php foreach ($errori as $campo => $testo): ?>
                <li><a href="#<?php echo e($campo); ?>"><?php echo e($testo); ?></a></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<form method="post"
    action="<?php echo e($sede === null ? url('controllo-sede') : url('controllo-sede', ['id' => $sede['id']])); ?>">
    <?php echo campo_csrf(); ?>

    <fieldset>
        <legend>Anagrafica</legend>

        <?php
        $etichette = [
            'nome' => 'Nome della sede',
            'citta' => 'Città',
            'provincia' => 'Provincia',
            'indirizzo' => 'Indirizzo',
            'cap' => 'CAP',
            'telefono' => 'Telefono',
            'email' => 'Email',
            'note_ritiro' => 'Come arrivare e dove ritirare',
        ];
        ?>
        <?php foreach ($etichette as $campo => $etichetta): ?>
            <p>
                <label for="<?php echo e($campo); ?>"><?php echo e($etichetta); ?></label>
                <input type="<?php echo $campo === 'email' ? 'email' : 'text'; ?>"
                    id="<?php echo e($campo); ?>" name="<?php echo e($campo); ?>"
                    <?php echo $campo === 'note_ritiro' ? '' : 'required="required"'; ?>
                    value="<?php echo e((string) $valori[$campo]); ?>" />
                <?php if ($campo === 'note_ritiro'): ?>
                    <small id="aiuto-note_ritiro">
                        Un'informazione pratica che cambia da sede a sede: parcheggio, accesso,
                        ingresso del ritiro.
                    </small>
                <?php endif; ?>
                <?php if (isset($errori[$campo])): ?>
                    <small id="errore-<?php echo e($campo); ?>"><?php echo e($errori[$campo]); ?></small>
                <?php endif; ?>
            </p>
        <?php endforeach; ?>

        <p>
            <label for="ordine">Posizione nell'elenco</label>
            <input type="number" id="ordine" name="ordine" min="0" max="255"
                value="<?php echo (int) $valori['ordine']; ?>" />
        </p>

        <p>
            <input type="checkbox" id="attiva" name="attiva" value="1"
                <?php echo (int) $valori['attiva'] === 1 ? 'checked="checked"' : ''; ?> />
            <label for="attiva">Sede attiva</label>
        </p>
    </fieldset>

    <p>
        <button type="submit"><?php echo $sede === null ? 'Crea la sede' : 'Salva le modifiche'; ?></button>
        <a href="<?php echo e(url('controllo-sedi')); ?>">Annulla</a>
    </p>
</form>

<?php if ($sede !== null): ?>
    <h3>Orari di apertura</h3>

    <form method="post" action="<?php echo e(url('controllo-sede', ['id' => $sede['id']])); ?>">
        <?php echo campo_csrf(); ?>
        <input type="hidden" name="azione" value="orari" />

        <table>
            <caption>Fasce di apertura per giorno</caption>
            <thead>
                <tr>
                    <th scope="col">Giorno</th>
                    <th scope="col">Apertura</th>
                    <th scope="col">Chiusura</th>
                    <th scope="col">Chiuso</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orari as $riga): $giorno = (int) $riga['giorno']; ?>
                    <tr>
                        <th scope="row"><?php echo e($giorni[$giorno]); ?></th>
                        <td>
                            <label for="apertura-<?php echo $giorno; ?>">Apertura di <?php echo e($giorni[$giorno]); ?></label>
                            <input type="time" id="apertura-<?php echo $giorno; ?>"
                                name="giorni[<?php echo $giorno; ?>][apertura]"
                                value="<?php echo e(orario($riga['apertura'])); ?>" />
                        </td>
                        <td>
                            <label for="chiusura-<?php echo $giorno; ?>">Chiusura di <?php echo e($giorni[$giorno]); ?></label>
                            <input type="time" id="chiusura-<?php echo $giorno; ?>"
                                name="giorni[<?php echo $giorno; ?>][chiusura]"
                                value="<?php echo e(orario($riga['chiusura'])); ?>" />
                        </td>
                        <td>
                            <label for="chiuso-<?php echo $giorno; ?>">Chiuso <?php echo e($giorni[$giorno]); ?></label>
                            <input type="checkbox" id="chiuso-<?php echo $giorno; ?>"
                                name="giorni[<?php echo $giorno; ?>][chiuso]" value="1"
                                <?php echo (int) $riga['chiuso'] === 1 ? 'checked="checked"' : ''; ?> />
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <p><button type="submit">Salva gli orari</button></p>
    </form>
<?php endif; ?>
