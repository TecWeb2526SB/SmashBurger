<?php
/**
 * Categorie del catalogo. Riceve $categorie e $daCancellare dal controller.
 *
 * I moduli di riga stanno fuori dalla tabella e i campi li raggiungono con l'attributo
 * form: un elemento form non puo' attraversare piu' celle, mentre questo collegamento e'
 * markup valido.
 *
 * I campi sono di testo, quindi il salvataggio resta esplicito: si scrive, poi si salva.
 */
?>
<h1>Categorie</h1>

<?php require __DIR__ . '/navigazione.php'; ?>

<?php if ($daCancellare !== null): ?>
    <section class="avviso" role="alert" data-tipo="errore">
        <h2>Vuoi cancellare <?php echo e($daCancellare['nome']); ?>?</h2>
        <p>La cancellazione riesce solo se nessun prodotto usa ancora questa categoria.</p>

        <form method="post" action="<?php echo e(url('controllo-categorie')); ?>"
            data-modulo="cancella-categoria">
            <?php echo campo_csrf(); ?>
            <p class="azioni">
                <button type="submit" name="elimina" value="<?php echo (int) $daCancellare['id']; ?>" data-tipo="negativo">
                    Cancella la categoria
                </button>
                <a class="pulsante secondario" href="<?php echo e(url('controllo-categorie')); ?>">Annulla</a>
            </p>
        </form>
    </section>
<?php endif; ?>

<section>
    <h2>Aggiungi una categoria</h2>

    <form method="post" action="<?php echo e(url('controllo-categorie')); ?>"
        data-modulo="nuova-categoria">
        <?php echo campo_csrf(); ?>

        <fieldset>
            <legend>Nuova categoria</legend>

            <p>
                <label for="nome">Nome</label>
                <input type="text" id="nome" name="nome" required="required" minlength="2" maxlength="80" />
            </p>

            <p>
                <label for="slug">Slug</label>
                <input type="text" id="slug" name="slug" required="required" pattern="[a-z0-9-]{2,80}"
                    aria-describedby="aiuto-slug" />
                <small id="aiuto-slug">Lettere minuscole, cifre e trattini.</small>
            </p>

            <p>
                <label for="descrizione">Descrizione</label>
                <input type="text" id="descrizione" name="descrizione" maxlength="255" />
            </p>

            <p>
                <label for="ordine">Posizione nel menu</label>
                <input type="number" id="ordine" name="ordine" min="0" max="255" value="0" />
            </p>

            <p><button type="submit">Crea la categoria</button></p>
        </fieldset>
    </form>
</section>

<section>
    <h2>Categorie esistenti</h2>

    <?php foreach ($categorie as $categoria): ?>
        <form method="post" action="<?php echo e(url('controllo-categorie')); ?>"
            id="categoria-<?php echo (int) $categoria['id']; ?>" data-modulo="categoria">
            <?php echo campo_csrf(); ?>
            <input type="hidden" name="categoria_id" value="<?php echo (int) $categoria['id']; ?>" />
            <input type="hidden" name="descrizione" value="<?php echo e($categoria['descrizione']); ?>" />
        </form>
    <?php endforeach; ?>

    <table>
        <caption>Categorie del catalogo</caption>
        <thead>
            <tr>
                <th scope="col">Nome</th>
                <th scope="col">Slug</th>
                <th scope="col">Posizione</th>
                <th scope="col">Azioni</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($categorie as $categoria): ?>
                <?php $modulo = 'categoria-' . (int) $categoria['id']; ?>
                <tr>
                    <td>
                        <label class="solo-lettori" for="nome-<?php echo (int) $categoria['id']; ?>">
                            Nome di <?php echo e($categoria['nome']); ?>
                        </label>
                        <input type="text" id="nome-<?php echo (int) $categoria['id']; ?>" name="nome"
                            form="<?php echo e($modulo); ?>" required="required" minlength="2" maxlength="80"
                            value="<?php echo e($categoria['nome']); ?>" />
                    </td>
                    <td>
                        <label class="solo-lettori" for="slug-<?php echo (int) $categoria['id']; ?>">
                            Slug di <?php echo e($categoria['nome']); ?>
                        </label>
                        <input type="text" id="slug-<?php echo (int) $categoria['id']; ?>" name="slug"
                            form="<?php echo e($modulo); ?>" required="required" pattern="[a-z0-9-]{2,80}"
                            value="<?php echo e($categoria['slug']); ?>" />
                    </td>
                    <td>
                        <label class="solo-lettori" for="ordine-<?php echo (int) $categoria['id']; ?>">
                            Posizione di <?php echo e($categoria['nome']); ?>
                        </label>
                        <input type="number" id="ordine-<?php echo (int) $categoria['id']; ?>" name="ordine"
                            form="<?php echo e($modulo); ?>" min="0" max="255"
                            value="<?php echo (int) $categoria['ordine']; ?>" />
                    </td>
                    <td>
                        <button type="submit" form="<?php echo e($modulo); ?>">
                            Salva
                            <span class="solo-lettori"><?php echo e($categoria['nome']); ?></span>
                        </button>
                        <a class="pulsante" data-tipo="negativo" href="<?php echo e(url('controllo-categorie', ['elimina' => $categoria['id']])); ?>">
                            Cancella
                            <span class="solo-lettori"><?php echo e($categoria['nome']); ?></span>
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</section>
