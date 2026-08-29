<?php
/**
 * Lettura del catalogo: categorie e prodotti.
 *
 * Il catalogo è unico per tutte le sedi; la colonna disponibile decide se un prodotto
 * può essere ordinato.
 */

/**
 * Restituisce le categorie nell'ordine stabilito per il menu.
 */
function catalogo_categorie(PDO $pdo): array
{
    return $pdo->query(
        'SELECT c.id, c.nome, c.slug, c.descrizione,
                COUNT(CASE WHEN p.disponibile = 1 THEN 1 END) AS prodotti
         FROM categorie c
         LEFT JOIN prodotti p ON p.categoria_id = c.id
         GROUP BY c.id
         ORDER BY c.ordine, c.nome'
    )->fetchAll();
}

/**
 * Restituisce i prodotti, eventualmente limitati a una categoria.
 *
 * @param string|null $slugCategoria  slug della categoria, null per l'intero catalogo
 */
function catalogo_prodotti(PDO $pdo, ?string $slugCategoria = null): array
{
    $sql = 'SELECT p.id, p.nome, p.slug, p.descrizione, p.allergeni, p.immagine,
                   p.prezzo_centesimi, p.disponibile,
                   c.nome AS categoria, c.slug AS categoria_slug
            FROM prodotti p
            INNER JOIN categorie c ON c.id = p.categoria_id';
    $parametri = [];

    if ($slugCategoria !== null) {
        $sql .= ' WHERE c.slug = :categoria';
        $parametri['categoria'] = $slugCategoria;
    }

    $sql .= ' ORDER BY c.ordine, p.nome';

    $query = $pdo->prepare($sql);
    $query->execute($parametri);

    return $query->fetchAll();
}

/**
 * Restituisce un prodotto a partire dal suo slug, oppure null se non esiste.
 */
function prodotto_per_slug(PDO $pdo, string $slug): ?array
{
    $query = $pdo->prepare(
        'SELECT p.*, c.nome AS categoria, c.slug AS categoria_slug
         FROM prodotti p
         INNER JOIN categorie c ON c.id = p.categoria_id
         WHERE p.slug = :slug'
    );
    $query->execute(['slug' => $slug]);
    $prodotto = $query->fetch();

    return $prodotto === false ? null : $prodotto;
}

/**
 * Restituisce un prodotto a partire dal suo identificativo, oppure null.
 */
function prodotto_per_id(PDO $pdo, int $prodottoId): ?array
{
    $query = $pdo->prepare('SELECT * FROM prodotti WHERE id = :id');
    $query->execute(['id' => $prodottoId]);
    $prodotto = $query->fetch();

    return $prodotto === false ? null : $prodotto;
}
