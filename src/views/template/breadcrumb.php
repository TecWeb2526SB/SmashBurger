<?php
/**
 * breadcrumb.php: Componente breadcrumb riutilizzabile.
 *
 * Variabile attesa dal controller:
 *   $breadcrumb  array  Es: [['Home', './'], ['Servizi', null]]
 *                       L'ultimo elemento ha href null → pagina corrente.
 */
if (empty($breadcrumb)) return;
?>
<nav class="breadcrumb" aria-label="Percorso di navigazione">
    <ol>
        <?php foreach ($breadcrumb as $i => $voce):
            [$etichetta, $href] = $voce;
            $isUltima = ($i === array_key_last($breadcrumb));
        ?>
            <li <?php echo $isUltima ? 'aria-current="page"' : ''; ?>>
                <?php if (!$isUltima && $href): ?>
                    <a href="<?php echo e($href); ?>">
                        <?php echo e($etichetta); ?>
                    </a>
                <?php else: ?>
                    <?php echo e($etichetta); ?>
                <?php endif; ?>
            </li>
        <?php endforeach; ?>
    </ol>
</nav>

