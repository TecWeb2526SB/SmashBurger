<?php
/**
 * Uscita dalla sessione.
 *
 * Avviene solo in POST: un'uscita raggiungibile con un collegamento potrebbe essere
 * innescata da un'immagine su un altro sito.
 */

require_once __DIR__ . '/includes/risorse.php';

richiedi_permesso($pdo);

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
    richiedi_post_valido();
    utente_esci();
    vai_a();
}

$urlAnnulla = null;

// 1. Priorita': eventuale parametro esplicito 'ritorno' in GET
$ritorno = (string) ($_GET['ritorno'] ?? '');
if ($ritorno !== '') {
    $queryRitorno = substr((string) ($_GET['query'] ?? ''), 0, 2048);
    $parametriRitorno = [];
    if ($queryRitorno !== '') {
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
    }

    if ($ritorno === 'home') {
        $urlAnnulla = url('', $parametriRitorno);
    } elseif ($ritorno !== 'esci' && pagina_dati($ritorno) !== null) {
        $urlAnnulla = url($ritorno, $parametriRitorno);
    }
}

// 2. HTTP_REFERER se proviene dallo stesso sito
if ($urlAnnulla === null) {
    $referer = (string) ($_SERVER['HTTP_REFERER'] ?? '');
    if ($referer !== '') {
        $partiReferer = parse_url($referer);
        $hostReferer = (string) ($partiReferer['host'] ?? '');
        $hostCorrente = explode(':', (string) ($_SERVER['HTTP_HOST'] ?? ''))[0];

        if ($hostReferer === '' || strcasecmp($hostReferer, $hostCorrente) === 0) {
            $pathReferer = (string) ($partiReferer['path'] ?? '');
            $radice = radice_applicazione();
            if (str_starts_with($pathReferer, $radice)) {
                $slugReferer = trim(substr($pathReferer, strlen($radice)), '/');
            } else {
                $slugReferer = trim($pathReferer, '/');
            }

            // Ignora pagine transitorie o di autenticazione
            if (!in_array($slugReferer, ['esci', 'accedi', 'registrati'], true)) {
                if ($slugReferer === '' || pagina_dati($slugReferer) !== null) {
                    $parametriReferer = [];
                    $queryReferer = (string) ($partiReferer['query'] ?? '');
                    if ($queryReferer !== '') {
                        parse_str($queryReferer, $parametriReferer);
                        foreach ($parametriReferer as $chiave => $valore) {
                            if (!is_string($chiave)
                                || preg_match('/^[a-zA-Z0-9_-]+$/', $chiave) !== 1
                                || !is_scalar($valore)
                                || strlen((string) $valore) > 255
                            ) {
                                unset($parametriReferer[$chiave]);
                            }
                        }
                    }
                    $urlAnnulla = url($slugReferer, $parametriReferer);
                }
            }
        }
    }
}

// 3. Se calcolato con successo, salvalo in sessione per mantenerlo in caso di ricaricamento
if ($urlAnnulla !== null) {
    $_SESSION['ritorno_esci'] = $urlAnnulla;
} elseif (isset($_SESSION['ritorno_esci']) && is_string($_SESSION['ritorno_esci']) && $_SESSION['ritorno_esci'] !== '') {
    $urlAnnulla = $_SESSION['ritorno_esci'];
} else {
    $urlAnnulla = url('area-personale');
}

mostra_pagina('account/esci.php', [
    'breadcrumb' => [['Home', url()], ['Esci', null]],
    'urlAnnulla' => $urlAnnulla,
]);
