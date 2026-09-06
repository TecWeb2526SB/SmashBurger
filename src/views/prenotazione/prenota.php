<?php
/**
 * Prenotazione della sala eventi.
 *
 * Riceve $sedi, $sede, $data, $fasce, $occupazione, $durate, $valori, $errori, $minimo
 * e $massimo.
 *
 * Il primo modulo viaggia in GET perchè non modifica nulla: serve solo a mostrare gli
 * orari liberi del giorno scelto. Il riquadro dei dettagli sta in prenota-dettagli.php,
 * per tenere ogni vista entro il limite di righe.
 */
?>
<h1>Prenota la sala eventi</h1>

<p>
    Scegli il giorno, l'orario di inizio e per quanto ti serve la sala, da
    <?php echo e(reset($durate)); ?> a <?php echo e(end($durate)); ?>. La prenotazione
    viene poi confermata dalla sede.
</p>

<?php if ($errori !== []): ?>
    <section class="avviso" role="alert" tabindex="-1" data-tipo="errore">
        <h2>Controlla questi campi</h2>
        <ul>
            <?php foreach ($errori as $campo => $testo): ?>
                <li><a href="#<?php echo e($campo); ?>"><?php echo e($testo); ?></a></li>
            <?php endforeach; ?>
        </ul>
    </section>
<?php endif; ?>

<form method="get" action="<?php echo e(url('prenota')); ?>">
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
            <p>Quel giorno la sede è chiusa: scegli un altro giorno.</p>
        <?php else: ?>
            <?php require __DIR__ . '/occupazione.php'; ?>

            <form method="post" action="<?php echo e(url('prenota')); ?>">
                <?php echo campo_csrf(); ?>
                <input type="hidden" name="sede" value="<?php echo e($sede['slug']); ?>" />
                <input type="hidden" name="data" value="<?php echo e($data); ?>" />

                <fieldset id="fascia">
                    <legend>Scegli l'orario di inizio</legend>

                    <ul class="scelte">
                        <?php foreach ($fasce as $fascia): ?>
                            <li>
                                <label for="fascia-<?php echo e(str_replace(':', '', $fascia['inizio'])); ?>">
                                    <input type="radio" id="fascia-<?php echo e(str_replace(':', '', $fascia['inizio'])); ?>"
                                        name="fascia" value="<?php echo e($fascia['inizio']); ?>" required="required"
                                        <?php echo $fascia['occupata'] ? 'disabled="disabled"' : ''; ?>
                                        <?php echo $valori['fascia'] === $fascia['inizio'] ? 'checked="checked"' : ''; ?> />
                                    <span>
                                        <?php echo e($fascia['etichetta']); ?>
                                        <?php if ($fascia['occupata']): ?>
                                            <span class="etichetta" data-tipo="negativo">non disponibile</span>
                                        <?php else: ?>
                                            <span class="solo-lettori">
                                                disponibile fino a <?php echo (int) $fascia['massimo']; ?> minuti
                                            </span>
                                        <?php endif; ?>
                                    </span>
                                </label>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </fieldset>

                <?php require __DIR__ . '/prenota-dettagli.php'; ?>
            </form>
        <?php endif; ?>
    </section>
<?php endif; ?>

<p class="navigazione-pagina">
    <a class="collegamento-indietro" href="<?php echo e(url('sedi')); ?>"><span class="segno-collegamento" aria-hidden="true">&lt;</span><span>Torna alle sedi</span></a>
    <a href="<?php echo e(url('area-personale')); ?>">Le tue prenotazioni</a>
</p>
