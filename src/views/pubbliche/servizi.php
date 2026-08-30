<?php
/**
 * Descrizione dei servizi: ritiro, consegna, sala eventi, allergeni e pagamento.
 */
?>
<h1>Servizi</h1>

<p>
    Da Smash Burger ordini online e scegli come ricevere: passi a ritirare in sede oppure
    ti facciamo consegnare a casa. In ogni sede c'e' anche una sala per le ricorrenze.
</p>

<section>
    <h2>Ritiro in sede</h2>
    <p>
        Scegli la sede, aggiungi i prodotti e indichi l'orario in cui passi. Prepariamo
        l'ordine per quell'ora, cosi' non aspetti al banco.
    </p>
    <p>Il ritiro e' possibile solo negli orari di apertura della sede scelta.</p>
</section>

<section>
    <h2>Consegna a domicilio</h2>
    <p>
        Se preferisci restare a casa, indichi l'indirizzo al momento dell'ordine e la
        consegna viene presa in carico da una societa' esterna dopo il pagamento.
    </p>
    <p>
        Se la consegna non e' possibile, per esempio perche' l'indirizzo non e'
        raggiungibile, l'ordine viene annullato e rimborsato: te lo comunichiamo via
        email e lo vedi anche nella tua area personale.
    </p>
</section>

<section>
    <h2>Sala eventi</h2>
    <p>
        Ogni sede ha una sala prenotabile per compleanni, feste di laurea e cene di
        gruppo. Scegli orario di inizio e durata, e la prenotazione viene poi confermata
        dal personale della sede.
    </p>
    <p><a href="<?php echo e(url('sedi')); ?>">Scegli la sede e prenota</a></p>
</section>

<section>
    <h2>Allergeni</h2>
    <p>
        Gli allergeni di ogni prodotto sono scritti nel menu e nella pagina del prodotto.
        Se hai un'allergia, <a href="<?php echo e(url('contatti')); ?>">scrivici</a> prima di
        ordinare: nelle nostre cucine si lavorano glutine, latte, uova e frutta a guscio.
    </p>
</section>

<section>
    <h2>Pagamento</h2>
    <p>
        Al momento dell'ordine scegli fra carta e contanti. Il pagamento con carta e'
        simulato: il sito non chiede e non conserva nessun dato della tua carta.
    </p>
</section>
