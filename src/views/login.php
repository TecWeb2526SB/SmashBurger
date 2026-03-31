<?php
/**
 * login.php: View della pagina di accesso.
 *
 * Variabili attese dal controller:
 *   $errori          array   Errori di validazione
 *   $valoreUsername  string  Valore username da ripopolare dopo errore
 *   $flash           ?array  Messaggio flash opzionale
 *   $csrfToken   string  Token CSRF della sessione
 */
?>

<section aria-labelledby="titolo-login" class="auth-sezione">
    <div class="contenitore">
        <div class="auth-box">
            <h1 id="titolo-login">Accedi al tuo account</h1>

            <?php if (!empty($flash)): ?>
                <div class="alert <?php echo htmlspecialchars($flash['type'] ?? 'info', ENT_QUOTES, 'UTF-8'); ?>">
                    <?php echo htmlspecialchars($flash['message'] ?? '', ENT_QUOTES, 'UTF-8'); ?>
                </div>
            <?php endif; ?>

            <?php if (!empty($errori)): ?>
                <div role="alert" class="errore-sommario">
                    <p><?php echo htmlspecialchars($errori['generale'] ?? 'Correggi gli errori nel modulo prima di procedere.', ENT_QUOTES, 'UTF-8'); ?></p>
                </div>
            <?php endif; ?>

            <form method="POST" action="login.php" data-valida novalidate>
                <input type="hidden" name="csrf_token"
                    value="<?php echo htmlspecialchars($csrfToken ?? '', ENT_QUOTES, 'UTF-8'); ?>">

                <div class="campo-gruppo">
                    <label for="username">Username</label>
                    <input
                        type="text"
                        id="username"
                        name="username"
                        value="<?php echo $valoreUsername ?? ''; ?>"
                        required
                        minlength="3"
                        maxlength="50"
                        autocomplete="username"
                        aria-describedby="username-errore"
                        <?php echo isset($errori['username']) ? 'aria-invalid="true"' : ''; ?>>
                    <span id="username-errore" class="campo-errore" <?php echo empty($errori['username']) ? 'hidden' : ''; ?>>
                        <?php echo htmlspecialchars($errori['username'] ?? '', ENT_QUOTES, 'UTF-8'); ?>
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
                            <span aria-hidden="true">Vedi</span>
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
