-- 
-- SmashBurger Database Schema
-- File per la creazione e popolamento iniziale del database.
-- 

CREATE DATABASE IF NOT EXISTS progetto_db;
USE progetto_db;

-- Esempio tabella utenti (richiesti admin/user come da specifiche)
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'user') DEFAULT 'user'
);

-- Inserimento utenti di test predefiniti (password in chiaro per test, in produzione andrebbero hashate)
INSERT INTO users (username, password, role) 
VALUES ('admin', 'admin', 'admin'), ('user', 'user', 'user')
ON DUPLICATE KEY UPDATE username=username;
