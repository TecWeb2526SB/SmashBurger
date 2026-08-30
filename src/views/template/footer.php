<?php
/**
 * Chiusura di ogni pagina: fine del contenuto, piede con i collegamenti di servizio,
 * contatti e badge di validazione.
 */
?>
    </main>

    <footer>
        <nav aria-label="Collegamenti di servizio">
            <ul>
                <?php foreach (pagine_del_menu('piede', $ruoloCorrente) as $slug => $etichetta): ?>
                    <li><a href="<?php echo e(url($slug)); ?>"><?php echo e($etichetta); ?></a></li>
                <?php endforeach; ?>
            </ul>
        </nav>

        <address>
            <p>Scrivici a <a href="mailto:<?php echo e(EMAIL_CONTATTO); ?>"><?php echo e(EMAIL_CONTATTO); ?></a></p>
            <p>Telefono <a href="tel:<?php echo e(str_replace(' ', '', TELEFONO_CONTATTO)); ?>"><?php echo e(TELEFONO_CONTATTO); ?></a></p>
        </address>

        <p>
            <a href="https://validator.w3.org/">
                <img src="<?php echo e(risorsa('images/w3chtml.png')); ?>" width="88" height="31"
                    alt="Markup validato dal servizio del W3C" />
            </a>
            <a href="https://jigsaw.w3.org/css-validator/">
                <img src="<?php echo e(risorsa('images/w3ccss.png')); ?>" width="88" height="31"
                    alt="Fogli di stile validati dal servizio del W3C" />
            </a>
        </p>

        <p><small>Smash Burger, progetto del corso di Tecnologie Web.</small></p>
    </footer>

    <script src="<?php echo e(risorsa('scripts/script.js', true)); ?>" defer="defer"></script>
</body>

</html>
