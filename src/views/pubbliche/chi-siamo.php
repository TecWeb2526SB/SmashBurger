<?php
/**
 * Presentazione dell'attività. Riceve $sedi dal controller.
 */
?>
<h1>Chi siamo</h1>

<p>
    Smash Burger prepara hamburger con la tecnica dello smash: la carne viene schiacciata
    sulla piastra rovente, così la superficie forma una crosta e l'interno resta succoso.
</p>

<section>
    <h2>Come lavoriamo</h2>

    <p>
        Ogni hamburger viene cotto dopo la conferma dell'ordine. Il pane arriva
        giornalmente, la carne viene lavorata in sede e le salse sono preparate
        internamente.
    </p>
</section>

<section>
    <h2>Il locale</h2>

    <figure>
        <img src="images/locale-interno.webp"
            alt="Bancone di un locale Smash Burger con la cucina a vista sul fondo" />
        <figcaption>La cucina è a vista in tutte le sedi.</figcaption>
    </figure>
</section>

<section>
    <h2>Dove siamo</h2>

    <ul>
        <?php foreach ($sedi as $sede): ?>
            <li><?php echo e($sede['citta']); ?>, <?php echo e($sede['indirizzo']); ?></li>
        <?php endforeach; ?>
    </ul>

    <p><a href="<?php echo e(url('sedi')); ?>">Orari e contatti di ogni sede</a></p>
</section>
