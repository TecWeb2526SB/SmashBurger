<?php
/**
 * Carrello. Riceve $carrello, $sedi, $prodotti, $righe, $totale e $articoli.
 *
 * Senza sede scelta la pagina mostra solo l'elenco delle sedi. Con la sede scelta mostra
 * la griglia dei prodotti e, in fondo, il riepilogo dentro un elemento details, che si
 * apre e si chiude senza JavaScript.
 */

if ($carrello === null) {
    require __DIR__ . '/carrello-sede.php';

    return;
}
?>
<h1>Il tuo ordine da <?php echo e($carrello['citta']); ?></h1>

<p>
    Stai ordinando dalla sede di <?php echo e($carrello['citta']); ?>. Qui sotto trovi solo
    quello che e' disponibile in questo locale.
</p>

<form method="post" action="<?php echo e(url('carrello')); ?>" class="scelta-sede">
    <?php echo campo_csrf(); ?>
    <input type="hidden" name="azione" value="scegli-sede" />
    <p>
        <label for="sede">Cambia sede</label>
        <select id="sede" name="sede">
            <?php foreach ($sedi as $sede): ?>
                <option value="<?php echo e($sede['slug']); ?>"
                    <?php echo (int) $sede['id'] === (int) $carrello['sede_id'] ? 'selected="selected"' : ''; ?>>
                    <?php echo e($sede['citta']); ?>
                </option>
            <?php endforeach; ?>
        </select>
        <button type="submit">Cambia sede</button>
    </p>
    <p><small>Cambiando sede il carrello viene svuotato, perche' la disponibilita' cambia da un locale all'altro.</small></p>
</form>

<?php if ($prodotti === []): ?>
    <p>In questo momento questa sede non ha prodotti disponibili.</p>
<?php else: ?>
    <form method="post" action="<?php echo e(url('carrello')); ?>" data-modulo="aggiungi">
        <?php echo campo_csrf(); ?>
        <input type="hidden" name="azione" value="aggiungi" />

        <ul class="griglia">
            <?php foreach ($prodotti as $prodotto): ?>
                <li>
                    <article class="scheda">
                        <img src="<?php echo e(risorsa('uploads/prodotti/' . $prodotto['immagine'])); ?>"
                            width="300" height="225" loading="lazy" alt="" />
                        <h2><?php echo e($prodotto['nome']); ?></h2>
                        <p class="prezzo"><?php echo e(prezzo((int) $prodotto['prezzo_centesimi'])); ?></p>
                        <p>
                            <button type="submit" name="prodotto_id" value="<?php echo (int) $prodotto['id']; ?>">
                                Aggiungi
                                <span class="solo-lettori"><?php echo e($prodotto['nome']); ?> al carrello</span>
                            </button>
                        </p>
                    </article>
                </li>
            <?php endforeach; ?>
        </ul>
    </form>
<?php endif; ?>

<section class="riepilogo">
    <h2 class="solo-lettori">Riepilogo del carrello</h2>

    <?php if ($righe === []): ?>
        <p>Il carrello e' vuoto: tocca un prodotto per aggiungerlo.</p>
    <?php else: ?>
        <details>
            <summary>
                <?php echo (int) $articoli; ?> articoli, <?php echo e(prezzo($totale)); ?>
            </summary>

            <?php require __DIR__ . '/carrello-righe.php'; ?>
        </details>

        <p class="navigazione-pagina">
            <span class="totale"><?php echo e(prezzo($totale)); ?></span>
            <a class="pulsante" data-tipo="positivo" href="<?php echo e(url('pagamento')); ?>">
                Procedi all'ordine
            </a>
        </p>
    <?php endif; ?>
</section>
