<?php
/**
 * Elenco degli ordini con i filtri e le azioni sullo stato.
 *
 * Riceve $sedi, $stati, $statiPagamento, $sedeFiltro, $statoFiltro e $ordini dal
 * controller. Ogni riga porta l'identificativo dell'ordine in data-ordine, così può
 * essere aggiornata da sola senza ricaricare la pagina.
 */
?>
<h1>Ordini</h1>

<?php include __DIR__ . '/navigazione.php'; ?>

<form method="get" action="<?php echo e(url('controllo')); ?>">
    <fieldset class="riga-campi">
        <legend>Filtri</legend>

        <p>
            <label for="sede">Sede</label>
            <select id="sede" name="sede">
                <option value="">Tutte le sedi</option>
                <?php foreach ($sedi as $sede): ?>
                    <option value="<?php echo (int) $sede['id']; ?>"
                        <?php echo (int) $sede['id'] === (int) $sedeFiltro ? 'selected="selected"' : ''; ?>>
                        <?php echo e($sede['citta']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </p>

        <p>
            <label for="stato">Stato</label>
            <select id="stato" name="stato">
                <option value="">Tutti gli stati</option>
                <?php foreach ($stati as $stato): ?>
                    <option value="<?php echo e($stato); ?>"
                        <?php echo $stato === $statoFiltro ? 'selected="selected"' : ''; ?>>
                        <?php echo e($stato); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </p>

        <p><button type="submit">Filtra</button></p>
    </fieldset>
</form>

<?php if ($ordini === []): ?>
    <p>Nessun ordine corrisponde ai filtri scelti.</p>
<?php else: ?>
    <table>
        <caption>Ordini registrati</caption>
        <thead>
            <tr>
                <th scope="col">Numero</th>
                <th scope="col">Cliente</th>
                <th scope="col">Sede</th>
                <th scope="col">Ritiro</th>
                <th scope="col">Totale</th>
                <th scope="col">Metodo</th>
                <th scope="col">Stato e pagamento</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($ordini as $ordine): ?>
                <tr data-ordine="<?php echo (int) $ordine['id']; ?>">
                    <th scope="row"><?php echo e($ordine['numero']); ?></th>
                    <td><?php echo e($ordine['nome_utente']); ?></td>
                    <td><?php echo e($ordine['citta']); ?></td>
                    <td><?php echo e(date('d/m/Y H:i', strtotime($ordine['ritiro_previsto']))); ?></td>
                    <td><?php echo e(prezzo((int) $ordine['totale_centesimi'])); ?></td>
                    <td><?php echo e($ordine['metodo_pagamento']); ?></td>
                    <td>
                        <form method="post" action="<?php echo e(url('controllo')); ?>" data-modulo="ordine">
                            <?php echo campo_csrf(); ?>
                            <input type="hidden" name="azione" value="aggiorna" />
                            <input type="hidden" name="ordine_id" value="<?php echo (int) $ordine['id']; ?>" />

                            <label class="solo-lettori" for="stato-<?php echo (int) $ordine['id']; ?>">
                                Stato dell'ordine <?php echo e($ordine['numero']); ?>
                            </label>
                            <select id="stato-<?php echo (int) $ordine['id']; ?>" name="stato">
                                <?php foreach ($stati as $stato): ?>
                                    <option value="<?php echo e($stato); ?>"
                                        <?php echo $stato === $ordine['stato'] ? 'selected="selected"' : ''; ?>>
                                        <?php echo e($stato); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>

                            <label class="solo-lettori" for="pagamento-<?php echo (int) $ordine['id']; ?>">
                                Pagamento dell'ordine <?php echo e($ordine['numero']); ?>
                            </label>
                            <select id="pagamento-<?php echo (int) $ordine['id']; ?>" name="stato_pagamento">
                                <?php foreach ($statiPagamento as $statoPagamento): ?>
                                    <option value="<?php echo e($statoPagamento); ?>"
                                        <?php echo $statoPagamento === $ordine['stato_pagamento'] ? 'selected="selected"' : ''; ?>>
                                        <?php echo e($statoPagamento); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>

                            <button type="submit">Aggiorna</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>
