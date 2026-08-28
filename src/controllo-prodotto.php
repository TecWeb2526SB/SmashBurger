<?php
/**
 * Controller del modulo di inserimento e modifica di un prodotto.
 *
 * Senza parametro id il modulo crea un prodotto nuovo; con id valido lo modifica.
 */

require_once __DIR__ . '/includes/risorse.php';

utente_richiedi_amministratore();

$prodotto = null;
$idRichiesto = (int) ($_GET['id'] ?? 0);

if ($idRichiesto > 0) {
    $prodotto = prodotto_per_id($pdo, $idRichiesto);

    if ($prodotto === null) {
        messaggio_imposta('errore', 'Prodotto non trovato.');
        vai_a('controllo-prodotti');
    }
}

$valori = [
    'nome' => $prodotto['nome'] ?? '',
    'descrizione' => $prodotto['descrizione'] ?? '',
    'allergeni' => $prodotto['allergeni'] ?? '',
    'prezzo' => isset($prodotto['prezzo_centesimi'])
        ? number_format($prodotto['prezzo_centesimi'] / 100, 2, ',', '')
        : '',
    'categoria_id' => $prodotto['categoria_id'] ?? '',
    'disponibile' => $prodotto['disponibile'] ?? 1,
];
$errori = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_valido($_POST['token_csrf'] ?? null)) {
        messaggio_imposta('errore', 'Sessione scaduta, riprova a inviare il modulo.');
        vai_a('controllo-prodotti');
    }

    foreach (['nome', 'descrizione', 'allergeni', 'prezzo', 'categoria_id'] as $campo) {
        $valori[$campo] = trim((string) ($_POST[$campo] ?? ''));
    }
    $valori['disponibile'] = isset($_POST['disponibile']) ? 1 : 0;

    // L'immagine viene sostituita solo se il modulo ne porta una nuova.
    if (($_FILES['immagine']['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_NO_FILE) {
        $caricamento = immagine_salva($_FILES['immagine']);

        if ($caricamento['ok']) {
            $valori['immagine'] = $caricamento['nome'];
        } else {
            $errori['immagine'] = $caricamento['messaggio'];
        }
    }

    if ($errori === []) {
        $esito = $prodotto === null
            ? catalogo_crea($pdo, $valori)
            : catalogo_aggiorna($pdo, (int) $prodotto['id'], $valori);

        if ($esito['ok']) {
            messaggio_imposta('successo', $prodotto === null ? 'Prodotto creato.' : 'Prodotto aggiornato.');
            vai_a('controllo-prodotti');
        }

        $errori = $esito['errori'];
    }
}

mostra_pagina('controllo/prodotto.php', [
    'titolo' => ($prodotto === null ? 'Nuovo prodotto' : 'Modifica prodotto') . ' - Pannello',
    'pagina' => 'Controllo',
    'breadcrumb' => [
        ['Home', url()],
        ['Controllo', url('controllo')],
        ['Prodotti', url('controllo-prodotti')],
        [$prodotto === null ? 'Nuovo prodotto' : 'Modifica', null],
    ],
    'sezione' => 'Prodotti',
    'prodotto' => $prodotto,
    'categorie' => catalogo_categorie($pdo),
    'valori' => $valori,
    'errori' => $errori,
]);
