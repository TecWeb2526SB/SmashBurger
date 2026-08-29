<?php
/**
 * Modulo di registrazione. Riceve $valori ed $errori dal controller.
 */
?>
<h1>Registrati</h1>

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

<form method="post" action="<?php echo e(url('registrati')); ?>">
    <?php echo campo_csrf(); ?>

    <fieldset>
        <legend>Dati dell'account</legend>

        <p>
            <label for="nome_utente">Nome utente</label>
            <input type="text" id="nome_utente" name="nome_utente" required="required"
                minlength="3" maxlength="32" pattern="[A-Za-z0-9._-]+"
                title="Sono ammesse lettere, cifre, punto, trattino e trattino basso."
                autocomplete="username"
                value="<?php echo e($valori['nome_utente']); ?>"
                <?php echo isset($errori['nome_utente']) ? 'data-stato="errore" aria-invalid="true" aria-describedby="errore-nome_utente"' : ''; ?> />
            <?php if (isset($errori['nome_utente'])): ?>
                <small id="errore-nome_utente" data-tipo="errore">
                    <b>Errore:</b> <?php echo e($errori['nome_utente']); ?>
                </small>
            <?php endif; ?>
        </p>

        <p>
            <label for="email">Email</label>
            <input type="email" id="email" name="email" required="required" maxlength="160"
                autocomplete="email" value="<?php echo e($valori['email']); ?>"
                <?php echo isset($errori['email']) ? 'data-stato="errore" aria-invalid="true" aria-describedby="errore-email"' : ''; ?> />
            <?php if (isset($errori['email'])): ?>
                <small id="errore-email" data-tipo="errore">
                    <b>Errore:</b> <?php echo e($errori['email']); ?>
                </small>
            <?php endif; ?>
        </p>

        <p>
            <label for="password">Password</label>
            <input type="password" id="password" name="password" required="required"
                minlength="<?php echo PASSWORD_MINIMO; ?>" maxlength="<?php echo PASSWORD_MASSIMO; ?>"
                pattern="[A-Za-z0-9!?@#$%&amp;*+_.-]+" title="<?php echo e(PASSWORD_CONDIZIONI); ?>"
                autocomplete="new-password" aria-describedby="condizioni-password"
                <?php echo isset($errori['password']) ? 'data-stato="errore" aria-invalid="true"' : ''; ?> />
            <small id="condizioni-password"><?php echo e(PASSWORD_CONDIZIONI); ?></small>
            <?php if (isset($errori['password'])): ?>
                <small id="errore-password" data-tipo="errore">
                    <b>Errore:</b> <?php echo e($errori['password']); ?>
                </small>
            <?php endif; ?>
        </p>
    </fieldset>

    <p><button type="submit">Crea l'account</button></p>
</form>

<p>Hai già un account? <a href="<?php echo e(url('accedi')); ?>">Accedi</a>.</p>
