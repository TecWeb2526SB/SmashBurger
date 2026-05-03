<?php
/**
 * accedi: View della pagina di accesso.
 *
 * Variabili attese dal controller:
 *   $errori          array   Errori di validazione
 *   $valoreIdentificativo string Valore username/email da ripopolare dopo errore
 *   $flash           ?array  Messaggio flash opzionale
 *   $csrfToken   string  Token CSRF della sessione
 */
?>

<section aria-labelledby="titolo-login" class="auth-sezione">
    <div class="contenitore">
        <div class="auth-box">
            <h1 id="titolo-login">Accedi al tuo account</h1>

            <?php echo ui_alert($flash); ?>
            <?php echo ui_error_summary($errori); ?>

            <form method="POST" action="accedi" data-valida novalidate>
                <input type="hidden" name="csrf_token" value="<?php echo e($csrfToken); ?>">
                <input type="hidden" name="redirect" value="<?php echo e($redirectTo); ?>">

                <?php
                echo ui_form_group('identifier', 'Username o email', 'text', [
                    'value' => $valoreIdentificativo,
                    'error' => $errori['identifier'] ?? null,
                    'autocomplete' => 'username',
                    'extra_attrs' => 'minlength="3" maxlength="160"'
                ]);

                echo ui_form_group('password', 'Password', 'password', [
                    'error' => $errori['password'] ?? null,
                    'autocomplete' => 'current-password'
                ]);
                ?>

                <button type="submit" class="bottone-primario">Accedi</button>

                <p class="auth-link">Non hai un account? <a href="registrati">Registrati</a></p>
            </form>
        </div>
    </div>
</section>
