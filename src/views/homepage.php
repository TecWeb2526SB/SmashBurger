<?php
/**
 * homepage.php: Contenuto dinamico per la pagina principale di SmashBurger.
 */
?>

<!-- HERO -->
<section id="hero" aria-labelledby="titolo-hero">
    <div class="contenitore">
        <h2 id="titolo-hero">L&apos;arte dello Smash Burger</h2>
        <p>Non il solito hamburger. Una tecnica di cottura precisa per una reazione
            di Maillard perfetta. Croccantezza esterna e succulenza interna garantite.</p>
        <a href="prodotti.php" class="bottone-primario">Scopri il menu</a>
        <figure>
            <img src="images/hero-burger.webp" alt="Double Smash Cheese con crosticina croccante e formaggio fuso"
                width="800" height="450">
            <figcaption>Il nostro iconico Double Smash Cheese</figcaption>
        </figure>
    </div>
</section>

<!-- PROMOZIONI -->
<section id="promozioni" aria-labelledby="titolo-promozioni">
    <div class="contenitore">
        <h2 id="titolo-promozioni">Offerte del mese</h2>

        <article aria-labelledby="titolo-studente">
            <h3 id="titolo-studente">Menu Studente</h3>
            <p>Mostra il tuo badge in cassa o inserisci il codice online per avere
                uno sconto del 15&percnt; sui menu combo.</p>
        </article>

        <article aria-labelledby="titolo-famiglia">
            <h3 id="titolo-famiglia">Combo Famiglia</h3>
            <p>Quattro Smash Burger classici e due porzioni di patatine extra large
                a un prezzo speciale.</p>
        </article>
    </div>
</section>

<!-- ASIDE ordini recenti (condizionale su $_SESSION) -->
<aside aria-labelledby="titolo-ordini">
    <div class="contenitore">
        <h2 id="titolo-ordini">Ultimi ordini</h2>
        <p>Entra nella tua
            <a href="area_personale.php">area personale</a>
            per ripetere l&apos;ultimo ordine con un solo click.
        </p>
    </div>
</aside>