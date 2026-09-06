<?php
/**
 * Prenotazioni da gestire. Riceve $prenotazioni dal controller.
 *
 * Ogni pulsante porta lo stato come nome e l'identificativo come valore: il browser
 * invia solo quello premuto, quindi il modulo resta uno per tutta la tabella e la
 * tabella si riscrive senza ricaricare la pagina.
 *
 * La nota ha una colonna propria e si legge per intero: il modulo che la raccoglie la
 * tiene entro CARATTERI_NOTA_PRENOTAZIONE caratteri, quindi ci sta in una riga.
 */
?>
<h1>Prenotazioni della sala</h1>

<?php require __DIR__ . '/navigazione.php'; ?>

<?php if ($prenotazioni === []): ?>
    <p>Non c'è nessuna prenotazione.</p>
<?php else: ?>
    <form method="post" action="<?php echo e(url('controllo-prenotazioni')); ?>"
        data-modulo="prenotazioni">
        <?php echo campo_csrf(); ?>

        <table>
            <caption>Prenotazioni ricevute</caption>
            <thead>
                <tr>
                    <th scope="col">Sede</th>
                    <th scope="col">Data</th>
                    <th scope="col">Orario</th>
                    <th scope="col">Persone</th>
                    <th scope="col">Cliente</th>
                    <th scope="col">Nota</th>
                    <th scope="col">Stato</th>
                    <th scope="col" data-colonna="azioni">Azioni</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($prenotazioni as $prenotazione): ?>
                    <?php
                    $tipoStato = in_array($prenotazione['stato'], ['rifiutata', 'annullata'], true)
                        ? 'negativo'
                        : (in_array($prenotazione['stato'], ['in attesa', 'in_attesa'], true) ? 'attenzione' : 'positivo');
                    $nota = trim((string) ($prenotazione['note'] ?? ''));
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
                            <?php echo e($prenotazione['nome']); ?> <?php echo e($prenotazione['cognome']); ?>
                        </td>
                        <td>
                            <?php if ($nota === ''): ?>
                                <span class="solo-lettori">Nessuna nota</span>
                                <span aria-hidden="true">-</span>
                            <?php else: ?>
                                <?php echo e($nota); ?>
                            <?php endif; ?>
                        </td>
                        <td>
                            <span class="etichetta" data-tipo="<?php echo e($tipoStato); ?>">
                                <?php echo e($prenotazione['stato']); ?>
                            </span>
                        </td>
                        <td data-colonna="azioni">
                            <?php if ($prenotazione['stato'] === 'in attesa'): ?>
                                <button type="submit" name="approvata" value="<?php echo (int) $prenotazione['id']; ?>">
                                    Approva
                                    <span class="solo-lettori">la prenotazione del <?php echo e(data_breve($prenotazione['data'])); ?></span>
                                </button>
                                <button type="submit" name="rifiutata" value="<?php echo (int) $prenotazione['id']; ?>">
                                    Rifiuta
                                    <span class="solo-lettori">la prenotazione del <?php echo e(data_breve($prenotazione['data'])); ?></span>
                                </button>
                            <?php elseif ($prenotazione['stato'] === 'approvata'): ?>
                                <button type="submit" name="annullata" value="<?php echo (int) $prenotazione['id']; ?>">
                                    Annulla
                                    <span class="solo-lettori">la prenotazione del <?php echo e(data_breve($prenotazione['data'])); ?></span>
                                </button>
                            <?php else: ?>
                                <span>nessuna</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </form>
<?php endif; ?>
