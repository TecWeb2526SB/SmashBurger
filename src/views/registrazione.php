<?php
/**
 * registrazione.php: View della pagina di registrazione.
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

            <?php if (!empty($errori)): ?>
                <div role="alert" class="errore-sommario">
                    <p><?php echo htmlspecialchars($errori['generale'] ?? 'Correggi gli errori nel modulo prima di procedere.', ENT_QUOTES, 'UTF-8'); ?></p>
                </div>
            <?php endif; ?>

            <form method="POST" action="registrazione.php" data-valida novalidate>
                <input type="hidden" name="csrf_token"
                    value="<?php echo htmlspecialchars($csrfToken ?? '', ENT_QUOTES, 'UTF-8'); ?>">

                <div class="campo-gruppo">
                    <label for="username">Username</label>
                    <input
                        type="text"
                        id="username"
                        name="username"
                        value="<?php echo $valori['username'] ?? ''; ?>"
                        required
                        aria-required="true"
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
                    <label for="email">Email</label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        value="<?php echo $valori['email'] ?? ''; ?>"
                        required
                        aria-required="true"
                        maxlength="160"
                        autocomplete="email"
                        aria-describedby="email-errore"
                        <?php echo isset($errori['email']) ? 'aria-invalid="true"' : ''; ?>>
                    <span id="email-errore" class="campo-errore" <?php echo empty($errori['email']) ? 'hidden' : ''; ?>>
                        <?php echo htmlspecialchars($errori['email'] ?? '', ENT_QUOTES, 'UTF-8'); ?>
                    </span>
                </div>

                <div class="campo-gruppo">
                    <label for="password">Password <span class="campo-suggerimento">(minimo 8 caratteri)</span></label>
                    <div class="campo-password-wrapper">
                        <input
                            type="password"
                            id="password"
                            name="password"
                            required
                            aria-required="true"
                            minlength="8"
                            autocomplete="new-password"
                            aria-describedby="password-suggerimento password-errore"
                            <?php echo isset($errori['password']) ? 'aria-invalid="true"' : ''; ?>>
                        <button type="button" class="mostra-password" aria-pressed="false"
                            aria-label="Tieni premuto per mostrare la password">
                            <svg class="icona-password icona-password-chiusa" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                                <path d="M2 12s3.5-6 10-6 10 6 10 6-3.5 6-10 6-10-6-10-6Z" />
                                <circle cx="12" cy="12" r="3" />
                                <path d="M4 4l16 16" />
                            </svg>
                            <svg class="icona-password icona-password-aperta" viewBox="0 0 24 24" aria-hidden="true" focusable="false" hidden>
                                <path d="M2 12s3.5-6 10-6 10 6 10 6-3.5 6-10 6-10-6-10-6Z" />
                                <circle cx="12" cy="12" r="3" />
                            </svg>
                        </button>
                    </div>
                    <p id="password-suggerimento" class="campo-aiuto">Caratteri ammessi: lettere, numeri, ! @ # $ % &amp;</p>
                    <span id="password-errore" class="campo-errore" <?php echo empty($errori['password']) ? 'hidden' : ''; ?>>
                        <?php echo htmlspecialchars($errori['password'] ?? '', ENT_QUOTES, 'UTF-8'); ?>
                    </span>
                </div>

                <div class="campo-gruppo">
                    <label for="conferma">Conferma password</label>
                    <div class="campo-password-wrapper">
                        <input
                            type="password"
                            id="conferma"
                            name="conferma"
                            required
                            aria-required="true"
                            autocomplete="new-password"
                            aria-describedby="conferma-errore"
                            <?php echo isset($errori['conferma']) ? 'aria-invalid="true"' : ''; ?>>
                        <button type="button" class="mostra-password" aria-pressed="false"
                            aria-label="Tieni premuto per mostrare la conferma password">
                            <svg class="icona-password icona-password-chiusa" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                                <path d="M2 12s3.5-6 10-6 10 6 10 6-3.5 6-10 6-10-6-10-6Z" />
                                <circle cx="12" cy="12" r="3" />
                                <path d="M4 4l16 16" />
                            </svg>
                            <svg class="icona-password icona-password-aperta" viewBox="0 0 24 24" aria-hidden="true" focusable="false" hidden>
                                <path d="M2 12s3.5-6 10-6 10 6 10 6-3.5 6-10 6-10-6-10-6Z" />
                                <circle cx="12" cy="12" r="3" />
                            </svg>
                        </button>
                    </div>
                    <span id="conferma-errore" class="campo-errore" <?php echo empty($errori['conferma']) ? 'hidden' : ''; ?>>
                        <?php echo htmlspecialchars($errori['conferma'] ?? '', ENT_QUOTES, 'UTF-8'); ?>
                    </span>
                </div>

                <button type="submit" class="bottone-primario">Crea account</button>

                <p class="auth-link">Hai già un account? <a href="login.php">Accedi</a></p>
            </form>
        </div>
    </div>
</section>
