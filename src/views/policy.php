<?php
/**
 * policy.php: View della pagina privacy policy.
 */

$policyBrandName = (string) ($brandContacts['brand_name'] ?? 'Smash Burger Original');
$policyEmail = (string) ($brandContacts['support_email'] ?? 'info@smashburger.it');
$policyInfoPhone = (string) ($brandContacts['info_phone'] ?? '+39 049 000 1099');
$policyOrderPhone = (string) ($brandContacts['order_phone'] ?? '+39 049 000 1000');
?>

<section aria-labelledby="titolo-policy">
    <div class="contenitore">
        <div class="legal-shell">
            <div class="legal-intro">
                <p class="home-eyebrow">Informativa privacy</p>
                <h1 id="titolo-policy">Privacy Policy</h1>
                <p class="legal-intro-copy">
                    Questa informativa descrive come <?php echo htmlspecialchars($policyBrandName, ENT_QUOTES, 'UTF-8'); ?>
                    tratta i dati personali raccolti attraverso il sito, l'area account, il carrello e il checkout.
                </p>
                <p class="legal-meta">
                    Ultimo aggiornamento:
                    <strong><?php echo htmlspecialchars($policyUpdatedAt ?? '1 aprile 2026', ENT_QUOTES, 'UTF-8'); ?></strong>
                </p>
            </div>

            <div class="legal-content">
                <article class="legal-card" id="sezione-identita">
                    <section class="legal-section" aria-labelledby="policy-titolare">
                        <h2 id="policy-titolare">1. Titolare del trattamento</h2>
                        <p>
                            Il titolare del trattamento è
                            <strong><?php echo htmlspecialchars($policyBrandName, ENT_QUOTES, 'UTF-8'); ?></strong>,
                            P.IVA 12345678901, contattabile ai seguenti recapiti:
                        </p>
                        <ul class="legal-list">
                            <li>
                                Email:
                                <a href="mailto:<?php echo htmlspecialchars($policyEmail, ENT_QUOTES, 'UTF-8'); ?>">
                                    <?php echo htmlspecialchars($policyEmail, ENT_QUOTES, 'UTF-8'); ?>
                                </a>
                            </li>
                            <li>
                                Telefono informazioni:
                                <a href="tel:<?php echo htmlspecialchars(preg_replace('/[^0-9+]/', '', $policyInfoPhone), ENT_QUOTES, 'UTF-8'); ?>">
                                    <?php echo htmlspecialchars($policyInfoPhone, ENT_QUOTES, 'UTF-8'); ?>
                                </a>
                            </li>
                            <li>
                                Telefono ordini:
                                <a href="tel:<?php echo htmlspecialchars(preg_replace('/[^0-9+]/', '', $policyOrderPhone), ENT_QUOTES, 'UTF-8'); ?>">
                                    <?php echo htmlspecialchars($policyOrderPhone, ENT_QUOTES, 'UTF-8'); ?>
                                </a>
                            </li>
                        </ul>
                    </section>

                    <section class="legal-section legal-section-last" aria-labelledby="policy-dati">
                        <h2 id="policy-dati">2. Quali dati trattiamo</h2>
                        <ul class="legal-list">
                            <li>Dati di navigazione: indirizzo IP, log tecnici, informazioni sul browser e sulla sessione necessari al funzionamento e alla sicurezza del sito.</li>
                            <li>Dati account: username, email, password cifrata, ruolo utente e informazioni tecniche di accesso.</li>
                            <li>Dati operativi dell'ordine: sede selezionata, contenuto del carrello, storico ordini, metodo di ritiro, data e fascia di ritiro, importi e stato dell'ordine.</li>
                            <li>Dati inseriti nel checkout: email PayPal oppure dati carta digitati nel modulo di pagamento, limitatamente al tempo necessario a completare il flusso di checkout.</li>
                            <li>Preferenze tecniche: tema chiaro/scuro salvato nel browser e sede attiva mantenuta nella sessione.</li>
                        </ul>
                    </section>
                </article>

                <article class="legal-card" id="sezione-finalita">
                    <section class="legal-section legal-section-last" aria-labelledby="policy-finalita">
                        <h2 id="policy-finalita">3. Finalità e base giuridica</h2>
                        <div class="legal-grid">
                            <div class="legal-item">
                                <h3>Creazione e gestione account</h3>
                                <p>Trattiamo username, email e password per registrarti, permetterti l'accesso, proteggere la sessione e consentirti di gestire il tuo profilo.</p>
                                <p><strong>Base giuridica:</strong> esecuzione di misure precontrattuali e del servizio richiesto.</p>
                            </div>
                            <div class="legal-item">
                                <h3>Carrello, checkout e ordini</h3>
                                <p>Usiamo i dati inseriti durante il percorso d'acquisto per comporre il carrello, confermare l'ordine, associare la sede corretta e conservare lo storico ordini nell'area personale.</p>
                                <p><strong>Base giuridica:</strong> esecuzione del contratto e adempimenti connessi al servizio.</p>
                            </div>
                            <div class="legal-item">
                                <h3>Sicurezza e assistenza</h3>
                                <p>Alcuni dati tecnici vengono trattati per prevenire accessi abusivi, applicare misure anti brute-force, gestire errori e fornire supporto agli utenti.</p>
                                <p><strong>Base giuridica:</strong> legittimo interesse del titolare alla sicurezza della piattaforma.</p>
                            </div>
                            <div class="legal-item">
                                <h3>Obblighi di legge</h3>
                                <p>Alcuni dati possono essere conservati o comunicati quando necessario per adempiere a obblighi normativi, fiscali, contabili o richieste dell'autorità.</p>
                                <p><strong>Base giuridica:</strong> obbligo legale.</p>
                            </div>
                        </div>
                    </section>
                </article>

                <article class="legal-card" id="sezione-gestione">
                    <section class="legal-section" aria-labelledby="policy-pagamenti">
                        <h2 id="policy-pagamenti">4. Dati di pagamento</h2>
                        <p>
                            I dati di pagamento inseriti nel modulo checkout sono utilizzati solo per completare il flusso di acquisto.
                            Nei sistemi del sito non viene memorizzato il numero completo della carta e non viene conservato il CVV.
                        </p>
                        <p>
                            L'eventuale email PayPal inserita nel checkout viene usata per gestire il pagamento scelto e non viene associata
                            in modo permanente al profilo utente se non per la gestione dell'ordine.
                        </p>
                    </section>

                    <section class="legal-section" aria-labelledby="policy-conservazione">
                        <h2 id="policy-conservazione">5. Tempi di conservazione</h2>
                        <ul class="legal-list">
                            <li>Dati account: fino alla cancellazione dell'account o per il tempo necessario a gestire il rapporto con l'utente.</li>
                            <li>Storico ordini e dati amministrativi: per il periodo necessario alla gestione del servizio e secondo i termini di legge fiscali.</li>
                            <li>Carrello attivo e sessione: per il tempo strettamente necessario all'utilizzo del sito.</li>
                        </ul>
                    </section>

                    <section class="legal-section legal-section-last" aria-labelledby="policy-destinatari">
                        <h2 id="policy-destinatari">6. Destinatari dei dati</h2>
                        <p>I dati possono essere trattati da:</p>
                        <ul class="legal-list">
                            <li>personale autorizzato dal titolare;</li>
                            <li>fornitori tecnici che ospitano o mantengono l'infrastruttura;</li>
                            <li>fornitori di servizi di pagamento;</li>
                            <li>autorità pubbliche, quando previsto dalla legge.</li>
                        </ul>
                    </section>
                </article>

                <article class="legal-card" id="sezione-tecnica">
                    <section class="legal-section" aria-labelledby="policy-cookie">
                        <h2 id="policy-cookie">7. Cookie e tecnologie simili</h2>
                        <p>
                            Il sito utilizza cookie tecnici strettamente necessari al funzionamento: autenticazione, sicurezza, gestione carrello e sede attiva.
                        </p>
                        <p>
                            Salviamo nel browser la preferenza del tema (chiaro/scuro) tramite <code>localStorage</code>.
                            Non utilizziamo cookie di profilazione o analytics di terze parti.
                        </p>
                    </section>

                    <section class="legal-section legal-section-last" aria-labelledby="policy-sicurezza">
                        <h2 id="policy-sicurezza">8. Sicurezza del trattamento</h2>
                        <p>
                            Adottiamo misure per prevenire accessi non autorizzati: hashing delle password, protezione delle sessioni,
                            validazione dei dati e limitazione dei tentativi di accesso.
                        </p>
                    </section>
                </article>

                <article class="legal-card" id="sezione-diritti">
                    <section class="legal-section" aria-labelledby="policy-diritti">
                        <h2 id="policy-diritti">9. Diritti dell'interessato</h2>
                        <p>
                            Puoi chiedere l'accesso ai tuoi dati, la rettifica, la cancellazione o la limitazione del trattamento.
                            Hai inoltre il diritto di proporre reclamo al Garante Privacy.
                        </p>
                    </section>

                    <section class="legal-section legal-section-last" aria-labelledby="policy-contatti">
                        <h2 id="policy-contatti">10. Esercizio dei diritti</h2>
                        <p>
                            Per richieste relative alla privacy scrivi a
                            <a href="mailto:<?php echo htmlspecialchars($policyEmail, ENT_QUOTES, 'UTF-8'); ?>">
                                <?php echo htmlspecialchars($policyEmail, ENT_QUOTES, 'UTF-8'); ?>
                            </a>
                            oppure chiama il numero
                            <a href="tel:<?php echo htmlspecialchars(preg_replace('/[^0-9+]/', '', $policyInfoPhone), ENT_QUOTES, 'UTF-8'); ?>">
                                <?php echo htmlspecialchars($policyInfoPhone, ENT_QUOTES, 'UTF-8'); ?>
                            </a>.
                        </p>
                    </section>
                </article>
            </div>
        </div>
    </div>
</section>
