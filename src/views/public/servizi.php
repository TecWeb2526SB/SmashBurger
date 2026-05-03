<?php
/**
 * servizi: View pagina servizi.
 *
 * Variabili attese:
 *   $selectedBranch ?array
 *   $brandContacts  array
 *   $allBranches    array
 */
?>

<section id="servizi-intro" aria-labelledby="titolo-servizi">
    <div class="contenitore">
        <h1 id="titolo-servizi">Servizi</h1>
        <p>
            Dalla scelta del menu al ritiro in sede, il flusso è progettato per essere rapido e chiaro:
            un solo carrello attivo, prodotti coerenti con la sede selezionata e aggiornamenti ordine in tempo reale.
        </p>
        <?php if (!empty($selectedBranch)): ?>
            <p>
                Sede attiva: <strong><?php echo e($selectedBranch['name']); ?></strong>.
            </p>
        <?php endif; ?>
    </div>
</section>

<section id="servizi-offerti" aria-labelledby="titolo-servizi-offerti">
    <div class="contenitore">
        <h2 id="titolo-servizi-offerti" class="home-section-title">Cosa puoi fare online</h2>
        <div class="feature-grid">
            <article class="feature-card" aria-labelledby="servizio-1">
                <h3 id="servizio-1">Ordine da menu dinamico</h3>
                <p>Catalogo e disponibilità aggiornati in base alla sede selezionata.</p>
            </article>
            <article class="feature-card" aria-labelledby="servizio-2">
                <h3 id="servizio-2">Asporto e ritiro in sede</h3>
                <p>Scegli modalità e fascia oraria al checkout con riepilogo completo prima della conferma.</p>
            </article>
            <article class="feature-card" aria-labelledby="servizio-3">
                <h3 id="servizio-3">Storico ordini personale</h3>
                <p>Rivedi ordini, importi e sede di riferimento direttamente dalla tua area personale.</p>
            </article>
        </div>
    </div>
</section>

<section id="servizi-standard" aria-labelledby="titolo-standard">
    <div class="contenitore">
        <h2 id="titolo-standard" class="home-section-title">Standard operativi</h2>
        <div class="steps-grid">
            <article class="step-card" aria-labelledby="standard-1">
                <strong>Standard 1</strong>
                <h3 id="standard-1">Un carrello per volta</h3>
                <p>Ogni account mantiene un solo carrello attivo, sempre associato a una singola sede.</p>
            </article>
            <article class="step-card" aria-labelledby="standard-2">
                <strong>Standard 2</strong>
                <h3 id="standard-2">Prezzo congelato in carrello</h3>
                <p>I prezzi dei prodotti vengono mantenuti nel carrello e riportati in ordine al momento della conferma.</p>
            </article>
            <article class="step-card" aria-labelledby="standard-3">
                <strong>Standard 3</strong>
                <h3 id="standard-3">Tracciamento stato ordine</h3>
                <p>Gli stati passano da confermato a pronto/completato con storico sempre disponibile.</p>
            </article>
        </div>
    </div>
</section>

<section id="servizi-contatti" aria-labelledby="titolo-contatti-servizi">
    <div class="contenitore">
        <h2 id="titolo-contatti-servizi" class="home-section-title">Supporto clienti</h2>
        <div class="sede-dettaglio-card">
            <p>
                Email brand:
                <a href="mailto:<?php echo e($brandContacts['support_email'] ?? 'info@smashburger.it'); ?>">
                    <?php echo e($brandContacts['support_email'] ?? 'info@smashburger.it'); ?>
                </a>
            </p>
            <p>
                Numero informazioni:
                <a href="tel:<?php echo e(preg_replace('/[^0-9+]/', '', (string) ($brandContacts['info_phone'] ?? ''))); ?>">
                    <?php echo e($brandContacts['info_phone'] ?? ''); ?>
                </a>
            </p>
            <p>
                Numero ordini:
                <a href="tel:<?php echo e(preg_replace('/[^0-9+]/', '', (string) ($brandContacts['order_phone'] ?? ''))); ?>">
                    <?php echo e($brandContacts['order_phone'] ?? ''); ?>
                </a>
            </p>
            <?php if (!empty($selectedBranch)): ?>
                <p>
                    Contatto sede attiva:
                    <a href="tel:<?php echo e(preg_replace('/[^0-9+]/', '', (string) $selectedBranch['phone'])); ?>">
                        <?php echo e((string) $selectedBranch['phone']); ?>
                    </a>
                    -
                    <a href="mailto:<?php echo e((string) $selectedBranch['email']); ?>">
                        <?php echo e((string) $selectedBranch['email']); ?>
                    </a>
                </p>
            <?php endif; ?>
        </div>
    </div>
</section>
