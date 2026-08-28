<?php
/**
 * Conferma dell'uscita dall'account.
 */
?>
<h1>Esci</h1>

<p>Vuoi uscire dal tuo account?</p>

<form method="post" action="<?php echo e(url('esci')); ?>">
    <?php echo campo_csrf(); ?>
    <p>
        <button type="submit">Esci</button>
        <a href="<?php echo e(url('area-personale')); ?>">Annulla</a>
    </p>
</form>
