<?php
/**
 * Contenuto della pagina 403: l'accesso è stato fatto, ma questo account non puo'
 * aprire la risorsa richiesta.
 *
 * La diapositiva accanto al testo porta l'illustrazione dell'errore: un disegno ritagliato
 * su fondo trasparente, che sul rosso del riquadro si vede per intero.
 */
?>
<section class="apertura-errore">
    <div class="testo-errore">
        <p class="codice-errore">403</p>
        <h1>Accesso non consentito</h1>
        <p class="introduzione">
            Il tuo account è in regola, ma questa porta dà sul retro cucina e non è la tua.
            Se ti aspettavi di poter entrare, scrivici: controlliamo i permessi.
        </p>
        <p class="azioni">
            <a class="pulsante" href="<?php echo e(url()); ?>">Torna alla home</a>
            <a class="pulsante secondario" href="<?php echo e(url('contatti')); ?>">Contattaci</a>
        </p>
    </div>

    <figure class="istantanea">
        <p class="foto-editoriale">
            <img src="<?php echo e(risorsa('images/errori/403.webp', true)); ?>"
                width="700" height="654"
                alt="Un sacchetto Smash Burger chiuso con il sigillo, e appeso un tesserino di riconoscimento con la sagoma di una persona." />
            <span>Errore 403 / solo personale</span>
            <strong>Di qua si passa in divisa</strong>
        </p>
        <figcaption>Dietro questa porta ci sono soltanto piastre e casse di cipolle.</figcaption>
    </figure>
</section>
