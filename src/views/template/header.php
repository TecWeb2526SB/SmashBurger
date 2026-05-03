<?php
/**
 * header.php: Intestazione comune a tutte le pagine.
 */
global $pdo, $navItems, $isLoggedIn, $canPlaceOrders;

$vResources = file_exists(__DIR__ . '/../../styles/resources.css')
    ? filemtime(__DIR__ . '/../../styles/resources.css')
    : time();

$headerSelectedBranch = null;
$headerAllBranches = [];
$headerCartCount = 0;
$headerCanPlaceOrders = function_exists('can_place_customer_orders')
    ? (!function_exists('is_logged_in') || !is_logged_in() || can_place_customer_orders())
    : true;

if (isset($pdo) && $pdo instanceof \PDO) {
    if (function_exists('branch_get_selected')) {
        try {
            $headerSelectedBranch = branch_get_selected($pdo);
            if (function_exists('branches_get_all')) {
                $headerAllBranches = branches_get_all($pdo);
            }
        } catch (\Throwable $e) {
            $headerSelectedBranch = null;
        }
    }

    if ($headerCanPlaceOrders && function_exists('is_logged_in') && is_logged_in()) {
        $headerCart = cart_get_summary($pdo, (int)$_SESSION['user']['id']);
        $headerCartCount = $headerCart['items_count'] ?? 0;
    }
}

if (isset($selectedBranch) && is_array($selectedBranch) && !empty($selectedBranch['id'])) {
    $headerSelectedBranch = $selectedBranch;
}
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="it" xml:lang="it">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?php echo e($pageTitle ?? 'Smash Burger Original'); ?></title>
    <meta name="description"
        content="<?php echo e($pageDescription ?? 'Scopri l\'autentico Smash Burger: carne croccante fuori e succosa dentro.'); ?>" />
    <meta name="keywords" content="smash burger, fast food, hamburger, domicilio, take away" />
    <meta name="color-scheme" content="light dark" />
    <meta name="theme-color" content="#c0392b" />

    <script>
        //<![CDATA[
        (function () {
            var t = localStorage.getItem('smashburger-tema');
            if (t === 'scuro' || (!t && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('tema-scuro');
            } else if (t === 'chiaro') {
                document.documentElement.classList.add('tema-chiaro');
            }
        }());
        //]]>
    </script>

    <link rel="stylesheet" href="styles/resources.css?v=<?php echo $vResources; ?>" />
    <link rel="icon" type="image/svg+xml" href="images/favicon.svg" />
    <link rel="manifest" href="site.webmanifest" />
</head>

<body data-cart-count="<?php echo (int) $headerCartCount; ?>">
    <a class="skip-link" href="#content">Vai al contenuto</a>
    <header>
        <div class="contenitore">
            <div class="brand-wrap">
                <?php if (!empty($isHomepage)): ?>
                    <p class="brand">Smash Burger</p>
                <?php else: ?>
                    <p class="brand">
                        <a href="./">Smash Burger</a>
                    </p>
                <?php endif; ?>

                <?php if (!empty($headerSelectedBranch)): ?>
                    <div class="header-sede-dropdown">
                        <button
                            id="sede-dropdown-toggle"
                            class="brand-sede"
                            type="button"
                            aria-expanded="false"
                            aria-haspopup="true"
                            aria-controls="sede-dropdown-menu">
                            <?php echo e((string) $headerSelectedBranch['city']); ?>
                            <svg xmlns="http://www.w3.org/2000/svg" class="freccetta-sede" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M6 9l6 6 6-6" />
                            </svg>
                        </button>
                        <div id="sede-dropdown-menu" class="sede-dropdown-menu" hidden="hidden">
                            <p class="sede-dropdown-titolo">Scegli la tua sede</p>
                            <ul>
                                <?php
                                $adminBranchPages = [
                                    'controllo', 'controllo-catalogo', 'controllo-catalogo-prodotto',
                                    'controllo-inventario', 'controllo-inventario-rettifica',
                                    'controllo-forniture', 'controllo-forniture-standard',
                                    'controllo-forniture-straordinaria', 'controllo-forniture-automatico',
                                    'controllo-manager',
                                ];
                                $requestPath = trim((string) parse_url((string) ($_SERVER['REQUEST_URI'] ?? ''), PHP_URL_PATH), '/');
                                $currentRequestPage = $requestPath !== '' ? basename($requestPath) : './';
                                $currentHeaderPage = (string) ($currentPage ?? $currentRequestPage);
                                $returnBasePage = './';
                                if (in_array($currentHeaderPage, ['prodotti', 'sedi'], true) || in_array($currentHeaderPage, $adminBranchPages, true)) {
                                    $returnBasePage = $currentHeaderPage;
                                } elseif (str_starts_with($currentHeaderPage, 'controllo')) {
                                    $returnBasePage = 'controllo';
                                } elseif ($currentHeaderPage !== '') {
                                    $returnBasePage = $currentHeaderPage;
                                }

                                $returnQueryParams = [];
                                foreach ($_GET as $paramKey => $paramValue) {
                                    if (is_string($paramKey) && !in_array($paramKey, ['ajax', 'force'], true) && is_scalar($paramValue)) {
                                        $returnQueryParams[$paramKey] = (string) $paramValue;
                                    }
                                }
                                ?>
                                <?php foreach ($headerAllBranches as $hb):
                                    $isCurrent = (int)$hb['id'] === (int)$headerSelectedBranch['id'];
                                    $hbSlug = (string) $hb['slug'];
                                    $switchUrl = 'prodotti?sede=' . rawurlencode($hbSlug);
                                    $returnParams = $returnQueryParams;
                                    if (in_array($returnBasePage, ['prodotti', 'sedi'], true) || in_array($returnBasePage, $adminBranchPages, true)) {
                                        $returnParams['sede'] = $hbSlug;
                                    } else {
                                        unset($returnParams['sede']);
                                    }
                                    $returnQuery = http_build_query($returnParams);
                                    $returnUrl = $returnBasePage . ($returnQuery !== '' ? '?' . $returnQuery : '');
                                ?>
                                    <li class="<?php echo $isCurrent ? 'corrente' : ''; ?>">
                                        <a href="<?php echo e($switchUrl); ?>"
                                           class="sede-opzione"
                                           data-switch-url="<?php echo e($switchUrl); ?>"
                                           data-return-url="<?php echo e($returnUrl); ?>"
                                           data-sede-slug="<?php echo e($hbSlug); ?>"
                                           data-sede-name="<?php echo e((string) $hb['name']); ?>">
                                            <strong><?php echo e((string) $hb['name']); ?></strong>
                                            <span class="sede-indirizzo"><?php echo e((string) $hb['address_line']); ?></span>
                                            <?php if ($isCurrent): ?>
                                                <span class="badge-corrente">Attiva</span>
                                            <?php endif; ?>
                                        </a>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>
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
                        <li class="<?php echo $isActive ? 'attivo' : ''; ?>">
                            <?php if ($isActive): ?>
                                <span class="nav-item-wrap" aria-current="page">
                                    <?php echo e($label); ?>
                                    <?php if ($isCart): ?>
                                        <span class="badge-notifica <?php echo $headerCartCount > 0 ? '' : 'badge-nascosto'; ?>">
                                            <?php echo (int) $headerCartCount; ?>
                                        </span>
                                    <?php endif; ?>
                                </span>
                            <?php else: ?>
                                <a
                                    href="<?php echo e($href); ?>"
                                    class="nav-item-wrap"
                                    <?php echo $href === 'esci' ? 'data-confirm-logout="true"' : ''; ?>>
                                    <?php echo e($label); ?>
                                    <?php if ($isCart): ?>
                                        <span class="badge-notifica <?php echo $headerCartCount > 0 ? '' : 'badge-nascosto'; ?>">
                                            <?php echo (int) $headerCartCount; ?>
                                        </span>
                                    <?php endif; ?>
                                </a>
                            <?php endif; ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </nav>

            <div class="header-azioni">
                <button id="theme-toggle" type="button" aria-pressed="false" aria-label="Cambia tema"></button>
            </div>
        </div>
    </header>

    <!-- Modal Cambio Sede -->
    <div
        id="modal-cambio-sede"
        class="modal-overlay"
        role="dialog"
        aria-modal="true"
        aria-labelledby="modal-titolo"
        aria-describedby="modal-messaggio"
        aria-hidden="true"
        hidden="hidden">
        <div class="modal-content">
            <h2 id="modal-titolo" class="modal-titolo">Cambiare sede?</h2>
            <p id="modal-messaggio" class="modal-messaggio">
                Hai già dei prodotti nel carrello per un'altra sede. 
                Cambiando sede ora, il tuo carrello attuale verrà svuotato. Vuoi procedere?
            </p>
            <div class="modal-azioni">
                <button type="button" class="modal-bottone modal-bottone-annulla" id="modal-annulla">Annulla</button>
                <button type="button" class="modal-bottone modal-bottone-conferma" id="modal-conferma">Sì, svuota e cambia</button>
            </div>
        </div>
    </div>

    <main id="content">
        <?php include __DIR__ . '/breadcrumb.php'; ?>
