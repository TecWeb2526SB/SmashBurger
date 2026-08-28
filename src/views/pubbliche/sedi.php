<?php
/**
 * Elenco delle sedi: indirizzo, contatti, note per il ritiro e orari della settimana.
 *
 * Riceve $sedi, $orari indicizzati per identificativo di sede e $giorni dal controller.
 */
?>
<h1>Sedi e orari</h1>

<p>
    Le quattro sedi preparano lo stesso menu. Il ritiro avviene nella sede scelta al
    momento dell'ordine.
</p>

<?php foreach ($sedi as $sede): ?>
    <section>
        <h2><?php echo e($sede['citta']); ?></h2>

        <p><?php echo e($sede['nome']); ?></p>
        <p><?php echo e($sede['indirizzo']); ?>, <?php echo e($sede['cap']); ?> <?php echo e($sede['citta']); ?> (<?php echo e($sede['provincia']); ?>)</p>
        <p>Telefono <a href="tel:+39<?php echo e(str_replace(' ', '', $sede['telefono'])); ?>"><?php echo e($sede['telefono']); ?></a></p>
        <p>Email <a href="mailto:<?php echo e($sede['email']); ?>"><?php echo e($sede['email']); ?></a></p>

        <?php if (!empty($sede['note_ritiro'])): ?>
            <p><?php echo e($sede['note_ritiro']); ?></p>
        <?php endif; ?>

        <table>
            <caption>Orari di apertura della sede di <?php echo e($sede['citta']); ?></caption>
            <thead>
                <tr>
                    <th scope="col">Giorno</th>
                    <th scope="col">Apertura</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orari[(int) $sede['id']] as $riga): ?>
                    <tr>
                        <th scope="row"><?php echo e($giorni[(int) $riga['giorno']]); ?></th>
                        <td>
                            <?php if ((int) $riga['chiuso'] === 1): ?>
                                Chiuso
                            <?php else: ?>
                                <?php echo e(orario($riga['apertura'])); ?> - <?php echo e(orario($riga['chiusura'])); ?>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </section>
<?php endforeach; ?>
