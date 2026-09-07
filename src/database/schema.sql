--
-- Schema del database di SmashBurger.
--

-- ---------------------------------------------------------------------------
-- Struttura
-- ---------------------------------------------------------------------------

-- Account del sito. Il ruolo distingue chi ordina da chi gestisce il servizio.
-- Il codice fiscale riguarda solo manager e amministratori; l'indirizzo e il metodo di
-- pagamento restano nulli finche' un cliente non li salva dal proprio profilo.
CREATE TABLE utenti (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nome_utente VARCHAR(50) NOT NULL UNIQUE,
    nome VARCHAR(80) NOT NULL,
    cognome VARCHAR(80) NOT NULL,
    email VARCHAR(160) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    ruolo ENUM('cliente', 'manager', 'amministratore') NOT NULL DEFAULT 'cliente',
    codice_fiscale CHAR(16) NULL,
    indirizzo VARCHAR(160) NULL,
    citta VARCHAR(80) NULL,
    provincia CHAR(2) NULL,
    cap VARCHAR(10) NULL,
    paese VARCHAR(60) NULL,
    telefono VARCHAR(30) NULL,
    metodo_pagamento_preferito ENUM('carta', 'contanti') NULL,
    attivo TINYINT(1) NOT NULL DEFAULT 1,
    creato_il DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    aggiornato_il DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Punti vendita. Lo slug identifica la sede negli indirizzi e nei moduli.
-- manager_id e' unico: una sede ha al massimo un manager e un manager al massimo una
-- sede.
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
    sala_eventi_disponibile TINYINT(1) NOT NULL DEFAULT 1,
    manager_id INT UNSIGNED NULL UNIQUE,
    attiva TINYINT(1) NOT NULL DEFAULT 1,
    ordine TINYINT UNSIGNED NOT NULL DEFAULT 0,
    CONSTRAINT fk_sedi_manager FOREIGN KEY (manager_id) REFERENCES utenti (id) ON DELETE SET NULL
) ENGINE=InnoDB;

-- Fascia di apertura di una sede per ogni giorno della settimana.
-- Il giorno segue la numerazione ISO 8601: 1 e' lunedi', 7 e' domenica.
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
    descrizione VARCHAR(255) NOT NULL DEFAULT '',
    ordine TINYINT UNSIGNED NOT NULL DEFAULT 0
) ENGINE=InnoDB;

-- Prodotti a catalogo. Questi dati sono comuni a tutte le sedi e li gestisce solo
-- l'amministratore. Lo slug identifica il prodotto nella sua pagina di dettaglio.
CREATE TABLE prodotti (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    categoria_id SMALLINT UNSIGNED NOT NULL,
    nome VARCHAR(120) NOT NULL,
    slug VARCHAR(120) NOT NULL UNIQUE,
    descrizione TEXT NOT NULL,
    allergeni VARCHAR(255) NOT NULL DEFAULT '',
    immagine VARCHAR(160) NOT NULL DEFAULT '',
    prezzo_centesimi INT UNSIGNED NOT NULL,
    creato_il DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    aggiornato_il DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_prodotti_categoria FOREIGN KEY (categoria_id) REFERENCES categorie (id)
) ENGINE=InnoDB;

-- Disponibilita' di un prodotto in una sede, gestita dal manager di quella sede.
-- La disponibilita' effettiva e' disponibile = 1 AND quantita > 0: l'interruttore toglie
-- il prodotto dal menu anche con merce a magazzino, lo zero lo toglie da solo.
-- La quantita' viene scalata dagli ordini e ripristinata dagli annullamenti; il vincolo
-- di non negativita' e' la rete di sicurezza dietro l'aggiornamento condizionale.
CREATE TABLE disponibilita_prodotti (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    sede_id SMALLINT UNSIGNED NOT NULL,
    prodotto_id INT UNSIGNED NOT NULL,
    disponibile TINYINT(1) NOT NULL DEFAULT 1,
    quantita INT NOT NULL DEFAULT 0,
    UNIQUE KEY sede_prodotto (sede_id, prodotto_id),
    CONSTRAINT ck_disponibilita_quantita CHECK (quantita >= 0),
    CONSTRAINT fk_disponibilita_sede FOREIGN KEY (sede_id) REFERENCES sedi (id) ON DELETE CASCADE,
    CONSTRAINT fk_disponibilita_prodotto FOREIGN KEY (prodotto_id) REFERENCES prodotti (id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Carrello in corso. Non e' persistente: viene eliminato quando l'ordine si conclude e
-- scade da solo dopo 15 minuti di inattivita', misurati su aggiornato_il.
-- Un cliente ha al massimo un carrello aperto, legato alla sede scelta per l'ordine.
CREATE TABLE carrelli (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    utente_id INT UNSIGNED NOT NULL UNIQUE,
    sede_id SMALLINT UNSIGNED NOT NULL,
    creato_il DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    aggiornato_il DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_carrelli_utente FOREIGN KEY (utente_id) REFERENCES utenti (id) ON DELETE CASCADE,
    CONSTRAINT fk_carrelli_sede FOREIGN KEY (sede_id) REFERENCES sedi (id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Righe del carrello. Non copiano il prezzo: si legge dal prodotto, cosi' il totale
-- mostrato e' sempre quello corrente.
CREATE TABLE righe_carrello (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    carrello_id INT UNSIGNED NOT NULL,
    prodotto_id INT UNSIGNED NOT NULL,
    quantita SMALLINT UNSIGNED NOT NULL DEFAULT 1,
    UNIQUE KEY carrello_prodotto (carrello_id, prodotto_id),
    CONSTRAINT fk_righe_carrello_carrello FOREIGN KEY (carrello_id) REFERENCES carrelli (id) ON DELETE CASCADE,
    CONSTRAINT fk_righe_carrello_prodotto FOREIGN KEY (prodotto_id) REFERENCES prodotti (id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Ordini confermati. La modalita' decide quali colonne sono valorizzate: ritiro_previsto
-- per il ritiro in sede, l'indirizzo congelato per la consegna a domicilio.
-- L'indirizzo viene copiato qui al momento dell'ordine e non letto dal profilo, perche'
-- un ordine deve restare leggibile anche se il cliente cambia i propri dati.
CREATE TABLE ordini (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    utente_id INT UNSIGNED NOT NULL,
    sede_id SMALLINT UNSIGNED NOT NULL,
    numero_ordine VARCHAR(20) NOT NULL UNIQUE,
    modalita ENUM('ritiro', 'domicilio') NOT NULL,
    ritiro_previsto DATETIME NULL,
    consegna_indirizzo VARCHAR(160) NULL,
    consegna_citta VARCHAR(80) NULL,
    consegna_provincia CHAR(2) NULL,
    consegna_cap VARCHAR(10) NULL,
    consegna_paese VARCHAR(60) NULL,
    consegna_telefono VARCHAR(30) NULL,
    stato ENUM('ricevuto', 'in preparazione', 'pronto', 'concluso', 'annullato') NOT NULL DEFAULT 'ricevuto',
    metodo_pagamento ENUM('carta', 'contanti') NOT NULL,
    stato_pagamento ENUM('in attesa', 'pagato', 'rimborsato') NOT NULL DEFAULT 'in attesa',
    motivo_annullamento VARCHAR(255) NULL,
    totale_centesimi INT UNSIGNED NOT NULL,
    creato_il DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    aggiornato_il DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    KEY sede_creato (sede_id, creato_il),
    CONSTRAINT fk_ordini_utente FOREIGN KEY (utente_id) REFERENCES utenti (id) ON DELETE CASCADE,
    CONSTRAINT fk_ordini_sede FOREIGN KEY (sede_id) REFERENCES sedi (id)
) ENGINE=InnoDB;

-- Righe di un ordine. Congelano nome e prezzo del prodotto al momento dell'acquisto,
-- cosi' la ricevuta resta leggibile anche se il prodotto cambia o viene cancellato.
CREATE TABLE righe_ordine (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    ordine_id INT UNSIGNED NOT NULL,
    prodotto_id INT UNSIGNED NULL,
    nome_prodotto VARCHAR(120) NOT NULL,
    quantita SMALLINT UNSIGNED NOT NULL,
    prezzo_centesimi INT UNSIGNED NOT NULL,
    CONSTRAINT fk_righe_ordine_ordine FOREIGN KEY (ordine_id) REFERENCES ordini (id) ON DELETE CASCADE,
    CONSTRAINT fk_righe_ordine_prodotto FOREIGN KEY (prodotto_id) REFERENCES prodotti (id) ON DELETE SET NULL
) ENGINE=InnoDB;

-- Prenotazioni della sala eventi. Ogni sede ha una sola sala, quindi la prenotazione
-- punta direttamente alla sede. La fascia dura tre ore.
-- La non sovrapposizione si verifica nel codice e non con un vincolo di unicita': due
-- prenotazioni rifiutate o annullate possono legittimamente avere la stessa fascia.
CREATE TABLE prenotazioni (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    sede_id SMALLINT UNSIGNED NOT NULL,
    utente_id INT UNSIGNED NOT NULL,
    data DATE NOT NULL,
    ora_inizio TIME NOT NULL,
    ora_fine TIME NOT NULL,
    numero_persone SMALLINT UNSIGNED NOT NULL,
    stato ENUM('in attesa', 'approvata', 'rifiutata', 'annullata') NOT NULL DEFAULT 'in attesa',
    note VARCHAR(120) NULL,
    creato_il DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    aggiornato_il DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    KEY sede_data (sede_id, data),
    CONSTRAINT fk_prenotazioni_sede FOREIGN KEY (sede_id) REFERENCES sedi (id) ON DELETE CASCADE,
    CONSTRAINT fk_prenotazioni_utente FOREIGN KEY (utente_id) REFERENCES utenti (id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Messaggi inviati dal modulo di contatto. Il sito gestisce solo il primo messaggio di
-- ogni richiesta: le risposte successive avvengono via email.
CREATE TABLE messaggi_contatto (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(120) NOT NULL,
    email VARCHAR(160) NOT NULL,
    categoria ENUM('ordine', 'prenotazione', 'segnalazione', 'altro') NOT NULL DEFAULT 'altro',
    testo VARCHAR(400) NOT NULL,
    stato ENUM('nuovo', 'chiuso') NOT NULL DEFAULT 'nuovo',
    creato_il DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    aggiornato_il DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------------
-- Dati di esempio
-- ---------------------------------------------------------------------------

-- Le tre utenze di prova richieste dai vincoli d'esame hanno login e password identici.
-- Gli altri account servono a popolare ordini, prenotazioni e pannello.
INSERT INTO utenti (nome_utente, nome, cognome, email, password_hash, ruolo, codice_fiscale, indirizzo, citta, provincia, cap, paese, telefono, metodo_pagamento_preferito) VALUES
('admin', 'Giulia', 'Ferrari', 'amministrazione@smashburger.it', '$2y$10$g0Ncx.jxAj8rWqA741OYHO3rLUFebPR5zLsZpJPMYM6EcXqcP8yya', 'amministratore', 'FRRGLI85M41G224X', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('manager', 'Marco', 'Bianchi', 'padova@smashburger.it', '$2y$10$IuJdusY2a6WHDg5nF6zaRuuSBNxJVL0UcyB/fL7fmp5P903df7LJm', 'manager', 'BNCMRC88T10G224K', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('user', 'Anna', 'Rossi', 'anna.rossi@example.it', '$2y$10$vLWER.nE/u.PI.NWL9UGWunGoaqEuU0NHivFnytPylZIA/nhKsRCC', 'cliente', NULL, 'Via Roma 12', 'Padova', 'PD', '35100', 'Italia', '3401234567', 'carta'),
('manager.treviso', 'Sara', 'Conti', 'treviso@smashburger.it', '$2y$10$IuJdusY2a6WHDg5nF6zaRuuSBNxJVL0UcyB/fL7fmp5P903df7LJm', 'manager', 'CNTSRA90A55L407J', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('manager.vicenza', 'Luca', 'Greco', 'vicenza@smashburger.it', '$2y$10$IuJdusY2a6WHDg5nF6zaRuuSBNxJVL0UcyB/fL7fmp5P903df7LJm', 'manager', 'GRCLCU87E20L840P', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('manager.udine', 'Elena', 'Vitale', 'udine@smashburger.it', '$2y$10$IuJdusY2a6WHDg5nF6zaRuuSBNxJVL0UcyB/fL7fmp5P903df7LJm', 'manager', 'VTLLNE91D62L483R', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('paolo.neri', 'Paolo', 'Neri', 'paolo.neri@example.it', '$2y$10$vLWER.nE/u.PI.NWL9UGWunGoaqEuU0NHivFnytPylZIA/nhKsRCC', 'cliente', NULL, 'Viale della Repubblica 8', 'Treviso', 'TV', '31100', 'Italia', '3399876543', 'contanti'),
('chiara.moretti', 'Chiara', 'Moretti', 'chiara.moretti@example.it', '$2y$10$vLWER.nE/u.PI.NWL9UGWunGoaqEuU0NHivFnytPylZIA/nhKsRCC', 'cliente', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);

INSERT INTO sedi (slug, nome, citta, provincia, indirizzo, cap, telefono, email, note_ritiro, sala_eventi_disponibile, manager_id, ordine) VALUES
('padova', 'Smash Burger Padova', 'Padova', 'PD', 'Via San Fermo 34', '35137', '049 1234567', 'padova@smashburger.it', 'Il ritiro avviene al banco a destra dell ingresso.', 1, 2, 1),
('treviso', 'Smash Burger Treviso', 'Treviso', 'TV', 'Via Calmaggiore 18', '31100', '0422 234567', 'treviso@smashburger.it', 'Il ritiro avviene alla cassa centrale.', 1, 4, 2),
('vicenza', 'Smash Burger Vicenza', 'Vicenza', 'VI', 'Corso Palladio 92', '36100', '0444 345678', 'vicenza@smashburger.it', 'Il ritiro avviene al bancone accanto alle vetrine.', 1, 5, 3),
('udine', 'Smash Burger Udine', 'Udine', 'UD', 'Via Mercatovecchio 7', '33100', '0432 456789', 'udine@smashburger.it', 'Il ritiro avviene al banco vicino alla scala.', 0, 6, 4);

-- Tutte le sedi aprono ogni giorno dalle 11:30 alle 22:30.
INSERT INTO orari_sedi (sede_id, giorno, apertura, chiusura, chiuso)
SELECT s.id, g.giorno, '11:30:00', '22:30:00', 0
FROM sedi s
CROSS JOIN (SELECT 1 AS giorno UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7) g;

INSERT INTO categorie (nome, slug, descrizione, ordine) VALUES
('Burger', 'burger', 'Hamburger schiacciati sulla piastra e serviti al momento.', 1),
('Contorni', 'contorni', 'Fritti e sfizi da accompagnare al burger.', 2),
('Bevande', 'bevande', 'Bibite, acqua e birra artigianale.', 3),
('Dessert', 'dessert', 'Gelato e milkshake preparati al momento.', 4);

INSERT INTO prodotti (categoria_id, nome, slug, descrizione, allergeni, immagine, prezzo_centesimi) VALUES
(1, 'Cheeseburger', 'cheeseburger', 'Il classico: manzo schiacciato sulla piastra, cheddar fuso, cetriolini sottaceto e salsa della casa.', 'glutine, latte, uova, senape', 'cheeseburger.webp', 890),
(1, 'Bacon Burger', 'bacon-burger', 'Doppio manzo, bacon croccante, cheddar e salsa affumicata.', 'glutine, latte, uova, senape', 'bacon-burger.webp', 1090),
(1, 'In-N-Out', 'in-n-out', 'Due dischi di manzo, cheddar, cipolla fresca, pomodoro, lattuga e salsa rosa.', 'glutine, latte, uova', 'in-n-out.webp', 1050),
(1, 'Italiano', 'italiano', 'Manzo, provola affumicata, pomodori secchi, rucola, olive nere e pesto ai pinoli.', 'glutine, latte, frutta a guscio', 'italiano.webp', 1150),
(1, 'Piccante', 'piccante', 'Manzo, salame piccante, cipolle caramellate, formaggio fuso e crema piccante.', 'glutine, latte, uova', 'piccante.webp', 1090),
(1, 'Chicken Burger', 'chicken-burger', 'Petto di pollo impanato, insalata, pomodoro e maionese.', 'glutine, uova, latte', 'chicken-burger.webp', 990),
(1, 'Chicken BBQ', 'chicken-bbq', 'Pollo fritto croccante, salsa barbecue, bacon, cheddar e coleslaw.', 'glutine, latte, uova, soia', 'chicken-bbq.webp', 1050),
(1, 'Vegan Burger', 'vegan-burger', 'Burger vegetale croccante, coleslaw di cavolo cappuccio, insalata e salsa vegana.', 'glutine, soia', 'vegan-burger.webp', 1090),
(2, 'Patate fritte', 'patate', 'Patate a bastoncino fritte al momento e salate a mano.', '', 'patate.webp', 450),
(2, 'Chicken Wings', 'chicken-wings', 'Sei alette di pollo marinate e cotte al forno.', 'sedano', 'chicken-wings.webp', 750),
(2, 'Tenders di pollo', 'tenders-di-pollo', 'Strisce di pollo impanate, servite con salsa a scelta.', 'glutine, uova', 'tenders-di-pollo.webp', 690),
(2, 'Panzerotti', 'panzerotti', 'Due panzerotti fritti ripieni di pomodoro e mozzarella.', 'glutine, latte', 'panzerotti.webp', 620),
(3, 'Acqua naturale', 'acqua-naturale', 'Bottiglia da mezzo litro di acqua naturale.', '', 'acqua-naturale.webp', 150),
(3, 'Acqua frizzante', 'acqua-frizzante', 'Bottiglia da mezzo litro di acqua frizzante.', '', 'acqua-frizzante.webp', 150),
(3, 'Bibita alla spina', 'bibita-alla-spina', 'Bicchiere da mezzo litro di bibita a scelta fra quelle del giorno.', '', 'cup.webp', 300),
(3, 'Birra artigianale', 'birra-artigianale', 'Bottiglia da 0,5 l di birra artigianale India Pale Ale.', 'glutine', 'birra-artigianale.webp', 550),
(4, 'Cono gelato', 'cono-gelato', 'Cialda croccante con gelato soft al fiordilatte.', 'glutine, latte, uova', 'cono-gelato.webp', 350),
(4, 'Milkshake alla vaniglia', 'milkshake-vaniglia', 'Milkshake denso preparato con gelato alla vaniglia.', 'latte', 'milkshake-vaniglia.webp', 480),
(4, 'Milkshake alla banana', 'milkshake-banana', 'Milkshake denso preparato con gelato e banana fresca.', 'latte', 'milkshake-banana.webp', 480);

-- Ogni sede parte con tutti i prodotti disponibili. Le righe che seguono correggono i
-- casi particolari usati per provare il comportamento del menu e del pannello.
INSERT INTO disponibilita_prodotti (sede_id, prodotto_id, disponibile, quantita)
SELECT s.id, p.id, 1, 40 FROM sedi s CROSS JOIN prodotti p;

-- Vicenza ha esaurito il Vegan Burger: la quantita' a zero lo toglie dal menu da sola.
UPDATE disponibilita_prodotti SET quantita = 0
WHERE sede_id = (SELECT id FROM sedi WHERE slug = 'vicenza')
  AND prodotto_id = (SELECT id FROM prodotti WHERE slug = 'vegan-burger');

-- Udine ha la piastra dei fritti guasta: i prodotti restano a magazzino ma fuori menu.
UPDATE disponibilita_prodotti SET disponibile = 0
WHERE sede_id = (SELECT id FROM sedi WHERE slug = 'udine')
  AND prodotto_id IN (SELECT id FROM prodotti WHERE slug IN ('patate', 'panzerotti'));

-- Padova ha pochi pezzi di Bacon Burger, utile per provare il limite di quantita'.
UPDATE disponibilita_prodotti SET quantita = 3
WHERE sede_id = (SELECT id FROM sedi WHERE slug = 'padova')
  AND prodotto_id = (SELECT id FROM prodotti WHERE slug = 'bacon-burger');

INSERT INTO ordini (utente_id, sede_id, numero_ordine, modalita, ritiro_previsto, consegna_indirizzo, consegna_citta, consegna_provincia, consegna_cap, consegna_paese, consegna_telefono, stato, metodo_pagamento, stato_pagamento, motivo_annullamento, totale_centesimi, creato_il) VALUES
(3, 1, 'SB-2026-0001', 'ritiro', '2026-08-20 12:30:00', NULL, NULL, NULL, NULL, NULL, NULL, 'concluso', 'carta', 'pagato', NULL, 1790, '2026-08-20 11:52:00'),
(3, 1, 'SB-2026-0002', 'domicilio', NULL, 'Via Roma 12', 'Padova', 'PD', '35100', 'Italia', '3401234567', 'concluso', 'carta', 'pagato', NULL, 2130, '2026-08-24 19:41:00'),
(7, 2, 'SB-2026-0003', 'ritiro', '2026-08-26 20:00:00', NULL, NULL, NULL, NULL, NULL, NULL, 'concluso', 'contanti', 'pagato', NULL, 1540, '2026-08-26 19:15:00'),
(3, 1, 'SB-2026-0004', 'domicilio', NULL, 'Via Roma 12', 'Padova', 'PD', '35100', 'Italia', '3401234567', 'annullato', 'carta', 'rimborsato', 'Indirizzo non raggiungibile dalla societa di consegna.', 1240, '2026-08-28 20:05:00'),
(8, 1, 'SB-2026-0005', 'ritiro', '2026-08-30 13:00:00', NULL, NULL, NULL, NULL, NULL, NULL, 'in preparazione', 'contanti', 'in attesa', NULL, 1340, '2026-08-30 12:22:00');

INSERT INTO righe_ordine (ordine_id, prodotto_id, nome_prodotto, quantita, prezzo_centesimi) VALUES
(1, 1, 'Cheeseburger', 1, 890),
(1, 9, 'Patate fritte', 1, 450),
(1, 13, 'Acqua naturale', 1, 150),
(1, 17, 'Cono gelato', 1, 350),
(2, 2, 'Bacon Burger', 1, 1090),
(2, 11, 'Tenders di pollo', 1, 690),
(2, 15, 'Bibita alla spina', 1, 300),
(3, 4, 'Italiano', 1, 1150),
(3, 9, 'Patate fritte', 1, 450),
(4, 6, 'Chicken Burger', 1, 990),
(4, 13, 'Acqua naturale', 1, 150),
(5, 8, 'Vegan Burger', 1, 1090),
(5, 13, 'Acqua naturale', 1, 150);

INSERT INTO prenotazioni (sede_id, utente_id, data, ora_inizio, ora_fine, numero_persone, stato, note, creato_il) VALUES
(1, 3, '2026-09-12', '19:00:00', '22:00:00', 18, 'approvata', 'Festa di laurea, serve un tavolo lungo.', '2026-08-22 10:14:00'),
(2, 7, '2026-09-19', '12:00:00', '15:00:00', 10, 'in attesa', NULL, '2026-08-29 09:02:00'),
(1, 8, '2026-09-05', '16:00:00', '19:00:00', 25, 'rifiutata', 'Sala gia impegnata per manutenzione.', '2026-08-18 17:30:00');

INSERT INTO messaggi_contatto (nome, email, categoria, testo, stato, creato_il) VALUES
('Paolo Neri', 'paolo.neri@example.it', 'ordine', 'Ho ritirato l ordine SB-2026-0003 ma mancavano le patate. Come posso fare?', 'nuovo', '2026-08-27 09:30:00'),
('Chiara Moretti', 'chiara.moretti@example.it', 'prenotazione', 'Vorrei sapere se la sala eventi di Udine tornera prenotabile a settembre.', 'nuovo', '2026-08-29 18:12:00'),
('Davide Longo', 'davide.longo@example.it', 'segnalazione', 'Sul telefono la pagina delle sedi mi sembra difficile da leggere in orizzontale.', 'chiuso', '2026-08-15 21:44:00');
