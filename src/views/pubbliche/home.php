<?php
/**
 * Contenuto della home: presentazione, categorie del menu, passaggi dell'ordine e
 * sedi con l'orario del giorno.
 *
 * Riceve $categorie e $sedi dal controller.
 */
?>
<h1>Smash burger, schiacciati al momento</h1>

<p>
    Ordina online e ritira a Padova, Treviso, Vicenza o Udine. La carne finisce sulla
    piastra quando arriva il tuo ordine.
</p>

<p><a href="<?php echo e(url('prodotti')); ?>">Vai al menu e ai prezzi</a></p>

<section>
    <h2>Che cosa trovi nel menu</h2>

    <p>
        Burger, contorni, bevande e dessert. Stesso menu e stessi prezzi in tutte le sedi,
        con gli allergeni indicati su ogni prodotto.
    </p>

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
