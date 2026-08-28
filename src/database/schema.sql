--
-- Schema del database di SmashBurger.
--
-- Lo script non crea il database e non esegue USE: viene eseguito dentro il database
-- già selezionato, sia da docker-entrypoint-initdb.d in sviluppo sia dall'importazione
-- manuale sul server di consegna, dove il nome del database è imposto dall'ateneo.
--
-- Lo script si applica a un database vuoto e non elimina nulla: in sviluppo il volume
-- viene ricreato, sul server di consegna il database viene svuotato prima dell'import.
--
-- Compatibile con MariaDB 10.6. Tutti gli importi sono interi in centesimi di euro.
--

-- Account del sito. Il ruolo distingue chi ordina da chi gestisce il servizio.
CREATE TABLE utenti (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nome_utente VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(160) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    ruolo ENUM('cliente', 'amministratore') NOT NULL DEFAULT 'cliente',
    attivo TINYINT(1) NOT NULL DEFAULT 1,
    creato_il DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    aggiornato_il DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Punti vendita. Lo slug identifica la sede negli indirizzi e nei moduli.
CREATE TABLE sedi (
    id SMALLINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    slug VARCHAR(60) NOT NULL UNIQUE,
    nome VARCHAR(120) NOT NULL,
    citta VARCHAR(80) NOT NULL,
    provincia CHAR(2) NOT NULL,
    indirizzo VARCHAR(160) NOT NULL,
    cap CHAR(5) NOT NULL,
    telefono VARCHAR(30) NOT NULL,
    email VARCHAR(160) NOT NULL,
    note_ritiro VARCHAR(255) NULL,
    attiva TINYINT(1) NOT NULL DEFAULT 1,
    ordine TINYINT UNSIGNED NOT NULL DEFAULT 0
) ENGINE=InnoDB;

-- Fascia di apertura di una sede per ogni giorno della settimana.
-- Il giorno segue la numerazione ISO 8601: 1 è lunedì, 7 è domenica.
-- Se chiuso vale 1 le due ore restano nulle.
CREATE TABLE orari_sedi (
    id SMALLINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    sede_id SMALLINT UNSIGNED NOT NULL,
    giorno TINYINT UNSIGNED NOT NULL,
    apertura TIME NULL,
    chiusura TIME NULL,
    chiuso TINYINT(1) NOT NULL DEFAULT 0,
    UNIQUE KEY sede_giorno (sede_id, giorno),
    CONSTRAINT fk_orari_sede FOREIGN KEY (sede_id) REFERENCES sedi (id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Categorie del catalogo. La colonna ordine decide la sequenza mostrata nel menu.
CREATE TABLE categorie (
    id SMALLINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(80) NOT NULL,
    slug VARCHAR(80) NOT NULL UNIQUE,
    ordine TINYINT UNSIGNED NOT NULL DEFAULT 0
) ENGINE=InnoDB;

-- Prodotti a catalogo. Il catalogo è unico per tutte le sedi.
-- La colonna disponibile toglie il prodotto dalla vendita senza cancellarlo.
CREATE TABLE prodotti (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    categoria_id SMALLINT UNSIGNED NOT NULL,
    nome VARCHAR(120) NOT NULL,
    slug VARCHAR(120) NOT NULL UNIQUE,
    descrizione VARCHAR(255) NOT NULL,
    allergeni VARCHAR(160) NULL,
    immagine VARCHAR(120) NULL,
    prezzo_centesimi INT UNSIGNED NOT NULL,
    disponibile TINYINT(1) NOT NULL DEFAULT 1,
    creato_il DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    aggiornato_il DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    KEY idx_prodotti_categoria (categoria_id),
    CONSTRAINT fk_prodotti_categoria FOREIGN KEY (categoria_id) REFERENCES categorie (id)
) ENGINE=InnoDB;

-- Carrello attivo: uno solo per utente, viene svuotato quando l'ordine è confermato.
-- La sede resta nulla finché non viene scelta.
CREATE TABLE carrelli (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    utente_id INT UNSIGNED NOT NULL UNIQUE,
    sede_id SMALLINT UNSIGNED NULL,
    aggiornato_il DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_carrelli_utente FOREIGN KEY (utente_id) REFERENCES utenti (id) ON DELETE CASCADE,
    CONSTRAINT fk_carrelli_sede FOREIGN KEY (sede_id) REFERENCES sedi (id) ON DELETE SET NULL
) ENGINE=InnoDB;

-- Righe del carrello. Il prezzo non viene copiato qui: si legge dal prodotto,
-- così il totale mostrato è sempre quello corrente.
CREATE TABLE righe_carrello (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    carrello_id INT UNSIGNED NOT NULL,
    prodotto_id INT UNSIGNED NOT NULL,
    quantita SMALLINT UNSIGNED NOT NULL DEFAULT 1,
    UNIQUE KEY carrello_prodotto (carrello_id, prodotto_id),
    CONSTRAINT fk_righe_carrello_carrello FOREIGN KEY (carrello_id) REFERENCES carrelli (id) ON DELETE CASCADE,
    CONSTRAINT fk_righe_carrello_prodotto FOREIGN KEY (prodotto_id) REFERENCES prodotti (id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Ordini confermati. Il numero e' il riferimento mostrato al cliente e al banco.
CREATE TABLE ordini (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    utente_id INT UNSIGNED NOT NULL,
    sede_id SMALLINT UNSIGNED NOT NULL,
    numero VARCHAR(20) NOT NULL UNIQUE,
    ritiro_previsto DATETIME NOT NULL,
    stato ENUM('ricevuto', 'in preparazione', 'pronto', 'ritirato', 'annullato') NOT NULL DEFAULT 'ricevuto',
    metodo_pagamento ENUM('carta', 'contanti') NOT NULL,
    stato_pagamento ENUM('da pagare', 'pagato') NOT NULL DEFAULT 'da pagare',
    totale_centesimi INT UNSIGNED NOT NULL,
    creato_il DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    aggiornato_il DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    KEY idx_ordini_utente (utente_id),
    KEY idx_ordini_sede_stato (sede_id, stato),
    CONSTRAINT fk_ordini_utente FOREIGN KEY (utente_id) REFERENCES utenti (id) ON DELETE CASCADE,
    CONSTRAINT fk_ordini_sede FOREIGN KEY (sede_id) REFERENCES sedi (id)
) ENGINE=InnoDB;

-- Righe dell'ordine. Nome e prezzo sono copiati al momento della conferma, così
-- l'ordine resta leggibile anche se il prodotto viene modificato o cancellato.
CREATE TABLE righe_ordine (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    ordine_id INT UNSIGNED NOT NULL,
    prodotto_id INT UNSIGNED NULL,
    nome_prodotto VARCHAR(120) NOT NULL,
    quantita SMALLINT UNSIGNED NOT NULL,
    prezzo_centesimi INT UNSIGNED NOT NULL,
    KEY idx_righe_ordine_ordine (ordine_id),
    CONSTRAINT fk_righe_ordine_ordine FOREIGN KEY (ordine_id) REFERENCES ordini (id) ON DELETE CASCADE,
    CONSTRAINT fk_righe_ordine_prodotto FOREIGN KEY (prodotto_id) REFERENCES prodotti (id) ON DELETE SET NULL
) ENGINE=InnoDB;

--
-- Dati di esempio.
-- Le due utenze richieste per la correzione hanno nome utente e password uguali.
--

INSERT INTO utenti (nome_utente, email, password_hash, ruolo) VALUES
    ('admin', 'admin@smashburger.it', '$2y$12$v1Lj1Q4.k9ekYcR1xKwcl.axsoz2HuvpN2.LgR9bCNkxyH08WOnmu', 'amministratore'),
    ('user', 'user@smashburger.it', '$2y$12$MtBkmlTz0i1pXAlYwdR1QuNqNS5p6Kt.aAN3y.za3m6hVaROr92LO', 'cliente');

INSERT INTO sedi (slug, nome, citta, provincia, indirizzo, cap, telefono, email, note_ritiro, ordine) VALUES
    ('padova', 'Smash Burger Padova', 'Padova', 'PD', 'Via Roma 42', '35122', '049 111 2201', 'padova@smashburger.it', 'Siamo in zona a traffico limitato: il parcheggio più vicino è quello di Prato della Valle, dieci minuti a piedi.', 1),
    ('treviso', 'Smash Burger Treviso', 'Treviso', 'TV', 'Piazza dei Signori 8', '31100', '0422 321 780', 'treviso@smashburger.it', 'Ingresso senza gradini sulla piazza, il banco del ritiro è subito a destra.', 2),
    ('vicenza', 'Smash Burger Vicenza', 'Vicenza', 'VI', 'Corso Palladio 64', '36100', '0444 210 995', 'vicenza@smashburger.it', 'Corso pedonale, si arriva a piedi o in bici: rastrelliera davanti al locale.', 3),
    ('udine', 'Smash Burger Udine', 'Udine', 'UD', 'Via Mercatovecchio 21', '33100', '0432 442 118', 'udine@smashburger.it', 'Sotto i portici, con un ingresso dedicato solo ai ritiri.', 4);

-- Tutte le sedi aprono ogni giorno con lo stesso orario.
INSERT INTO orari_sedi (sede_id, giorno, apertura, chiusura, chiuso)
SELECT s.id, g.giorno, '11:30:00', '22:30:00', 0
FROM sedi s
CROSS JOIN (
    SELECT 1 AS giorno UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4
    UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7
) g;

INSERT INTO categorie (nome, slug, ordine) VALUES
    ('Burger', 'burger', 1),
    ('Contorni', 'contorni', 2),
    ('Bevande', 'bevande', 3),
    ('Dessert', 'dessert', 4);

INSERT INTO prodotti (categoria_id, nome, slug, descrizione, allergeni, immagine, prezzo_centesimi) VALUES
    ((SELECT id FROM categorie WHERE slug = 'burger'), 'Cheeseburger', 'cheeseburger', 'Patty di carne, cheddar, cetriolini, ketchup.', 'glutine, lattosio', 'cheeseburger.webp', 1090),
    ((SELECT id FROM categorie WHERE slug = 'burger'), 'Bacon Burger', 'bacon-burger', 'Patty di carne, bacon, cheddar, salsa al bacon.', 'glutine, lattosio', 'bacon-burger.webp', 1250),
    ((SELECT id FROM categorie WHERE slug = 'burger'), 'In-n-out', 'in-n-out', 'Patty di carne, cipolla cruda, insalata, pomodoro, cheddar.', 'glutine, lattosio', 'in-n-out.webp', 1150),
    ((SELECT id FROM categorie WHERE slug = 'burger'), 'Chicken Burger', 'chicken-burger', 'Tender di pollo, maionese, insalata.', 'glutine, uova', 'chicken-burger.webp', 1050),
    ((SELECT id FROM categorie WHERE slug = 'burger'), 'Piccante', 'piccante', 'Patty di carne, nduja, spianata piccante, provola, cipolla caramellata.', 'glutine, lattosio', 'piccante.webp', 1350),
    ((SELECT id FROM categorie WHERE slug = 'burger'), 'Italiano', 'italiano', 'Patty di carne, caciocavallo, pomodori secchi, maionese al basilico e pinoli.', 'glutine, lattosio, frutta a guscio', 'italiano.webp', 1400),
    ((SELECT id FROM categorie WHERE slug = 'burger'), 'Chicken BBQ', 'chicken-bbq', 'Sovracoscia di pollo, salsa barbecue e miele, bacon, insalata coleslaw, edamer fuso.', 'glutine, lattosio', 'chicken-bbq.webp', 1300),
    ((SELECT id FROM categorie WHERE slug = 'burger'), 'Vegan Burger', 'vegan-burger', 'Pollo vegetale, coleslaw vegana, maionese piccante vegana, lattuga.', 'glutine, soia', 'vegan-burger.webp', 1250),
    ((SELECT id FROM categorie WHERE slug = 'contorni'), 'Patate fritte', 'patate-fritte', 'Patate fritte con sale.', 'possibili tracce di glutine', 'patate.webp', 390),
    ((SELECT id FROM categorie WHERE slug = 'contorni'), 'Chicken wings', 'chicken-wings', 'Alette di pollo speziate con salsa barbecue.', 'senape, soia', 'chicken-wings.webp', 550),
    ((SELECT id FROM categorie WHERE slug = 'contorni'), 'Tenders di pollo', 'tenders-di-pollo', 'Striscioline di pollo fritte.', 'glutine', 'tenders-di-pollo.webp', 600),
    ((SELECT id FROM categorie WHERE slug = 'contorni'), 'Panzerotti', 'panzerotti', 'Panzerotti fritti ripieni di pomodoro e mozzarella.', 'glutine, lattosio', 'panzerotti.webp', 450),
    ((SELECT id FROM categorie WHERE slug = 'bevande'), 'Bibita alla spina', 'bibita-alla-spina', 'Bicchiere da 40 cl, gusto a scelta al banco.', NULL, 'cup.webp', 250),
    ((SELECT id FROM categorie WHERE slug = 'bevande'), 'Acqua naturale', 'acqua-naturale', 'Bottiglia da 50 cl.', NULL, 'acqua-naturale.webp', 150),
    ((SELECT id FROM categorie WHERE slug = 'bevande'), 'Acqua frizzante', 'acqua-frizzante', 'Bottiglia da 50 cl.', NULL, 'acqua-frizzante.webp', 150),
    ((SELECT id FROM categorie WHERE slug = 'bevande'), 'Birra artigianale', 'birra-artigianale', 'Bottiglia da 33 cl.', 'glutine', 'birra-artigianale.webp', 500),
    ((SELECT id FROM categorie WHERE slug = 'dessert'), 'Cono gelato', 'cono-gelato', 'Cono gelato classico.', 'glutine, lattosio', 'cono-gelato.webp', 300),
    ((SELECT id FROM categorie WHERE slug = 'dessert'), 'Milkshake alla vaniglia', 'milkshake-vaniglia', 'Milkshake al gusto di vaniglia.', 'lattosio', 'milkshake-vaniglia.webp', 450),
    ((SELECT id FROM categorie WHERE slug = 'dessert'), 'Milkshake alla banana', 'milkshake-banana', 'Milkshake al gusto di banana.', 'lattosio', 'milkshake-banana.webp', 450),
    ((SELECT id FROM categorie WHERE slug = 'dessert'), 'Milkshake alla fragola', 'milkshake-fragola', 'Milkshake al gusto di fragola.', 'lattosio', 'milkshake-fragola.webp', 450);
