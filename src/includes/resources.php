<?php
/**
 * resources.php: File centrale per l'inclusione ordinata degli script necessari.
 */

// 1. Carica la configurazione e le costanti globali
require_once __DIR__ . '/config.php';

// 2. Carica le variabili globali del sito
require_once __DIR__ . '/variables.php';

// 3. Carica le classi e le funzioni (da popolare man mano)
require_once __DIR__ . '/functions/security.php';
require_once __DIR__ . '/functions/auth.php';
require_once __DIR__ . '/functions/shop.php';
require_once __DIR__ . '/functions/admin.php';
require_once __DIR__ . '/functions/ui.php';
// require_once __DIR__ . '/functions/utility.php';
// require_once __DIR__ . '/class/Database.php';

// 4. Carica la connessione al database
require_once __DIR__ . '/db_connection.php';

/**
 * Gestione centralizzata del cambio sede.
 *
 * La selezione viene inviata via POST con CSRF, senza query string.
 */
$isBranchChangeRequest = $_SERVER['REQUEST_METHOD'] === 'POST'
    && isset($_POST['branch_action'])
    && (string) $_POST['branch_action'] === 'cambia_sede';

if ($isBranchChangeRequest) {
    $branchSlug = trim((string) ($_POST['branch_slug'] ?? ''));
    $force = (string) ($_POST['branch_force'] ?? '0') === '1';
    $redirectToRaw = (string) ($_POST['branch_redirect'] ?? './');
    $token = $_POST['csrf_token'] ?? null;
    $isAjax = isset($_SERVER['HTTP_X_REQUESTED_WITH'])
        && strtolower((string) $_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';

    $response = [
        'ok' => false,
        'message' => '',
    ];

    if (!csrf_is_valid($token)) {
        $response['message'] = 'Operazione non autorizzata.';
    } elseif ($branchSlug === '') {
        $response['message'] = 'Sede non valida.';
    } else {
        if (branch_select_by_slug($pdo, $branchSlug)) {
            $branch = branch_get_selected($pdo);
            $sync = ['ok' => true, 'message' => null];

            if (!empty($branch) && is_logged_in() && function_exists('cart_get_active_row')) {
                $selectedBranchId = (int) ($branch['id'] ?? 0);
                $sync = cart_sync_with_selected_branch($pdo, (int) $_SESSION['user']['id'], $selectedBranchId, $force);
            }

            if ($sync['ok']) {
                $response['ok'] = true;
            }
            $response['message'] = (string) ($sync['message'] ?? '');
        } else {
            $response['message'] = 'La sede richiesta non è disponibile.';
        }
    }

    if ($isAjax) {
        header('Content-Type: application/json');
        if (!$response['ok'] && $response['message'] !== '') {
            http_response_code(200);
        }

        echo json_encode($response);
        exit;
    }

    $safeRedirect = auth_normalize_redirect_target((string) parse_url($redirectToRaw, PHP_URL_PATH), './');
    if ($response['message'] !== '') {
        flash_set($response['ok'] ? 'success' : 'error', $response['message']);
    }

    header('Location: ' . $safeRedirect);
    exit;
}
