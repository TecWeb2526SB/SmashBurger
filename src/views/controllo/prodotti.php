<?php
/**
 * Prodotti di una sede con disponibilita' e quantita'.
 *
 * Riceve $prodotti, $sede, $sedi, $limitato e $amministratore dal controller.
 * Ogni riga ha due moduli: uno per il comando di disponibilita', uno per la quantita'.
 * Il secondo si invia da solo quando il numero cambia, quindi non ha pulsante.
 */
?>
<h1>Prodotti a <?php echo e($sede['citta']); ?></h1>

<?php require __DIR__ . '/navigazione.php'; ?>

<?php if (!$limitato): ?>
    <form method="get" action="<?php echo e(url('controllo-prodotti')); ?>">
        <fieldset>
            <legend>Sede</legend>
            <p>
                <label for="sede">Di quale sede vedere la disponibilita</label>
                <select id="sede" name="sede">
                    <?php foreach ($sedi as $unaSede): ?>
                        <option value="<?php echo (int) $unaSede['id']; ?>"
                            <?php echo (int) $unaSede['id'] === (int) $sede['id'] ? 'selected="selected"' : ''; ?>>
                            <?php echo e($unaSede['citta']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <button type="submit">Mostra</button>
            </p>
        </fieldset>
    </form>
<?php endif; ?>

<?php if ($amministratore): ?>
    <p class="azioni azioni-tabella">
        <a class="pulsante" data-tipo="positivo" href="<?php echo e(url('controllo-prodotto')); ?>">
            <?php echo icona('piu'); ?>
            Aggiungi un prodotto
        </a>
    </p>
<?php endif; ?>

<table>
    <caption>Prodotti e loro disponibilita a <?php echo e($sede['citta']); ?></caption>
    <thead>
        <tr>
            <th scope="col">Prodotto</th>
            <th scope="col">Categoria</th>
            <th scope="col">Prezzo</th>
            <th scope="col" data-colonna="azioni">Nel menu</th>
            <th scope="col">Quantita</th>
            <?php if ($amministratore): ?>
                <th scope="col" data-colonna="azioni">Scheda</th>
            <?php endif; ?>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($prodotti as $prodotto): ?>
            <?php $inMenu = (int) $prodotto['disponibile'] === 1; ?>
            <tr>
                <th scope="row"><?php echo e($prodotto['nome']); ?></th>
                <td><?php echo e($prodotto['categoria_nome']); ?></td>
                <td><?php echo e(prezzo((int) $prodotto['prezzo_centesimi'])); ?></td>
                <td data-colonna="azioni">
                    <form method="post" action="<?php echo e(url('controllo-prodotti')); ?>"
                        data-modulo="disponibilita">
                        <?php echo campo_csrf(); ?>
                        <input type="hidden" name="sede_id" value="<?php echo (int) $sede['id']; ?>" />
                        <input type="hidden" name="prodotto_id" value="<?php echo (int) $prodotto['id']; ?>" />
                        <button type="submit" name="<?php echo $inMenu ? 'nascondi' : 'mostra'; ?>" value="1"
                            aria-pressed="<?php echo $inMenu ? 'true' : 'false'; ?>">
                            <?php echo $inMenu ? 'Togli dal menu' : 'Rimetti nel menu'; ?>
                            <span class="solo-lettori"><?php echo e($prodotto['nome']); ?></span>
                        </button>
                    </form>
                </td>
                <td>
                    <form method="post" action="<?php echo e(url('controllo-prodotti')); ?>"
                        data-modulo="quantita">
                        <?php echo campo_csrf(); ?>
                        <input type="hidden" name="sede_id" value="<?php echo (int) $sede['id']; ?>" />
                        <input type="hidden" name="prodotto_id" value="<?php echo (int) $prodotto['id']; ?>" />
                        <label class="solo-lettori" for="quantita-<?php echo (int) $prodotto['id']; ?>">
                            Quantita di <?php echo e($prodotto['nome']); ?>
                        </label>
                        <input type="number" id="quantita-<?php echo (int) $prodotto['id']; ?>"
                            name="quantita" value="<?php echo (int) $prodotto['quantita']; ?>"
                            min="0" max="9999" />
                        <button type="submit">
                            Salva
                            <span class="solo-lettori">quantita di <?php echo e($prodotto['nome']); ?></span>
                        </button>
                    </form>
                </td>
                <?php if ($amministratore): ?>
                    <td data-colonna="azioni">
                        <a href="<?php echo e(url('controllo-prodotto', ['prodotto' => $prodotto['id']])); ?>">
                            Modifica
                            <span class="solo-lettori"><?php echo e($prodotto['nome']); ?></span>
                        </a>
                    </td>
                <?php endif; ?>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<p>
    Un prodotto compare nel menu del sito solo se è nel menu di questa sede
    <strong>e</strong> la quantita' è maggiore di zero. Gli ordini la scalano da soli.
</p>
