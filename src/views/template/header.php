<?php
/**
 * header.php: Frammento di codice per l'intestazione comune di tutte le pagine.
 */
?>
<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($pageTitle) ? $pageTitle : 'Home - Smash Burger Original | Il vero gusto dello smash'; ?>
    </title>
    <meta name="description"
        content="<?php echo isset($pageDescription) ? $pageDescription : 'Scopri l\'autentico Smash Burger: carne croccante fuori e succosa dentro. Ordina a domicilio o ritira in sede.'; ?>">
    <meta name="keywords" content="smash burger, fast food, hamburger, domicilio, take away">
    <meta name="color-scheme" content="light dark">

    <!-- Script inline: applica il tema salvato PRIMA del render per evitare il flash di colori sbagliati -->
    <script>
        (function () {
            var tema = localStorage.getItem('smashburger-tema');
            if (tema === 'scuro' || (!tema && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('tema-scuro');
            } else if (tema === 'chiaro') {
                document.documentElement.classList.add('tema-chiaro');
            }
        }());
    </script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700;800&display=swap" rel="stylesheet">

    <?php
    $v = file_exists(__DIR__ . '/../../styles/css/style.css')
        ? filemtime(__DIR__ . '/../../styles/css/style.css')
        : time();
    ?>
    <link rel="stylesheet" href="styles/css/style.css?v=<?php echo $v; ?>" media="screen">
    <link rel="stylesheet" href="styles/css/mobile.css" media="screen">
    <link rel="stylesheet" href="styles/css/print.css" media="print">

    <link rel="shortcut icon" href="images/favicon.ico">
</head>

<body>

    <!-- Skip link -->
    <nav id="skip-link" aria-label="Salta al contenuto">
        <a href="#content">Vai al contenuto principale</a>
    </nav>

    <header>
        <div class="contenitore">

            <?php if (isset($isHomepage) && $isHomepage): ?>
                <!-- h1 SOLO nella home -->
                <h1 class="brand">Smash Burger</h1>
            <?php else: ?>
                <p class="brand">Smash Burger</p>
            <?php endif; ?>

            <nav id="menu-principale" aria-label="Navigazione principale">
                <ul>
                    <?php
                    $navItems = [
                        'Home' => 'index.php',
                        'I nostri prodotti' => 'prodotti.php',
                        'Servizi' => 'servizi.php',
                        'Chi siamo' => 'sedi.php',
                        'Accedi' => 'login.php',
                    ];
                    foreach ($navItems as $label => $href):
                        $isActive = isset($currentPage) && $currentPage === $href;
                        ?>
                        <?php if ($isActive): ?>
                            <li class="attivo" aria-current="page"><?php echo $label; ?></li>
                        <?php else: ?>
                            <li><a href="<?php echo $href; ?>"><?php echo $label; ?></a></li>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </ul>
            </nav>

            <button id="theme-toggle" type="button" aria-label="Cambia tema"></button>

        </div>
    </header>

    <main id="content">