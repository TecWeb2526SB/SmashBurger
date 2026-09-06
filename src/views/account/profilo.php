<?php
/**
 * Profilo. Riceve $utente, $errori e $sezione dal controller.
 *
 * Ogni riquadro è un modulo indipendente: gli errori riguardano solo quello che e'
 * stato inviato, indicato da $sezione.
 */

$erroreDi = static function (string $sezioneAttesa, string $campo) use ($errori, $sezione): ?string {
    return $sezione === $sezioneAttesa ? ($errori[$campo] ?? null) : null;
};
?>
<h1>Il tuo profilo</h1>

<section>
    <h2>Dati personali</h2>

    <form method="post" action="<?php echo e(url('profilo')); ?>">
        <?php echo campo_csrf(); ?>
        <input type="hidden" name="azione" value="dati" />

        <fieldset>
            <legend>Nome, cognome ed email</legend>

            <?php foreach (['nome' => 'Nome', 'cognome' => 'Cognome'] as $campo => $etichetta): ?>
                <?php $errore = $erroreDi('dati', $campo); ?>
                <p>
                    <label for="<?php echo e($campo); ?>"><?php echo e($etichetta); ?></label>
                    <input type="text" id="<?php echo e($campo); ?>" name="<?php echo e($campo); ?>"
                        required="required" minlength="2" maxlength="80"
                        value="<?php echo e($utente[$campo]); ?>"
                        <?php if ($errore !== null): ?>aria-describedby="errore-<?php echo e($campo); ?>" data-stato="errore"<?php endif; ?> />
                    <?php if ($errore !== null): ?>
                        <small id="errore-<?php echo e($campo); ?>"><?php echo e($errore); ?></small>
                    <?php endif; ?>
                </p>
            <?php endforeach; ?>

            <?php $errore = $erroreDi('dati', 'email'); ?>
            <p>
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required="required" maxlength="160"
                    autocomplete="email" value="<?php echo e($utente['email']); ?>"
                    <?php if ($errore !== null): ?>aria-describedby="errore-email" data-stato="errore"<?php endif; ?> />
                <?php if ($errore !== null): ?>
                    <small id="errore-email"><?php echo e($errore); ?></small>
                <?php endif; ?>
            </p>

            <p><button type="submit">Salva i dati</button></p>
        </fieldset>
    </form>
</section>

<section>
    <h2>Password</h2>

    <form method="post" action="<?php echo e(url('profilo')); ?>">
        <?php echo campo_csrf(); ?>
        <input type="hidden" name="azione" value="password" />

        <fieldset>
            <legend>Cambia la password</legend>

            <?php
            $campiPassword = [
                'attuale' => ['Password attuale', 'current-password'],
                'nuova' => ['Nuova password', 'new-password'],
                'conferma' => ['Ripeti la nuova password', 'new-password'],
            ];
            ?>
            <?php foreach ($campiPassword as $campo => $dettagli): ?>
                <?php $errore = $erroreDi('password', $campo); ?>
                <p>
                    <label for="pwd-<?php echo e($campo); ?>"><?php echo e($dettagli[0]); ?></label>
                    <input type="password" id="pwd-<?php echo e($campo); ?>" name="<?php echo e($campo); ?>"
                        required="required" autocomplete="<?php echo e($dettagli[1]); ?>"
                        <?php echo $campo === 'attuale' ? '' : 'minlength="8"'; ?>
                        <?php if ($errore !== null): ?>aria-describedby="errore-pwd-<?php echo e($campo); ?>" data-stato="errore"<?php endif; ?> />
                    <?php if ($errore !== null): ?>
                        <small id="errore-pwd-<?php echo e($campo); ?>"><?php echo e($errore); ?></small>
                    <?php endif; ?>
                </p>
            <?php endforeach; ?>

            <p><button type="submit">Cambia la password</button></p>
        </fieldset>
    </form>
</section>

<p class="navigazione-pagina">
    <a class="collegamento-indietro" href="<?php echo e(url('area-personale')); ?>"><span class="segno-collegamento" aria-hidden="true">&lt;</span><span>Torna all'area personale</span></a>
</p>
