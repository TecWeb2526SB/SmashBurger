<?php
/**
 * Ricevuta di un ordine confermato. Riceve $ordine, comprensivo delle righe.
 */
?>
<h1>Ricevuta dell'ordine <?php echo e($ordine['numero']); ?></h1>

<section>
    <h2>Ritiro</h2>

    <p>Sede di <?php echo e($ordine['citta']); ?>, <?php echo e($ordine['indirizzo']); ?>,
        <?php echo e($ordine['cap']); ?> <?php echo e($ordine['citta']); ?> (<?php echo e($ordine['provincia']); ?>)</p>
    <p>Orario previsto: <?php echo e(date('d/m/Y H:i', strtotime($ordine['ritiro_previsto']))); ?></p>

    <?php if (!empty($ordine['note_ritiro'])): ?>
        <p><?php echo e($ordine['note_ritiro']); ?></p>
    <?php endif; ?>

    <p>Stato dell'ordine: <span class="etichetta" data-tipo="<?php echo e(ordine_tipo_stato($ordine['stato'])); ?>"><?php echo e($ordine['stato']); ?></span></p>
</section>

<section>
    <h2>Prodotti</h2>

    <table>
        <caption>Righe dell'ordine <?php echo e($ordine['numero']); ?></caption>
        <thead>
            <tr>
                <th scope="col">Prodotto</th>
                <th scope="col">Quantità</th>
                <th scope="col">Prezzo</th>
                <th scope="col">Subtotale</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($ordine['righe'] as $riga): ?>
                <tr>
                    <th scope="row"><?php echo e($riga['nome_prodotto']); ?></th>
                    <td><?php echo (int) $riga['quantita']; ?></td>
                    <td><?php echo e(prezzo((int) $riga['prezzo_centesimi'])); ?></td>
                    <td><?php echo e(prezzo((int) $riga['subtotale_centesimi'])); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="3">Totale</td>
                <td><?php echo e(prezzo((int) $ordine['totale_centesimi'])); ?></td>
            </tr>
        </tfoot>
    </table>
</section>

<section>
    <h2>Pagamento</h2>

    <p>Metodo: <?php echo e($ordine['metodo_pagamento']); ?></p>
    <p>Stato: <?php echo e($ordine['stato_pagamento']); ?></p>
</section>

<p><a href="<?php echo e(url('area-personale')); ?>">Torna all'area personale</a></p>
