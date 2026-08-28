<?php
/**
 * Gestione del catalogo dal pannello di controllo: creazione, modifica e cancellazione
 * dei prodotti.
 *
 * La lettura del catalogo usata dalle pagine pubbliche sta in catalogo.php.
 */

/**
 * Ricava uno slug dal nome: minuscole, lettere e cifre, trattino come separatore.
 */
function catalogo_slug(string $testo): string
{
    $slug = iconv('UTF-8', 'ASCII//TRANSLIT', $testo);
    $slug = strtolower((string) $slug);
    $slug = preg_replace('/[^a-z0-9]+/', '-', $slug);

    return trim((string) $slug, '-');
}

/**
 * Rende lo slug univoco aggiungendo un numero progressivo se necessario.
 *
 * @param int|null $esclusoId  prodotto da ignorare nel confronto, usato in modifica
 */
function catalogo_slug_univoco(PDO $pdo, string $slug, ?int $esclusoId = null): string
{
    $query = $pdo->prepare('SELECT COUNT(*) FROM prodotti WHERE slug = :slug AND id <> :id');
    $candidato = $slug;
    $numero = 1;

    while (true) {
        $query->execute(['slug' => $candidato, 'id' => $esclusoId ?? 0]);

        if ((int) $query->fetchColumn() === 0) {
            return $candidato;
        }

        $numero++;
        $candidato = $slug . '-' . $numero;
    }
}

/**
 * Converte un prezzo scritto dall'utente, ad esempio "10,90", nel numero di centesimi.
 * Restituisce null se il testo non è un importo valido.
 */
function catalogo_prezzo_in_centesimi(string $testo): ?int
{
    $testo = str_replace([' ', '.'], ['', ''], trim($testo));
    $testo = str_replace(',', '.', $testo);

    if (!is_numeric($testo) || (float) $testo < 0) {
        return null;
    }

    return (int) round((float) $testo * 100);
}

/**
 * Controlla i dati di un prodotto inviati dal pannello.
 *
 * @return array<string, string>  errori indicizzati per nome del campo
 */
function catalogo_valida(array $dati): array
{
    $errori = [];

    if (trim($dati['nome']) === '' || mb_strlen($dati['nome']) > 120) {
        $errori['nome'] = 'Il nome è obbligatorio e non può superare 120 caratteri.';
    }

    if (trim($dati['descrizione']) === '' || mb_strlen($dati['descrizione']) > 255) {
        $errori['descrizione'] = 'La descrizione è obbligatoria e non può superare 255 caratteri.';
    }

    if (mb_strlen($dati['allergeni']) > 160) {
        $errori['allergeni'] = 'L\'elenco degli allergeni non può superare 160 caratteri.';
    }

    if (catalogo_prezzo_in_centesimi($dati['prezzo']) === null) {
        $errori['prezzo'] = 'Il prezzo deve essere un importo, ad esempio 10,90.';
    }

    if ((int) $dati['categoria_id'] <= 0) {
        $errori['categoria_id'] = 'Scegli una categoria.';
    }

    return $errori;
}

/**
 * Inserisce un prodotto.
 *
 * @return array{ok: bool, errori: array<string, string>, id?: int}
 */
function catalogo_crea(PDO $pdo, array $dati): array
{
    $errori = catalogo_valida($dati);

    if ($errori !== []) {
        return ['ok' => false, 'errori' => $errori];
    }

    $query = $pdo->prepare(
        'INSERT INTO prodotti (categoria_id, nome, slug, descrizione, allergeni, immagine,
                               prezzo_centesimi, disponibile)
         VALUES (:categoria, :nome, :slug, :descrizione, :allergeni, :immagine, :prezzo, :disponibile)'
    );
    $query->execute([
        'categoria' => (int) $dati['categoria_id'],
        'nome' => trim($dati['nome']),
        'slug' => catalogo_slug_univoco($pdo, catalogo_slug($dati['nome'])),
        'descrizione' => trim($dati['descrizione']),
        'allergeni' => trim($dati['allergeni']) === '' ? null : trim($dati['allergeni']),
        'immagine' => $dati['immagine'] ?? null,
        'prezzo' => catalogo_prezzo_in_centesimi($dati['prezzo']),
        'disponibile' => empty($dati['disponibile']) ? 0 : 1,
    ]);

    return ['ok' => true, 'errori' => [], 'id' => (int) $pdo->lastInsertId()];
}

/**
 * Aggiorna un prodotto esistente. L'immagine viene sostituita solo se ne arriva una nuova.
 *
 * @return array{ok: bool, errori: array<string, string>}
 */
function catalogo_aggiorna(PDO $pdo, int $prodottoId, array $dati): array
{
    $errori = catalogo_valida($dati);

    if ($errori !== []) {
        return ['ok' => false, 'errori' => $errori];
    }

    $prodotto = prodotto_per_id($pdo, $prodottoId);

    if ($prodotto === null) {
        return ['ok' => false, 'errori' => ['nome' => 'Prodotto non trovato.']];
    }

    $query = $pdo->prepare(
        'UPDATE prodotti
         SET categoria_id = :categoria, nome = :nome, slug = :slug, descrizione = :descrizione,
             allergeni = :allergeni, immagine = :immagine, prezzo_centesimi = :prezzo,
             disponibile = :disponibile
         WHERE id = :id'
    );
    $query->execute([
        'categoria' => (int) $dati['categoria_id'],
        'nome' => trim($dati['nome']),
        'slug' => catalogo_slug_univoco($pdo, catalogo_slug($dati['nome']), $prodottoId),
        'descrizione' => trim($dati['descrizione']),
        'allergeni' => trim($dati['allergeni']) === '' ? null : trim($dati['allergeni']),
        'immagine' => $dati['immagine'] ?? $prodotto['immagine'],
        'prezzo' => catalogo_prezzo_in_centesimi($dati['prezzo']),
        'disponibile' => empty($dati['disponibile']) ? 0 : 1,
        'id' => $prodottoId,
    ]);

    return ['ok' => true, 'errori' => []];
}

/**
 * Cancella un prodotto e l'eventuale immagine caricata insieme a lui.
 *
 * Le righe degli ordini già registrati conservano nome e prezzo, quindi restano
 * leggibili anche dopo la cancellazione.
 *
 * @return array{ok: bool, messaggio: string}
 */
function catalogo_cancella(PDO $pdo, int $prodottoId): array
{
    $prodotto = prodotto_per_id($pdo, $prodottoId);

    if ($prodotto === null) {
        return ['ok' => false, 'messaggio' => 'Prodotto non trovato.'];
    }

    $pdo->prepare('DELETE FROM prodotti WHERE id = :id')->execute(['id' => $prodottoId]);
    immagine_cancella($prodotto['immagine']);

    return ['ok' => true, 'messaggio' => 'Prodotto cancellato.'];
}
