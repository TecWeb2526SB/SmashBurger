<?php
/**
 * mappa.php: View della mappa del sito.
 */
?>

<section aria-labelledby="titolo-mappa">
    <div class="contenitore">
        <h1 id="titolo-mappa">Mappa del sito</h1>
        <ul>
            <?php foreach (($siteMapItems ?? []) as $label => $href): ?>
                <?php if (($currentPage ?? '') === $href): ?>
                    <li aria-current="page"><?php echo htmlspecialchars($label, ENT_QUOTES, 'UTF-8'); ?></li>
                <?php else: ?>
                    <li>
                        <a href="<?php echo htmlspecialchars($href, ENT_QUOTES, 'UTF-8'); ?>">
                            <?php echo htmlspecialchars($label, ENT_QUOTES, 'UTF-8'); ?>
                        </a>
                    </li>
                <?php endif; ?>
            <?php endforeach; ?>
        </ul>
    </div>
</section>
