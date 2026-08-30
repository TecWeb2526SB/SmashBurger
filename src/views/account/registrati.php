<?php
/**
 * Modulo di registrazione. Riceve $valori e $errori dal controller.
 *
 * Le password non vengono mai ristampate nel modulo, nemmeno quando il resto dei dati
 * viene conservato dopo un errore.
 */
?>
<h1>Registrati</h1>

<p>Serve un account per ordinare e per prenotare la sala eventi.</p>

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

<form method="post" action="<?php echo e(url('registrati')); ?>">
    <?php echo campo_csrf(); ?>

    <fieldset>
        <legend>I tuoi dati</legend>

        <p>
            <label for="nome">Nome</label>
            <input type="text" id="nome" name="nome" required="required"
                minlength="2" maxlength="80" autocomplete="given-name"
                value="<?php echo e($valori['nome']); ?>"
                <?php if (isset($errori['nome'])): ?>aria-describedby="errore-nome" data-stato="errore"<?php endif; ?> />
            <?php if (isset($errori['nome'])): ?>
                <small id="errore-nome"><?php echo e($errori['nome']); ?></small>
            <?php endif; ?>
        </p>

        <p>
            <label for="cognome">Cognome</label>
            <input type="text" id="cognome" name="cognome" required="required"
                minlength="2" maxlength="80" autocomplete="family-name"
                value="<?php echo e($valori['cognome']); ?>"
                <?php if (isset($errori['cognome'])): ?>aria-describedby="errore-cognome" data-stato="errore"<?php endif; ?> />
            <?php if (isset($errori['cognome'])): ?>
                <small id="errore-cognome"><?php echo e($errori['cognome']); ?></small>
            <?php endif; ?>
        </p>

        <p>
            <label for="email">Email</label>
            <input type="email" id="email" name="email" required="required"
                maxlength="160" autocomplete="email"
                value="<?php echo e($valori['email']); ?>"
                <?php if (isset($errori['email'])): ?>aria-describedby="errore-email" data-stato="errore"<?php endif; ?> />
            <?php if (isset($errori['email'])): ?>
                <small id="errore-email"><?php echo e($errori['email']); ?></small>
            <?php endif; ?>
        </p>
    </fieldset>

    <fieldset>
        <legend>Come accedi</legend>

        <p>
            <label for="nome_utente">Nome utente</label>
            <input type="text" id="nome_utente" name="nome_utente" required="required"
                pattern="[a-z0-9._-]{3,50}" minlength="3" maxlength="50" autocomplete="username"
                aria-describedby="aiuto-nome-utente<?php echo isset($errori['nome_utente']) ? ' errore-nome_utente' : ''; ?>"
                value="<?php echo e($valori['nome_utente']); ?>"
                <?php if (isset($errori['nome_utente'])): ?>data-stato="errore"<?php endif; ?> />
            <small id="aiuto-nome-utente">Da 3 a 50 fra lettere minuscole, cifre, punto, trattino e trattino basso.</small>
            <?php if (isset($errori['nome_utente'])): ?>
                <small id="errore-nome_utente"><?php echo e($errori['nome_utente']); ?></small>
            <?php endif; ?>
        </p>

        <p>
            <label for="password">Password</label>
            <input type="password" id="password" name="password" required="required"
                minlength="8" autocomplete="new-password"
                aria-describedby="aiuto-password<?php echo isset($errori['password']) ? ' errore-password' : ''; ?>"
                <?php if (isset($errori['password'])): ?>data-stato="errore"<?php endif; ?> />
            <small id="aiuto-password">Almeno 8 caratteri.</small>
            <?php if (isset($errori['password'])): ?>
                <small id="errore-password"><?php echo e($errori['password']); ?></small>
            <?php endif; ?>
        </p>

        <p>
            <label for="conferma">Ripeti la password</label>
            <input type="password" id="conferma" name="conferma" required="required"
                minlength="8" autocomplete="new-password"
                <?php if (isset($errori['conferma'])): ?>aria-describedby="errore-conferma" data-stato="errore"<?php endif; ?> />
            <?php if (isset($errori['conferma'])): ?>
                <small id="errore-conferma"><?php echo e($errori['conferma']); ?></small>
            <?php endif; ?>
        </p>

        <p><button type="submit">Crea l'account</button></p>
    </fieldset>
</form>

<p>Hai gia' un account? <a href="<?php echo e(url('accedi')); ?>">Accedi</a>.</p>
