<?php
/**
 * Cambio del tema chiaro/scuro.
 *
 * Avviene solo in POST, come ogni azione che lascia una traccia sul browser. La pagina
 * di ritorno arriva dal modulo ma non viene mai usata cosi' com'e': viene confrontata
 * con l'elenco delle pagine del sito, quindi un valore inventato riporta alla home
 * invece che a una destinazione scelta da chi invia la richiesta.
 */

require_once __DIR__ . '/includes/risorse.php';

richiedi_permesso($pdo);
richiedi_post_valido();

tema_salva((string) ($_POST['tema'] ?? ''));

$ritorno = (string) ($_POST['ritorno'] ?? '');
$queryRitorno = substr((string) ($_POST['ritorno_query'] ?? ''), 0, 2048);
$parametriRitorno = [];

parse_str($queryRitorno, $parametriRitorno);

foreach ($parametriRitorno as $chiave => $valore) {
    if (!is_string($chiave)
        || preg_match('/^[a-zA-Z0-9_-]+$/', $chiave) !== 1
        || !is_scalar($valore)
        || strlen((string) $valore) > 255
    ) {
        unset($parametriRitorno[$chiave]);
    }
}

vai_a(pagina_dati($ritorno) === null ? '' : $ritorno, $parametriRitorno);
