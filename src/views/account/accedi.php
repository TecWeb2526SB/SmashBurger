<?php
/**
 * Modulo di accesso. Riceve $nomeUtente e $errore dal controller.
 *
 * Il messaggio di errore e' sempre lo stesso, sia che il nome utente non esista sia che
 * la password sia sbagliata: non rivela quali account esistono.
 */
?>
<h1>Accedi</h1>

<?php if ($errore !== ''): ?>
    <p class="avviso" role="alert" data-tipo="errore">
        <strong>Errore:</strong> <?php echo e($errore); ?>
    </p>
<?php endif; ?>

<form method="post" action="<?php echo e(url('accedi')); ?>">
    <?php echo campo_csrf(); ?>

    <fieldset>
        <legend>Le tue credenziali</legend>

        <p>
            <label for="nome_utente">Nome utente</label>
            <input type="text" id="nome_utente" name="nome_utente" required="required"
                maxlength="50" autocomplete="username"
                value="<?php echo e($nomeUtente); ?>" />
        </p>

        <p>
            <label for="password">Password</label>
            <input type="password" id="password" name="password" required="required"
                autocomplete="current-password" />
        </p>

        <p><button type="submit">Accedi</button></p>
    </fieldset>
</form>

<p>Non hai un account? <a href="<?php echo e(url('registrati')); ?>">Registrati</a>.</p>
