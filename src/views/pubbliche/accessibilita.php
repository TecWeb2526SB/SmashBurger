<?php
/**
 * Dichiarazione di accessibilita'.
 *
 * Le prove elencate qui vanno tenute allineate a quelle davvero eseguite: la
 * dichiarazione e' una promessa verificabile, non una formula.
 */
?>
<h1>Accessibilita'</h1>

<p>
    Questo sito punta alla conformita' alle linee guida WCAG 2.1 di livello AA. Qui trovi
    che cosa abbiamo fatto, come lo verifichiamo e come segnalarci un problema.
</p>

<section>
    <h2>Che cosa abbiamo fatto</h2>
    <ul>
        <li>Markup semantico con intestazioni in ordine e un solo titolo principale per pagina.</li>
        <li>Collegamento per saltare direttamente al contenuto, primo elemento di ogni pagina.</li>
        <li>Ogni campo dei moduli ha un'etichetta associata; il testo di esempio non sostituisce l'etichetta.</li>
        <li>Errori dei moduli riepilogati in cima e ripetuti accanto al campo interessato.</li>
        <li>Tabelle con didascalia e intestazioni di riga e di colonna dichiarate.</li>
        <li>Immagini di contenuto con testo alternativo; le immagini decorative sono nascoste.</li>
        <li>Informazioni mai affidate al solo colore: gli stati hanno sempre anche una parola.</li>
        <li>Navigazione completa da tastiera con il punto di attenzione sempre visibile.</li>
        <li>Il sito funziona per intero anche con JavaScript disattivato.</li>
    </ul>
</section>

<section>
    <h2>Come lo verifichiamo</h2>
    <p>
        A ogni modifica il markup viene controllato dal validatore del W3C, i fogli di
        stile dal validatore CSS, e le pagine da uno strumento automatico di
        accessibilita' sullo standard WCAG 2.1 AA. Ai controlli automatici affianchiamo
        prove manuali da tastiera e con un lettore di schermo, perche' un punteggio pieno
        non dimostra da solo che una pagina sia utilizzabile.
    </p>
</section>

<section>
    <h2>Limiti noti</h2>
    <p>
        Le fotografie delle sedi sono ancora immagini segnaposto, e il loro testo
        alternativo lo dichiara: verranno sostituite dalle fotografie reali con una
        descrizione adeguata.
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
