<?php
/**
 * header.php: Intestazione comune a tutte le pagine.
 *
 * Variabili attese dal controller:
 *   $pageTitle       string  Titolo della pagina (max 60 car.)
 *   $pageDescription string  Meta description
 *   $currentPage     string  Nome del file controller (es. 'prodotti.php')
 *   $isHomepage      bool    true solo nella home → usa <h1> per il brand
 *
 * $navItems viene da includes/variables.php (caricato via resources.php).
 */

$vResources = file_exists(__DIR__ . '/../../styles/resources.css')
    ? filemtime(__DIR__ . '/../../styles/resources.css')
    : time();
?>
<!DOCTYPE html>
<html lang="it" xml:lang="it">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($pageTitle ?? 'Smash Burger Original'); ?></title>
    <meta name="description"
        content="<?php echo htmlspecialchars($pageDescription ?? 'Scopri l\'autentico Smash Burger: carne croccante fuori e succosa dentro.'); ?>">
    <meta name="keywords" content="smash burger, fast food, hamburger, domicilio, take away">
    <meta name="color-scheme" content="light dark">

    <script>
        (function () {
            var t = localStorage.getItem('smashburger-tema');
            if (t === 'scuro' || (!t && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('tema-scuro');
            } else if (t === 'chiaro') {
                document.documentElement.classList.add('tema-chiaro');
            }
        }());
    </script>

    <link rel="stylesheet" href="styles/resources.css?v=<?php echo $vResources; ?>">

    <link rel="shortcut icon" href="images/favicon.ico">
</head>

<body>

    <nav id="skip-link" aria-label="Salta al contenuto">
        <a href="#content">Vai al contenuto principale</a>
    </nav>

    <header>
        <div class="contenitore">

            <?php if (!empty($isHomepage)): ?>
                <h1 class="brand">
                    <a href="index.php">Smash Burger</a>
                </h1>
            <?php else: ?>
                <p class="brand">
                    <a href="index.php">Smash Burger</a>
                </p>
            <?php endif; ?>

            <button id="menu-toggle" type="button" aria-expanded="false" aria-controls="menu-principale">
                Menu
            </button>

            <nav id="menu-principale" aria-label="Navigazione principale">
                <ul>
                    <?php foreach ($navItems as $label => $href):
                        $isActive = isset($currentPage) && $currentPage === $href;
                    ?>
                        <?php if ($isActive): ?>
                            <li class="attivo" aria-current="page">
                                <?php echo htmlspecialchars($label); ?>
                            </li>
                        <?php else: ?>
                            <li>
                                <a href="<?php echo htmlspecialchars($href); ?>">
                                    <?php echo htmlspecialchars($label); ?>
                                </a>
                            </li>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </ul>
            </nav>

            <button id="theme-toggle" type="button" aria-pressed="false"
                aria-label="Attiva modalità scura">Cambia tema</button>

        </div>
    </header>

    <main id="content">
        <?php include_once __DIR__ . '/breadcrumb.php'; ?>
