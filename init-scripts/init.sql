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
    UNIQUE(DataInizio, DataFine, Spazio),
    FOREIGN KEY (Username) REFERENCES UTENTE(Username) ON UPDATE CASCADE ON DELETE CASCADE,
    FOREIGN KEY (Spazio) REFERENCES SPAZIO(Posizione) ON UPDATE CASCADE ON DELETE CASCADE
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
    FOREIGN KEY (Spazio) REFERENCES SPAZIO(Posizione) ON UPDATE CASCADE ON DELETE CASCADE
);

INSERT INTO UTENTE (Username, Password, Nome, Cognome, Ruolo) VALUES ('user', '$2y$10$jUmfsNGBXq5IUXDQ1fUSOuGAM7KIdwN6I.lxyJWNyysqns.pAP0pe', 'user', 'user', 'Docente');
iNSERT INTO UTENTE (Username, Password, Nome, Cognome, Ruolo) VALUES ('admin', '$2y$10$K6wj8TJzSVeA6y9JeMriiOT3Fh9enThoTvKCSCh/n2P7xATNTD4fG', 'admin', 'admin', 'Amministratore');
INSERT INTO UTENTE (Username, Password, Nome, Cognome, Ruolo) VALUES ('mario', '$2y$10$5NafWjT2WSdbgXnyJGBOjugR9u25xyEWPVzLHysO2RTE47oYjBwpm', 'Mario', 'Rossi', 'Docente'); -- password = pass 
INSERT INTO UTENTE (Username, Password, Nome, Cognome, Ruolo) VALUES ('rossoAndrea', '$2y$10$GUEDKZ4JWU7as8JfjpORXutyGpgOCcyr36K2R9Vm2KFNhHGH6WJki', 'Andrea', 'Rosso', 'Docente'); -- password = pass
INSERT INTO UTENTE (Username, Password, Nome, Cognome, Ruolo) VALUES ('ernestino', '$2y$10$IUTnzyVhpHgJgtCd.1kkruW/hQuF/NaAZtHebyZxD1ZRgC8rWFko.', 'Ernesto', 'Gialli', 'Docente'); -- password = pass

-- SPAZIO 
INSERT INTO SPAZIO (Posizione, Nome, Descrizione, Tipo, N_tavoli) VALUES (1, 'tiglio', "un' aula verde vicino alle palestre di ginnastica", 'Aula verde', 20);
INSERT INTO SPAZIO (Posizione, Nome, Descrizione, Tipo, N_tavoli) VALUES (2, 'ginepro', "un' aula verde vicino alle palestre di ginnastica", 'Aula verde', 20);
INSERT INTO SPAZIO (Posizione, Nome, Descrizione, Tipo, N_tavoli) VALUES (3, 'acero', "un' aula verde vicino alla statua", 'Aula verde', 20);
INSERT INTO SPAZIO (Posizione, Nome, Descrizione, Tipo, N_tavoli) VALUES (4, 'ping pong verde', 'un tavolo da ping pong verde', 'Spazio ricreativo', 10);
INSERT INTO SPAZIO (Posizione, Nome, Descrizione, Tipo, N_tavoli) VALUES (5, 'ping pong rosso', 'un tavolo da ping pong rosso', 'Spazio ricreativo', 10);
INSERT INTO SPAZIO (Posizione, Nome, Descrizione, Tipo, N_tavoli) VALUES (6, 'magnolie', "un'aula verde", 'Aula verde', 10);
INSERT INTO SPAZIO (Posizione, Nome, Descrizione, Tipo, N_tavoli) VALUES (7, 'osmanto', "un'aula verde", 'Aula verde', 10);
INSERT INTO SPAZIO (Posizione, Nome, Descrizione, Tipo, N_tavoli) VALUES (8, 'ligustri', "un'aula verde", 'Aula verde', 10);
INSERT INTO SPAZIO (Posizione, Nome, Descrizione, Tipo, N_tavoli) VALUES (9, 'olmo', "un'aula verde", 'Aula verde', 10);

-- DISPONIBILITA
INSERT INTO DISPONIBILITA (Spazio, Mese, Giorno_settimana, Orario_apertura, Orario_chiusura) VALUES (1, 'Agosto', 'Lunedì', '08:00:00', '18:00:00');
INSERT INTO DISPONIBILITA (Spazio, Mese, Giorno_settimana, Orario_apertura, Orario_chiusura) VALUES (1, 'Agosto', 'Martedì', '08:00:00', '18:00:00');
INSERT INTO DISPONIBILITA (Spazio, Mese, Giorno_settimana, Orario_apertura, Orario_chiusura) VALUES (1, 'Agosto', 'Mercoledì', '08:00:00', '18:00:00');
INSERT INTO DISPONIBILITA (Spazio, Mese, Giorno_settimana, Orario_apertura, Orario_chiusura) VALUES (1, 'Agosto', 'Giovedì', '08:00:00', '18:00:00');
INSERT INTO DISPONIBILITA (Spazio, Mese, Giorno_settimana, Orario_apertura, Orario_chiusura) VALUES (1, 'Agosto', 'Venerdì', '08:00:00', '18:00:00');
INSERT INTO DISPONIBILITA (Spazio, Mese, Giorno_settimana, Orario_apertura, Orario_chiusura) VALUES (1, 'Settembre', 'Lunedì', '08:00:00', '18:00:00');
INSERT INTO DISPONIBILITA (Spazio, Mese, Giorno_settimana, Orario_apertura, Orario_chiusura) VALUES (1, 'Settembre', 'Martedì', '08:00:00', '18:00:00');
INSERT INTO DISPONIBILITA (Spazio, Mese, Giorno_settimana, Orario_apertura, Orario_chiusura) VALUES (1, 'Settembre', 'Mercoledì', '08:00:00', '18:00:00');
INSERT INTO DISPONIBILITA (Spazio, Mese, Giorno_settimana, Orario_apertura, Orario_chiusura) VALUES (1, 'Settembre', 'Giovedì', '08:00:00', '18:00:00');
INSERT INTO DISPONIBILITA (Spazio, Mese, Giorno_settimana, Orario_apertura, Orario_chiusura) VALUES (1, 'Settembre', 'Venerdì', '08:00:00', '18:00:00');
INSERT INTO DISPONIBILITA (Spazio, Mese, Giorno_settimana, Orario_apertura, Orario_chiusura) VALUES (2, 'Agosto', 'Lunedì', '08:00:00', '18:00:00');
INSERT INTO DISPONIBILITA (Spazio, Mese, Giorno_settimana, Orario_apertura, Orario_chiusura) VALUES (2, 'Agosto', 'Martedì', '08:00:00', '18:00:00');
INSERT INTO DISPONIBILITA (Spazio, Mese, Giorno_settimana, Orario_apertura, Orario_chiusura) VALUES (2, 'Agosto', 'Mercoledì', '08:00:00', '18:00:00');
INSERT INTO DISPONIBILITA (Spazio, Mese, Giorno_settimana, Orario_apertura, Orario_chiusura) VALUES (2, 'Agosto', 'Giovedì', '08:00:00', '18:00:00');
INSERT INTO DISPONIBILITA (Spazio, Mese, Giorno_settimana, Orario_apertura, Orario_chiusura) VALUES (2, 'Agosto', 'Venerdì', '08:00:00', '18:00:00');
INSERT INTO DISPONIBILITA (Spazio, Mese, Giorno_settimana, Orario_apertura, Orario_chiusura) VALUES (2, 'Settembre', 'Lunedì', '08:00:00', '18:00:00');
INSERT INTO DISPONIBILITA (Spazio, Mese, Giorno_settimana, Orario_apertura, Orario_chiusura) VALUES (2, 'Settembre', 'Martedì', '08:00:00', '18:00:00');
INSERT INTO DISPONIBILITA (Spazio, Mese, Giorno_settimana, Orario_apertura, Orario_chiusura) VALUES (2, 'Settembre', 'Mercoledì', '08:00:00', '18:00:00');
INSERT INTO DISPONIBILITA (Spazio, Mese, Giorno_settimana, Orario_apertura, Orario_chiusura) VALUES (2, 'Settembre', 'Giovedì', '08:00:00', '18:00:00');
INSERT INTO DISPONIBILITA (Spazio, Mese, Giorno_settimana, Orario_apertura, Orario_chiusura) VALUES (2, 'Settembre', 'Venerdì', '08:00:00', '18:00:00');
INSERT INTO DISPONIBILITA (Spazio, Mese, Giorno_settimana, Orario_apertura, Orario_chiusura) VALUES (3, 'Agosto', 'Lunedì', '08:00:00', '18:00:00');
INSERT INTO DISPONIBILITA (Spazio, Mese, Giorno_settimana, Orario_apertura, Orario_chiusura) VALUES (3, 'Agosto', 'Martedì', '08:00:00', '18:00:00');
INSERT INTO DISPONIBILITA (Spazio, Mese, Giorno_settimana, Orario_apertura, Orario_chiusura) VALUES (3, 'Agosto', 'Mercoledì', '08:00:00', '18:00:00');
INSERT INTO DISPONIBILITA (Spazio, Mese, Giorno_settimana, Orario_apertura, Orario_chiusura) VALUES (3, 'Agosto', 'Giovedì', '08:00:00', '18:00:00');
INSERT INTO DISPONIBILITA (Spazio, Mese, Giorno_settimana, Orario_apertura, Orario_chiusura) VALUES (3, 'Agosto', 'Venerdì', '08:00:00', '18:00:00');
INSERT INTO DISPONIBILITA (Spazio, Mese, Giorno_settimana, Orario_apertura, Orario_chiusura) VALUES (3, 'Settembre', 'Lunedì', '08:00:00', '18:00:00');
INSERT INTO DISPONIBILITA (Spazio, Mese, Giorno_settimana, Orario_apertura, Orario_chiusura) VALUES (3, 'Settembre', 'Martedì', '08:00:00', '18:00:00');
INSERT INTO DISPONIBILITA (Spazio, Mese, Giorno_settimana, Orario_apertura, Orario_chiusura) VALUES (3, 'Settembre', 'Mercoledì', '08:00:00', '18:00:00');
INSERT INTO DISPONIBILITA (Spazio, Mese, Giorno_settimana, Orario_apertura, Orario_chiusura) VALUES (3, 'Settembre', 'Giovedì', '08:00:00', '18:00:00');
INSERT INTO DISPONIBILITA (Spazio, Mese, Giorno_settimana, Orario_apertura, Orario_chiusura) VALUES (3, 'Settembre', 'Venerdì', '08:00:00', '18:00:00');
INSERT INTO DISPONIBILITA (Spazio, Mese, Giorno_settimana, Orario_apertura, Orario_chiusura) VALUES (4, 'Agosto', 'Lunedì', '08:00:00', '18:00:00');
INSERT INTO DISPONIBILITA (Spazio, Mese, Giorno_settimana, Orario_apertura, Orario_chiusura) VALUES (4, 'Agosto', 'Martedì', '08:00:00', '18:00:00');
INSERT INTO DISPONIBILITA (Spazio, Mese, Giorno_settimana, Orario_apertura, Orario_chiusura) VALUES (4, 'Agosto', 'Mercoledì', '08:00:00', '18:00:00');
INSERT INTO DISPONIBILITA (Spazio, Mese, Giorno_settimana, Orario_apertura, Orario_chiusura) VALUES (4, 'Agosto', 'Giovedì', '08:00:00', '18:00:00');
INSERT INTO DISPONIBILITA (Spazio, Mese, Giorno_settimana, Orario_apertura, Orario_chiusura) VALUES (4, 'Agosto', 'Venerdì', '08:00:00', '18:00:00');
INSERT INTO DISPONIBILITA (Spazio, Mese, Giorno_settimana, Orario_apertura, Orario_chiusura) VALUES (4, 'Settembre', 'Lunedì', '08:00:00', '18:00:00');
INSERT INTO DISPONIBILITA (Spazio, Mese, Giorno_settimana, Orario_apertura, Orario_chiusura) VALUES (4, 'Settembre', 'Martedì', '08:00:00', '18:00:00');
INSERT INTO DISPONIBILITA (Spazio, Mese, Giorno_settimana, Orario_apertura, Orario_chiusura) VALUES (4, 'Settembre', 'Mercoledì', '08:00:00', '18:00:00');
INSERT INTO DISPONIBILITA (Spazio, Mese, Giorno_settimana, Orario_apertura, Orario_chiusura) VALUES (4, 'Settembre', 'Giovedì', '08:00:00', '18:00:00');
INSERT INTO DISPONIBILITA (Spazio, Mese, Giorno_settimana, Orario_apertura, Orario_chiusura) VALUES (4, 'Settembre', 'Venerdì', '08:00:00', '18:00:00');
INSERT INTO DISPONIBILITA (Spazio, Mese, Giorno_settimana, Orario_apertura, Orario_chiusura) VALUES (5, 'Agosto', 'Lunedì', '08:00:00', '18:00:00');
INSERT INTO DISPONIBILITA (Spazio, Mese, Giorno_settimana, Orario_apertura, Orario_chiusura) VALUES (5, 'Agosto', 'Martedì', '08:00:00', '18:00:00');
INSERT INTO DISPONIBILITA (Spazio, Mese, Giorno_settimana, Orario_apertura, Orario_chiusura) VALUES (5, 'Agosto', 'Mercoledì', '08:00:00', '18:00:00');
INSERT INTO DISPONIBILITA (Spazio, Mese, Giorno_settimana, Orario_apertura, Orario_chiusura) VALUES (5, 'Agosto', 'Giovedì', '08:00:00', '18:00:00');
INSERT INTO DISPONIBILITA (Spazio, Mese, Giorno_settimana, Orario_apertura, Orario_chiusura) VALUES (5, 'Agosto', 'Venerdì', '08:00:00', '18:00:00');
INSERT INTO DISPONIBILITA (Spazio, Mese, Giorno_settimana, Orario_apertura, Orario_chiusura) VALUES (5, 'Settembre', 'Lunedì', '08:00:00', '18:00:00');
INSERT INTO DISPONIBILITA (Spazio, Mese, Giorno_settimana, Orario_apertura, Orario_chiusura) VALUES (5, 'Settembre', 'Martedì', '08:00:00', '18:00:00');
INSERT INTO DISPONIBILITA (Spazio, Mese, Giorno_settimana, Orario_apertura, Orario_chiusura) VALUES (5, 'Settembre', 'Mercoledì', '08:00:00', '18:00:00');
INSERT INTO DISPONIBILITA (Spazio, Mese, Giorno_settimana, Orario_apertura, Orario_chiusura) VALUES (5, 'Settembre', 'Giovedì', '08:00:00', '18:00:00');
INSERT INTO DISPONIBILITA (Spazio, Mese, Giorno_settimana, Orario_apertura, Orario_chiusura) VALUES (5, 'Settembre', 'Venerdì', '08:00:00', '18:00:00');
INSERT INTO DISPONIBILITA (Spazio, Mese, Giorno_settimana, Orario_apertura, Orario_chiusura) VALUES (6, 'Agosto', 'Lunedì', '08:00:00', '18:00:00');
INSERT INTO DISPONIBILITA (Spazio, Mese, Giorno_settimana, Orario_apertura, Orario_chiusura) VALUES (6, 'Agosto', 'Martedì', '08:00:00', '18:00:00');
INSERT INTO DISPONIBILITA (Spazio, Mese, Giorno_settimana, Orario_apertura, Orario_chiusura) VALUES (6, 'Agosto', 'Mercoledì', '08:00:00', '18:00:00');
INSERT INTO DISPONIBILITA (Spazio, Mese, Giorno_settimana, Orario_apertura, Orario_chiusura) VALUES (6, 'Agosto', 'Giovedì', '08:00:00', '18:00:00');
INSERT INTO DISPONIBILITA (Spazio, Mese, Giorno_settimana, Orario_apertura, Orario_chiusura) VALUES (6, 'Agosto', 'Venerdì', '08:00:00', '18:00:00');
INSERT INTO DISPONIBILITA (Spazio, Mese, Giorno_settimana, Orario_apertura, Orario_chiusura) VALUES (6, 'Settembre', 'Lunedì', '08:00:00', '18:00:00');
INSERT INTO DISPONIBILITA (Spazio, Mese, Giorno_settimana, Orario_apertura, Orario_chiusura) VALUES (6, 'Settembre', 'Martedì', '08:00:00', '18:00:00');
INSERT INTO DISPONIBILITA (Spazio, Mese, Giorno_settimana, Orario_apertura, Orario_chiusura) VALUES (6, 'Settembre', 'Mercoledì', '08:00:00', '18:00:00');
INSERT INTO DISPONIBILITA (Spazio, Mese, Giorno_settimana, Orario_apertura, Orario_chiusura) VALUES (6, 'Settembre', 'Giovedì', '08:00:00', '18:00:00');
INSERT INTO DISPONIBILITA (Spazio, Mese, Giorno_settimana, Orario_apertura, Orario_chiusura) VALUES (6, 'Settembre', 'Venerdì', '08:00:00', '18:00:00');
INSERT INTO DISPONIBILITA (Spazio, Mese, Giorno_settimana, Orario_apertura, Orario_chiusura) VALUES (7, 'Agosto', 'Lunedì', '08:00:00', '18:00:00');
INSERT INTO DISPONIBILITA (Spazio, Mese, Giorno_settimana, Orario_apertura, Orario_chiusura) VALUES (7, 'Agosto', 'Martedì', '08:00:00', '18:00:00');
INSERT INTO DISPONIBILITA (Spazio, Mese, Giorno_settimana, Orario_apertura, Orario_chiusura) VALUES (7, 'Agosto', 'Mercoledì', '08:00:00', '18:00:00');
INSERT INTO DISPONIBILITA (Spazio, Mese, Giorno_settimana, Orario_apertura, Orario_chiusura) VALUES (7, 'Agosto', 'Giovedì', '08:00:00', '18:00:00');
INSERT INTO DISPONIBILITA (Spazio, Mese, Giorno_settimana, Orario_apertura, Orario_chiusura) VALUES (7, 'Agosto', 'Venerdì', '08:00:00', '18:00:00');
INSERT INTO DISPONIBILITA (Spazio, Mese, Giorno_settimana, Orario_apertura, Orario_chiusura) VALUES (7, 'Settembre', 'Lunedì', '08:00:00', '18:00:00');
INSERT INTO DISPONIBILITA (Spazio, Mese, Giorno_settimana, Orario_apertura, Orario_chiusura) VALUES (7, 'Settembre', 'Martedì', '08:00:00', '18:00:00');
INSERT INTO DISPONIBILITA (Spazio, Mese, Giorno_settimana, Orario_apertura, Orario_chiusura) VALUES (7, 'Settembre', 'Mercoledì', '08:00:00', '18:00:00');
INSERT INTO DISPONIBILITA (Spazio, Mese, Giorno_settimana, Orario_apertura, Orario_chiusura) VALUES (7, 'Settembre', 'Giovedì', '08:00:00', '18:00:00');
INSERT INTO DISPONIBILITA (Spazio, Mese, Giorno_settimana, Orario_apertura, Orario_chiusura) VALUES (7, 'Settembre', 'Venerdì', '08:00:00', '18:00:00');
INSERT INTO DISPONIBILITA (Spazio, Mese, Giorno_settimana, Orario_apertura, Orario_chiusura) VALUES (8, 'Agosto', 'Lunedì', '08:00:00', '18:00:00');
INSERT INTO DISPONIBILITA (Spazio, Mese, Giorno_settimana, Orario_apertura, Orario_chiusura) VALUES (8, 'Agosto', 'Martedì', '08:00:00', '18:00:00');
INSERT INTO DISPONIBILITA (Spazio, Mese, Giorno_settimana, Orario_apertura, Orario_chiusura) VALUES (8, 'Agosto', 'Mercoledì', '08:00:00', '18:00:00');
INSERT INTO DISPONIBILITA (Spazio, Mese, Giorno_settimana, Orario_apertura, Orario_chiusura) VALUES (8, 'Agosto', 'Giovedì', '08:00:00', '18:00:00');
INSERT INTO DISPONIBILITA (Spazio, Mese, Giorno_settimana, Orario_apertura, Orario_chiusura) VALUES (8, 'Agosto', 'Venerdì', '08:00:00', '18:00:00');
INSERT INTO DISPONIBILITA (Spazio, Mese, Giorno_settimana, Orario_apertura, Orario_chiusura) VALUES (8, 'Settembre', 'Lunedì', '08:00:00', '18:00:00');
INSERT INTO DISPONIBILITA (Spazio, Mese, Giorno_settimana, Orario_apertura, Orario_chiusura) VALUES (8, 'Settembre', 'Martedì', '08:00:00', '18:00:00');
INSERT INTO DISPONIBILITA (Spazio, Mese, Giorno_settimana, Orario_apertura, Orario_chiusura) VALUES (8, 'Settembre', 'Mercoledì', '08:00:00', '18:00:00');
INSERT INTO DISPONIBILITA (Spazio, Mese, Giorno_settimana, Orario_apertura, Orario_chiusura) VALUES (8, 'Settembre', 'Giovedì', '08:00:00', '18:00:00');
INSERT INTO DISPONIBILITA (Spazio, Mese, Giorno_settimana, Orario_apertura, Orario_chiusura) VALUES (8, 'Settembre', 'Venerdì', '08:00:00', '18:00:00');
INSERT INTO DISPONIBILITA (Spazio, Mese, Giorno_settimana, Orario_apertura, Orario_chiusura) VALUES (9, 'Agosto', 'Lunedì', '08:00:00', '18:00:00');
INSERT INTO DISPONIBILITA (Spazio, Mese, Giorno_settimana, Orario_apertura, Orario_chiusura) VALUES (9, 'Agosto', 'Martedì', '08:00:00', '18:00:00');
INSERT INTO DISPONIBILITA (Spazio, Mese, Giorno_settimana, Orario_apertura, Orario_chiusura) VALUES (9, 'Agosto', 'Mercoledì', '08:00:00', '18:00:00');
INSERT INTO DISPONIBILITA (Spazio, Mese, Giorno_settimana, Orario_apertura, Orario_chiusura) VALUES (9, 'Agosto', 'Giovedì', '08:00:00', '18:00:00');
INSERT INTO DISPONIBILITA (Spazio, Mese, Giorno_settimana, Orario_apertura, Orario_chiusura) VALUES (9, 'Agosto', 'Venerdì', '08:00:00', '18:00:00');
INSERT INTO DISPONIBILITA (Spazio, Mese, Giorno_settimana, Orario_apertura, Orario_chiusura) VALUES (9, 'Settembre', 'Lunedì', '08:00:00', '18:00:00');
INSERT INTO DISPONIBILITA (Spazio, Mese, Giorno_settimana, Orario_apertura, Orario_chiusura) VALUES (9, 'Settembre', 'Martedì', '08:00:00', '18:00:00');
INSERT INTO DISPONIBILITA (Spazio, Mese, Giorno_settimana, Orario_apertura, Orario_chiusura) VALUES (9, 'Settembre', 'Mercoledì', '08:00:00', '18:00:00');
INSERT INTO DISPONIBILITA (Spazio, Mese, Giorno_settimana, Orario_apertura, Orario_chiusura) VALUES (9, 'Settembre', 'Giovedì', '08:00:00', '18:00:00');
INSERT INTO DISPONIBILITA (Spazio, Mese, Giorno_settimana, Orario_apertura, Orario_chiusura) VALUES (9, 'Settembre', 'Venerdì', '08:00:00', '18:00:00');

-- PRENOTAZIONI 
-- INSERT INTO PRENOTAZIONE (DataInizio, DataFine, Username, Spazio, Descrizione) VALUES ('2024-08-15 12:00:00', '2024-08-15 16:00:00', 'user', 1, 'descrizione relativa alla prenotazione');
INSERT INTO PRENOTAZIONE (DataInizio, DataFine, Username, Spazio, Descrizione) VALUES ('2024-09-01 12:00:00', '2024-09-01 16:00:00', 'user', 1, 'descrizione relativa alla prenotazione');
INSERT INTO PRENOTAZIONE (DataInizio, DataFine, Username, Spazio, Descrizione) VALUES ('2024-09-05 11:00:00', '2024-09-05 13:00:00', 'admin', 1, 'descrizione relativa alla prenotazione');
INSERT INTO PRENOTAZIONE (DataInizio, DataFine, Username, Spazio, Descrizione) VALUES ('2024-09-22 14:00:00', '2024-09-22 16:00:00', 'ernestino', 1, 'descrizione relativa alla prenotazione');
INSERT INTO PRENOTAZIONE (DataInizio, DataFine, Username, Spazio, Descrizione) VALUES ('2024-09-25 14:00:00', '2024-09-25 16:00:00', 'mario', 1, 'descrizione relativa alla prenotazione');

INSERT INTO PRENOTAZIONE (DataInizio, DataFine, Username, Spazio, Descrizione) VALUES ('2024-09-23 12:00:00', '2024-09-23 16:00:00', 'mario', 2, 'descrizione relativa alla prenotazione');
INSERT INTO PRENOTAZIONE (DataInizio, DataFine, Username, Spazio, Descrizione) VALUES ('2024-09-24 14:00:00', '2024-09-24 15:00:00', 'user', 2, 'descrizione relativa alla prenotazione');
INSERT INTO PRENOTAZIONE (DataInizio, DataFine, Username, Spazio, Descrizione) VALUES ('2024-09-01 13:00:00', '2024-09-01 15:00:00', 'ernestino', 2, 'descrizione relativa alla prenotazione');
INSERT INTO PRENOTAZIONE (DataInizio, DataFine, Username, Spazio, Descrizione) VALUES ('2024-09-05 16:00:00', '2024-09-05 18:00:00', 'admin', 2, 'descrizione relativa alla prenotazione');

INSERT INTO PRENOTAZIONE (DataInizio, DataFine, Username, Spazio, Descrizione) VALUES ('2024-09-23 14:00:00', '2024-09-23 16:00:00', 'admin', 3, 'descrizione relativa alla prenotazione');
INSERT INTO PRENOTAZIONE (DataInizio, DataFine, Username, Spazio, Descrizione) VALUES ('2024-09-24 14:00:00', '2024-09-24 16:00:00', 'mario', 3, 'descrizione relativa alla prenotazione');
INSERT INTO PRENOTAZIONE (DataInizio, DataFine, Username, Spazio, Descrizione) VALUES ('2024-09-01 14:00:00', '2024-09-01 16:00:00', 'user', 3, 'descrizione relativa alla prenotazione');
INSERT INTO PRENOTAZIONE (DataInizio, DataFine, Username, Spazio, Descrizione) VALUES ('2024-09-05 16:00:00', '2024-09-05 18:00:00', 'ernestino', 3, 'descrizione relativa alla prenotazione');

INSERT INTO PRENOTAZIONE (DataInizio, DataFine, Username, Spazio, Descrizione) VALUES ('2024-09-23 14:00:00', '2024-09-23 16:00:00', 'ernestino', 4, 'descrizione relativa alla prenotazione');
INSERT INTO PRENOTAZIONE (DataInizio, DataFine, Username, Spazio, Descrizione) VALUES ('2024-09-24 14:00:00', '2024-09-24 16:00:00', 'mario', 4, 'descrizione relativa alla prenotazione');
INSERT INTO PRENOTAZIONE (DataInizio, DataFine, Username, Spazio, Descrizione) VALUES ('2024-09-01 14:00:00', '2024-09-01 16:00:00', 'user', 4, 'descrizione relativa alla prenotazione');
INSERT INTO PRENOTAZIONE (DataInizio, DataFine, Username, Spazio, Descrizione) VALUES ('2024-09-05 16:00:00', '2024-09-05 18:00:00', 'admin', 4, 'descrizione relativa alla prenotazione');

INSERT INTO PRENOTAZIONE (DataInizio, DataFine, Username, Spazio, Descrizione) VALUES ('2024-09-24 14:00:00', '2024-09-24 16:00:00', 'admin', 5, 'descrizione relativa alla prenotazione');
INSERT INTO PRENOTAZIONE (DataInizio, DataFine, Username, Spazio, Descrizione) VALUES ('2024-09-24 12:00:00', '2024-09-24 14:00:00', 'ernestino', 5, 'descrizione relativa alla prenotazione');
INSERT INTO PRENOTAZIONE (DataInizio, DataFine, Username, Spazio, Descrizione) VALUES ('2024-09-01 14:00:00', '2024-09-01 16:00:00', 'mario', 5, 'descrizione relativa alla prenotazione');
INSERT INTO PRENOTAZIONE (DataInizio, DataFine, Username, Spazio, Descrizione) VALUES ('2024-09-05 16:00:00', '2024-09-05 18:00:00', 'user', 5, 'descrizione relativa alla prenotazione');

INSERT INTO PRENOTAZIONE (DataInizio, DataFine, Username, Spazio, Descrizione) VALUES ('2024-09-23 11:00:00', '2024-09-23 13:00:00', 'mario', 6, 'descrizione relativa alla prenotazione');
INSERT INTO PRENOTAZIONE (DataInizio, DataFine, Username, Spazio, Descrizione) VALUES ('2024-09-24 14:00:00', '2024-09-24 16:00:00', 'user', 6, 'descrizione relativa alla prenotazione');
INSERT INTO PRENOTAZIONE (DataInizio, DataFine, Username, Spazio, Descrizione) VALUES ('2024-09-01 13:00:00', '2024-09-01 14:00:00', 'admin', 6, 'descrizione relativa alla prenotazione');
INSERT INTO PRENOTAZIONE (DataInizio, DataFine, Username, Spazio, Descrizione) VALUES ('2024-09-05 09:00:00', '2024-09-05 10:00:00', 'ernestino', 6, 'descrizione relativa alla prenotazione');

INSERT INTO PRENOTAZIONE (DataInizio, DataFine, Username, Spazio, Descrizione) VALUES ('2024-09-23 13:00:00', '2024-09-23 15:00:00', 'ernestino', 7, 'descrizione relativa alla prenotazione');
INSERT INTO PRENOTAZIONE (DataInizio, DataFine, Username, Spazio, Descrizione) VALUES ('2024-09-24 12:00:00', '2024-09-24 13:00:00', 'mario', 7, 'descrizione relativa alla prenotazione');
INSERT INTO PRENOTAZIONE (DataInizio, DataFine, Username, Spazio, Descrizione) VALUES ('2024-09-01 14:00:00', '2024-09-01 16:00:00', 'admin', 7, 'descrizione relativa alla prenotazione');
INSERT INTO PRENOTAZIONE (DataInizio, DataFine, Username, Spazio, Descrizione) VALUES ('2024-09-01 16:00:00', '2024-09-01 18:00:00', 'user', 7, 'descrizione relativa alla prenotazione');

INSERT INTO PRENOTAZIONE (DataInizio, DataFine, Username, Spazio, Descrizione) VALUES ('2024-09-23 14:00:00', '2024-09-23 16:00:00', 'user', 8, 'descrizione relativa alla prenotazione');
INSERT INTO PRENOTAZIONE (DataInizio, DataFine, Username, Spazio, Descrizione) VALUES ('2024-09-24 14:00:00', '2024-09-24 16:00:00', 'ernestino', 8, 'descrizione relativa alla prenotazione');
INSERT INTO PRENOTAZIONE (DataInizio, DataFine, Username, Spazio, Descrizione) VALUES ('2024-09-01 11:00:00', '2024-09-01 16:00:00', 'admin', 8, 'descrizione relativa alla prenotazione');
INSERT INTO PRENOTAZIONE (DataInizio, DataFine, Username, Spazio, Descrizione) VALUES ('2024-09-05 16:00:00', '2024-09-05 17:00:00', 'mario', 8, 'descrizione relativa alla prenotazione');

INSERT INTO PRENOTAZIONE (DataInizio, DataFine, Username, Spazio, Descrizione) VALUES ('2024-09-23 14:00:00', '2024-09-23 16:00:00', 'mario', 9, 'descrizione relativa alla prenotazione');
INSERT INTO PRENOTAZIONE (DataInizio, DataFine, Username, Spazio, Descrizione) VALUES ('2024-09-24 16:00:00', '2024-09-24 19:00:00', 'user', 9, 'descrizione relativa alla prenotazione');
INSERT INTO PRENOTAZIONE (DataInizio, DataFine, Username, Spazio, Descrizione) VALUES ('2024-09-01 14:00:00', '2024-09-01 16:00:00', 'admin', 9, 'descrizione relativa alla prenotazione');
INSERT INTO PRENOTAZIONE (DataInizio, DataFine, Username, Spazio, Descrizione) VALUES ('2024-09-01 16:00:00', '2024-09-05 18:00:00', 'ernestino', 9, 'descrizione relativa alla prenotazione');
