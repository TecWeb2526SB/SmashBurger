<?php
/**
 * Modulo di accesso. Riceve $nome e $errore dal controller.
 */
?>
<h1>Accedi</h1>

<?php if ($errore !== null): ?>
    <p class="avviso" role="alert"><?php echo e($errore); ?></p>
<?php endif; ?>

<form method="post" action="<?php echo e(url('accedi')); ?>">
    <?php echo campo_csrf(); ?>

    <fieldset>
        <legend>Dati di accesso</legend>

        <p>
            <label for="nome_utente">Nome utente</label>
            <input type="text" id="nome_utente" name="nome_utente" required="required"
                autocomplete="username" value="<?php echo e($nome); ?>" />
        </p>

        <p>
            <label for="password">Password</label>
            <input type="password" id="password" name="password" required="required"
                autocomplete="current-password" />
        </p>
    </fieldset>

    <p><button type="submit">Accedi</button></p>
</form>

<p>Non hai un account? <a href="<?php echo e(url('registrati')); ?>">Registrati</a>.</p>
