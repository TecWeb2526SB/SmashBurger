<?php
/**
 * Elenco del catalogo con il filtro per categoria.
 *
 * Riceve $categorie, $categoriaAttiva e $prodotti dal controller.
 */
?>
<h1>Menu</h1>

<p>
    Il menu è lo stesso in tutte le sedi. I prezzi sono comprensivi di IVA e sotto ogni
    prodotto trovi gli allergeni.
</p>

<?php if (!utente_autenticato()): ?>
    <p>
        Per ordinare serve un account:
        <a href="<?php echo e(url('accedi')); ?>">accedi</a> oppure
        <a href="<?php echo e(url('registrati')); ?>">registrati</a>.
    </p>
<?php endif; ?>

<nav class="filtri" aria-label="Categorie del menu">
    <ul>
        <li>
            <?php if ($categoriaAttiva === null): ?>
                <span aria-current="true">Tutto il menu</span>
            <?php else: ?>
                <a href="<?php echo e(url('menu')); ?>">Tutto il menu</a>
            <?php endif; ?>
        </li>
        <?php foreach ($categorie as $categoria): ?>
            <li>
                <?php if (($categoriaAttiva['slug'] ?? null) === $categoria['slug']): ?>
                    <span aria-current="true"><?php echo e($categoria['nome']); ?></span>
                <?php else: ?>
                    <a href="<?php echo e(url('menu', ['categoria' => $categoria['slug']])); ?>">
                        <?php echo e($categoria['nome']); ?>
                    </a>
                <?php endif; ?>
            </li>
        <?php endforeach; ?>
    </ul>
</nav>

<section>
    <h2><?php echo e($categoriaAttiva['nome'] ?? 'Tutto il menu'); ?></h2>

    <?php if ($prodotti === []): ?>
        <p>Non ci sono prodotti in questa categoria.</p>
    <?php else: ?>
        <ul class="griglia">
            <?php foreach ($prodotti as $indice => $prodotto): ?>
                <li>
                    <article class="scheda">
                        <p class="immagine">
                            <?php if (!empty($prodotto['immagine'])): ?>
                                <img src="<?php echo e(immagine_url($prodotto['immagine'])); ?>"
                                    alt="<?php echo e($prodotto['nome']); ?>"
                                    <?php echo $indice === 0 ? 'fetchpriority="high"' : 'loading="lazy"'; ?> />
                            <?php endif; ?>
                        </p>

                        <h3><?php echo e($prodotto['nome']); ?></h3>

                        <p class="descrizione"><?php echo e($prodotto['descrizione']); ?></p>

                        <p class="allergeni">
                            <?php if (!empty($prodotto['allergeni'])): ?>
                                Allergeni: <?php echo e($prodotto['allergeni']); ?>
                            <?php else: ?>
                                Nessun allergene dichiarato
                            <?php endif; ?>
                        </p>

                        <p class="prezzo"><?php echo e(prezzo((int) $prodotto['prezzo_centesimi'])); ?></p>

                        <?php if ((int) $prodotto['disponibile'] !== 1): ?>
                            <p><span class="etichetta" data-tipo="negativo">Non disponibile</span></p>
                        <?php elseif (utente_e_amministratore()): ?>
                            <p><a href="<?php echo e(url('controllo-prodotti')); ?>">Gestisci nel pannello</a></p>
                        <?php elseif (!utente_autenticato()): ?>
                            <p><a href="<?php echo e(url('accedi')); ?>">Accedi per ordinare</a></p>
                        <?php else: ?>
                            <form method="post" action="<?php echo e(url('carrello')); ?>">
                                <?php echo campo_csrf(); ?>
                                <input type="hidden" name="azione" value="aggiungi" />
                                <input type="hidden" name="ritorno" value="menu" />
                                <input type="hidden" name="prodotto_id" value="<?php echo (int) $prodotto['id']; ?>" />
                                <p>
                                    <label class="solo-lettori" for="quantita-<?php echo (int) $prodotto['id']; ?>">
                                        Quantità di <?php echo e($prodotto['nome']); ?>
                                    </label>
                                    <input type="number" id="quantita-<?php echo (int) $prodotto['id']; ?>"
                                        name="quantita" value="1" min="1"
                                        max="<?php echo QUANTITA_MASSIMA; ?>" required="required" />
                                </p>
                                <p><button type="submit">Aggiungi al carrello</button></p>
                            </form>
                        <?php endif; ?>
                    </article>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
</section>
