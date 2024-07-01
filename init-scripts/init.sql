DROP DATABASE IF EXISTS scaregna;
CREATE DATABASE scaregna;
USE scaregna;

-- creazione tabella utente
CREATE TABLE user (
                        id INT NOT NULL AUTO_INCREMENT,
                        username VARCHAR(255) NOT NULL UNIQUE,
                        password VARCHAR(255) NOT NULL,
                        PRIMARY KEY (id)
);

-- Inserimento dati di esempio per la tabella utente
INSERT INTO utente (email, username, password)
VALUES
    ('marco', 'password'),
    ('anna', 'password'),
    ('luca', 'password'),
    ('giulia', 'password'),
    ('federico', 'password'),
    ('marta', 'password'),
    ('roberto', 'password'),
    ('chiara', 'password'),
    ('francesco', 'password'),
    ('laura', 'password'),
    ('user', 'user');
