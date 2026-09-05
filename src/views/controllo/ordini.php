<?php
/**
 * Elenco degli ordini del pannello, con incasso e filtri.
 *
 * Riceve $ordini, $sedi, $sedeCorrente, $filtroSede, $filtroStato, $stati,
 * $statiPagamento, $serie e $incasso dal controller.
 *
 * Il modulo di ogni riga si invia da solo quando cambia uno dei due elenchi: non ha un
 * pulsante di conferma, e la tabella si riscrive senza ricaricare la pagina.
 */
?>
<h1>Ordini<?php echo $sedeCorrente === null ? '' : ' di ' . e($sedeCorrente['citta']); ?></h1>

<?php require __DIR__ . '/navigazione.php'; ?>

<?php require __DIR__ . '/incasso.php'; ?>

<form method="get" action="<?php echo e(url('controllo')); ?>">
    <fieldset>
        <legend>Filtri</legend>

        <?php if ($sedeCorrente === null): ?>
            <p>
                <label for="sede">Sede</label>
                <select id="sede" name="sede">
                    <option value="">Tutte le sedi</option>
                    <?php foreach ($sedi as $sede): ?>
                        <option value="<?php echo (int) $sede['id']; ?>"
                            <?php echo (int) $sede['id'] === (int) $filtroSede ? 'selected="selected"' : ''; ?>>
                            <?php echo e($sede['citta']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </p>
        <?php endif; ?>

        <p>
            <label for="stato">Stato</label>
            <select id="stato" name="stato">
                <option value="">Tutti gli stati</option>
                <?php foreach ($stati as $stato): ?>
                    <option value="<?php echo e($stato); ?>"
                        <?php echo $stato === $filtroStato ? 'selected="selected"' : ''; ?>>
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
                <th scope="col">Modalita</th>
                <th scope="col">Totale</th>
                <th scope="col">Stato e pagamento</th>
                <th scope="col">Dettaglio</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($ordini as $ordine): ?>
                <tr data-ordine="<?php echo (int) $ordine['id']; ?>">
                    <th scope="row"><?php echo e($ordine['numero_ordine']); ?></th>
                    <td><?php echo e($ordine['nome_utente']); ?></td>
                    <td><?php echo e($ordine['citta']); ?></td>
                    <td><?php echo e($ordine['modalita']); ?></td>
                    <td><?php echo e(prezzo((int) $ordine['totale_centesimi'])); ?></td>
                    <td>
                        <?php if ($ordine['stato'] === 'annullato'): ?>
                            <span class="etichetta" data-tipo="negativo">annullato</span>
                            <span class="etichetta"><?php echo e($ordine['stato_pagamento']); ?></span>
                        <?php else: ?>
                            <form method="post" action="<?php echo e(url('controllo')); ?>"
                                data-modulo="ordine">
                                <?php echo campo_csrf(); ?>
                                <input type="hidden" name="ordine_id" value="<?php echo (int) $ordine['id']; ?>" />

                                <label class="solo-lettori" for="stato-<?php echo (int) $ordine['id']; ?>">
                                    Stato dell'ordine <?php echo e($ordine['numero_ordine']); ?>
                                </label>
                                <select id="stato-<?php echo (int) $ordine['id']; ?>" name="stato">
                                    <?php foreach ($stati as $stato): ?>
                                        <?php if ($stato !== 'annullato'): ?>
                                            <option value="<?php echo e($stato); ?>"
                                                <?php echo $stato === $ordine['stato'] ? 'selected="selected"' : ''; ?>>
                                                <?php echo e($stato); ?>
                                            </option>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </select>

                                <label class="solo-lettori" for="pagamento-<?php echo (int) $ordine['id']; ?>">
                                    Pagamento dell'ordine <?php echo e($ordine['numero_ordine']); ?>
                                </label>
                                <select id="pagamento-<?php echo (int) $ordine['id']; ?>" name="stato_pagamento">
                                    <?php foreach ($statiPagamento as $statoPagamento): ?>
                                        <option value="<?php echo e($statoPagamento); ?>"
                                            <?php echo $statoPagamento === $ordine['stato_pagamento'] ? 'selected="selected"' : ''; ?>>
                                            <?php echo e($statoPagamento); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>

                                <button type="submit">
                                    Aggiorna
                                    <span class="solo-lettori">stato e pagamento di <?php echo e($ordine['numero_ordine']); ?></span>
                                </button>
                            </form>
                        <?php endif; ?>
                    </td>
                    <td>
                        <a href="<?php echo e(url('controllo-ordine', ['ordine' => $ordine['id']])); ?>">
                            Apri
                            <span class="solo-lettori">il dettaglio di <?php echo e($ordine['numero_ordine']); ?></span>
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>
