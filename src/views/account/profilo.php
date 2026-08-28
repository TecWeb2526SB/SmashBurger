<?php
/**
 * Modifica dei dati dell'account.
 *
 * Riceve $utente e $confermaCancella, vero quando è stata chiesta la conferma di
 * cancellazione dell'account.
 */
?>
<h1>Profilo</h1>

<p>Nome utente: <?php echo e($utente['nome_utente']); ?></p>

<section>
    <h2>Email</h2>

    <form method="post" action="<?php echo e(url('profilo')); ?>">
        <?php echo campo_csrf(); ?>
        <input type="hidden" name="azione" value="email" />

        <p>
            <label for="email">Indirizzo email</label>
            <input type="email" id="email" name="email" required="required" maxlength="160"
                autocomplete="email" value="<?php echo e($utente['email']); ?>" />
        </p>

        <p><button type="submit">Aggiorna email</button></p>
    </form>
</section>

<section>
    <h2>Password</h2>

    <form method="post" action="<?php echo e(url('profilo')); ?>">
        <?php echo campo_csrf(); ?>
        <input type="hidden" name="azione" value="password" />

        <p>
            <label for="password_attuale">Password attuale</label>
            <input type="password" id="password_attuale" name="password_attuale"
                required="required" autocomplete="current-password" />
        </p>

        <p>
            <label for="password_nuova">Nuova password</label>
            <input type="password" id="password_nuova" name="password_nuova"
                required="required" minlength="8" autocomplete="new-password" />
            <small id="aiuto-password-nuova">Almeno 8 caratteri.</small>
        </p>

        <p><button type="submit">Aggiorna password</button></p>
    </form>
</section>

<?php if (!utente_e_amministratore()): ?>
    <section>
        <h2>Cancellazione dell'account</h2>

        <p>La cancellazione elimina l'account, il carrello e lo storico degli ordini.</p>

        <?php if ($confermaCancella): ?>
            <div role="alert">
                <p>Confermi la cancellazione? L'operazione non può essere annullata.</p>

                <form method="post" action="<?php echo e(url('profilo')); ?>">
                    <?php echo campo_csrf(); ?>
                    <input type="hidden" name="azione" value="cancella" />
                    <p>
                        <button type="submit">Sì, cancella il mio account</button>
                        <a href="<?php echo e(url('profilo')); ?>">Annulla</a>
                    </p>
                </form>
            </div>
        <?php else: ?>
            <p><a href="<?php echo e(url('profilo', ['cancella' => 1])); ?>">Cancella il mio account</a></p>
        <?php endif; ?>
    </section>
<?php endif; ?>
