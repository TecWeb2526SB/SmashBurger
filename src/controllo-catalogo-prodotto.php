<?php
require_once __DIR__ . '/includes/resources.php';

function admin_product_flow_default_draft(): array
{
    return [
        'category_id' => '',
        'name' => '',
        'description' => '',
        'allergens' => '',
        'is_available' => '1',
        'price_cents' => '',
        'image_path' => '',
        'image_focus_x' => 50,
        'image_focus_y' => 50,
    ];
}

$context = admin_panel_bootstrap_context($pdo);
$isGeneralAdmin = (string) ($context['utente']['role'] ?? '') === 'admin';

if (!$isGeneralAdmin && !can_manage_global_catalog()) {
    flash_set('error', 'Solo l admin centrale può gestire il catalogo globale.');
    header('Location: controllo');
    exit;
}

$productId = max(0, (int) ($_GET['id'] ?? 0));
$existingProduct = $productId > 0 ? catalog_get_product_by_id($pdo, $productId) : null;

if (isset($_GET['reset'])) {
    unset($_SESSION['admin_product_draft']);
    header('Location: controllo-catalogo-prodotto' . ($productId > 0 ? '?id=' . $productId : ''));
    exit;
}

if (!isset($_SESSION['admin_product_draft'])) {
    $_SESSION['admin_product_draft'] = $existingProduct ? [
        'category_id' => (int) $existingProduct['category_id'],
        'name' => (string) $existingProduct['name'],
        'description' => (string) $existingProduct['description'],
        'allergens' => (string) $existingProduct['allergens'],
        'is_available' => (int) $existingProduct['is_available'],
        'price_cents' => (int) $existingProduct['price_cents'],
        'image_path' => (string) $existingProduct['image_path'],
        'image_focus_x' => (int) ($existingProduct['image_focus_x'] ?? 50),
        'image_focus_y' => (int) ($existingProduct['image_focus_y'] ?? 50),
    ] : admin_product_flow_default_draft();
}

$draft = &$_SESSION['admin_product_draft'];
$currentStep = trim((string) ($_GET['step'] ?? 'dettagli'));
$steps = ['dettagli', 'immagine', 'riepilogo'];
if (!in_array($currentStep, $steps, true)) {
    $currentStep = 'dettagli';
}

$flash = flash_get();
$csrfToken = csrf_token();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_is_valid($_POST['csrf_token'] ?? null)) {
        flash_set('error', 'Sessione scaduta. Riprova.');
        header('Location: ' . $_SERVER['REQUEST_URI']);
        exit;
    }

    $action = (string) ($_POST['action'] ?? '');

    try {
        if ($action === 'save_details') {
            $draft['category_id'] = (int) ($_POST['category_id'] ?? 0);
            $draft['name'] = trim((string) ($_POST['name'] ?? ''));
            $draft['description'] = trim((string) ($_POST['description'] ?? ''));
            $draft['allergens'] = trim((string) ($_POST['allergens'] ?? ''));
            $draft['price_cents'] = max(0, (int) ($_POST['price_cents'] ?? 0));
            $draft['is_available'] = (string) ($_POST['is_available'] ?? '1') === '1' ? 1 : 0;
            header('Location: controllo-catalogo-prodotto?step=immagine' . ($productId > 0 ? '&id=' . $productId : ''));
            exit;
        }

        if ($action === 'save_image') {
            $draft['image_focus_x'] = max(0, min(100, (int) ($_POST['image_focus_x'] ?? 50)));
            $draft['image_focus_y'] = max(0, min(100, (int) ($_POST['image_focus_y'] ?? 50)));

            if (isset($_FILES['product_image']) && $_FILES['product_image']['error'] === UPLOAD_ERR_OK) {
                $uploadResult = admin_upload_product_image($_FILES['product_image']);
                $draft['image_path'] = $uploadResult['path'];
            }
            header('Location: controllo-catalogo-prodotto?step=riepilogo' . ($productId > 0 ? '&id=' . $productId : ''));
            exit;
        }

        if ($action === 'confirm_product') {
            if ($productId > 0) {
                catalog_update_product($pdo, $productId, $draft);
                flash_set('success', 'Prodotto aggiornato con successo.');
            } else {
                catalog_create_product($pdo, $draft);
                flash_set('success', 'Prodotto creato con successo.');
            }
            unset($_SESSION['admin_product_draft']);
            header('Location: controllo-catalogo');
            exit;
        }
    } catch (\Throwable $e) {
        $flash = ['type' => 'error', 'message' => $e->getMessage()];
    }
}

$categories = categories_get_all($pdo);

render_admin_page('catalogo', [
    'pageTitle' => ($existingProduct !== null ? 'Modifica prodotto' : 'Nuovo prodotto') . ' - Smash Burger',
    'productId' => $productId,
    'existingProduct' => $existingProduct,
    'categories' => $categories,
    'draft' => $draft,
    'currentStep' => $currentStep,
    'isReviewStep' => $currentStep === 'riepilogo',
    'currentTitle' => ($existingProduct !== null ? 'Modifica ' . $existingProduct['name'] : 'Nuovo prodotto globale'),
    'categoryNameById' => array_column($categories, 'name', 'id')
], 'controllo/catalogo-prodotto.php');
