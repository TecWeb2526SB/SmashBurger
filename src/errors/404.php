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
        
        /* Pattern decorativo di sfondo */
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
        
        .error-burger {
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
                <svg class="error-burger" viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
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
                <span class="error-code">404</span>
            </div>
            
            <h1 class="error-title">Ops, panino non trovato!</h1>
            <p class="error-desc">
                Questo ingrediente non &egrave; nel nostro menu. 
                Forse &egrave; finito o non &egrave; mai esistito. 
                Ma tranquillo, abbiamo tanti altri burger che aspettano solo te!
            </p>
            
            <div class="error-actions">
                <a href="../" class="error-btn error-btn-primary">Torna alla home</a>
                <a href="../prodotti" class="error-btn error-btn-secondary">Scopri il menu</a>
            </div>
            
            <footer class="error-footer">
                <a href="../" class="error-logo">Smash<span>Burger</span></a>
            </footer>
        </div>
    </main>
</body>
</html>
