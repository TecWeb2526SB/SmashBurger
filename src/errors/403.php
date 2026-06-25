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
    <main id="content" class="error-page">
        <article>
            <figure aria-hidden="true">
                <svg viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <!-- Lucchetto -->
                    <rect x="25" y="45" width="50" height="40" rx="5" fill="#ce493c"/>
                    <path d="M35 45 V35 Q35 15 50 15 Q65 15 65 35 V45" stroke="#f5f5f5" stroke-width="6" fill="none" stroke-linecap="round"/>
                    <!-- Buco serratura -->
                    <circle cx="50" cy="62" r="6" fill="#1a1a1a"/>
                    <rect x="47" y="62" width="6" height="12" rx="1" fill="#1a1a1a"/>
                </svg>
                <strong>403</strong>
            </figure>

            <h1>Ehi, questa cucina &egrave; riservata!</h1>
            <p>
                Non hai il permesso di entrare in questa zona.
                Solo lo staff autorizzato pu&ograve; accedere qui.
                Se pensi ci sia un errore, prova ad accedere con le tue credenziali.
            </p>

            <nav aria-label="Azioni errore">
                <a href="../">Torna alla home</a>
                <a href="../accedi">Accedi</a>
            </nav>

            <footer>
                <a href="../">Smash<span>Burger</span></a>
            </footer>
        </article>
    </main>
</body>
</html>
