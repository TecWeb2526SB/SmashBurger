<?php
/**
 * Chiusura di ogni pagina: fine del contenuto, footer con contatti e link di servizio.
 */
?>
    </main>

    <footer>
        <h2>Contatti</h2>
        <ul>
            <li>Scrivi a <a href="mailto:<?php echo e(EMAIL_SITO); ?>"><?php echo e(EMAIL_SITO); ?></a></li>
            <li>Telefono <a href="tel:+39<?php echo e(str_replace(' ', '', TELEFONO_SITO)); ?>"><?php echo e(TELEFONO_SITO); ?></a></li>
        </ul>

        <h2>Informazioni</h2>
        <ul>
            <li><a href="<?php echo e(url('privacy')); ?>">Privacy policy</a></li>
            <li><a href="<?php echo e(url('accessibilita')); ?>">Accessibilità</a></li>
            <li><a href="<?php echo e(url('mappa-sito')); ?>">Mappa del sito</a></li>
        </ul>

        <p><?php echo e(NOME_SITO); ?>, progetto per il corso di Tecnologie Web.</p>
    </footer>
</body>

</html>
