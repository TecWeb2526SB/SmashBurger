<?php
/**
 * Sitemap XML delle pagine pubbliche.
 *
 * L'elenco arriva da pagine_pubbliche(), cioè dalle pagine senza vincolo di ruolo che
 * hanno una descrizione: la stessa fonte che alimenta la mappa del sito leggibile. Non
 * c'è un secondo elenco da tenere allineato a mano.
 *
 * Gli indirizzi sono assoluti perchè lo chiede la specifica delle sitemap, e si ricavano
 * dalla richiesta: il sito resta installabile in una sottocartella qualsiasi senza che
 * questo file vada modificato.
 */

require_once __DIR__ . '/includes/risorse.php';

header('Content-Type: application/xml; charset=UTF-8');

$base = indirizzo_base();

echo '<?xml version="1.0" encoding="UTF-8"?>', "\n";
echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">', "\n";

foreach (pagine_pubbliche() as $slug) {
    echo '    <url><loc>', e($base . $slug), '</loc></url>', "\n";
}

echo '</urlset>', "\n";
