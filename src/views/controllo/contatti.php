<?php
/**
 * Messaggi ricevuti dal modulo di contatto.
 *
 * Riceve $messaggi, $stati e $categorie dal controller. In $stati ogni stato porta con
 * se' il verbo del pulsante che ci fa arrivare e il tipo della propria etichetta.
 *
 * Il testo si legge per intero nella riga, senza pulsante per aprirlo: il modulo che lo
 * raccoglie lo tiene entro CARATTERI_MESSAGGIO_CONTATTO caratteri, quindi ci sta.
 *
 * Ogni pulsante porta lo stato come nome e l'identificativo come valore: il browser invia
 * solo quello premuto, quindi il modulo resta uno per tutta la tabella. I comandi di una
 * riga si susseguono sempre nello stesso ordine, quello degli stati, e la riga corrente
 * non compare fra loro: non ha senso portare un messaggio dove gia' si trova.
 */
?>
<h1>Messaggi</h1>

<?php require __DIR__ . '/navigazione.php'; ?>

<?php if ($messaggi === []): ?>
    <p>Non è arrivato nessun messaggio.</p>
<?php else: ?>
    <form method="post" action="<?php echo e(url('controllo-contatti')); ?>"
        data-modulo="messaggi">
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
                    <th scope="col" data-colonna="azioni">Azioni</th>
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
                        <td><?php echo e($messaggio['testo']); ?></td>
                        <td>
                            <span class="etichetta" data-tipo="<?php echo e($stati[$messaggio['stato']]['tipo']); ?>">
                                <?php echo e($messaggio['stato']); ?>
                            </span>
                        </td>
                        <td data-colonna="azioni">
                            <p class="azioni-riga">
                                <?php foreach ($stati as $stato => $presentazione): ?>
                                    <?php if ($stato !== $messaggio['stato']): ?>
                                        <button type="submit" name="<?php echo e(str_replace(' ', '-', $stato)); ?>"
                                            value="<?php echo (int) $messaggio['id']; ?>">
                                            <?php echo e($presentazione['comando']); ?>
                                            <span class="solo-lettori">
                                                il messaggio di <?php echo e($messaggio['nome']); ?>
                                            </span>
                                        </button>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </p>
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
