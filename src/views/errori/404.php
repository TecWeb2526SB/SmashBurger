<?php
/**
 * Contenuto della pagina 404. Oltre a dire che cosa è successo offre i collegamenti
 * alle pagine principali, perchè chi arriva qui deve poter ripartire senza tornare
 * indietro con il browser.
 */
?>
<h1>Pagina non trovata</h1>

<p>
    L'indirizzo richiesto non corrisponde a nessuna pagina del sito. Puo' essere stato
    scritto male, oppure la pagina non esiste piu'.
</p>

<section>
    <h2>Dove vuoi andare</h2>
    <ul>
        <?php foreach (pagine_del_menu('principale', $ruoloCorrente) as $slug => $etichetta): ?>
            <li><a href="<?php echo e(url($slug)); ?>"><?php echo e($etichetta); ?></a></li>
        <?php endforeach; ?>
        <li><a href="<?php echo e(url('mappa-sito')); ?>">Mappa del sito</a></li>
        <li><a href="<?php echo e(url('contatti')); ?>">Segnala il problema</a></li>
    </ul>
</section>
