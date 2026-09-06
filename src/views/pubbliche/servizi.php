<?php
/**
 * Descrizione dei servizi: ritiro, consegna, sala eventi, allergeni e pagamento.
 */
?>
<section class="apertura-editoriale">
    <div>
        <p class="occhiello">Come vuoi mangiarlo?</p>
        <h1>Servizi</h1>
        <p class="introduzione">
            Ordina online, scegli come ricevere e lascia a noi il lavoro caldo. Ritiro,
            consegna e una sala per le occasioni che meritano un tavolo lungo.
        </p>
        <p><a class="pulsante" data-tipo="positivo" href="<?php echo e(url('carrello')); ?>">Inizia un ordine</a></p>
    </div>

    <figure class="istantanea">
        <div class="foto-editoriale" role="img"
            aria-label="Un addetto porge al cliente un vassoio con un burger fumante e patatine.">
            <img src="<?php echo e(risorsa('images/servizi-ritiro.webp', true)); ?>"
                width="800" height="600" alt="" />
            <span>Ritiro / pronto al banco</span>
            <strong>Pronto quando lo sei tu</strong>
        </div>
        <figcaption>Ritiro, consegna o una serata intera da noi.</figcaption>
    </figure>
</section>

<div class="griglia-servizi">
    <section class="scheda-servizio servizio-ritiro">
        <p class="indice-pannello">01 / Ritiro</p>
        <h2>Passa, prendi, mordi.</h2>
        <p>
            Scegli la sede, aggiungi i prodotti e indica l'orario. Prepariamo l'ordine
            per quel momento, così al banco non perdi tempo.
        </p>
        <p class="nota-servizio">Disponibile negli orari di apertura della sede scelta.</p>
    </section>

    <section class="scheda-servizio servizio-consegna">
        <p class="indice-pannello">02 / Consegna</p>
        <h2>La piastra arriva a casa.</h2>
        <p>
            Inserisci l'indirizzo durante l'ordine. Dopo il pagamento la consegna viene
            presa in carico da una societa' esterna.
        </p>
        <p class="nota-servizio">
            Se l'indirizzo non è raggiungibile, annulliamo e rimborsiamo l'ordine.
        </p>
    </section>

    <section class="scheda-servizio servizio-eventi">
        <p class="indice-pannello">03 / Eventi</p>
        <h2>Porta la crew. Alla sala pensiamo noi.</h2>
        <p>
            Compleanni, feste di laurea e cene di gruppo: scegli sede, giorno, orario di
            inizio e durata. Il personale ti confermera' la prenotazione.
        </p>
        <p><a class="pulsante secondario" href="<?php echo e(url('sedi')); ?>">Scegli la sede e prenota</a></p>
    </section>

    <section class="scheda-servizio servizio-info">
        <p class="indice-pannello">04 / Allergeni</p>
        <h2>Tutto scritto. Prima del morso.</h2>
        <p>
            Gli allergeni sono nel menu e nella scheda di ogni prodotto. Nelle cucine si
            lavorano glutine, latte, uova e frutta a guscio.
        </p>
        <p><a href="<?php echo e(url('contatti')); ?>">Scrivici se hai un'allergia</a>.</p>
    </section>

    <section class="scheda-servizio servizio-pagamento">
        <p class="indice-pannello">05 / Pagamento</p>
        <h2>Carta o contanti. Senza sorprese.</h2>
        <p>
            Scegli il metodo al termine dell'ordine. Con la carta chiudi tutto subito,
            con i contanti paghi al ritiro in sede o quando arriva la consegna.
        </p>
    </section>
</div>
