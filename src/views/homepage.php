<?php
/**
 * homepage.php: Contenuto dinamico per la pagina principale di SmashBurger.
 */
?>
<section class="welcome-section">
    <h2>I Nostri Burgers</h2>
    <p>Il miglior smash burger della città, preparato con ingredienti freschi e di alta qualità.</p>
    
    <div class="main-visual">
        <!-- Immagine di contenuto con attributo alt descrittivo come da specifiche -->
        <img src="styles/img/placeholder_burger.jpg" alt="Un succulento smash burger con formaggio fuso, lattuga e salsa speciale su un vassoio di legno" width="600" height="400">
    </div>

    <div class="features">
        <article>
            <h3>Cottura Smash</h3>
            <p>Carne pressata sulla piastra bollente per ottenere una crosticina perfetta.</p>
        </article>
        <article>
            <h3>Ingredienti Freschi</h3>
            <p>Solo prodotti selezionati dai nostri fornitori locali di fiducia.</p>
        </article>
    </div>

    <section class="reservation">
        <h3>Prenota un Tavolo</h3>
        <form action="index.php" method="POST">
            <fieldset>
                <legend>I tuoi dati</legend>
                <p>
                    <label for="name">Nome completo:</label>
                    <input type="text" id="name" name="name" required aria-required="true">
                </p>
                <p>
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" required aria-required="true">
                </p>
                <p>
                    <label for="message">Note per la prenotazione (campo testo libero richiesto):</label>
                    <textarea id="message" name="message" rows="4"></textarea>
                </p>
                <button type="submit" class="btn-primary">Invia Prenotazione</button>
            </fieldset>
        </form>
    </section>

    <p>Sito in fase di sviluppo con Apache e MariaDB.</p>
</section>
