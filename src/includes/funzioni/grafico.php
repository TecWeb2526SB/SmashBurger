<?php
/**
 * Grafico dell'incasso, generato lato server.
 *
 * L'immagine e' SVG scritto nel markup, come le icone: nessuna libreria, nessuna
 * richiesta a un altro dominio, nessun JavaScript. E' decorativa e nascosta ai lettori di
 * schermo, perche' gli stessi numeri sono disponibili come testo accanto al grafico.
 */

/**
 * Disegna una serie giornaliera come grafico a barre.
 *
 * Le misure sono in unita' del sistema di coordinate interno: il foglio di stile decide
 * quanto spazio occupa l'immagine sulla pagina, tramite viewBox.
 *
 * @param array $serie coppie data/centesimi, in ordine cronologico
 */
function grafico_incasso(array $serie): string
{
    if ($serie === []) {
        return '';
    }

    $larghezza = 600;
    $altezza = 160;
    $massimo = max(max($serie), 1);
    $colonne = count($serie);
    $passo = $larghezza / $colonne;
    $spessore = max(1, $passo * 0.7);

    $barre = '';
    $indice = 0;

    foreach ($serie as $centesimi) {
        // Una giornata senza incasso resta senza barra: una barra alta zero non si vede
        // e una barra minima direbbe il falso.
        if ($centesimi > 0) {
            $alta = max(1, ($centesimi / $massimo) * ($altezza - 2));
            $barre .= sprintf(
                '<rect x="%.2f" y="%.2f" width="%.2f" height="%.2f" />',
                $indice * $passo + ($passo - $spessore) / 2,
                $altezza - $alta,
                $spessore,
                $alta
            );
        }

        $indice++;
    }

    return '<svg class="grafico" viewBox="0 0 ' . $larghezza . ' ' . $altezza . '"'
        . ' preserveAspectRatio="none" aria-hidden="true" focusable="false">'
        . $barre
        . '</svg>';
}
