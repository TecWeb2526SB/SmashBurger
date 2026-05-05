<?php
/**
 * registrati: View della pagina di registrazione.
 *
 * Variabili attese dal controller:
 *   $errori  array   Errori di validazione (chiavi: 'username', 'email', 'password', 'conferma')
 *   $valori  array   Valori da ripopolare dopo errore
 *   $csrfToken string Token CSRF della sessione
 */
?>

<section aria-labelledby="titolo-registrazione" class="auth-sezione">
    <div class="contenitore">
        <div class="auth-box">
            <h1 id="titolo-registrazione">Crea un account</h1>

            <?php echo ui_error_summary($errori); ?>

            <form method="POST" action="registrati" data-valida="true" novalidate="novalidate">
                <input type="hidden" name="csrf_token" value="<?php echo e($csrfToken); ?>" />

                <?php
                echo ui_form_group('username', 'Username', 'text', [
                    'value' => $valori['username'] ?? '',
                    'error' => $errori['username'] ?? null,
                    'autocomplete' => 'username',
                    'extra_attrs' => 'minlength="3" maxlength="50"'
                ]);

                echo ui_form_group('email', 'Email', 'email', [
                    'value' => $valori['email'] ?? '',
                    'error' => $errori['email'] ?? null,
                    'autocomplete' => 'email',
                    'extra_attrs' => 'maxlength="160"'
                ]);

                echo ui_form_group('password', 'Password', 'password', [
                    'error' => $errori['password'] ?? null,
                    'autocomplete' => 'new-password',
                    'extra_attrs' => 'minlength="8"',
                    'described_by' => 'password-suggerimento'
                ]);
                ?>
                <p id="password-suggerimento" class="campo-aiuto">Caratteri ammessi: lettere, numeri, ! @ # $ % &amp; (minimo 8)</p>

                <?php
                echo ui_form_group('conferma', 'Conferma password', 'password', [
                    'error' => $errori['conferma'] ?? null,
                    'autocomplete' => 'new-password'
                ]);
                ?>

                <button type="submit" class="bottone-primario">Crea account</button>

                <p class="auth-link">Hai già un account? <a href="accedi">Accedi</a></p>
            </form>
        </div>
    </div>
</section>
