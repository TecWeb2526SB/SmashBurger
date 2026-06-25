<?php
/**
 * 404.php: Pagina di errore HTTP 404 (Not Found).
 * Autonoma: non include resources.php perché potrebbe essere servita
 * anche quando il bootstrap dell'applicazione fallisce.
 */
http_response_code(404);
?>
<!DOCTYPE html>
<html lang="it" xml:lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Panino non trovato - Smash Burger Original</title>
    <link rel="stylesheet" href="../styles/resources.css">
</head>
<body>
    <main id="content" class="error-page">
        <article>
            <figure aria-hidden="true">
                <svg viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <!-- Bun superiore -->
                    <path d="M15 45 Q15 20 50 20 Q85 20 85 45 Z" fill="#D4A056"/>
                    <ellipse cx="30" cy="32" rx="3" ry="2" fill="#E8C078"/>
                    <ellipse cx="50" cy="28" rx="2" ry="1.5" fill="#E8C078"/>
                    <ellipse cx="70" cy="34" rx="2.5" ry="1.5" fill="#E8C078"/>
                    <!-- Lattuga -->
                    <path d="M12 48 Q20 52 30 46 Q40 52 50 46 Q60 52 70 46 Q80 52 88 48" stroke="#4CAF50" stroke-width="6" stroke-linecap="round" fill="none"/>
                    <!-- Pomodoro -->
                    <rect x="18" y="52" width="64" height="8" rx="2" fill="#E53935"/>
                    <!-- Formaggio -->
                    <path d="M14 62 L20 68 L86 68 L86 62 Z" fill="#FFC107"/>
                    <!-- Patty -->
                    <rect x="14" y="68" width="72" height="12" rx="3" fill="#5D4037"/>
                    <!-- Bun inferiore -->
                    <path d="M14 82 Q14 92 50 92 Q86 92 86 82 Z" fill="#D4A056"/>
                </svg>
                <strong>404</strong>
            </figure>

            <h1>Ops, panino non trovato!</h1>
            <p>
                Questo ingrediente non &egrave; nel nostro menu.
                Forse &egrave; finito o non &egrave; mai esistito.
                Ma tranquillo, abbiamo tanti altri burger che aspettano solo te!
            </p>

            <nav aria-label="Azioni errore">
                <a href="../">Torna alla home</a>
                <a href="../prodotti">Scopri il menu</a>
            </nav>

            <footer>
                <a href="../">Smash<span>Burger</span></a>
            </footer>
        </article>
    </main>
</body>
</html>
