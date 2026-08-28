<?php
/**
 * Navigazione fra le sezioni del pannello.
 *
 * La sezione corrente arriva in $sezione e viene indicata con aria-current invece che
 * con un collegamento.
 */
?>
<nav aria-label="Sezioni del pannello">
    <ul>
        <?php foreach (menu_controllo() as $etichetta => $indirizzo): ?>
            <li>
                <?php if (($sezione ?? '') === $etichetta): ?>
                    <span aria-current="page"><?php echo e($etichetta); ?></span>
                <?php else: ?>
                    <a href="<?php echo e($indirizzo); ?>"><?php echo e($etichetta); ?></a>
                <?php endif; ?>
            </li>
        <?php endforeach; ?>
    </ul>
</nav>
