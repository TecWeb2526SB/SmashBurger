<?php
/**
 * Pagina mostrata per l'errore HTTP 403.
 *
 * Non dipende dal resto dell'applicazione: viene servita anche quando il database non
 * risponde, quindi non carica includes né funzioni.
 */

http_response_code(403);
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="it" xml:lang="it">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Accesso non consentito</title>
    <link rel="stylesheet" href="styles/stile.css" />
</head>

<body>
    <main>
        <h1>Accesso non consentito</h1>
        <p>Non hai i permessi per vedere questa pagina.</p>
        <p><a href="./">Torna alla home</a></p>
    </main>
</body>

</html>
