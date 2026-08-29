<?php
/**
 * Presentazione dell'attività. Riceve $sedi dal controller.
 */
?>
<h1>Chi siamo</h1>

<p>
    Smash Burger prepara hamburger con la tecnica dello smash: la carne viene schiacciata
    sulla piastra rovente, la superficie forma una crosta e l'interno resta succoso.
</p>

<section>
    <h2>Come lavoriamo</h2>

    <p>
        Il patty è sottile e cuoce in un paio di minuti su piastra molto calda: è la
        temperatura a creare la crosta scura in superficie. Un hamburger così non regge
        l'attesa, quindi va in piastra quando serve.
    </p>
</section>

<section>
    <h2>Il locale</h2>

    <figure>
        <img src="images/locale-interno.webp" width="1400" height="1400"
            alt="Bancone di un locale Smash Burger con la cucina a vista sul fondo" />
        <figcaption>La cucina è a vista: mentre aspetti vedi la piastra al lavoro.</figcaption>
    </figure>
</section>

<section>
    <h2>Dove siamo</h2>

    <p>Quattro sedi in Veneto e Friuli.</p>

    <ul>
        <?php foreach ($sedi as $sede): ?>
            <li><?php echo e($sede['citta']); ?>, <?php echo e($sede['indirizzo']); ?></li>
        <?php endforeach; ?>
    </ul>

    <p><a href="<?php echo e(url('sedi')); ?>">Orari e contatti di ogni sede</a></p>
</section>
