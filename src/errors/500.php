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
    <main id="content" class="error-page">
        <article>
            <figure aria-hidden="true">
                <svg viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <!-- Fuoco / Fiamma -->
                    <path d="M50 90 Q30 70 35 50 Q38 35 50 25 Q45 45 55 50 Q60 35 55 20 Q80 40 75 60 Q72 75 50 90" fill="#ce493c"/>
                    <path d="M50 90 Q38 75 42 60 Q45 50 50 42 Q48 55 55 58 Q58 48 55 38 Q70 50 67 65 Q64 78 50 90" fill="#e74c3c"/>
                    <path d="M50 90 Q42 78 45 68 Q47 60 50 55 Q49 62 53 65 Q55 58 53 50 Q62 58 60 70 Q58 80 50 90" fill="#f39c12"/>
                </svg>
                <strong>500</strong>
            </figure>

            <h1>Ops, abbiamo bruciato qualcosa!</h1>
            <p>
                C&apos;&egrave; stato un problema in cucina e qualcosa &egrave; andato storto.
                I nostri chef stanno gi&agrave; sistemando tutto.
                Riprova tra qualche istante o torna alla home.
            </p>

            <nav aria-label="Azioni errore">
                <a href="../">Torna alla home</a>
                <a href="500.php">Riprova</a>
            </nav>

            <footer>
                <a href="../">Smash<span>Burger</span></a>
            </footer>
        </article>
    </main>
</body>
</html>
