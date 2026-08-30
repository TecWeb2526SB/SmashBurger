<?php
/**
 * Elenco delle sedi. Riceve $sedi, $orari e $giorni dal controller.
 */
?>
<h1>Sedi e orari</h1>

<p>
    Siamo a Padova, Treviso, Vicenza e Udine. Da ogni sede puoi ritirare il tuo ordine
    oppure farti consegnare a casa; in ogni sede c'e' una sala per feste e ricorrenze.
</p>

<ul class="griglia">
    <?php foreach ($sedi as $sede): ?>
        <li>
            <article class="scheda">
                <h2>
                    <a href="<?php echo e(url('sede', ['slug' => $sede['slug']])); ?>">
                        <?php echo e($sede['citta']); ?>
                    </a>
                </h2>

                <p>
                    <?php echo e($sede['indirizzo']); ?><br />
                    <?php echo e($sede['cap']); ?> <?php echo e($sede['citta']); ?> (<?php echo e($sede['provincia']); ?>)
                </p>

                <p>
                    Telefono
                    <a href="tel:<?php echo e(str_replace(' ', '', $sede['telefono'])); ?>">
                        <?php echo e($sede['telefono']); ?>
                    </a>
                </p>

                <table>
                    <caption>Orari di <?php echo e($sede['citta']); ?></caption>
                    <thead>
                        <tr>
                            <th scope="col">Giorno</th>
                            <th scope="col">Apertura</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($giorni as $numero => $nome): ?>
                            <tr>
                                <th scope="row"><?php echo e($nome); ?></th>
                                <td><?php echo e(fascia_leggibile($orari[$sede['id']][$numero] ?? ['chiuso' => 1, 'apertura' => null])); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

                <p>
                    <?php if ((int) $sede['sala_eventi_disponibile'] === 1): ?>
                        <span class="etichetta" data-tipo="positivo">Sala eventi prenotabile</span>
                    <?php else: ?>
                        <span class="etichetta" data-tipo="attenzione">Sala eventi non prenotabile</span>
                    <?php endif; ?>
                </p>
            </article>
        </li>
    <?php endforeach; ?>
</ul>
