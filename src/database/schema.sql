--
-- SmashBurger Database Schema v3 (multi-sede)
-- Allineato alla configurazione locale in .env (DB_NAME=esame_web).
--

CREATE DATABASE IF NOT EXISTS esame_web
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE esame_web;

SET FOREIGN_KEY_CHECKS = 0;
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
    role ENUM('admin', 'user') NOT NULL DEFAULT 'user',
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
    allergens VARCHAR(255) NULL,
    is_available TINYINT(1) NOT NULL DEFAULT 1,
    price_cents INT UNSIGNED NOT NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_products_category
        FOREIGN KEY (category_id) REFERENCES categories(id)
        ON UPDATE CASCADE ON DELETE RESTRICT,
    CONSTRAINT chk_products_price CHECK (price_cents >= 0),
    INDEX idx_products_category (category_id),
    INDEX idx_products_available (is_available)
) ENGINE=InnoDB;

CREATE TABLE branch_products (
    branch_id SMALLINT UNSIGNED NOT NULL,
    product_id INT UNSIGNED NOT NULL,
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

INSERT INTO users (username, email, password_hash, role, email_verified_at)
VALUES
    ('admin', 'admin@smashburger.it', '$2y$10$TuGSbILQNQipwO6uiHAz0uxgmt.21iLrD/Wmv9DNXbpEwjkIuf2LS', 'admin', NOW()),
    ('user', 'user@smashburger.it', '$2y$10$/vM6p.rHEgV2uspgXG5uHegXCxoWmthYDWfXRNuPAhwf5VjgC5nQa', 'user', NOW());

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

INSERT INTO categories (name, slug, sort_order)
VALUES
    ('Burger', 'burger', 1),
    ('Contorni', 'contorni', 2),
    ('Bevande', 'bevande', 3);

INSERT INTO products (category_id, name, slug, description, image_path, allergens, is_available, price_cents)
VALUES
    ((SELECT id FROM categories WHERE slug = 'burger'), 'Classic Smash', 'classic-smash', 'Doppio smash, cheddar, cipolla, salsa signature.', NULL, 'glutine, lattosio', 1, 1090),
    ((SELECT id FROM categories WHERE slug = 'burger'), 'Bacon Smash', 'bacon-smash', 'Doppio smash con bacon croccante e cheddar.', NULL, 'glutine, lattosio', 1, 1250),
    ((SELECT id FROM categories WHERE slug = 'burger'), 'Veggie Smash', 'veggie-smash', 'Burger vegetale con salsa yogurt e insalata.', NULL, 'glutine, lattosio, soia', 1, 1150),
    ((SELECT id FROM categories WHERE slug = 'contorni'), 'Patatine Classiche', 'patatine-classiche', 'Porzione media di patatine fritte.', NULL, 'possibili tracce di glutine', 1, 390),
    ((SELECT id FROM categories WHERE slug = 'contorni'), 'Onion Rings', 'onion-rings', 'Anelli di cipolla croccanti con salsa BBQ.', NULL, 'glutine', 1, 450),
    ((SELECT id FROM categories WHERE slug = 'contorni'), 'Nuggets di Pollo', 'nuggets-pollo', '6 nuggets con salsa a scelta.', NULL, 'glutine, uova', 1, 590),
    ((SELECT id FROM categories WHERE slug = 'bevande'), 'Cola', 'cola', 'Lattina 33cl.', NULL, NULL, 1, 250),
    ((SELECT id FROM categories WHERE slug = 'bevande'), 'Acqua Naturale', 'acqua-naturale', 'Bottiglia 50cl.', NULL, NULL, 1, 150),
    ((SELECT id FROM categories WHERE slug = 'bevande'), 'Birra Artigianale', 'birra-artigianale', 'Bottiglia 33cl.', NULL, 'glutine', 1, 500);

INSERT INTO branch_products (branch_id, product_id, is_available, price_cents_override, pickup_eta_minutes)
SELECT b.id, p.id, 1, NULL, 15
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
WHERE b.slug = 'vicenza-centro' AND p.slug = 'onion-rings';

UPDATE branch_products bp
INNER JOIN branches b ON b.id = bp.branch_id
INNER JOIN products p ON p.id = bp.product_id
SET bp.is_available = 0
WHERE b.slug = 'udine-centro' AND p.slug = 'veggie-smash';

UPDATE branch_products bp
INNER JOIN branches b ON b.id = bp.branch_id
INNER JOIN products p ON p.id = bp.product_id
SET bp.price_cents_override = 1290
WHERE b.slug = 'padova-centro' AND p.slug = 'bacon-smash';
