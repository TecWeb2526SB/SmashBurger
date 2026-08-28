<?php
/**
 * Elenco delle sedi con le azioni di modifica e cancellazione. Riceve $sedi.
 */
?>
<h1>Pannello di controllo</h1>

<?php include __DIR__ . '/navigazione.php'; ?>

<h2>Sedi</h2>

<p><a href="<?php echo e(url('controllo-sede')); ?>">Aggiungi una sede</a></p>

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
                    <a href="<?php echo e(url('controllo-sede', ['id' => $sede['id']])); ?>">
                        Modifica la sede di <?php echo e($sede['citta']); ?>
                    </a>
                    <form method="post" action="<?php echo e(url('controllo-sedi')); ?>">
                        <?php echo campo_csrf(); ?>
                        <input type="hidden" name="azione" value="cancella" />
                        <input type="hidden" name="sede_id" value="<?php echo (int) $sede['id']; ?>" />
                        <button type="submit">Cancella la sede di <?php echo e($sede['citta']); ?></button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
