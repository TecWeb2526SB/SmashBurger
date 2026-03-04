<?php
/**
 * Pagina di errore HTTP 403 (Forbidden).
 * Utilizza le classi semantiche definite nel file CSS globale.
 */
http_response_code(403);
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 - Accesso Negato</title>
    <!-- Inclusione delle risorse esterne -->
    <link rel="stylesheet" href="/styles/resources.css">
    <script src="/styles/js/main.js" defer></script>
</head>
<body class="status-layout">
    <main class="status-container">
        <h1 class="status-code forbidden">403</h1>
        <p class="status-message">Non hai i permessi necessari per visualizzare questa risorsa.</p>
        <a href="/" class="btn-primary">Richiedi Accesso / Home</a>
    </main>
</body>
</html>