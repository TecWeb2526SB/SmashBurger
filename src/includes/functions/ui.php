<?php
/**
 * ui.php: Helper per la generazione di componenti UI comuni e gestione del rendering.
 */

/**
 * Renderizza una pagina completa (Header + View + Footer).
 *
 * @param string $viewPath Percorso della view relativo a src/views/
 * @param array $data Dati da estrarre per la view
 */
function render_page(string $viewPath, array $data = []): void
{
    global $pdo, $navItems, $siteMapItems, $appName, $appVersion, $isLoggedIn, $sessionRole, $canAccessAdminPanel, $canPlaceOrders;

    // Estrai i dati per renderli disponibili come variabili locali
    extract($data);

    // Variabili globali predefinite se non fornite
    $pageTitle       = $data['pageTitle'] ?? 'Smash Burger Original';
    $pageDescription = $data['pageDescription'] ?? '';
    $currentPage     = $data['currentPage'] ?? '';
    $breadcrumb      = $data['breadcrumb'] ?? [];
    $flash           = $data['flash'] ?? flash_get();
    $csrfToken       = $data['csrfToken'] ?? csrf_token();

    // Inclusioni standard
    require __DIR__ . '/../../views/template/header.php';
    require __DIR__ . '/../../views/' . ltrim($viewPath, '/');
    require __DIR__ . '/../../views/template/footer.php';
}

/**
 * Genera un alert HTML (Successo, Errore, Info).
 */
function ui_alert(?array $flash): string
{
    if (empty($flash)) {
        return '';
    }

    $type    = htmlspecialchars($flash['type'] ?? 'info', ENT_QUOTES, 'UTF-8');
    $message = htmlspecialchars($flash['message'] ?? '', ENT_QUOTES, 'UTF-8');

    return '<div class="alert ' . $type . '">' . $message . '</div>';
}

/**
 * Genera un sommario di errori per i form.
 */
function ui_error_summary(array $errori): string
{
    if (empty($errori)) {
        return '';
    }

    $messaggio = htmlspecialchars($errori['generale'] ?? 'Correggi gli errori nel modulo prima di procedere.', ENT_QUOTES, 'UTF-8');

    return '<div role="alert" class="errore-sommario"><p>' . $messaggio . '</p></div>';
}

/**
 * Genera un gruppo campo form (Label + Input + Errore).
 */
function ui_form_group(string $id, string $label, string $type = 'text', array $options = []): string
{
    $value       = $options['value'] ?? '';
    $error       = $options['error'] ?? null;
    $required    = ($options['required'] ?? true) ? 'required="required" aria-required="true"' : '';
    $placeholder = isset($options['placeholder']) ? 'placeholder="' . htmlspecialchars($options['placeholder'], ENT_QUOTES, 'UTF-8') . '"' : '';
    $autocomplete = isset($options['autocomplete']) ? 'autocomplete="' . htmlspecialchars($options['autocomplete'], ENT_QUOTES, 'UTF-8') . '"' : '';
    $extraAttrs  = $options['extra_attrs'] ?? '';
    $ariaInvalid = $error ? 'aria-invalid="true"' : '';
    $errorId = $id . '-errore';
    $describedBy = trim((string) ($options['described_by'] ?? '') . ' ' . $errorId);

    $inputHtml = '';
    if ($type === 'textarea') {
        $inputHtml = '<textarea id="' . $id . '" name="' . $id . '" ' . $required . ' ' . $ariaInvalid . ' aria-describedby="' . $describedBy . '" ' . $extraAttrs . '>' . htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8') . '</textarea>';
    } elseif ($type === 'password') {
        $inputHtml = '
            <div class="campo-password-wrapper">
                <input type="password" id="' . $id . '" name="' . $id . '" ' . $required . ' ' . $autocomplete . ' ' . $ariaInvalid . ' aria-describedby="' . $describedBy . '" ' . $extraAttrs . ' />
                <button type="button" class="mostra-password" aria-pressed="false" aria-label="Tieni premuto per mostrare la password">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icona-password icona-password-chiusa" viewBox="0 0 24 24" focusable="false">
                        <path d="M2 12s3.5-6 10-6 10 6 10 6-3.5 6-10 6-10-6-10-6Z" />
                        <circle cx="12" cy="12" r="3" />
                        <path d="M4 4l16 16" />
                    </svg>
                    <svg xmlns="http://www.w3.org/2000/svg" class="icona-password icona-password-aperta is-hidden" viewBox="0 0 24 24" focusable="false">
                        <path d="M2 12s3.5-6 10-6 10 6 10 6-3.5 6-10 6-10-6-10-6Z" />
                        <circle cx="12" cy="12" r="3" />
                    </svg>
                </button>
            </div>';
    } else {
        $inputHtml = '<input type="' . $type . '" id="' . $id . '" name="' . $id . '" value="' . htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8') . '" ' . $required . ' ' . $placeholder . ' ' . $autocomplete . ' ' . $ariaInvalid . ' aria-describedby="' . $describedBy . '" ' . $extraAttrs . ' />';
    }

    $erroreHtml = '<span id="' . $errorId . '" class="campo-errore" ' . ($error ? '' : 'hidden="hidden"') . '>' . htmlspecialchars($error ?? '', ENT_QUOTES, 'UTF-8') . '</span>';

    return '
        <div class="campo-gruppo">
            <label for="' . $id . '">' . htmlspecialchars($label, ENT_QUOTES, 'UTF-8') . '</label>
            ' . $inputHtml . '
            ' . $erroreHtml . '
        </div>';
}

/**
 * Renderizza una pagina del pannello di controllo, iniettando automaticamente il contesto admin.
 */
function render_admin_page(string $currentSection, array $viewData = [], ?string $customViewPath = null): void
{
    global $pdo;

    $context = admin_panel_bootstrap_context($pdo);
    
    // Sezioni e Navigazione
    $isGeneralAdmin = (string) ($context['utente']['role'] ?? '') === 'admin';
    $canManageBranchManagers = can_manage_branch_managers();
    $selectedBranchSlug = (string) ($context['selectedBranch']['slug'] ?? '');
    
    $sectionMeta = admin_panel_section_meta($currentSection, $canManageBranchManagers);
    $sectionLinks = admin_panel_build_navigation($selectedBranchSlug, $isGeneralAdmin, $canManageBranchManagers, $currentSection);
    
    $sectionUrls = [];
    foreach ($sectionLinks as $link) {
        $sectionUrls[(string) $link['section']] = (string) $link['href'];
    }

    $flash = flash_get();
    $backgroundMessages = admin_panel_background_messages(
        $pdo, 
        (string) ($context['utente']['role'] ?? '') === 'branch_manager', 
        (int) ($context['selectedBranch']['id'] ?? 0), 
        (int) ($context['utente']['id'] ?? 0)
    );

    // Titoli e Breadcrumb predefiniti per l'admin
    $pageTitle = ($sectionMeta['title'] ?? 'Controllo') . ' - Smash Burger Original';
    $breadcrumb = [
        ['Home', './'],
        ['Controllo', 'controllo' . ($isGeneralAdmin ? '?sede=' . rawurlencode($selectedBranchSlug) : '')],
        [$sectionMeta['label'] ?? 'Dettaglio', null],
    ];

    $fullData = array_merge($context, [
        'pageTitle' => $pageTitle,
        'currentPage' => 'controllo',
        'breadcrumb' => $breadcrumb,
        'currentSection' => $currentSection,
        'sectionMeta' => $sectionMeta,
        'sectionLinks' => $sectionLinks,
        'sectionUrls' => $sectionUrls,
        'flash' => $flash,
        'backgroundMessages' => $backgroundMessages,
        'csrfToken' => csrf_token(),
    ], $viewData);

    render_page($customViewPath ?? 'controllo/pannello.php', $fullData);
}

/**
 * Escapes a string for HTML output. Short alias for htmlspecialchars.
 */
function e(string $string): string
{
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}
