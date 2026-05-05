<?php
/**
 * footer.php: Frammento di codice per il piè di pagina comune di tutte le pagine.
 */
global $pdo;

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
                            <span class="giorni"><?php echo e($row['days']); ?>:</span>
                            <span class="ore"><?php echo e($row['hours']); ?></span>
                        </li>
                    <?php endforeach; ?>
                <?php else: ?>
                    <li>Orari non disponibili</li>
                <?php endif; ?>
            </ul>
            <?php if (!empty($footerBranch['name'])): ?>
                <p class="sede-footer-corrente">Sede: <strong><?php echo e($footerBranch['name']); ?></strong></p>
            <?php endif; ?>
        </section>

        <!-- CONTATTI -->
        <section aria-labelledby="titolo-contatti">
            <h2 id="titolo-contatti">Contatti</h2>
            <address>
                <p>Email brand: <a href="mailto:<?php echo e($brandEmail); ?>"><?php echo e($brandEmail); ?></a></p>
                <p>Info brand: <a href="tel:<?php echo e(preg_replace('/[^0-9+]/', '', $brandInfoPhone)); ?>"><?php echo e($brandInfoPhone); ?></a></p>
                <p>Ordini brand: <a href="tel:<?php echo e(preg_replace('/[^0-9+]/', '', $brandOrderPhone)); ?>"><?php echo e($brandOrderPhone); ?></a></p>
                <?php if (!empty($footerBranch['phone'])): ?>
                    <p>
                        Telefono sede:
                        <a href="tel:<?php echo e(preg_replace('/[^0-9+]/', '', (string) $footerBranch['phone'])); ?>">
                            <?php echo e((string) $footerBranch['phone']); ?>
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
                    <p><?php echo e($footerBranch['address_line']); ?></p>
                    <p>
                        <?php echo e($footerBranch['postal_code']); ?>
                        <?php echo e($footerBranch['city']); ?>
                        (<?php echo e($footerBranch['province']); ?>)
                    </p>
                    <p>
                        <a href="sedi?sede=<?php echo rawurlencode((string) $footerBranch['slug']); ?>">Dettagli sede attiva &#8594;</a>
                    </p>
                <?php else: ?>
                    <p>Sede non disponibile.</p>
                    <p><a href="sedi">Tutte le sedi &#8594;</a></p>
                <?php endif; ?>
            </address>
        </section>

        <!-- SEGUICI -->
        <section aria-labelledby="titolo-social">
            <h2 id="titolo-social">Seguici</h2>
            <p>
                <a href="<?php echo e($brandInstagram); ?>" rel="noopener noreferrer" target="_blank"
                    aria-label="Instagram di Smash Burger (apre in nuova scheda)">
                    Instagram
                </a>
            </p>
        </section>

        <!-- BARRA INFERIORE -->
        <div class="footer-basso">
            <p>&#169; <?php echo date('Y'); ?> Smash Burger Original &#8212; P.IVA 12345678901</p>

            <nav aria-label="Informazioni legali">
                <ul>
                    <li><a href="privacy">Privacy Policy</a></li>
                    <li><a href="mappa-sito">Mappa del sito</a></li>
                    <li><a href="accessibilita">Accessibilità</a></li>
                </ul>
            </nav>

            <div class="w3c-badges">
                <img src="images/w3chtml.png" alt="" aria-hidden="true" width="88" height="31" />
                <img src="images/w3ccss.png" alt="" aria-hidden="true" width="88" height="31" />
            </div>
        </div>

    </div>
</footer>

<?php
$vjs = file_exists(__DIR__ . '/../../scripts/main.js')
    ? filemtime(__DIR__ . '/../../scripts/main.js')
    : time();
?>
<script src="scripts/main.js?v=<?php echo $vjs; ?>" defer="defer"></script>

<button id="torna-su" type="button" aria-label="Torna all'inizio della pagina">
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
         width="18" height="18" fill="none" stroke="currentColor"
         stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"
         focusable="false">
        <polyline points="18 15 12 9 6 15" />
    </svg>
</button>

</body>

</html>
