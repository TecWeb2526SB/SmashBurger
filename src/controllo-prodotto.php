<?php
/**
 * Scheda di un prodotto: creazione, modifica e cancellazione dei dati comuni.
 *
 * Il modulo segue lo stesso ordine della pagina pubblica del prodotto, cosi' chi lo
 * compila sa già dove finira' ogni campo, ma con etichette regolari su ogni controllo.
 */

require_once __DIR__ . '/includes/risorse.php';

richiedi_permesso($pdo);

$prodottoId = identificativo($_GET, 'prodotto') ?? identificativo($_POST, 'prodotto_id');
$prodotto = $prodottoId === null ? null : prodotto_per_id($pdo, $prodottoId);

if ($prodottoId !== null && $prodotto === null) {
    errore(404);
}

$valori = [
    'nome' => (string) ($prodotto['nome'] ?? ''),
    'slug' => (string) ($prodotto['slug'] ?? ''),
    'categoria_id' => (string) ($prodotto['categoria_id'] ?? ''),
    'prezzo' => $prodotto === null ? '' : number_format((int) $prodotto['prezzo_centesimi'] / 100, 2, '.', ''),
    'descrizione' => (string) ($prodotto['descrizione'] ?? ''),
    'allergeni' => (string) ($prodotto['allergeni'] ?? ''),
    'immagine' => (string) ($prodotto['immagine'] ?? ''),
];
$errori = [];

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
    richiedi_post_valido();

    if (isset($_POST['elimina'])) {
        if ($prodotto === null) {
            errore(404);
        }

        $esito = prodotto_elimina($pdo, $prodottoId);
        messaggio_imposta($esito['ok'] ? 'successo' : 'errore', $esito['messaggio']);
        vai_a('controllo-prodotti');
    }

    foreach (['nome', 'slug', 'categoria_id', 'prezzo', 'descrizione', 'allergeni'] as $campo) {
        $valori[$campo] = is_string($_POST[$campo] ?? null) ? $_POST[$campo] : '';
    }

    $errori = prodotto_errori($pdo, $valori, $prodottoId);
    $fileImmagine = is_array($_FILES['immagine'] ?? null)
        ? $_FILES['immagine']
        : ['error' => UPLOAD_ERR_NO_FILE];
    $immagineObbligatoria = $prodotto === null || trim($valori['immagine']) === '';
    $erroreImmagine = immagine_prodotto_errore($fileImmagine, $immagineObbligatoria);

    if ($erroreImmagine !== null) {
        $errori['immagine'] = $erroreImmagine;
    }

    if ($errori === [] && !immagine_prodotto_assente($fileImmagine)) {
        $caricamento = immagine_prodotto_salva($fileImmagine);

        if (!$caricamento['ok']) {
            $errori['immagine'] = $caricamento['messaggio'];
        } else {
            $valori['immagine'] = $caricamento['nome'];
        }
    }

    if ($errori === []) {
        $esito = prodotto_salva($pdo, $valori, $prodottoId);
        messaggio_imposta($esito['ok'] ? 'successo' : 'errore', $esito['messaggio']);
        vai_a('controllo-prodotti');
    }
}

mostra_pagina('controllo/prodotto.php', [
    'breadcrumb' => [
        ['Home', url()],
        ['Ordini', url('controllo')],
        ['Prodotti', url('controllo-prodotti')],
        [$prodotto === null ? 'Nuovo prodotto' : $prodotto['nome'], null],
    ],
    'prodotto' => $prodotto,
    'valori' => $valori,
    'errori' => $errori,
    'categorie' => categorie_tutte($pdo),
    'confermaCancellazione' => isset($_GET['elimina']),
]);
