<?php
/**
 * Scheda pubblica di una sede. Riceve $sede, $orari e $giorni dal controller.
 */
?>
<section class="apertura-sede">
    <div class="testo-apertura-sede">
        <p class="occhiello"><?php echo e($sede['provincia']); ?> · Aperto ogni giorno</p>
        <h1>Smash Burger <?php echo e($sede['citta']); ?></h1>
        <address class="indirizzo-grande">
            <?php echo e($sede['indirizzo']); ?><br />
            <?php echo e($sede['cap']); ?> <?php echo e($sede['citta']); ?> (<?php echo e($sede['provincia']); ?>)
        </address>
        <p class="azioni">
            <a class="pulsante" data-tipo="positivo" href="<?php echo e(url('carrello', ['sede' => $sede['slug']])); ?>">Ordina da qui</a>
            <a class="pulsante secondario" href="tel:<?php echo e(str_replace(' ', '', $sede['telefono'])); ?>">Chiama la sede</a>
        </p>
    </div>

    <figure class="istantanea istantanea-locale">
        <img src="<?php echo e(risorsa('images/sedi/' . $sede['slug'] . '.webp', true)); ?>"
            width="800" height="600"
            alt="<?php echo e(testo_alternativo_sede($sede['slug'], $sede['citta'])); ?>" />
        <figcaption>La sede Smash Burger di <?php echo e($sede['citta']); ?>.</figcaption>
    </figure>
</section>

<div class="griglia-sede-dettaglio">
    <section class="pannello-informativo contatti-sede">
        <p class="indice-pannello">01 / Contatti</p>
        <h2>Dove siamo</h2>
        <address>
            <?php echo e($sede['indirizzo']); ?><br />
            <?php echo e($sede['cap']); ?> <?php echo e($sede['citta']); ?> (<?php echo e($sede['provincia']); ?>)<br />
            <a href="tel:<?php echo e(str_replace(' ', '', $sede['telefono'])); ?>"><?php echo e($sede['telefono']); ?></a><br />
            <a href="mailto:<?php echo e($sede['email']); ?>"><?php echo e($sede['email']); ?></a>
        </address>
    </section>

    <section class="pannello-informativo orari-sede">
        <p class="indice-pannello">02 / Orari</p>
        <h2>Quando trovarci</h2>
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

    <section class="pannello-informativo ritiro-sede">
        <p class="indice-pannello">03 / Ordini</p>
        <h2>Ritiro senza coda</h2>
        <?php if ($sede['note_ritiro'] !== null && $sede['note_ritiro'] !== ''): ?>
            <p><?php echo e($sede['note_ritiro']); ?></p>
        <?php endif; ?>
        <p>Puoi anche farti consegnare l'ordine a casa.</p>
        <p><a class="collegamento-freccia" href="<?php echo e(url('carrello', ['sede' => $sede['slug']])); ?>"><span>Comincia l'ordine</span><span class="segno-collegamento" aria-hidden="true">&gt;</span></a></p>
    </section>

    <section class="pannello-informativo eventi-sede">
        <p class="indice-pannello">04 / Eventi</p>
        <h2>Una sala per la tua crew</h2>
        <?php if ((int) $sede['sala_eventi_disponibile'] === 1): ?>
            <p>
                Scegli giorno, orario di inizio e durata. La sala è adatta a compleanni,
                feste di laurea e cene di gruppo.
            </p>
            <p><a class="pulsante secondario" href="<?php echo e(url('prenota', ['sede' => $sede['slug']])); ?>">Prenota la sala</a></p>
        <?php else: ?>
            <p><span class="etichetta" data-tipo="attenzione">Non prenotabile</span></p>
            <p>In questo periodo la sala di <?php echo e($sede['citta']); ?> non accetta prenotazioni.</p>
            <p><a href="<?php echo e(url('sedi')); ?>">Guarda le altre sedi</a></p>
        <?php endif; ?>
    </section>
</div>

<p class="navigazione-pagina">
    <a class="pulsante secondario collegamento-indietro" href="<?php echo e(url('sedi')); ?>"><span class="segno-collegamento" aria-hidden="true">&lt;</span><span>Torna alle sedi</span></a>
    <a class="pulsante" href="<?php echo e(url('menu')); ?>">Guarda il menu</a>
</p>
