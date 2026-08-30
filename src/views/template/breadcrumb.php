<?php
/**
 * Percorso di navigazione, presente su tutte le pagine tranne la home.
 *
 * Riceve $breadcrumb come elenco di coppie [etichetta, indirizzo]; l'ultima voce e' la
 * pagina corrente e non ha indirizzo.
 */

if (($breadcrumb ?? []) === []) {
    return;
}
?>
<nav aria-label="Percorso">
    <ol>
        <?php foreach ($breadcrumb as $voce): ?>
            <li>
                <?php if ($voce[1] === null): ?>
                    <span aria-current="page"><?php echo e($voce[0]); ?></span>
                <?php else: ?>
                    <a href="<?php echo e($voce[1]); ?>"><?php echo e($voce[0]); ?></a>
                <?php endif; ?>
            </li>
        <?php endforeach; ?>
    </ol>
</nav>
