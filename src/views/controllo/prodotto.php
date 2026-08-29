<?php
/**
 * Modulo di inserimento e modifica di un prodotto.
 *
 * Riceve $prodotto, che vale null in inserimento, insieme a $categorie, $valori ed
 * $errori.
 */
?>
<h1><?php echo $prodotto === null ? 'Nuovo prodotto' : 'Modifica ' . e($prodotto['nome']); ?></h1>

<?php include __DIR__ . '/navigazione.php'; ?>

<?php if ($errori !== []): ?>
    <div role="alert">
        <h2>Controlla i dati inseriti</h2>
        <ul>
            <?php foreach ($errori as $campo => $testo): ?>
                <li><a href="#<?php echo e($campo); ?>"><?php echo e($testo); ?></a></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<form method="post" enctype="multipart/form-data"
    action="<?php echo e($prodotto === null ? url('controllo-prodotto') : url('controllo-prodotto', ['id' => $prodotto['id']])); ?>">
    <?php echo campo_csrf(); ?>

    <fieldset>
        <legend>Dati del prodotto</legend>

        <p>
            <label for="nome">Nome</label>
            <input type="text" id="nome" name="nome" required="required" maxlength="120"
                value="<?php echo e($valori['nome']); ?>" />
        </p>

        <p>
            <label for="categoria_id">Categoria</label>
            <select id="categoria_id" name="categoria_id" required="required">
                <option value="">Scegli una categoria</option>
                <?php foreach ($categorie as $categoria): ?>
                    <option value="<?php echo (int) $categoria['id']; ?>"
                        <?php echo (int) $categoria['id'] === (int) $valori['categoria_id'] ? 'selected="selected"' : ''; ?>>
                        <?php echo e($categoria['nome']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </p>

        <p>
            <label for="descrizione">Descrizione</label>
            <input type="text" id="descrizione" name="descrizione" required="required"
                maxlength="255" value="<?php echo e($valori['descrizione']); ?>" />
        </p>

        <p>
            <label for="allergeni">Allergeni</label>
            <input type="text" id="allergeni" name="allergeni" maxlength="160"
                value="<?php echo e($valori['allergeni']); ?>" />
            <small id="aiuto-allergeni">Elenco separato da virgole, lascia vuoto se non ce ne sono.</small>
        </p>

        <p>
            <label for="prezzo">Prezzo in euro</label>
            <input type="text" id="prezzo" name="prezzo" required="required"
                inputmode="decimal" value="<?php echo e($valori['prezzo']); ?>" />
            <small id="aiuto-prezzo">Ad esempio 10,90.</small>
        </p>

        <p>
            <input type="checkbox" id="disponibile" name="disponibile" value="1"
                <?php echo (int) $valori['disponibile'] === 1 ? 'checked="checked"' : ''; ?> />
            <label for="disponibile">Disponibile per gli ordini</label>
        </p>
    </fieldset>

    <fieldset>
        <legend>Immagine</legend>

        <?php if (!empty($prodotto['immagine'])): ?>
            <p><img src="<?php echo e(immagine_url($prodotto['immagine'])); ?>" alt="Immagine attuale di <?php echo e($prodotto['nome']); ?>" /></p>
        <?php endif; ?>

        <p>
            <label for="immagine">Nuova immagine</label>
            <input type="file" id="immagine" name="immagine" accept="image/webp,image/jpeg,image/png" />
            <small id="aiuto-immagine">Formati webp, jpg o png, al massimo 300 KB.</small>
        </p>
    </fieldset>

    <p class="navigazione-pagina">
        <a class="pulsante" data-tipo="indietro" href="<?php echo e(url('controllo-prodotti')); ?>">
            <?php echo icona('freccia-sinistra'); ?> Torna ai prodotti
        </a>
        <button type="submit" data-tipo="positivo">
            <?php echo $prodotto === null ? 'Crea il prodotto' : 'Salva le modifiche'; ?>
        </button>
    </p>
</form>
