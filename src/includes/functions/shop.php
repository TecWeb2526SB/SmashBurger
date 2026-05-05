<?php
/**
 * Helper dominio catalogo/carrello/ordini + multi-sede/contatti.
 */

function money_eur(int $cents): string
{
    return number_format($cents / 100, 2, ',', '.') . ' EUR';
}

function branch_osm_embed_url(float $latitude, float $longitude): string
{
    $delta = 0.02;
    $minLon = $longitude - $delta;
    $maxLon = $longitude + $delta;
    $minLat = $latitude - $delta;
    $maxLat = $latitude + $delta;

    $bbox = rawurlencode(
        number_format($minLon, 6, '.', '') . ',' .
        number_format($minLat, 6, '.', '') . ',' .
        number_format($maxLon, 6, '.', '') . ',' .
        number_format($maxLat, 6, '.', '')
    );
    $marker = rawurlencode(
        number_format($latitude, 6, '.', '') . ',' .
        number_format($longitude, 6, '.', '')
    );

    return 'https://www.openstreetmap.org/export/embed.html?bbox=' . $bbox . '&layer=mapnik&marker=' . $marker;
}

function branch_hours_grouped(array $hours): array
{
    if (empty($hours)) {
        return [];
    }

    $fmt = static function ($time): string {
        $value = (string) $time;
        return strlen($value) >= 5 ? substr($value, 0, 5) : $value;
    };

    $days = [
        1 => 'Lun',
        2 => 'Mar',
        3 => 'Mer',
        4 => 'Gio',
        5 => 'Ven',
        6 => 'Sab',
        7 => 'Dom'
    ];

    $groups = [];
    foreach ($hours as $h) {
        $w = (int) $h['weekday'];
        $label = $days[$w] ?? $h['day_label'];
        $timeStr = ((int) $h['is_closed'] === 1)
            ? 'Chiuso'
            : $fmt($h['open_time']) . ' - ' . $fmt($h['close_time']);

        if (!isset($groups[$timeStr])) {
            $groups[$timeStr] = [];
        }
        $groups[$timeStr][] = ['weekday' => $w, 'label' => $label];
    }

    $final = [];
    foreach ($groups as $time => $dayList) {
        $dayLabels = array_column($dayList, 'label');
        $count = count($dayLabels);

        if ($count === 7) {
            $range = 'Tutti i giorni';
        } elseif ($count > 1) {
            // Check if sequential
            $isSequential = true;
            for ($i = 1; $i < $count; $i++) {
                if ($dayList[$i]['weekday'] !== $dayList[$i - 1]['weekday'] + 1) {
                    $isSequential = false;
                    break;
                }
            }

            if ($isSequential && $count >= 3) {
                $range = $dayLabels[0] . ' - ' . $dayLabels[$count - 1];
            } else {
                $range = implode(', ', $dayLabels);
            }
        } else {
            $range = $dayLabels[0];
        }

        $final[] = [
            'days' => $range,
            'hours' => $time
        ];
    }

    return $final;
}

function branch_hours_compact(array $hours): string
{
    if (empty($hours)) {
        return 'Orari non disponibili';
    }

    $fmt = static function ($time): string {
        $value = (string) $time;
        return strlen($value) >= 5 ? substr($value, 0, 5) : $value;
    };

    $first = $hours[0];
    if ((int) ($first['is_closed'] ?? 0) === 1) {
        return 'Orari non disponibili';
    }

    $weekTime = $fmt($hours[0]['open_time'] ?? '') . ' - ' . $fmt($hours[0]['close_time'] ?? '');
    $sat = $hours[5] ?? null;
    $sun = $hours[6] ?? null;
    $satTime = $sat ? ($fmt($sat['open_time'] ?? '') . ' - ' . $fmt($sat['close_time'] ?? '')) : $weekTime;
    $sunTime = $sun ? ($fmt($sun['open_time'] ?? '') . ' - ' . $fmt($sun['close_time'] ?? '')) : $weekTime;

    return 'Lun-Ven ' . $weekTime . ' | Sab ' . $satTime . ' | Dom ' . $sunTime;
}

function brand_contact_get(PDO $pdo): array
{
    $stmt = $pdo->query(
        'SELECT brand_name, support_email, info_phone, order_phone, instagram_url
         FROM brand_contacts
         ORDER BY id ASC
         LIMIT 1'
    );
    $row = $stmt->fetch();

    if ($row) {
        return $row;
    }

    return [
        'brand_name' => 'Smash Burger Original',
        'support_email' => 'info@smashburger.it',
        'info_phone' => '+39 049 000 1099',
        'order_phone' => '+39 049 000 1000',
        'instagram_url' => 'https://instagram.com/smashburgeroriginal',
    ];
}

function branches_get_all(PDO $pdo, bool $onlyActive = true): array
{
    $sql = 'SELECT
                id,
                name,
                slug,
                city,
                province,
                address_line,
                postal_code,
                phone,
                email,
                latitude,
                longitude,
                pickup_notes,
                is_active,
                sort_order
            FROM branches';

    if ($onlyActive) {
        $sql .= ' WHERE is_active = 1';
    }

    $sql .= ' ORDER BY sort_order ASC, city ASC';
    $stmt = $pdo->query($sql);
    $branches = $stmt->fetchAll();

    if (empty($branches)) {
        return [];
    }

    $ids = array_map(static function (array $branch): int {
        return (int) $branch['id'];
    }, $branches);

    $placeholders = implode(',', array_fill(0, count($ids), '?'));
    $hoursStmt = $pdo->prepare(
        "SELECT branch_id, weekday, day_label, open_time, close_time, is_closed
         FROM branch_hours
         WHERE branch_id IN ($placeholders)
         ORDER BY weekday ASC"
    );
    foreach ($ids as $index => $id) {
        $hoursStmt->bindValue($index + 1, $id, PDO::PARAM_INT);
    }
    $hoursStmt->execute();

    $hoursByBranch = [];
    foreach ($hoursStmt->fetchAll() as $hourRow) {
        $branchId = (int) $hourRow['branch_id'];
        if (!isset($hoursByBranch[$branchId])) {
            $hoursByBranch[$branchId] = [];
        }
        $hoursByBranch[$branchId][] = $hourRow;
    }

    foreach ($branches as &$branch) {
        $branchId = (int) $branch['id'];
        $branch['hours'] = $hoursByBranch[$branchId] ?? [];
        $branch['hours_compact'] = branch_hours_compact($branch['hours']);
        $branch['map_embed_url'] = branch_osm_embed_url((float) $branch['latitude'], (float) $branch['longitude']);
    }
    unset($branch);

    return $branches;
}

function branch_get_by_slug(PDO $pdo, string $slug): ?array
{
    $stmt = $pdo->prepare(
        'SELECT
            id,
            name,
            slug,
            city,
            province,
            address_line,
            postal_code,
            phone,
            email,
            latitude,
            longitude,
            pickup_notes,
            is_active,
            sort_order
         FROM branches
         WHERE slug = :slug AND is_active = 1
         LIMIT 1'
    );
    $stmt->execute(['slug' => $slug]);
    $branch = $stmt->fetch();

    if (!$branch) {
        return null;
    }

    $hoursStmt = $pdo->prepare(
        'SELECT weekday, day_label, open_time, close_time, is_closed
         FROM branch_hours
         WHERE branch_id = :branch_id
         ORDER BY weekday ASC'
    );
    $hoursStmt->execute(['branch_id' => (int) $branch['id']]);
    $branch['hours'] = $hoursStmt->fetchAll();
    $branch['hours_compact'] = branch_hours_compact($branch['hours']);
    $branch['map_embed_url'] = branch_osm_embed_url((float) $branch['latitude'], (float) $branch['longitude']);

    return $branch;
}

function branch_get_by_id(PDO $pdo, int $branchId): ?array
{
    $stmt = $pdo->prepare('SELECT slug FROM branches WHERE id = :id AND is_active = 1 LIMIT 1');
    $stmt->execute(['id' => $branchId]);
    $slug = $stmt->fetchColumn();
    if ($slug === false) {
        return null;
    }

    return branch_get_by_slug($pdo, (string) $slug);
}

function branch_get_default(PDO $pdo): ?array
{
    $stmt = $pdo->query(
        'SELECT slug
         FROM branches
         WHERE is_active = 1
         ORDER BY sort_order ASC, city ASC
         LIMIT 1'
    );
    $slug = $stmt->fetchColumn();
    if ($slug === false) {
        return null;
    }

    return branch_get_by_slug($pdo, (string) $slug);
}

function branch_get_selected(PDO $pdo): ?array
{
    $branchId = isset($_SESSION['selected_branch_id']) ? (int) $_SESSION['selected_branch_id'] : 0;
    if ($branchId > 0) {
        $branch = branch_get_by_id($pdo, $branchId);
        if ($branch !== null) {
            return $branch;
        }
    }

    $default = branch_get_default($pdo);
    if ($default !== null) {
        $_SESSION['selected_branch_id'] = (int) $default['id'];
        $_SESSION['selected_branch_slug'] = (string) $default['slug'];
    }

    return $default;
}

function branch_select_by_slug(PDO $pdo, string $slug): bool
{
    $branch = branch_get_by_slug($pdo, $slug);
    if ($branch === null) {
        return false;
    }

    $_SESSION['selected_branch_id'] = (int) $branch['id'];
    $_SESSION['selected_branch_slug'] = (string) $branch['slug'];
    return true;
}

function branch_get_hours_for_weekday(PDO $pdo, int $branchId, int $weekday): ?array
{
    if ($branchId <= 0 || $weekday < 1 || $weekday > 7) {
        return null;
    }

    $stmt = $pdo->prepare(
        'SELECT day_label, open_time, close_time, is_closed
         FROM branch_hours
         WHERE branch_id = :branch_id
           AND weekday = :weekday
         LIMIT 1'
    );
    $stmt->execute([
        'branch_id' => $branchId,
        'weekday' => $weekday,
    ]);
    $row = $stmt->fetch();

    return $row ?: null;
}

function branch_get_opening_window_for_date(
    PDO $pdo,
    int $branchId,
    \DateTimeImmutable $date,
    ?\DateTimeZone $tz = null
): ?array {
    $timezone = $tz ?? new \DateTimeZone('Europe/Rome');
    $weekday = (int) $date->format('N');
    $hours = branch_get_hours_for_weekday($pdo, $branchId, $weekday);
    if ($hours === null) {
        return null;
    }

    if ((int) ($hours['is_closed'] ?? 0) === 1) {
        return null;
    }

    $openTime = (string) ($hours['open_time'] ?? '');
    $closeTime = (string) ($hours['close_time'] ?? '');
    if ($openTime === '' || $closeTime === '') {
        return null;
    }

    $open = \DateTimeImmutable::createFromFormat(
        'Y-m-d H:i:s',
        $date->format('Y-m-d') . ' ' . substr($openTime, 0, 8),
        $timezone
    );
    $close = \DateTimeImmutable::createFromFormat(
        'Y-m-d H:i:s',
        $date->format('Y-m-d') . ' ' . substr($closeTime, 0, 8),
        $timezone
    );

    if ($open === false || $close === false) {
        return null;
    }

    if ($close <= $open) {
        $close = $close->modify('+1 day');
    }

    return [
        'day_label' => (string) ($hours['day_label'] ?? ''),
        'open' => $open,
        'close' => $close,
    ];
}

function branch_get_next_pickup_datetime(
    PDO $pdo,
    int $branchId,
    ?\DateTimeImmutable $now = null,
    ?\DateTimeZone $tz = null
): ?\DateTimeImmutable {
    $timezone = $tz ?? new \DateTimeZone('Europe/Rome');
    $reference = $now ?? new \DateTimeImmutable('now', $timezone);
    $minimumFromNow = $reference->modify('+10 minutes');
    $startDay = $reference->setTime(0, 0);

    for ($dayOffset = 0; $dayOffset <= 7; $dayOffset++) {
        $day = $startDay->modify('+' . $dayOffset . ' day');
        $window = branch_get_opening_window_for_date($pdo, $branchId, $day, $timezone);
        if ($window === null) {
            continue;
        }

        $candidate = $window['open']->modify('+10 minutes');
        if ($candidate < $minimumFromNow) {
            $candidate = $minimumFromNow;
        }

        if ($candidate <= $window['close']) {
            return $candidate;
        }
    }

    return null;
}

function branch_round_up_to_interval(\DateTimeImmutable $dt, int $intervalMinutes = 10): \DateTimeImmutable
{
    $minutesFromMidnight = ((int) $dt->format('H') * 60) + (int) $dt->format('i');
    $rounded = (int) (ceil($minutesFromMidnight / $intervalMinutes) * $intervalMinutes);

    return $dt->setTime(0, 0)->modify('+' . $rounded . ' minutes');
}

function branch_get_today_pickup_slots(
    PDO $pdo,
    int $branchId,
    int $intervalMinutes = 10,
    ?\DateTimeImmutable $now = null,
    ?\DateTimeZone $tz = null
): array {
    $timezone = $tz ?? new \DateTimeZone('Europe/Rome');
    $reference = $now ?? new \DateTimeImmutable('now', $timezone);
    $window = branch_get_opening_window_for_date($pdo, $branchId, $reference, $timezone);
    if ($window === null) {
        return [];
    }

    $minimumFromNow = $reference->modify('+10 minutes');
    $earliest = $window['open']->modify('+10 minutes');
    if ($minimumFromNow > $earliest) {
        $earliest = $minimumFromNow;
    }

    $cursor = branch_round_up_to_interval($earliest, $intervalMinutes);
    if ($cursor > $window['close']) {
        return [];
    }

    $slots = [];
    while ($cursor <= $window['close']) {
        $slots[] = [
            'time' => $cursor->format('H:i'),
            'raw' => $cursor->format('Y-m-d\TH:i'),
            'display' => $cursor->format('H:i'),
        ];
        $cursor = $cursor->modify('+' . $intervalMinutes . ' minutes');
    }

    return $slots;
}

function branch_validate_pickup_datetime(
    PDO $pdo,
    int $branchId,
    string $pickupAtRaw,
    ?\DateTimeZone $tz = null,
    bool $sameDayOnly = false
): array {
    $timezone = $tz ?? new \DateTimeZone('Europe/Rome');
    $value = trim($pickupAtRaw);
    if ($value === '') {
        return [
            'ok' => false,
            'message' => 'Seleziona data e orario di ritiro.',
        ];
    }

    $pickup = \DateTimeImmutable::createFromFormat('Y-m-d\TH:i', $value, $timezone);
    $errors = \DateTimeImmutable::getLastErrors();
    $hasFormatErrors = is_array($errors)
        && ((int) ($errors['warning_count'] ?? 0) > 0 || (int) ($errors['error_count'] ?? 0) > 0);
    if ($pickup === false || $hasFormatErrors) {
        return [
            'ok' => false,
            'message' => 'Formato data/ora non valido.',
        ];
    }

    $now = new \DateTimeImmutable('now', $timezone);
    if ($sameDayOnly && $pickup->format('Y-m-d') !== $now->format('Y-m-d')) {
        return [
            'ok' => false,
            'message' => 'Il ritiro programmato è disponibile solo per la giornata corrente.',
        ];
    }

    $minimumFromNow = $now->modify('+10 minutes');
    if ($pickup < $minimumFromNow) {
        if ($sameDayOnly) {
            $todaySlots = branch_get_today_pickup_slots($pdo, $branchId, 10, $now, $timezone);
            $suggestion = !empty($todaySlots) ? ' Primo orario disponibile: ' . $todaySlots[0]['display'] . '.' : '';
        } else {
            $next = branch_get_next_pickup_datetime($pdo, $branchId, $now, $timezone);
            $suggestion = $next ? ' Primo orario disponibile: ' . $next->format('d/m/Y H:i') . '.' : '';
        }
        return [
            'ok' => false,
            'message' => 'L\'orario selezionato non è più disponibile.' . $suggestion,
        ];
    }

    $window = branch_get_opening_window_for_date($pdo, $branchId, $pickup, $timezone);
    if ($window === null) {
        return [
            'ok' => false,
            'message' => 'La sede selezionata è chiusa nell\'orario richiesto.',
        ];
    }

    $earliest = $window['open']->modify('+10 minutes');
    if ($minimumFromNow > $earliest) {
        $earliest = $minimumFromNow;
    }

    if ($pickup < $earliest) {
        return [
            'ok' => false,
            'message' => 'Per il ritiro in sede il primo orario disponibile è ' . $earliest->format('d/m/Y H:i') . '.',
            'earliest_raw' => $earliest->format('Y-m-d\TH:i'),
            'earliest_display' => $earliest->format('d/m/Y H:i'),
        ];
    }

    if ($pickup > $window['close']) {
        return [
            'ok' => false,
            'message' => 'L\'orario selezionato supera la chiusura della sede.',
        ];
    }

    return [
        'ok' => true,
        'pickup_sql' => $pickup->format('Y-m-d H:i:s'),
        'pickup_raw' => $pickup->format('Y-m-d\TH:i'),
        'pickup_display' => $pickup->format('d/m/Y H:i'),
        'earliest_raw' => $earliest->format('Y-m-d\TH:i'),
        'earliest_display' => $earliest->format('d/m/Y H:i'),
    ];
}

function catalog_get(PDO $pdo, ?int $branchId = null): array
{
    if ($branchId === null) {
        $selectedBranch = branch_get_selected($pdo);
        $branchId = $selectedBranch ? (int) $selectedBranch['id'] : 0;
    }

    $categoriesStmt = $pdo->query(
        'SELECT id, name, slug
         FROM categories
         ORDER BY sort_order ASC, name ASC'
    );
    $categories = $categoriesStmt->fetchAll();

    $productsSql = 'SELECT
                        p.id,
                        p.category_id,
                        p.name,
                        p.description,
                        p.image_path,
                        p.image_focus_x,
                        p.image_focus_y,
                        p.allergens,
                        CASE
                            WHEN COALESCE(bp.is_listed, 0) <> 1 THEN 0
                            WHEN COALESCE(bi.manual_unavailable, 0) = 1 THEN 0
                            WHEN bp.is_available IS NULL
                                THEN CASE
                                    WHEN p.is_available = 1 AND bi.product_id IS NOT NULL AND bi.on_hand_qty > 0 THEN 1
                                    ELSE 0
                                END
                            WHEN p.is_available = 1 AND bp.is_available = 1 AND bi.product_id IS NOT NULL AND bi.on_hand_qty > 0 THEN 1
                            ELSE 0
                        END AS is_available,
                        COALESCE(bp.price_cents_override, p.price_cents) AS price_cents,
                        COALESCE(bp.pickup_eta_minutes, 15) AS pickup_eta_minutes,
                        COALESCE(bi.on_hand_qty, 0) AS inventory_qty,
                        COALESCE(bp.is_listed, 0) AS is_listed
                    FROM products p
                    LEFT JOIN branch_products bp
                        ON bp.product_id = p.id AND bp.branch_id = :branch_id
                    LEFT JOIN branch_inventory bi
                        ON bi.product_id = p.id AND bi.branch_id = :branch_id_2
                    ORDER BY p.category_id ASC, p.name ASC';

    $productsStmt = $pdo->prepare($productsSql);
    $productsStmt->execute([
        'branch_id' => $branchId,
        'branch_id_2' => $branchId
    ]);

    $byCategory = [];
    foreach ($productsStmt->fetchAll() as $product) {
        if ($branchId > 0 && (int) ($product['is_listed'] ?? 0) !== 1) {
            continue;
        }

        $categoryId = (int) $product['category_id'];
        if (!isset($byCategory[$categoryId])) {
            $byCategory[$categoryId] = [];
        }
        $byCategory[$categoryId][] = $product;
    }

    foreach ($categories as &$category) {
        $categoryId = (int) $category['id'];
        $category['products'] = $byCategory[$categoryId] ?? [];
    }
    unset($category);

    return $categories;
}

function cart_get_active_row(PDO $pdo, int $userId): ?array
{
    $stmt = $pdo->prepare(
        'SELECT id, branch_id
         FROM carts
         WHERE user_id = :user_id AND status = "active"
         ORDER BY updated_at DESC, id DESC
         LIMIT 1'
    );
    $stmt->execute(['user_id' => $userId]);
    $row = $stmt->fetch();

    return $row ?: null;
}

function cart_count_items(PDO $pdo, int $cartId): int
{
    $stmt = $pdo->prepare('SELECT COUNT(*) FROM cart_items WHERE cart_id = :cart_id');
    $stmt->execute(['cart_id' => $cartId]);
    return (int) $stmt->fetchColumn();
}

function cart_get_active_id(PDO $pdo, int $userId, ?int $branchId = null): int
{
    if ($branchId === null) {
        $selectedBranch = branch_get_selected($pdo);
        $branchId = $selectedBranch ? (int) $selectedBranch['id'] : 0;
    }

    if ($branchId <= 0) {
        throw new RuntimeException('Nessuna sede disponibile per creare il carrello.');
    }

    $active = cart_get_active_row($pdo, $userId);
    if ($active !== null) {
        $activeCartId = (int) $active['id'];
        $activeBranchId = (int) $active['branch_id'];

        if ($activeBranchId !== $branchId) {
            $itemsCount = cart_count_items($pdo, $activeCartId);
            if ($itemsCount > 0) {
                $activeBranch = branch_get_by_id($pdo, $activeBranchId);
                $activeBranchName = $activeBranch ? (string) $activeBranch['name'] : 'sede corrente';
                throw new RuntimeException(
                    'Hai già un carrello attivo per ' . $activeBranchName . '. ' .
                    'Svuotalo prima di cambiare sede.'
                );
            }

            $updateBranch = $pdo->prepare(
                'UPDATE carts
                 SET branch_id = :branch_id, updated_at = NOW()
                 WHERE id = :id'
            );
            $updateBranch->execute([
                'branch_id' => $branchId,
                'id' => $activeCartId,
            ]);
        }

        return $activeCartId;
    }

    $insert = $pdo->prepare(
        'INSERT INTO carts (user_id, branch_id, status, created_at, updated_at)
         VALUES (:user_id, :branch_id, "active", NOW(), NOW())'
    );
    $insert->execute([
        'user_id' => $userId,
        'branch_id' => $branchId,
    ]);
    return (int) $pdo->lastInsertId();
}

function cart_sync_with_selected_branch(PDO $pdo, int $userId, int $selectedBranchId, bool $force = false): array
{
    if ($selectedBranchId <= 0) {
        return ['ok' => false, 'message' => 'Nessuna sede valida selezionata.'];
    }

    $active = cart_get_active_row($pdo, $userId);
    if ($active === null) {
        return ['ok' => true, 'message' => null];
    }

    $activeCartId = (int) $active['id'];
    $activeBranchId = (int) $active['branch_id'];

    if ($activeBranchId === $selectedBranchId) {
        return ['ok' => true, 'message' => null];
    }

    $itemsCount = cart_count_items($pdo, $activeCartId);
    if ($itemsCount > 0) {
        if (!$force) {
            $activeBranch = branch_get_by_id($pdo, $activeBranchId);
            if ($activeBranch !== null) {
                $_SESSION['selected_branch_id'] = (int) $activeBranch['id'];
                $_SESSION['selected_branch_slug'] = (string) $activeBranch['slug'];
            }

            $branchName = $activeBranch ? (string) $activeBranch['name'] : 'sede corrente';
            return [
                'ok' => false,
                'message' => 'Hai un carrello attivo per ' . $branchName . '. Svuotalo prima di cambiare sede.',
            ];
        }

        // Se forziamo, svuotiamo il carrello usando la funzione già presente (linea 996)
        cart_clear($pdo, $userId);
    }

    $update = $pdo->prepare(
        'UPDATE carts
         SET branch_id = :branch_id, updated_at = NOW()
         WHERE id = :id'
    );
    $update->execute([
        'branch_id' => $selectedBranchId,
        'id' => $activeCartId,
    ]);

    // Dopo l'aggiornamento forzato, assicuriamoci che la sessione rifletta la NUOVA sede
    $newBranch = branch_get_by_id($pdo, $selectedBranchId);
    if ($newBranch) {
        $_SESSION['selected_branch_id'] = (int) $newBranch['id'];
        $_SESSION['selected_branch_slug'] = (string) $newBranch['slug'];
    }

    return [
        'ok' => true,
        'message' => 'Sede carrello aggiornata.',
    ];
}

function cart_get_summary(PDO $pdo, int $userId, ?int $branchId = null): array
{
    $active = cart_get_active_row($pdo, $userId);
    if ($active === null) {
        $selectedBranch = $branchId !== null ? branch_get_by_id($pdo, $branchId) : branch_get_selected($pdo);
        return [
            'cart_id' => null,
            'branch' => $selectedBranch,
            'items' => [],
            'total_cents' => 0,
            'items_count' => 0,
        ];
    }

    $cartId = (int) $active['id'];
    $cartBranchId = (int) $active['branch_id'];
    $cartBranch = branch_get_by_id($pdo, $cartBranchId);
    if ($cartBranch !== null) {
        $_SESSION['selected_branch_id'] = (int) $cartBranch['id'];
        $_SESSION['selected_branch_slug'] = (string) $cartBranch['slug'];
    }

    $itemsStmt = $pdo->prepare(
        'SELECT
            ci.id,
            ci.product_id,
            ci.quantity,
            ci.unit_price_cents,
            ci.line_total_cents,
            p.name AS product_name,
            p.image_path,
            p.allergens,
            CASE
                WHEN COALESCE(bp.is_listed, 0) <> 1 THEN 0
                WHEN COALESCE(bi.manual_unavailable, 0) = 1 THEN 0
                WHEN bp.is_available IS NULL
                    THEN CASE
                        WHEN p.is_available = 1 AND bi.product_id IS NOT NULL AND bi.on_hand_qty > 0 THEN 1
                        ELSE 0
                    END
                WHEN p.is_available = 1 AND bp.is_available = 1 AND bi.product_id IS NOT NULL AND bi.on_hand_qty > 0 THEN 1
                ELSE 0
            END AS is_available,
            COALESCE(bi.on_hand_qty, 0) AS inventory_qty,
            COALESCE(bp.is_listed, 0) AS is_listed
         FROM cart_items ci
         INNER JOIN products p ON p.id = ci.product_id
         LEFT JOIN branch_products bp ON bp.product_id = p.id AND bp.branch_id = :branch_id_bp
         LEFT JOIN branch_inventory bi ON bi.product_id = p.id AND bi.branch_id = :branch_id_bi
         WHERE ci.cart_id = :cart_id
         ORDER BY ci.id ASC'
    );
    $itemsStmt->execute([
        'cart_id' => $cartId,
        'branch_id_bp' => $cartBranchId,
        'branch_id_bi' => $cartBranchId,
    ]);
    $items = $itemsStmt->fetchAll();

    $totalCents = 0;
    $itemsCount = 0;
    foreach ($items as $item) {
        $totalCents += (int) $item['line_total_cents'];
        $itemsCount += (int) $item['quantity'];
    }

    return [
        'cart_id' => $cartId,
        'branch' => $cartBranch,
        'items' => $items,
        'total_cents' => $totalCents,
        'items_count' => $itemsCount,
    ];
}

function cart_add_product(PDO $pdo, int $userId, int $productId, int $quantity = 1, ?int $branchId = null): array
{
    if (function_exists('can_place_customer_orders') && !can_place_customer_orders()) {
        return ['ok' => false, 'message' => 'Gli account amministrativi e manager non possono aggiungere prodotti al carrello.'];
    }

    $maxQuantity = 100;

    if ($quantity < 1) {
        return ['ok' => false, 'message' => 'Quantità non valida.'];
    }

    $quantity = min($quantity, $maxQuantity);

    $selectedBranch = $branchId !== null ? branch_get_by_id($pdo, $branchId) : branch_get_selected($pdo);
    $branchId = $selectedBranch ? (int) $selectedBranch['id'] : 0;

    if ($branchId <= 0) {
        return ['ok' => false, 'message' => 'Nessuna sede selezionata.'];
    }

    $productStmt = $pdo->prepare(
        'SELECT
            p.id,
            p.name,
            COALESCE(bp.price_cents_override, p.price_cents) AS effective_price_cents,
            CASE
                WHEN COALESCE(bp.is_listed, 0) <> 1 THEN 0
                WHEN COALESCE(bi.manual_unavailable, 0) = 1 THEN 0
                WHEN bp.is_available IS NULL
                    THEN CASE
                        WHEN p.is_available = 1 AND bi.product_id IS NOT NULL AND bi.on_hand_qty > 0 THEN 1
                        ELSE 0
                    END
                WHEN p.is_available = 1 AND bp.is_available = 1 AND bi.product_id IS NOT NULL AND bi.on_hand_qty > 0 THEN 1
                ELSE 0
            END AS is_available,
            COALESCE(bi.on_hand_qty, 0) AS inventory_qty,
            CASE WHEN bi.product_id IS NULL THEN 0 ELSE 1 END AS has_inventory_row,
            COALESCE(bp.is_listed, 0) AS is_listed
         FROM products p
         LEFT JOIN branch_products bp ON bp.product_id = p.id AND bp.branch_id = :branch_id_bp
         LEFT JOIN branch_inventory bi ON bi.product_id = p.id AND bi.branch_id = :branch_id_bi
         WHERE p.id = :id
         LIMIT 1'
    );
    $productStmt->execute([
        'id' => $productId,
        'branch_id_bp' => $branchId,
        'branch_id_bi' => $branchId,
    ]);
    $product = $productStmt->fetch();

    if (!$product) {
        return ['ok' => false, 'message' => 'Prodotto non trovato.'];
    }

    if ((int) $product['is_available'] !== 1) {
        return [
            'ok' => false,
            'message' => (int) ($product['is_listed'] ?? 0) === 1
                ? 'Prodotto non disponibile nella sede selezionata.'
                : 'Prodotto non presente nel catalogo della sede selezionata.',
        ];
    }

    try {
        $cartId = cart_get_active_id($pdo, $userId, $branchId);
    } catch (RuntimeException $e) {
        return ['ok' => false, 'message' => $e->getMessage()];
    }

    $itemStmt = $pdo->prepare(
        'SELECT id, quantity, unit_price_cents
         FROM cart_items
         WHERE cart_id = :cart_id AND product_id = :product_id
         LIMIT 1'
    );
    $itemStmt->execute([
        'cart_id' => $cartId,
        'product_id' => $productId,
    ]);
    $existingItem = $itemStmt->fetch();

    if ($existingItem) {
        $newQty = min($maxQuantity, (int) $existingItem['quantity'] + $quantity);
        if ((int) ($product['has_inventory_row'] ?? 0) !== 1 || $newQty > (int) $product['inventory_qty']) {
            return [
                'ok' => false,
                'message' => 'Disponibilità limitata: in sede restano ' . (int) $product['inventory_qty'] . ' unità di ' . $product['name'] . '.',
            ];
        }
        $unit = (int) $existingItem['unit_price_cents'];
        $line = $unit * $newQty;
        $updateStmt = $pdo->prepare(
            'UPDATE cart_items
             SET quantity = :quantity,
                 line_total_cents = :line_total,
                 updated_at = NOW()
             WHERE id = :id'
        );
        $updateStmt->execute([
            'quantity' => $newQty,
            'line_total' => $line,
            'id' => (int) $existingItem['id'],
        ]);
    } else {
        if ((int) ($product['has_inventory_row'] ?? 0) !== 1 || $quantity > (int) $product['inventory_qty']) {
            return [
                'ok' => false,
                'message' => 'Disponibilità limitata: in sede restano ' . (int) $product['inventory_qty'] . ' unità di ' . $product['name'] . '.',
            ];
        }
        $unit = (int) $product['effective_price_cents'];
        $line = $unit * $quantity;
        $insertStmt = $pdo->prepare(
            'INSERT INTO cart_items
                (cart_id, product_id, quantity, unit_price_cents, line_total_cents, created_at, updated_at)
             VALUES
                (:cart_id, :product_id, :quantity, :unit_price, :line_total, NOW(), NOW())'
        );
        $insertStmt->execute([
            'cart_id' => $cartId,
            'product_id' => $productId,
            'quantity' => $quantity,
            'unit_price' => $unit,
            'line_total' => $line,
        ]);
    }

    $touchStmt = $pdo->prepare('UPDATE carts SET updated_at = NOW() WHERE id = :id');
    $touchStmt->execute(['id' => $cartId]);

    return ['ok' => true, 'message' => 'Prodotto aggiunto al carrello.'];
}

function cart_update_item_qty(PDO $pdo, int $userId, int $itemId, int $quantity, ?int $branchId = null): array
{
    $maxQuantity = 100;
    $active = cart_get_active_row($pdo, $userId);
    if ($active === null) {
        return ['ok' => false, 'message' => 'Carrello non trovato.'];
    }
    $cartId = (int) $active['id'];

    $stmt = $pdo->prepare(
        'SELECT
            ci.id,
            ci.cart_id,
            ci.unit_price_cents,
            p.name AS product_name,
            COALESCE(bi.on_hand_qty, 0) AS inventory_qty,
            CASE WHEN bi.product_id IS NULL THEN 0 ELSE 1 END AS has_inventory_row
         FROM cart_items ci
         INNER JOIN carts c ON c.id = ci.cart_id
         INNER JOIN products p ON p.id = ci.product_id
         LEFT JOIN branch_inventory bi ON bi.branch_id = c.branch_id AND bi.product_id = ci.product_id
         WHERE ci.id = :item_id
           AND c.user_id = :user_id
           AND c.id = :cart_id
           AND c.status = "active"
         LIMIT 1'
    );
    $stmt->execute([
        'item_id' => $itemId,
        'user_id' => $userId,
        'cart_id' => $cartId,
    ]);
    $item = $stmt->fetch();

    if (!$item) {
        return ['ok' => false, 'message' => 'Voce carrello non trovata.'];
    }

    if ($quantity <= 0) {
        $deleteStmt = $pdo->prepare('DELETE FROM cart_items WHERE id = :id');
        $deleteStmt->execute(['id' => $itemId]);
        return ['ok' => true, 'message' => 'Prodotto rimosso dal carrello.'];
    }

    $quantity = min($quantity, $maxQuantity);

    if ((int) ($item['has_inventory_row'] ?? 0) !== 1 || $quantity > (int) $item['inventory_qty']) {
        return [
            'ok' => false,
            'message' => 'Disponibilita limitata: in sede restano ' . (int) $item['inventory_qty'] . ' unita di ' . $item['product_name'] . '.',
        ];
    }

    $unit = (int) $item['unit_price_cents'];
    $line = $unit * $quantity;
    $updateStmt = $pdo->prepare(
        'UPDATE cart_items
         SET quantity = :quantity,
             line_total_cents = :line_total,
             updated_at = NOW()
         WHERE id = :id'
    );
    $updateStmt->execute([
        'quantity' => $quantity,
        'line_total' => $line,
        'id' => $itemId,
    ]);

    return ['ok' => true, 'message' => 'Quantita aggiornata.'];
}

function cart_remove_item(PDO $pdo, int $userId, int $itemId, ?int $branchId = null): array
{
    $active = cart_get_active_row($pdo, $userId);
    if ($active === null) {
        return ['ok' => false, 'message' => 'Carrello non trovato.'];
    }
    $cartId = (int) $active['id'];

    $stmt = $pdo->prepare(
        'DELETE ci FROM cart_items ci
         INNER JOIN carts c ON c.id = ci.cart_id
         WHERE ci.id = :item_id
           AND c.user_id = :user_id
           AND c.id = :cart_id
           AND c.status = "active"'
    );
    $stmt->execute([
        'item_id' => $itemId,
        'user_id' => $userId,
        'cart_id' => $cartId,
    ]);

    if ($stmt->rowCount() === 0) {
        return ['ok' => false, 'message' => 'Voce carrello non trovata.'];
    }

    return ['ok' => true, 'message' => 'Prodotto rimosso dal carrello.'];
}

function cart_clear(PDO $pdo, int $userId, ?int $branchId = null): void
{
    $active = cart_get_active_row($pdo, $userId);
    if ($active === null) {
        return;
    }
    $cartId = (int) $active['id'];

    $stmt = $pdo->prepare(
        'DELETE FROM cart_items
         WHERE cart_id = :cart_id'
    );
    $stmt->execute([
        'cart_id' => $cartId,
    ]);
}

function payment_simulate(string $method, int $totalCents, string $cardNumber = '', string $paypalEmail = ''): array
{
    $reference = strtoupper($method) . '-' . date('YmdHis') . '-' . random_int(1000, 9999);

    if ($method === 'card') {
        $digits = preg_replace('/\D+/', '', $cardNumber);
        if (strlen($digits) < 13 || strlen($digits) > 19) {
            return [
                'success' => false,
                'payment_status' => 'failed',
                'transaction_status' => 'declined',
                'gateway_reference' => $reference,
                'message' => 'Numero carta non valido.',
            ];
        }

        if (substr($digits, -4) === '0000') {
            return [
                'success' => false,
                'payment_status' => 'failed',
                'transaction_status' => 'declined',
                'gateway_reference' => $reference,
                'message' => 'Pagamento rifiutato dal circuito.',
            ];
        }

        $approved = random_int(1, 100) <= 88;
        return [
            'success' => $approved,
            'payment_status' => $approved ? 'paid' : 'failed',
            'transaction_status' => $approved ? 'approved' : 'declined',
            'gateway_reference' => $reference,
            'message' => $approved
                ? 'Pagamento autorizzato con successo.'
                : 'Pagamento non autorizzato. Riprova o usa un altro metodo.',
        ];
    }

    if ($method === 'paypal') {
        if (!filter_var($paypalEmail, FILTER_VALIDATE_EMAIL)) {
            return [
                'success' => false,
                'payment_status' => 'failed',
                'transaction_status' => 'declined',
                'gateway_reference' => $reference,
                'message' => 'Email PayPal non valida.',
            ];
        }

        $approved = random_int(1, 100) <= 92;
        return [
            'success' => $approved,
            'payment_status' => $approved ? 'paid' : 'failed',
            'transaction_status' => $approved ? 'approved' : 'declined',
            'gateway_reference' => $reference,
            'message' => $approved
                ? 'Pagamento PayPal completato.'
                : 'Pagamento PayPal rifiutato.',
        ];
    }

    return [
        'success' => false,
        'payment_status' => 'failed',
        'transaction_status' => 'declined',
        'gateway_reference' => $reference,
        'message' => 'Metodo di pagamento non supportato.',
    ];
}

function order_place(
    PDO $pdo,
    int $userId,
    string $fulfillmentType,
    string $paymentMethod,
    string $cardNumber = '',
    string $paypalEmail = '',
    string $pickupAtRaw = '',
    ?int $branchId = null
): array {
    if (function_exists('can_place_customer_orders') && !can_place_customer_orders()) {
        return ['ok' => false, 'message' => 'Gli account amministrativi e manager non possono effettuare ordini cliente.'];
    }

    $selectedBranch = $branchId !== null ? branch_get_by_id($pdo, $branchId) : branch_get_selected($pdo);
    $branchId = $selectedBranch ? (int) $selectedBranch['id'] : 0;

    if ($branchId <= 0) {
        return ['ok' => false, 'message' => 'Nessuna sede selezionata per il checkout.'];
    }

    $cart = cart_get_summary($pdo, $userId, $branchId);
    if (empty($cart['items'])) {
        return ['ok' => false, 'message' => 'Il carrello e vuoto.'];
    }

    foreach ($cart['items'] as $cartItem) {
        if ((int) ($cartItem['is_available'] ?? 0) !== 1) {
            return [
                'ok' => false,
                'message' => (int) ($cartItem['is_listed'] ?? 0) === 1
                    ? 'Nel carrello è presente un prodotto che non è più disponibile per questa sede. Aggiorna il carrello e riprova.'
                    : 'Nel carrello è presente un prodotto che non appartiene più al catalogo della sede selezionata. Aggiorna il carrello e riprova.',
            ];
        }
    }

    $cartBranchId = (int) ($cart['branch']['id'] ?? 0);
    if ($cartBranchId !== $branchId) {
        return [
            'ok' => false,
            'message' => 'Il carrello appartiene a una sede diversa. Allinea prima la sede selezionata.',
        ];
    }

    $fulfillment = in_array($fulfillmentType, ['asporto', 'ritiro'], true) ? $fulfillmentType : 'ritiro';
    $payment = in_array($paymentMethod, ['card', 'paypal'], true) ? $paymentMethod : 'card';

    $pickupAt = null;
    if ($fulfillment === 'ritiro') {
        $pickupValidation = branch_validate_pickup_datetime($pdo, $branchId, $pickupAtRaw, null, true);
        if (!$pickupValidation['ok']) {
            return ['ok' => false, 'message' => (string) $pickupValidation['message']];
        }
        $pickupAt = (string) $pickupValidation['pickup_sql'];
    }

    $paymentResult = payment_simulate(
        $payment,
        (int) $cart['total_cents'],
        $cardNumber,
        $paypalEmail
    );

    $orderStatus = $paymentResult['success'] ? 'confirmed' : 'cancelled';
    $paymentStatus = $paymentResult['payment_status'];
    $orderNumber = 'SB-' . date('YmdHis') . '-' . random_int(1000, 9999);
    $branchNameSnapshot = (string) ($selectedBranch['name'] ?? 'Sede non definita');

    try {
        $pdo->beginTransaction();

        $orderStmt = $pdo->prepare(
            'INSERT INTO orders
                (user_id, branch_id, branch_name_snapshot, order_number, fulfillment_type, pickup_at, order_status, payment_method, payment_status, total_cents, created_at, updated_at)
             VALUES
                (:user_id, :branch_id, :branch_name_snapshot, :order_number, :fulfillment_type, :pickup_at, :order_status, :payment_method, :payment_status, :total_cents, NOW(), NOW())'
        );
        $orderStmt->execute([
            'user_id' => $userId,
            'branch_id' => $branchId,
            'branch_name_snapshot' => $branchNameSnapshot,
            'order_number' => $orderNumber,
            'fulfillment_type' => $fulfillment,
            'pickup_at' => $pickupAt,
            'order_status' => $orderStatus,
            'payment_method' => $payment,
            'payment_status' => $paymentStatus,
            'total_cents' => (int) $cart['total_cents'],
        ]);
        $orderId = (int) $pdo->lastInsertId();

        $costSnapshots = [];
        if ($paymentResult['success']) {
            $costSnapshots = inventory_consume_for_order($pdo, $branchId, $cart['items'], $orderId, $userId);
        }

        $itemStmt = $pdo->prepare(
            'INSERT INTO order_items
                (order_id, product_id, product_name, quantity, unit_price_cents, line_total_cents, unit_cost_snapshot_cents, allergens_snapshot, created_at)
             VALUES
                (:order_id, :product_id, :product_name, :quantity, :unit_price_cents, :line_total_cents, :unit_cost_snapshot_cents, :allergens_snapshot, NOW())'
        );
        foreach ($cart['items'] as $item) {
            $productId = (int) $item['product_id'];
            $itemStmt->execute([
                'order_id' => $orderId,
                'product_id' => $productId,
                'product_name' => (string) $item['product_name'],
                'quantity' => (int) $item['quantity'],
                'unit_price_cents' => (int) $item['unit_price_cents'],
                'line_total_cents' => (int) $item['line_total_cents'],
                'unit_cost_snapshot_cents' => $costSnapshots[$productId] ?? null,
                'allergens_snapshot' => (string) ($item['allergens'] ?? ''),
            ]);
        }

        $paymentStmt = $pdo->prepare(
            'INSERT INTO payment_transactions
                (order_id, payment_method, transaction_status, gateway_reference, amount_cents, details, created_at)
             VALUES
                (:order_id, :payment_method, :transaction_status, :gateway_reference, :amount_cents, :details, NOW())'
        );
        $paymentStmt->execute([
            'order_id' => $orderId,
            'payment_method' => $payment,
            'transaction_status' => $paymentResult['transaction_status'],
            'gateway_reference' => $paymentResult['gateway_reference'],
            'amount_cents' => (int) $cart['total_cents'],
            'details' => $paymentResult['message'],
        ]);

        if ($paymentResult['success']) {
            $convertStmt = $pdo->prepare(
                'UPDATE carts
                 SET status = "converted",
                     converted_order_id = :order_id,
                     updated_at = NOW()
                 WHERE id = :cart_id'
            );
            $convertStmt->execute([
                'order_id' => $orderId,
                'cart_id' => (int) $cart['cart_id'],
            ]);
        }

        $pdo->commit();

        if ($paymentResult['success']) {
            try {
                auto_reorder_evaluate_branch($pdo, $branchId, $userId);
            } catch (\Throwable $autoReorderException) {
                error_log('Errore auto-riordino branch ' . $branchId . ': ' . $autoReorderException->getMessage());
            }
        }
    } catch (\Throwable $e) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        error_log('Errore creazione ordine: ' . $e->getMessage());
        return [
            'ok' => false,
            'message' => $e instanceof RuntimeException
                ? $e->getMessage()
                : 'Errore interno durante il checkout.',
        ];
    }

    return [
        'ok' => $paymentResult['success'],
        'order_id' => $orderId,
        'order_number' => $orderNumber,
        'message' => $paymentResult['message'],
        'fulfillment_type' => $fulfillment,
        'pickup_at' => $pickupAt,
    ];
}

function orders_get_for_user(PDO $pdo, int $userId): array
{
    $ordersStmt = $pdo->prepare(
        'SELECT
            id,
            order_number,
            branch_id,
            branch_name_snapshot,
            fulfillment_type,
            pickup_at,
            order_status,
            payment_method,
            payment_status,
            total_cents,
            created_at
         FROM orders
         WHERE user_id = :user_id
         ORDER BY created_at DESC
         LIMIT 20'
    );
    $ordersStmt->execute(['user_id' => $userId]);
    $orders = $ordersStmt->fetchAll();

    if (empty($orders)) {
        return [];
    }

    $orderIds = array_map(static function (array $order): int {
        return (int) $order['id'];
    }, $orders);

    $placeholders = implode(',', array_fill(0, count($orderIds), '?'));
    $itemsStmt = $pdo->prepare(
        "SELECT order_id, product_name, quantity, unit_price_cents, line_total_cents
         FROM order_items
         WHERE order_id IN ($placeholders)
         ORDER BY id ASC"
    );
    foreach ($orderIds as $index => $id) {
        $itemsStmt->bindValue($index + 1, $id, PDO::PARAM_INT);
    }
    $itemsStmt->execute();

    $itemsByOrder = [];
    foreach ($itemsStmt->fetchAll() as $item) {
        $orderId = (int) $item['order_id'];
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
