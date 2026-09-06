<?php
/**
 * Navigazione fra le sezioni del pannello.
 *
 * Le voci derivano da includes/pagine.php e sono già filtrate per ruolo: un manager non
 * vede le sezioni riservate all'amministratore.
 *
 * La scheda della propria sede non sta nell'elenco perchè l'amministratore ci arriva
 * dalla lista delle sedi, mentre il manager ne ha una sola: per lui è una voce a se'.
 */
?>
<nav class="filtri" aria-label="Sezioni del pannello">
    <ul>
        <?php foreach (pagine_del_menu('controllo', $ruoloCorrente) as $slug => $etichetta): ?>
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

        <?php if ($ruoloCorrente === 'manager'): ?>
            <li>
                <?php if ($slugCorrente === 'controllo-sede'): ?>
                    <span aria-current="page">La tua sede</span>
                <?php else: ?>
                    <a href="<?php echo e(url('controllo-sede')); ?>">La tua sede</a>
                <?php endif; ?>
            </li>
        <?php endif; ?>
    </ul>
</nav>
