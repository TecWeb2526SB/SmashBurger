<?php
/**
 * Contenuto della pagina 401: la risorsa esiste, ma serve avere fatto l'accesso.
 *
 * La diapositiva accanto al testo porta l'illustrazione dell'errore: un disegno ritagliato
 * su fondo trasparente, che sul rosso del riquadro si vede per intero.
 */
?>
<section class="apertura-errore">
    <div class="testo-errore">
        <p class="codice-errore">401</p>
        <h1>Serve l&apos;accesso</h1>
        <p class="introduzione">
            Questa pagina sta dietro al bancone: entra con le tue credenziali e la trovi
            dove l&apos;avevi lasciata. Se non hai ancora un account, si apre in un minuto.
        </p>
        <p class="azioni">
            <a class="pulsante" data-tipo="positivo" href="<?php echo e(url('accedi')); ?>">Accedi</a>
            <a class="pulsante secondario" href="<?php echo e(url('registrati')); ?>">Registrati</a>
        </p>
    </div>

    <figure class="istantanea">
        <p class="foto-editoriale">
            <img src="<?php echo e(risorsa('images/errori/401.webp', true)); ?>"
                width="700" height="656"
                alt="Due spatole da piastra incrociate davanti a un burger nel suo incarto, come un cancello." />
            <span>Errore 401 / area riservata</span>
            <strong>Prima il pass, poi il panino</strong>
        </p>
        <figcaption>Il bancone è chiuso a chiave, e la chiave è il tuo account.</figcaption>
    </figure>
</section>
