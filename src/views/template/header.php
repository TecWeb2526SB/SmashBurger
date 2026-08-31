<?php
/**
 * Parte iniziale di ogni pagina: testa del documento, intestazione del sito, menu e
 * apertura del contenuto.
 *
 * Riceve $titolo, $descrizione, $breadcrumb, $ruoloCorrente e $slugCorrente da
 * mostra_pagina(). Le voci del menu derivano da includes/pagine.php e sono gia' filtrate
 * per il ruolo di chi guarda: qui non si interroga il database.
 */
?>
<?php
$tema = tema_corrente();
$classiContenuto = [];

if (in_array($slugCorrente, ['accedi', 'registrati', 'esci'], true)) {
    $classiContenuto = ['pagina-interna', 'pagina-accesso'];
} elseif (in_array($slugCorrente, ['area-personale', 'profilo'], true)) {
    $classiContenuto = ['pagina-interna', 'pagina-account'];
} elseif (in_array($slugCorrente, ['carrello', 'pagamento', 'ricevuta', 'prenota'], true)) {
    $classiContenuto = ['pagina-interna', 'pagina-ordine'];
} elseif (strncmp($slugCorrente, 'controllo', 9) === 0) {
    $classiContenuto = ['pagina-interna', 'pagina-controllo'];
} elseif ($slugCorrente === 'mappa-sito') {
    $classiContenuto = ['pagina-interna', 'pagina-mappa'];
}
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="it" xml:lang="it"<?php echo $tema === '' ? '' : ' class="tema-' . $tema . '"'; ?>>

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

<body id="inizio">
    <a class="salta" href="#contenuto">Vai al contenuto</a>

    <header>
        <div class="header-interna">
            <p class="marchio">
                <a href="<?php echo e(url()); ?>"><?php echo e(NOME_SITO); ?></a>
                <span>Hot off the grill</span>
            </p>

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
                    <li>
                        <form method="post" action="<?php echo e(url('tema')); ?>" class="tema">
                            <?php echo campo_csrf(); ?>
                            <input type="hidden" name="ritorno" value="<?php echo e($slugCorrente); ?>" />
                            <input type="hidden" name="ritorno_query"
                                value="<?php echo e(http_build_query($_GET, '', '&', PHP_QUERY_RFC3986)); ?>" />

                            <button type="submit" name="tema" value="scuro"
                                class="tema-toggle tema-toggle-verso-scuro" aria-label="Passa al tema scuro"
                                title="Passa al tema scuro">
                                <span class="tema-toggle-contenuto" aria-hidden="true">
                                    <?php echo icona('luna'); ?>
                                    <span>Scuro</span>
                                </span>
                            </button>

                            <button type="submit" name="tema" value="chiaro"
                                class="tema-toggle tema-toggle-verso-chiaro" aria-label="Passa al tema chiaro"
                                title="Passa al tema chiaro">
                                <span class="tema-toggle-contenuto" aria-hidden="true">
                                    <?php echo icona('sole'); ?>
                                    <span>Chiaro</span>
                                </span>
                            </button>
                        </form>
                    </li>

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
                        <li><a class="azione-header" href="<?php echo e(url('registrati')); ?>">Registrati</a></li>
                    <?php else: ?>
                        <?php if ($ruoloCorrente !== 'cliente'): ?>
                            <li><a href="<?php echo e(url('controllo')); ?>">Controllo</a></li>
                        <?php endif; ?>
                        <li><a href="<?php echo e(url('esci')); ?>">Esci</a></li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
    </header>

    <div class="pagina-scorribile">
    <main id="contenuto" data-pagina="<?php echo e($slugCorrente === '' ? 'home' : $slugCorrente); ?>"<?php echo $classiContenuto === [] ? '' : ' class="' . e(implode(' ', $classiContenuto)) . '"'; ?>>
        <?php require __DIR__ . '/breadcrumb.php'; ?>

        <?php $messaggio = messaggio_leggi(); ?>
        <?php if ($messaggio !== null): ?>
            <p class="avviso" role="status" data-tipo="<?php echo e($messaggio['tipo']); ?>">
                <strong><?php echo $messaggio['tipo'] === 'errore' ? 'Errore:' : 'Fatto:'; ?></strong>
                <?php echo e($messaggio['testo']); ?>
            </p>
        <?php endif; ?>
