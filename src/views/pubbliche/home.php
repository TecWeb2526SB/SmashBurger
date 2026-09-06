<?php
/**
 * Home pubblica. Riceve $podio e $sedi dal controller.
 */
?>
<section class="apertura-home">
    <div class="apertura-testo">
        <p class="occhiello">Smash burger · Veneto e Friuli</p>
        <h1>Schiacciata sulla piastra. Pronta quando arrivi.</h1>
        <p class="introduzione">
            Carne pressata sul momento, crosta scura e succosa dentro. Scegli la sede,
            ordina online e decidi se passare da noi o riceverla a casa.
        </p>
        <p class="azioni">
            <a class="pulsante" data-tipo="positivo" href="<?php echo e(url('carrello')); ?>">Ordina adesso</a>
            <a class="pulsante secondario" href="<?php echo e(url('menu')); ?>">Guarda il menu</a>
        </p>
        <ul class="promesse" aria-label="I nostri punti fermi">
            <li><strong>04</strong> sedi</li>
            <li><strong>01</strong> burger alla volta</li>
            <li><strong>100%</strong> fatto al momento</li>
        </ul>
    </div>

    <figure class="istantanea istantanea-hero">
        <div class="foto-editoriale" role="img"
            aria-label="Una pressa d'acciaio schiaccia una pallina di manzo sulla piastra rovente.">
            <img src="<?php echo e(risorsa('images/home-hero-smash.webp', true)); ?>"
                width="640" height="800" alt="" />
            <span>Piastra / fatto al momento</span>
            <strong>Lo smash nell'istante esatto</strong>
        </div>
        <figcaption>Piastra rovente, crosta netta, zero attese.</figcaption>
    </figure>
</section>

<?php if ($podio !== []): ?>
    <section class="podio">
        <header class="header-sezione">
            <div>
                <p class="occhiello">Scelti dalla crew</p>
                <h2>Il podio della piastra</h2>
            </div>
            <p>Tre burger, tre caratteri. Se è la prima volta che ordini da noi, parti da qui.</p>
        </header>

        <ol class="podio-classifica">
            <?php foreach ($podio as $posizione => $prodotto): ?>
                <li>
                    <article class="concorrente-podio">
                        <div class="panino-podio">
                            <img src="<?php echo e(risorsa('uploads/prodotti/' . $prodotto['immagine'], true)); ?>"
                                width="400" height="300" alt="" />
                            <div>
                                <h3>
                                    <a href="<?php echo e(url('prodotto', ['slug' => $prodotto['slug']])); ?>">
                                        <?php echo e($prodotto['nome']); ?>
                                    </a>
                                </h3>
                                <p class="prezzo"><?php echo e(prezzo((int) $prodotto['prezzo_centesimi'])); ?></p>
                            </div>
                        </div>

                        <div class="gradino-podio">
                            <p class="posizione">
                                <?php echo (int) $posizione + 1; ?>
                                <span class="solo-lettori">posto</span>
                            </p>
                            <p><?php echo e($prodotto['descrizione']); ?></p>
                        </div>
                    </article>
                </li>
            <?php endforeach; ?>
        </ol>

        <p class="chiusura-sezione">
            <a class="collegamento-freccia" href="<?php echo e(url('menu')); ?>"><span>Vedi tutto il menu</span><span class="segno-collegamento" aria-hidden="true">&gt;</span></a>
        </p>
    </section>
<?php endif; ?>

<section class="racconto racconto-eventi">
    <figure class="istantanea">
        <div class="foto-editoriale" role="img"
            aria-label="Un gruppo di ragazzi brinda a tavola con burger e patatine; uno skateboard è appoggiato al tavolo.">
            <img src="<?php echo e(risorsa('images/home-eventi.webp', true)); ?>"
                width="800" height="600" loading="lazy" alt="" />
            <span>Sala eventi / la crew al completo</span>
            <strong>La vostra serata, apparecchiata</strong>
        </div>
        <figcaption>Compleanni, lauree e cene con tutta la crew.</figcaption>
    </figure>

    <div class="racconto-testo">
        <p class="occhiello">Sala eventi</p>
        <h2>La sala è vostra per una sera</h2>
        <p>
            Ogni sede ha uno spazio prenotabile. Scegli il giorno, l'orario di inizio e
            per quanto ti serve: al resto pensiamo noi.
        </p>
        <p><a class="pulsante" href="<?php echo e(url('servizi')); ?>">Scopri come funziona</a></p>
    </div>
</section>

<section class="racconto racconto-metodo">
    <div class="racconto-testo">
        <p class="occhiello">Il nostro metodo</p>
        <h2>Una tecnica, non una ricetta</h2>
        <p>
            La pallina di carne tocca la piastra rovente e viene schiacciata subito. Il
            contatto largo crea la crosta; la cottura breve tiene l'interno succoso.
        </p>
        <p><a class="pulsante secondario" href="<?php echo e(url('chi-siamo')); ?>">Entra in cucina</a></p>
    </div>

    <figure class="istantanea">
        <div class="foto-editoriale" role="img"
            aria-label="Una mano con guanto nero preme la carne sulla piastra con una spatola dal manico senape.">
            <img src="<?php echo e(risorsa('images/home-metodo-smash.webp', true)); ?>"
                width="800" height="600" loading="lazy" alt="" />
            <span>Metodo / pressione e calore</span>
            <strong>Schiaccia. Sfrigola. Gira.</strong>
        </div>
        <figcaption>Ogni ordine comincia da questo gesto.</figcaption>
    </figure>
</section>

<section class="citta">
    <header class="header-sezione">
        <div>
            <p class="occhiello">La sede giusta è quella vicina</p>
            <h2>Siamo dietro l'angolo</h2>
        </div>
        <p>Padova, Treviso, Vicenza e Udine. Aperti ogni giorno dalle 11:30 alle 22:30.</p>
    </header>

    <ul class="griglia griglia-citta">
        <?php foreach ($sedi as $sede): ?>
            <li>
                <article class="scheda scheda-citta">
                    <p class="sigla-citta"><?php echo e($sede['provincia']); ?></p>
                    <h3>
                        <a href="<?php echo e(url('sede', ['slug' => $sede['slug']])); ?>">
                            <?php echo e($sede['citta']); ?>
                        </a>
                    </h3>
                    <p><?php echo e($sede['indirizzo']); ?></p>
                    <?php if ((int) $sede['sala_eventi_disponibile'] === 1): ?>
                        <p><span class="etichetta" data-tipo="positivo">Sala eventi</span></p>
                    <?php endif; ?>
                </article>
            </li>
        <?php endforeach; ?>
    </ul>
</section>

<section class="ordine-semplice">
    <header class="header-sezione">
        <div>
            <p class="occhiello">Dal telefono al primo morso</p>
            <h2>Quattro passi. Nessuna coda.</h2>
        </div>
    </header>

    <ol class="passi">
        <li><strong>Scegli</strong> la sede da cui vuoi ordinare.</li>
        <li><strong>Componi</strong> il tuo ordine con i prodotti disponibili.</li>
        <li><strong>Decidi</strong> fra ritiro in sede o consegna a casa.</li>
        <li><strong>Ritira</strong> all'orario scelto e salta la coda.</li>
    </ol>
    <p class="azioni"><a class="pulsante" data-tipo="positivo" href="<?php echo e(url('carrello')); ?>">Inizia l'ordine</a></p>
</section>
