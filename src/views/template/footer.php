<?php
/**
 * footer.php: Frammento di codice per il piè di pagina comune di tutte le pagine.
 */
?>
</main>

<footer>
    <div class="contenitore">

        <!-- ORARI DI APERTURA -->
        <section aria-labelledby="titolo-orari">
            <h2 id="titolo-orari">Orari di apertura</h2>
            <table class="orari-tabella">
                <caption class="sr-only">Orari settimanali di apertura</caption>
                <tbody>
                    <tr>
                        <th scope="row">Lunedì – Venerdì</th>
                        <td>11:30 – 22:30</td>
                    </tr>
                    <tr>
                        <th scope="row">Sabato</th>
                        <td>11:00 – 23:30</td>
                    </tr>
                    <tr>
                        <th scope="row">Domenica</th>
                        <td>12:00 – 22:00</td>
                    </tr>
                </tbody>
            </table>
        </section>

        <!-- CONTATTI -->
        <section aria-labelledby="titolo-contatti">
            <h2 id="titolo-contatti">Contatti</h2>
            <address>
                <p>Email: <a href="mailto:info@smashburger.it">info&commat;smashburger.it</a></p>
                <p>Telefono: <a href="tel:+390123456789">+39 0123 456 789</a></p>
            </address>
        </section>

        <!-- INDIRIZZO -->
        <section aria-labelledby="titolo-indirizzo">
            <h2 id="titolo-indirizzo">Indirizzo</h2>
            <address>
                <p>Via Roma 42</p>
                <p>35100 Padova (PD)</p>
                <p>
                    <a href="sedi.php">Tutte le sedi &rarr;</a>
                </p>
            </address>
        </section>

        <!-- SEGUICI -->
        <section aria-labelledby="titolo-social">
            <h2 id="titolo-social">Seguici</h2>
            <p>
                <a href="https://instagram.com/smashburgeroriginal" rel="noopener noreferrer" target="_blank"
                    aria-label="Instagram di Smash Burger (apre in nuova scheda)">
                    Instagram
                </a>
            </p>
        </section>

        <!-- BARRA INFERIORE -->
        <div class="footer-basso">
            <p>&copy; <?php echo date('Y'); ?> Smash Burger Original &mdash; P.IVA 12345678901</p>

            <nav aria-label="Informazioni legali">
                <ul>
                    <li><a href="policy.php">Privacy Policy</a></li>
                    <li><a href="mappa.php">Mappa del sito</a></li>
                    <li><a href="accessibilita.php">Accessibilità</a></li>
                </ul>
            </nav>

            <div class="w3c-badges">
                <img src="images/w3chtml.png" alt="HTML5 Valido" width="88" height="31">
                <img src="images/w3ccss.png" alt="CSS Valido" width="88" height="31">
            </div>
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