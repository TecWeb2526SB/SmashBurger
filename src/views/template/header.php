<?php
/**
 * Parte iniziale di ogni pagina: testa del documento, header del sito, menu principale
 * e apertura del contenuto.
 *
 * Le variabili $titolo, $descrizione, $pagina e $breadcrumb arrivano da mostra_pagina().
 * La classe sul tag html applica il tema scelto prima che la pagina venga disegnata.
 */

$temaScelto = tema_scelto();
$paginaCorrente = app_pagina_corrente();
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="it" xml:lang="it"
    <?php echo $temaScelto === '' ? '' : 'class="tema-' . e($temaScelto) . '"'; ?>>

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title><?php echo e($titolo ?? NOME_SITO); ?></title>
    <meta name="description" content="<?php echo e($descrizione ?? ''); ?>" />
    <link rel="stylesheet" href="styles/stile.css?v=<?php echo e(VERSIONE_RISORSE); ?>" />
    <link rel="icon" type="image/svg+xml" href="images/favicon.svg" />
    <link rel="manifest" href="site.webmanifest" />
    <script src="scripts/script.js?v=<?php echo e(VERSIONE_RISORSE); ?>" defer="defer"></script>
</head>

<body>
    <a href="#contenuto">Vai al contenuto</a>

    <header>
        <button type="button" class="apri-menu" aria-expanded="false" aria-controls="pannello-menu">
            <svg class="icona icona-righe" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                <path d="M3 6h18M3 12h18M3 18h18" />
            </svg>
            <svg class="icona icona-croce" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                <path d="M5 5l14 14M19 5L5 19" />
            </svg>
            <span class="solo-lettori quando-chiuso">Apri il menu</span>
            <span class="solo-lettori quando-aperto">Chiudi il menu</span>
        </button>

        <p class="marchio"><a href="<?php echo e(url()); ?>"><?php echo e(NOME_SITO); ?></a></p>

        <div class="pannello" id="pannello-menu">
        <nav aria-label="Navigazione principale">
            <ul>
                <?php foreach (menu_principale() as $etichetta => $indirizzo): ?>
                    <li>
                        <?php if (($pagina ?? '') === $etichetta): ?>
                            <span aria-current="page"><?php echo e($etichetta); ?></span>
                        <?php else: ?>
                            <a href="<?php echo e($indirizzo); ?>"><?php echo e($etichetta); ?></a>
                        <?php endif; ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        </nav>

        <div class="azioni">
            <form method="post" action="<?php echo e(url('tema')); ?>" data-modulo="tema" class="tema">
                <?php echo campo_csrf(); ?>
                <input type="hidden" name="tema" value="<?php echo $temaScelto === 'scuro' ? 'chiaro' : 'scuro'; ?>" />
                <input type="hidden" name="ritorno" value="<?php echo e($paginaCorrente); ?>" />
                <button type="submit">
                    <svg class="icona icona-luna" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                        <path d="M20 14.5A8.5 8.5 0 0 1 9.5 4a8.5 8.5 0 1 0 10.5 10.5z" />
                    </svg>
                    <svg class="icona icona-sole" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                        <circle cx="12" cy="12" r="4.5" />
                        <path d="M12 1.5v3M12 19.5v3M1.5 12h3M19.5 12h3M4.2 4.2l2.1 2.1M17.7 17.7l2.1 2.1M19.8 4.2l-2.1 2.1M6.3 17.7l-2.1 2.1" />
                    </svg>
                    <span class="solo-scuro">Tema chiaro</span>
                    <span class="solo-chiaro">Tema scuro</span>
                </button>
            </form>

            <?php foreach (menu_azioni() as $etichetta => $indirizzo): ?>
                <?php if (($pagina ?? '') === $etichetta): ?>
                    <span aria-current="page"><?php echo e($etichetta); ?></span>
                <?php else: ?>
                    <a href="<?php echo e($indirizzo); ?>"><?php echo e($etichetta); ?></a>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
        </div>
    </header>

    <main id="contenuto" class="container">
        <?php include __DIR__ . '/breadcrumb.php'; ?>

        <?php $messaggio = messaggio_leggi(); ?>
        <?php if ($messaggio !== null): ?>
            <p class="avviso" role="status" data-tipo="<?php echo e($messaggio['tipo']); ?>">
                <b><?php echo $messaggio['tipo'] === 'errore' ? 'Errore:' : 'Fatto:'; ?></b>
                <?php echo e($messaggio['testo']); ?>
            </p>
        <?php endif; ?>
