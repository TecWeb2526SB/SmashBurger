<?php
/**
 * Account registrati. Riceve $utenti, $sedi, $ruoli, $utenteCorrente e $daCancellare.
 *
 * I moduli di riga stanno fuori dalla tabella e i campi li raggiungono con l'attributo
 * form: un elemento form non puo' attraversare piu' celle.
 *
 * Sulla riga del proprio account non compaiono azioni: per quello c'è il profilo.
 */
?>
<h1>Utenti</h1>

<?php require __DIR__ . '/navigazione.php'; ?>

<?php if ($daCancellare !== null): ?>
    <section class="avviso" role="alert" data-tipo="errore">
        <h2>Vuoi cancellare l account <?php echo e($daCancellare['nome_utente']); ?>?</h2>
        <p>Vengono cancellati anche il carrello, gli ordini e le prenotazioni di questo account.</p>

        <form method="post" action="<?php echo e(url('controllo-utenti')); ?>">
            <?php echo campo_csrf(); ?>
            <input type="hidden" name="azione" value="cancella" />
            <input type="hidden" name="utente_id" value="<?php echo (int) $daCancellare['id']; ?>" />
            <p class="azioni">
                <button type="submit" data-tipo="negativo">Cancella <?php echo e($daCancellare['nome_utente']); ?></button>
                <a class="pulsante secondario" href="<?php echo e(url('controllo-utenti')); ?>">Annulla</a>
            </p>
        </form>
    </section>
<?php endif; ?>

<?php foreach ($utenti as $utente): ?>
    <?php if ((int) $utente['id'] !== $utenteCorrente): ?>
        <form method="post" action="<?php echo e(url('controllo-utenti')); ?>"
            id="utente-<?php echo (int) $utente['id']; ?>">
            <?php echo campo_csrf(); ?>
            <input type="hidden" name="azione" value="ruolo" />
            <input type="hidden" name="utente_id" value="<?php echo (int) $utente['id']; ?>" />
        </form>

        <form method="post" action="<?php echo e(url('controllo-utenti')); ?>"
            id="stato-<?php echo (int) $utente['id']; ?>">
            <?php echo campo_csrf(); ?>
            <input type="hidden" name="azione"
                value="<?php echo (int) $utente['attivo'] === 1 ? 'disattiva' : 'attiva'; ?>" />
            <input type="hidden" name="utente_id" value="<?php echo (int) $utente['id']; ?>" />
        </form>
    <?php endif; ?>
<?php endforeach; ?>

<table>
    <caption>Account registrati</caption>
    <thead>
        <tr>
            <th scope="col">Nome utente</th>
            <th scope="col">Email</th>
            <th scope="col">Ordini</th>
            <th scope="col">Ruolo e sede</th>
            <th scope="col">Stato</th>
            <th scope="col">Azioni</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($utenti as $utente): ?>
            <?php
            $suo = (int) $utente['id'] === $utenteCorrente;
            $modulo = 'utente-' . (int) $utente['id'];
            $attivo = (int) $utente['attivo'] === 1;
            ?>
            <tr>
                <th scope="row"><?php echo e($utente['nome_utente']); ?></th>
                <td><?php echo e($utente['email']); ?></td>
                <td><?php echo (int) $utente['ordini']; ?></td>
                <td>
                    <?php if ($suo): ?>
                        <?php echo e($utente['ruolo']); ?>
                    <?php else: ?>
                        <label class="solo-lettori" for="ruolo-<?php echo (int) $utente['id']; ?>">
                            Ruolo di <?php echo e($utente['nome_utente']); ?>
                        </label>
                        <select id="ruolo-<?php echo (int) $utente['id']; ?>" name="ruolo"
                            form="<?php echo e($modulo); ?>">
                            <?php foreach ($ruoli as $ruolo): ?>
                                <option value="<?php echo e($ruolo); ?>"
                                    <?php echo $ruolo === $utente['ruolo'] ? 'selected="selected"' : ''; ?>>
                                    <?php echo e($ruolo); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>

                        <label class="solo-lettori" for="sede-<?php echo (int) $utente['id']; ?>">
                            Sede affidata a <?php echo e($utente['nome_utente']); ?>
                        </label>
                        <select id="sede-<?php echo (int) $utente['id']; ?>" name="sede_id"
                            form="<?php echo e($modulo); ?>">
                            <option value="">Nessuna sede</option>
                            <?php foreach ($sedi as $sede): ?>
                                <option value="<?php echo (int) $sede['id']; ?>"
                                    <?php echo (int) $sede['id'] === (int) $utente['sede_id'] ? 'selected="selected"' : ''; ?>>
                                    <?php echo e($sede['citta']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    <?php endif; ?>
                </td>
                <td>
                    <span class="etichetta" data-tipo="<?php echo $attivo ? 'positivo' : 'negativo'; ?>">
                        <?php echo $attivo ? 'attivo' : 'disattivato'; ?>
                    </span>
                </td>
                <td>
                    <?php if ($suo): ?>
                        <a href="<?php echo e(url('profilo')); ?>">Modifica dal profilo</a>
                    <?php else: ?>
                        <button type="submit" form="<?php echo e($modulo); ?>">
                            Salva ruolo
                            <span class="solo-lettori">di <?php echo e($utente['nome_utente']); ?></span>
                        </button>
                        <button type="submit" form="stato-<?php echo (int) $utente['id']; ?>">
                            <?php echo $attivo ? 'Disattiva' : 'Attiva'; ?>
                            <span class="solo-lettori"><?php echo e($utente['nome_utente']); ?></span>
                        </button>
                        <a class="pulsante" data-tipo="negativo" href="<?php echo e(url('controllo-utenti', ['cancella' => $utente['id']])); ?>">
                            Cancella
                            <span class="solo-lettori"><?php echo e($utente['nome_utente']); ?></span>
                        </a>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<p>
    Per affidare una sede a un manager scegli il ruolo e la sede, poi salva. Una sede ha
    al massimo un manager: se è già occupata va prima liberata.
</p>
