<?php
/**
 * Elenco dei prodotti del menu, con i filtri per categoria.
 *
 * Riceve $categorie, $categoriaScelta e $prodotti dal controller.
 */
?>
<h1>
    <?php echo $categoriaScelta === null ? 'Menu e prezzi' : e($categoriaScelta['nome']); ?>
</h1>

<p>
    <?php if ($categoriaScelta === null): ?>
        Tutti i prodotti con prezzo e allergeni. Per ordinare scegli prima la sede: la
        disponibilita' cambia da un locale all'altro.
    <?php else: ?>
        <?php echo e($categoriaScelta['descrizione']); ?>
    <?php endif; ?>
</p>

<nav class="filtri" aria-label="Categorie del menu">
    <ul>
        <li>
            <?php if ($categoriaScelta === null): ?>
                <a href="<?php echo e(url('menu')); ?>" aria-current="true">Tutte</a>
            <?php else: ?>
                <a href="<?php echo e(url('menu')); ?>">Tutte</a>
            <?php endif; ?>
        </li>
        <?php foreach ($categorie as $categoria): ?>
            <li>
                <?php $attiva = $categoriaScelta !== null && $categoria['id'] === $categoriaScelta['id']; ?>
                <?php if ($attiva): ?>
                    <a href="<?php echo e(url('menu', ['categoria' => $categoria['slug']])); ?>" aria-current="true">
                        <?php echo e($categoria['nome']); ?>
                    </a>
                <?php else: ?>
                    <a href="<?php echo e(url('menu', ['categoria' => $categoria['slug']])); ?>">
                        <?php echo e($categoria['nome']); ?>
                    </a>
                <?php endif; ?>
            </li>
        <?php endforeach; ?>
    </ul>
</nav>

<?php if ($prodotti === []): ?>
    <p>Non c'e' ancora nessun prodotto in questa categoria.</p>
<?php else: ?>
    <ul class="griglia">
        <?php foreach ($prodotti as $prodotto): ?>
            <li>
                <article class="scheda">
                    <img src="<?php echo e(risorsa('uploads/prodotti/' . $prodotto['immagine'])); ?>"
                        width="400" height="300" loading="lazy"
                        alt="<?php echo e($prodotto['nome']); ?>" />

                    <h2>
                        <a href="<?php echo e(url('prodotto', ['slug' => $prodotto['slug']])); ?>">
                            <?php echo e($prodotto['nome']); ?>
                        </a>
                    </h2>

                    <p class="prezzo"><?php echo e(prezzo((int) $prodotto['prezzo_centesimi'])); ?></p>
                    <p><?php echo e($prodotto['descrizione']); ?></p>

                    <?php if ($prodotto['allergeni'] !== ''): ?>
                        <p><strong>Allergeni:</strong> <?php echo e($prodotto['allergeni']); ?></p>
                    <?php else: ?>
                        <p><strong>Allergeni:</strong> nessuno fra quelli dichiarati.</p>
                    <?php endif; ?>
                </article>
            </li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>

<p><a href="<?php echo e(url('carrello')); ?>">Scegli la sede e ordina</a></p>
