<?php
/**
 * Mappa del sito. Riceve $aree, cioè gruppi di coppie slug/etichetta già filtrate
 * per il ruolo di chi guarda.
 */
?>
<h1>Mappa del sito</h1>

<p>
    Tutte le pagine che puoi raggiungere, raggruppate per area. L'elenco cambia in base
    al tuo account: da qui vedi solo quello che sei autorizzato ad aprire.
</p>

<?php foreach ($aree as $titoloArea => $voci): ?>
    <?php if ($voci !== []): ?>
        <section>
            <h2><?php echo e($titoloArea); ?></h2>
            <ul>
                <?php foreach ($voci as $slug => $etichetta): ?>
                    <li>
                        <?php if ($slug === $slugCorrente): ?>
                            <span aria-current="page">
                                <?php echo e($etichetta); ?>
                                <?php if ($slug === 'controllo-sedi'): ?>
                                    <span class="solo-lettori"> del pannello</span>
                                <?php endif; ?>
                            </span>
                        <?php else: ?>
                            <a href="<?php echo e(url($slug)); ?>">
                                <?php echo e($etichetta); ?>
                                <?php if ($slug === 'controllo-sedi'): ?>
                                    <span class="solo-lettori"> del pannello</span>
                                <?php endif; ?>
                            </a>
                        <?php endif; ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        </section>
    <?php endif; ?>
<?php endforeach; ?>
