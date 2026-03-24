<?php
/**
 * 404.php: Pagina di errore HTTP 404 (Not Found).
 */
http_response_code(404);
?>
<!DOCTYPE html>
<html lang="it" xml:lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Pagina non trovata - Smash Burger Original</title>
    <link rel="stylesheet" href="../styles/resources.css">
</head>
<body>

    <nav id="skip-link" aria-label="Salta al contenuto">
        <a href="#content">Vai al contenuto principale</a>
    </nav>

    <main id="content">
        <div class="contenitore">
            <h1>404</h1>
            <p>La pagina che stai cercando non esiste o &egrave; stata spostata.</p>
            <p><a href="../index.php">Torna alla home</a></p>
        </div>
    </main>

</body>
</html>
