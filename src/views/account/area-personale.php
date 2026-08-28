<?php
/**
 * Riepilogo dell'account e storico degli ordini. Riceve $utente e $ordini.
 */
?>
<h1>Area personale</h1>

<p>Ciao <?php echo e($utente['nome_utente']); ?>.</p>

<ul>
    <li><a href="<?php echo e(url('profilo')); ?>">Modifica i dati dell'account</a></li>
    <li><a href="<?php echo e(url('prodotti')); ?>">Vai al menu</a></li>
    <li><a href="<?php echo e(url('carrello')); ?>">Vai al carrello</a></li>
</ul>

<section>
    <h2>I tuoi ordini</h2>

    <?php if ($ordini === []): ?>
        <p>Non hai ancora fatto ordini.</p>
    <?php else: ?>
        <table>
            <caption>Ordini effettuati</caption>
            <thead>
                <tr>
                    <th scope="col">Numero</th>
                    <th scope="col">Sede</th>
                    <th scope="col">Ritiro</th>
                    <th scope="col">Stato</th>
                    <th scope="col">Totale</th>
                    <th scope="col">Ricevuta</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($ordini as $ordine): ?>
                    <tr>
                        <th scope="row"><?php echo e($ordine['numero']); ?></th>
                        <td><?php echo e($ordine['citta']); ?></td>
                        <td><?php echo e(date('d/m/Y H:i', strtotime($ordine['ritiro_previsto']))); ?></td>
                        <td><span class="etichetta"><?php echo e($ordine['stato']); ?></span></td>
                        <td><?php echo e(prezzo((int) $ordine['totale_centesimi'])); ?></td>
                        <td>
                            <a href="<?php echo e(url('ricevuta', ['numero' => $ordine['numero']])); ?>">
                                Ricevuta dell'ordine <?php echo e($ordine['numero']); ?>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</section>
