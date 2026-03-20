<?php
/**
 * registrazione.php: View della pagina di registrazione.
 *
 * Variabili attese dal controller:
 *   $errori  array   Errori di validazione (chiavi: 'nome', 'email', 'password', 'conferma')
 *   $valori  array   Valori da ripopolare dopo errore (chiavi: 'nome', 'email')
 */
?>

<section aria-labelledby="titolo-registrazione" class="auth-sezione">
    <div class="contenitore">
        <div class="auth-box">
            <h1 id="titolo-registrazione">Crea un account</h1>

            <?php if (!empty($errori)): ?>
                <div role="alert" class="errore-sommario">
                    <p>Correggi gli errori nel modulo prima di procedere.</p>
                </div>
            <?php endif; ?>

            <form method="POST" action="registrazione.php" data-valida novalidate>

                <div class="campo-gruppo">
                    <label for="nome">Nome</label>
                    <input
                        type="text"
                        id="nome"
                        name="nome"
                        value="<?php echo $valori['nome'] ?? ''; ?>"
                        required
                        minlength="2"
                        autocomplete="given-name"
                        aria-describedby="nome-errore"
                        <?php echo isset($errori['nome']) ? 'aria-invalid="true"' : ''; ?>>
                    <span id="nome-errore" class="campo-errore" <?php echo empty($errori['nome']) ? 'hidden' : ''; ?>>
                        <?php echo htmlspecialchars($errori['nome'] ?? '', ENT_QUOTES, 'UTF-8'); ?>
                    </span>
                </div>

                <div class="campo-gruppo">
                    <label for="email">E-mail</label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        value="<?php echo $valori['email'] ?? ''; ?>"
                        required
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
                            minlength="8"
                            autocomplete="new-password"
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

                <div class="campo-gruppo">
                    <label for="conferma">Conferma password</label>
                    <div class="campo-password-wrapper">
                        <input
                            type="password"
                            id="conferma"
                            name="conferma"
                            required
                            autocomplete="new-password"
                            aria-describedby="conferma-errore"
                            <?php echo isset($errori['conferma']) ? 'aria-invalid="true"' : ''; ?>>
                        <button type="button" class="mostra-password" aria-pressed="false"
                            aria-label="Mostra conferma password">
                            <span aria-hidden="true">👁</span>
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
