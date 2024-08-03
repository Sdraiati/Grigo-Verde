DROP DATABASE IF EXISTS scaregna;
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
    Nome VARCHAR(100) NOT NULL UNIQUE,
    Descrizione TEXT,
    Tipo VARCHAR(50) NOT NULL,
    N_tavoli INT DEFAULT 0
);

CREATE TABLE PRENOTAZIONE (
    Id INT AUTO_INCREMENT PRIMARY KEY,
    DataInizio DATETIME NOT NULL,
    DataFine DATETIME NOT NULL,
    Username VARCHAR(50) NOT NULL,
    Spazio INT NOT NULL,
    Descrizione TEXT,
    FOREIGN KEY (Username) REFERENCES UTENTE(Username),
    FOREIGN KEY (Spazio) REFERENCES SPAZIO(Posizione)
);


CREATE TABLE IMMAGINE (
    Spazio INT NOT NULL PRIMARY KEY,
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

INSERT INTO UTENTE (Username, Password, Nome, Cognome, Ruolo) VALUES ('user', 'user', 'user', 'user', 'Docente');
INSERT INTO UTENTE (Username, Password, Nome, Cognome, Ruolo) VALUES ('admin', 'admin', 'admin', 'admin', 'Amministratore');
INSERT INTO UTENTE (Username, Password, Nome, Cognome, Ruolo) VALUES ('misto_segando', 'user', 'Leopoldo', 'Luamaro', 'Docente');
INSERT INTO SPAZIO (Posizione, Nome, Descrizione, Tipo, N_tavoli) VALUES (23, 'prova_in_culo', 'descrizione_in_culo', 'indovina?bravo_in_culo', 99999999);
INSERT INTO PRENOTAZIONE (DataInizio, DataFine, Username, Spazio, Descrizione) VALUES ('2024-08-05 14:00:00', '2024-08-05 16:00:00', 'misto_segando', 23, 'quante r ha gara di scorregge');