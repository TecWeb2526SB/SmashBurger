<?php
/**
 * Area personale. Riceve $utente, $cliente, $ordini e $prenotazioni dal controller.
 *
 * Le sezioni degli ordini e delle prenotazioni compaiono solo per i clienti: chi gestisce
 * il servizio trova le proprie nel pannello di controllo.
 */
?>
<h1>Area personale</h1>

<p>
    Ciao <?php echo e($utente['nome']); ?>.
    <?php if ($cliente): ?>
        Da qui vedi i tuoi ordini, le tue prenotazioni e puoi cambiare i tuoi dati.
    <?php else: ?>
        Da qui controlli i tuoi dati di accesso: ordini e prenotazioni della tua sede
        stanno nel pannello di controllo.
    <?php endif; ?>
</p>

<?php if ($cliente): ?>
    <section>
        <h2>I tuoi ordini</h2>

        <?php if ($ordini === []): ?>
            <p>Non hai ancora fatto nessun ordine.</p>
            <p><a href="<?php echo e(url('menu')); ?>">Guarda il menu</a></p>
        <?php else: ?>
            <table>
                <caption>Ordini effettuati</caption>
                <thead>
                    <tr>
                        <th scope="col">Numero</th>
                        <th scope="col">Data</th>
                        <th scope="col">Sede</th>
                        <th scope="col">Modalita</th>
                        <th scope="col">Totale</th>
                        <th scope="col">Stato</th>
                        <th scope="col">Ricevuta</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($ordini as $ordine): ?>
                        <tr>
                            <th scope="row"><?php echo e($ordine['numero_ordine']); ?></th>
                            <td><?php echo e(data_ora($ordine['creato_il'])); ?></td>
                            <td><?php echo e($ordine['citta']); ?></td>
                            <td><?php echo e($ordine['modalita']); ?></td>
                            <td><?php echo e(prezzo((int) $ordine['totale_centesimi'])); ?></td>
                            <td>
                                <span class="etichetta" data-tipo="<?php echo $ordine['stato'] === 'annullato' ? 'negativo' : 'positivo'; ?>">
                                    <?php echo e($ordine['stato']); ?>
                                </span>
                            </td>
                            <td>
                                <a href="<?php echo e(url('ricevuta', ['ordine' => $ordine['id']])); ?>">
                                    Apri la ricevuta
                                    <span class="solo-lettori">dell'ordine <?php echo e($ordine['numero_ordine']); ?></span>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </section>

    <section>
        <h2>Le tue prenotazioni</h2>

        <?php if ($prenotazioni === []): ?>
            <p>Non hai prenotazioni per la sala eventi.</p>
            <p><a href="<?php echo e(url('sedi')); ?>">Scegli una sede e prenota</a></p>
        <?php else: ?>
            <table>
                <caption>Prenotazioni della sala eventi</caption>
                <thead>
                    <tr>
                        <th scope="col">Sede</th>
                        <th scope="col">Data</th>
                        <th scope="col">Fascia</th>
                        <th scope="col">Persone</th>
                        <th scope="col">Stato</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($prenotazioni as $prenotazione): ?>
                        <?php
                        $tipoStato = in_array($prenotazione['stato'], ['rifiutata', 'annullata'], true)
                            ? 'negativo'
                            : (in_array($prenotazione['stato'], ['in attesa', 'in_attesa'], true) ? 'attenzione' : 'positivo');
                        ?>
                        <tr>
                            <th scope="row"><?php echo e($prenotazione['citta']); ?></th>
                            <td><?php echo e(data_breve($prenotazione['data'])); ?></td>
                            <td>
                                <?php echo e(substr($prenotazione['ora_inizio'], 0, 5)); ?> -
                                <?php echo e(substr($prenotazione['ora_fine'], 0, 5)); ?>
                            </td>
                            <td><?php echo (int) $prenotazione['numero_persone']; ?></td>
                            <td>
                                <span class="etichetta" data-tipo="<?php echo e($tipoStato); ?>">
                                    <?php echo e($prenotazione['stato']); ?>
                                </span>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </section>
<?php endif; ?>

<section>
    <h2>I tuoi dati</h2>
    <ul>
        <li>Nome utente: <?php echo e($utente['nome_utente']); ?></li>
        <li>Nome e cognome: <?php echo e($utente['nome']); ?> <?php echo e($utente['cognome']); ?></li>
        <li>Email: <?php echo e($utente['email']); ?></li>
    </ul>
    <p><a href="<?php echo e(url('profilo')); ?>">Modifica i tuoi dati</a></p>
</section>
