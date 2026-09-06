<?php
/**
 * Lettura degli ordini.
 *
 * La creazione di un ordine e le azioni del pannello vivono nelle fasi successive: qui
 * ci sono le letture che servono a chi ha ordinato.
 */

/**
 * Stati in cui puo' trovarsi un ordine.
 */
function stati_ordine(): array
{
    return ['ricevuto', 'in preparazione', 'pronto', 'concluso', 'annullato'];
}

/**
 * Stati del pagamento di un ordine.
 */
function stati_pagamento(): array
{
    return ['in attesa', 'pagato', 'rimborsato'];
}

/**
 * Ordini di una persona, dal piu' recente.
 */
function ordini_dell_utente(PDO $pdo, int $utenteId): array
{
    $query = $pdo->prepare(
        'SELECT o.*, s.citta, s.slug AS sede_slug
           FROM ordini o
           JOIN sedi s ON s.id = o.sede_id
          WHERE o.utente_id = :utente
          ORDER BY o.creato_il DESC'
    );
    $query->execute([':utente' => $utenteId]);

    return $query->fetchAll();
}

/**
 * Un ordine con la sua sede, ma solo se appartiene alla persona indicata.
 *
 * Il vincolo di proprieta' sta nella query: un identificativo che arriva da fuori non
 * basta mai da solo ad aprire un ordine.
 */
function ordine_dell_utente(PDO $pdo, int $ordineId, int $utenteId): ?array
{
    $query = $pdo->prepare(
        'SELECT o.*, s.citta, s.indirizzo AS sede_indirizzo, s.slug AS sede_slug
           FROM ordini o
           JOIN sedi s ON s.id = o.sede_id
          WHERE o.id = :ordine AND o.utente_id = :utente'
    );
    $query->execute([':ordine' => $ordineId, ':utente' => $utenteId]);
    $ordine = $query->fetch();

    return $ordine === false ? null : $ordine;
}

/**
 * Righe di un ordine, nell'ordine in cui sono state registrate.
 */
function righe_dell_ordine(PDO $pdo, int $ordineId): array
{
    $query = $pdo->prepare(
        'SELECT * FROM righe_ordine WHERE ordine_id = :ordine ORDER BY id'
    );
    $query->execute([':ordine' => $ordineId]);

    return $query->fetchAll();
}
