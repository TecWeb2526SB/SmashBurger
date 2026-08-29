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
            <span class="icona-righe"><?php echo icona('menu'); ?></span>
            <span class="icona-croce"><?php echo icona('croce'); ?></span>
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
                    <span class="icona-luna"><?php echo icona('luna'); ?></span>
                    <span class="icona-sole"><?php echo icona('sole'); ?></span>
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
