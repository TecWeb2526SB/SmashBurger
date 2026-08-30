<?php
/**
 * Contenuto della pagina 401: la risorsa esiste, ma serve avere fatto l'accesso.
 */
?>
<h1>Serve l'accesso</h1>

<p>
    Questa pagina e' riservata a chi ha un account. Entra con le tue credenziali oppure
    registrati, poi torna qui.
</p>

<p>
    <a href="<?php echo e(url('accedi')); ?>">Accedi</a>
    <a href="<?php echo e(url('registrati')); ?>">Registrati</a>
</p>
