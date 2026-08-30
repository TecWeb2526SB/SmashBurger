<?php
/**
 * Prenotazione della sala eventi.
 *
 * Riceve $sedi, $sede, $data, $fasce, $valori, $errori, $minimo e $massimo.
 * Il primo modulo viaggia in GET perche' non modifica nulla: serve solo a mostrare le
 * orari liberi del giorno scelto.
 */
?>
<h1>Prenota la sala eventi</h1>

<p>
    Scegli l'orario di inizio: la sala resta tua per <?php echo (int) ORE_PRENOTAZIONE; ?> ore,
    oppure fino alla chiusura se la sede chiude prima. La prenotazione viene poi
    confermata dalla sede.
</p>

<?php if ($errori !== []): ?>
    <section class="avviso" role="alert" data-tipo="errore">
        <h2>Controlla questi campi</h2>
        <ul>
            <?php foreach ($errori as $campo => $testo): ?>
                <li><?php echo e($testo); ?></li>
            <?php endforeach; ?>
        </ul>
    </section>
<?php endif; ?>

<form method="get" action="<?php echo e(url('prenota')); ?>" data-modulo="fasce">
    <fieldset>
        <legend>Sede e giorno</legend>

        <p>
            <label for="sede">Sede</label>
            <select id="sede" name="sede" required="required">
                <option value="">Scegli la sede</option>
                <?php foreach ($sedi as $unaSede): ?>
                    <?php if ((int) $unaSede['sala_eventi_disponibile'] === 1): ?>
                        <option value="<?php echo e($unaSede['slug']); ?>"
                            <?php echo $sede !== null && $unaSede['id'] === $sede['id'] ? 'selected="selected"' : ''; ?>>
                            <?php echo e($unaSede['citta']); ?>
                        </option>
                    <?php endif; ?>
                <?php endforeach; ?>
            </select>
        </p>

        <p>
            <label for="data">Giorno</label>
            <input type="date" id="data" name="data" required="required"
                min="<?php echo e($minimo); ?>" max="<?php echo e($massimo); ?>"
                value="<?php echo e($data); ?>" />
        </p>

        <p><button type="submit">Vedi gli orari liberi</button></p>
    </fieldset>
</form>

<?php if ($sede !== null && (int) $sede['sala_eventi_disponibile'] !== 1): ?>
    <p class="avviso" role="status" data-tipo="attenzione">
        <strong>Attenzione:</strong> la sala di <?php echo e($sede['citta']); ?> non accetta
        prenotazioni in questo periodo.
    </p>
<?php elseif ($sede !== null && $data !== ''): ?>
    <section>
        <h2>Orari del <?php echo e(data_breve($data)); ?> a <?php echo e($sede['citta']); ?></h2>

        <?php if ($fasce === []): ?>
            <p>Quel giorno la sede e' chiusa: scegli un altro giorno.</p>
        <?php else: ?>
            <form method="post" action="<?php echo e(url('prenota')); ?>">
                <?php echo campo_csrf(); ?>
                <input type="hidden" name="sede" value="<?php echo e($sede['slug']); ?>" />
                <input type="hidden" name="data" value="<?php echo e($data); ?>" />

                <fieldset>
                    <legend>Scegli l'orario di inizio</legend>

                    <ul class="scelte">
                        <?php foreach ($fasce as $fascia): ?>
                            <li>
                                <input type="radio" id="fascia-<?php echo e(str_replace(':', '', $fascia['inizio'])); ?>"
                                    name="fascia" value="<?php echo e($fascia['inizio']); ?>" required="required"
                                    <?php echo $fascia['occupata'] ? 'disabled="disabled"' : ''; ?>
                                    <?php echo $valori['fascia'] === $fascia['inizio'] ? 'checked="checked"' : ''; ?> />
                                <label for="fascia-<?php echo e(str_replace(':', '', $fascia['inizio'])); ?>">
                                    <?php echo e($fascia['etichetta']); ?>
                                    <?php if ($fascia['occupata']): ?>
                                        <span class="etichetta" data-tipo="negativo">non disponibile</span>
                                    <?php endif; ?>
                                </label>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </fieldset>

                <fieldset>
                    <legend>Dettagli</legend>

                    <p>
                        <label for="numero_persone">Quante persone siete</label>
                        <input type="number" id="numero_persone" name="numero_persone"
                            required="required" min="1" max="80"
                            value="<?php echo e($valori['numero_persone']); ?>"
                            <?php if (isset($errori['numero_persone'])): ?>data-stato="errore"<?php endif; ?> />
                    </p>

                    <p>
                        <label for="note">Note per la sede</label>
                        <textarea id="note" name="note" rows="3" maxlength="255"><?php echo e($valori['note']); ?></textarea>
                    </p>

                    <p><button type="submit">Invia la richiesta</button></p>
                </fieldset>
            </form>
        <?php endif; ?>
    </section>
<?php endif; ?>

<p class="navigazione-pagina">
    <a href="<?php echo e(url('sedi')); ?>">Torna alle sedi</a>
    <a href="<?php echo e(url('area-personale')); ?>">Le tue prenotazioni</a>
</p>
