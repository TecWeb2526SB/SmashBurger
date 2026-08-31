<?php
/**
 * Catalogo visto dal pannello: prodotti, categorie e disponibilita' per sede.
 *
 * I dati comuni del prodotto li tocca solo l'amministratore; disponibilita' e quantita'
 * di una sede le tocca il manager di quella sede, e il limite entra nella query.
 */

/**
 * Cambia la disponibilita' di un prodotto in una sede.
 */
function disponibilita_cambia(PDO $pdo, int $sedeId, int $prodottoId, bool $disponibile): array
{
    $pdo->prepare(
        'INSERT INTO disponibilita_prodotti (sede_id, prodotto_id, disponibile, quantita)
         VALUES (:sede, :prodotto, :disponibile, 0)
         ON DUPLICATE KEY UPDATE disponibile = :aggiornato'
    )->execute([
        ':sede' => $sedeId,
        ':prodotto' => $prodottoId,
        ':disponibile' => $disponibile ? 1 : 0,
        ':aggiornato' => $disponibile ? 1 : 0,
    ]);

    return [
        'ok' => true,
        'messaggio' => $disponibile ? 'Prodotto rimesso nel menu.' : 'Prodotto tolto dal menu.',
    ];
}

/**
 * Imposta la quantita' di un prodotto in una sede, per esempio dopo un rifornimento.
 */
function quantita_imposta(PDO $pdo, int $sedeId, int $prodottoId, int $quantita): array
{
    if ($quantita < 0 || $quantita > 9999) {
        return ['ok' => false, 'messaggio' => 'La quantità deve stare fra 0 e 9999.'];
    }

    $pdo->prepare(
        'INSERT INTO disponibilita_prodotti (sede_id, prodotto_id, disponibile, quantita)
         VALUES (:sede, :prodotto, 1, :quantita)
         ON DUPLICATE KEY UPDATE quantita = :aggiornata'
    )->execute([
        ':sede' => $sedeId,
        ':prodotto' => $prodottoId,
        ':quantita' => $quantita,
        ':aggiornata' => $quantita,
    ]);

    return ['ok' => true, 'messaggio' => 'Quantita aggiornata.'];
}

/**
 * Controlla i dati comuni di un prodotto.
 */
function prodotto_errori(PDO $pdo, array $dati, ?int $escludiId = null): array
{
    $errori = [];

    $nome = trim((string) ($dati['nome'] ?? ''));
    if (mb_strlen($nome) < 2 || mb_strlen($nome) > 120) {
        $errori['nome'] = 'Scrivi il nome del prodotto, fra 2 e 120 caratteri.';
    }

    $slug = trim((string) ($dati['slug'] ?? ''));
    if (preg_match('/^[a-z0-9-]{2,120}$/', $slug) !== 1) {
        $errori['slug'] = 'Lo slug accetta lettere minuscole, cifre e trattini.';
    } elseif (slug_gia_usato($pdo, $slug, $escludiId)) {
        $errori['slug'] = 'Questo slug e già usato da un altro prodotto.';
    }

    if (categoria_esiste($pdo, (int) ($dati['categoria_id'] ?? 0)) === false) {
        $errori['categoria_id'] = 'Scegli una categoria esistente.';
    }

    $prezzo = filter_var($dati['prezzo'] ?? '', FILTER_VALIDATE_FLOAT);
    if ($prezzo === false || $prezzo <= 0 || $prezzo > 999) {
        $errori['prezzo'] = 'Scrivi un prezzo fra 0,01 e 999 euro.';
    }

    if (mb_strlen(trim((string) ($dati['descrizione'] ?? ''))) < 10) {
        $errori['descrizione'] = 'Scrivi una descrizione di almeno 10 caratteri.';
    }

    if (mb_strlen((string) ($dati['allergeni'] ?? '')) > 255) {
        $errori['allergeni'] = 'L elenco degli allergeni non puo superare i 255 caratteri.';
    }

    return $errori;
}

/**
 * Verifica se uno slug appartiene già a un altro prodotto.
 */
function slug_gia_usato(PDO $pdo, string $slug, ?int $escludiId): bool
{
    $sql = 'SELECT 1 FROM prodotti WHERE slug = :slug';
    $parametri = [':slug' => $slug];

    if ($escludiId !== null) {
        $sql .= ' AND id <> :id';
        $parametri[':id'] = $escludiId;
    }

    $query = $pdo->prepare($sql);
    $query->execute($parametri);

    return $query->fetchColumn() !== false;
}

/**
 * Verifica che una categoria esista.
 */
function categoria_esiste(PDO $pdo, int $categoriaId): bool
{
    $query = $pdo->prepare('SELECT 1 FROM categorie WHERE id = :id');
    $query->execute([':id' => $categoriaId]);

    return $query->fetchColumn() !== false;
}

/**
 * Salva un prodotto, creandolo o aggiornandolo.
 *
 * Alla creazione il prodotto nasce nel menu di ogni sede con quantita' zero: risulta
 * quindi non disponibile finchè un manager non lo rifornisce.
 */
function prodotto_salva(PDO $pdo, array $dati, ?int $prodottoId): array
{
    $parametri = [
        ':categoria' => (int) $dati['categoria_id'],
        ':nome' => trim($dati['nome']),
        ':slug' => trim($dati['slug']),
        ':descrizione' => trim($dati['descrizione']),
        ':allergeni' => trim((string) ($dati['allergeni'] ?? '')),
        ':immagine' => trim((string) ($dati['immagine'] ?? '')),
        ':prezzo' => (int) round((float) $dati['prezzo'] * 100),
    ];

    if ($prodottoId === null) {
        $pdo->prepare(
            'INSERT INTO prodotti (categoria_id, nome, slug, descrizione, allergeni, immagine, prezzo_centesimi)
             VALUES (:categoria, :nome, :slug, :descrizione, :allergeni, :immagine, :prezzo)'
        )->execute($parametri);

        $nuovo = (int) $pdo->lastInsertId();

        $pdo->prepare(
            'INSERT INTO disponibilita_prodotti (sede_id, prodotto_id, disponibile, quantita)
             SELECT id, :prodotto, 1, 0 FROM sedi'
        )->execute([':prodotto' => $nuovo]);

        return ['ok' => true, 'messaggio' => 'Prodotto creato. Le sedi devono ancora rifornirlo.'];
    }

    $parametri[':id'] = $prodottoId;

    $pdo->prepare(
        'UPDATE prodotti SET categoria_id = :categoria, nome = :nome, slug = :slug,
                descrizione = :descrizione, allergeni = :allergeni, immagine = :immagine,
                prezzo_centesimi = :prezzo
          WHERE id = :id'
    )->execute($parametri);

    return ['ok' => true, 'messaggio' => 'Prodotto aggiornato.'];
}

/**
 * Cancella un prodotto e, per chiave esterna, le sue righe di disponibilita'.
 *
 * Le righe degli ordini restano: hanno già copiato nome e prezzo, quindi le ricevute
 * emesse continuano a leggersi.
 */
function prodotto_elimina(PDO $pdo, int $prodottoId): array
{
    $pdo->prepare('DELETE FROM prodotti WHERE id = :id')->execute([':id' => $prodottoId]);

    return ['ok' => true, 'messaggio' => 'Prodotto cancellato.'];
}

/**
 * Salva una categoria, creandola o rinominandola.
 */
function categoria_salva(PDO $pdo, array $dati, ?int $categoriaId): array
{
    $nome = trim((string) ($dati['nome'] ?? ''));
    $slug = trim((string) ($dati['slug'] ?? ''));

    if (mb_strlen($nome) < 2 || mb_strlen($nome) > 80) {
        return ['ok' => false, 'messaggio' => 'Scrivi il nome della categoria, fra 2 e 80 caratteri.'];
    }

    if (preg_match('/^[a-z0-9-]{2,80}$/', $slug) !== 1) {
        return ['ok' => false, 'messaggio' => 'Lo slug accetta lettere minuscole, cifre e trattini.'];
    }

    $parametri = [
        ':nome' => $nome,
        ':slug' => $slug,
        ':descrizione' => trim((string) ($dati['descrizione'] ?? '')),
        ':ordine' => max(0, min(255, (int) ($dati['ordine'] ?? 0))),
    ];

    try {
        if ($categoriaId === null) {
            $pdo->prepare(
                'INSERT INTO categorie (nome, slug, descrizione, ordine)
                 VALUES (:nome, :slug, :descrizione, :ordine)'
            )->execute($parametri);

            return ['ok' => true, 'messaggio' => 'Categoria creata.'];
        }

        $parametri[':id'] = $categoriaId;

        $pdo->prepare(
            'UPDATE categorie SET nome = :nome, slug = :slug, descrizione = :descrizione,
                    ordine = :ordine
              WHERE id = :id'
        )->execute($parametri);

        return ['ok' => true, 'messaggio' => 'Categoria aggiornata.'];
    } catch (PDOException $errore) {
        // L'unico vincolo che puo' fallire qui è l'unicita' dello slug.
        return ['ok' => false, 'messaggio' => 'Questo slug e già usato da un altra categoria.'];
    }
}

/**
 * Cancella una categoria, solo se non ha prodotti.
 *
 * La chiave esterna dei prodotti impedirebbe comunque la cancellazione: il controllo qui
 * serve a spiegare il motivo invece di mostrare un errore del database.
 */
function categoria_elimina(PDO $pdo, int $categoriaId): array
{
    $query = $pdo->prepare('SELECT COUNT(*) FROM prodotti WHERE categoria_id = :id');
    $query->execute([':id' => $categoriaId]);

    if ((int) $query->fetchColumn() > 0) {
        return ['ok' => false, 'messaggio' => 'Questa categoria ha ancora dei prodotti: spostali prima di cancellarla.'];
    }

    $pdo->prepare('DELETE FROM categorie WHERE id = :id')->execute([':id' => $categoriaId]);

    return ['ok' => true, 'messaggio' => 'Categoria cancellata.'];
}

/**
 * Un prodotto per il pannello, oppure null.
 */
function prodotto_per_id(PDO $pdo, int $prodottoId): ?array
{
    $query = $pdo->prepare('SELECT * FROM prodotti WHERE id = :id');
    $query->execute([':id' => $prodottoId]);
    $prodotto = $query->fetch();

    return $prodotto === false ? null : $prodotto;
}

/**
 * Una categoria per il pannello, oppure null.
 */
function categoria_per_id(PDO $pdo, int $categoriaId): ?array
{
    $query = $pdo->prepare('SELECT * FROM categorie WHERE id = :id');
    $query->execute([':id' => $categoriaId]);
    $categoria = $query->fetch();

    return $categoria === false ? null : $categoria;
}
