<?php
/**
 * Conferma dell'uscita. L'uscita avviene in POST, quindi serve un modulo e non un
 * semplice collegamento.
 */
?>
<h1>Esci</h1>

<p>Vuoi chiudere la sessione su questo dispositivo?</p>

<form method="post" action="<?php echo e(url('esci')); ?>">
    <?php echo campo_csrf(); ?>
    <p>
        <button type="submit">Esci</button>
        <a href="<?php echo e($urlAnnulla); ?>">Annulla</a>
    </p>
</form>
