<?php
/**
 * Pagina di errore HTTP 500 (Internal Server Error).
 * Fornisce un feedback all'utente in caso di fallimento del sistema.
 */
http_response_code(500);
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>500 - Errore Interno del Server</title>
    <!-- Inclusione delle risorse esterne -->
    <link rel="stylesheet" href="/styles/resources.css">
    <script src="/styles/js/main.js" defer></script>
</head>
<body class="status-layout">
    <main class="status-container">
        <h1 class="status-code server-error">500</h1>
        <p class="status-message">Qualcosa è andato storto nei nostri server. Stiamo già lavorando per risolvere il problema.</p>
        <a href="/" class="btn-primary">Torna alla Home</a>
    </main>
</body>
</html>