<?php
/**
 * Modulo di contatto. Riceve $valori, $errori e $categorie dal controller.
 *
 * Gli errori sono riepilogati in cima e ripetuti accanto al campo, collegati con
 * aria-describedby: chi usa un lettore di schermo li sente insieme all'etichetta.
 */
?>
<section class="apertura-contatti">
    <div>
        <p class="occhiello">Parliamo chiaro</p>
        <h1>Contatti</h1>
        <p class="introduzione">
            Ordine, prenotazione o segnalazione: scrivici qui. Rispondiamo via email,
            di solito entro un giorno lavorativo.
        </p>
    </div>

    <aside class="contatti-diretti">
        <p class="indice-pannello">Contatto diretto</p>
        <p><span>Email</span><a href="mailto:<?php echo e(EMAIL_CONTATTO); ?>"><?php echo e(EMAIL_CONTATTO); ?></a></p>
        <p><span>Telefono</span><a href="tel:<?php echo e(str_replace(' ', '', TELEFONO_CONTATTO)); ?>"><?php echo e(TELEFONO_CONTATTO); ?></a></p>
        <p><span>Orari</span>Ogni giorno, 11:30 - 22:30</p>
    </aside>
</section>

<?php if ($errori !== []): ?>
    <section class="avviso" role="alert" tabindex="-1" data-tipo="errore">
        <h2>Controlla questi campi</h2>
        <ul>
            <?php foreach ($errori as $campo => $testo): ?>
                <li><a href="#<?php echo e($campo); ?>"><?php echo e($testo); ?></a></li>
            <?php endforeach; ?>
        </ul>
    </section>
<?php endif; ?>

<form class="modulo-contatto" method="post" action="<?php echo e(url('contatti')); ?>">
    <?php echo campo_csrf(); ?>

    <fieldset>
        <legend>Raccontaci di cosa hai bisogno</legend>

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
                minlength="10" maxlength="<?php echo CARATTERI_MESSAGGIO_CONTATTO; ?>"
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

<p class="nota-privacy">
    I dati che scrivi qui servono solo a risponderti: leggi la
    <a href="<?php echo e(url('privacy')); ?>">privacy policy</a>.
</p>
