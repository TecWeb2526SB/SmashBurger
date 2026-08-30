<?php
/**
 * Scheda pubblica di una sede. Riceve $sede, $orari e $giorni dal controller.
 *
 * L'immagine e' ancora un segnaposto: il testo alternativo dice quello che si vede
 * davvero e va riscritto insieme alla fotografia definitiva.
 */
?>
<h1>Smash Burger <?php echo e($sede['citta']); ?></h1>

<img src="<?php echo e(risorsa('images/sedi/' . $sede['slug'] . '.webp')); ?>"
    width="600" height="400"
    alt="Immagine segnaposto in attesa della fotografia della sede di <?php echo e($sede['citta']); ?>" />

<section>
    <h2>Dove siamo</h2>
    <address>
        <?php echo e($sede['indirizzo']); ?><br />
        <?php echo e($sede['cap']); ?> <?php echo e($sede['citta']); ?> (<?php echo e($sede['provincia']); ?>)<br />
        Telefono <a href="tel:<?php echo e(str_replace(' ', '', $sede['telefono'])); ?>"><?php echo e($sede['telefono']); ?></a><br />
        <a href="mailto:<?php echo e($sede['email']); ?>"><?php echo e($sede['email']); ?></a>
    </address>
</section>

<section>
    <h2>Orari</h2>
    <table>
        <caption>Orari di apertura della sede di <?php echo e($sede['citta']); ?></caption>
        <thead>
            <tr>
                <th scope="col">Giorno</th>
                <th scope="col">Apertura</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($giorni as $numero => $nome): ?>
                <tr>
                    <th scope="row"><?php echo e($nome); ?></th>
                    <td><?php echo e(fascia_leggibile($orari[$numero])); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</section>

<section>
    <h2>Ritiro degli ordini</h2>
    <?php if ($sede['note_ritiro'] !== null && $sede['note_ritiro'] !== ''): ?>
        <p><?php echo e($sede['note_ritiro']); ?></p>
    <?php endif; ?>
    <p>Da questa sede puoi anche farti consegnare l'ordine a casa.</p>
    <p><a href="<?php echo e(url('carrello', ['sede' => $sede['slug']])); ?>">Ordina da questa sede</a></p>
</section>

<section>
    <h2>Sala eventi</h2>
    <?php if ((int) $sede['sala_eventi_disponibile'] === 1): ?>
        <p>
            Scegli il giorno, l'orario di inizio e per quanto ti serve la sala. E' adatta a
            compleanni, feste di laurea e cene di gruppo.
        </p>
        <p><a href="<?php echo e(url('prenota', ['sede' => $sede['slug']])); ?>">Prenota la sala</a></p>
    <?php else: ?>
        <p>
            <span class="etichetta" data-tipo="attenzione">Non prenotabile</span>
            In questo periodo la sala di <?php echo e($sede['citta']); ?> non accetta prenotazioni.
        </p>
        <p><a href="<?php echo e(url('sedi')); ?>">Guarda le altre sedi</a></p>
    <?php endif; ?>
</section>

<p class="navigazione-pagina">
    <a href="<?php echo e(url('sedi')); ?>">Torna alle sedi</a>
    <a href="<?php echo e(url('menu')); ?>">Guarda il menu</a>
</p>
