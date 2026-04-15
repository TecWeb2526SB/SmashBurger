<?php
require_once __DIR__ . '/includes/resources.php';

function admin_product_flow_default_draft(): array
{
    return [
        'category_id' => '',
        'name' => '',
        'description' => '',
        'allergens' => '',
        'price_cents' => '',
        'is_available' => '1',
        'image_path' => '',
        'image_focus_x' => '50',
        'image_focus_y' => '50',
    ];
}

function admin_product_flow_upload_image(array $file): string
{
    if (($file['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
        throw new RuntimeException('Caricamento immagine non riuscito.');
    }

    $tmpName = (string) ($file['tmp_name'] ?? '');
    if ($tmpName === '' || !is_uploaded_file($tmpName)) {
        throw new RuntimeException('File immagine non valido.');
    }

    $size = (int) ($file['size'] ?? 0);
    if ($size <= 0 || $size > 1024 * 1024) {
        throw new RuntimeException('L immagine deve pesare al massimo 1 MB.');
    }

    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mime = (string) $finfo->file($tmpName);
    $allowedMimes = [
        'image/jpeg' => 'jpg',
        'image/png' => 'png',
        'image/webp' => 'webp',
    ];

    if (!isset($allowedMimes[$mime])) {
        throw new RuntimeException('Formato immagine non supportato. Usa JPG, PNG o WEBP.');
    }

    $targetDir = __DIR__ . '/uploads/products';
    if (!is_dir($targetDir) && !mkdir($targetDir, 0755, true) && !is_dir($targetDir)) {
        throw new RuntimeException('Impossibile creare la cartella immagini del catalogo.');
    }

    $filename = 'product-' . date('YmdHis') . '-' . bin2hex(random_bytes(6)) . '.' . $allowedMimes[$mime];
    $targetPath = $targetDir . '/' . $filename;

    if (!move_uploaded_file($tmpName, $targetPath)) {
        throw new RuntimeException('Impossibile salvare l immagine caricata.');
    }

    return 'uploads/products/' . $filename;
}

function admin_product_flow_validate_details(array $payload, array $categories): array
{
    $draft = admin_product_flow_default_draft();
    $validCategoryIds = [];
    foreach ($categories as $category) {
        $validCategoryIds[(int) $category['id']] = true;
    }

    $draft['category_id'] = (string) ((int) ($payload['category_id'] ?? 0));
    $draft['name'] = trim((string) ($payload['name'] ?? ''));
    $draft['description'] = trim((string) ($payload['description'] ?? ''));
    $draft['allergens'] = trim((string) ($payload['allergens'] ?? ''));
    $draft['price_cents'] = (string) ((int) ($payload['price_cents'] ?? 0));
    $draft['is_available'] = (string) (((string) ($payload['is_available'] ?? '1')) === '1' ? '1' : '0');

    if (!isset($validCategoryIds[(int) $draft['category_id']])) {
        throw new RuntimeException('Seleziona una categoria valida.');
    }

    if ($draft['name'] === '' || mb_strlen($draft['name']) < 3) {
        throw new RuntimeException('Inserisci un titolo prodotto di almeno 3 caratteri.');
    }

    if ($draft['description'] === '' || mb_strlen($draft['description']) < 10) {
        throw new RuntimeException('Inserisci una descrizione prodotto più completa.');
    }

    if ((int) $draft['price_cents'] <= 0) {
        throw new RuntimeException('Inserisci un prezzo valido in centesimi.');
    }

    return $draft;
}

require_login();

$sessionUser = current_user();
$utente = auth_get_user_by_id($pdo, (int) ($sessionUser['id'] ?? 0));

if ($utente === null) {
    logout_user();
    flash_set('error', 'Sessione amministrativa non valida. Effettua di nuovo l accesso.');
    header('Location: accedi');
    exit;
}

login_user($utente, false);

if (!is_general_admin()) {
    flash_set('error', 'Solo l admin centrale può gestire il catalogo globale.');
    header('Location: controllo-catalogo');
    exit;
}

$productId = (int) ($_GET['id'] ?? 0);
$existingProduct = $productId > 0 ? catalog_get_product_by_id($pdo, $productId) : null;
if ($productId > 0 && $existingProduct === null) {
    flash_set('error', 'Prodotto non trovato.');
    header('Location: controllo-catalogo');
    exit;
}

$flowKey = 'admin_product_flow_' . ($productId > 0 ? 'edit_' . $productId : 'new');
if (isset($_GET['reset']) && $_GET['reset'] === '1') {
    unset($_SESSION[$flowKey]);
}

$categories = categories_get_all($pdo);
$draft = $_SESSION[$flowKey]['draft'] ?? null;
if (!is_array($draft)) {
    $draft = admin_product_flow_default_draft();
    if ($existingProduct !== null) {
        $draft = [
            'category_id' => (string) $existingProduct['category_id'],
            'name' => (string) $existingProduct['name'],
            'description' => (string) $existingProduct['description'],
            'allergens' => (string) ($existingProduct['allergens'] ?? ''),
            'price_cents' => (string) $existingProduct['price_cents'],
            'is_available' => (string) ((int) $existingProduct['is_available'] === 1 ? '1' : '0'),
            'image_path' => (string) ($existingProduct['image_path'] ?? ''),
            'image_focus_x' => (string) ((int) ($existingProduct['image_focus_x'] ?? 50)),
            'image_focus_y' => (string) ((int) ($existingProduct['image_focus_y'] ?? 50)),
        ];
    }
}

$steps = ['dettagli', 'immagine', 'riepilogo'];
$currentStep = trim((string) ($_GET['step'] ?? 'dettagli'));
if (!in_array($currentStep, $steps, true)) {
    $currentStep = 'dettagli';
}

$csrfToken = csrf_token();
$flash = flash_get();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $csrfTokenForm = $_POST['csrf_token'] ?? null;
    if (!csrf_is_valid($csrfTokenForm)) {
        flash_set('error', 'Sessione scaduta o richiesta non valida. Ricarica la pagina e riprova.');
        header('Location: controllo-catalogo-prodotto' . ($productId > 0 ? '?id=' . $productId : ''));
        exit;
    }

    $action = (string) ($_POST['action'] ?? '');

    try {
        if ($action === 'save_details') {
            $details = admin_product_flow_validate_details($_POST, $categories);
            $draft = array_merge($draft, $details);
            $_SESSION[$flowKey] = ['draft' => $draft];
            header('Location: controllo-catalogo-prodotto?' . http_build_query(array_filter([
                'id' => $productId > 0 ? $productId : null,
                'step' => 'immagine',
            ])));
            exit;
        }

        if ($action === 'save_image') {
            $draft['image_focus_x'] = (string) max(0, min(100, (int) ($_POST['image_focus_x'] ?? 50)));
            $draft['image_focus_y'] = (string) max(0, min(100, (int) ($_POST['image_focus_y'] ?? 50)));

            if (isset($_FILES['product_image']) && (int) ($_FILES['product_image']['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_NO_FILE) {
                $draft['image_path'] = admin_product_flow_upload_image($_FILES['product_image']);
            }

            $_SESSION[$flowKey] = ['draft' => $draft];
            header('Location: controllo-catalogo-prodotto?' . http_build_query(array_filter([
                'id' => $productId > 0 ? $productId : null,
                'step' => 'riepilogo',
            ])));
            exit;
        }

        if ($action === 'go_to_details') {
            $_SESSION[$flowKey] = ['draft' => $draft];
            header('Location: controllo-catalogo-prodotto?' . http_build_query(array_filter([
                'id' => $productId > 0 ? $productId : null,
                'step' => 'dettagli',
            ])));
            exit;
        }

        if ($action === 'go_to_image') {
            $_SESSION[$flowKey] = ['draft' => $draft];
            header('Location: controllo-catalogo-prodotto?' . http_build_query(array_filter([
                'id' => $productId > 0 ? $productId : null,
                'step' => 'immagine',
            ])));
            exit;
        }

        if ($action === 'confirm_product') {
            $draft = admin_product_flow_validate_details($draft, $categories);
            $draft['image_path'] = (string) ($draft['image_path'] ?? '');
            $draft['image_focus_x'] = (string) max(0, min(100, (int) ($draft['image_focus_x'] ?? 50)));
            $draft['image_focus_y'] = (string) max(0, min(100, (int) ($draft['image_focus_y'] ?? 50)));

            $pdo->beginTransaction();
            if ($existingProduct !== null) {
                catalog_update_product($pdo, $productId, $draft, (int) $utente['id']);
            } else {
                $newProductId = catalog_create_product($pdo, $draft, (int) $utente['id']);
                branch_catalog_ensure_state_for_new_product($pdo, $newProductId);
            }
            $pdo->commit();

            unset($_SESSION[$flowKey]);
            flash_set('success', $existingProduct !== null ? 'Prodotto aggiornato nel catalogo globale.' : 'Nuovo prodotto creato nel catalogo globale.');
            header('Location: controllo-catalogo');
            exit;
        }

        throw new RuntimeException('Azione non riconosciuta nel flusso catalogo.');
    } catch (\Throwable $e) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        $flash = ['type' => 'error', 'message' => $e->getMessage()];
    }
}

$pageTitle = ($existingProduct !== null ? 'Modifica prodotto' : 'Nuovo prodotto') . ' - Smash Burger Original';
$pageDescription = 'Flusso guidato per aggiungere o aggiornare prodotti del catalogo globale.';
$currentPage = 'controllo';
$breadcrumb = [
    ['Home', './'],
    ['Controllo', 'controllo'],
    ['Catalogo', 'controllo-catalogo'],
    [$existingProduct !== null ? 'Modifica prodotto' : 'Nuovo prodotto', null],
];

include_once __DIR__ . '/views/template/header.php';
include_once __DIR__ . '/views/controllo/catalogo-prodotto.php';
include_once __DIR__ . '/views/template/footer.php';
