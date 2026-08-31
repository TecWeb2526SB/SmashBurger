<?php
/**
 * Elenco dei prodotti del menu, con i filtri per categoria.
 *
 * Riceve $categorie, $categoriaScelta e $prodotti dal controller.
 */
?>
<section class="apertura-pagina apertura-menu">
    <div>
        <p class="occhiello">Dalla piastra al vassoio</p>
        <h1><?php echo $categoriaScelta === null ? 'Menu e prezzi' : e($categoriaScelta['nome']); ?></h1>
        <p class="introduzione">
            <?php if ($categoriaScelta === null): ?>
                Burger, contorni, bevande e dessert. Prezzi e allergeni sono sempre in
                chiaro; la disponibilita' viene confermata quando scegli la sede.
            <?php else: ?>
                <?php echo e($categoriaScelta['descrizione']); ?>
            <?php endif; ?>
        </p>
    </div>

    <aside class="cartello-menu" aria-label="Riepilogo del menu mostrato">
        <span><?php echo $categoriaScelta === null ? 'Menu completo' : e($categoriaScelta['nome']); ?></span>
        <strong><?php echo count($prodotti); ?></strong>
        <p><?php echo count($prodotti) === 1 ? 'prodotto in lista' : 'prodotti in lista'; ?></p>
    </aside>
</section>

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
    <p class="stato-vuoto">Non c'e' ancora nessun prodotto in questa categoria.</p>
<?php else: ?>
    <ul class="griglia griglia-menu">
        <?php foreach ($prodotti as $prodotto): ?>
            <li>
                <article class="scheda scheda-prodotto">
                    <div class="foto-prodotto">
                        <span class="etichetta-categoria"><?php echo e($prodotto['categoria_nome']); ?></span>
                        <img src="<?php echo e(risorsa('uploads/prodotti/' . $prodotto['immagine'])); ?>"
                            width="400" height="300" loading="lazy"
                            alt="<?php echo e($prodotto['nome']); ?>" />
                    </div>

                    <div class="corpo-scheda">
                        <div class="nome-prezzo">
                            <h2>
                                <a href="<?php echo e(url('prodotto', ['slug' => $prodotto['slug']])); ?>">
                                    <?php echo e($prodotto['nome']); ?>
                                </a>
                            </h2>
                            <p class="prezzo"><?php echo e(prezzo((int) $prodotto['prezzo_centesimi'])); ?></p>
                        </div>

                        <p class="descrizione-prodotto"><?php echo e($prodotto['descrizione']); ?></p>

                        <?php if ($prodotto['allergeni'] !== ''): ?>
                            <p class="allergeni"><span>Allergeni</span> <?php echo e($prodotto['allergeni']); ?></p>
                        <?php else: ?>
                            <p class="allergeni"><span>Allergeni</span> nessuno fra quelli dichiarati.</p>
                        <?php endif; ?>
                    </div>
                </article>
            </li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>

<section class="invito-finale">
    <div>
        <p class="occhiello">Hai scelto?</p>
        <h2>Adesso trova la piastra piu' vicina.</h2>
    </div>
    <p><a class="pulsante" data-tipo="positivo" href="<?php echo e(url('carrello')); ?>">Scegli la sede e ordina</a></p>
</section>
