<?php
/**
 * Elenco delle sedi. Riceve $sedi dal controller.
 */
?>
<h1>Sedi</h1>

<?php require __DIR__ . '/navigazione.php'; ?>

<table>
    <caption>Sedi della catena</caption>
    <thead>
        <tr>
            <th scope="col">Citta</th>
            <th scope="col">Indirizzo</th>
            <th scope="col">Telefono</th>
            <th scope="col">Sala eventi</th>
            <th scope="col">Scheda</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($sedi as $sede): ?>
            <tr>
                <th scope="row"><?php echo e($sede['citta']); ?></th>
                <td><?php echo e($sede['indirizzo']); ?>, <?php echo e($sede['cap']); ?></td>
                <td><?php echo e($sede['telefono']); ?></td>
                <td>
                    <span class="etichetta" data-tipo="<?php echo (int) $sede['sala_eventi_disponibile'] === 1 ? 'positivo' : 'attenzione'; ?>">
                        <?php echo (int) $sede['sala_eventi_disponibile'] === 1 ? 'prenotabile' : 'chiusa'; ?>
                    </span>
                </td>
                <td>
                    <a href="<?php echo e(url('controllo-sede', ['sede' => $sede['id']])); ?>">
                        Modifica
                        <span class="solo-lettori">la sede di <?php echo e($sede['citta']); ?></span>
                    </a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
