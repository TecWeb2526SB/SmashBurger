<?php
/**
 * Dichiarazione di accessibilità. Contenuto statico.
 */
?>
<h1>Accessibilità</h1>

<p>
    Il sito punta alla conformità con le Web Content Accessibility Guidelines 2.1 di
    livello AA, pubblicate dal W3C.
</p>

<section>
    <h2>Scelte adottate</h2>

    <ul>
        <li>struttura basata su elementi semantici e intestazioni in ordine gerarchico;</li>
        <li>collegamento iniziale per saltare direttamente al contenuto;</li>
        <li>ogni campo dei moduli ha un'etichetta associata;</li>
        <li>ogni immagine di contenuto ha un testo alternativo;</li>
        <li>le tabelle hanno didascalia e intestazioni di riga e colonna;</li>
        <li>tutte le funzioni sono utilizzabili da tastiera e senza JavaScript.</li>
    </ul>
</section>

<section>
    <h2>Verifiche</h2>

    <p>
        Il markup viene verificato con il validatore del W3C e con controlli automatici di
        accessibilità. I controlli automatici non coprono tutti i criteri, quindi possono
        restare problemi non rilevati.
    </p>
</section>

<section>
    <h2>Segnalazioni</h2>

    <p>
        Chi incontra una barriera può scriverlo a
        <a href="mailto:<?php echo e(EMAIL_SITO); ?>"><?php echo e(EMAIL_SITO); ?></a>,
        indicando la pagina e il problema riscontrato.
    </p>
</section>
