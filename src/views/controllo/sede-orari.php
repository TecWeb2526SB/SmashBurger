<?php
/**
 * Orari settimanali di una sede.
 *
 * Estratto da sede.php per tenere ogni vista entro il limite di righe. Usa le stesse
 * variabili: $sede, $orari e $giorni.
 */
?>
<section>
    <h2>Orari settimanali</h2>

    <form method="post" action="<?php echo e(url('controllo-sede')); ?>" data-modulo="orari">
        <?php echo campo_csrf(); ?>
        <input type="hidden" name="azione" value="orari" />
        <input type="hidden" name="sede_id" value="<?php echo (int) $sede['id']; ?>" />

        <table>
            <caption>Apertura di <?php echo e($sede['citta']); ?> giorno per giorno</caption>
            <thead>
                <tr>
                    <th scope="col">Giorno</th>
                    <th scope="col">Apre</th>
                    <th scope="col">Chiude</th>
                    <th scope="col">Chiuso</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($giorni as $numero => $nome): ?>
                    <?php $orario = $orari[$numero]; ?>
                    <tr>
                        <th scope="row"><?php echo e($nome); ?></th>
                        <td>
                            <label class="solo-lettori" for="apre-<?php echo (int) $numero; ?>">
                                Apertura di <?php echo e($nome); ?>
                            </label>
                            <input type="time" id="apre-<?php echo (int) $numero; ?>"
                                name="apertura[<?php echo (int) $numero; ?>]"
                                value="<?php echo e(substr((string) $orario['apertura'], 0, 5)); ?>" />
                        </td>
                        <td>
                            <label class="solo-lettori" for="chiude-<?php echo (int) $numero; ?>">
                                Chiusura di <?php echo e($nome); ?>
                            </label>
                            <input type="time" id="chiude-<?php echo (int) $numero; ?>"
                                name="chiusura[<?php echo (int) $numero; ?>]"
                                value="<?php echo e(substr((string) $orario['chiusura'], 0, 5)); ?>" />
                        </td>
                        <td>
                            <input type="checkbox" id="chiuso-<?php echo (int) $numero; ?>"
                                name="chiuso[<?php echo (int) $numero; ?>]" value="1"
                                <?php echo (int) $orario['chiuso'] === 1 ? 'checked="checked"' : ''; ?> />
                            <label for="chiuso-<?php echo (int) $numero; ?>">
                                Chiuso<span class="solo-lettori"> il <?php echo e($nome); ?></span>
                            </label>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <p class="azioni"><button type="submit">Salva gli orari</button></p>
    </form>
</section>
