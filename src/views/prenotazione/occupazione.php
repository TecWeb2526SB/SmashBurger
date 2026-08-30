<?php
/**
 * Come e' occupata la sala in un giorno.
 *
 * Mostra gli intervalli liberi e quelli presi senza dire nulla di chi ha prenotato:
 * serve a scegliere un orario, non a sapere chi c'e'.
 */
?>
        <table>
            <caption>
                Come e' occupata la sala di <?php echo e($sede['citta']); ?> il
                <?php echo e(data_breve($data)); ?>
            </caption>
            <thead>
                <tr>
                    <th scope="col">Dalle</th>
                    <th scope="col">Alle</th>
                    <th scope="col">Stato</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($occupazione as $intervallo): ?>
                    <tr>
                        <th scope="row"><?php echo e(substr($intervallo['inizio'], 0, 5)); ?></th>
                        <td><?php echo e(substr($intervallo['fine'], 0, 5)); ?></td>
                        <td>
                            <span class="etichetta" data-tipo="<?php echo $intervallo['occupata'] ? 'negativo' : 'positivo'; ?>">
                                <?php echo $intervallo['occupata'] ? 'occupata' : 'libera'; ?>
                            </span>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
