<?php
/**
 * footer.php: Frammento di codice per il piè di pagina comune di tutte le pagine.
 */
?>
</main>

<footer>
    <div class="contenitore">

        <section aria-labelledby="titolo-contatti">
            <h2 id="titolo-contatti">Contatti</h2>
            <address>
                <p>Email: <a href="mailto:info@smashburger.it">info&commat;smashburger.it</a></p>
                <p>Telefono: <a href="tel:+390123456789">+39 0123 456 789</a></p>
            </address>
        </section>

        <section aria-labelledby="titolo-social">
            <h2 id="titolo-social">Seguici</h2>
            <p>
                <a href="https://instagram.com/smashburgeroriginal" rel="noopener noreferrer" target="_blank"
                    aria-label="Instagram di Smash Burger (apre in nuova scheda)">
                    Instagram
                </a>
            </p>
        </section>

        <div class="footer-basso">
            <p>&copy; <?php echo date('Y'); ?> Smash Burger Original &mdash; P.IVA 12345678901</p>
            <nav aria-label="Informazioni legali">
                <ul>
                    <li><a href="policy.php">Privacy Policy</a></li>
                    <li><a href="mappa.php">Mappa del sito</a></li>
                </ul>
            </nav>
        </div>

    </div>
</footer>

<?php
$vjs = file_exists(__DIR__ . '/../../scripts/main.js')
    ? filemtime(__DIR__ . '/../../scripts/main.js')
    : time();
?>
<script src="scripts/main.js?v=<?php echo $vjs; ?>" defer></script>

</body>

</html>