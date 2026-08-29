<?php
/**
 * Elenco delle sedi con le azioni di modifica e cancellazione.
 *
 * Riceve $sedi e $daCancellare, che contiene la sede per cui è stata chiesta la conferma
 * di cancellazione.
 */
?>
<h1>Sedi</h1>

<?php include __DIR__ . '/navigazione.php'; ?>

<?php if ($daCancellare !== null): ?>
    <section role="alert">
        <h2>Vuoi cancellare la sede di <?php echo e($daCancellare['citta']); ?>?</h2>
        <p>Una sede con ordini registrati non può essere cancellata, solo disattivata.</p>

        <form method="post" action="<?php echo e(url('controllo-sedi')); ?>">
            <?php echo campo_csrf(); ?>
            <input type="hidden" name="azione" value="cancella" />
            <input type="hidden" name="sede_id" value="<?php echo (int) $daCancellare['id']; ?>" />
            <p>
                <button type="submit">Cancella la sede di <?php echo e($daCancellare['citta']); ?></button>
                <a href="<?php echo e(url('controllo-sedi')); ?>">Annulla</a>
            </p>
        </form>
    </section>
<?php endif; ?>

<p class="azione-principale">
    <a class="pulsante" href="<?php echo e(url('controllo-sede')); ?>">
        <?php echo icona('piu'); ?> Aggiungi una sede
    </a>
</p>

<table>
    <caption>Sedi registrate</caption>
    <thead>
        <tr>
            <th scope="col">Citt&#224;</th>
            <th scope="col">Indirizzo</th>
            <th scope="col">Attiva</th>
            <th scope="col">Azioni</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($sedi as $sede): ?>
            <tr>
                <th scope="row"><?php echo e($sede['citta']); ?> (<?php echo e($sede['provincia']); ?>)</th>
                <td><?php echo e($sede['indirizzo']); ?></td>
                <td><?php echo (int) $sede['attiva'] === 1 ? 'sì' : 'no'; ?></td>
                <td>
                    <span class="azioni-riga">
                        <a class="pulsante" data-tipo="indietro"
                            href="<?php echo e(url('controllo-sede', ['id' => $sede['id']])); ?>">
                            <?php echo icona('matita'); ?>
                            <span class="solo-lettori">Modifica la sede di <?php echo e($sede['citta']); ?></span>
                        </a>
                        <a class="pulsante" data-tipo="negativo"
                            href="<?php echo e(url('controllo-sedi', ['cancella' => $sede['id']])); ?>">
                            <?php echo icona('cestino'); ?>
                            <span class="solo-lettori">Cancella la sede di <?php echo e($sede['citta']); ?></span>
                        </a>
                    </span>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
