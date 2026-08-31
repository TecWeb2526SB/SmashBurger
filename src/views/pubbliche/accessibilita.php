<?php
/**
 * Dichiarazione di accessibilita'.
 */
?>
<article class="pagina-documento">
    <header class="header-documento">
        <p class="occhiello">Il sito è per tutti</p>
        <h1>Accessibilita'</h1>
        <p class="introduzione">
            Puntiamo alla conformita' WCAG 2.1 AA. Qui trovi cosa abbiamo fatto, come lo
            verifichiamo e come segnalarci un problema.
        </p>
    </header>

    <section>
        <h2>Che cosa abbiamo fatto</h2>
        <ul>
            <li>Markup semantico con intestazioni in ordine e un solo titolo principale.</li>
            <li>Collegamento per saltare direttamente al contenuto.</li>
            <li>Ogni campo dei moduli ha un'etichetta associata.</li>
            <li>Errori riepilogati in cima e ripetuti accanto al campo interessato.</li>
            <li>Tabelle con didascalia e intestazioni dichiarate.</li>
            <li>Immagini di contenuto con testo alternativo.</li>
            <li>Informazioni mai affidate al solo colore.</li>
            <li>Navigazione da tastiera con il punto di attenzione visibile.</li>
            <li>Il sito funziona per intero anche senza JavaScript.</li>
        </ul>
    </section>

    <section>
        <h2>Come lo verifichiamo</h2>
        <p>
            A ogni modifica controlliamo markup, fogli di stile e pagine con strumenti
            automatici sullo standard WCAG 2.1 AA. Affianchiamo prove da tastiera e con
            un lettore di schermo, perchè un punteggio non basta.
        </p>
    </section>

    <section>
        <h2>Immagini</h2>
        <p>
            Le immagini che comunicano contenuto hanno un testo alternativo scritto dopo
            aver verificato il file definitivo. Le immagini puramente decorative vengono
            ignorate dai lettori di schermo.
        </p>
    </section>

    <section>
        <h2>Segnalare un problema</h2>
        <p>
            Se incontri una difficolta', <a href="<?php echo e(url('contatti')); ?>">scrivici</a>
            scegliendo l'argomento "Una segnalazione sul sito", oppure manda una email a
            <a href="mailto:<?php echo e(EMAIL_CONTATTO); ?>"><?php echo e(EMAIL_CONTATTO); ?></a>.
            Indica la pagina e che cosa non ha funzionato.
        </p>
    </section>
</article>
