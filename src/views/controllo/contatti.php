<?php
/**
 * Messaggi ricevuti dal modulo di contatto.
 *
 * Riceve $messaggi, $stati e $categorie dal controller. Il testo del messaggio sta in un
 * elemento che si apre: in una cella di tabella occuperebbe troppo spazio e allargherebbe
 * la riga oltre lo schermo.
 */
?>
<h1>Messaggi</h1>

<?php require __DIR__ . '/navigazione.php'; ?>

<?php if ($messaggi === []): ?>
    <p>Non è arrivato nessun messaggio.</p>
<?php else: ?>
    <form method="post" action="<?php echo e(url('controllo-contatti')); ?>">
        <?php echo campo_csrf(); ?>

        <table>
            <caption>Messaggi arrivati dal modulo di contatto</caption>
            <thead>
                <tr>
                    <th scope="col">Ricevuto</th>
                    <th scope="col">Da</th>
                    <th scope="col">Argomento</th>
                    <th scope="col">Messaggio</th>
                    <th scope="col">Stato</th>
                    <th scope="col">Azioni</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($messaggi as $messaggio): ?>
                    <tr>
                        <th scope="row"><?php echo e(data_ora($messaggio['creato_il'])); ?></th>
                        <td>
                            <?php echo e($messaggio['nome']); ?><br />
                            <a href="mailto:<?php echo e($messaggio['email']); ?>">
                                <?php echo e($messaggio['email']); ?>
                            </a>
                        </td>
                        <td><?php echo e($categorie[$messaggio['categoria']] ?? $messaggio['categoria']); ?></td>
                        <td>
                            <details>
                                <summary>
                                    Leggi
                                    <span class="solo-lettori">il messaggio di <?php echo e($messaggio['nome']); ?></span>
                                </summary>
                                <p><?php echo e($messaggio['testo']); ?></p>
                            </details>
                        </td>
                        <td>
                            <span class="etichetta" data-tipo="<?php echo $messaggio['stato'] === 'nuovo' ? 'attenzione' : 'positivo'; ?>">
                                <?php echo e($messaggio['stato']); ?>
                            </span>
                        </td>
                        <td>
                            <?php foreach ($stati as $stato): ?>
                                <?php if ($stato !== $messaggio['stato']): ?>
                                    <button type="submit" name="<?php echo e(str_replace(' ', '-', $stato)); ?>"
                                        value="<?php echo (int) $messaggio['id']; ?>">
                                        <?php echo e($stato); ?>
                                        <span class="solo-lettori">
                                            per il messaggio di <?php echo e($messaggio['nome']); ?>
                                        </span>
                                    </button>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </form>

    <p>
        Il sito gestisce solo il primo messaggio di ogni richiesta: le risposte successive
        avvengono via email.
    </p>
<?php endif; ?>
