<?php
/**
 * Caricamento delle immagini dei prodotti.
 *
 * I file arrivano dal pannello di controllo e finiscono in uploads/prodotti/. Il tipo
 * viene riconosciuto dal contenuto con getimagesize(), non dal nome né dal tipo
 * dichiarato dal browser, che sono entrambi scelti da chi invia la richiesta.
 */

// Dimensione massima accettata, in byte.
define('IMMAGINE_PESO_MASSIMO', 300 * 1024);

// Tipi ammessi, associati all'estensione con cui il file viene salvato.
define('IMMAGINE_TIPI_AMMESSI', [
    IMAGETYPE_WEBP => 'webp',
    IMAGETYPE_JPEG => 'jpg',
    IMAGETYPE_PNG => 'png',
]);

/**
 * Salva l'immagine ricevuta e restituisce il percorso da scrivere nel database.
 *
 * @param array $file  elemento di $_FILES relativo al campo del modulo
 * @return array{ok: bool, messaggio: string, percorso?: string}
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
    $cartella = dirname(__DIR__, 2) . '/uploads/prodotti/';

    if (!move_uploaded_file($file['tmp_name'], $cartella . $nome)) {
        return ['ok' => false, 'messaggio' => 'Non è stato possibile salvare l\'immagine.'];
    }

    chmod($cartella . $nome, 0644);

    return ['ok' => true, 'messaggio' => 'Immagine caricata.', 'percorso' => 'uploads/prodotti/' . $nome];
}

/**
 * Cancella un'immagine caricata in precedenza. Le immagini di serie, che stanno in
 * images/, non vengono toccate.
 */
function immagine_cancella(?string $percorso): void
{
    if ($percorso === null || !str_starts_with($percorso, 'uploads/prodotti/')) {
        return;
    }

    $file = dirname(__DIR__, 2) . '/' . $percorso;

    if (is_file($file)) {
        unlink($file);
    }
}
