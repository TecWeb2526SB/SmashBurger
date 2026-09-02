<?php
/**
 * Scheda pubblica di un prodotto. Riceve $prodotto e $sediDisponibili dal controller.
 *
 * Le sedi sono un'informazione, non un comando di acquisto: si ordina dal carrello,
 * dopo avere scelto la sede.
 *
 * L'elenco contiene le sedi che tengono il prodotto in carta, comprese quelle che lo
 * hanno finito: quelle portano l'etichetta di esaurito. Cosi' la pagina dice la stessa
 * cosa del menu, dove il prodotto compare comunque.
 */
?>
<section class="dettaglio-prodotto">
    <div class="immagine-dettaglio-prodotto">
        <p class="numero-edizione">Smash / <?php echo e($prodotto['categoria_nome']); ?></p>
        <img src="<?php echo e(risorsa('uploads/prodotti/' . $prodotto['immagine'])); ?>"
            width="600" height="450"
            alt="<?php echo e($prodotto['nome']); ?>" />
    </div>

    <div class="testo-dettaglio-prodotto">
        <p class="occhiello"><?php echo e($prodotto['categoria_nome']); ?> · Preparato al momento</p>
        <h1><?php echo e($prodotto['nome']); ?></h1>
        <p class="prezzo prezzo-grande"><?php echo e(prezzo((int) $prodotto['prezzo_centesimi'])); ?></p>
        <p class="introduzione"><?php echo e($prodotto['descrizione']); ?></p>
        <p class="azioni">
            <a class="pulsante" data-tipo="positivo" href="<?php echo e(url('carrello')); ?>">Scegli la sede e ordina</a>
            <a class="pulsante secondario" href="<?php echo e(url('menu', ['categoria' => $prodotto['categoria_slug']])); ?>">
                Altri <?php echo e($prodotto['categoria_nome']); ?>
            </a>
        </p>
    </div>
</section>

<div class="griglia-dettaglio">
    <section class="pannello-informativo pannello-allergeni">
        <p class="indice-pannello">01</p>
        <h2>Allergeni</h2>
        <?php if ($prodotto['allergeni'] !== ''): ?>
            <p>Contiene: <strong><?php echo e($prodotto['allergeni']); ?></strong>.</p>
        <?php else: ?>
            <p>Non contiene nessuno degli allergeni che dichiariamo.</p>
        <?php endif; ?>
        <p>
            Hai un'intolleranza? <a href="<?php echo e(url('contatti')); ?>">Scrivici prima di ordinare</a>.
        </p>
    </section>

    <section class="pannello-informativo pannello-disponibilita">
        <p class="indice-pannello">02</p>
    <h2>Dove lo trovi adesso</h2>

    <?php if ($sediDisponibili === []): ?>
        <p>
            Nessuna sede lo tiene in carta in questo periodo. Torna a trovarci, oppure
            guarda il resto del <a href="<?php echo e(url('menu')); ?>">menu</a>.
        </p>
    <?php else: ?>
        <ul class="elenco-sedi-disponibili">
            <?php foreach ($sediDisponibili as $sede): ?>
                <li>
                    <a href="<?php echo e(url('sede', ['slug' => $sede['slug']])); ?>">
                        <?php echo e($sede['citta']); ?>
                    </a>,
                    <?php echo e($sede['indirizzo']); ?>
                    <?php if ((int) $sede['quantita'] <= 0): ?>
                        <span class="etichetta" data-tipo="attenzione">esaurito</span>
                    <?php endif; ?>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
</section>
</div>

<p class="navigazione-pagina">
    <a class="pulsante secondario" href="<?php echo e(url('menu', ['categoria' => $prodotto['categoria_slug']])); ?>">
        Torna a <?php echo e($prodotto['categoria_nome']); ?>
    </a>
    <a class="pulsante" data-tipo="positivo" href="<?php echo e(url('carrello')); ?>">Scegli la sede e ordina</a>
</p>
