<?php
/**
 * Pagina mostrata per l'errore HTTP 404.
 *
 * Non dipende dal resto dell'applicazione: viene servita anche quando il database non
 * risponde, quindi non carica includes né funzioni.
 */

http_response_code(404);
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="it" xml:lang="it">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Pagina non trovata</title>
    <link rel="stylesheet" href="styles/stile.css" />
</head>

<body>
    <main>
        <h1>Pagina non trovata</h1>
        <p>L'indirizzo richiesto non corrisponde a nessuna pagina del sito.</p>
        <p><a href="./">Torna alla home</a></p>
        <p><a href="prodotti">Vai al menu</a></p>
    </main>
</body>

</html>
