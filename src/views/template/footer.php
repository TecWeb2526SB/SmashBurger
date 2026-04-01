<?php
/**
 * footer.php: Frammento di codice per il piè di pagina comune di tutte le pagine.
 */
$brandContacts = isset($pdo) ? brand_contact_get($pdo) : [];
$footerBranch = isset($pdo) ? branch_get_selected($pdo) : null;
$footerHours = $footerBranch['hours'] ?? [];

$brandEmail = (string) ($brandContacts['support_email'] ?? 'info@smashburger.it');
$brandInfoPhone = (string) ($brandContacts['info_phone'] ?? '+39 049 000 1099');
$brandOrderPhone = (string) ($brandContacts['order_phone'] ?? '+39 049 000 1000');
$brandInstagram = (string) ($brandContacts['instagram_url'] ?? 'https://instagram.com/smashburgeroriginal');
?>
</main>

<footer>
    <div class="contenitore">

        <!-- ORARI DI APERTURA -->
        <section aria-labelledby="titolo-orari">
            <h2 id="titolo-orari">Orari di apertura</h2>
            <?php $groupedHours = branch_hours_grouped($footerHours); ?>
            <ul class="orari-lista-footer">
                <?php if (!empty($groupedHours)): ?>
                    <?php foreach ($groupedHours as $row): ?>
                        <li>
                            <span class="giorni"><?php echo htmlspecialchars($row['days'], ENT_QUOTES, 'UTF-8'); ?>:</span>
                            <span class="ore"><?php echo htmlspecialchars($row['hours'], ENT_QUOTES, 'UTF-8'); ?></span>
                        </li>
                    <?php endforeach; ?>
                <?php else: ?>
                    <li>Orari non disponibili</li>
                <?php endif; ?>
            </ul>
            <?php if (!empty($footerBranch['name'])): ?>
                <p class="sede-footer-corrente">Sede: <strong><?php echo htmlspecialchars($footerBranch['name'], ENT_QUOTES, 'UTF-8'); ?></strong></p>
            <?php endif; ?>
        </section>

        <!-- CONTATTI -->
        <section aria-labelledby="titolo-contatti">
            <h2 id="titolo-contatti">Contatti</h2>
            <address>
                <p>Email brand: <a href="mailto:<?php echo htmlspecialchars($brandEmail, ENT_QUOTES, 'UTF-8'); ?>"><?php echo htmlspecialchars($brandEmail, ENT_QUOTES, 'UTF-8'); ?></a></p>
                <p>Info brand: <a href="tel:<?php echo htmlspecialchars(preg_replace('/[^0-9+]/', '', $brandInfoPhone), ENT_QUOTES, 'UTF-8'); ?>"><?php echo htmlspecialchars($brandInfoPhone, ENT_QUOTES, 'UTF-8'); ?></a></p>
                <p>Ordini brand: <a href="tel:<?php echo htmlspecialchars(preg_replace('/[^0-9+]/', '', $brandOrderPhone), ENT_QUOTES, 'UTF-8'); ?>"><?php echo htmlspecialchars($brandOrderPhone, ENT_QUOTES, 'UTF-8'); ?></a></p>
                <?php if (!empty($footerBranch['phone'])): ?>
                    <p>
                        Telefono sede:
                        <a href="tel:<?php echo htmlspecialchars(preg_replace('/[^0-9+]/', '', (string) $footerBranch['phone']), ENT_QUOTES, 'UTF-8'); ?>">
                            <?php echo htmlspecialchars((string) $footerBranch['phone'], ENT_QUOTES, 'UTF-8'); ?>
                        </a>
                    </p>
                <?php endif; ?>
            </address>
        </section>

        <!-- INDIRIZZO -->
        <section aria-labelledby="titolo-indirizzo">
            <h2 id="titolo-indirizzo">Indirizzo</h2>
            <address>
                <?php if (!empty($footerBranch)): ?>
                    <p><?php echo htmlspecialchars($footerBranch['address_line'], ENT_QUOTES, 'UTF-8'); ?></p>
                    <p>
                        <?php echo htmlspecialchars($footerBranch['postal_code'], ENT_QUOTES, 'UTF-8'); ?>
                        <?php echo htmlspecialchars($footerBranch['city'], ENT_QUOTES, 'UTF-8'); ?>
                        (<?php echo htmlspecialchars($footerBranch['province'], ENT_QUOTES, 'UTF-8'); ?>)
                    </p>
                    <p>
                        <a href="sedi.php?sede=<?php echo rawurlencode((string) $footerBranch['slug']); ?>">Dettagli sede attiva &rarr;</a>
                    </p>
                <?php else: ?>
                    <p>Sede non disponibile.</p>
                    <p><a href="sedi.php">Tutte le sedi &rarr;</a></p>
                <?php endif; ?>
            </address>
        </section>

        <!-- SEGUICI -->
        <section aria-labelledby="titolo-social">
            <h2 id="titolo-social">Seguici</h2>
            <p>
                <a href="<?php echo htmlspecialchars($brandInstagram, ENT_QUOTES, 'UTF-8'); ?>" rel="noopener noreferrer" target="_blank"
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
                <img src="images/w3chtml.png" alt="" aria-hidden="true" width="88" height="31">
                <img src="images/w3ccss.png" alt="" aria-hidden="true" width="88" height="31">
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

<button id="torna-su" type="button" aria-label="Torna all'inizio della pagina">
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
         width="18" height="18" fill="none" stroke="currentColor"
         stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"
         aria-hidden="true" focusable="false">
        <polyline points="18 15 12 9 6 15"/>
    </svg>
</button>

</body>

</html>
