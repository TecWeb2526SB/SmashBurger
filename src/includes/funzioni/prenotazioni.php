<?php
/**
 * Lettura delle prenotazioni della sala eventi.
 *
 * La creazione e le decisioni del personale arrivano nelle fasi successive.
 */

/**
 * Stati in cui puo' trovarsi una prenotazione.
 */
function stati_prenotazione(): array
{
    return ['in attesa', 'approvata', 'rifiutata', 'annullata'];
}

/**
 * Prenotazioni di una persona, dalla data piu' vicina.
 */
function prenotazioni_dell_utente(PDO $pdo, int $utenteId): array
{
    $query = $pdo->prepare(
        'SELECT p.*, s.citta, s.slug AS sede_slug
           FROM prenotazioni p
           JOIN sedi s ON s.id = p.sede_id
          WHERE p.utente_id = :utente
          ORDER BY p.data DESC, p.ora_inizio DESC'
    );
    $query->execute([':utente' => $utenteId]);

    return $query->fetchAll();
}
