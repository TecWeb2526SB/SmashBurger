<?php
/**
 * Chiusura di ogni pagina: fine del contenuto, footer con i collegamenti di servizio,
 * contatti e badge di validazione.
 *
 * Il piede di pagina non riporta un orario di apertura: ogni sede ha il proprio e lo
 * cambia dal pannello, quindi un orario unico qui sarebbe sbagliato per qualcuno. Al suo
 * posto c'è il collegamento all'elenco delle sedi, dove gli orari sono quelli veri.
 */
?>
    </main>

    <a class="torna-su" href="#inizio" title="Torna su">
        <?php echo icona('freccia-su'); ?>
        <span class="solo-lettori">Torna su</span>
    </a>

    <footer>
        <div class="footer-principale">
            <section class="firma-footer">
                <p class="marchio-footer"><?php echo e(NOME_SITO); ?></p>
                <h2>La crosta fa il rumore. Il resto lo senti al primo morso.</h2>
                <p>Quattro sedi, una sola regola: ogni burger si schiaccia quando lo ordini.</p>
            </section>

            <nav aria-label="Collegamenti di servizio">
                <h2>Esplora</h2>
                <ul>
                    <?php foreach (pagine_del_menu('footer', $ruoloCorrente) as $slug => $etichetta): ?>
                        <li><a href="<?php echo e(url($slug)); ?>"><?php echo e($etichetta); ?></a></li>
                    <?php endforeach; ?>
                </ul>
            </nav>

            <section class="recapiti-footer">
                <h2>Parliamone</h2>
                <address>
                <p><a href="mailto:<?php echo e(EMAIL_CONTATTO); ?>"><?php echo e(EMAIL_CONTATTO); ?></a></p>
                <p><a href="tel:<?php echo e(str_replace(' ', '', TELEFONO_CONTATTO)); ?>"><?php echo e(TELEFONO_CONTATTO); ?></a></p>
                <p><a href="<?php echo e(url('sedi')); ?>">Orari di ogni sede</a></p>
                </address>
            </section>
        </div>

        <div class="footer-finale">
            <p><small>Smash Burger, progetto del corso di Tecnologie Web.</small></p>
            <p class="validazioni">
                <a href="https://validator.w3.org/">
                    <img src="<?php echo e(risorsa('images/w3chtml.webp')); ?>" width="88" height="31"
                        loading="lazy" alt="Markup validato dal servizio del W3C" />
                </a>
                <a href="https://jigsaw.w3.org/css-validator/">
                    <img src="<?php echo e(risorsa('images/w3ccss.webp')); ?>" width="88" height="31"
                        loading="lazy" alt="Fogli di stile validati dal servizio del W3C" />
                </a>
            </p>
        </div>
    </footer>
    </div>

    <script src="<?php echo e(risorsa('scripts/script.js', true)); ?>" defer="defer"></script>
</body>

</html>
