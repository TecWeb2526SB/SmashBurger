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
                    <a href="<?php echo htmlspecialchars($href, ENT_QUOTES, 'UTF-8'); ?>">
                        <?php echo htmlspecialchars($etichetta, ENT_QUOTES, 'UTF-8'); ?>
                    </a>
                <?php else: ?>
                    <?php echo htmlspecialchars($etichetta, ENT_QUOTES, 'UTF-8'); ?>
                <?php endif; ?>
            </li>
        <?php endforeach; ?>
    </ol>
</nav>

