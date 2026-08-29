<?php
/**
 * Riepilogo dell'account e storico degli ordini. Riceve $utente e $ordini.
 */
?>
<h1>Area personale</h1>

<p>Ciao <?php echo e($utente['nome_utente']); ?>.</p>

<p class="azione-principale">
    <a class="pulsante" data-tipo="indietro" href="<?php echo e(url('profilo')); ?>">
        <?php echo icona('matita'); ?> Modifica i dati dell'account
    </a>
</p>

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
                        <td><span class="etichetta" data-tipo="<?php echo e(ordine_tipo_stato($ordine['stato'])); ?>"><?php echo e($ordine['stato']); ?></span></td>
                        <td><?php echo e(prezzo((int) $ordine['totale_centesimi'])); ?></td>
                        <td>
                            <a class="pulsante" data-tipo="indietro"
                                href="<?php echo e(url('ricevuta', ['numero' => $ordine['numero']])); ?>">
                                Ricevuta
                                <span class="solo-lettori">dell'ordine <?php echo e($ordine['numero']); ?></span>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</section>

<p class="navigazione-pagina">
    <?php if (!utente_e_amministratore()): ?>
        <a class="pulsante" data-tipo="positivo" href="<?php echo e(url('prodotti')); ?>">
            Vai al menu <?php echo icona('freccia-destra'); ?>
        </a>
    <?php endif; ?>
</p>
