<?php
/**
 * Parte iniziale di ogni pagina: testa del documento, intestazione del sito, menu e
 * apertura del contenuto.
 *
 * Riceve $titolo, $descrizione, $breadcrumb, $ruoloCorrente e $slugCorrente da
 * mostra_pagina(). Le voci del menu derivano da includes/pagine.php e sono già filtrate
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
    <!-- Il foglio base vale anche su carta: stampa.css contiene i soli scostamenti,
         quindi senza media="all" la stampa uscirebbe priva di stile. -->
    <link rel="stylesheet" media="all" href="<?php echo e(risorsa('styles/stile.css', true)); ?>" />
    <link rel="stylesheet" media="screen and (max-width: 48em)" href="<?php echo e(risorsa('styles/mobile.css', true)); ?>" />
    <link rel="stylesheet" media="print" href="<?php echo e(risorsa('styles/stampa.css', true)); ?>" />
    <link rel="icon" type="image/png" href="<?php echo e(risorsa('images/favicon.png', true)); ?>" />
    <link rel="manifest" href="<?php echo e(risorsa('site.webmanifest')); ?>" />
</head>

<body id="inizio">
    <a class="salta" href="#contenuto">Vai al contenuto</a>

    <header>
        <div class="header-interna">
            <p class="marchio">
                <a href="<?php echo e(url()); ?>">
                    <img src="<?php echo e(risorsa('images/logo.webp', true)); ?>"
                        width="192" height="180" alt="" />
                    <img class="marchio-stampa" src="<?php echo e(risorsa('images/logo-stampa.webp', true)); ?>"
                        width="192" height="180" alt="" />
                    <span class="solo-lettori"><?php echo e(NOME_SITO); ?></span>
                </a>
            </p>

            <button type="button" aria-expanded="false" aria-controls="menu-sito" title="Apri il menu">
                <?php echo icona('menu'); ?><span class="solo-lettori">Apri il menu</span>
            </button>

            <div class="menu-sito" id="menu-sito">
                <button type="button" title="Chiudi il menu">
                    <?php echo icona('croce'); ?><span class="solo-lettori">Chiudi il menu</span>
                </button>

                <nav aria-label="Navigazione principale">
                    <ul>
                        <?php foreach (pagine_del_menu('principale', $ruoloCorrente) as $slug => $etichetta): ?>
                            <li>
                                <?php if ($slug === $slugCorrente): ?>
                                    <span aria-current="page"><?php echo e($etichetta); ?></span>
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
                                    <span aria-current="page"><?php echo e($etichetta); ?></span>
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
        </div>
    </header>

    <div class="pagina-scorribile">
    <main id="contenuto" data-pagina="<?php echo e($slugCorrente === '' ? 'home' : $slugCorrente); ?>"<?php echo $classiContenuto === [] ? '' : ' class="' . e(implode(' ', $classiContenuto)) . '"'; ?>>
        <?php require __DIR__ . '/breadcrumb.php'; ?>

        <?php $messaggio = messaggio_leggi(); ?>
        <?php if ($messaggio !== null): ?>
            <p class="avviso" role="status" tabindex="-1" data-tipo="<?php echo e($messaggio['tipo']); ?>">
                <strong><?php echo $messaggio['tipo'] === 'errore' ? 'Errore:' : 'Fatto:'; ?></strong>
                <?php echo e($messaggio['testo']); ?>
            </p>
        <?php endif; ?>
