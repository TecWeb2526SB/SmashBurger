--
-- SmashBurger Database Schema v3 (multi-sede)
-- Allineato alla configurazione locale in .env (DB_NAME=esame_web).
--

CREATE DATABASE IF NOT EXISTS esame_web
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE esame_web;

SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS inventory_movements;
DROP TABLE IF EXISTS supply_order_items;
DROP TABLE IF EXISTS supply_orders;
DROP TABLE IF EXISTS supply_template_items;
DROP TABLE IF EXISTS supply_templates;
DROP TABLE IF EXISTS auto_reorder_policies;
DROP TABLE IF EXISTS branch_inventory;
DROP TABLE IF EXISTS payment_transactions;
DROP TABLE IF EXISTS order_items;
DROP TABLE IF EXISTS orders;
DROP TABLE IF EXISTS cart_items;
DROP TABLE IF EXISTS carts;
DROP TABLE IF EXISTS branch_products;
DROP TABLE IF EXISTS products;
DROP TABLE IF EXISTS categories;
DROP TABLE IF EXISTS branch_hours;
DROP TABLE IF EXISTS branches;
DROP TABLE IF EXISTS brand_contacts;
DROP TABLE IF EXISTS users;
SET FOREIGN_KEY_CHECKS = 1;

CREATE TABLE users (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(160) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    role ENUM('admin', 'branch_manager', 'user') NOT NULL DEFAULT 'user',
    managed_branch_id SMALLINT UNSIGNED NULL,
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    email_verified_at DATETIME NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE brand_contacts (
    id TINYINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    brand_name VARCHAR(120) NOT NULL,
    support_email VARCHAR(160) NOT NULL,
    info_phone VARCHAR(40) NOT NULL,
    order_phone VARCHAR(40) NOT NULL,
    instagram_url VARCHAR(255) NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE branches (
    id SMALLINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(120) NOT NULL UNIQUE,
    slug VARCHAR(120) NOT NULL UNIQUE,
    city VARCHAR(80) NOT NULL,
    province CHAR(2) NOT NULL,
    address_line VARCHAR(180) NOT NULL,
    postal_code VARCHAR(10) NOT NULL,
    phone VARCHAR(40) NOT NULL,
    email VARCHAR(160) NOT NULL,
    latitude DECIMAL(10, 7) NOT NULL,
    longitude DECIMAL(10, 7) NOT NULL,
    pickup_notes VARCHAR(255) NULL,
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    sort_order SMALLINT UNSIGNED NOT NULL DEFAULT 0,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_branches_active_sort (is_active, sort_order, city)
) ENGINE=InnoDB;

ALTER TABLE users
    ADD CONSTRAINT fk_users_managed_branch
    FOREIGN KEY (managed_branch_id) REFERENCES branches(id)
    ON UPDATE CASCADE ON DELETE SET NULL;

CREATE TABLE branch_hours (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    branch_id SMALLINT UNSIGNED NOT NULL,
    weekday TINYINT UNSIGNED NOT NULL,
    day_label VARCHAR(20) NOT NULL,
    open_time TIME NULL,
    close_time TIME NULL,
    is_closed TINYINT(1) NOT NULL DEFAULT 0,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_branch_hours_branch
        FOREIGN KEY (branch_id) REFERENCES branches(id)
        ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT uq_branch_weekday UNIQUE (branch_id, weekday),
    CONSTRAINT chk_branch_hours_weekday CHECK (weekday BETWEEN 1 AND 7)
) ENGINE=InnoDB;

CREATE TABLE categories (
    id SMALLINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(80) NOT NULL,
    slug VARCHAR(80) NOT NULL UNIQUE,
    sort_order SMALLINT UNSIGNED NOT NULL DEFAULT 0,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE products (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    category_id SMALLINT UNSIGNED NOT NULL,
    name VARCHAR(120) NOT NULL,
    slug VARCHAR(120) NOT NULL UNIQUE,
    description TEXT NOT NULL,
    image_path VARCHAR(255) NULL,
    image_focus_x TINYINT UNSIGNED NOT NULL DEFAULT 50,
    image_focus_y TINYINT UNSIGNED NOT NULL DEFAULT 50,
    allergens VARCHAR(255) NULL,
    is_available TINYINT(1) NOT NULL DEFAULT 1,
    price_cents INT UNSIGNED NOT NULL,
    created_by_user_id INT UNSIGNED NULL,
    updated_by_user_id INT UNSIGNED NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_products_category
        FOREIGN KEY (category_id) REFERENCES categories(id)
        ON UPDATE CASCADE ON DELETE RESTRICT,
    CONSTRAINT fk_products_created_by
        FOREIGN KEY (created_by_user_id) REFERENCES users(id)
        ON UPDATE CASCADE ON DELETE SET NULL,
    CONSTRAINT fk_products_updated_by
        FOREIGN KEY (updated_by_user_id) REFERENCES users(id)
        ON UPDATE CASCADE ON DELETE SET NULL,
    CONSTRAINT chk_products_price CHECK (price_cents >= 0),
    INDEX idx_products_category (category_id),
    INDEX idx_products_available (is_available)
) ENGINE=InnoDB;

CREATE TABLE branch_products (
    branch_id SMALLINT UNSIGNED NOT NULL,
    product_id INT UNSIGNED NOT NULL,
    is_listed TINYINT(1) NOT NULL DEFAULT 1,
    is_available TINYINT(1) NOT NULL DEFAULT 1,
    price_cents_override INT UNSIGNED NULL,
    pickup_eta_minutes SMALLINT UNSIGNED NOT NULL DEFAULT 15,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (branch_id, product_id),
    CONSTRAINT fk_branch_products_branch
        FOREIGN KEY (branch_id) REFERENCES branches(id)
        ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT fk_branch_products_product
        FOREIGN KEY (product_id) REFERENCES products(id)
        ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT chk_branch_products_price CHECK (price_cents_override IS NULL OR price_cents_override >= 0),
    INDEX idx_branch_products_available (branch_id, is_available)
) ENGINE=InnoDB;

CREATE TABLE branch_inventory (
    branch_id SMALLINT UNSIGNED NOT NULL,
    product_id INT UNSIGNED NOT NULL,
    on_hand_qty INT UNSIGNED NOT NULL DEFAULT 0,
    average_unit_cost_cents INT UNSIGNED NOT NULL DEFAULT 0,
    manual_unavailable TINYINT(1) NOT NULL DEFAULT 0,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (branch_id, product_id),
    CONSTRAINT fk_branch_inventory_branch
        FOREIGN KEY (branch_id) REFERENCES branches(id)
        ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT fk_branch_inventory_product
        FOREIGN KEY (product_id) REFERENCES products(id)
        ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT chk_branch_inventory_qty CHECK (on_hand_qty >= 0),
    CONSTRAINT chk_branch_inventory_avg_cost CHECK (average_unit_cost_cents >= 0)
) ENGINE=InnoDB;

CREATE TABLE auto_reorder_policies (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    branch_id SMALLINT UNSIGNED NOT NULL,
    product_id INT UNSIGNED NOT NULL,
    threshold_qty INT UNSIGNED NOT NULL,
    reorder_qty INT UNSIGNED NOT NULL,
    cooldown_hours SMALLINT UNSIGNED NOT NULL DEFAULT 6,
    max_pending_qty INT UNSIGNED NOT NULL DEFAULT 0,
    mode ENUM('draft', 'auto-order') NOT NULL DEFAULT 'draft',
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    last_triggered_at DATETIME NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_auto_reorder_branch
        FOREIGN KEY (branch_id) REFERENCES branches(id)
        ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT fk_auto_reorder_product
        FOREIGN KEY (product_id) REFERENCES products(id)
        ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT uq_auto_reorder_branch_product UNIQUE (branch_id, product_id),
    CONSTRAINT chk_auto_reorder_threshold CHECK (threshold_qty > 0),
    CONSTRAINT chk_auto_reorder_qty CHECK (reorder_qty > 0)
) ENGINE=InnoDB;

CREATE TABLE carts (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNSIGNED NOT NULL,
    branch_id SMALLINT UNSIGNED NOT NULL,
    status ENUM('active', 'converted', 'abandoned') NOT NULL DEFAULT 'active',
    active_user_lock INT UNSIGNED NULL,
    converted_order_id INT UNSIGNED NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_carts_user
        FOREIGN KEY (user_id) REFERENCES users(id)
        ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT fk_carts_branch
        FOREIGN KEY (branch_id) REFERENCES branches(id)
        ON UPDATE CASCADE ON DELETE RESTRICT,
    CONSTRAINT uq_carts_one_active_user UNIQUE (active_user_lock),
    INDEX idx_carts_user_branch_status (user_id, branch_id, status)
) ENGINE=InnoDB;

DELIMITER $$

CREATE TRIGGER trg_carts_before_insert
BEFORE INSERT ON carts
FOR EACH ROW
BEGIN
    IF NEW.status = 'active' THEN
        SET NEW.active_user_lock = NEW.user_id;
    ELSE
        SET NEW.active_user_lock = NULL;
    END IF;
END$$

CREATE TRIGGER trg_carts_before_update
BEFORE UPDATE ON carts
FOR EACH ROW
BEGIN
    IF NEW.status = 'active' THEN
        SET NEW.active_user_lock = NEW.user_id;
    ELSE
        SET NEW.active_user_lock = NULL;
    END IF;
END$$

DELIMITER ;

CREATE TABLE cart_items (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    cart_id INT UNSIGNED NOT NULL,
    product_id INT UNSIGNED NOT NULL,
    quantity INT UNSIGNED NOT NULL DEFAULT 1,
    unit_price_cents INT UNSIGNED NOT NULL,
    line_total_cents INT UNSIGNED NOT NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_cart_items_cart
        FOREIGN KEY (cart_id) REFERENCES carts(id)
        ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT fk_cart_items_product
        FOREIGN KEY (product_id) REFERENCES products(id)
        ON UPDATE CASCADE ON DELETE RESTRICT,
    CONSTRAINT uq_cart_items_product UNIQUE (cart_id, product_id),
    CONSTRAINT chk_cart_items_qty CHECK (quantity > 0),
    CONSTRAINT chk_cart_items_prices CHECK (unit_price_cents >= 0 AND line_total_cents >= 0),
    INDEX idx_cart_items_cart (cart_id)
) ENGINE=InnoDB;

CREATE TABLE orders (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNSIGNED NOT NULL,
    branch_id SMALLINT UNSIGNED NOT NULL,
    branch_name_snapshot VARCHAR(140) NOT NULL,
    order_number VARCHAR(32) NOT NULL UNIQUE,
    fulfillment_type ENUM('asporto', 'ritiro') NOT NULL DEFAULT 'ritiro',
    pickup_at DATETIME NULL,
    order_status ENUM('pending', 'confirmed', 'preparing', 'ready', 'completed', 'cancelled') NOT NULL DEFAULT 'pending',
    payment_method ENUM('card', 'paypal') NOT NULL,
    payment_status ENUM('pending', 'paid', 'failed', 'refunded') NOT NULL DEFAULT 'pending',
    total_cents INT UNSIGNED NOT NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_orders_user
        FOREIGN KEY (user_id) REFERENCES users(id)
        ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT fk_orders_branch
        FOREIGN KEY (branch_id) REFERENCES branches(id)
        ON UPDATE CASCADE ON DELETE RESTRICT,
    CONSTRAINT chk_orders_total CHECK (total_cents >= 0),
    INDEX idx_orders_user (user_id),
    INDEX idx_orders_branch_status (branch_id, order_status)
) ENGINE=InnoDB;

ALTER TABLE carts
    ADD CONSTRAINT fk_carts_converted_order
    FOREIGN KEY (converted_order_id) REFERENCES orders(id)
    ON UPDATE CASCADE ON DELETE SET NULL;

CREATE TABLE order_items (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    order_id INT UNSIGNED NOT NULL,
    product_id INT UNSIGNED NULL,
    product_name VARCHAR(140) NOT NULL,
    quantity INT UNSIGNED NOT NULL,
    unit_price_cents INT UNSIGNED NOT NULL,
    line_total_cents INT UNSIGNED NOT NULL,
    unit_cost_snapshot_cents INT UNSIGNED NULL,
    allergens_snapshot VARCHAR(255) NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_order_items_order
        FOREIGN KEY (order_id) REFERENCES orders(id)
        ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT fk_order_items_product
        FOREIGN KEY (product_id) REFERENCES products(id)
        ON UPDATE CASCADE ON DELETE SET NULL,
    CONSTRAINT chk_order_items_qty CHECK (quantity > 0),
    CONSTRAINT chk_order_items_prices CHECK (unit_price_cents >= 0 AND line_total_cents >= 0),
    INDEX idx_order_items_order (order_id)
) ENGINE=InnoDB;

CREATE TABLE payment_transactions (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    order_id INT UNSIGNED NOT NULL,
    payment_method ENUM('card', 'paypal') NOT NULL,
    transaction_status ENUM('pending', 'approved', 'declined', 'refunded') NOT NULL DEFAULT 'pending',
    gateway_reference VARCHAR(64) NOT NULL,
    amount_cents INT UNSIGNED NOT NULL,
    details VARCHAR(255) NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_payment_transactions_order
        FOREIGN KEY (order_id) REFERENCES orders(id)
        ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT chk_payment_amount CHECK (amount_cents >= 0),
    INDEX idx_payment_transactions_order (order_id)
) ENGINE=InnoDB;

CREATE TABLE supply_templates (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    branch_id SMALLINT UNSIGNED NOT NULL,
    template_name VARCHAR(120) NOT NULL,
    frequency ENUM('weekly', 'biweekly', 'monthly') NOT NULL DEFAULT 'weekly',
    next_run_at DATETIME NOT NULL,
    last_generated_at DATETIME NULL,
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    notes VARCHAR(255) NULL,
    created_by_user_id INT UNSIGNED NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_supply_templates_branch
        FOREIGN KEY (branch_id) REFERENCES branches(id)
        ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT fk_supply_templates_user
        FOREIGN KEY (created_by_user_id) REFERENCES users(id)
        ON UPDATE CASCADE ON DELETE SET NULL,
    INDEX idx_supply_templates_branch_next_run (branch_id, is_active, next_run_at)
) ENGINE=InnoDB;

CREATE TABLE supply_template_items (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    template_id INT UNSIGNED NOT NULL,
    product_id INT UNSIGNED NOT NULL,
    quantity INT UNSIGNED NOT NULL,
    unit_cost_cents INT UNSIGNED NOT NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_supply_template_items_template
        FOREIGN KEY (template_id) REFERENCES supply_templates(id)
        ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT fk_supply_template_items_product
        FOREIGN KEY (product_id) REFERENCES products(id)
        ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT chk_supply_template_items_qty CHECK (quantity > 0),
    CONSTRAINT chk_supply_template_items_cost CHECK (unit_cost_cents >= 0)
) ENGINE=InnoDB;

CREATE TABLE supply_orders (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    branch_id SMALLINT UNSIGNED NOT NULL,
    template_id INT UNSIGNED NULL,
    created_by_user_id INT UNSIGNED NULL,
    order_code VARCHAR(32) NOT NULL UNIQUE,
    order_type ENUM('standard', 'extraordinary', 'automatic') NOT NULL DEFAULT 'extraordinary',
    status ENUM('draft', 'scheduled', 'ordered', 'received', 'cancelled') NOT NULL DEFAULT 'draft',
    supplier_name VARCHAR(120) NOT NULL,
    scheduled_for DATETIME NULL,
    ordered_at DATETIME NULL,
    received_at DATETIME NULL,
    notes VARCHAR(255) NULL,
    total_cents INT UNSIGNED NOT NULL DEFAULT 0,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_supply_orders_branch
        FOREIGN KEY (branch_id) REFERENCES branches(id)
        ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT fk_supply_orders_template
        FOREIGN KEY (template_id) REFERENCES supply_templates(id)
        ON UPDATE CASCADE ON DELETE SET NULL,
    CONSTRAINT fk_supply_orders_user
        FOREIGN KEY (created_by_user_id) REFERENCES users(id)
        ON UPDATE CASCADE ON DELETE SET NULL,
    CONSTRAINT chk_supply_orders_total CHECK (total_cents >= 0),
    INDEX idx_supply_orders_branch_status (branch_id, status, created_at)
) ENGINE=InnoDB;

CREATE TABLE supply_order_items (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    supply_order_id INT UNSIGNED NOT NULL,
    product_id INT UNSIGNED NOT NULL,
    product_name_snapshot VARCHAR(140) NOT NULL,
    quantity_ordered INT UNSIGNED NOT NULL,
    quantity_received INT UNSIGNED NOT NULL DEFAULT 0,
    unit_cost_cents INT UNSIGNED NOT NULL,
    line_total_cents INT UNSIGNED NOT NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_supply_order_items_supply_order
        FOREIGN KEY (supply_order_id) REFERENCES supply_orders(id)
        ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT fk_supply_order_items_product
        FOREIGN KEY (product_id) REFERENCES products(id)
        ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT chk_supply_order_items_ordered CHECK (quantity_ordered > 0),
    CONSTRAINT chk_supply_order_items_received CHECK (quantity_received <= quantity_ordered),
    CONSTRAINT chk_supply_order_items_cost CHECK (unit_cost_cents >= 0),
    CONSTRAINT chk_supply_order_items_line CHECK (line_total_cents >= 0)
) ENGINE=InnoDB;

CREATE TABLE inventory_movements (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    branch_id SMALLINT UNSIGNED NOT NULL,
    product_id INT UNSIGNED NOT NULL,
    movement_type ENUM('supply_received', 'customer_order', 'manual_adjustment', 'supply_cancelled', 'order_cancelled') NOT NULL,
    quantity_delta INT NOT NULL,
    quantity_after INT NOT NULL,
    unit_cost_cents INT UNSIGNED NULL,
    reference_type ENUM('order', 'supply_order', 'manual', 'policy') NOT NULL DEFAULT 'manual',
    reference_id INT UNSIGNED NULL,
    created_by_user_id INT UNSIGNED NULL,
    notes VARCHAR(255) NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_inventory_movements_branch
        FOREIGN KEY (branch_id) REFERENCES branches(id)
        ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT fk_inventory_movements_product
        FOREIGN KEY (product_id) REFERENCES products(id)
        ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT fk_inventory_movements_user
        FOREIGN KEY (created_by_user_id) REFERENCES users(id)
        ON UPDATE CASCADE ON DELETE SET NULL,
    INDEX idx_inventory_movements_branch_product (branch_id, product_id, created_at)
) ENGINE=InnoDB;

INSERT INTO users (username, email, password_hash, role, is_active, email_verified_at)
VALUES
    ('admin', 'admin@smashburger.it', '$2y$10$TuGSbILQNQipwO6uiHAz0uxgmt.21iLrD/Wmv9DNXbpEwjkIuf2LS', 'admin', 1, NOW()),
    ('user', 'user@smashburger.it', '$2y$10$/vM6p.rHEgV2uspgXG5uHegXCxoWmthYDWfXRNuPAhwf5VjgC5nQa', 'user', 1, NOW());

INSERT INTO brand_contacts (brand_name, support_email, info_phone, order_phone, instagram_url)
VALUES
    (
        'Smash Burger Original',
        'info@smashburger.it',
        '+39 049 000 1099',
        '+39 049 000 1000',
        'https://instagram.com/smashburgeroriginal'
    );

INSERT INTO branches (
    name, slug, city, province, address_line, postal_code, phone, email, latitude, longitude, pickup_notes, is_active, sort_order
) VALUES
    (
        'Smash Burger Padova Centro',
        'padova-centro',
        'Padova',
        'PD',
        'Via Roma 42',
        '35122',
        '+39 049 111 2201',
        'padova@smashburger.it',
        45.4064349,
        11.8767611,
        'Ritiro dal banco dedicato vicino alla cassa principale.',
        1,
        1
    ),
    (
        'Smash Burger Treviso Centro',
        'treviso-centro',
        'Treviso',
        'TV',
        'Piazza dei Signori 8',
        '31100',
        '+39 0422 321 780',
        'treviso@smashburger.it',
        45.6668893,
        12.2430437,
        'Ritiro rapido con numero ordine mostrato al totem.',
        1,
        2
    ),
    (
        'Smash Burger Vicenza Centro',
        'vicenza-centro',
        'Vicenza',
        'VI',
        'Corso Palladio 64',
        '36100',
        '+39 0444 210 995',
        'vicenza@smashburger.it',
        45.5454787,
        11.5354214,
        'Area ritiro dedicata all interno del locale.',
        1,
        3
    ),
    (
        'Smash Burger Udine Centro',
        'udine-centro',
        'Udine',
        'UD',
        'Via Mercatovecchio 21',
        '33100',
        '+39 0432 442 118',
        'udine@smashburger.it',
        46.0634632,
        13.2358377,
        'Ritiro disponibile anche dal pickup desk laterale.',
        1,
        4
    );

INSERT INTO branch_hours (branch_id, weekday, day_label, open_time, close_time, is_closed)
SELECT b.id, d.weekday, d.day_label, d.open_time, d.close_time, d.is_closed
FROM branches b
JOIN (
    SELECT 1 AS weekday, 'Lunedi' AS day_label, '11:30:00' AS open_time, '22:30:00' AS close_time, 0 AS is_closed
    UNION ALL
    SELECT 2, 'Martedi', '11:30:00', '22:30:00', 0
    UNION ALL
    SELECT 3, 'Mercoledi', '11:30:00', '22:30:00', 0
    UNION ALL
    SELECT 4, 'Giovedi', '11:30:00', '22:30:00', 0
    UNION ALL
    SELECT 5, 'Venerdi', '11:30:00', '22:30:00', 0
    UNION ALL
    SELECT 6, 'Sabato', '11:00:00', '23:30:00', 0
    UNION ALL
    SELECT 7, 'Domenica', '12:00:00', '22:00:00', 0
) AS d;

INSERT INTO users (username, email, password_hash, role, managed_branch_id, is_active, email_verified_at)
VALUES
    (
        'manager.padova',
        'manager.padova@smashburger.it',
        '$2y$10$FLKBQukAwlC66SFGUKQ.aOoxbksjNIFXypbcL0s0lqvyJFk8Jr.7e',
        'branch_manager',
        (SELECT id FROM branches WHERE slug = 'padova-centro'),
        1,
        NOW()
    ),
    (
        'manager.treviso',
        'manager.treviso@smashburger.it',
        '$2y$10$ux57UfeRfP/4HYzGhudq6ugOtdl.peaDsgtvjRvMlW0zJAekHzs2W',
        'branch_manager',
        (SELECT id FROM branches WHERE slug = 'treviso-centro'),
        1,
        NOW()
    ),
    (
        'manager.vicenza',
        'manager.vicenza@smashburger.it',
        '$2y$10$011JTJB/JxSJdRYcDscM9Ol6poXSN0FguB1WqyqCfZlXZDlOFT3J.',
        'branch_manager',
        (SELECT id FROM branches WHERE slug = 'vicenza-centro'),
        1,
        NOW()
    ),
    (
        'manager.udine',
        'manager.udine@smashburger.it',
        '$2y$10$TmmPsWmVF0peDHSdfiZeZu7SZkgMH9T.QM9v7FYAeCwMzeb0N3Cc6',
        'branch_manager',
        (SELECT id FROM branches WHERE slug = 'udine-centro'),
        1,
        NOW()
    );

INSERT INTO categories (name, slug, sort_order)
VALUES
    ('Burger', 'burger', 1),
    ('Contorni', 'contorni', 2),
    ('Bevande', 'bevande', 3),
    ('Dessert', 'dessert', 4);

INSERT INTO products (category_id, name, slug, description, image_path, image_focus_x, image_focus_y, allergens, is_available, price_cents, created_by_user_id)
VALUES
    ((SELECT id FROM categories WHERE slug = 'burger'), 'Cheeseburger', 'cheeseburger', 'Patty di carne, Cheddar, Cetriolini, Ketchup', 'images/Cheeseburger.webp', 50, 50, 'glutine, lattosio', 1, 1090, (SELECT id FROM users WHERE username = 'admin')),
    ((SELECT id FROM categories WHERE slug = 'burger'), 'Bacon Burger', 'bacon-burger', 'Patty di carne, Bacon, Cheddar, Salsa al bacon', 'images/Bacon-Burger.webp', 50, 50, 'glutine, lattosio', 1, 1250, (SELECT id FROM users WHERE username = 'admin')),
    ((SELECT id FROM categories WHERE slug = 'burger'), 'IN-N-OUT', 'in-n-out', 'Patty di carne, cipolla cruda, Insalata, Pomodoro, Cheddar', 'images/IN-N-OUT.webp', 50, 50, 'glutine, lattosio', 1, 1150, (SELECT id FROM users WHERE username = 'admin')),
    ((SELECT id FROM categories WHERE slug = 'burger'), 'Chicken Burger', 'chicken-burger', 'Tender di pollo, Maionese, Insalata', 'images/Chicken_burger.webp', 50, 50, 'glutine, uova', 1, 1050, (SELECT id FROM users WHERE username = 'admin')),
    ((SELECT id FROM categories WHERE slug = 'burger'), 'Piccante', 'piccante', 'Patty di carne, nduja, spianata piccante, provola, cipolla caramellata', 'images/Piccante.webp', 50, 50, 'glutine, lattosio', 1, 1350, (SELECT id FROM users WHERE username = 'admin')),
    ((SELECT id FROM categories WHERE slug = 'burger'), 'Italiano', 'italiano', 'Patty di carne, Caciocavallo, Pomodori secchi, Maionese al basilico e pinoli', 'images/Italiano.webp', 50, 50, 'glutine, lattosio, frutta a guscio', 1, 1400, (SELECT id FROM users WHERE username = 'admin')),
    ((SELECT id FROM categories WHERE slug = 'burger'), 'Chicken BBQ', 'chicken-bbq', 'Sovraccoscia di pollo, Salsa BBQ e miele, Bacon, Insalata colesaw, Edamer fuso', 'images/Chicken-BBQ.webp', 50, 50, 'glutine, lattosio', 1, 1300, (SELECT id FROM users WHERE username = 'admin')),
    ((SELECT id FROM categories WHERE slug = 'burger'), 'Vegan burger', 'vegan-burger', 'Pollo vegano, Colesaw vegana, Maionese piccante vegana, Lattuga', 'images/VeganBurger.webp', 50, 50, 'glutine, soia', 1, 1250, (SELECT id FROM users WHERE username = 'admin')),
    ((SELECT id FROM categories WHERE slug = 'contorni'), 'Patate fritte', 'patate-fritte', 'Patate e sale', 'images/Patate.webp', 50, 50, 'possibili tracce di glutine', 1, 390, (SELECT id FROM users WHERE username = 'admin')),
    ((SELECT id FROM categories WHERE slug = 'contorni'), 'Chicken wings', 'chicken-wings', 'Alette di pollo speziate in salsa BBQ.', 'images/Chicken_wings.webp', 50, 50, 'senape, soia', 1, 550, (SELECT id FROM users WHERE username = 'admin')),
    ((SELECT id FROM categories WHERE slug = 'contorni'), 'Tenders di pollo', 'tenders-pollo', 'Striscioline di pollo fritte.', 'images/Tenders_di_pollo.webp', 50, 50, 'glutine', 1, 600, (SELECT id FROM users WHERE username = 'admin')),
    ((SELECT id FROM categories WHERE slug = 'contorni'), 'Panzerotti', 'panzerotti', 'Panzerotti fritti.', 'images/Panzerotti.webp', 50, 50, 'glutine, lattosio', 1, 450, (SELECT id FROM users WHERE username = 'admin')),
    ((SELECT id FROM categories WHERE slug = 'bevande'), 'Cup', 'cup', 'Bicchiere per bevande.', 'images/Cup.webp', 50, 50, NULL, 1, 250, (SELECT id FROM users WHERE username = 'admin')),
    ((SELECT id FROM categories WHERE slug = 'bevande'), 'Acqua Naturale', 'acqua-naturale', 'Bottiglia 50cl.', 'images/Acqua_Naturale.webp', 50, 50, NULL, 1, 150, (SELECT id FROM users WHERE username = 'admin')),
    ((SELECT id FROM categories WHERE slug = 'bevande'), 'Acqua Frizzante', 'acqua-frizzante', 'Bottiglia 50cl.', 'images/Acqua_Frizzante.webp', 50, 50, NULL, 1, 150, (SELECT id FROM users WHERE username = 'admin')),
    ((SELECT id FROM categories WHERE slug = 'bevande'), 'Birra Artigianale', 'birra-artigianale', 'Bottiglia 33cl.', 'images/Birra_Artigianale.webp', 50, 50, 'glutine', 1, 500, (SELECT id FROM users WHERE username = 'admin')),
    ((SELECT id FROM categories WHERE slug = 'dessert'), 'Cono gelato', 'cono-gelato', 'Cono gelato classico.', 'images/Cono_gelato.webp', 50, 50, 'glutine, lattosio', 1, 300, (SELECT id FROM users WHERE username = 'admin')),
    ((SELECT id FROM categories WHERE slug = 'dessert'), 'Milkshake Vaniglia', 'milkshake-vaniglia', 'Milkshake gusto vaniglia.', 'images/Milkshake_vaniglia.webp', 50, 50, 'lattosio', 1, 450, (SELECT id FROM users WHERE username = 'admin')),
    ((SELECT id FROM categories WHERE slug = 'dessert'), 'Milkshake Banana', 'milkshake-banana', 'Milkshake gusto banana.', 'images/Milkshake_banana.webp', 50, 50, 'lattosio', 1, 450, (SELECT id FROM users WHERE username = 'admin')),
    ((SELECT id FROM categories WHERE slug = 'dessert'), 'Milkshake Fragola', 'milkshake-fragola', 'Milkshake gusto fragola.', 'images/Milkshake_fragola.webp', 50, 50, 'lattosio', 1, 450, (SELECT id FROM users WHERE username = 'admin'));

INSERT INTO branch_products (branch_id, product_id, is_listed, is_available, price_cents_override, pickup_eta_minutes)
SELECT b.id, p.id, 1, 1, NULL, 15
FROM branches b
CROSS JOIN products p;

UPDATE branch_products bp
INNER JOIN branches b ON b.id = bp.branch_id
INNER JOIN products p ON p.id = bp.product_id
SET bp.is_available = 0
WHERE b.slug = 'treviso-centro' AND p.slug = 'birra-artigianale';

UPDATE branch_products bp
INNER JOIN branches b ON b.id = bp.branch_id
INNER JOIN products p ON p.id = bp.product_id
SET bp.is_available = 0
WHERE b.slug = 'vicenza-centro' AND p.slug = 'chicken-wings';

UPDATE branch_products bp
INNER JOIN branches b ON b.id = bp.branch_id
INNER JOIN products p ON p.id = bp.product_id
SET bp.is_available = 0
WHERE b.slug = 'udine-centro' AND p.slug = 'in-n-out';

UPDATE branch_products bp
INNER JOIN branches b ON b.id = bp.branch_id
INNER JOIN products p ON p.id = bp.product_id
SET bp.price_cents_override = 1290
WHERE b.slug = 'padova-centro' AND p.slug = 'bacon-burger';

INSERT INTO branch_inventory (branch_id, product_id, on_hand_qty, average_unit_cost_cents, manual_unavailable)
SELECT
    b.id,
    p.id,
    CASE
        WHEN c.slug = 'burger' THEN 40
        WHEN c.slug = 'contorni' THEN 32
        ELSE 54
    END AS on_hand_qty,
    CASE
        WHEN p.slug = 'cheeseburger' THEN 420
        WHEN p.slug = 'bacon-burger' THEN 520
        WHEN p.slug = 'in-n-out' THEN 440
        WHEN p.slug = 'chicken-burger' THEN 400
        WHEN p.slug = 'piccante' THEN 550
        WHEN p.slug = 'italiano' THEN 600
        WHEN p.slug = 'chicken-bbq' THEN 550
        WHEN p.slug = 'vegan-burger' THEN 500
        WHEN p.slug = 'patate-fritte' THEN 160
        WHEN p.slug = 'chicken-wings' THEN 190
        WHEN p.slug = 'tenders-pollo' THEN 250
        WHEN p.slug = 'panzerotti' THEN 180
        WHEN p.slug = 'cup' THEN 80
        WHEN p.slug = 'acqua-naturale' THEN 45
        WHEN p.slug = 'acqua-frizzante' THEN 45
        WHEN p.slug = 'cono-gelato' THEN 100
        WHEN p.slug = 'milkshake-vaniglia' THEN 150
        WHEN p.slug = 'milkshake-banana' THEN 150
        WHEN p.slug = 'milkshake-fragola' THEN 150
        ELSE 120
    END AS average_unit_cost_cents,
    0
FROM branches b
CROSS JOIN products p
INNER JOIN categories c ON c.id = p.category_id;

UPDATE branch_inventory bi
INNER JOIN branches b ON b.id = bi.branch_id
INNER JOIN products p ON p.id = bi.product_id
SET bi.on_hand_qty = 18
WHERE b.slug = 'padova-centro' AND p.slug = 'patate-fritte';

INSERT INTO auto_reorder_policies (
    branch_id,
    product_id,
    threshold_qty,
    reorder_qty,
    cooldown_hours,
    max_pending_qty,
    mode,
    is_active
)
SELECT
    b.id,
    p.id,
    CASE
        WHEN c.slug = 'burger' THEN 14
        WHEN c.slug = 'contorni' THEN 12
        ELSE 20
    END AS threshold_qty,
    CASE
        WHEN c.slug = 'burger' THEN 24
        WHEN c.slug = 'contorni' THEN 18
        ELSE 36
    END AS reorder_qty,
    6,
    CASE
        WHEN c.slug = 'burger' THEN 48
        WHEN c.slug = 'contorni' THEN 36
        ELSE 72
    END AS max_pending_qty,
    'draft',
    1
FROM branches b
CROSS JOIN products p
INNER JOIN categories c ON c.id = p.category_id;

INSERT INTO supply_templates (
    branch_id,
    template_name,
    frequency,
    next_run_at,
    is_active,
    notes,
    created_by_user_id
)
VALUES
    (
        (SELECT id FROM branches WHERE slug = 'padova-centro'),
        'Fornitura settimanale apertura',
        'weekly',
        DATE_ADD(NOW(), INTERVAL 2 DAY),
        1,
        'Template base per prodotti ad alta rotazione.',
        (SELECT id FROM users WHERE username = 'manager.padova')
    );

INSERT INTO supply_template_items (template_id, product_id, quantity, unit_cost_cents)
VALUES
    (
        (SELECT id FROM supply_templates WHERE template_name = 'Fornitura settimanale apertura' LIMIT 1),
        (SELECT id FROM products WHERE slug = 'cheeseburger'),
        24,
        420
    ),
    (
        (SELECT id FROM supply_templates WHERE template_name = 'Fornitura settimanale apertura' LIMIT 1),
        (SELECT id FROM products WHERE slug = 'patate-fritte'),
        20,
        160
    ),
    (
        (SELECT id FROM supply_templates WHERE template_name = 'Fornitura settimanale apertura' LIMIT 1),
        (SELECT id FROM products WHERE slug = 'cup'),
        36,
        80
    );
