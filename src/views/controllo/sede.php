<?php
/**
 * Scheda di una sede. Riceve $sede, $orari, $giorni, $errori e $valori dal controller.
 *
 * Tre riquadri indipendenti: dati del locale, orari settimanali e sala eventi. Ognuno
 * salva per conto suo, cosi' un errore in uno non fa perdere quello che si stava
 * scrivendo negli altri.
 *
 * Il riquadro della sala eventi si legge dall'alto in basso: titolo, stato in cui si
 * trova adesso, che cosa succede premendo il pulsante e infine il pulsante. Chiudere le
 * prenotazioni toglie un servizio al pubblico, quindi porta il colore delle operazioni
 * negative; riaprirle non lo e', e resta un pulsante normale.
 */

$salaAperta = (int) $sede['sala_eventi_disponibile'] === 1;

$campi = [
    'nome' => ['Nome della sede', 120, 'organization'],
    'citta' => ['Città', 80, 'address-level2'],
    'indirizzo' => ['Indirizzo e numero civico', 160, 'address-line1'],
    'provincia' => ['Provincia', 2, 'address-level1'],
    'cap' => ['CAP', 5, 'postal-code'],
    'telefono' => ['Telefono', 30, 'tel'],
    'email' => ['Email', 160, 'email'],
];
?>
<h1>Sede di <?php echo e($sede['citta']); ?></h1>

<?php require __DIR__ . '/navigazione.php'; ?>

<?php if ($errori !== []): ?>
    <section class="avviso" role="alert" data-tipo="errore">
        <h2>Controlla questi campi</h2>
        <ul>
            <?php foreach ($errori as $campo => $testo): ?>
                <li><a href="#<?php echo e($campo); ?>"><?php echo e($testo); ?></a></li>
            <?php endforeach; ?>
        </ul>
    </section>
<?php endif; ?>

<section>
    <h2>Dati del locale</h2>

    <form method="post" action="<?php echo e(url('controllo-sede')); ?>" data-modulo="dati-sede">
        <?php echo campo_csrf(); ?>
        <input type="hidden" name="azione" value="dati" />
        <input type="hidden" name="sede_id" value="<?php echo (int) $sede['id']; ?>" />

        <fieldset>
            <legend>Indirizzo e recapiti</legend>

            <?php foreach ($campi as $campo => $dettagli): ?>
                <p>
                    <label for="<?php echo e($campo); ?>"><?php echo $campo === 'cap' ? '<abbr title="Codice di Avviamento Postale">CAP</abbr>' : e($dettagli[0]); ?></label>
                    <input type="<?php echo $campo === 'email' ? 'email' : ($campo === 'telefono' ? 'tel' : 'text'); ?>"
                        id="<?php echo e($campo); ?>" name="<?php echo e($campo); ?>"
                        required="required" maxlength="<?php echo (int) $dettagli[1]; ?>"
                        autocomplete="<?php echo e($dettagli[2]); ?>"
                        value="<?php echo e((string) ($valori[$campo] ?? '')); ?>"
                        <?php if (isset($errori[$campo])): ?>aria-describedby="errore-<?php echo e($campo); ?>" data-stato="errore"<?php endif; ?> />
                    <?php if (isset($errori[$campo])): ?>
                        <small id="errore-<?php echo e($campo); ?>"><?php echo e($errori[$campo]); ?></small>
                    <?php endif; ?>
                </p>
            <?php endforeach; ?>

            <p>
                <label for="note_ritiro">Note per il ritiro</label>
                <input type="text" id="note_ritiro" name="note_ritiro" maxlength="255"
                    aria-describedby="aiuto-note"
                    value="<?php echo e((string) ($valori['note_ritiro'] ?? '')); ?>" />
                <small id="aiuto-note">Dove si ritira l&apos;ordine dentro il locale.</small>
            </p>

            <p><button type="submit">Salva i dati</button></p>
        </fieldset>
    </form>
</section>

<?php require __DIR__ . '/sede-orari.php'; ?>

<section>
    <h2>Sala eventi</h2>

    <p>
        Stato:
        <span class="etichetta" data-tipo="<?php echo $salaAperta ? 'positivo' : 'attenzione'; ?>">
            <?php echo $salaAperta ? 'accetta prenotazioni' : 'non accetta prenotazioni'; ?>
        </span>
    </p>

    <form method="post" action="<?php echo e(url('controllo-sede')); ?>" data-modulo="sala">
        <?php echo campo_csrf(); ?>
        <input type="hidden" name="azione" value="sala" />
        <input type="hidden" name="sede_id" value="<?php echo (int) $sede['id']; ?>" />

        <p id="effetto-sala">
            <?php if ($salaAperta): ?>
                Chiudendo le prenotazioni la sala esce dall&apos;elenco di quelle prenotabili e
                il sito smette di accettare nuove richieste per questa sede. Le prenotazioni
                già ricevute restano dove sono e si gestiscono dalla sezione Prenotazioni:
                puoi riaprire quando vuoi.
            <?php else: ?>
                La sala non compare fra quelle prenotabili e il sito rifiuta le nuove
                richieste per questa sede. Riaprendo torna nell&apos;elenco e i clienti possono
                sceglierla di nuovo, senza che le prenotazioni già ricevute cambino stato.
            <?php endif; ?>
        </p>

        <p class="azioni">
            <button type="submit" aria-describedby="effetto-sala"
                <?php echo $salaAperta ? 'data-tipo="negativo"' : 'name="apri" value="1"'; ?>
                aria-pressed="<?php echo $salaAperta ? 'true' : 'false'; ?>">
                <?php echo $salaAperta ? 'Chiudi le prenotazioni' : 'Riapri le prenotazioni'; ?>
            </button>
        </p>
    </form>
</section>
