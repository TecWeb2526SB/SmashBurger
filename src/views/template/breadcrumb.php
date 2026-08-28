<?php
/**
 * Percorso di navigazione.
 *
 * Viene stampato solo se il controller ha passato $breadcrumb, come elenco di coppie
 * [etichetta, indirizzo]; l'ultima voce ha indirizzo nullo perché corrisponde alla
 * pagina corrente.
 */
?>
<?php if (!empty($breadcrumb)): ?>
    <nav aria-label="Percorso">
        <ol>
            <?php foreach ($breadcrumb as [$etichetta, $indirizzo]): ?>
                <li>
                    <?php if ($indirizzo === null): ?>
                        <span aria-current="page"><?php echo e($etichetta); ?></span>
                    <?php else: ?>
                        <a href="<?php echo e($indirizzo); ?>"><?php echo e($etichetta); ?></a>
                    <?php endif; ?>
                </li>
            <?php endforeach; ?>
        </ol>
    </nav>
<?php endif; ?>
