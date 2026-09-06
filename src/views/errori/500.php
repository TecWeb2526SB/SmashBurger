<?php
/**
 * Contenuto della pagina 500: la richiesta non è stata completata per un problema del
 * server. Il dettaglio tecnico resta nel log.
 *
 * Questa vista compare anche quando il database non risponde, quindi non legge nessun
 * dato: l'illustrazione della diapositiva e' un file statico, non un contenuto caricato.
 */
?>
<section class="apertura-errore">
    <div class="testo-errore">
        <p class="codice-errore">500</p>
        <h1>Errore del server</h1>
        <p class="introduzione">
            Non siamo riusciti a completare la richiesta, e non è colpa tua. Il problema è
            già finito nel registro del server: riprova fra qualche minuto.
        </p>
        <p class="azioni">
            <a class="pulsante" href="<?php echo e(url()); ?>">Torna alla home</a>
        </p>
    </div>

    <figure class="istantanea">
        <p class="foto-editoriale">
            <img src="<?php echo e(risorsa('images/errori/500.webp', true)); ?>"
                width="700" height="568"
                alt="Un burger schiacciato e rovesciato sul suo incarto, con la carne e la salsa colate fuori." />
            <span>Errore 500 / piastra fredda</span>
            <strong>Si è spenta la piastra</strong>
        </p>
        <figcaption>Ci stiamo già rimettendo il grembiule.</figcaption>
    </figure>
</section>
