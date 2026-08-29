<?php
/**
 * Contenuto della home: presentazione, categorie del menu, passaggi dell'ordine e
 * sedi con l'orario del giorno.
 *
 * Riceve $categorie, $categoriaScelta e $sedi dal controller.
 */
?>
<h1>Smash burger, schiacciati al momento</h1>

<p>
    Ordina online e ritira a Padova, Treviso, Vicenza o Udine. La carne finisce sulla
    piastra quando arriva il tuo ordine.
</p>

<p><a href="<?php echo e(url('menu')); ?>">Vai al menu e ai prezzi</a></p>

<section id="categorie">
    <h2>Che cosa trovi nel menu</h2>

    <p>
        Burger, contorni, bevande e dessert, con gli allergeni indicati su ogni prodotto.
        Scegli una categoria per sapere che cosa contiene.
    </p>

    <nav class="filtri" aria-label="Categorie del menu">
        <ul>
            <?php foreach ($categorie as $categoria): ?>
                <li>
                    <?php if (($categoriaScelta['slug'] ?? null) === $categoria['slug']): ?>
                        <span aria-current="true"><?php echo e($categoria['nome']); ?></span>
                    <?php else: ?>
                        <a href="<?php echo e(url('', ['categoria' => $categoria['slug']])); ?>#categorie">
                            <?php echo e($categoria['nome']); ?>
                        </a>
                    <?php endif; ?>
                </li>
            <?php endforeach; ?>
        </ul>
    </nav>

    <?php if ($categoriaScelta !== null): ?>
        <article class="scheda">
            <h3><?php echo e($categoriaScelta['nome']); ?></h3>
            <p><?php echo e($categoriaScelta['descrizione']); ?></p>
            <p><?php echo (int) $categoriaScelta['prodotti']; ?> prodotti disponibili.</p>
            <p>
                <a class="pulsante" data-tipo="positivo"
                    href="<?php echo e(url('menu', ['categoria' => $categoriaScelta['slug']])); ?>">
                    Vai a <?php echo e($categoriaScelta['nome']); ?> nel menu
                    <?php echo icona('freccia-destra'); ?>
                </a>
            </p>
        </article>
    <?php endif; ?>
</section>

<section>
    <h2>Come funziona</h2>

    <ol>
        <li>Scegli i prodotti dal menu e mettili nel carrello.</li>
        <li>Indica la sede e scegli uno degli orari di ritiro disponibili.</li>
        <li>Paghi con carta adesso, oppure in contanti quando ritiri.</li>
    </ol>
</section>

<section>
    <h2>Dove siamo</h2>

    <p>Quattro sedi, aperte tutti i giorni dalle 11:30 alle 22:30.</p>

    <ul>
        <?php foreach ($sedi as $sede): ?>
            <li>
                <h3><?php echo e($sede['citta']); ?></h3>
                <p><?php echo e($sede['indirizzo']); ?>, <?php echo e($sede['cap']); ?> <?php echo e($sede['citta']); ?> (<?php echo e($sede['provincia']); ?>)</p>
                <p>
                    <?php if (empty($sede['apertura']) || (int) $sede['chiuso'] === 1): ?>
                        Oggi chiuso
                    <?php else: ?>
                        Oggi aperto dalle <?php echo e(orario($sede['apertura'])); ?>
                        alle <?php echo e(orario($sede['chiusura'])); ?>
                    <?php endif; ?>
                </p>
                <p>Telefono <a href="tel:+39<?php echo e(str_replace(' ', '', $sede['telefono'])); ?>"><?php echo e($sede['telefono']); ?></a></p>
            </li>
        <?php endforeach; ?>
    </ul>

    <p><a href="<?php echo e(url('sedi')); ?>">Vedi indirizzi e orari completi</a></p>
</section>
