<?php
/**
 * Controller del cambio tema.
 *
 * Salva la scelta in un cookie e riporta alla pagina di partenza. Il nome della pagina
 * viene accettato solo se corrisponde a un controller esistente, così il parametro non
 * può portare altrove.
 */

require_once __DIR__ . '/includes/risorse.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !csrf_valido($_POST['token_csrf'] ?? null)) {
    vai_a();
}

$tema = (string) ($_POST['tema'] ?? '');

if (in_array($tema, ['chiaro', 'scuro'], true)) {
    tema_salva($tema);
}

$ritorno = basename((string) ($_POST['ritorno'] ?? ''));

vai_a(is_file(__DIR__ . '/' . $ritorno . '.php') ? $ritorno : '');
