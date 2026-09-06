<?php
/**
 * Contenuto della pagina 404. Oltre a dire che cosa è successo offre i collegamenti
 * alle pagine principali, perchè chi arriva qui deve poter ripartire senza tornare
 * indietro con il browser.
 *
 * La diapositiva accanto al testo porta l'illustrazione dell'errore: un disegno ritagliato
 * su fondo trasparente, che sul rosso del riquadro si vede per intero.
 */
?>
<section class="apertura-errore">
    <div class="testo-errore">
        <p class="codice-errore">404</p>
        <h1>Pagina non trovata</h1>
        <p class="introduzione">
            Questo indirizzo non è in carta: può darsi che sia stato scritto male, oppure
            che la pagina non esista più. Il menu vero, però, è tutto qui sotto.
        </p>
        <p class="azioni">
            <a class="pulsante" href="<?php echo e(url()); ?>">Torna alla home</a>
            <a class="pulsante secondario" href="<?php echo e(url('menu')); ?>">Guarda il menu</a>
        </p>
    </div>

    <figure class="istantanea">
        <p class="foto-editoriale">
            <img src="<?php echo e(risorsa('images/errori/404.webp', true)); ?>"
                width="700" height="698"
                alt="Un incarto Smash Burger accartocciato e vuoto: del panino non è rimasto niente." />
            <span>Errore 404 / fuori menu</span>
            <strong>Questo panino non è in carta</strong>
        </p>
        <figcaption>Abbiamo guardato su tutte le piastre: qui non c&apos;è.</figcaption>
    </figure>
</section>

<section>
    <h2>Dove vuoi andare</h2>
    <ul class="ripartenza">
        <?php foreach (pagine_del_menu('principale', $ruoloCorrente) as $slug => $etichetta): ?>
            <li><a class="pulsante secondario" href="<?php echo e(url($slug)); ?>"><?php echo e($etichetta); ?></a></li>
        <?php endforeach; ?>
        <li><a class="pulsante secondario" href="<?php echo e(url('mappa-sito')); ?>">Mappa del sito</a></li>
        <li><a class="pulsante secondario" href="<?php echo e(url('contatti')); ?>">Segnala il problema</a></li>
    </ul>
</section>
