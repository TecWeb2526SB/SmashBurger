<?php
/**
 * Elenco degli account con le azioni su ruolo, stato e cancellazione.
 *
 * Riceve $utenti, $utenteCorrenteId e $daCancellare. Sulla riga del proprio account le
 * azioni non compaiono, perché si usa la pagina profilo.
 */
?>
<h1>Pannello di controllo</h1>

<?php include __DIR__ . '/navigazione.php'; ?>

<h2>Utenti</h2>

<?php if ($daCancellare !== null): ?>
    <section role="alert">
        <h3>Vuoi cancellare l'account <?php echo e($daCancellare['nome_utente']); ?>?</h3>
        <p>Vengono cancellati anche il carrello e gli ordini di questo account.</p>

        <form method="post" action="<?php echo e(url('controllo-utenti')); ?>">
            <?php echo campo_csrf(); ?>
            <input type="hidden" name="azione" value="cancella" />
            <input type="hidden" name="utente_id" value="<?php echo (int) $daCancellare['id']; ?>" />
            <p>
                <button type="submit">Cancella <?php echo e($daCancellare['nome_utente']); ?></button>
                <a href="<?php echo e(url('controllo-utenti')); ?>">Annulla</a>
            </p>
        </form>
    </section>
<?php endif; ?>

<table>
    <caption>Account registrati</caption>
    <thead>
        <tr>
            <th scope="col">Nome utente</th>
            <th scope="col">Email</th>
            <th scope="col">Ordini</th>
            <th scope="col">Ruolo</th>
            <th scope="col">Stato</th>
            <th scope="col">Azioni</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($utenti as $utente): $suo = (int) $utente['id'] === (int) $utenteCorrenteId; ?>
            <tr>
                <th scope="row"><?php echo e($utente['nome_utente']); ?></th>
                <td><?php echo e($utente['email']); ?></td>
                <td><?php echo (int) $utente['ordini']; ?></td>
                <td>
                    <?php if ($suo): ?>
                        <?php echo e($utente['ruolo']); ?>
                    <?php else: ?>
                        <form method="post" action="<?php echo e(url('controllo-utenti')); ?>">
                            <?php echo campo_csrf(); ?>
                            <input type="hidden" name="azione" value="ruolo" />
                            <input type="hidden" name="utente_id" value="<?php echo (int) $utente['id']; ?>" />
                            <label for="ruolo-<?php echo (int) $utente['id']; ?>">
                                Ruolo di <?php echo e($utente['nome_utente']); ?>
                            </label>
                            <select id="ruolo-<?php echo (int) $utente['id']; ?>" name="ruolo">
                                <?php foreach (['cliente', 'amministratore'] as $ruolo): ?>
                                    <option value="<?php echo e($ruolo); ?>"
                                        <?php echo $ruolo === $utente['ruolo'] ? 'selected="selected"' : ''; ?>>
                                        <?php echo e($ruolo); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <button type="submit">Aggiorna</button>
                        </form>
                    <?php endif; ?>
                </td>
                <td><?php echo (int) $utente['attivo'] === 1 ? 'attivo' : 'disattivato'; ?></td>
                <td>
                    <?php if ($suo): ?>
                        <a href="<?php echo e(url('profilo')); ?>">Modifica dal profilo</a>
                    <?php else: ?>
                        <form method="post" action="<?php echo e(url('controllo-utenti')); ?>">
                            <?php echo campo_csrf(); ?>
                            <input type="hidden" name="azione"
                                value="<?php echo (int) $utente['attivo'] === 1 ? 'disattiva' : 'attiva'; ?>" />
                            <input type="hidden" name="utente_id" value="<?php echo (int) $utente['id']; ?>" />
                            <button type="submit">
                                <?php echo (int) $utente['attivo'] === 1 ? 'Disattiva' : 'Attiva'; ?>
                                <?php echo e($utente['nome_utente']); ?>
                            </button>
                        </form>

                        <a href="<?php echo e(url('controllo-utenti', ['cancella' => $utente['id']])); ?>">
                            Cancella <?php echo e($utente['nome_utente']); ?>
                        </a>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
