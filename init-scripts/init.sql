DROP DATABASE IF EXISTS scaregna;
DROP TABLE IF EXISTS DISPONIBILITA;
DROP TABLE IF EXISTS IMMAGINE;
DROP TABLE IF EXISTS PRENOTAZIONE;
DROP TABLE IF EXISTS SPAZIO;
DROP TABLE IF EXISTS UTENTE;
CREATE DATABASE scaregna;
USE scaregna;

CREATE TABLE UTENTE (
    Username VARCHAR(50) PRIMARY KEY,
    Password VARCHAR(256) NOT NULL,
    Nome VARCHAR(100) NOT NULL,
    Cognome VARCHAR(100) NOT NULL,
    Ruolo ENUM('Amministratore', 'Docente') NOT NULL
);

CREATE TABLE SPAZIO (
    Posizione INT PRIMARY KEY,
    Nome VARCHAR(100) NOT NULL,
    Descrizione TEXT,
    Tipo VARCHAR(50) NOT NULL,
    N_tavoli INT DEFAULT 0
);

CREATE TABLE PRENOTAZIONE (
    Id INT AUTO_INCREMENT PRIMARY KEY,
    Data DATETIME NOT NULL,
    Username VARCHAR(50) NOT NULL,
    Spazio INT NOT NULL,
    FOREIGN KEY (Username) REFERENCES UTENTE(Username),
    FOREIGN KEY (Spazio) REFERENCES SPAZIO(Posizione)
);

CREATE TABLE IMMAGINE (
    Id INT AUTO_INCREMENT PRIMARY KEY,
    Spazio INT NOT NULL,
    Byte LONGBLOB NOT NULL,
    Alt VARCHAR(100) DEFAULT "",
    Mime_type VARCHAR(30) NOT NULL,
    FOREIGN KEY (Spazio) REFERENCES SPAZIO(Posizione) ON DELETE CASCADE
);

CREATE TABLE DISPONIBILITA (
    Spazio INT NOT NULL,
    Mese ENUM('Gennaio', 'Febbraio', 'Marzo', 'Aprile', 'Maggio', 'Giugno', 'Luglio', 'Agosto', 'Settembre', 'Ottobre', 'Novembre', 'Dicembre'),
    Giorno_settimana ENUM('Lunedì', 'Martedì', 'Mercoledì', 'Giovedì', 'Venerdì', 'Sabato', 'Domenica'),
    Orario_apertura TIME,
    Orario_chiusura TIME,
    FOREIGN KEY (Spazio) REFERENCES SPAZIO(Posizione)
);