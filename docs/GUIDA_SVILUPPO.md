# Guida allo Sviluppo - SmashBurger

Questa guida descrive come estendere il progetto SmashBurger rispettando l'architettura e le specifiche definite in `REGOLE.md`.

---

## 1. Come creare una Nuova Pagina
Il progetto segue un pattern **Model-View-Controller (MVC)** semplificato. Per aggiungere una pagina (es: `menu.php`):

1.  **Crea il Controller**: Crea un file `src/menu.php` nella root directory.
    ```php
    <?php
    require_once __DIR__ . '/includes/resources.php'; // Inizializza tutto
    $pageTitle = 'Il Nostro Menù - SmashBurger';    // Variabile per <title>
    $breadcrumbLabel = 'Menù';                      // Etichetta per breadcrumbs

    include_once __DIR__ . '/views/template/header.php'; // Header comune
    include_once __DIR__ . '/views/menu.php';            // Contenuto specifico
    include_once __DIR__ . '/views/template/footer.php'; // Footer comune
    ```
2.  **Crea la View**: Crea il file `src/views/menu.php` con solo il markup del contenuto.
    ```html
    <section>
        <h2>I Nostri Burgers</h2>
        <!-- Contenuto della pagina -->
    </section>
    ```

---

## 2. Implementare Funzioni Backend
Tutta la logica di business deve essere separata dalla presentazione.

*   **Funzioni di Utilità**: Aggiungi script in `src/includes/functions/` (es: `utility.php`) e includili in `resources.php`.
*   **Classi (Modelli)**: Crea classi per la gestione dei dati in `src/includes/class/` (es: `User.php`, `Burger.php`).
*   **Database**: Usa sempre la variabile globale `$pdo` definita in `db_connection.php` e utilizza **Prepared Statements** per la sicurezza.
    ```php
    $stmt = $pdo->prepare("SELECT * FROM burgers WHERE id = ?");
    $stmt->execute([$id]);
    $burger = $stmt->fetch();
    ```

---

## 3. Accedere a phpMyAdmin
Per gestire il database visualmente:
*   **URL**: `http://localhost:8081`
*   **Server**: `db`
*   **Username**: Quello definito nel file `.env` (es: `admin_db`)
*   **Password**: Quella definita nel file `.env` (es: `SuperSegreta!2026`)

---

## 4. Scrittura del Codice (HTML, CSS, JS)

### HTML (Struttura)
*   Usa **tag semantici** HTML5 (`<nav>`, `<main>`, `<article>`, `<section>`, etc.).
*   **Accessibilità**: Ogni immagine deve avere `alt=""` (se decorativa) o descrittivo. I link devono avere testi chiari.
*   **Link**: Usa **esclusivamente link relativi** (es: `href="menu.php"` o `src="styles/img/logo.png"`).

### CSS (Presentazione)
*   I file CSS vanno in `src/styles/css/`.
*   Importa i nuovi file in `src/styles/resources.css` tramite `@import`.
*   Usa **unità relative** (`em`, `rem`, `%`) per font e dimensioni.
*   Garantisci un contrasto minimo di 4.5:1 per il testo.

### JavaScript (Comportamento)
*   I file JS vanno in `src/styles/js/`.
*   Usa l'attributo `defer` nel tag `<script>` per non bloccare il caricamento della pagina.
*   Assicurati che le funzionalità di base funzionino anche se JS è disabilitato (**Graceful Degradation**).

---

## 5. Validazione
Prima di consegnare:
1.  **HTML**: Valida il markup con [W3C Validator](https://validator.w3.org/). Deve essere privo di errori.
2.  **CSS**: Valida i fogli di stile con [W3C CSS Validator](https://jigsaw.w3.org/css-validator/).
3.  **Accessibilità**: Controlla l'accessibilità con Lighthouse, WAVE o Mauve per garantire la conformità WCAG 2.1 AA.

---

## 6. Ottimizzazione SEO (Search Engine Optimization)
Come da specifica (punto 6), ogni pagina deve essere ottimizzata per i motori di ricerca:

*   **Titolo della Pagina (`$pageTitle`)**:
    - Massimo **60 caratteri**.
    - Deve essere unico per ogni pagina (es: "Menù | SmashBurger").
*   **Descrizione della Pagina (`$pageDescription`)**:
    - Definisci questa variabile in ogni controller prima di includere l'header.
    - Scrivi una descrizione concisa e attraente del contenuto della pagina.
*   **Markup Semantico**:
    - L'uso corretto di `<h1>`, `<h2>`, `<article>`, etc., non serve solo all'accessibilità, ma aiuta i crawler dei motori di ricerca a capire la gerarchia dei contenuti.
*   **Validazione W3C**:
    - Un codice senza errori (HTML e CSS) è premiato dai motori di ricerca.

