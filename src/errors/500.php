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
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        
        :root {
            --colore-accento: #c0392b;
            --colore-accento-hover: #a5281b;
            --sfondo-scuro: #1a1a1a;
            --testo-chiaro: #f5f5f5;
            --testo-secondario: #a0a0a0;
            --bordo-chiaro: rgba(255, 255, 255, 0.1);
        }
        
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background-color: var(--sfondo-scuro);
            color: var(--testo-chiaro);
            line-height: 1.5;
        }
        
        .error-page {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
            position: relative;
            overflow: hidden;
        }
        
        .error-page::before {
            content: "";
            position: absolute;
            inset: 0;
            background-image: 
                radial-gradient(circle at 20% 80%, rgba(192, 57, 43, 0.08) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, rgba(192, 57, 43, 0.05) 0%, transparent 40%);
            pointer-events: none;
        }
        
        .error-box {
            text-align: center;
            max-width: 36rem;
            position: relative;
            z-index: 1;
        }
        
        .error-visual {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }
        
        .error-icon {
            width: 80px;
            height: 80px;
            opacity: 0.9;
        }
        
        .error-code {
            font-size: clamp(5rem, 15vw, 9rem);
            font-weight: 800;
            color: var(--colore-accento);
            line-height: 1;
            letter-spacing: -0.04em;
        }
        
        .error-title {
            font-size: clamp(1.5rem, 4vw, 2rem);
            font-weight: 700;
            margin: 0 0 1rem;
            color: var(--testo-chiaro);
        }
        
        .error-desc {
            color: var(--testo-secondario);
            line-height: 1.7;
            margin: 0 0 2.5rem;
            font-size: 1.05rem;
        }
        
        .error-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
            justify-content: center;
        }
        
        .error-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            min-height: 3rem;
            padding: 0.75rem 1.75rem;
            border-radius: 0.5rem;
            font-weight: 600;
            font-size: 0.95rem;
            text-decoration: none;
            transition: background-color 0.2s, transform 0.2s, box-shadow 0.2s, border-color 0.2s;
            min-width: 10rem;
        }
        
        .error-btn:hover {
            transform: translateY(-2px);
        }
        
        .error-btn:active {
            transform: translateY(0);
        }
        
        .error-btn-primary {
            background-color: var(--colore-accento);
            color: #fff;
            border: 1px solid transparent;
            box-shadow: 0 2px 8px rgba(192, 57, 43, 0.25);
        }
        
        .error-btn-primary:hover {
            background-color: var(--colore-accento-hover);
            box-shadow: 0 6px 20px rgba(192, 57, 43, 0.35);
        }
        
        .error-btn-secondary {
            background-color: transparent;
            color: var(--testo-chiaro);
            border: 1px solid var(--bordo-chiaro);
        }
        
        .error-btn-secondary:hover {
            border-color: var(--colore-accento);
            color: var(--colore-accento);
            background-color: rgba(192, 57, 43, 0.08);
        }
        
        .error-footer {
            margin-top: 3rem;
            padding-top: 2rem;
            border-top: 1px solid var(--bordo-chiaro);
        }
        
        .error-logo {
            font-weight: 800;
            font-size: 1.1rem;
            color: var(--testo-chiaro);
            text-decoration: none;
            letter-spacing: -0.02em;
        }
        
        .error-logo span {
            color: var(--colore-accento);
        }
    </style>
</head>
<body>
    <main id="content" class="error-page">
        <div class="error-box">
            <div class="error-visual">
                <svg class="error-icon" viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                    <!-- Fuoco / Fiamma -->
                    <path d="M50 90 Q30 70 35 50 Q38 35 50 25 Q45 45 55 50 Q60 35 55 20 Q80 40 75 60 Q72 75 50 90" fill="#c0392b"/>
                    <path d="M50 90 Q38 75 42 60 Q45 50 50 42 Q48 55 55 58 Q58 48 55 38 Q70 50 67 65 Q64 78 50 90" fill="#e74c3c"/>
                    <path d="M50 90 Q42 78 45 68 Q47 60 50 55 Q49 62 53 65 Q55 58 53 50 Q62 58 60 70 Q58 80 50 90" fill="#f39c12"/>
                </svg>
                <span class="error-code">500</span>
            </div>
            
            <h1 class="error-title">Ops, abbiamo bruciato qualcosa!</h1>
            <p class="error-desc">
                C&apos;&egrave; stato un problema in cucina e qualcosa &egrave; andato storto.
                I nostri chef stanno gi&agrave; sistemando tutto.
                Riprova tra qualche istante o torna alla home.
            </p>
            
            <div class="error-actions">
                <a href="../" class="error-btn error-btn-primary">Torna alla home</a>
                <a href="javascript:location.reload()" class="error-btn error-btn-secondary">Riprova</a>
            </div>
            
            <footer class="error-footer">
                <a href="../" class="error-logo">Smash<span>Burger</span></a>
            </footer>
        </div>
    </main>
</body>
</html>
