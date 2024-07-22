DROP DATABASE IF EXISTS scaregna;
CREATE DATABASE scaregna;
USE scaregna;

CREATE TABLE UTENTE (
    Username VARCHAR(50) PRIMARY KEY,
    Password varchar(256) NOT NULL,
    Nome VARCHAR(100) NOT NULL,
    Cognome VARCHAR(100) NOT NULL,
    Ruolo ENUM('Amministratore', 'Docente') NOT NULL
);

CREATE TABLE SPAZIO (
    Posizione INT PRIMARY KEY,
    Nome VARCHAR(100) NOT NULL,
    Descrizione TEXT DEFAULT "",
    Tipo VARCHAR(50) NOT NULL,
    N_tavoli INT DEFAULT 0
);

CREATE TABLE PRENOTAZIONE (
    Id INT AUTO_INCREMENT PRIMARY KEY,
    Data DATETIME NOT NULL,
    Username VARCHAR(50) NOT NULL REFERENCES UTENTE(Username),
    Spazio INT NOT NULL REFERENCES SPAZIO(Posizione)
);

CREATE TABLE IMMAGINE (
    Spazio INT AUTO_INCREMENT PRIMARY KEY REFERENCES SPAZIO(Posizione) ON DELETE CASCADE,
    Byte LONGBLOB NOT NULL,
    Alt VARCHAR(100) DEFAULT "",
    Mime_type VARCHAR(30) NOT NULL,
);

CREATE TABLE DISPONIBILITA (
    Spazio INT NOT NULL REFERENCES SPAZIO(Posizione),
    Mese ENUM('Gennaio', 'Febbraio', 'Marzo', 'Aprile', 'Maggio', 'Giugno', 'Luglio', 'Agosto', 'Settembre', 'Ottobre', 'Novembre', 'Dicembre'),
    Giorno_settimana ENUM('Lunedì', 'Martedì', 'Mercoledì', 'Giovedì', 'Venerdì', 'Sabato', 'Domenica'),
    Orario_apertura TIME,
    Orario_chiusura TIME
)
