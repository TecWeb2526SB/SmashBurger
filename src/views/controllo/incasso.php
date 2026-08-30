<?php
/**
 * Incasso del periodo, come numero e come grafico.
 *
 * Il grafico e' decorativo e nascosto ai lettori di schermo: gli stessi dati sono
 * disponibili come testo, con il totale in evidenza e il dettaglio giornaliero dentro
 * una tabella che si apre. Un disegno non deve essere l'unico modo di leggere un dato.
 */
?>
<section class="incasso">
    <h2>Incasso degli ultimi <?php echo (int) GIORNI_INCASSO; ?> giorni</h2>

    <p class="totale"><?php echo e(prezzo($incasso)); ?></p>

    <?php echo grafico_incasso($serie); ?>

    <details>
        <summary>Vedi il dettaglio giorno per giorno</summary>

        <table>
            <caption>Incasso giornaliero degli ultimi <?php echo (int) GIORNI_INCASSO; ?> giorni</caption>
            <thead>
                <tr>
                    <th scope="col">Giorno</th>
                    <th scope="col">Incasso</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($serie as $giorno => $centesimi): ?>
                    <tr>
                        <th scope="row"><?php echo e(data_breve($giorno)); ?></th>
                        <td><?php echo e(prezzo((int) $centesimi)); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </details>

    <p><small>Gli ordini annullati non contano: la merce e' tornata a disposizione.</small></p>
</section>
