<?php
/**
 * Modulo di contatto. Riceve $valori, $errori e $categorie dal controller.
 *
 * Gli errori sono riepilogati in cima e ripetuti accanto al campo, collegati con
 * aria-describedby: chi usa un lettore di schermo li sente insieme all'etichetta.
 */
?>
<h1>Contatti</h1>

<p>
    Per un ordine, una prenotazione o una segnalazione scrivici da qui. Rispondiamo via
    email, di solito entro un giorno lavorativo.
</p>

<p>
    Puoi anche scriverci a <a href="mailto:<?php echo e(EMAIL_CONTATTO); ?>"><?php echo e(EMAIL_CONTATTO); ?></a>
    o telefonare al <a href="tel:<?php echo e(str_replace(' ', '', TELEFONO_CONTATTO)); ?>"><?php echo e(TELEFONO_CONTATTO); ?></a>.
</p>

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

<form method="post" action="<?php echo e(url('contatti')); ?>">
    <?php echo campo_csrf(); ?>

    <fieldset>
        <legend>Il tuo messaggio</legend>

        <p>
            <label for="nome">Nome</label>
            <input type="text" id="nome" name="nome" required="required"
                minlength="2" maxlength="120" autocomplete="name"
                value="<?php echo e($valori['nome']); ?>"
                <?php if (isset($errori['nome'])): ?>
                    aria-describedby="errore-nome" data-stato="errore"
                <?php endif; ?> />
            <?php if (isset($errori['nome'])): ?>
                <small id="errore-nome"><?php echo e($errori['nome']); ?></small>
            <?php endif; ?>
        </p>

        <p>
            <label for="email">Email</label>
            <input type="email" id="email" name="email" required="required"
                maxlength="160" autocomplete="email"
                value="<?php echo e($valori['email']); ?>"
                <?php if (isset($errori['email'])): ?>
                    aria-describedby="errore-email" data-stato="errore"
                <?php endif; ?> />
            <?php if (isset($errori['email'])): ?>
                <small id="errore-email"><?php echo e($errori['email']); ?></small>
            <?php endif; ?>
        </p>

        <p>
            <label for="categoria">Di che cosa si tratta</label>
            <select id="categoria" name="categoria" required="required"
                <?php if (isset($errori['categoria'])): ?>
                    aria-describedby="errore-categoria" data-stato="errore"
                <?php endif; ?>>
                <option value="">Scegli un argomento</option>
                <?php foreach ($categorie as $valore => $etichetta): ?>
                    <option value="<?php echo e($valore); ?>"
                        <?php echo $valore === $valori['categoria'] ? 'selected="selected"' : ''; ?>>
                        <?php echo e($etichetta); ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <?php if (isset($errori['categoria'])): ?>
                <small id="errore-categoria"><?php echo e($errori['categoria']); ?></small>
            <?php endif; ?>
        </p>

        <p>
            <label for="testo">Messaggio</label>
            <textarea id="testo" name="testo" rows="6" required="required"
                minlength="10" maxlength="2000"
                <?php if (isset($errori['testo'])): ?>
                    aria-describedby="errore-testo" data-stato="errore"
                <?php endif; ?>><?php echo e($valori['testo']); ?></textarea>
            <?php if (isset($errori['testo'])): ?>
                <small id="errore-testo"><?php echo e($errori['testo']); ?></small>
            <?php endif; ?>
        </p>

        <p><button type="submit">Invia il messaggio</button></p>
    </fieldset>
</form>

<p>
    I dati che scrivi qui servono solo a risponderti: leggi la
    <a href="<?php echo e(url('privacy')); ?>">privacy policy</a>.
</p>
