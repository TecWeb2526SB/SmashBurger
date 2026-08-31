<?php
/**
 * Contenuto della pagina 403: l'accesso è stato fatto, ma questo account non puo'
 * aprire la risorsa richiesta.
 */
?>
<h1>Accesso non consentito</h1>

<p>
    Il tuo account non ha i permessi per questa pagina. Se pensi che sia un errore,
    scrivici dal modulo di contatto.
</p>

<p>
    <a class="collegamento-indietro" href="<?php echo e(url()); ?>"><span class="segno-collegamento" aria-hidden="true">&lt;</span><span>Torna alla home</span></a>
    <a href="<?php echo e(url('contatti')); ?>">Contattaci</a>
</p>
