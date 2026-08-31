<?php
/**
 * Presentazione del locale e del metodo di cottura.
 */
?>
<section class="apertura-editoriale apertura-chi-siamo">
    <div>
        <p class="occhiello">Una cosa sola. Fatta sul serio.</p>
        <h1>Chi siamo</h1>
        <p class="introduzione">
            Quattro locali tra Veneto e Friuli, una piastra rovente e una tecnica che
            non ammette scorciatoie. Il burger comincia solo quando arriva il tuo ordine.
        </p>
    </div>

    <figure class="istantanea">
        <div class="foto-editoriale" role="img"
            aria-label="Un cuoco in grembiule nero solleva dalla piastra fumante un burger dalla crosta scura.">
            <img src="<?php echo e(risorsa('images/chi-siamo-smash.webp', true)); ?>"
                width="1200" height="900" alt="" />
            <span>La piastra / il nostro gesto</span>
            <strong>Il gesto che cambia tutto</strong>
        </div>
        <figcaption>Pressione, calore, crosta. Tutto in pochi secondi.</figcaption>
    </figure>
</section>

<blockquote class="manifesto">
    <p>Non prepariamo burger in anticipo. Prepariamo il tuo.</p>
</blockquote>

<div class="capitoli-storia">
    <section>
        <p class="indice-pannello">01 / La tecnica</p>
        <h2>Che cos'e' lo smash</h2>
        <div>
            <p>
                La pallina di carne viene schiacciata appena tocca la piastra. Il contatto
                largo con il metallo crea una crosta scura e saporita.
            </p>
            <p>
                La cottura dura pochissimo, cosi' l'interno resta succoso. E' una tecnica,
                non una ricetta: si fa al momento e non si prepara in anticipo.
            </p>
        </div>
    </section>

    <section>
        <p class="indice-pannello">02 / Gli ingredienti</p>
        <h2>Vicini, freschi, senza giri lunghi</h2>
        <div>
            <p>
                La carne arriva fresca e viene macinata in giornata. Pane e salse sono
                preparati per noi da fornitori della zona.
            </p>
            <p>Ogni burger viene composto quando l'ordine entra in cucina.</p>
        </div>
    </section>

    <section>
        <p class="indice-pannello">03 / Le sedi</p>
        <h2>Stesso menu. Presenza reale.</h2>
        <div>
            <p>
                I quattro locali hanno gli stessi prezzi. Ogni sede gestisce orari,
                disponibilita' dei prodotti e prenotazioni della sala.
            </p>
            <p>
                Quando ordini vedi solo cio' che e' davvero disponibile nel locale che
                hai scelto.
            </p>
        </div>
    </section>
</div>

<section class="invito-finale">
    <div>
        <p class="occhiello">Vieni a sentirla sfrigolare</p>
        <h2>Quattro citta'. La stessa piastra.</h2>
    </div>
    <p><a class="pulsante" href="<?php echo e(url('sedi')); ?>">Guarda sedi e orari</a></p>
</section>
