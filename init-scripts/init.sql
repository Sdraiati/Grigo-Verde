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

INSERT INTO UTENTE (Username, Password, Nome, Cognome, Ruolo) VALUES ('user', '$2y$10$jUmfsNGBXq5IUXDQ1fUSOuGAM7KIdwN6I.lxyJWNyysqns.pAP0pe', 'user', 'user', 'Docente');
INSERT INTO UTENTE (Username, Password, Nome, Cognome, Ruolo) VALUES ('admin', '$2y$10$K6wj8TJzSVeA6y9JeMriiOT3Fh9enThoTvKCSCh/n2P7xATNTD4fG', 'admin', 'admin', 'Amministratore');
INSERT INTO UTENTE (Username, Password, Nome, Cognome, Ruolo) VALUES ('misto_segando', 'user', 'Leopoldo', 'Luamaro', 'Docente');


INSERT INTO SPAZIO (Posizione, Nome, Descrizione, Tipo, N_tavoli) VALUES (4, 'pino', 'grigio', 'grigio', 10);
INSERT INTO SPAZIO (Posizione, Nome, Descrizione, Tipo, N_tavoli) VALUES (3, 'tiglio', "un' aula verde vicino alla statua", 'verde', 20);
INSERT INTO SPAZIO (Posizione, Nome, Descrizione, Tipo, N_tavoli) VALUES (2, 'ginepro', "un' aula verde vicino alle palestre di ginnastica", 'verde', 20);

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

INSERT INTO PRENOTAZIONE (DataInizio, DataFine, Username, Spazio, Descrizione) VALUES ('2024-08-05 14:00:00', '2024-08-05 16:00:00', 'user', 4, 'quante r ha gara di scorregge');

INSERT INTO PRENOTAZIONE (DataInizio, DataFine, Username, Spazio, Descrizione) VALUES ('2024-08-12 14:00:00', '2024-08-12 16:00:00', 'user', 2, 'attivita didattica');
INSERT INTO PRENOTAZIONE (DataInizio, DataFine, Username, Spazio, Descrizione) VALUES ('2024-08-12 14:00:00', '2024-08-12 16:00:00', 'user', 3, 'napoleone');
