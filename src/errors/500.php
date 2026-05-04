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
    <title>500 - Panino bruciato - Smash Burger Original</title>
    <link rel="stylesheet" href="../styles/resources.css">
    <style>
        .error-page {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
            background-color: #fafafa;
        }
        .error-box {
            text-align: center;
            max-width: 32rem;
        }
        .error-icon {
            font-size: 5rem;
            margin-bottom: 1rem;
            line-height: 1;
        }
        .error-code {
            font-size: clamp(4rem, 12vw, 8rem);
            font-weight: 800;
            color: #c0392b;
            line-height: 1;
            margin: 0 0 0.5rem;
            letter-spacing: -0.04em;
        }
        .error-title {
            font-size: 1.5rem;
            font-weight: 700;
            margin: 0 0 1rem;
            color: #1a1a1a;
        }
        .error-desc {
            color: #666;
            line-height: 1.6;
            margin: 0 0 2rem;
        }
        .error-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 0.75rem;
            justify-content: center;
        }
        .error-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.75rem 1.5rem;
            border-radius: 999px;
            font-weight: 600;
            text-decoration: none;
            transition: transform 0.15s, box-shadow 0.15s;
        }
        .error-btn:hover {
            transform: translateY(-2px);
        }
        .error-btn-primary {
            background-color: #c0392b;
            color: #fff;
        }
        .error-btn-primary:hover {
            box-shadow: 0 4px 12px rgba(192, 57, 43, 0.3);
        }
        .error-btn-secondary {
            background-color: #fff;
            color: #1a1a1a;
            border: 1px solid #e0e0e0;
        }
        .error-btn-secondary:hover {
            border-color: #c0392b;
            color: #c0392b;
        }
    </style>
</head>
<body>
    <main id="content" class="error-page">
        <div class="error-box">
            <div class="error-icon" aria-hidden="true">&#128293;</div>
            <p class="error-code">500</p>
            <h1 class="error-title">Ops, abbiamo bruciato qualcosa!</h1>
            <p class="error-desc">
                La nostra cucina ha avuto un piccolo incidente tecnico. 
                I nostri chef stanno gi&agrave; sistemando tutto. 
                Riprova tra qualche istante!
            </p>
            <div class="error-actions">
                <a href="../" class="error-btn error-btn-primary">
                    <span aria-hidden="true">&#8592;</span> Torna alla home
                </a>
                <a href="javascript:location.reload()" class="error-btn error-btn-secondary">
                    Riprova
                </a>
            </div>
        </div>
    </main>
</body>
</html>
