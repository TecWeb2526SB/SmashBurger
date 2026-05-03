<?php
/**
 * account-profilo: View per modifica credenziali account.
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
                <a class="bottone-secondario" href="account">&larr; Torna all'area personale</a>
            </div>
        </div>

        <?php echo ui_alert($flash); ?>

        <div class="checkout-shell'account-shell">
            <form class="checkout-card checkout-form" method="POST" action="account-profilo" data-valida novalidate aria-labelledby="titolo-identita-account">
                <input type="hidden" name="csrf_token" value="<?php echo e($csrfToken); ?>">
                <input type="hidden" name="action" value="update_identity">

                <div class="account-panel-head">
                    <span class="account-panel-kicker">Accesso</span>
                    <h2 id="titolo-identita-account">Credenziali</h2>
                    <p class="checkout-muted">Modifica username o email. Per confermare l'operazione ti chiediamo la password attuale.</p>
                </div>

                <?php echo ui_error_summary($erroriIdentita); ?>

                <?php
                echo ui_form_group('profilo-username', 'Username', 'text', [
                    'value' => $formIdentita['username'],
                    'error' => $erroriIdentita['username'] ?? null,
                    'autocomplete' => 'username',
                    'extra_attrs' => 'name="username" minlength="3" maxlength="50"'
                ]);

                echo ui_form_group('profilo-email', 'Email', 'email', [
                    'value' => $formIdentita['email'],
                    'error' => $erroriIdentita['email'] ?? null,
                    'autocomplete' => 'email',
                    'extra_attrs' => 'name="email" maxlength="160"'
                ]);

                echo ui_form_group('profilo-current-password-identita', 'Password attuale', 'password', [
                    'error' => $erroriIdentita['current_password'] ?? null,
                    'autocomplete' => 'current-password',
                    'extra_attrs' => 'name="current_password"'
                ]);
                ?>

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

        <div class="checkout-shell'account-shell">
            <form class="checkout-card checkout-form" method="POST" action="account-profilo" data-valida novalidate aria-labelledby="titolo-password-account">
                <input type="hidden" name="csrf_token" value="<?php echo e($csrfToken); ?>">
                <input type="hidden" name="action" value="update_password">

                <div class="account-panel-head">
                    <span class="account-panel-kicker">Protezione</span>
                    <h2 id="titolo-password-account">Aggiorna password</h2>
                    <p class="checkout-muted">Scegli una nuova password e confermala per completare il salvataggio in modo sicuro.</p>
                </div>

                <?php echo ui_error_summary($erroriPassword); ?>

                <?php
                echo ui_form_group('profilo-current-password', 'Password attuale', 'password', [
                    'error' => $erroriPassword['current_password'] ?? null,
                    'autocomplete' => 'current-password',
                    'extra_attrs' => 'name="current_password"'
                ]);

                echo ui_form_group('profilo-new-password', 'Nuova password', 'password', [
                    'error' => $erroriPassword['new_password'] ?? null,
                    'autocomplete' => 'new-password',
                    'extra_attrs' => 'name="new_password" minlength="8" aria-describedby="profilo-new-password-suggerimento profilo-new-password-errore"'
                ]);
                ?>
                <p id="profilo-new-password-suggerimento" class="campo-aiuto">Caratteri ammessi: lettere, numeri, underscore (_) e ! @ # $ % &amp; (minimo 8)</p>

                <?php
                echo ui_form_group('profilo-confirm-password', 'Conferma nuova password', 'password', [
                    'error' => $erroriPassword['confirm_password'] ?? null,
                    'autocomplete' => 'new-password',
                    'extra_attrs' => 'name="confirm_password"'
                ]);
                ?>

                <div class="checkout-navigation checkout-navigation--solo-azione">
                    <button class="bottone-primario" type="submit">Aggiorna password</button>
                </div>
            </form>

            <aside class="checkout-card account-side" aria-labelledby="titolo-note-sicurezza">
                <h2 id="titolo-note-sicurezza">Suggerimenti</h2>
                <ul class="riepilogo-lista">
                    <li><span>Password attuale</span><strong>Richiesta</strong></li>
                    <li><span>Lunghezza minima</span><strong>8 caratteri</strong></li>
                    <li><span>Caratteri ammessi</span><strong>Lettere, numeri, underscore (_) e ! @ # $ % &amp;</strong></li>
                </ul>
                <p class="checkout-muted account-note">Una password più lunga e unica rende l'accesso più solido e semplice da gestire nel tempo.</p>
            </aside>
        </div>
    </div>
</section>
