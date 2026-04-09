<?php
/**
 * Funzioni dominio amministrazione: ruoli operativi, inventario, forniture,
 * riordino automatico, statistiche e ricevute.
 */

function role_label(string $role): string
{
    return match ($role) {
        'admin' => 'Admin generale',
        'branch_manager' => 'Manager di filiale',
        default => 'Utente registrato',
    };
}

function admin_panel_sections(bool $canManageBranchManagers): array
{
    $sections = [
        'dashboard' => [
            'label' => 'Panoramica',
            'path' => 'admin.php',
            'uses_branch' => true,
            'title' => 'Panoramica',
            'kicker' => 'Panoramica',
            'description' => 'Ricavi, margine, stock e confronto operativo con una vista piu ordinata e focalizzata.',
        ],
        'catalogo' => [
            'label' => 'Catalogo',
            'path' => 'admin_catalogo.php',
            'uses_branch' => true,
            'title' => 'Catalogo',
            'kicker' => 'Catalogo',
            'description' => 'Prodotti condivisi, presenza nel catalogo di filiale e flusso guidato per nuove referenze.',
        ],
        'inventario' => [
            'label' => 'Inventario',
            'path' => 'admin_inventario.php',
            'uses_branch' => true,
            'title' => 'Inventario',
            'kicker' => 'Magazzino',
            'description' => 'Quantita presenti, merce in arrivo e rettifiche operative concentrate in una sola pagina.',
        ],
        'forniture' => [
            'label' => 'Forniture',
            'path' => 'admin_forniture.php',
            'uses_branch' => true,
            'title' => 'Forniture',
            'kicker' => 'Approvvigionamento',
            'description' => 'Template ricorrenti, ordini straordinari, riordino automatico e ricevute fornitura in un unico flusso.',
        ],
    ];

    if ($canManageBranchManagers) {
        $sections['team'] = [
            'label' => 'Manager',
            'path' => 'admin_team.php',
            'uses_branch' => false,
            'title' => 'Manager filiale',
            'kicker' => 'Credenziali',
            'description' => 'Crea, modifica o revoca gli accessi dei manager di filiale senza confondere il resto del pannello.',
        ];
    }

    return $sections;
}

function admin_panel_section_meta(string $section, bool $canManageBranchManagers): array
{
    $sections = admin_panel_sections($canManageBranchManagers);

    return $sections[$section] ?? $sections['dashboard'];
}

function admin_panel_section_url(
    string $section,
    ?string $branchSlug,
    bool $isGeneralAdmin,
    bool $canManageBranchManagers
): string {
    $sections = admin_panel_sections($canManageBranchManagers);
    $meta = $sections[$section] ?? $sections['dashboard'];
    $params = [];

    if ($isGeneralAdmin && !empty($meta['uses_branch']) && is_string($branchSlug) && $branchSlug !== '') {
        $params['sede'] = $branchSlug;
    }

    $query = http_build_query($params);
    return (string) $meta['path'] . ($query !== '' ? '?' . $query : '');
}

function admin_panel_build_navigation(
    ?string $branchSlug,
    bool $isGeneralAdmin,
    bool $canManageBranchManagers,
    string $currentSection
): array {
    $items = [];

    foreach (admin_panel_sections($canManageBranchManagers) as $section => $meta) {
        $items[] = [
            'section' => $section,
            'label' => (string) $meta['label'],
            'href' => admin_panel_section_url($section, $branchSlug, $isGeneralAdmin, $canManageBranchManagers),
            'is_active' => $section === $currentSection,
        ];
    }

    return $items;
}

function admin_panel_branch_query_params(?string $branchSlug, bool $isGeneralAdmin): array
{
    if (!$isGeneralAdmin || !is_string($branchSlug) || $branchSlug === '') {
        return [];
    }

    return ['sede' => $branchSlug];
}

function admin_panel_build_path(string $path, array $params = []): string
{
    $filteredParams = [];

    foreach ($params as $key => $value) {
        if (!is_string($key) || $key === '' || $value === null || $value === '') {
            continue;
        }

        $filteredParams[$key] = (string) $value;
    }

    $query = http_build_query($filteredParams);

    return $path . ($query !== '' ? '?' . $query : '');
}

function admin_supply_builder_pages(): array
{
    return [
        'standard' => [
            'path' => 'admin_forniture_standard.php',
            'kicker' => 'Scenario 1',
            'title' => 'Routine ricorrente',
            'description' => 'Builder guidato per template settimanali, quindicinali o mensili con righe prodotto espandibili.',
            'breadcrumb' => 'Routine ricorrente',
            'submit_label' => 'Salva fornitura standard',
        ],
        'extra' => [
            'path' => 'admin_forniture_straordinaria.php',
            'kicker' => 'Scenario 2',
            'title' => 'Intervento una tantum',
            'description' => 'Builder manuale per urgenze e integrazioni fuori programma, con righe prodotto aggiungibili al volo.',
            'breadcrumb' => 'Intervento una tantum',
            'submit_label' => 'Registra fornitura straordinaria',
        ],
        'automatic' => [
            'path' => 'admin_forniture_automatico.php',
            'kicker' => 'Scenario 3',
            'title' => 'Automazione stock',
            'description' => 'Builder per configurare trigger, controlli e output del riordino automatico della filiale.',
            'breadcrumb' => 'Automazione stock',
            'submit_label' => 'Salva policy',
        ],
    ];
}

function admin_supply_builder_meta(string $builderKey): array
{
    $pages = admin_supply_builder_pages();

    return $pages[$builderKey] ?? $pages['standard'];
}

function admin_supply_builder_url(string $builderKey, ?string $branchSlug, bool $isGeneralAdmin): string
{
    $meta = admin_supply_builder_meta($builderKey);

    return admin_panel_build_path(
        (string) $meta['path'],
        admin_panel_branch_query_params($branchSlug, $isGeneralAdmin)
    );
}

function admin_inventory_adjustment_url(
    ?string $branchSlug,
    bool $isGeneralAdmin,
    ?string $mode = null,
    ?int $productId = null
): string {
    $params = admin_panel_branch_query_params($branchSlug, $isGeneralAdmin);

    if (is_string($mode) && $mode !== '') {
        $params['modo'] = $mode;
    }

    if (is_int($productId) && $productId > 0) {
        $params['prodotto'] = (string) $productId;
    }

    return admin_panel_build_path('admin_inventario_rettifica.php', $params);
}

function admin_panel_bootstrap_context(PDO $pdo): array
{
    require_admin_panel_access();

    $sessionUser = current_user();
    $utente = auth_get_user_by_id($pdo, (int) ($sessionUser['id'] ?? 0));

    if ($utente === null) {
        logout_user();
        flash_set('error', 'Sessione amministrativa non valida. Effettua di nuovo l accesso.');
        header('Location: login.php');
        exit;
    }

    login_user($utente, false);

    $isGeneralAdmin = (string) $utente['role'] === 'admin';
    $isBranchManager = (string) $utente['role'] === 'branch_manager';
    $canModifyBranchOperations = $isBranchManager;
    $canManageGlobalCatalog = can_manage_global_catalog();
    $canManageBranchManagers = can_manage_branch_managers();
    $allBranches = branches_get_all($pdo);

    if (empty($allBranches)) {
        flash_set('error', 'Nessuna sede disponibile per il pannello di controllo.');
        header('Location: area_personale.php');
        exit;
    }

    $requestedBranchSlug = trim((string) ($_GET['sede'] ?? ''));
    $selectedBranch = null;

    if ($isBranchManager) {
        $managedBranchId = (int) ($utente['managed_branch_id'] ?? 0);
        $selectedBranch = $managedBranchId > 0 ? branch_get_by_id($pdo, $managedBranchId) : null;

        if ($selectedBranch === null) {
            flash_set('error', 'Il tuo account manager non e associato a una filiale valida.');
            header('Location: area_personale.php');
            exit;
        }
    } else {
        if ($requestedBranchSlug !== '') {
            $selectedBranch = branch_get_by_slug($pdo, $requestedBranchSlug);
        }

        if ($selectedBranch === null) {
            $selectedBranch = branch_get_selected($pdo);
        }

        if ($selectedBranch === null) {
            $selectedBranch = $allBranches[0] ?? null;
        }

        if ($selectedBranch !== null) {
            $_SESSION['selected_branch_id'] = (int) $selectedBranch['id'];
            $_SESSION['selected_branch_slug'] = (string) $selectedBranch['slug'];
        }
    }

    if ($selectedBranch === null) {
        flash_set('error', 'Impossibile determinare la filiale in analisi.');
        header('Location: area_personale.php');
        exit;
    }

    return [
        'utente' => $utente,
        'isGeneralAdmin' => $isGeneralAdmin,
        'isBranchManager' => $isBranchManager,
        'canModifyBranchOperations' => $canModifyBranchOperations,
        'canManageGlobalCatalog' => $canManageGlobalCatalog,
        'canManageBranchManagers' => $canManageBranchManagers,
        'allBranches' => $allBranches,
        'selectedBranch' => $selectedBranch,
        'selectedBranchId' => (int) $selectedBranch['id'],
        'selectedBranchSlug' => (string) $selectedBranch['slug'],
    ];
}

function admin_panel_background_messages(PDO $pdo, bool $isBranchManager, int $branchId, int $userId): array
{
    if (!$isBranchManager) {
        return [];
    }

    $messages = [];

    try {
        $generatedTemplates = supply_sync_due_templates($pdo, $branchId, $userId);
        $generatedAutoOrders = auto_reorder_evaluate_branch($pdo, $branchId, $userId);

        if ($generatedTemplates > 0) {
            $messages[] = 'Sono state generate ' . $generatedTemplates . ' forniture standard pianificate.';
        }

        if ($generatedAutoOrders > 0) {
            $messages[] = 'Il motore di ordine automatico ha aggiunto ' . $generatedAutoOrders . ' nuove forniture.';
        }
    } catch (\Throwable $backgroundException) {
        $messages[] = 'Nota operativa: alcune automazioni non sono state elaborate (' . $backgroundException->getMessage() . ').';
    }

    return $messages;
}

function admin_build_products_lookup(array $inventoryItems): array
{
    $productsById = [];

    foreach ($inventoryItems as $inventoryItem) {
        $productsById[(int) $inventoryItem['product_id']] = $inventoryItem;
    }

    return $productsById;
}

function admin_supply_item_rows_default(int $count = 1): array
{
    $count = max(1, $count);
    $rows = [];

    for ($index = 0; $index < $count; $index++) {
        $rows[] = [
            'product_id' => '',
            'quantity' => '',
        ];
    }

    return $rows;
}

function admin_supply_item_rows_from_payload(array $payload, string $prefix): array
{
    $productIds = array_values((array) ($payload[$prefix . '_product_id'] ?? []));
    $quantities = array_values((array) ($payload[$prefix . '_quantity'] ?? []));
    $rowCount = max(1, count($productIds), count($quantities));
    $rows = [];

    for ($index = 0; $index < $rowCount; $index++) {
        $productId = (int) ($productIds[$index] ?? 0);
        $quantity = (int) ($quantities[$index] ?? 0);

        $rows[] = [
            'product_id' => $productId > 0 ? (string) $productId : '',
            'quantity' => $quantity > 0 ? (string) $quantity : '',
        ];
    }

    while (count($rows) > 1) {
        $lastRow = $rows[array_key_last($rows)] ?? ['product_id' => '', 'quantity' => ''];
        if (($lastRow['product_id'] ?? '') !== '' || ($lastRow['quantity'] ?? '') !== '') {
            break;
        }

        array_pop($rows);
    }

    return $rows !== [] ? array_values($rows) : admin_supply_item_rows_default();
}

function admin_supply_standard_default_draft(): array
{
    return [
        'template_name' => '',
        'frequency' => 'weekly',
        'next_run_at' => '',
        'notes' => '',
        'items' => admin_supply_item_rows_default(),
    ];
}

function admin_supply_standard_draft_from_payload(array $payload): array
{
    return [
        'template_name' => trim((string) ($payload['template_name'] ?? '')),
        'frequency' => (string) ($payload['frequency'] ?? 'weekly'),
        'next_run_at' => trim((string) ($payload['next_run_at'] ?? '')),
        'notes' => trim((string) ($payload['notes'] ?? '')),
        'items' => admin_supply_item_rows_from_payload($payload, 'template'),
    ];
}

function admin_supply_extra_default_draft(): array
{
    return [
        'supplier_name' => 'Centro forniture SmashBurger',
        'scheduled_for' => '',
        'notes' => '',
        'items' => admin_supply_item_rows_default(),
    ];
}

function admin_supply_extra_draft_from_payload(array $payload): array
{
    $supplierName = trim((string) ($payload['supplier_name'] ?? ''));

    return [
        'supplier_name' => $supplierName !== '' ? $supplierName : 'Centro forniture SmashBurger',
        'scheduled_for' => trim((string) ($payload['scheduled_for'] ?? '')),
        'notes' => trim((string) ($payload['notes'] ?? '')),
        'items' => admin_supply_item_rows_from_payload($payload, 'extra'),
    ];
}

function admin_supply_policy_default_draft(): array
{
    return [
        'product_id' => '',
        'threshold_qty' => '',
        'reorder_qty' => '',
        'cooldown_hours' => '6',
        'max_pending_qty' => '0',
        'mode' => 'draft',
    ];
}

function admin_supply_policy_draft_from_payload(array $payload): array
{
    return [
        'product_id' => (int) ($payload['product_id'] ?? 0) > 0 ? (string) ((int) ($payload['product_id'] ?? 0)) : '',
        'threshold_qty' => (int) ($payload['threshold_qty'] ?? 0) > 0 ? (string) ((int) ($payload['threshold_qty'] ?? 0)) : '',
        'reorder_qty' => (int) ($payload['reorder_qty'] ?? 0) > 0 ? (string) ((int) ($payload['reorder_qty'] ?? 0)) : '',
        'cooldown_hours' => (string) max(0, (int) ($payload['cooldown_hours'] ?? 6)),
        'max_pending_qty' => (string) max(0, (int) ($payload['max_pending_qty'] ?? 0)),
        'mode' => (string) ($payload['mode'] ?? 'draft'),
    ];
}

function admin_inventory_adjustment_modes(): array
{
    return [
        'carico' => [
            'label' => 'Carico merce',
            'title' => 'Registra un carico',
            'description' => 'Aggiungi unità quando entra nuova merce o correggi una mancanza di carico precedente.',
            'submit_label' => 'Conferma carico',
        ],
        'scarico' => [
            'label' => 'Scarico o scarto',
            'title' => 'Registra uno scarico',
            'description' => 'Rimuovi unità per scarto, deterioramento, reso o rettifica negativa di fine turno.',
            'submit_label' => 'Conferma scarico',
        ],
        'conteggio' => [
            'label' => 'Conteggio fisico',
            'title' => 'Allinea al conteggio reale',
            'description' => 'Inserisci la quantità reale trovata in magazzino e lascia al sistema il calcolo della differenza.',
            'submit_label' => 'Allinea inventario',
        ],
    ];
}

function admin_inventory_adjustment_default_draft(string $mode = 'carico', int $productId = 0): array
{
    $modes = admin_inventory_adjustment_modes();
    if (!isset($modes[$mode])) {
        $mode = 'carico';
    }

    return [
        'mode' => $mode,
        'product_id' => $productId > 0 ? (string) $productId : '',
        'quantity' => '',
        'counted_qty' => '',
        'notes' => '',
    ];
}

function admin_inventory_adjustment_draft_from_payload(array $payload): array
{
    $mode = trim((string) ($payload['mode'] ?? 'carico'));
    $modes = admin_inventory_adjustment_modes();
    if (!isset($modes[$mode])) {
        $mode = 'carico';
    }

    return [
        'mode' => $mode,
        'product_id' => (int) ($payload['product_id'] ?? 0) > 0 ? (string) ((int) ($payload['product_id'] ?? 0)) : '',
        'quantity' => (int) ($payload['quantity'] ?? 0) > 0 ? (string) ((int) ($payload['quantity'] ?? 0)) : '',
        'counted_qty' => (string) max(0, (int) ($payload['counted_qty'] ?? 0)),
        'notes' => trim((string) ($payload['notes'] ?? '')),
    ];
}

function inventory_resolve_adjustment_unit_cost_cents(PDO $pdo, int $branchId, array $product): int
{
    $currentAverage = (int) ($product['average_unit_cost_cents'] ?? 0);
    if ($currentAverage > 0) {
        return $currentAverage;
    }

    return max(
        0,
        inventory_resolve_supply_unit_cost_cents(
            $pdo,
            $branchId,
            (int) ($product['product_id'] ?? 0),
            $product
        )
    );
}

function inventory_apply_manual_adjustment_flow(
    PDO $pdo,
    int $branchId,
    int $userId,
    array $draft,
    array $productsById
): void {
    $mode = (string) ($draft['mode'] ?? 'carico');
    $modes = admin_inventory_adjustment_modes();

    if (!isset($modes[$mode])) {
        throw new RuntimeException('Modalita di rettifica inventario non valida.');
    }

    $productId = (int) ($draft['product_id'] ?? 0);
    if ($productId <= 0 || !isset($productsById[$productId])) {
        throw new RuntimeException('Seleziona un prodotto valido per la rettifica inventario.');
    }

    $product = $productsById[$productId];
    $quantity = max(0, (int) ($draft['quantity'] ?? 0));
    $countedQty = max(0, (int) ($draft['counted_qty'] ?? 0));
    $currentQty = (int) ($product['on_hand_qty'] ?? 0);
    $notes = trim((string) ($draft['notes'] ?? ''));
    $quantityDelta = 0;
    $defaultNote = 'Rettifica manuale inventario da pannello manager.';

    if ($mode === 'carico') {
        if ($quantity <= 0) {
            throw new RuntimeException('Inserisci una quantita positiva per il carico.');
        }

        $quantityDelta = $quantity;
        $defaultNote = 'Carico manuale inventario.';
    } elseif ($mode === 'scarico') {
        if ($quantity <= 0) {
            throw new RuntimeException('Inserisci una quantita positiva per lo scarico.');
        }

        $quantityDelta = -$quantity;
        $defaultNote = 'Scarico manuale inventario.';
    } else {
        $quantityDelta = $countedQty - $currentQty;
        $defaultNote = 'Allineamento inventario a conteggio fisico.';

        if ($quantityDelta === 0) {
            throw new RuntimeException('Il conteggio inserito coincide gia con la quantita registrata.');
        }
    }

    $pdo->beginTransaction();

    try {
        inventory_adjust_stock(
            $pdo,
            $branchId,
            $productId,
            $quantityDelta,
            'manual_adjustment',
            'manual',
            null,
            $notes !== '' ? $notes : $defaultNote,
            $userId,
            $quantityDelta > 0 ? inventory_resolve_adjustment_unit_cost_cents($pdo, $branchId, $product) : null
        );
        $pdo->commit();
    } catch (\Throwable $e) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        throw $e;
    }

    try {
        auto_reorder_evaluate_branch($pdo, $branchId, $userId);
    } catch (\Throwable $autoReorderException) {
        error_log('Errore auto-riordino dopo rettifica inventario: ' . $autoReorderException->getMessage());
    }
}

function inventory_resolve_supply_unit_cost_cents(PDO $pdo, int $branchId, int $productId, ?array $productRow = null): int
{
    $candidate = (int) (($productRow['supply_unit_cost_cents'] ?? $productRow['average_unit_cost_cents'] ?? 0));
    if ($candidate > 0) {
        return $candidate;
    }

    inventory_ensure_row($pdo, $branchId, $productId);

    $inventoryStmt = $pdo->prepare(
        'SELECT average_unit_cost_cents
         FROM branch_inventory
         WHERE branch_id = :branch_id AND product_id = :product_id
         LIMIT 1'
    );
    $inventoryStmt->execute([
        'branch_id' => $branchId,
        'product_id' => $productId,
    ]);
    $inventoryCost = (int) $inventoryStmt->fetchColumn();
    if ($inventoryCost > 0) {
        return $inventoryCost;
    }

    $orderStmt = $pdo->prepare(
        'SELECT soi.unit_cost_cents
         FROM supply_order_items soi
         INNER JOIN supply_orders so ON so.id = soi.supply_order_id
         WHERE so.branch_id = :branch_id
           AND soi.product_id = :product_id
           AND soi.unit_cost_cents > 0
         ORDER BY
            CASE WHEN so.received_at IS NULL THEN 1 ELSE 0 END ASC,
            so.received_at DESC,
            so.created_at DESC,
            soi.id DESC
         LIMIT 1'
    );
    $orderStmt->execute([
        'branch_id' => $branchId,
        'product_id' => $productId,
    ]);
    $orderCost = (int) $orderStmt->fetchColumn();
    if ($orderCost > 0) {
        $updateInventory = $pdo->prepare(
            'UPDATE branch_inventory
             SET average_unit_cost_cents = :average_unit_cost_cents,
                 updated_at = NOW()
             WHERE branch_id = :branch_id AND product_id = :product_id'
        );
        $updateInventory->execute([
            'average_unit_cost_cents' => $orderCost,
            'branch_id' => $branchId,
            'product_id' => $productId,
        ]);

        return $orderCost;
    }

    $templateStmt = $pdo->prepare(
        'SELECT sti.unit_cost_cents
         FROM supply_template_items sti
         INNER JOIN supply_templates st ON st.id = sti.template_id
         WHERE st.branch_id = :branch_id
           AND sti.product_id = :product_id
           AND sti.unit_cost_cents > 0
         ORDER BY st.updated_at DESC, sti.id DESC
         LIMIT 1'
    );
    $templateStmt->execute([
        'branch_id' => $branchId,
        'product_id' => $productId,
    ]);
    $templateCost = (int) $templateStmt->fetchColumn();
    if ($templateCost > 0) {
        $updateInventory = $pdo->prepare(
            'UPDATE branch_inventory
             SET average_unit_cost_cents = :average_unit_cost_cents,
                 updated_at = NOW()
             WHERE branch_id = :branch_id AND product_id = :product_id'
        );
        $updateInventory->execute([
            'average_unit_cost_cents' => $templateCost,
            'branch_id' => $branchId,
            'product_id' => $productId,
        ]);

        return $templateCost;
    }

    return 0;
}

function admin_prepare_supply_products(PDO $pdo, int $branchId, array $inventoryItems): array
{
    foreach ($inventoryItems as &$inventoryItem) {
        $resolvedUnitCost = inventory_resolve_supply_unit_cost_cents(
            $pdo,
            $branchId,
            (int) ($inventoryItem['product_id'] ?? 0),
            $inventoryItem
        );

        $inventoryItem['supply_unit_cost_cents'] = $resolvedUnitCost;
        $inventoryItem['has_supply_unit_cost'] = $resolvedUnitCost > 0;
    }
    unset($inventoryItem);

    return $inventoryItems;
}

function admin_extract_supply_items(PDO $pdo, int $branchId, array $payload, string $prefix, array $productsById): array
{
    $productIds = (array) ($payload[$prefix . '_product_id'] ?? []);
    $quantities = (array) ($payload[$prefix . '_quantity'] ?? []);
    $rowCount = max(count($productIds), count($quantities));
    $items = [];

    for ($index = 0; $index < $rowCount; $index++) {
        $productId = (int) ($productIds[$index] ?? 0);
        $quantity = (int) ($quantities[$index] ?? 0);

        if ($productId <= 0 && $quantity <= 0) {
            continue;
        }

        if ($productId <= 0 || !isset($productsById[$productId])) {
            throw new RuntimeException('Seleziona un prodotto valido per ogni riga fornitura compilata.');
        }

        if ($quantity <= 0) {
            throw new RuntimeException('La quantita di fornitura deve essere maggiore di zero.');
        }

        $product = $productsById[$productId];
        $unitCostCents = inventory_resolve_supply_unit_cost_cents($pdo, $branchId, $productId, $product);

        if ($unitCostCents <= 0) {
            throw new RuntimeException(
                'Il costo filiale di ' . (string) $product['product_name'] . ' non e definito. Completa prima la base costi della sede.'
            );
        }

        $items[] = [
            'product_id' => $productId,
            'product_name' => (string) $product['product_name'],
            'quantity' => $quantity,
            'unit_cost_cents' => $unitCostCents,
        ];
    }

    return $items;
}

function admin_slugify(string $value): string
{
    $value = mb_strtolower(trim($value));
    $value = preg_replace('/[^a-z0-9]+/u', '-', $value) ?? '';
    $value = trim($value, '-');

    return $value !== '' ? $value : 'prodotto';
}

function admin_parse_money_to_cents(string $value): int
{
    $normalized = str_replace([' ', "\xc2\xa0"], '', trim($value));
    $normalized = str_replace(',', '.', $normalized);

    if ($normalized === '' || !preg_match('/^\d+(?:\.\d{1,2})?$/', $normalized)) {
        throw new RuntimeException('Inserisci un importo valido usando al massimo due decimali.');
    }

    return (int) round(((float) $normalized) * 100);
}

function categories_get_all(PDO $pdo): array
{
    $stmt = $pdo->query(
        'SELECT id, name, slug, sort_order
         FROM categories
         ORDER BY sort_order ASC, name ASC'
    );

    return $stmt->fetchAll();
}

function catalog_get_product_by_id(PDO $pdo, int $productId): ?array
{
    $stmt = $pdo->prepare(
        'SELECT
            p.id,
            p.category_id,
            c.name AS category_name,
            p.name,
            p.slug,
            p.description,
            p.image_path,
            p.image_focus_x,
            p.image_focus_y,
            p.allergens,
            p.is_available,
            p.price_cents,
            p.created_at,
            p.updated_at
         FROM products p
         INNER JOIN categories c ON c.id = p.category_id
         WHERE p.id = :id
         LIMIT 1'
    );
    $stmt->execute(['id' => $productId]);
    $product = $stmt->fetch();

    return $product ?: null;
}

function catalog_get_all_products_with_branch_usage(PDO $pdo): array
{
    $stmt = $pdo->query(
        'SELECT
            p.id,
            p.category_id,
            c.name AS category_name,
            p.name,
            p.slug,
            p.description,
            p.image_path,
            p.image_focus_x,
            p.image_focus_y,
            p.allergens,
            p.is_available,
            p.price_cents,
            COUNT(CASE WHEN bp.is_listed = 1 THEN 1 END) AS listed_branches_count,
            COUNT(bp.branch_id) AS configured_branches_count
         FROM products p
         INNER JOIN categories c ON c.id = p.category_id
         LEFT JOIN branch_products bp ON bp.product_id = p.id
         GROUP BY p.id, p.category_id, c.name, p.name, p.slug, p.description, p.image_path, p.image_focus_x, p.image_focus_y, p.allergens, p.is_available, p.price_cents
         ORDER BY c.sort_order ASC, p.name ASC'
    );

    return $stmt->fetchAll();
}

function catalog_product_slug_exists(PDO $pdo, string $slug, ?int $excludeProductId = null): bool
{
    $sql = 'SELECT id FROM products WHERE slug = :slug';
    $params = ['slug' => $slug];

    if ($excludeProductId !== null) {
        $sql .= ' AND id <> :exclude_product_id';
        $params['exclude_product_id'] = $excludeProductId;
    }

    $sql .= ' LIMIT 1';
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);

    return $stmt->fetchColumn() !== false;
}

function catalog_prepare_unique_slug(PDO $pdo, string $baseName, ?int $excludeProductId = null): string
{
    $baseSlug = admin_slugify($baseName);
    $slug = $baseSlug;
    $suffix = 2;

    while (catalog_product_slug_exists($pdo, $slug, $excludeProductId)) {
        $slug = $baseSlug . '-' . $suffix;
        $suffix++;
    }

    return $slug;
}

function catalog_create_product(PDO $pdo, array $data, int $createdByUserId): int
{
    $slug = catalog_prepare_unique_slug($pdo, (string) ($data['slug'] ?? $data['name'] ?? ''));
    $stmt = $pdo->prepare(
        'INSERT INTO products
            (
                category_id,
                name,
                slug,
                description,
                image_path,
                image_focus_x,
                image_focus_y,
                allergens,
                is_available,
                price_cents,
                created_by_user_id,
                updated_by_user_id,
                created_at,
                updated_at
            )
         VALUES
            (
                :category_id,
                :name,
                :slug,
                :description,
                :image_path,
                :image_focus_x,
                :image_focus_y,
                :allergens,
                :is_available,
                :price_cents,
                :created_by_user_id,
                :updated_by_user_id,
                NOW(),
                NOW()
            )'
    );
    $stmt->execute([
        'category_id' => (int) $data['category_id'],
        'name' => mb_substr((string) $data['name'], 0, 120),
        'slug' => $slug,
        'description' => trim((string) $data['description']),
        'image_path' => $data['image_path'] !== '' ? (string) $data['image_path'] : null,
        'image_focus_x' => (int) $data['image_focus_x'],
        'image_focus_y' => (int) $data['image_focus_y'],
        'allergens' => trim((string) $data['allergens']) !== '' ? mb_substr(trim((string) $data['allergens']), 0, 255) : null,
        'is_available' => (int) $data['is_available'],
        'price_cents' => (int) $data['price_cents'],
        'created_by_user_id' => $createdByUserId,
        'updated_by_user_id' => $createdByUserId,
    ]);

    return (int) $pdo->lastInsertId();
}

function catalog_update_product(PDO $pdo, int $productId, array $data, int $updatedByUserId): void
{
    $slug = catalog_prepare_unique_slug($pdo, (string) ($data['slug'] ?? $data['name'] ?? ''), $productId);
    $stmt = $pdo->prepare(
        'UPDATE products
         SET category_id = :category_id,
             name = :name,
             slug = :slug,
             description = :description,
             image_path = :image_path,
             image_focus_x = :image_focus_x,
             image_focus_y = :image_focus_y,
             allergens = :allergens,
             is_available = :is_available,
             price_cents = :price_cents,
             updated_by_user_id = :updated_by_user_id,
             updated_at = NOW()
         WHERE id = :id
         LIMIT 1'
    );
    $stmt->execute([
        'id' => $productId,
        'category_id' => (int) $data['category_id'],
        'name' => mb_substr((string) $data['name'], 0, 120),
        'slug' => $slug,
        'description' => trim((string) $data['description']),
        'image_path' => $data['image_path'] !== '' ? (string) $data['image_path'] : null,
        'image_focus_x' => (int) $data['image_focus_x'],
        'image_focus_y' => (int) $data['image_focus_y'],
        'allergens' => trim((string) $data['allergens']) !== '' ? mb_substr(trim((string) $data['allergens']), 0, 255) : null,
        'is_available' => (int) $data['is_available'],
        'price_cents' => (int) $data['price_cents'],
        'updated_by_user_id' => $updatedByUserId,
    ]);
}

function catalog_delete_product(PDO $pdo, int $productId): void
{
    $referenceChecks = [
        'SELECT COUNT(*) FROM cart_items WHERE product_id = :product_id',
        'SELECT COUNT(*) FROM order_items WHERE product_id = :product_id',
        'SELECT COUNT(*) FROM supply_order_items WHERE product_id = :product_id',
        'SELECT COUNT(*) FROM supply_template_items WHERE product_id = :product_id',
    ];

    foreach ($referenceChecks as $sql) {
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['product_id' => $productId]);
        if ((int) $stmt->fetchColumn() > 0) {
            throw new RuntimeException('Questo prodotto e gia stato usato in ordini o forniture. Per non perdere lo storico, nascondilo dalle filiali invece di eliminarlo.');
        }
    }

    $stmt = $pdo->prepare(
        'DELETE FROM products
         WHERE id = :id
         LIMIT 1'
    );
    $stmt->execute(['id' => $productId]);
}

function branch_catalog_set_product_state(
    PDO $pdo,
    int $branchId,
    int $productId,
    bool $isListed,
    bool $isAvailable,
    ?int $priceOverrideCents = null
): void {
    $stmt = $pdo->prepare(
        'INSERT INTO branch_products
            (branch_id, product_id, is_listed, is_available, price_cents_override, pickup_eta_minutes, updated_at)
         VALUES
            (:branch_id, :product_id, :is_listed, :is_available, :price_cents_override, 15, NOW())
         ON DUPLICATE KEY UPDATE
            is_listed = VALUES(is_listed),
            is_available = VALUES(is_available),
            price_cents_override = VALUES(price_cents_override),
            updated_at = NOW()'
    );
    $stmt->execute([
        'branch_id' => $branchId,
        'product_id' => $productId,
        'is_listed' => $isListed ? 1 : 0,
        'is_available' => $isListed && $isAvailable ? 1 : 0,
        'price_cents_override' => $priceOverrideCents,
    ]);
}

function branch_catalog_ensure_state_for_new_product(PDO $pdo, int $productId): void
{
    $branches = branches_get_all($pdo, false);
    $stmt = $pdo->prepare(
        'INSERT INTO branch_products
            (branch_id, product_id, is_listed, is_available, price_cents_override, pickup_eta_minutes, updated_at)
         VALUES
            (:branch_id, :product_id, 0, 0, NULL, 15, NOW())'
    );

    foreach ($branches as $branch) {
        $stmt->execute([
            'branch_id' => (int) $branch['id'],
            'product_id' => $productId,
        ]);
    }
}

function format_datetime_for_ui(?string $value, string $fallback = 'Da definire'): string
{
    if (!is_string($value) || trim($value) === '') {
        return $fallback;
    }

    try {
        $date = new \DateTimeImmutable($value, new \DateTimeZone('Europe/Rome'));
        return $date->format('d/m/Y H:i');
    } catch (\Throwable $e) {
        return $value;
    }
}

function format_date_for_ui(?string $value, string $fallback = 'Da definire'): string
{
    if (!is_string($value) || trim($value) === '') {
        return $fallback;
    }

    try {
        $date = new \DateTimeImmutable($value, new \DateTimeZone('Europe/Rome'));
        return $date->format('d/m/Y');
    } catch (\Throwable $e) {
        return $value;
    }
}

function parse_local_datetime_to_sql(string $raw): ?string
{
    $raw = trim($raw);
    if ($raw === '') {
        return null;
    }

    $timezone = new \DateTimeZone('Europe/Rome');
    $date = \DateTimeImmutable::createFromFormat('Y-m-d\TH:i', $raw, $timezone);

    if (!$date instanceof \DateTimeImmutable) {
        return null;
    }

    return $date->format('Y-m-d H:i:s');
}

function admin_generate_document_code(string $prefix): string
{
    return strtoupper($prefix) . '-' . date('YmdHis') . '-' . random_int(1000, 9999);
}

function supply_frequency_options(): array
{
    return [
        'weekly' => 'Ogni settimana',
        'biweekly' => 'Ogni due settimane',
        'monthly' => 'Ogni mese',
    ];
}

function supply_next_run_datetime(string $frequency, \DateTimeImmutable $from): \DateTimeImmutable
{
    return match ($frequency) {
        'biweekly' => $from->modify('+14 days'),
        'monthly' => $from->modify('+1 month'),
        default => $from->modify('+7 days'),
    };
}

function inventory_ensure_row(PDO $pdo, int $branchId, int $productId): void
{
    $stmt = $pdo->prepare(
        'INSERT INTO branch_inventory
            (branch_id, product_id, on_hand_qty, average_unit_cost_cents, manual_unavailable, created_at, updated_at)
         VALUES
            (:branch_id, :product_id, 0, 0, 0, NOW(), NOW())
         ON DUPLICATE KEY UPDATE updated_at = updated_at'
    );
    $stmt->execute([
        'branch_id' => $branchId,
        'product_id' => $productId,
    ]);
}

function inventory_record_movement(
    PDO $pdo,
    int $branchId,
    int $productId,
    string $movementType,
    int $quantityDelta,
    int $quantityAfter,
    ?int $unitCostCents,
    string $referenceType,
    ?int $referenceId,
    ?int $createdByUserId,
    string $notes = ''
): void {
    $stmt = $pdo->prepare(
        'INSERT INTO inventory_movements
            (
                branch_id,
                product_id,
                movement_type,
                quantity_delta,
                quantity_after,
                unit_cost_cents,
                reference_type,
                reference_id,
                created_by_user_id,
                notes,
                created_at
            )
         VALUES
            (
                :branch_id,
                :product_id,
                :movement_type,
                :quantity_delta,
                :quantity_after,
                :unit_cost_cents,
                :reference_type,
                :reference_id,
                :created_by_user_id,
                :notes,
                NOW()
            )'
    );
    $stmt->execute([
        'branch_id' => $branchId,
        'product_id' => $productId,
        'movement_type' => $movementType,
        'quantity_delta' => $quantityDelta,
        'quantity_after' => $quantityAfter,
        'unit_cost_cents' => $unitCostCents,
        'reference_type' => $referenceType,
        'reference_id' => $referenceId,
        'created_by_user_id' => $createdByUserId,
        'notes' => mb_substr(trim($notes), 0, 255),
    ]);
}

function inventory_adjust_stock(
    PDO $pdo,
    int $branchId,
    int $productId,
    int $quantityDelta,
    string $movementType,
    string $referenceType,
    ?int $referenceId = null,
    string $notes = '',
    ?int $createdByUserId = null,
    ?int $unitCostCents = null
): int {
    inventory_ensure_row($pdo, $branchId, $productId);

    $select = $pdo->prepare(
        'SELECT on_hand_qty, average_unit_cost_cents
         FROM branch_inventory
         WHERE branch_id = :branch_id AND product_id = :product_id
         LIMIT 1
         FOR UPDATE'
    );
    $select->execute([
        'branch_id' => $branchId,
        'product_id' => $productId,
    ]);
    $inventory = $select->fetch();

    if (!$inventory) {
        throw new RuntimeException('Inventario di filiale non disponibile.');
    }

    $currentQty = (int) $inventory['on_hand_qty'];
    $currentAverageCost = (int) $inventory['average_unit_cost_cents'];
    $newQty = $currentQty + $quantityDelta;

    if ($newQty < 0) {
        throw new RuntimeException('Quantita inventario insufficiente per completare l operazione.');
    }

    $usedUnitCost = $unitCostCents !== null
        ? max(0, $unitCostCents)
        : $currentAverageCost;
    $newAverageCost = $currentAverageCost;

    if ($quantityDelta > 0) {
        if ($newQty === 0) {
            $newAverageCost = $usedUnitCost;
        } elseif ($currentQty <= 0) {
            $newAverageCost = $usedUnitCost;
        } else {
            $newAverageCost = (int) round(
                (($currentQty * $currentAverageCost) + ($quantityDelta * $usedUnitCost)) / $newQty
            );
        }
    }

    $update = $pdo->prepare(
        'UPDATE branch_inventory
         SET on_hand_qty = :on_hand_qty,
             average_unit_cost_cents = :average_unit_cost_cents,
             updated_at = NOW()
         WHERE branch_id = :branch_id AND product_id = :product_id'
    );
    $update->execute([
        'on_hand_qty' => $newQty,
        'average_unit_cost_cents' => $newAverageCost,
        'branch_id' => $branchId,
        'product_id' => $productId,
    ]);

    inventory_record_movement(
        $pdo,
        $branchId,
        $productId,
        $movementType,
        $quantityDelta,
        $newQty,
        $usedUnitCost,
        $referenceType,
        $referenceId,
        $createdByUserId,
        $notes
    );

    return $usedUnitCost;
}

function inventory_get_projection(PDO $pdo, int $branchId, int $productId): array
{
    inventory_ensure_row($pdo, $branchId, $productId);

    $stmt = $pdo->prepare(
        'SELECT on_hand_qty, average_unit_cost_cents
         FROM branch_inventory
         WHERE branch_id = :branch_id AND product_id = :product_id
         LIMIT 1'
    );
    $stmt->execute([
        'branch_id' => $branchId,
        'product_id' => $productId,
    ]);
    $row = $stmt->fetch() ?: ['on_hand_qty' => 0, 'average_unit_cost_cents' => 0];

    $pendingStmt = $pdo->prepare(
        'SELECT COALESCE(SUM(GREATEST(soi.quantity_ordered - soi.quantity_received, 0)), 0)
         FROM supply_order_items soi
         INNER JOIN supply_orders so ON so.id = soi.supply_order_id
         WHERE so.branch_id = :branch_id
           AND soi.product_id = :product_id
           AND so.status IN ("draft", "scheduled", "ordered")'
    );
    $pendingStmt->execute([
        'branch_id' => $branchId,
        'product_id' => $productId,
    ]);
    $pendingQty = (int) $pendingStmt->fetchColumn();
    $onHandQty = (int) $row['on_hand_qty'];

    return [
        'on_hand_qty' => $onHandQty,
        'pending_supply_qty' => $pendingQty,
        'projected_qty' => $onHandQty + $pendingQty,
        'average_unit_cost_cents' => (int) $row['average_unit_cost_cents'],
    ];
}

function inventory_consume_for_order(PDO $pdo, int $branchId, array $items, int $orderId, int $userId): array
{
    $costSnapshots = [];
    $productIds = [];

    foreach ($items as $item) {
        $productId = (int) ($item['product_id'] ?? 0);
        if ($productId > 0) {
            $productIds[$productId] = $productId;
        }
    }

    sort($productIds);
    foreach ($productIds as $productId) {
        inventory_ensure_row($pdo, $branchId, $productId);
    }

    foreach ($items as $item) {
        $productId = (int) ($item['product_id'] ?? 0);
        $quantity = (int) ($item['quantity'] ?? 0);
        $productName = (string) ($item['product_name'] ?? 'Prodotto');

        if ($productId <= 0 || $quantity <= 0) {
            continue;
        }

        $projection = inventory_get_projection($pdo, $branchId, $productId);
        if ((int) $projection['on_hand_qty'] < $quantity) {
            throw new RuntimeException(
                'Disponibilita insufficiente per ' . $productName . '. Restano ' . (int) $projection['on_hand_qty'] . ' unita in sede.'
            );
        }

        $costSnapshots[$productId] = inventory_adjust_stock(
            $pdo,
            $branchId,
            $productId,
            -$quantity,
            'customer_order',
            'order',
            $orderId,
            'Scarico automatico su ordine cliente.',
            $userId,
            (int) $projection['average_unit_cost_cents']
        );
    }

    return $costSnapshots;
}

function inventory_get_branch_products(PDO $pdo, int $branchId): array
{
    $stmt = $pdo->prepare(
        'SELECT
            p.id AS product_id,
            p.name AS product_name,
            p.slug AS product_slug,
            p.description,
            p.category_id,
            p.price_cents AS base_price_cents,
            p.allergens,
            p.image_path,
            p.image_focus_x,
            p.image_focus_y,
            c.name AS category_name,
            c.sort_order AS category_sort_order,
            COALESCE(bp.price_cents_override, p.price_cents) AS sale_price_cents,
            COALESCE(bp.is_listed, 0) AS is_listed,
            COALESCE(bp.is_available, p.is_available) AS branch_availability_flag,
            COALESCE(bi.on_hand_qty, 0) AS on_hand_qty,
            COALESCE(bi.average_unit_cost_cents, 0) AS average_unit_cost_cents,
            COALESCE(bi.manual_unavailable, 0) AS manual_unavailable,
            COALESCE(pending.pending_qty, 0) AS pending_supply_qty,
            COALESCE(policies.threshold_qty, 0) AS threshold_qty,
            COALESCE(policies.reorder_qty, 0) AS reorder_qty
         FROM products p
         INNER JOIN categories c ON c.id = p.category_id
         LEFT JOIN branch_products bp ON bp.product_id = p.id AND bp.branch_id = :branch_id_products
         LEFT JOIN branch_inventory bi ON bi.product_id = p.id AND bi.branch_id = :branch_id_inventory
         LEFT JOIN auto_reorder_policies policies ON policies.branch_id = :branch_id_policies AND policies.product_id = p.id
         LEFT JOIN (
             SELECT
                so.branch_id,
                soi.product_id,
                SUM(GREATEST(soi.quantity_ordered - soi.quantity_received, 0)) AS pending_qty
             FROM supply_orders so
             INNER JOIN supply_order_items soi ON soi.supply_order_id = so.id
             WHERE so.status IN ("draft", "scheduled", "ordered")
             GROUP BY so.branch_id, soi.product_id
         ) pending ON pending.branch_id = :branch_id_pending AND pending.product_id = p.id
         ORDER BY c.sort_order ASC, p.name ASC'
    );
    $stmt->execute([
        'branch_id_products' => $branchId,
        'branch_id_inventory' => $branchId,
        'branch_id_policies' => $branchId,
        'branch_id_pending' => $branchId,
    ]);
    $rows = $stmt->fetchAll();

    foreach ($rows as &$row) {
        $baseAvailable = (int) $row['branch_availability_flag'] === 1;
        $manualUnavailable = (int) $row['manual_unavailable'] === 1;
        $inStock = (int) $row['on_hand_qty'] > 0;
        $row['is_available_for_sale'] = (int) $row['is_listed'] === 1 && $baseAvailable && !$manualUnavailable && $inStock;
        $row['stock_value_cents'] = (int) $row['on_hand_qty'] * (int) $row['average_unit_cost_cents'];
        $row['projected_qty'] = (int) $row['on_hand_qty'] + (int) $row['pending_supply_qty'];
        $row['is_below_threshold'] = (int) $row['threshold_qty'] > 0 && (int) $row['projected_qty'] < (int) $row['threshold_qty'];
    }
    unset($row);

    return $rows;
}

function analytics_get_branch_kpis(PDO $pdo, int $branchId): array
{
    $salesStmt = $pdo->prepare(
        'SELECT
            COUNT(*) AS orders_count,
            COALESCE(SUM(total_cents), 0) AS revenue_cents
         FROM orders
         WHERE branch_id = :branch_id
           AND payment_status = "paid"
           AND order_status <> "cancelled"'
    );
    $salesStmt->execute(['branch_id' => $branchId]);
    $sales = $salesStmt->fetch() ?: ['orders_count' => 0, 'revenue_cents' => 0];

    $marginStmt = $pdo->prepare(
        'SELECT
            COALESCE(SUM(oi.line_total_cents), 0) AS revenue_cents,
            COALESCE(SUM(oi.quantity * COALESCE(oi.unit_cost_snapshot_cents, 0)), 0) AS cost_cents
         FROM order_items oi
         INNER JOIN orders o ON o.id = oi.order_id
         WHERE o.branch_id = :branch_id
           AND o.payment_status = "paid"
           AND o.order_status <> "cancelled"'
    );
    $marginStmt->execute(['branch_id' => $branchId]);
    $margin = $marginStmt->fetch() ?: ['revenue_cents' => 0, 'cost_cents' => 0];

    $supplyStmt = $pdo->prepare(
        'SELECT
            COUNT(*) AS received_supplies,
            COALESCE(SUM(total_cents), 0) AS supply_spend_cents
         FROM supply_orders
         WHERE branch_id = :branch_id
           AND status = "received"'
    );
    $supplyStmt->execute(['branch_id' => $branchId]);
    $supply = $supplyStmt->fetch() ?: ['received_supplies' => 0, 'supply_spend_cents' => 0];

    $inventoryStmt = $pdo->prepare(
        'SELECT
            COALESCE(SUM(on_hand_qty), 0) AS total_units,
            COALESCE(SUM(on_hand_qty * average_unit_cost_cents), 0) AS inventory_value_cents
         FROM branch_inventory
         WHERE branch_id = :branch_id'
    );
    $inventoryStmt->execute(['branch_id' => $branchId]);
    $inventory = $inventoryStmt->fetch() ?: ['total_units' => 0, 'inventory_value_cents' => 0];

    $pendingStmt = $pdo->prepare(
        'SELECT COUNT(*)
         FROM supply_orders
         WHERE branch_id = :branch_id
           AND status IN ("draft", "scheduled", "ordered")'
    );
    $pendingStmt->execute(['branch_id' => $branchId]);
    $pendingSupplies = (int) $pendingStmt->fetchColumn();

    $alertsStmt = $pdo->prepare(
        'SELECT COUNT(*)
         FROM auto_reorder_policies arp
         LEFT JOIN branch_inventory bi ON bi.branch_id = arp.branch_id AND bi.product_id = arp.product_id
         LEFT JOIN (
            SELECT
                so.branch_id,
                soi.product_id,
                SUM(GREATEST(soi.quantity_ordered - soi.quantity_received, 0)) AS pending_qty
            FROM supply_orders so
            INNER JOIN supply_order_items soi ON soi.supply_order_id = so.id
            WHERE so.status IN ("draft", "scheduled", "ordered")
            GROUP BY so.branch_id, soi.product_id
         ) pending ON pending.branch_id = arp.branch_id AND pending.product_id = arp.product_id
         WHERE arp.branch_id = :branch_id
           AND arp.is_active = 1
           AND (COALESCE(bi.on_hand_qty, 0) + COALESCE(pending.pending_qty, 0)) < arp.threshold_qty'
    );
    $alertsStmt->execute(['branch_id' => $branchId]);
    $stockAlerts = (int) $alertsStmt->fetchColumn();

    $revenue = (int) $margin['revenue_cents'];
    $costs = (int) $margin['cost_cents'];

    return [
        'orders_count' => (int) $sales['orders_count'],
        'revenue_cents' => (int) $sales['revenue_cents'],
        'gross_margin_cents' => $revenue - $costs,
        'supply_spend_cents' => (int) $supply['supply_spend_cents'],
        'received_supplies' => (int) $supply['received_supplies'],
        'inventory_units' => (int) $inventory['total_units'],
        'inventory_value_cents' => (int) $inventory['inventory_value_cents'],
        'pending_supplies' => $pendingSupplies,
        'stock_alerts' => $stockAlerts,
    ];
}

function analytics_get_top_products(PDO $pdo, int $branchId, int $limit = 8): array
{
    $limit = max(1, min($limit, 20));
    $stmt = $pdo->prepare(
        'SELECT
            COALESCE(oi.product_id, 0) AS product_id,
            oi.product_name,
            SUM(oi.quantity) AS quantity_sold,
            SUM(oi.line_total_cents) AS revenue_cents,
            SUM(oi.quantity * COALESCE(oi.unit_cost_snapshot_cents, 0)) AS cost_cents
         FROM order_items oi
         INNER JOIN orders o ON o.id = oi.order_id
         WHERE o.branch_id = :branch_id
           AND o.payment_status = "paid"
           AND o.order_status <> "cancelled"
         GROUP BY COALESCE(oi.product_id, 0), oi.product_name
         ORDER BY quantity_sold DESC, revenue_cents DESC
         LIMIT ' . $limit
    );
    $stmt->execute(['branch_id' => $branchId]);
    $rows = $stmt->fetchAll();

    foreach ($rows as &$row) {
        $row['gross_margin_cents'] = (int) $row['revenue_cents'] - (int) $row['cost_cents'];
    }
    unset($row);

    return $rows;
}

function analytics_get_sales_trend(PDO $pdo, int $branchId, int $days = 7): array
{
    $days = max(3, min($days, 30));
    $timezone = new \DateTimeZone('Europe/Rome');
    $startDate = (new \DateTimeImmutable('today', $timezone))->modify('-' . ($days - 1) . ' days');

    $stmt = $pdo->prepare(
        'SELECT
            DATE(created_at) AS sales_day,
            COUNT(*) AS orders_count,
            COALESCE(SUM(total_cents), 0) AS revenue_cents
         FROM orders
         WHERE branch_id = :branch_id
           AND payment_status = "paid"
           AND order_status <> "cancelled"
           AND created_at >= :start_date
         GROUP BY DATE(created_at)
         ORDER BY sales_day ASC'
    );
    $stmt->execute([
        'branch_id' => $branchId,
        'start_date' => $startDate->format('Y-m-d 00:00:00'),
    ]);

    $rows = [];
    foreach ($stmt->fetchAll() as $row) {
        $rows[(string) $row['sales_day']] = [
            'date_sql' => (string) $row['sales_day'],
            'label' => format_date_for_ui((string) $row['sales_day'], (string) $row['sales_day']),
            'orders_count' => (int) $row['orders_count'],
            'revenue_cents' => (int) $row['revenue_cents'],
        ];
    }

    $trend = [];
    for ($i = 0; $i < $days; $i++) {
        $current = $startDate->modify('+' . $i . ' days');
        $key = $current->format('Y-m-d');
        $trend[] = $rows[$key] ?? [
            'date_sql' => $key,
            'label' => $current->format('d/m'),
            'orders_count' => 0,
            'revenue_cents' => 0,
        ];
    }

    return $trend;
}

function analytics_get_category_mix(PDO $pdo, int $branchId): array
{
    $stmt = $pdo->prepare(
        'SELECT
            c.name AS category_name,
            SUM(oi.quantity) AS quantity_sold,
            SUM(oi.line_total_cents) AS revenue_cents
         FROM order_items oi
         INNER JOIN orders o ON o.id = oi.order_id
         LEFT JOIN products p ON p.id = oi.product_id
         LEFT JOIN categories c ON c.id = p.category_id
         WHERE o.branch_id = :branch_id
           AND o.payment_status = "paid"
           AND o.order_status <> "cancelled"
         GROUP BY c.id, c.name
         ORDER BY revenue_cents DESC, quantity_sold DESC'
    );
    $stmt->execute(['branch_id' => $branchId]);
    $rows = $stmt->fetchAll();
    $totalRevenue = 0;

    foreach ($rows as $row) {
        $totalRevenue += (int) $row['revenue_cents'];
    }

    foreach ($rows as &$row) {
        $row['revenue_share'] = $totalRevenue > 0
            ? round(((int) $row['revenue_cents'] / $totalRevenue) * 100, 1)
            : 0;
    }
    unset($row);

    return $rows;
}

function analytics_get_recent_customer_orders(PDO $pdo, int $branchId, int $limit = 8): array
{
    $limit = max(1, min($limit, 20));
    $stmt = $pdo->prepare(
        'SELECT
            id,
            order_number,
            branch_name_snapshot,
            payment_status,
            order_status,
            total_cents,
            created_at
         FROM orders
         WHERE branch_id = :branch_id
         ORDER BY created_at DESC
         LIMIT ' . $limit
    );
    $stmt->execute(['branch_id' => $branchId]);

    return $stmt->fetchAll();
}

function supply_create_template(
    PDO $pdo,
    int $branchId,
    int $userId,
    string $templateName,
    string $frequency,
    string $nextRunAtSql,
    array $items,
    string $notes = ''
): int {
    $name = trim($templateName);
    $frequencyOptions = supply_frequency_options();

    if ($name === '') {
        throw new RuntimeException('Inserisci un nome per la fornitura standard.');
    }

    if (!isset($frequencyOptions[$frequency])) {
        throw new RuntimeException('Frequenza fornitura non valida.');
    }

    if ($nextRunAtSql === '') {
        throw new RuntimeException('Inserisci la data di prima esecuzione della fornitura standard.');
    }

    if (empty($items)) {
        throw new RuntimeException('Aggiungi almeno un prodotto alla fornitura standard.');
    }

    $pdo->beginTransaction();

    try {
        $templateStmt = $pdo->prepare(
            'INSERT INTO supply_templates
                (branch_id, template_name, frequency, next_run_at, is_active, notes, created_by_user_id, created_at, updated_at)
             VALUES
                (:branch_id, :template_name, :frequency, :next_run_at, 1, :notes, :created_by_user_id, NOW(), NOW())'
        );
        $templateStmt->execute([
            'branch_id' => $branchId,
            'template_name' => mb_substr($name, 0, 120),
            'frequency' => $frequency,
            'next_run_at' => $nextRunAtSql,
            'notes' => mb_substr(trim($notes), 0, 255),
            'created_by_user_id' => $userId,
        ]);
        $templateId = (int) $pdo->lastInsertId();

        $itemStmt = $pdo->prepare(
            'INSERT INTO supply_template_items
                (template_id, product_id, quantity, unit_cost_cents, created_at, updated_at)
             VALUES
                (:template_id, :product_id, :quantity, :unit_cost_cents, NOW(), NOW())'
        );
        foreach ($items as $item) {
            $itemStmt->execute([
                'template_id' => $templateId,
                'product_id' => (int) $item['product_id'],
                'quantity' => (int) $item['quantity'],
                'unit_cost_cents' => (int) $item['unit_cost_cents'],
            ]);
        }

        $pdo->commit();
        return $templateId;
    } catch (\Throwable $e) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        throw $e;
    }
}

function supply_create_order(
    PDO $pdo,
    int $branchId,
    int $userId,
    string $orderType,
    string $status,
    array $items,
    ?string $scheduledForSql = null,
    ?int $templateId = null,
    string $notes = '',
    string $supplierName = 'Centro forniture SmashBurger',
    bool $manageTransaction = true
): int {
    if (empty($items)) {
        throw new RuntimeException('Inserisci almeno un prodotto nella fornitura.');
    }

    $validTypes = ['standard', 'extraordinary', 'automatic'];
    $validStatuses = ['draft', 'scheduled', 'ordered'];

    if (!in_array($orderType, $validTypes, true)) {
        throw new RuntimeException('Tipo fornitura non valido.');
    }

    if (!in_array($status, $validStatuses, true)) {
        throw new RuntimeException('Stato fornitura non valido.');
    }

    $orderCode = admin_generate_document_code('sup');
    $totalCents = 0;
    foreach ($items as $item) {
        $totalCents += (int) $item['quantity'] * (int) $item['unit_cost_cents'];
    }

    try {
        if ($manageTransaction) {
            $pdo->beginTransaction();
        }

        $stmt = $pdo->prepare(
            'INSERT INTO supply_orders
                (
                    branch_id,
                    template_id,
                    created_by_user_id,
                    order_code,
                    order_type,
                    status,
                    supplier_name,
                    scheduled_for,
                    ordered_at,
                    notes,
                    total_cents,
                    created_at,
                    updated_at
                )
             VALUES
                (
                    :branch_id,
                    :template_id,
                    :created_by_user_id,
                    :order_code,
                    :order_type,
                    :status,
                    :supplier_name,
                    :scheduled_for,
                    :ordered_at,
                    :notes,
                    :total_cents,
                    NOW(),
                    NOW()
                )'
        );
        $orderedAt = $status === 'ordered' ? date('Y-m-d H:i:s') : null;
        $stmt->execute([
            'branch_id' => $branchId,
            'template_id' => $templateId,
            'created_by_user_id' => $userId,
            'order_code' => $orderCode,
            'order_type' => $orderType,
            'status' => $status,
            'supplier_name' => mb_substr(trim($supplierName), 0, 120),
            'scheduled_for' => $scheduledForSql,
            'ordered_at' => $orderedAt,
            'notes' => mb_substr(trim($notes), 0, 255),
            'total_cents' => $totalCents,
        ]);
        $supplyOrderId = (int) $pdo->lastInsertId();

        $itemStmt = $pdo->prepare(
            'INSERT INTO supply_order_items
                (
                    supply_order_id,
                    product_id,
                    product_name_snapshot,
                    quantity_ordered,
                    quantity_received,
                    unit_cost_cents,
                    line_total_cents,
                    created_at,
                    updated_at
                )
             VALUES
                (
                    :supply_order_id,
                    :product_id,
                    :product_name_snapshot,
                    :quantity_ordered,
                    0,
                    :unit_cost_cents,
                    :line_total_cents,
                    NOW(),
                    NOW()
                )'
        );
        foreach ($items as $item) {
            $itemStmt->execute([
                'supply_order_id' => $supplyOrderId,
                'product_id' => (int) $item['product_id'],
                'product_name_snapshot' => mb_substr((string) $item['product_name'], 0, 140),
                'quantity_ordered' => (int) $item['quantity'],
                'unit_cost_cents' => (int) $item['unit_cost_cents'],
                'line_total_cents' => (int) $item['quantity'] * (int) $item['unit_cost_cents'],
            ]);
        }

        if ($manageTransaction && $pdo->inTransaction()) {
            $pdo->commit();
        }
        return $supplyOrderId;
    } catch (\Throwable $e) {
        if ($manageTransaction && $pdo->inTransaction()) {
            $pdo->rollBack();
        }
        throw $e;
    }
}

function supply_get_templates(PDO $pdo, int $branchId): array
{
    $stmt = $pdo->prepare(
        'SELECT
            st.id,
            st.template_name,
            st.frequency,
            st.next_run_at,
            st.last_generated_at,
            st.is_active,
            st.notes,
            st.created_at
         FROM supply_templates st
         WHERE st.branch_id = :branch_id
         ORDER BY st.is_active DESC, st.next_run_at ASC, st.id DESC'
    );
    $stmt->execute(['branch_id' => $branchId]);
    $templates = $stmt->fetchAll();

    if (empty($templates)) {
        return [];
    }

    $templateIds = array_map(static fn(array $template): int => (int) $template['id'], $templates);
    $placeholders = implode(',', array_fill(0, count($templateIds), '?'));
    $itemsStmt = $pdo->prepare(
        "SELECT
            sti.template_id,
            sti.product_id,
            p.name AS product_name,
            sti.quantity,
            sti.unit_cost_cents
         FROM supply_template_items sti
         INNER JOIN products p ON p.id = sti.product_id
         WHERE sti.template_id IN ($placeholders)
         ORDER BY p.name ASC"
    );
    foreach ($templateIds as $index => $templateId) {
        $itemsStmt->bindValue($index + 1, $templateId, PDO::PARAM_INT);
    }
    $itemsStmt->execute();

    $itemsByTemplate = [];
    foreach ($itemsStmt->fetchAll() as $item) {
        $templateId = (int) $item['template_id'];
        if (!isset($itemsByTemplate[$templateId])) {
            $itemsByTemplate[$templateId] = [];
        }
        $itemsByTemplate[$templateId][] = $item;
    }

    foreach ($templates as &$template) {
        $templateId = (int) $template['id'];
        $template['items'] = $itemsByTemplate[$templateId] ?? [];
    }
    unset($template);

    return $templates;
}

function supply_toggle_template(PDO $pdo, int $branchId, int $templateId, bool $isActive): void
{
    $stmt = $pdo->prepare(
        'UPDATE supply_templates
         SET is_active = :is_active,
             updated_at = NOW()
         WHERE id = :id
           AND branch_id = :branch_id
         LIMIT 1'
    );
    $stmt->execute([
        'is_active' => $isActive ? 1 : 0,
        'id' => $templateId,
        'branch_id' => $branchId,
    ]);
}

function supply_sync_due_templates(PDO $pdo, int $branchId, int $userId): int
{
    $stmt = $pdo->prepare(
        'SELECT
            id,
            template_name,
            frequency,
            next_run_at,
            notes
         FROM supply_templates
         WHERE branch_id = :branch_id
           AND is_active = 1
           AND next_run_at <= NOW()
         ORDER BY next_run_at ASC'
    );
    $stmt->execute(['branch_id' => $branchId]);
    $templates = $stmt->fetchAll();

    if (empty($templates)) {
        return 0;
    }

    $generated = 0;
    foreach ($templates as $template) {
        $itemStmt = $pdo->prepare(
            'SELECT
                sti.product_id,
                p.name AS product_name,
                sti.quantity,
                sti.unit_cost_cents
             FROM supply_template_items sti
             INNER JOIN products p ON p.id = sti.product_id
             WHERE sti.template_id = :template_id
             ORDER BY p.name ASC'
        );
        $itemStmt->execute(['template_id' => (int) $template['id']]);
        $items = $itemStmt->fetchAll();

        if (empty($items)) {
            continue;
        }

        $scheduledFor = (string) $template['next_run_at'];
        $nextRunBase = new \DateTimeImmutable($scheduledFor, new \DateTimeZone('Europe/Rome'));
        $nextRun = supply_next_run_datetime((string) $template['frequency'], $nextRunBase);

            $pdo->beginTransaction();

        try {
            supply_create_order(
                $pdo,
                $branchId,
                $userId,
                'standard',
                'scheduled',
                $items,
                $scheduledFor,
                (int) $template['id'],
                (string) $template['notes'],
                'Centro forniture SmashBurger',
                false
            );

            $update = $pdo->prepare(
                'UPDATE supply_templates
                 SET last_generated_at = :last_generated_at,
                     next_run_at = :next_run_at,
                     updated_at = NOW()
                 WHERE id = :id AND branch_id = :branch_id'
            );
            $update->execute([
                'last_generated_at' => $scheduledFor,
                'next_run_at' => $nextRun->format('Y-m-d H:i:s'),
                'id' => (int) $template['id'],
                'branch_id' => $branchId,
            ]);

            $pdo->commit();
            $generated++;
        } catch (\Throwable $e) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }
            throw $e;
        }
    }

    return $generated;
}

function supply_get_orders(PDO $pdo, int $branchId, int $limit = 18): array
{
    $limit = max(1, min($limit, 50));
    $stmt = $pdo->prepare(
        'SELECT
            so.id,
            so.order_code,
            so.order_type,
            so.status,
            so.supplier_name,
            so.scheduled_for,
            so.ordered_at,
            so.received_at,
            so.total_cents,
            so.notes,
            so.created_at,
            st.template_name
         FROM supply_orders so
         LEFT JOIN supply_templates st ON st.id = so.template_id
         WHERE so.branch_id = :branch_id
         ORDER BY so.created_at DESC, so.id DESC
         LIMIT ' . $limit
    );
    $stmt->execute(['branch_id' => $branchId]);
    $orders = $stmt->fetchAll();

    if (empty($orders)) {
        return [];
    }

    $orderIds = array_map(static fn(array $order): int => (int) $order['id'], $orders);
    $placeholders = implode(',', array_fill(0, count($orderIds), '?'));
    $itemsStmt = $pdo->prepare(
        "SELECT
            supply_order_id,
            product_id,
            product_name_snapshot,
            quantity_ordered,
            quantity_received,
            unit_cost_cents,
            line_total_cents
         FROM supply_order_items
         WHERE supply_order_id IN ($placeholders)
         ORDER BY id ASC"
    );
    foreach ($orderIds as $index => $orderId) {
        $itemsStmt->bindValue($index + 1, $orderId, PDO::PARAM_INT);
    }
    $itemsStmt->execute();

    $itemsByOrder = [];
    foreach ($itemsStmt->fetchAll() as $item) {
        $orderId = (int) $item['supply_order_id'];
        if (!isset($itemsByOrder[$orderId])) {
            $itemsByOrder[$orderId] = [];
        }
        $itemsByOrder[$orderId][] = $item;
    }

    foreach ($orders as &$order) {
        $orderId = (int) $order['id'];
        $order['items'] = $itemsByOrder[$orderId] ?? [];
    }
    unset($order);

    return $orders;
}

function supply_receive_order(PDO $pdo, int $branchId, int $supplyOrderId, int $userId): void
{
    $pdo->beginTransaction();

    try {
        $orderStmt = $pdo->prepare(
            'SELECT id, status
             FROM supply_orders
             WHERE id = :id AND branch_id = :branch_id
             LIMIT 1
             FOR UPDATE'
        );
        $orderStmt->execute([
            'id' => $supplyOrderId,
            'branch_id' => $branchId,
        ]);
        $order = $orderStmt->fetch();

        if (!$order) {
            throw new RuntimeException('Fornitura non trovata.');
        }

        if (in_array((string) $order['status'], ['received', 'cancelled'], true)) {
            throw new RuntimeException('La fornitura selezionata non può essere più modificata.');
        }

        $itemsStmt = $pdo->prepare(
            'SELECT
                product_id,
                quantity_ordered,
                unit_cost_cents
             FROM supply_order_items
             WHERE supply_order_id = :supply_order_id
             ORDER BY id ASC'
        );
        $itemsStmt->execute(['supply_order_id' => $supplyOrderId]);
        $items = $itemsStmt->fetchAll();

        if (empty($items)) {
            throw new RuntimeException('La fornitura non contiene prodotti.');
        }

        foreach ($items as $item) {
            inventory_adjust_stock(
                $pdo,
                $branchId,
                (int) $item['product_id'],
                (int) $item['quantity_ordered'],
                'supply_received',
                'supply_order',
                $supplyOrderId,
                'Carico automatico da ricezione fornitura.',
                $userId,
                (int) $item['unit_cost_cents']
            );
        }

        $itemUpdate = $pdo->prepare(
            'UPDATE supply_order_items
             SET quantity_received = quantity_ordered,
                 updated_at = NOW()
             WHERE supply_order_id = :supply_order_id'
        );
        $itemUpdate->execute(['supply_order_id' => $supplyOrderId]);

        $orderUpdate = $pdo->prepare(
            'UPDATE supply_orders
             SET status = "received",
                 received_at = NOW(),
                 updated_at = NOW()
             WHERE id = :id AND branch_id = :branch_id'
        );
        $orderUpdate->execute([
            'id' => $supplyOrderId,
            'branch_id' => $branchId,
        ]);

        $pdo->commit();
    } catch (\Throwable $e) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        throw $e;
    }
}

function supply_cancel_order(PDO $pdo, int $branchId, int $supplyOrderId): void
{
    $stmt = $pdo->prepare(
        'UPDATE supply_orders
         SET status = "cancelled",
             updated_at = NOW()
         WHERE id = :id
           AND branch_id = :branch_id
           AND status IN ("draft", "scheduled", "ordered")
         LIMIT 1'
    );
    $stmt->execute([
        'id' => $supplyOrderId,
        'branch_id' => $branchId,
    ]);
}

function auto_reorder_upsert_policy(
    PDO $pdo,
    int $branchId,
    int $productId,
    int $thresholdQty,
    int $reorderQty,
    int $cooldownHours,
    int $maxPendingQty,
    string $mode
): void {
    if ($thresholdQty <= 0 || $reorderQty <= 0) {
        throw new RuntimeException('Soglia e quantità di riordino devono essere maggiori di zero.');
    }

    if (!in_array($mode, ['draft', 'auto-order'], true)) {
        throw new RuntimeException('Modalità ordine automatico non valida.');
    }

    $stmt = $pdo->prepare(
        'INSERT INTO auto_reorder_policies
            (
                branch_id,
                product_id,
                threshold_qty,
                reorder_qty,
                cooldown_hours,
                max_pending_qty,
                mode,
                is_active,
                created_at,
                updated_at
            )
         VALUES
            (
                :branch_id,
                :product_id,
                :threshold_qty,
                :reorder_qty,
                :cooldown_hours,
                :max_pending_qty,
                :mode,
                1,
                NOW(),
                NOW()
            )
         ON DUPLICATE KEY UPDATE
            threshold_qty = VALUES(threshold_qty),
            reorder_qty = VALUES(reorder_qty),
            cooldown_hours = VALUES(cooldown_hours),
            max_pending_qty = VALUES(max_pending_qty),
            mode = VALUES(mode),
            is_active = 1,
            updated_at = NOW()'
    );
    $stmt->execute([
        'branch_id' => $branchId,
        'product_id' => $productId,
        'threshold_qty' => $thresholdQty,
        'reorder_qty' => $reorderQty,
        'cooldown_hours' => max(0, $cooldownHours),
        'max_pending_qty' => max(0, $maxPendingQty),
        'mode' => $mode,
    ]);
}

function auto_reorder_toggle_policy(PDO $pdo, int $branchId, int $policyId, bool $isActive): void
{
    $stmt = $pdo->prepare(
        'UPDATE auto_reorder_policies
         SET is_active = :is_active,
             updated_at = NOW()
         WHERE id = :id
           AND branch_id = :branch_id
         LIMIT 1'
    );
    $stmt->execute([
        'is_active' => $isActive ? 1 : 0,
        'id' => $policyId,
        'branch_id' => $branchId,
    ]);
}

function auto_reorder_get_policies(PDO $pdo, int $branchId): array
{
    $stmt = $pdo->prepare(
        'SELECT
            arp.id,
            arp.product_id,
            arp.threshold_qty,
            arp.reorder_qty,
            arp.cooldown_hours,
            arp.max_pending_qty,
            arp.mode,
            arp.is_active,
            arp.last_triggered_at,
            arp.updated_at,
            p.name AS product_name,
            COALESCE(bi.on_hand_qty, 0) AS on_hand_qty
         FROM auto_reorder_policies arp
         INNER JOIN products p ON p.id = arp.product_id
         LEFT JOIN branch_inventory bi ON bi.branch_id = arp.branch_id AND bi.product_id = arp.product_id
         WHERE arp.branch_id = :branch_id
         ORDER BY p.name ASC'
    );
    $stmt->execute(['branch_id' => $branchId]);

    return $stmt->fetchAll();
}

function auto_reorder_evaluate_branch(PDO $pdo, int $branchId, int $userId): int
{
    $policyStmt = $pdo->prepare(
        'SELECT
            arp.id,
            arp.product_id,
            arp.threshold_qty,
            arp.reorder_qty,
            arp.cooldown_hours,
            arp.max_pending_qty,
            arp.mode,
            arp.last_triggered_at,
            p.name AS product_name
         FROM auto_reorder_policies arp
         INNER JOIN products p ON p.id = arp.product_id
         WHERE arp.branch_id = :branch_id
           AND arp.is_active = 1
         ORDER BY p.name ASC'
    );
    $policyStmt->execute(['branch_id' => $branchId]);
    $policies = $policyStmt->fetchAll();

    if (empty($policies)) {
        return 0;
    }

    $generated = 0;
    foreach ($policies as $policy) {
        $projection = inventory_get_projection($pdo, $branchId, (int) $policy['product_id']);
        $pendingQty = (int) $projection['pending_supply_qty'];
        $projectedQty = (int) $projection['projected_qty'];
        $thresholdQty = (int) $policy['threshold_qty'];
        $maxPendingQty = (int) $policy['max_pending_qty'];
        $reorderQty = (int) $policy['reorder_qty'];

        if ($projectedQty >= $thresholdQty) {
            continue;
        }

        if ($maxPendingQty > 0 && $pendingQty >= $maxPendingQty) {
            continue;
        }

        $lastTriggeredAt = (string) ($policy['last_triggered_at'] ?? '');
        if ($lastTriggeredAt !== '' && (int) $policy['cooldown_hours'] > 0) {
            $lastTrigger = new \DateTimeImmutable($lastTriggeredAt, new \DateTimeZone('Europe/Rome'));
            $cooldownEndsAt = $lastTrigger->modify('+' . (int) $policy['cooldown_hours'] . ' hours');
            if ($cooldownEndsAt > new \DateTimeImmutable('now', new \DateTimeZone('Europe/Rome'))) {
                continue;
            }
        }

        $status = (string) $policy['mode'] === 'auto-order' ? 'ordered' : 'draft';
        supply_create_order(
            $pdo,
            $branchId,
            $userId,
            'automatic',
            $status,
            [[
                'product_id' => (int) $policy['product_id'],
                'product_name' => (string) $policy['product_name'],
                'quantity' => $reorderQty,
                'unit_cost_cents' => (int) $projection['average_unit_cost_cents'],
            ]],
            null,
            null,
            'Generata automaticamente da regola di riordino.'
        );

        $update = $pdo->prepare(
            'UPDATE auto_reorder_policies
             SET last_triggered_at = NOW(),
                 updated_at = NOW()
             WHERE id = :id AND branch_id = :branch_id'
        );
        $update->execute([
            'id' => (int) $policy['id'],
            'branch_id' => $branchId,
        ]);

        $generated++;
    }

    return $generated;
}

function receipt_get_customer_order(PDO $pdo, int $orderId): ?array
{
    $stmt = $pdo->prepare(
        'SELECT
            o.id,
            o.user_id,
            o.branch_id,
            o.branch_name_snapshot,
            o.order_number,
            o.fulfillment_type,
            o.pickup_at,
            o.order_status,
            o.payment_method,
            o.payment_status,
            o.total_cents,
            o.created_at,
            u.username,
            u.email
         FROM orders o
         INNER JOIN users u ON u.id = o.user_id
         WHERE o.id = :id
         LIMIT 1'
    );
    $stmt->execute(['id' => $orderId]);
    $order = $stmt->fetch();

    if (!$order) {
        return null;
    }

    $itemsStmt = $pdo->prepare(
        'SELECT
            product_name,
            quantity,
            unit_price_cents,
            line_total_cents,
            unit_cost_snapshot_cents
         FROM order_items
         WHERE order_id = :order_id
         ORDER BY id ASC'
    );
    $itemsStmt->execute(['order_id' => $orderId]);
    $order['items'] = $itemsStmt->fetchAll();
    $order['receipt_code'] = 'RCV-CLI-' . $order['order_number'];

    return $order;
}

function receipt_get_supply_order(PDO $pdo, int $supplyOrderId): ?array
{
    $stmt = $pdo->prepare(
        'SELECT
            so.id,
            so.branch_id,
            so.order_code,
            so.order_type,
            so.status,
            so.supplier_name,
            so.scheduled_for,
            so.ordered_at,
            so.received_at,
            so.total_cents,
            so.notes,
            so.created_at,
            b.name AS branch_name
         FROM supply_orders so
         INNER JOIN branches b ON b.id = so.branch_id
         WHERE so.id = :id
         LIMIT 1'
    );
    $stmt->execute(['id' => $supplyOrderId]);
    $order = $stmt->fetch();

    if (!$order) {
        return null;
    }

    $itemsStmt = $pdo->prepare(
        'SELECT
            product_name_snapshot,
            quantity_ordered,
            quantity_received,
            unit_cost_cents,
            line_total_cents
         FROM supply_order_items
         WHERE supply_order_id = :supply_order_id
         ORDER BY id ASC'
    );
    $itemsStmt->execute(['supply_order_id' => $supplyOrderId]);
    $order['items'] = $itemsStmt->fetchAll();
    $order['receipt_code'] = 'RCV-FOR-' . $order['order_code'];

    return $order;
}
