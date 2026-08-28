<?php
/**
 * Lettura delle sedi e dei loro orari di apertura.
 *
 * Il giorno della settimana segue la numerazione ISO 8601, la stessa restituita da
 * date('N'): 1 è lunedì e 7 è domenica.
 */

/**
 * Restituisce le sedi attive, ognuna con l'orario del giorno indicato.
 *
 * @param int|null $giorno  giorno da usare per l'orario, per impostazione predefinita oggi
 */
function sedi_tutte(PDO $pdo, ?int $giorno = null): array
{
    $giorno = $giorno ?? (int) date('N');

    $query = $pdo->prepare(
        'SELECT s.id, s.slug, s.nome, s.citta, s.provincia, s.indirizzo, s.cap,
                s.telefono, s.email, s.note_ritiro,
                o.apertura, o.chiusura, o.chiuso
         FROM sedi s
         LEFT JOIN orari_sedi o ON o.sede_id = s.id AND o.giorno = :giorno
         WHERE s.attiva = 1
         ORDER BY s.ordine, s.citta'
    );
    $query->execute(['giorno' => $giorno]);

    return $query->fetchAll();
}

/**
 * Restituisce una sede attiva a partire dal suo slug, oppure null se non esiste.
 */
function sede_per_slug(PDO $pdo, string $slug): ?array
{
    $query = $pdo->prepare('SELECT * FROM sedi WHERE slug = :slug AND attiva = 1');
    $query->execute(['slug' => $slug]);
    $sede = $query->fetch();

    return $sede === false ? null : $sede;
}

/**
 * Restituisce i sette orari settimanali di una sede, ordinati da lunedì a domenica.
 */
function sede_orari(PDO $pdo, int $sedeId): array
{
    $query = $pdo->prepare(
        'SELECT giorno, apertura, chiusura, chiuso
         FROM orari_sedi
         WHERE sede_id = :sede
         ORDER BY giorno'
    );
    $query->execute(['sede' => $sedeId]);

    return $query->fetchAll();
}

/**
 * Restituisce la sede scelta dall'utente, oppure la prima sede attiva se non è ancora
 * stata fatta una scelta.
 */
function sede_selezionata(PDO $pdo): ?array
{
    $slug = $_SESSION['sede'] ?? null;

    if (is_string($slug)) {
        $sede = sede_per_slug($pdo, $slug);
        if ($sede !== null) {
            return $sede;
        }
    }

    $sedi = sedi_tutte($pdo);

    return $sedi === [] ? null : sede_per_slug($pdo, (string) $sedi[0]['slug']);
}

/**
 * Salva in sessione la sede scelta. Restituisce false se lo slug non corrisponde a
 * nessuna sede attiva.
 */
function sede_seleziona(PDO $pdo, string $slug): bool
{
    if (sede_per_slug($pdo, $slug) === null) {
        return false;
    }

    $_SESSION['sede'] = $slug;

    return true;
}

/**
 * Riduce un orario nel formato del database, ad esempio 11:30:00, alla forma mostrata
 * nelle pagine, 11:30.
 */
function orario(?string $ora): string
{
    return $ora === null ? '' : substr($ora, 0, 5);
}
