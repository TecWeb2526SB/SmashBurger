<?php
/**
 * Contenuto della home. Riceve $categorie e $sedi dal controller.
 */
?>
<h1>Hamburger smash, preparati al momento</h1>

<p>
    Schiacciamo la carne sulla piastra rovente e la serviamo appena pronta, nelle nostre
    quattro sedi di Padova, Treviso, Vicenza e Udine. Ordini online e scegli se passare a
    ritirare o farti consegnare a casa.
</p>

<p>
    <a href="<?php echo e(url('menu')); ?>">Guarda il menu</a>
    <a href="<?php echo e(url('carrello')); ?>">Ordina adesso</a>
</p>

<section>
    <h2>Che cosa si mangia</h2>

    <ul class="griglia">
        <?php foreach ($categorie as $categoria): ?>
            <li>
                <article class="scheda">
                    <h3>
                        <a href="<?php echo e(url('menu', ['categoria' => $categoria['slug']])); ?>">
                            <?php echo e($categoria['nome']); ?>
                        </a>
                    </h3>
                    <p><?php echo e($categoria['descrizione']); ?></p>
                </article>
            </li>
        <?php endforeach; ?>
    </ul>
</section>

<section>
    <h2>Dove siamo</h2>

    <ul class="griglia">
        <?php foreach ($sedi as $sede): ?>
            <li>
                <article class="scheda">
                    <h3>
                        <a href="<?php echo e(url('sede', ['slug' => $sede['slug']])); ?>">
                            <?php echo e($sede['citta']); ?>
                        </a>
                    </h3>
                    <p><?php echo e($sede['indirizzo']); ?>, <?php echo e($sede['cap']); ?> <?php echo e($sede['citta']); ?> (<?php echo e($sede['provincia']); ?>)</p>
                    <?php if ((int) $sede['sala_eventi_disponibile'] === 1): ?>
                        <p><span class="etichetta" data-tipo="positivo">Sala eventi prenotabile</span></p>
                    <?php endif; ?>
                </article>
            </li>
        <?php endforeach; ?>
    </ul>

    <p><a href="<?php echo e(url('sedi')); ?>">Vedi tutte le sedi con gli orari</a></p>
</section>

<section>
    <h2>Come funziona l'ordine</h2>

    <ol>
        <li>Scegli la sede da cui vuoi ordinare.</li>
        <li>Aggiungi i prodotti disponibili in quella sede.</li>
        <li>Decidi se ritirare in sede o farti consegnare a casa.</li>
        <li>Paghi e ricevi la ricevuta nella tua area personale.</li>
    </ol>

    <p><a href="<?php echo e(url('servizi')); ?>">Leggi i dettagli sui servizi</a></p>
</section>
