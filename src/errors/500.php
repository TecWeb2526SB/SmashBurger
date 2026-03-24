<?php
/**
 * 500.php: Pagina di errore HTTP 500 (Internal Server Error).
 * Non include resources.php: viene servita proprio quando il bootstrap fallisce.
 */
http_response_code(500);
?>
<!DOCTYPE html>
<html lang="it" xml:lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>500 - Errore del server - Smash Burger Original</title>
    <link rel="stylesheet" href="../styles/resources.css">
</head>
<body>

    <nav id="skip-link" aria-label="Salta al contenuto">
        <a href="#content">Vai al contenuto principale</a>
    </nav>

    <main id="content">
        <div class="contenitore">
            <h1>500</h1>
            <p>Qualcosa &egrave; andato storto nei nostri server. Stiamo lavorando per risolvere il problema.</p>
            <p><a href="../index.php">Torna alla home</a></p>
        </div>
    </main>

</body>
</html>
