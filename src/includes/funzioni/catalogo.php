<?php
/**
 * Lettura pubblica del catalogo: categorie, prodotti e loro disponibilita' nelle sedi.
 *
 * La disponibilita' effettiva di un prodotto in una sede e' disponibile = 1 AND
 * quantita > 0: l'interruttore lo toglie dal menu anche con merce a magazzino, lo zero
 * lo toglie da solo.
 */

/**
 * Tutte le categorie, nell'ordine deciso dalla colonna ordine.
 */
function categorie_tutte(PDO $pdo): array
{
    return $pdo->query('SELECT * FROM categorie ORDER BY ordine, nome')->fetchAll();
}

/**
 * Una categoria a partire dal suo slug, oppure null.
 */
function categoria_per_slug(PDO $pdo, string $slug): ?array
{
    $query = $pdo->prepare('SELECT * FROM categorie WHERE slug = :slug');
    $query->execute([':slug' => $slug]);
    $categoria = $query->fetch();

    return $categoria === false ? null : $categoria;
}

/**
 * Prodotti del catalogo, con il nome della categoria.
 *
 * @param int|null $categoriaId restringe a una categoria; null restituisce tutto
 */
function prodotti_catalogo(PDO $pdo, ?int $categoriaId = null): array
{
    $sql = 'SELECT p.*, c.nome AS categoria_nome, c.slug AS categoria_slug
              FROM prodotti p
              JOIN categorie c ON c.id = p.categoria_id';

    if ($categoriaId !== null) {
        $sql .= ' WHERE p.categoria_id = :categoria';
    }

    $sql .= ' ORDER BY c.ordine, p.nome';

    $query = $pdo->prepare($sql);
    $query->execute($categoriaId === null ? [] : [':categoria' => $categoriaId]);

    return $query->fetchAll();
}

/**
 * Un prodotto a partire dal suo slug, oppure null.
 */
function prodotto_per_slug(PDO $pdo, string $slug): ?array
{
    $query = $pdo->prepare(
        'SELECT p.*, c.nome AS categoria_nome, c.slug AS categoria_slug
           FROM prodotti p
           JOIN categorie c ON c.id = p.categoria_id
          WHERE p.slug = :slug'
    );
    $query->execute([':slug' => $slug]);
    $prodotto = $query->fetch();

    return $prodotto === false ? null : $prodotto;
}

/**
 * Piu' prodotti a partire dai loro slug, nell'ordine in cui gli slug sono stati chiesti.
 *
 * L'ordine di uscita e' quello dell'elenco ricevuto e non quello del database, perche'
 * chi chiama sta componendo una selezione in cui la posizione conta, come il podio della
 * home. Uno slug inesistente viene semplicemente saltato.
 *
 * @param array $slug elenco di slug
 */
function prodotti_per_slug(PDO $pdo, array $slug): array
{
    if ($slug === []) {
        return [];
    }

    // I segnaposto sono testo scritto qui, non dati della richiesta: il valore degli
    // slug passa comunque dai parametri del prepared statement.
    $segnaposto = [];
    $parametri = [];

    foreach (array_values($slug) as $indice => $valore) {
        $segnaposto[] = ':slug' . $indice;
        $parametri[':slug' . $indice] = $valore;
    }

    $query = $pdo->prepare(
        'SELECT p.*, c.nome AS categoria_nome, c.slug AS categoria_slug
           FROM prodotti p
           JOIN categorie c ON c.id = p.categoria_id
          WHERE p.slug IN (' . implode(', ', $segnaposto) . ')'
    );
    $query->execute($parametri);

    $trovati = [];

    foreach ($query->fetchAll() as $prodotto) {
        $trovati[$prodotto['slug']] = $prodotto;
    }

    $ordinati = [];

    foreach ($slug as $valore) {
        if (isset($trovati[$valore])) {
            $ordinati[] = $trovati[$valore];
        }
    }

    return $ordinati;
}

/**
 * Sedi attive dove un prodotto e' disponibile in questo momento.
 *
 * Alimenta la pagina di dettaglio del prodotto, che dice dove trovarlo senza chiedere
 * di scegliere una sede.
 */
function sedi_con_prodotto(PDO $pdo, int $prodottoId): array
{
    $query = $pdo->prepare(
        'SELECT s.* FROM disponibilita_prodotti d
           JOIN sedi s ON s.id = d.sede_id
          WHERE d.prodotto_id = :prodotto
            AND d.disponibile = 1 AND d.quantita > 0 AND s.attiva = 1
          ORDER BY s.ordine, s.citta'
    );
    $query->execute([':prodotto' => $prodottoId]);

    return $query->fetchAll();
}

/**
 * Prodotti di una sede con la loro disponibilita', per il menu di un ordine in corso e
 * per l'elenco del pannello.
 *
 * @param bool $soloDisponibili true per il cliente che ordina, false per il manager che
 *                              deve vedere anche cio' che ha esaurito
 */
function prodotti_in_sede(PDO $pdo, int $sedeId, bool $soloDisponibili = true): array
{
    $sql = 'SELECT p.*, c.nome AS categoria_nome, c.slug AS categoria_slug,
                   d.disponibile, d.quantita
              FROM prodotti p
              JOIN categorie c ON c.id = p.categoria_id
              LEFT JOIN disponibilita_prodotti d
                ON d.prodotto_id = p.id AND d.sede_id = :sede';

    if ($soloDisponibili) {
        $sql .= ' WHERE d.disponibile = 1 AND d.quantita > 0';
    }

    $sql .= ' ORDER BY c.ordine, p.nome';

    $query = $pdo->prepare($sql);
    $query->execute([':sede' => $sedeId]);

    return $query->fetchAll();
}

/**
 * Quantita' disponibile di un prodotto in una sede, zero se la riga non esiste o se il
 * prodotto e' stato tolto dal menu.
 */
function quantita_disponibile(PDO $pdo, int $sedeId, int $prodottoId): int
{
    $query = $pdo->prepare(
        'SELECT quantita FROM disponibilita_prodotti
          WHERE sede_id = :sede AND prodotto_id = :prodotto AND disponibile = 1'
    );
    $query->execute([':sede' => $sedeId, ':prodotto' => $prodottoId]);
    $quantita = $query->fetchColumn();

    return $quantita === false ? 0 : (int) $quantita;
}
