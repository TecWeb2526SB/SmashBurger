<?php
/**
 * Pagina di errore HTTP 404 (Not Found).
 * Carica stili e logica esterni per garantire pulizia e consistenza.
 */
http_response_code(404);
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Risorsa Non Trovata</title>
    <!-- Inclusione delle risorse esterne -->
    <link rel="stylesheet" href="/styles/resources.css">
    <script src="/styles/js/main.js" defer></script>
</head>
<body class="status-layout">
    <main class="status-container">
        <h1 class="status-code not-found">404</h1>
        <p class="status-message">La pagina che stai cercando non esiste o è stata spostata.</p>
        <a href="/" class="btn-primary">Torna alla Home</a>
    </main>
</body>
</html>