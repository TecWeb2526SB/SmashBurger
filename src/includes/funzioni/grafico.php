<?php
/**
 * Grafico dell'incasso, generato lato server.
 *
 * L'immagine è un SVG scritto direttamente nel markup: non usa librerie, richieste
 * esterne o JavaScript. Date, importi e descrizione accessibile fanno parte dello
 * stesso grafico.
 */

/**
 * Trova un intervallo monetario regolare per l'asse verticale.
 *
 * Il passo è sempre un valore 1, 2, 5 o 10 moltiplicato per una potenza di dieci.
 * In questo modo la scala non cambia tra una linea e l'altra e il limite superiore
 * resta un importo leggibile, arrotondato sopra al maggiore incasso della serie.
 */
function passo_scala_incasso(int $massimo): int
{
    $passoIdeale = max($massimo / 5, 100);
    $potenza = 10 ** floor(log10($passoIdeale));
    $normalizzato = $passoIdeale / $potenza;

    if ($normalizzato <= 1) {
        $moltiplicatore = 1;
    } elseif ($normalizzato <= 2) {
        $moltiplicatore = 2;
    } elseif ($normalizzato <= 5) {
        $moltiplicatore = 5;
    } else {
        $moltiplicatore = 10;
    }

    return max(100, (int) round($moltiplicatore * $potenza));
}

/**
 * Scrive un importo compatto adatto alle etichette dell'asse verticale.
 */
function etichetta_scala_incasso(int $centesimi): string
{
    $decimali = $centesimi % 100 === 0 ? 0 : 2;

    return number_format($centesimi / 100, $decimali, ',', '.') . ' €';
}

/**
 * Disegna una serie giornaliera come grafico a barre.
 *
 * Le misure sono in unita' del sistema di coordinate interno: il foglio di stile
 * mantiene il grafico leggibile e abilita lo scorrimento orizzontale sugli schermi
 * stretti.
 *
 * @param array $serie coppie data/centesimi, in ordine cronologico
 */
function grafico_incasso(array $serie): string
{
    if ($serie === []) {
        return '';
    }

    $larghezza = 1000;
    $altezza = 390;
    $margineSinistro = 86;
    $margineDestro = 20;
    $margineSuperiore = 24;
    $margineInferiore = 104;
    $larghezzaArea = $larghezza - $margineSinistro - $margineDestro;
    $altezzaArea = $altezza - $margineSuperiore - $margineInferiore;
    $base = $margineSuperiore + $altezzaArea;

    $valori = array_map('intval', array_values($serie));
    $massimo = max(max($valori), 0);
    $passoScala = passo_scala_incasso($massimo);
    $massimoScala = max($passoScala, (int) ceil($massimo / $passoScala) * $passoScala);
    $intervalli = (int) ($massimoScala / $passoScala);

    $colonne = count($serie);
    $passoColonna = $larghezzaArea / $colonne;
    $spessore = max(4, $passoColonna * 0.68);

    $griglia = '';
    for ($indice = 0; $indice <= $intervalli; $indice++) {
        $importo = $indice * $passoScala;
        $y = $base - ($importo / $massimoScala) * $altezzaArea;
        $etichetta = htmlspecialchars(etichetta_scala_incasso($importo), ENT_QUOTES, 'UTF-8');

        $griglia .= sprintf(
            '<line x1="%d" y1="%.2f" x2="%d" y2="%.2f" />'
            . '<text x="%d" y="%.2f" text-anchor="end">%s</text>',
            $margineSinistro,
            $y,
            $larghezza - $margineDestro,
            $y,
            $margineSinistro - 12,
            $y + 4,
            $etichetta
        );
    }

    $barre = '';
    $date = '';
    $descrizione = [];
    $indice = 0;

    foreach ($serie as $giorno => $centesimi) {
        $centesimi = (int) $centesimi;
        $centro = $margineSinistro + $indice * $passoColonna + $passoColonna / 2;
        $data = DateTimeImmutable::createFromFormat('!Y-m-d', (string) $giorno);
        $dataAbbreviata = $data instanceof DateTimeImmutable ? $data->format('d/m') : (string) $giorno;
        $dataSicura = htmlspecialchars($dataAbbreviata, ENT_QUOTES, 'UTF-8');
        $importoSicuro = htmlspecialchars(prezzo($centesimi), ENT_QUOTES, 'UTF-8');

        if ($centesimi > 0) {
            $altezzaBarra = ($centesimi / $massimoScala) * $altezzaArea;
            $barre .= sprintf(
                '<g><title>%s: %s</title>'
                . '<rect class="barra-grafico" x="%.2f" y="%.2f" width="%.2f" height="%.2f" /></g>',
                $dataSicura,
                $importoSicuro,
                $centro - $spessore / 2,
                $base - $altezzaBarra,
                $spessore,
                $altezzaBarra
            );
        }

        $date .= sprintf(
            '<text x="%.2f" y="%.2f" text-anchor="end" transform="rotate(-50 %.2f %.2f)">%s</text>',
            $centro + 3,
            $base + 26,
            $centro + 3,
            $base + 26,
            $dataSicura
        );
        $descrizione[] = $dataAbbreviata . ': ' . prezzo($centesimi);
        $indice++;
    }

    $descrizioneAccessibile = htmlspecialchars(
        'Incasso giornaliero. ' . implode('; ', $descrizione) . '.',
        ENT_QUOTES,
        'UTF-8'
    );

    return '<svg class="grafico grafico-incasso" viewBox="0 0 ' . $larghezza . ' ' . $altezza . '"'
        . ' role="img" aria-labelledby="titolo-grafico-incasso descrizione-grafico-incasso" focusable="false">'
        . '<title id="titolo-grafico-incasso">Incasso giornaliero degli ultimi giorni</title>'
        . '<desc id="descrizione-grafico-incasso">' . $descrizioneAccessibile . '</desc>'
        . '<g class="griglia-grafico">' . $griglia . '</g>'
        . '<g class="asse-grafico"><line x1="' . $margineSinistro . '" y1="' . $margineSuperiore
        . '" x2="' . $margineSinistro . '" y2="' . $base . '" /></g>'
        . '<g class="barre-grafico">' . $barre . '</g>'
        . '<g class="date-grafico">' . $date . '</g>'
        . '</svg>';
}
