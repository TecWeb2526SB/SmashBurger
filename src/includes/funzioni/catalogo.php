<?php
/**
 * Lettura pubblica del catalogo: categorie, prodotti e loro disponibilita' nelle sedi.
 *
 * La disponibilita' effettiva di un prodotto in una sede è disponibile = 1 AND
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
 * I due filtri si sommano: chi cerca dentro una categoria resta dentro quella categoria.
 * Il termine cercato viene confrontato con il nome e con la descrizione, cosi' "cipolla"
 * trova il panino che la contiene anche se non la porta nel nome.
 *
 * @param int|null $categoriaId restringe a una categoria; null restituisce tutto
 * @param string   $ricerca     termine cercato; stringa vuota non restringe nulla
 */
function prodotti_catalogo(PDO $pdo, ?int $categoriaId = null, string $ricerca = ''): array
{
    $sql = 'SELECT p.*, c.nome AS categoria_nome, c.slug AS categoria_slug
              FROM prodotti p
              JOIN categorie c ON c.id = p.categoria_id';
    $condizioni = [];
    $parametri = [];

    if ($categoriaId !== null) {
        $condizioni[] = 'p.categoria_id = :categoria';
        $parametri[':categoria'] = $categoriaId;
    }

    if ($ricerca !== '') {
        // Due segnaposto per lo stesso valore: un nome non si puo' riusare nella query.
        $condizioni[] = '(p.nome LIKE :nome OR p.descrizione LIKE :descrizione)';
        $parametri[':nome'] = '%' . like_letterale($ricerca) . '%';
        $parametri[':descrizione'] = $parametri[':nome'];
    }

    if ($condizioni !== []) {
        $sql .= ' WHERE ' . implode(' AND ', $condizioni);
    }

    $sql .= ' ORDER BY c.ordine, p.nome';

    $query = $pdo->prepare($sql);
    $query->execute($parametri);

    return $query->fetchAll();
}

/**
 * Neutralizza i caratteri speciali di LIKE dentro un termine cercato.
 *
 * Senza questo passaggio una percentuale scritta nel campo varrebbe da jolly e la
 * ricerca restituirebbe tutto.
 */
function like_letterale(string $termine): string
{
    return str_replace(['\\', '%', '_'], ['\\\\', '\\%', '\\_'], $termine);
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
 * L'ordine di uscita è quello dell'elenco ricevuto e non quello del database, perche'
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
 * Sedi attive che tengono un prodotto nel proprio menu.
 *
 * Alimenta la pagina di dettaglio del prodotto, che dice dove trovarlo senza chiedere di
 * scegliere una sede. Il filtro si ferma a `disponibile`, cioè alla decisione della sede
 * di tenere il prodotto in carta: la quantita' viene riportata insieme alla sede, cosi'
 * chi legge distingue un locale che lo ha da uno che lo ha esaurito. Escluderle entrambe
 * direbbe che il prodotto non esiste da nessuna parte, mentre nel menu si vede.
 */
function sedi_con_prodotto(PDO $pdo, int $prodottoId): array
{
    $query = $pdo->prepare(
        'SELECT s.*, d.quantita FROM disponibilita_prodotti d
           JOIN sedi s ON s.id = d.sede_id
          WHERE d.prodotto_id = :prodotto
            AND d.disponibile = 1 AND s.attiva = 1
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
 * prodotto è stato tolto dal menu.
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
