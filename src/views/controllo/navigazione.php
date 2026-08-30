<?php
/**
 * Navigazione fra le sezioni del pannello.
 *
 * Le voci derivano da includes/pagine.php e sono gia' filtrate per ruolo: un manager non
 * vede le sezioni riservate all'amministratore.
 *
 * La scheda della propria sede non sta nell'elenco perche' l'amministratore ci arriva
 * dalla lista delle sedi, mentre il manager ne ha una sola: per lui e' una voce a se'.
 */
?>
<nav class="filtri" aria-label="Sezioni del pannello">
    <ul>
        <?php foreach (pagine_del_menu('controllo', $ruoloCorrente) as $slug => $etichetta): ?>
            <li>
                <?php if ($slug === $slugCorrente): ?>
                    <a href="<?php echo e(url($slug)); ?>" aria-current="page"><?php echo e($etichetta); ?></a>
                <?php else: ?>
                    <a href="<?php echo e(url($slug)); ?>"><?php echo e($etichetta); ?></a>
                <?php endif; ?>
            </li>
        <?php endforeach; ?>

        <?php if ($ruoloCorrente === 'manager'): ?>
            <li>
                <?php if ($slugCorrente === 'controllo-sede'): ?>
                    <a href="<?php echo e(url('controllo-sede')); ?>" aria-current="page">La tua sede</a>
                <?php else: ?>
                    <a href="<?php echo e(url('controllo-sede')); ?>">La tua sede</a>
                <?php endif; ?>
            </li>
        <?php endif; ?>
    </ul>
</nav>
