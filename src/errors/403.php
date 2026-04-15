<?php
/**
 * 403.php: Pagina di errore HTTP 403 (Forbidden).
 * Autonoma: non include resources.php perché potrebbe essere servita
 * anche quando il bootstrap dell'applicazione fallisce.
 */
http_response_code(403);
?>
<!DOCTYPE html>
<html lang="it" xml:lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 - Accesso negato - Smash Burger Original</title>
    <link rel="stylesheet" href="../styles/resources.css">
</head>
<body>
    <main id="content">
        <div class="contenitore">
            <h1>403</h1>
            <p>Non hai i permessi necessari per visualizzare questa risorsa.</p>
            <p><a href="../">Torna alla home</a></p>
        </div>
    </main>

</body>
</html>
