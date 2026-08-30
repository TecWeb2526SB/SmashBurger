<?php
/**
 * Contenuto della pagina 403: l'accesso e' stato fatto, ma questo account non puo'
 * aprire la risorsa richiesta.
 */
?>
<h1>Accesso non consentito</h1>

<p>
    Il tuo account non ha i permessi per questa pagina. Se pensi che sia un errore,
    scrivici dal modulo di contatto.
</p>

<p>
    <a href="<?php echo e(url()); ?>">Torna alla home</a>
    <a href="<?php echo e(url('contatti')); ?>">Contattaci</a>
</p>
