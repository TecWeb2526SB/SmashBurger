<?php
/**
 * Navigazione fra le sezioni del pannello.
 *
 * Le voci derivano da includes/pagine.php e sono gia' filtrate per ruolo: un manager non
 * vede le sezioni riservate all'amministratore.
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
    </ul>
</nav>
