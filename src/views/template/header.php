<?php
/**
 * header.php: Frammento di codice per l'intestazione comune di tutte le pagine.
 */
?>
<!DOCTYPE html>
<html lang="it" xml:lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?php echo isset($pageDescription) ? $pageDescription : 'Il miglior smash burger della città, preparato con ingredienti freschi e di alta qualità.'; ?>">
    <title><?php echo isset($pageTitle) ? $pageTitle : 'SmashBurger - Gusto e Qualità'; ?></title>
    <!-- Unico file CSS centrale (uso di link relativo) -->
    <link rel="stylesheet" href="styles/resources.css">
    <!-- Script JavaScript posticipato -->
    <script src="styles/js/main.js" defer></script>
</head>
<body>
    <!-- Link invisibile per accessibilità (Skip to content) -->
    <a href="#main-content" class="sr-only">Vai al contenuto</a>

    <header class="main-header">
        <h1>Benvenuti in SmashBurger</h1>
        <nav aria-label="Navigazione principale">
            <ul>
                <?php foreach ($mainNavItems as $label => $link): ?>
                    <li><a href="<?php echo $link; ?>"><?php echo $label; ?></a></li>
                <?php endforeach; ?>
            </ul>
        </nav>
    </header>

    <!-- Briciole di pane (Breadcrumbs) per orientamento utente -->
    <nav aria-label="Percorso di navigazione" class="breadcrumbs">
        <ul>
            <li><a href="index.php">Home</a></li>
            <?php if (isset($breadcrumbLabel)): ?>
                <li> / <?php echo $breadcrumbLabel; ?></li>
            <?php endif; ?>
        </ul>
    </nav>

    <!-- Inizio contenuto principale con ID per il salto della navigazione -->
    <main id="main-content" class="content-container">
