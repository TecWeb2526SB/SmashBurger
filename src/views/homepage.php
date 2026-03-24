<?php
/**
 * homepage.php: Contenuto dinamico per la pagina principale di SmashBurger.
 */
?>

<section id="hero" class="home-hero" aria-labelledby="titolo-hero">
    <div class="contenitore home-hero-grid">
        <div>
            <p class="home-eyebrow">
                Smash Burger Original -
                <?php echo htmlspecialchars($selectedBranch['city'] ?? 'Sede principale', ENT_QUOTES, 'UTF-8'); ?>
            </p>
            <h2 id="titolo-hero">Smash fatto bene, servizio veloce, gusto costante.</h2>
            <p>
                Ogni burger viene pressato al momento su piastra rovente per creare la classica crosticina
                esterna e mantenere la carne succosa all'interno. Ordini online, ritiri in sede e paghi in pochi passaggi.
            </p>
            <p>
                Lavoriamo con una cucina snella e standard tecnici chiari: stessi tempi, stessa qualita e stesso
                risultato, anche nei picchi serali.
            </p>

            <div class="hero-cta">
                <a href="prodotti.php" class="bottone-primario">Scopri il menu</a>
                <a href="carrello.php" class="bottone-secondario">Vai al carrello</a>
            </div>

            <ul class="hero-kpi" aria-label="Punti chiave del servizio">
                <li><strong>10-15 min</strong> tempo medio preparazione</li>
                <li><strong>Ingredienti freschi</strong> preparazione espressa</li>
                <li><strong>4.8/5</strong> media recensioni locali</li>
            </ul>
        </div>

        <aside class="hero-panel" aria-labelledby="titolo-panel-hero">
            <h3 id="titolo-panel-hero">
                Orari
                <?php if (!empty($selectedBranch['city'])): ?>
                    sede di <?php echo htmlspecialchars($selectedBranch['city'], ENT_QUOTES, 'UTF-8'); ?>
                <?php endif; ?>
            </h3>
            <p><?php echo htmlspecialchars($selectedBranch['hours_compact'] ?? 'Orari non disponibili', ENT_QUOTES, 'UTF-8'); ?></p>
            <p><strong>Servizi attivi:</strong> asporto e ritiro in sede</p>
            <p class="hero-panel-note">Ritiro disponibile fino a 15 minuti prima della chiusura.</p>
            <a href="sedi.php<?php echo !empty($selectedBranch['slug']) ? '?sede=' . rawurlencode((string) $selectedBranch['slug']) : ''; ?>">
                Vedi sedi e contatti
            </a>
        </aside>
    </div>
</section>

<section id="punti-forza" aria-labelledby="titolo-punti-forza">
    <div class="contenitore">
        <h2 id="titolo-punti-forza" class="home-section-title">Perche scegliere Smash Burger</h2>
        <div class="feature-grid">
            <article class="feature-card" aria-labelledby="forza-1">
                <h3 id="forza-1">Cottura tecnica</h3>
                <p>Pressatura controllata e tostatura uniforme per un profilo di gusto intenso e pulito.</p>
            </article>
            <article class="feature-card" aria-labelledby="forza-2">
                <h3 id="forza-2">Menu bilanciato</h3>
                <p>Scelta ampia tra burger, contorni e bevande con allergeni sempre visibili in scheda prodotto.</p>
            </article>
            <article class="feature-card" aria-labelledby="forza-3">
                <h3 id="forza-3">Ordine semplice</h3>
                <p>Account personale, un solo carrello attivo e checkout rapido allineato alla sede selezionata.</p>
            </article>
        </div>
    </div>
</section>

<section id="promozioni" aria-labelledby="titolo-promozioni">
    <div class="contenitore">
        <h2 id="titolo-promozioni" class="home-section-title">Offerte del mese</h2>
        <div class="offer-grid">
            <article class="offer-card" aria-labelledby="titolo-studente">
                <h3 id="titolo-studente">Menu Studente</h3>
                <p>Burger classico, patatine e bevanda con sconto 15% mostrando badge universitario.</p>
                <p class="offer-note">Valido dal lunedi al venerdi, fascia pranzo.</p>
            </article>
            <article class="offer-card" aria-labelledby="titolo-famiglia">
                <h3 id="titolo-famiglia">Combo Famiglia</h3>
                <p>Quattro burger classici + due porzioni grandi di patatine a prezzo agevolato.</p>
                <p class="offer-note">Ideale per 3-4 persone.</p>
            </article>
            <article class="offer-card" aria-labelledby="titolo-bundle">
                <h3 id="titolo-bundle">Bundle Serata</h3>
                <p>Due burger premium + onion rings + due birre artigianali a tariffa ridotta.</p>
                <p class="offer-note">Disponibile tutti i giorni dopo le 18:30.</p>
            </article>
        </div>
    </div>
</section>

<section id="come-ordinare" aria-labelledby="titolo-come-ordinare">
    <div class="contenitore">
        <h2 id="titolo-come-ordinare" class="home-section-title">Come ordinare in meno di 2 minuti</h2>
        <ol class="steps-grid">
            <li class="step-card">
                <strong>Step 1</strong>
                <h3>Crea il tuo ordine</h3>
                <p>Seleziona burger, contorni e bevande dal catalogo con prezzi aggiornati in tempo reale.</p>
            </li>
            <li class="step-card">
                <strong>Step 2</strong>
                <h3>Conferma ritiro</h3>
                <p>Scegli fascia oraria e modalita di ritiro, con riepilogo completo prima del pagamento.</p>
            </li>
            <li class="step-card">
                <strong>Step 3</strong>
                <h3>Conferma il pagamento</h3>
                <p>Concludi l ordine in sicurezza e segui gli aggiornamenti direttamente dalla tua area personale.</p>
            </li>
        </ol>
    </div>
</section>

<section id="best-seller" aria-labelledby="titolo-best-seller">
    <div class="contenitore">
        <h2 id="titolo-best-seller" class="home-section-title">I piu ordinati</h2>
        <div class="menu-preview-grid">
            <article class="menu-preview-card" aria-labelledby="best-1">
                <h3 id="best-1">Classic Smash</h3>
                <p>Doppio smash, cheddar, cipolla, salsa signature.</p>
                <p class="prezzo">10,90 EUR</p>
            </article>
            <article class="menu-preview-card" aria-labelledby="best-2">
                <h3 id="best-2">Bacon Smash</h3>
                <p>Doppio smash con bacon croccante e cheddar.</p>
                <p class="prezzo">12,50 EUR</p>
            </article>
            <article class="menu-preview-card" aria-labelledby="best-3">
                <h3 id="best-3">Onion Rings</h3>
                <p>Anelli di cipolla croccanti con salsa BBQ.</p>
                <p class="prezzo">4,50 EUR</p>
            </article>
        </div>
        <p class="home-bottom-cta">
            <a href="prodotti.php">Vedi il catalogo completo</a>
        </p>
    </div>
</section>

<aside class="home-closing-cta" aria-labelledby="titolo-ordini">
    <div class="contenitore">
        <h2 id="titolo-ordini">Hai gia ordinato con noi?</h2>
        <p>
            Accedi alla tua <a href="area_personale.php">area personale</a> per ritrovare lo storico ordini,
            ripetere un acquisto e gestire il checkout in meno tempo.
        </p>
    </div>
</aside>
