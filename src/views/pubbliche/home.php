<?php
/**
 * Contenuto della home: presentazione, categorie del menu, passaggi dell'ordine e
 * sedi con l'orario del giorno.
 *
 * Riceve $categorie e $sedi dal controller.
 */
?>
<h1>Hamburger smash, pronti da ritirare</h1>

<p>
    Prepariamo hamburger schiacciati sulla piastra al momento dell'ordine.
    Scegli i prodotti, indica la sede e l'orario, e ritira senza fare la coda.
</p>

<p><a href="<?php echo e(url('prodotti')); ?>">Vai al menu e ai prezzi</a></p>

<section>
    <h2>Che cosa trovi nel menu</h2>

    <ul>
        <?php foreach ($categorie as $categoria): ?>
            <li>
                <a href="<?php echo e(url('prodotti', ['categoria' => $categoria['slug']])); ?>">
                    <?php echo e($categoria['nome']); ?>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>
</section>

<section>
    <h2>Come funziona l'ordine</h2>

    <ol>
        <li>Scegli i prodotti dal menu e aggiungili al carrello.</li>
        <li>Indica la sede e l'orario di ritiro fra quelli disponibili.</li>
        <li>Paghi online oppure in cassa al momento del ritiro.</li>
    </ol>
</section>

<section>
    <h2>Le nostre sedi</h2>

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
