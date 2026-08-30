<?php
/**
 * Scheda pubblica di un prodotto. Riceve $prodotto e $sediDisponibili dal controller.
 *
 * Le sedi sono un'informazione, non un comando di acquisto: si ordina dal carrello,
 * dopo avere scelto la sede.
 */
?>
<h1><?php echo e($prodotto['nome']); ?></h1>

<img src="<?php echo e(risorsa('uploads/prodotti/' . $prodotto['immagine'])); ?>"
    width="600" height="450"
    alt="<?php echo e($prodotto['nome']); ?>" />

<p class="prezzo"><?php echo e(prezzo((int) $prodotto['prezzo_centesimi'])); ?></p>

<p><?php echo e($prodotto['descrizione']); ?></p>

<section>
    <h2>Allergeni</h2>
    <?php if ($prodotto['allergeni'] !== ''): ?>
        <p>Contiene: <?php echo e($prodotto['allergeni']); ?>.</p>
    <?php else: ?>
        <p>Non contiene nessuno degli allergeni che dichiariamo.</p>
    <?php endif; ?>
    <p>
        Se hai un'intolleranza o un'allergia, <a href="<?php echo e(url('contatti')); ?>">scrivici</a>
        prima di ordinare.
    </p>
</section>

<section>
    <h2>Dove lo trovi adesso</h2>

    <?php if ($sediDisponibili === []): ?>
        <p>
            In questo momento non e' disponibile in nessuna sede. Torna a trovarci, oppure
            guarda il resto del <a href="<?php echo e(url('menu')); ?>">menu</a>.
        </p>
    <?php else: ?>
        <ul>
            <?php foreach ($sediDisponibili as $sede): ?>
                <li>
                    <a href="<?php echo e(url('sede', ['slug' => $sede['slug']])); ?>">
                        <?php echo e($sede['citta']); ?>
                    </a>,
                    <?php echo e($sede['indirizzo']); ?>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
</section>

<p class="navigazione-pagina">
    <a href="<?php echo e(url('menu', ['categoria' => $prodotto['categoria_slug']])); ?>">
        Torna a <?php echo e($prodotto['categoria_nome']); ?>
    </a>
    <a href="<?php echo e(url('carrello')); ?>">Scegli la sede e ordina</a>
</p>
