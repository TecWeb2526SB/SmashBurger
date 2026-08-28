<?php
/**
 * Elenco delle pagine raggiungibili. Riceve $sezioni dal controller.
 */
?>
<h1>Mappa del sito</h1>

<?php foreach ($sezioni as $titoloSezione => $voci): ?>
    <section>
        <h2><?php echo e($titoloSezione); ?></h2>

        <ul>
            <?php foreach ($voci as $etichetta => $indirizzo): ?>
                <li><a href="<?php echo e($indirizzo); ?>"><?php echo e($etichetta); ?></a></li>
            <?php endforeach; ?>
        </ul>
    </section>
<?php endforeach; ?>
