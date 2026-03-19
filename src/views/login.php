<?php
/**
 * login.php: View della pagina di accesso.
 *
 * Variabili attese dal controller:
 *   $errori      array   Errori di validazione (chiavi: 'email', 'password')
 *   $valoreEmail string  Valore dell'email da ripopolare dopo errore
 */
?>

<section aria-labelledby="titolo-login" class="auth-sezione">
    <div class="contenitore">
        <div class="auth-box">
            <h2 id="titolo-login">Accedi al tuo account</h2>

            <?php if (!empty($errori)): ?>
                <div role="alert" class="errore-sommario">
                    <p>Correggi gli errori nel modulo prima di procedere.</p>
                </div>
            <?php endif; ?>

            <form method="POST" action="login.php" data-valida novalidate>

                <div class="campo-gruppo">
                    <label for="email">E-mail</label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        value="<?php echo $valoreEmail ?? ''; ?>"
                        required
                        autocomplete="email"
                        aria-describedby="email-errore"
                        <?php echo isset($errori['email']) ? 'aria-invalid="true"' : ''; ?>>
                    <span id="email-errore" class="campo-errore" <?php echo empty($errori['email']) ? 'hidden' : ''; ?>>
                        <?php echo htmlspecialchars($errori['email'] ?? '', ENT_QUOTES, 'UTF-8'); ?>
                    </span>
                </div>

                <div class="campo-gruppo">
                    <label for="password">Password</label>
                    <div class="campo-password-wrapper">
                        <input
                            type="password"
                            id="password"
                            name="password"
                            required
                            autocomplete="current-password"
                            aria-describedby="password-errore"
                            <?php echo isset($errori['password']) ? 'aria-invalid="true"' : ''; ?>>
                        <button type="button" class="mostra-password" aria-pressed="false"
                            aria-label="Mostra password">
                            <span aria-hidden="true">👁</span>
                        </button>
                    </div>
                    <span id="password-errore" class="campo-errore" <?php echo empty($errori['password']) ? 'hidden' : ''; ?>>
                        <?php echo htmlspecialchars($errori['password'] ?? '', ENT_QUOTES, 'UTF-8'); ?>
                    </span>
                </div>

                <button type="submit" class="bottone-primario">Accedi</button>

                <p class="auth-link">Non hai un account? <a href="registrazione.php">Registrati</a></p>
            </form>
        </div>
    </div>
</section>
