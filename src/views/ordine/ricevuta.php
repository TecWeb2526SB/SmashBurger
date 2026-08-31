<?php
/**
 * Ricevuta dell'ordine. Riceve $ordine, $righe e $utente dal controller.
 *
 * E' la pagina piu' probabile da stampare, quindi la struttura resta lineare.
 */
?>
<h1>Ricevuta <?php echo e($ordine['numero_ordine']); ?></h1>

<p>
    Ordine del <?php echo e(data_ora($ordine['creato_il'])); ?>, intestato a
    <?php echo e($utente['nome']); ?> <?php echo e($utente['cognome']); ?>.
</p>

<section>
    <h2>Stato</h2>
    <ul>
        <li>
            Ordine:
            <span class="etichetta" data-tipo="<?php echo $ordine['stato'] === 'annullato' ? 'negativo' : 'positivo'; ?>">
                <?php echo e($ordine['stato']); ?>
            </span>
        </li>
        <li>
            Pagamento: <?php echo e($ordine['metodo_pagamento']); ?>,
            <span class="etichetta" data-tipo="<?php echo $ordine['stato_pagamento'] === 'rimborsato' ? 'attenzione' : 'positivo'; ?>">
                <?php echo e($ordine['stato_pagamento']); ?>
            </span>
        </li>
    </ul>

    <?php if ($ordine['motivo_annullamento'] !== null && $ordine['motivo_annullamento'] !== ''): ?>
        <p class="avviso" role="status" data-tipo="attenzione">
            <strong>Motivo dell annullamento:</strong> <?php echo e($ordine['motivo_annullamento']); ?>
        </p>
    <?php endif; ?>
</section>

<section>
    <h2>Consegna</h2>
    <?php if ($ordine['modalita'] === 'ritiro'): ?>
        <p>
            Ritiro in sede a <?php echo e($ordine['citta']); ?>,
            <?php echo e($ordine['sede_indirizzo']); ?>,
            il <?php echo e(data_ora($ordine['ritiro_previsto'])); ?>.
        </p>
    <?php else: ?>
        <p>Consegna a domicilio, presa in carico dopo il pagamento.</p>
        <address>
            <?php echo e($ordine['consegna_indirizzo']); ?><br />
            <?php echo e($ordine['consegna_cap']); ?> <?php echo e($ordine['consegna_citta']); ?>
            (<?php echo e($ordine['consegna_provincia']); ?>)<br />
            <?php echo e($ordine['consegna_paese']); ?><br />
            Telefono <?php echo e($ordine['consegna_telefono']); ?>
        </address>
    <?php endif; ?>
</section>

<section>
    <h2>Prodotti</h2>
    <table>
        <caption>Righe dell'ordine <?php echo e($ordine['numero_ordine']); ?></caption>
        <thead>
            <tr>
                <th scope="col">Prodotto</th>
                <th scope="col">Quantita</th>
                <th scope="col">Prezzo</th>
                <th scope="col">Totale</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($righe as $riga): ?>
                <tr>
                    <th scope="row"><?php echo e($riga['nome_prodotto']); ?></th>
                    <td><?php echo (int) $riga['quantita']; ?></td>
                    <td><?php echo e(prezzo((int) $riga['prezzo_centesimi'])); ?></td>
                    <td><?php echo e(prezzo((int) $riga['prezzo_centesimi'] * (int) $riga['quantita'])); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
        <tfoot>
            <tr>
                <th scope="row" colspan="3">Totale dell'ordine</th>
                <td><?php echo e(prezzo((int) $ordine['totale_centesimi'])); ?></td>
            </tr>
        </tfoot>
    </table>
</section>

<p class="navigazione-pagina">
    <a class="collegamento-indietro" href="<?php echo e(url('area-personale')); ?>"><span class="segno-collegamento" aria-hidden="true">&lt;</span><span>Torna ai tuoi ordini</span></a>
</p>
