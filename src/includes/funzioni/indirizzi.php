<?php
/**
 * Controlli sugli indirizzi di consegna.
 *
 * Stanno in un file proprio perchè servono a due percorsi diversi: il profilo, che
 * salva un indirizzo per riusarlo, e il pagamento, che ne accetta uno scritto al
 * momento senza salvarlo.
 */

/**
 * Controlla un indirizzo di consegna.
 *
 * Usata sia dal profilo sia dalla pagina di pagamento, che accetta un indirizzo scritto
 * al momento senza salvarlo.
 */
function indirizzo_errori(array $dati): array
{
    $errori = [];

    $indirizzo = trim((string) ($dati['indirizzo'] ?? ''));
    if (mb_strlen($indirizzo) < 5 || mb_strlen($indirizzo) > 160) {
        $errori['indirizzo'] = 'Scrivi via e numero civico.';
    }

    $città = trim((string) ($dati['citta'] ?? ''));
    if (mb_strlen($citta) < 2 || mb_strlen($citta) > 80) {
        $errori['citta'] = 'Scrivi la città.';
    }

    if (preg_match('/^[A-Za-z]{2}$/', trim((string) ($dati['provincia'] ?? ''))) !== 1) {
        $errori['provincia'] = 'La provincia si scrive con due lettere, ad esempio PD.';
    }

    if (preg_match('/^[0-9]{5}$/', trim((string) ($dati['cap'] ?? ''))) !== 1) {
        $errori['cap'] = 'Il CAP è composto da cinque cifre.';
    }

    $paese = trim((string) ($dati['paese'] ?? ''));
    if (mb_strlen($paese) < 2 || mb_strlen($paese) > 60) {
        $errori['paese'] = 'Scrivi il paese di consegna.';
    }

    if (preg_match('/^[0-9 +().-]{6,30}$/', trim((string) ($dati['telefono'] ?? ''))) !== 1) {
        $errori['telefono'] = 'Scrivi un numero di telefono valido, per il corriere.';
    }

    return $errori;
}
