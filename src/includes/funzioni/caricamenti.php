<?php
/**
 * Controllo e salvataggio delle immagini caricate dal pannello.
 *
 * Il nome originale non viene mai usato sul server. Estensione, tipo MIME e dimensioni
 * sono controllati sul contenuto prima di spostare il file nella cartella pubblica.
 */

/**
 * Dice se il browser non ha inviato alcun file.
 */
function immagine_prodotto_assente(array $file): bool
{
    return (int) ($file['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE;
}

/**
 * Restituisce il messaggio di errore dell'immagine, oppure null se puo' essere salvata.
 */
function immagine_prodotto_errore(array $file, bool $obbligatoria): ?string
{
    $errore = (int) ($file['error'] ?? UPLOAD_ERR_NO_FILE);

    if ($errore === UPLOAD_ERR_NO_FILE) {
        return $obbligatoria ? "Carica un'immagine del prodotto." : null;
    }

    if ($errore === UPLOAD_ERR_INI_SIZE || $errore === UPLOAD_ERR_FORM_SIZE) {
        return "L'immagine non puo' superare 300 KB.";
    }

    if ($errore !== UPLOAD_ERR_OK) {
        return "Il caricamento dell'immagine non e' riuscito. Riprova.";
    }

    $nome = is_string($file['name'] ?? null) ? $file['name'] : '';
    $temporaneo = is_string($file['tmp_name'] ?? null) ? $file['tmp_name'] : '';
    $peso = filter_var($file['size'] ?? null, FILTER_VALIDATE_INT);

    if ($temporaneo === '' || !is_uploaded_file($temporaneo) || $peso === false || $peso <= 0) {
        return "Il file caricato non e' un'immagine valida.";
    }

    if ($peso > PESO_MASSIMO_IMMAGINE) {
        return "L'immagine non puo' superare 300 KB.";
    }

    $estensione = strtolower(pathinfo($nome, PATHINFO_EXTENSION));
    $tipiAmmessi = [
        'jpg' => 'image/jpeg',
        'jpeg' => 'image/jpeg',
        'png' => 'image/png',
        'webp' => 'image/webp',
    ];

    if (!isset($tipiAmmessi[$estensione])) {
        return 'Usa un file JPG, PNG o WebP.';
    }

    $rilevatore = new finfo(FILEINFO_MIME_TYPE);
    $tipo = $rilevatore->file($temporaneo);
    $dimensioni = @getimagesize($temporaneo);

    if ($tipo !== $tipiAmmessi[$estensione]
        || $dimensioni === false
        || ($dimensioni['mime'] ?? '') !== $tipo) {
        return "Estensione e contenuto dell'immagine non corrispondono.";
    }

    [$larghezza, $altezza] = $dimensioni;
    if ($larghezza < LATO_MINIMO_IMMAGINE || $altezza < LATO_MINIMO_IMMAGINE
        || $larghezza > LATO_MASSIMO_IMMAGINE || $altezza > LATO_MASSIMO_IMMAGINE) {
        return "L'immagine deve misurare da 300 a 2000 pixel per lato.";
    }

    return null;
}

/**
 * Sposta un'immagine gia' validata in uploads/prodotti con un nome casuale.
 */
function immagine_prodotto_salva(array $file): array
{
    $temporaneo = (string) $file['tmp_name'];
    $rilevatore = new finfo(FILEINFO_MIME_TYPE);
    $estensioni = [
        'image/jpeg' => 'jpg',
        'image/png' => 'png',
        'image/webp' => 'webp',
    ];
    $estensione = $estensioni[$rilevatore->file($temporaneo)] ?? null;

    if ($estensione === null) {
        return ['ok' => false, 'messaggio' => "Il formato dell'immagine non e' supportato."];
    }

    $cartella = dirname(__DIR__, 2) . '/uploads/prodotti';
    if (!is_dir($cartella) && !mkdir($cartella, 0755, true) && !is_dir($cartella)) {
        return ['ok' => false, 'messaggio' => "Non e' stato possibile preparare la cartella delle immagini."];
    }

    try {
        $nome = bin2hex(random_bytes(16)) . '.' . $estensione;
    } catch (Throwable $errore) {
        return ['ok' => false, 'messaggio' => "Non e' stato possibile generare il nome dell'immagine."];
    }

    if (!move_uploaded_file($temporaneo, $cartella . '/' . $nome)) {
        return ['ok' => false, 'messaggio' => "Non e' stato possibile salvare l'immagine."];
    }

    return ['ok' => true, 'nome' => $nome];
}
