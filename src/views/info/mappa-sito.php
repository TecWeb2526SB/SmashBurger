<?php
/**
 * mappa-sito: View della mappa del sito.
 *
 * Variabili attese dal controller:
 *   $siteMapItems array   Mappa dei link del sito
 *   $currentPage  string  Pagina corrente
 */
?>

<section aria-labelledby="titolo-mappa">
    <div class="contenitore">
        <h1 id="titolo-mappa">Mappa del sito</h1>
        <ul>
            <?php foreach (($siteMapItems ?? []) as $label => $href): ?>
                <?php if (($currentPage ?? '') === $href): ?>
                    <li aria-current="page"><?php echo e($label); ?></li>
                <?php else: ?>
                    <li>
                        <a href="<?php echo e($href); ?>">
                            <?php echo e($label); ?>
                        </a>
                    </li>
                <?php endif; ?>
            <?php endforeach; ?>
        </ul>
    </div>
</section>
