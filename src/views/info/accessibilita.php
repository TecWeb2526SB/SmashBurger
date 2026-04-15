<?php
/**
 * accessibilita: View della dichiarazione di accessibilità.
 */

$accessibilityBrandName = (string) ($brandContacts['brand_name'] ?? 'Smash Burger Original');
$accessibilityEmail = (string) ($brandContacts['support_email'] ?? 'info@smashburger.it');
$accessibilityPhone = (string) ($brandContacts['info_phone'] ?? '+39 049 000 1099');
?>

<section aria-labelledby="titolo-accessibilita">
    <div class="contenitore">
        <div class="legal-shell">
            <div class="legal-intro">
                <p class="home-eyebrow">Accessibilità</p>
                <h1 id="titolo-accessibilita">Dichiarazione di accessibilità</h1>
                <p class="legal-intro-copy">
                    <?php echo htmlspecialchars($accessibilityBrandName, ENT_QUOTES, 'UTF-8'); ?> si impegna a rendere il proprio sito
                    accessibile e utilizzabile dal maggior numero possibile di persone, con attenzione a navigazione da tastiera,
                    compatibilità con tecnologie assistive, chiarezza semantica e leggibilità dei contenuti.
                </p>
                <p class="legal-meta">
                    Ultimo aggiornamento:
                    <strong><?php echo htmlspecialchars($accessibilityUpdatedAt ?? '1 aprile 2026', ENT_QUOTES, 'UTF-8'); ?></strong>
                </p>
            </div>

            <div class="legal-content">
                <article class="legal-card" id="sezione-stato">
                    <section class="legal-section" aria-labelledby="accessibilita-stato">
                        <h2 id="accessibilita-stato">1. Stato di conformità</h2>
                        <p>
                            Alla data di questo aggiornamento il sito è da considerarsi
                            <strong>parzialmente conforme</strong> ai requisiti tecnici di accessibilità ispirati alle
                            <strong>WCAG 2.1 livello AA</strong>.
                        </p>
                        <p>
                            La valutazione è basata su verifiche tecniche automatiche e controlli manuali sul markup e sui principali flussi dell'interfaccia.
                        </p>
                    </section>

                    <section class="legal-section legal-section-last" aria-labelledby="accessibilita-metodo">
                        <h2 id="accessibilita-metodo">2. Monitoraggio</h2>
                        <div class="legal-grid">
                            <div class="legal-item">
                                <h3>Validazione tecnica</h3>
                                <p>Controlli con validazione HTML, audit automatici e Lighthouse sulle pagine pubbliche.</p>
                            </div>
                            <div class="legal-item">
                                <h3>Controlli periodici</h3>
                                <p>Riesecuzione dei test in integrazione continua su home, prodotti, carrello e checkout.</p>
                            </div>
                            <div class="legal-item">
                                <h3>Obiettivo AA</h3>
                                <p>Focus su contrasto, semantica, navigazione da tastiera e feedback di errore.</p>
                            </div>
                            <div class="legal-item">
                                <h3>Miglioramento</h3>
                                <p>Documentiamo lo stato attuale e ci impegniamo in un percorso di correzione costante.</p>
                            </div>
                        </div>
                    </section>
                </article>

                <article class="legal-card" id="sezione-funzionalita">
                    <section class="legal-section" aria-labelledby="accessibilita-supporto">
                        <h2 id="accessibilita-supporto">3. Funzionalità supportate</h2>
                        <ul class="legal-list">
                            <li>Struttura semantica con titoli, landmark e breadcrumb;</li>
                            <li>Skip link per il contenuto principale;</li>
                            <li>Focus visibile e navigazione coerente da tastiera;</li>
                            <li>Etichette e messaggi di errore nei form;</li>
                            <li>Gestione accessibile di menu e modali.</li>
                        </ul>
                    </section>

                    <section class="legal-section legal-section-last" aria-labelledby="accessibilita-limiti">
                        <h2 id="accessibilita-limiti">4. Aspetti migliorabili</h2>
                        <p>Punti su cui stiamo lavorando:</p>
                        <ul class="legal-list">
                            <li>Target interattivi su touch per alcune pagine specifiche;</li>
                            <li>Verifiche manuali estese con diversi screen reader;</li>
                            <li>Manutenzione continua della conformità sui nuovi componenti.</li>
                        </ul>
                    </section>
                </article>

                <article class="legal-card" id="sezione-contatti">
                    <section class="legal-section" aria-labelledby="accessibilita-compatibilita">
                        <h2 id="accessibilita-compatibilita">5. Compatibilità</h2>
                        <p>
                            Il sito è ottimizzato per i browser moderni e le principali tecnologie assistive, con particolare attenzione
                            alla navigazione da tastiera nei flussi d'acquisto.
                        </p>
                    </section>

                    <section class="legal-section legal-section-last" aria-labelledby="accessibilita-feedback">
                        <h2 id="accessibilita-feedback">6. Segnalazioni</h2>
                        <p>
                            Se riscontri difficoltà, puoi segnalarle a
                            <a href="mailto:<?php echo htmlspecialchars($accessibilityEmail, ENT_QUOTES, 'UTF-8'); ?>">
                                <?php echo htmlspecialchars($accessibilityEmail, ENT_QUOTES, 'UTF-8'); ?>
                            </a>
                            oppure al numero
                            <a href="tel:<?php echo htmlspecialchars(preg_replace('/[^0-9+]/', '', $accessibilityPhone), ENT_QUOTES, 'UTF-8'); ?>">
                                <?php echo htmlspecialchars($accessibilityPhone, ENT_QUOTES, 'UTF-8'); ?>
                            </a>.
                        </p>
                    </section>
                </article>

                <article class="legal-card" id="sezione-riferimenti">
                    <section class="legal-section legal-section-last" aria-labelledby="accessibilita-riferimenti">
                        <h2 id="accessibilita-riferimenti">7. Riferimenti</h2>
                        <p>
                            Ispirato alle WCAG 2.1 AA e alle linee guida AgID per i servizi digitali al pubblico.
                        </p>
                        <ul class="legal-list">
                            <li>
                                <a href="https://www.w3.org/WAI/WCAG2AA-Conformance" rel="noopener noreferrer" target="_blank">
                                    W3C WCAG 2 Level AA
                                </a>
                            </li>
                            <li>
                                <a href="https://www.agid.gov.it" rel="noopener noreferrer" target="_blank">
                                    Linee guida AgID Accessibilità
                                </a>
                            </li>
                        </ul>
                    </section>
                </article>
            </div>
        </div>
    </div>
</section>
