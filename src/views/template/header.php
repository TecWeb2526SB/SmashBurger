<?php
/**
 * Parte iniziale di ogni pagina: testa del documento, intestazione del sito, menu e
 * apertura del contenuto.
 *
 * Riceve $titolo, $descrizione e $breadcrumb da mostra_pagina(). Le voci del menu
 * derivano da includes/pagine.php e sono gia' filtrate per il ruolo di chi guarda.
 */

// La pagina 500 viene mostrata anche quando la connessione al database non riesce e
// $pdo non esiste: in quel caso il menu resta quello di chi non ha fatto l'accesso.
$ruoloCorrente = isset($pdo) ? ruolo_corrente($pdo) : null;
$slugCorrente = pagina_corrente();
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="it" xml:lang="it">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title><?php echo e($titolo); ?></title>
    <?php if ($descrizione !== ''): ?>
        <meta name="description" content="<?php echo e($descrizione); ?>" />
    <?php endif; ?>
    <link rel="stylesheet" media="screen" href="<?php echo e(risorsa('styles/stile.css', true)); ?>" />
    <link rel="stylesheet" media="screen and (max-width: 48em)" href="<?php echo e(risorsa('styles/mobile.css', true)); ?>" />
    <link rel="stylesheet" media="print" href="<?php echo e(risorsa('styles/stampa.css', true)); ?>" />
    <link rel="icon" type="image/svg+xml" href="<?php echo e(risorsa('images/favicon.svg')); ?>" />
    <link rel="manifest" href="<?php echo e(risorsa('site.webmanifest')); ?>" />
</head>

<body>
    <a class="salta" href="#contenuto">Vai al contenuto</a>

    <header>
        <p class="marchio"><a href="<?php echo e(url()); ?>"><?php echo e(NOME_SITO); ?></a></p>

        <nav aria-label="Navigazione principale">
            <ul>
                <?php foreach (pagine_del_menu('principale', $ruoloCorrente) as $slug => $etichetta): ?>
                    <li>
                        <?php if ($slug === $slugCorrente): ?>
                            <a href="<?php echo e(url($slug)); ?>" aria-current="page"><?php echo e($etichetta); ?></a>
                        <?php else: ?>
                            <a href="<?php echo e(url($slug)); ?>"><?php echo e($etichetta); ?></a>
                        <?php endif; ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        </nav>

        <nav aria-label="Il tuo account">
            <ul>
                <?php foreach (pagine_del_menu('azioni', $ruoloCorrente) as $slug => $etichetta): ?>
                    <li>
                        <?php if ($slug === $slugCorrente): ?>
                            <a href="<?php echo e(url($slug)); ?>" aria-current="page"><?php echo e($etichetta); ?></a>
                        <?php else: ?>
                            <a href="<?php echo e(url($slug)); ?>"><?php echo e($etichetta); ?></a>
                        <?php endif; ?>
                    </li>
                <?php endforeach; ?>

                <?php if ($ruoloCorrente === null): ?>
                    <li><a href="<?php echo e(url('accedi')); ?>">Accedi</a></li>
                    <li><a href="<?php echo e(url('registrati')); ?>">Registrati</a></li>
                <?php else: ?>
                    <?php if ($ruoloCorrente !== 'cliente'): ?>
                        <li><a href="<?php echo e(url('controllo')); ?>">Controllo</a></li>
                    <?php endif; ?>
                    <li><a href="<?php echo e(url('esci')); ?>">Esci</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </header>

    <main id="contenuto">
        <?php require __DIR__ . '/breadcrumb.php'; ?>

        <?php $messaggio = messaggio_leggi(); ?>
        <?php if ($messaggio !== null): ?>
            <p class="avviso" role="status" data-tipo="<?php echo e($messaggio['tipo']); ?>">
                <strong><?php echo $messaggio['tipo'] === 'errore' ? 'Errore:' : 'Fatto:'; ?></strong>
                <?php echo e($messaggio['testo']); ?>
            </p>
        <?php endif; ?>
