<?php
/**
 * Elenco sintetico delle sedi. Gli orari completi sono nella scheda della sede.
 */
?>
<section class="apertura-pagina apertura-sedi">
    <div>
        <p class="occhiello">Quattro citta'. Una sola crosta.</p>
        <h1>Sedi e orari</h1>
        <p class="introduzione">
            Ritira il tuo ordine o ricevilo a casa. Ogni sede ha il proprio ritmo, gli
            stessi prezzi e una sala per feste e ricorrenze.
        </p>
    </div>
    <p class="numero-sedi"><strong>04</strong><span>locali<br />aperti ogni giorno</span></p>
</section>

<ul class="elenco-sedi">
    <?php foreach ($sedi as $sede): ?>
        <li>
            <article class="scheda-sede">
                <figure class="istantanea istantanea-sede">
                    <img src="<?php echo e(risorsa('images/sedi/' . $sede['slug'] . '.webp', true)); ?>"
                        width="1200" height="800" loading="lazy"
                        alt="<?php echo e(testo_alternativo_sede($sede['slug'], $sede['citta'])); ?>" />
                    <figcaption>Smash Burger <?php echo e($sede['citta']); ?>, vista dalla strada.</figcaption>
                </figure>

                <div class="dati-sede">
                    <div class="titolo-sede">
                        <p class="sigla-citta"><?php echo e($sede['provincia']); ?></p>
                        <h2>
                            <a href="<?php echo e(url('sede', ['slug' => $sede['slug']])); ?>">
                                <?php echo e($sede['citta']); ?>
                            </a>
                        </h2>
                    </div>

                    <address>
                        <?php echo e($sede['indirizzo']); ?><br />
                        <?php echo e($sede['cap']); ?> <?php echo e($sede['citta']); ?> (<?php echo e($sede['provincia']); ?>)<br />
                        <a href="tel:<?php echo e(str_replace(' ', '', $sede['telefono'])); ?>"><?php echo e($sede['telefono']); ?></a>
                    </address>

                    <p>
                        <?php if ((int) $sede['sala_eventi_disponibile'] === 1): ?>
                            <span class="etichetta" data-tipo="positivo">Sala eventi prenotabile</span>
                        <?php else: ?>
                            <span class="etichetta" data-tipo="attenzione">Sala eventi non prenotabile</span>
                        <?php endif; ?>
                    </p>

                    <p><a class="collegamento-freccia" href="<?php echo e(url('sede', ['slug' => $sede['slug']])); ?>"><span>Apri la sede</span><span class="segno-collegamento" aria-hidden="true">&gt;</span></a></p>
                </div>
            </article>
        </li>
    <?php endforeach; ?>
</ul>
