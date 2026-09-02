<?php
/**
 * Dettaglio di un ordine nel pannello.
 *
 * Riceve $ordine, $righe e $confermaAnnullamento dal controller. L'annullamento passa da
 * una conferma nella stessa pagina, come le altre operazioni distruttive del pannello.
 */
?>
<h1>Ordine <?php echo e($ordine['numero_ordine']); ?></h1>

<?php require __DIR__ . '/navigazione.php'; ?>

<?php if ($confermaAnnullamento && $ordine['stato'] !== 'annullato'): ?>
    <section class="avviso" role="alert" data-tipo="errore">
        <h2>Vuoi annullare questo ordine?</h2>
        <p>
            I prodotti tornano disponibili nella sede di <?php echo e($ordine['citta']); ?>.
            Scrivi il motivo: viene mostrato al cliente nella sua ricevuta.
        </p>

        <form method="post" action="<?php echo e(url('controllo-ordine')); ?>"
            data-modulo="annullamento">
            <?php echo campo_csrf(); ?>
            <input type="hidden" name="ordine" value="<?php echo (int) $ordine['id']; ?>" />

            <p>
                <label for="motivo">Motivo dell&apos;annullamento</label>
                <textarea id="motivo" name="motivo" rows="3" required="required"
                    minlength="5" maxlength="255"></textarea>
            </p>

            <p>
                <input type="checkbox" id="rimborsa" name="rimborsa" value="1"
                    <?php echo $ordine['stato_pagamento'] === 'pagato' ? 'checked="checked"' : ''; ?> />
                <label for="rimborsa">Segna il pagamento come rimborsato</label>
            </p>

            <p class="azioni">
                <button type="submit" data-tipo="negativo">Annulla l&apos;ordine</button>
                <a class="pulsante secondario" href="<?php echo e(url('controllo-ordine', ['ordine' => $ordine['id']])); ?>">Lascia com&apos;è</a>
            </p>
        </form>
    </section>
<?php endif; ?>

<section>
    <h2>Stato</h2>
    <ul>
        <li>
            Ordine:
            <span class="etichetta" data-tipo="<?php echo $ordine['stato'] === 'annullato' ? 'negativo' : 'positivo'; ?>">
                <?php echo e($ordine['stato']); ?>
            </span>
        </li>
        <li>Pagamento: <?php echo e($ordine['metodo_pagamento']); ?>, <?php echo e($ordine['stato_pagamento']); ?></li>
        <li>Ricevuto il <?php echo e(data_ora($ordine['creato_il'])); ?></li>
        <li>Totale: <?php echo e(prezzo((int) $ordine['totale_centesimi'])); ?></li>
    </ul>

    <?php if ($ordine['motivo_annullamento'] !== null && $ordine['motivo_annullamento'] !== ''): ?>
        <p class="avviso" role="status" data-tipo="attenzione">
            <strong>Motivo dell&apos;annullamento:</strong> <?php echo e($ordine['motivo_annullamento']); ?>
        </p>
    <?php endif; ?>
</section>

<section>
    <h2>Cliente</h2>
    <ul>
        <li><?php echo e($ordine['nome']); ?> <?php echo e($ordine['cognome']); ?>, <?php echo e($ordine['nome_utente']); ?></li>
        <li><a href="mailto:<?php echo e($ordine['email']); ?>"><?php echo e($ordine['email']); ?></a></li>
    </ul>
</section>

<section>
    <h2>Recapito</h2>
    <?php if ($ordine['modalita'] === 'ritiro'): ?>
        <p>
            Ritiro in sede a <?php echo e($ordine['citta']); ?>, <?php echo e($ordine['sede_indirizzo']); ?>,
            previsto per il <?php echo e(data_ora($ordine['ritiro_previsto'])); ?>.
        </p>
    <?php else: ?>
        <p>Consegna a domicilio.</p>
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
    </table>
</section>

<p class="navigazione-pagina">
    <a class="collegamento-indietro" href="<?php echo e(url('controllo')); ?>"><span class="segno-collegamento" aria-hidden="true">&lt;</span><span>Torna agli ordini</span></a>
    <?php if ($ordine['stato'] !== 'annullato'): ?>
        <a href="<?php echo e(url('controllo-ordine', ['ordine' => $ordine['id'], 'annulla' => 1])); ?>">
            Annulla l&apos;ordine
        </a>
    <?php endif; ?>
</p>
