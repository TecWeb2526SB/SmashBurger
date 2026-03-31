<?php
/**
 * header.php: Intestazione comune a tutte le pagine.
 */

$vResources = file_exists(__DIR__ . '/../../styles/resources.css')
    ? filemtime(__DIR__ . '/../../styles/resources.css')
    : time();

$headerSelectedBranch = null;
if (isset($pdo) && $pdo instanceof \PDO && function_exists('branch_get_selected')) {
    try {
        $headerSelectedBranch = branch_get_selected($pdo);
    } catch (\Throwable $e) {
        $headerSelectedBranch = null;
    }
}
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
    <header>
        <div class="contenitore">
            <div class="brand-wrap">
                <span class="brand">
                    <a href="index.php">Smash Burger</a>
                </span>

                <?php if (!empty($headerSelectedBranch)): ?>
                    <p class="brand-sede">
                        <?php echo htmlspecialchars((string) $headerSelectedBranch['city'], ENT_QUOTES, 'UTF-8'); ?>
                    </p>
                <?php endif; ?>
            </div>

            <button id="menu-toggle" type="button" aria-expanded="false" aria-controls="menu-principale">
                Menu
            </button>

            <nav id="menu-principale" aria-label="Navigazione principale">
                <ul>
                    <?php foreach ($navItems as $label => $href):
                        $isActive = isset($currentPage) && $currentPage === $href;
                        $isCart = ($label === 'Carrello');
                    ?>
                        <li class="<?php echo $isActive ? 'attivo' : ''; ?>" <?php echo $isActive ? 'aria-current="page"' : ''; ?>>
                            <?php if ($isActive): ?>
                                <span class="nav-item-wrap">
                                    <?php echo htmlspecialchars($label); ?>
                                    <?php if ($isCart && is_logged_in()): 
                                        $headerCart = cart_get_summary($pdo, (int)$_SESSION['user']['id']);
                                        $cartCount = $headerCart['items_count'] ?? 0;
                                    ?>
                                        <span class="badge-notifica"><?php echo $cartCount; ?></span>
                                    <?php endif; ?>
                                </span>
                            <?php else: ?>
                                <a href="<?php echo htmlspecialchars($href); ?>" class="nav-item-wrap">
                                    <?php echo htmlspecialchars($label); ?>
                                    <?php if ($isCart && is_logged_in()): 
                                        $headerCart = cart_get_summary($pdo, (int)$_SESSION['user']['id']);
                                        $cartCount = $headerCart['items_count'] ?? 0;
                                    ?>
                                        <span class="badge-notifica"><?php echo $cartCount; ?></span>
                                    <?php endif; ?>
                                </a>
                            <?php endif; ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </nav>

            <div class="header-azioni">
                <button id="theme-toggle" type="button" aria-pressed="false"
                    aria-label="Attiva modalità scura">Cambia tema</button>
            </div>
        </div>
    </header>

    <main id="content">
        <?php include_once __DIR__ . '/breadcrumb.php'; ?>
