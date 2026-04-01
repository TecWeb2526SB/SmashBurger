<?php
/**
 * profilo.php: View per modifica credenziali account.
 *
 * Variabili attese:
 *   $utente           array
 *   $flash            ?array
 *   $csrfToken        string
 *   $formIdentita     array
 *   $formPassword     array
 *   $erroriIdentita   array
 *   $erroriPassword   array
 */
?>

<section class="account-page" aria-labelledby="titolo-profilo">
    <div class="contenitore">
        <div class="account-page-head">
            <span class="home-eyebrow">Profilo</span>
            <h1 id="titolo-profilo">Gestisci account</h1>
            <p class="account-hero-text">
                Aggiorna i dati di accesso e mantieni il tuo account ordinato, chiaro e sempre sotto controllo.
            </p>
            <div class="account-action-row">
                <a class="bottone-secondario" href="area_personale.php">&larr; Torna all'area personale</a>
            </div>
        </div>

        <?php if (!empty($flash)): ?>
            <div class="alert <?php echo htmlspecialchars($flash['type'] ?? 'info', ENT_QUOTES, 'UTF-8'); ?>">
                <?php echo htmlspecialchars($flash['message'] ?? '', ENT_QUOTES, 'UTF-8'); ?>
            </div>
        <?php endif; ?>

        <div class="checkout-shell account-shell">
            <form class="checkout-card checkout-form" method="POST" action="profilo.php" data-valida novalidate aria-labelledby="titolo-identita-account">
                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8'); ?>">
                <input type="hidden" name="action" value="update_identity">

                <div class="account-panel-head">
                    <span class="account-panel-kicker">Accesso</span>
                    <h2 id="titolo-identita-account">Credenziali</h2>
                    <p class="checkout-muted">Modifica username o email. Per confermare l'operazione ti chiediamo la password attuale.</p>
                </div>

                <?php if (!empty($erroriIdentita['generale'])): ?>
                    <div role="alert" class="errore-sommario">
                        <p><?php echo htmlspecialchars($erroriIdentita['generale'], ENT_QUOTES, 'UTF-8'); ?></p>
                    </div>
                <?php endif; ?>

                <div class="campo-gruppo">
                    <label for="profilo-username">Username</label>
                    <input
                        type="text"
                        id="profilo-username"
                        name="username"
                        value="<?php echo htmlspecialchars($formIdentita['username'], ENT_QUOTES, 'UTF-8'); ?>"
                        required
                        aria-required="true"
                        minlength="3"
                        maxlength="50"
                        autocomplete="username"
                        aria-describedby="profilo-username-errore"
                        <?php echo isset($erroriIdentita['username']) ? 'aria-invalid="true"' : ''; ?>>
                    <span id="profilo-username-errore" class="campo-errore" <?php echo empty($erroriIdentita['username']) ? 'hidden' : ''; ?>>
                        <?php echo htmlspecialchars($erroriIdentita['username'] ?? '', ENT_QUOTES, 'UTF-8'); ?>
                    </span>
                </div>

                <div class="campo-gruppo">
                    <label for="profilo-email">Email</label>
                    <input
                        type="email"
                        id="profilo-email"
                        name="email"
                        value="<?php echo htmlspecialchars($formIdentita['email'], ENT_QUOTES, 'UTF-8'); ?>"
                        required
                        aria-required="true"
                        maxlength="160"
                        autocomplete="email"
                        aria-describedby="profilo-email-errore"
                        <?php echo isset($erroriIdentita['email']) ? 'aria-invalid="true"' : ''; ?>>
                    <span id="profilo-email-errore" class="campo-errore" <?php echo empty($erroriIdentita['email']) ? 'hidden' : ''; ?>>
                        <?php echo htmlspecialchars($erroriIdentita['email'] ?? '', ENT_QUOTES, 'UTF-8'); ?>
                    </span>
                </div>

                <div class="campo-gruppo">
                    <label for="profilo-current-password-identita">Password attuale</label>
                    <div class="campo-password-wrapper">
                        <input
                            type="password"
                            id="profilo-current-password-identita"
                            name="current_password"
                            required
                            aria-required="true"
                            autocomplete="current-password"
                            aria-describedby="profilo-current-password-identita-errore"
                            <?php echo isset($erroriIdentita['current_password']) ? 'aria-invalid="true"' : ''; ?>>
                        <button type="button" class="mostra-password" aria-pressed="false" aria-label="Tieni premuto per mostrare la password attuale">
                            <svg class="icona-password icona-password-chiusa" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                                <path d="M2 12s3.5-6 10-6 10 6 10 6-3.5 6-10 6-10-6-10-6Z" />
                                <circle cx="12" cy="12" r="3" />
                                <path d="M4 4l16 16" />
                            </svg>
                            <svg class="icona-password icona-password-aperta is-hidden" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                                <path d="M2 12s3.5-6 10-6 10 6 10 6-3.5 6-10 6-10-6-10-6Z" />
                                <circle cx="12" cy="12" r="3" />
                            </svg>
                        </button>
                    </div>
                    <span id="profilo-current-password-identita-errore" class="campo-errore" <?php echo empty($erroriIdentita['current_password']) ? 'hidden' : ''; ?>>
                        <?php echo htmlspecialchars($erroriIdentita['current_password'] ?? '', ENT_QUOTES, 'UTF-8'); ?>
                    </span>
                </div>

                <div class="checkout-navigation checkout-navigation--solo-azione">
                    <button class="bottone-primario" type="submit">Salva credenziali</button>
                </div>
            </form>

            <aside class="checkout-card account-side" aria-labelledby="titolo-riepilogo-account">
                <h2 id="titolo-riepilogo-account">In breve</h2>
                <p class="checkout-muted">
                    Qui puoi aggiornare le credenziali di accesso in un unico passaggio. Username ed email vengono salvati subito dopo la conferma con la password attuale.
                </p>
            </aside>
        </div>

        <div class="checkout-shell account-shell">
            <form class="checkout-card checkout-form" method="POST" action="profilo.php" data-valida novalidate aria-labelledby="titolo-password-account">
                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8'); ?>">
                <input type="hidden" name="action" value="update_password">

                <div class="account-panel-head">
                    <span class="account-panel-kicker">Protezione</span>
                    <h2 id="titolo-password-account">Aggiorna password</h2>
                    <p class="checkout-muted">Scegli una nuova password e confermala per completare il salvataggio in modo sicuro.</p>
                </div>

                <?php if (!empty($erroriPassword['generale'])): ?>
                    <div role="alert" class="errore-sommario">
                        <p><?php echo htmlspecialchars($erroriPassword['generale'], ENT_QUOTES, 'UTF-8'); ?></p>
                    </div>
                <?php endif; ?>

                <div class="campo-gruppo">
                    <label for="profilo-current-password">Password attuale</label>
                    <div class="campo-password-wrapper">
                        <input
                            type="password"
                            id="profilo-current-password"
                            name="current_password"
                            required
                            aria-required="true"
                            autocomplete="current-password"
                            aria-describedby="profilo-current-password-errore"
                            <?php echo isset($erroriPassword['current_password']) ? 'aria-invalid="true"' : ''; ?>>
                        <button type="button" class="mostra-password" aria-pressed="false" aria-label="Tieni premuto per mostrare la password attuale">
                            <svg class="icona-password icona-password-chiusa" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                                <path d="M2 12s3.5-6 10-6 10 6 10 6-3.5 6-10 6-10-6-10-6Z" />
                                <circle cx="12" cy="12" r="3" />
                                <path d="M4 4l16 16" />
                            </svg>
                            <svg class="icona-password icona-password-aperta is-hidden" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                                <path d="M2 12s3.5-6 10-6 10 6 10 6-3.5 6-10 6-10-6-10-6Z" />
                                <circle cx="12" cy="12" r="3" />
                            </svg>
                        </button>
                    </div>
                    <span id="profilo-current-password-errore" class="campo-errore" <?php echo empty($erroriPassword['current_password']) ? 'hidden' : ''; ?>>
                        <?php echo htmlspecialchars($erroriPassword['current_password'] ?? '', ENT_QUOTES, 'UTF-8'); ?>
                    </span>
                </div>

                <div class="campo-gruppo">
                    <label for="profilo-new-password">Nuova password <span class="campo-suggerimento">(minimo 8 caratteri)</span></label>
                    <div class="campo-password-wrapper">
                        <input
                            type="password"
                            id="profilo-new-password"
                            name="new_password"
                            required
                            aria-required="true"
                            minlength="8"
                            autocomplete="new-password"
                            aria-describedby="profilo-new-password-suggerimento profilo-new-password-errore"
                            <?php echo isset($erroriPassword['new_password']) ? 'aria-invalid="true"' : ''; ?>>
                        <button type="button" class="mostra-password" aria-pressed="false" aria-label="Tieni premuto per mostrare la nuova password">
                            <svg class="icona-password icona-password-chiusa" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                                <path d="M2 12s3.5-6 10-6 10 6 10 6-3.5 6-10 6-10-6-10-6Z" />
                                <circle cx="12" cy="12" r="3" />
                                <path d="M4 4l16 16" />
                            </svg>
                            <svg class="icona-password icona-password-aperta is-hidden" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                                <path d="M2 12s3.5-6 10-6 10 6 10 6-3.5 6-10 6-10-6-10-6Z" />
                                <circle cx="12" cy="12" r="3" />
                            </svg>
                        </button>
                    </div>
                    <p id="profilo-new-password-suggerimento" class="campo-aiuto">Caratteri ammessi: lettere, numeri, ! @ # $ % &amp;</p>
                    <span id="profilo-new-password-errore" class="campo-errore" <?php echo empty($erroriPassword['new_password']) ? 'hidden' : ''; ?>>
                        <?php echo htmlspecialchars($erroriPassword['new_password'] ?? '', ENT_QUOTES, 'UTF-8'); ?>
                    </span>
                </div>

                <div class="campo-gruppo">
                    <label for="profilo-confirm-password">Conferma nuova password</label>
                    <div class="campo-password-wrapper">
                        <input
                            type="password"
                            id="profilo-confirm-password"
                            name="confirm_password"
                            required
                            aria-required="true"
                            autocomplete="new-password"
                            aria-describedby="profilo-confirm-password-errore"
                            <?php echo isset($erroriPassword['confirm_password']) ? 'aria-invalid="true"' : ''; ?>>
                        <button type="button" class="mostra-password" aria-pressed="false" aria-label="Tieni premuto per mostrare la conferma password">
                            <svg class="icona-password icona-password-chiusa" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                                <path d="M2 12s3.5-6 10-6 10 6 10 6-3.5 6-10 6-10-6-10-6Z" />
                                <circle cx="12" cy="12" r="3" />
                                <path d="M4 4l16 16" />
                            </svg>
                            <svg class="icona-password icona-password-aperta is-hidden" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                                <path d="M2 12s3.5-6 10-6 10 6 10 6-3.5 6-10 6-10-6-10-6Z" />
                                <circle cx="12" cy="12" r="3" />
                            </svg>
                        </button>
                    </div>
                    <span id="profilo-confirm-password-errore" class="campo-errore" <?php echo empty($erroriPassword['confirm_password']) ? 'hidden' : ''; ?>>
                        <?php echo htmlspecialchars($erroriPassword['confirm_password'] ?? '', ENT_QUOTES, 'UTF-8'); ?>
                    </span>
                </div>

                <div class="checkout-navigation checkout-navigation--solo-azione">
                    <button class="bottone-primario" type="submit">Aggiorna password</button>
                </div>
            </form>

            <aside class="checkout-card account-side" aria-labelledby="titolo-note-sicurezza">
                <h2 id="titolo-note-sicurezza">Suggerimenti</h2>
                <ul class="riepilogo-lista">
                    <li><span>Password attuale</span><strong>Richiesta</strong></li>
                    <li><span>Lunghezza minima</span><strong>8 caratteri</strong></li>
                    <li><span>Caratteri ammessi</span><strong>Lettere, numeri, ! @ # $ % &amp;</strong></li>
                </ul>
                <p class="checkout-muted account-note">Una password piu lunga e unica rende l'accesso piu solido e semplice da gestire nel tempo.</p>
            </aside>
        </div>
    </div>
</section>
