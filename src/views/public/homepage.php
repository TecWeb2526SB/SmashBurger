<?php
/**
 * homepage.php: Contenuto dinamico per la pagina principale di SmashBurger.
 */

// Recupero ID reali dei best seller (assumo ID 1, 2, 3 siano Classic, Bacon, Onion)
// In un sistema reale verrebbero dal DB, qui manteniamo la struttura ma aggiungiamo i token
$csrfToken = csrf_token();
?>

<section id="hero" class="home-hero" aria-labelledby="titolo-hero">
    <div class="contenitore">
        <div class="home-hero-grid">
            <div class="hero-content">
                <span class="home-eyebrow">Autentico Gusto Americano</span>
                <h1 id="titolo-hero">Il vero Smash Burger.<br>Croccante, succoso, iconico.</h1>
                <p>Scegli la sede più vicina e ordina il tuo burger pressato al momento.</p>
                
                <div class="hero-cta">
                    <a href="prodotti" class="bottone-primario">Inizia l'ordine</a>
                    <a href="sedi" class="bottone-secondario">Trova sede</a>
                </div>

                <ul class="hero-kpi" aria-label="I nostri punti di forza">
                    <li>
                        <strong>Rapido 15 min</strong>
                        <span>Ritiro veloce</span>
                    </li>
                    <li>
                        <strong>Fresco 100% IT</strong>
                        <span>Carne selezionata</span>
                    </li>
                    <li>
                        <strong>Sicuro SSL</strong>
                        <span>Pagamento protetto</span>
                    </li>
                </ul>
            </div>
            
            <div class="hero-panel-wrap">
                <div class="hero-panel">
                    <h2>Ordinare è semplice</h2>
                    <p>Scegli i tuoi prodotti preferiti, indica l'orario di ritiro e paga in sicurezza online.</p>
                    <div class="hero-panel-note">
                        Disponibile in tutte le nostre <a href="sedi">sedi</a>.
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section id="best-seller" aria-labelledby="titolo-best-seller">
    <div class="contenitore">
        <h2 id="titolo-best-seller" class="home-section-title">I nostri Best Seller</h2>
        
        <div class="menu-preview-grid">
            <article class="menu-preview-card" aria-labelledby="best-1">
                <h3 id="best-1">Cheeseburger</h3>
                <p>Patty di carne, Cheddar, Cetriolini, Ketchup</p>
                <span class="prezzo">10,90 EUR</span>
                
                <?php if (is_logged_in()): ?>
                    <form action="carrello" method="POST" class="scheda-prodotto-azioni">
                        <input type="hidden" name="action" value="add_product">
                        <input type="hidden" name="product_id" value="1">
                        <input type="hidden" name="quantity" value="1">
                        <input type="hidden" name="csrf_token" value="<?php echo $csrfToken; ?>">
                        <input type="hidden" name="redirect_to" value="./">
                        <button type="submit" class="bottone-primario">Aggiungi</button>
                    </form>
                <?php endif; ?>
            </article>

            <article class="menu-preview-card" aria-labelledby="best-2">
                <h3 id="best-2">Bacon Burger</h3>
                <p>Patty di carne, Bacon, Cheddar, Salsa al bacon</p>
                <span class="prezzo">12,50 EUR</span>
                
                <?php if (is_logged_in()): ?>
                    <form action="carrello" method="POST" class="scheda-prodotto-azioni">
                        <input type="hidden" name="action" value="add_product">
                        <input type="hidden" name="product_id" value="2">
                        <input type="hidden" name="quantity" value="1">
                        <input type="hidden" name="csrf_token" value="<?php echo $csrfToken; ?>">
                        <input type="hidden" name="redirect_to" value="./">
                        <button type="submit" class="bottone-primario">Aggiungi</button>
                    </form>
                <?php endif; ?>
            </article>

            <article class="menu-preview-card" aria-labelledby="best-3">
                <h3 id="best-3">IN-N-OUT</h3>
                <p>Patty di carne, cipolla cruda, Insalata, Pomodoro, Cheddar</p>
                <span class="prezzo">11,50 EUR</span>
                
                <?php if (is_logged_in()): ?>
                    <form action="carrello" method="POST" class="scheda-prodotto-azioni">
                        <input type="hidden" name="action" value="add_product">
                        <input type="hidden" name="product_id" value="3">
                        <input type="hidden" name="quantity" value="1">
                        <input type="hidden" name="csrf_token" value="<?php echo $csrfToken; ?>">
                        <input type="hidden" name="redirect_to" value="./">
                        <button type="submit" class="bottone-primario">Aggiungi</button>
                    </form>
                <?php endif; ?>
            </article>
        </div>
        
        <p class="home-bottom-cta">
            <a href="prodotti" class="bottone-secondario">Vedi il catalogo completo</a>
        </p>
    </div>
</section>

<section id="promozioni" aria-labelledby="titolo-promozioni">
    <div class="contenitore">
        <h2 id="titolo-promozioni" class="home-section-title">Offerte del mese</h2>
        <div class="offer-grid">
            <article class="offer-card" aria-labelledby="titolo-studente">
                <h3 id="titolo-studente">Menu Studente</h3>
                <p>Burger classico, patatine e bevanda con sconto 15% mostrando badge universitario.</p>
                <p class="offer-note">Valido dal lunedì al venerdì, fascia pranzo.</p>
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
                <p>Scegli fascia oraria e modalità di ritiro, con riepilogo completo prima del pagamento.</p>
            </li>
            <li class="step-card">
                <strong>Step 3</strong>
                <h3>Conferma il pagamento</h3>
                <p>Concludi l'ordine in sicurezza e segui gli aggiornamenti direttamente dalla tua area personale.</p>
            </li>
        </ol>
    </div>
</section>

<aside class="home-closing-cta" aria-labelledby="titolo-ordini">
    <div class="contenitore">
        <h2 id="titolo-ordini">Hai già ordinato con noi?</h2>
        <p>
            Accedi alla tua <a href="account">area personale</a> per ritrovare lo storico ordini,
            ripetere un acquisto e gestire il checkout in meno tempo.
        </p>
    </div>
</aside>
