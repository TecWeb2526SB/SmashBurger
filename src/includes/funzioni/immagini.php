<?php
/**
 * Immagini dei prodotti.
 *
 * Tutte le immagini stanno in uploads/prodotti/ e seguono lo stesso trattamento, comprese
 * quelle dei dati di esempio. Nel database viene salvato il solo nome del file; il
 * percorso completo si compone con immagine_url().
 *
 * Il tipo viene riconosciuto dal contenuto con getimagesize(), non dal nome né dal tipo
 * dichiarato dal browser, che sono entrambi scelti da chi invia la richiesta.
 */

// Cartella che contiene le immagini dei prodotti, relativa alla radice del sito.
define('IMMAGINI_CARTELLA', 'uploads/prodotti/');

// Dimensione massima accettata, in byte.
define('IMMAGINE_PESO_MASSIMO', 300 * 1024);

// Tipi ammessi, associati all'estensione con cui il file viene salvato.
define('IMMAGINE_TIPI_AMMESSI', [
    IMAGETYPE_WEBP => 'webp',
    IMAGETYPE_JPEG => 'jpg',
    IMAGETYPE_PNG => 'png',
]);

/**
 * Compone l'indirizzo dell'immagine di un prodotto, oppure null se il prodotto non ne ha.
 */
function immagine_url(?string $nome): ?string
{
    return $nome === null || $nome === '' ? null : IMMAGINI_CARTELLA . $nome;
}

/**
 * Salva l'immagine ricevuta e restituisce il nome del file da scrivere nel database.
 *
 * @param array $file  elemento di $_FILES relativo al campo del modulo
 * @return array{ok: bool, messaggio: string, nome?: string}
 */
function immagine_salva(array $file): array
{
    if (($file['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) {
        return ['ok' => false, 'messaggio' => 'Nessun file inviato.'];
    }

    if (($file['error'] ?? UPLOAD_ERR_OK) !== UPLOAD_ERR_OK) {
        return ['ok' => false, 'messaggio' => 'Caricamento non riuscito.'];
    }

    if (!is_uploaded_file($file['tmp_name'])) {
        return ['ok' => false, 'messaggio' => 'File non valido.'];
    }

    if ((int) $file['size'] > IMMAGINE_PESO_MASSIMO) {
        return ['ok' => false, 'messaggio' => 'L\'immagine supera i 300 KB.'];
    }

    $informazioni = getimagesize($file['tmp_name']);

    if ($informazioni === false || !isset(IMMAGINE_TIPI_AMMESSI[$informazioni[2]])) {
        return ['ok' => false, 'messaggio' => 'Sono ammesse solo immagini webp, jpg o png.'];
    }

    // Il nome viene generato qui: quello inviato dal browser non viene mai usato.
    $nome = bin2hex(random_bytes(8)) . '.' . IMMAGINE_TIPI_AMMESSI[$informazioni[2]];
    $cartella = dirname(__DIR__, 2) . '/' . IMMAGINI_CARTELLA;

    if (!move_uploaded_file($file['tmp_name'], $cartella . $nome)) {
        return ['ok' => false, 'messaggio' => 'Non è stato possibile salvare l\'immagine.'];
    }

    chmod($cartella . $nome, 0644);

    return ['ok' => true, 'messaggio' => 'Immagine caricata.', 'nome' => $nome];
}

/**
 * Cancella l'immagine di un prodotto.
 *
 * Del valore ricevuto si usa solo il nome del file, così un percorso costruito ad arte
 * non può raggiungere cartelle diverse da quella delle immagini.
 */
function immagine_cancella(?string $nome): void
{
    if ($nome === null || $nome === '') {
        return;
    }

    $file = dirname(__DIR__, 2) . '/' . IMMAGINI_CARTELLA . basename($nome);

    if (is_file($file)) {
        unlink($file);
    }
}
