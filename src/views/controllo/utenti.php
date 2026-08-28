<?php
/**
 * Elenco degli account con le azioni su ruolo, stato e cancellazione.
 *
 * Riceve $utenti e $utenteCorrenteId: sulla riga del proprio account le azioni non
 * compaiono, perché si usa la pagina profilo.
 */
?>
<h1>Pannello di controllo</h1>

<?php include __DIR__ . '/navigazione.php'; ?>

<h2>Utenti</h2>

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

                        <form method="post" action="<?php echo e(url('controllo-utenti')); ?>">
                            <?php echo campo_csrf(); ?>
                            <input type="hidden" name="azione" value="cancella" />
                            <input type="hidden" name="utente_id" value="<?php echo (int) $utente['id']; ?>" />
                            <button type="submit">Cancella <?php echo e($utente['nome_utente']); ?></button>
                        </form>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
