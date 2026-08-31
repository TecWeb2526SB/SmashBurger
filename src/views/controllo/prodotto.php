<?php
/**
 * Scheda di un prodotto. Riceve $prodotto, $valori, $errori, $categorie e
 * $confermaCancellazione dal controller.
 *
 * I campi seguono l'ordine della pagina pubblica: immagine, nome, categoria, prezzo,
 * descrizione, allergeni.
 */

$nuovo = $prodotto === null;
?>
<h1><?php echo $nuovo ? 'Nuovo prodotto' : 'Modifica ' . e($prodotto['nome']); ?></h1>

<?php require __DIR__ . '/navigazione.php'; ?>

<?php if ($confermaCancellazione && !$nuovo): ?>
    <section class="avviso" role="alert" data-tipo="errore">
        <h2>Vuoi cancellare <?php echo e($prodotto['nome']); ?>?</h2>
        <p>
            Sparisce dal menu di tutte le sedi. Gli ordini gia' fatti restano leggibili,
            perche' hanno copiato nome e prezzo al momento dell acquisto.
        </p>

        <form method="post" action="<?php echo e(url('controllo-prodotto')); ?>">
            <?php echo campo_csrf(); ?>
            <input type="hidden" name="prodotto_id" value="<?php echo (int) $prodotto['id']; ?>" />
            <p class="azioni">
                <button type="submit" name="elimina" value="1" data-tipo="negativo">Cancella il prodotto</button>
                <a class="pulsante secondario" href="<?php echo e(url('controllo-prodotto', ['prodotto' => $prodotto['id']])); ?>">Annulla</a>
            </p>
        </form>
    </section>
<?php endif; ?>

<?php if ($errori !== []): ?>
    <section class="avviso" role="alert" data-tipo="errore">
        <h2>Controlla questi campi</h2>
        <ul>
            <?php foreach ($errori as $campo => $testo): ?>
                <li><a href="#<?php echo e($campo); ?>"><?php echo e($testo); ?></a></li>
            <?php endforeach; ?>
        </ul>
    </section>
<?php endif; ?>

<form method="post" enctype="multipart/form-data" action="<?php echo e(url('controllo-prodotto')); ?>">
    <?php echo campo_csrf(); ?>
    <input type="hidden" name="MAX_FILE_SIZE" value="<?php echo PESO_MASSIMO_IMMAGINE; ?>" />
    <?php if (!$nuovo): ?>
        <input type="hidden" name="prodotto_id" value="<?php echo (int) $prodotto['id']; ?>" />
    <?php endif; ?>

    <fieldset>
        <legend>Dati del prodotto</legend>

        <?php if (!$nuovo && $valori['immagine'] !== ''): ?>
            <figure class="anteprima-prodotto">
                <img src="<?php echo e(risorsa('uploads/prodotti/' . basename($valori['immagine']))); ?>"
                    width="600" height="450"
                    alt="Immagine attuale di <?php echo e($valori['nome']); ?>" />
                <figcaption>Immagine attuale</figcaption>
            </figure>
        <?php endif; ?>

        <p>
            <label for="immagine"><?php echo $nuovo ? 'Immagine' : 'Sostituisci immagine'; ?></label>
            <input type="file" id="immagine" name="immagine"
                accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp"
                aria-describedby="aiuto-immagine<?php echo isset($errori['immagine']) ? ' errore-immagine' : ''; ?>"
                <?php if ($nuovo): ?>required="required"<?php endif; ?>
                <?php if (isset($errori['immagine'])): ?>data-stato="errore"<?php endif; ?> />
            <small id="aiuto-immagine">
                JPG, PNG o WebP, da 300 x 300 a 2000 x 2000 pixel, massimo 300 KB.
                <?php if (!$nuovo): ?>Se non scegli un file, resta l'immagine attuale.<?php endif; ?>
            </small>
            <?php if (isset($errori['immagine'])): ?>
                <small id="errore-immagine"><?php echo e($errori['immagine']); ?></small>
            <?php endif; ?>
        </p>

        <p>
            <label for="nome">Nome</label>
            <input type="text" id="nome" name="nome" required="required" minlength="2" maxlength="120"
                value="<?php echo e($valori['nome']); ?>"
                <?php if (isset($errori['nome'])): ?>aria-describedby="errore-nome" data-stato="errore"<?php endif; ?> />
            <?php if (isset($errori['nome'])): ?>
                <small id="errore-nome"><?php echo e($errori['nome']); ?></small>
            <?php endif; ?>
        </p>

        <p>
            <label for="slug">Slug</label>
            <input type="text" id="slug" name="slug" required="required" pattern="[a-z0-9-]{2,120}"
                aria-describedby="aiuto-slug<?php echo isset($errori['slug']) ? ' errore-slug' : ''; ?>"
                value="<?php echo e($valori['slug']); ?>"
                <?php if (isset($errori['slug'])): ?>data-stato="errore"<?php endif; ?> />
            <small id="aiuto-slug">Compare nell indirizzo della pagina pubblica del prodotto.</small>
            <?php if (isset($errori['slug'])): ?>
                <small id="errore-slug"><?php echo e($errori['slug']); ?></small>
            <?php endif; ?>
        </p>

        <p>
            <label for="categoria_id">Categoria</label>
            <select id="categoria_id" name="categoria_id" required="required"
                <?php if (isset($errori['categoria_id'])): ?>aria-describedby="errore-categoria" data-stato="errore"<?php endif; ?>>
                <option value="">Scegli la categoria</option>
                <?php foreach ($categorie as $categoria): ?>
                    <option value="<?php echo (int) $categoria['id']; ?>"
                        <?php echo (string) $categoria['id'] === $valori['categoria_id'] ? 'selected="selected"' : ''; ?>>
                        <?php echo e($categoria['nome']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <?php if (isset($errori['categoria_id'])): ?>
                <small id="errore-categoria"><?php echo e($errori['categoria_id']); ?></small>
            <?php endif; ?>
        </p>

        <p>
            <label for="prezzo">Prezzo in euro</label>
            <input type="number" id="prezzo" name="prezzo" required="required"
                min="0.01" max="999" step="0.01"
                value="<?php echo e($valori['prezzo']); ?>"
                <?php if (isset($errori['prezzo'])): ?>aria-describedby="errore-prezzo" data-stato="errore"<?php endif; ?> />
            <?php if (isset($errori['prezzo'])): ?>
                <small id="errore-prezzo"><?php echo e($errori['prezzo']); ?></small>
            <?php endif; ?>
        </p>

        <p>
            <label for="descrizione">Descrizione</label>
            <textarea id="descrizione" name="descrizione" rows="4" required="required" minlength="10"
                <?php if (isset($errori['descrizione'])): ?>aria-describedby="errore-descrizione" data-stato="errore"<?php endif; ?>><?php echo e($valori['descrizione']); ?></textarea>
            <?php if (isset($errori['descrizione'])): ?>
                <small id="errore-descrizione"><?php echo e($errori['descrizione']); ?></small>
            <?php endif; ?>
        </p>

        <p>
            <label for="allergeni">Allergeni</label>
            <input type="text" id="allergeni" name="allergeni" maxlength="255"
                aria-describedby="aiuto-allergeni"
                value="<?php echo e($valori['allergeni']); ?>" />
            <small id="aiuto-allergeni">Separati da virgola. Lascia vuoto se non ne contiene.</small>
        </p>

        <p><button type="submit"><?php echo $nuovo ? 'Crea il prodotto' : 'Salva le modifiche'; ?></button></p>
    </fieldset>
</form>

<p class="navigazione-pagina">
    <a class="collegamento-indietro" href="<?php echo e(url('controllo-prodotti')); ?>"><span class="segno-collegamento" aria-hidden="true">&lt;</span><span>Torna ai prodotti</span></a>
    <?php if (!$nuovo): ?>
        <a href="<?php echo e(url('prodotto', ['slug' => $prodotto['slug']])); ?>">Vedi la pagina pubblica</a>
        <a class="pulsante" data-tipo="negativo" href="<?php echo e(url('controllo-prodotto', ['prodotto' => $prodotto['id'], 'elimina' => 1])); ?>">Cancella</a>
    <?php endif; ?>
</p>
