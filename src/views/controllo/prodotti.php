<?php
/**
 * Elenco dei prodotti con le azioni di modifica e cancellazione.
 *
 * Riceve $prodotti e $daCancellare, che contiene il prodotto per cui è stata chiesta la
 * conferma di cancellazione.
 */
?>
<h1>Pannello di controllo</h1>

<?php include __DIR__ . '/navigazione.php'; ?>

<h2>Prodotti</h2>

<?php if ($daCancellare !== null): ?>
    <section role="alert">
        <h3>Vuoi cancellare <?php echo e($daCancellare['nome']); ?>?</h3>
        <p>Il prodotto sparisce dal menu. Gli ordini già registrati restano leggibili.</p>

        <form method="post" action="<?php echo e(url('controllo-prodotti')); ?>">
            <?php echo campo_csrf(); ?>
            <input type="hidden" name="azione" value="cancella" />
            <input type="hidden" name="prodotto_id" value="<?php echo (int) $daCancellare['id']; ?>" />
            <p>
                <button type="submit">Cancella <?php echo e($daCancellare['nome']); ?></button>
                <a href="<?php echo e(url('controllo-prodotti')); ?>">Annulla</a>
            </p>
        </form>
    </section>
<?php endif; ?>

<p><a href="<?php echo e(url('controllo-prodotto')); ?>">Aggiungi un prodotto</a></p>

<table>
    <caption>Prodotti a catalogo</caption>
    <thead>
        <tr>
            <th scope="col">Nome</th>
            <th scope="col">Categoria</th>
            <th scope="col">Prezzo</th>
            <th scope="col">Disponibile</th>
            <th scope="col">Azioni</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($prodotti as $prodotto): ?>
            <tr>
                <th scope="row"><?php echo e($prodotto['nome']); ?></th>
                <td><?php echo e($prodotto['categoria']); ?></td>
                <td><?php echo e(prezzo((int) $prodotto['prezzo_centesimi'])); ?></td>
                <td><?php echo (int) $prodotto['disponibile'] === 1 ? 'sì' : 'no'; ?></td>
                <td>
                    <a href="<?php echo e(url('controllo-prodotto', ['id' => $prodotto['id']])); ?>">
                        Modifica <?php echo e($prodotto['nome']); ?>
                    </a>
                    <a href="<?php echo e(url('controllo-prodotti', ['cancella' => $prodotto['id']])); ?>">
                        Cancella <?php echo e($prodotto['nome']); ?>
                    </a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
