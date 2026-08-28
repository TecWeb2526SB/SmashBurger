<?php
/**
 * Parte iniziale di ogni pagina: testa del documento, header del sito, menu principale
 * e apertura del contenuto.
 *
 * Le variabili $titolo, $descrizione, $pagina e $breadcrumb arrivano da mostra_pagina().
 */
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="it" xml:lang="it">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title><?php echo e($titolo ?? NOME_SITO); ?></title>
    <meta name="description" content="<?php echo e($descrizione ?? ''); ?>" />
    <link rel="stylesheet" href="styles/stile.css" />
    <link rel="icon" type="image/svg+xml" href="images/favicon.svg" />
    <link rel="manifest" href="site.webmanifest" />
</head>

<body>
    <a href="#contenuto">Vai al contenuto</a>

    <header>
        <p><a href="<?php echo e(url()); ?>"><?php echo e(NOME_SITO); ?></a></p>

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
    </header>

    <main id="contenuto">
        <?php include __DIR__ . '/breadcrumb.php'; ?>
