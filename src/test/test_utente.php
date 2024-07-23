<?php
require_once 'utente.php';
require_once 'database.php';

$utente = new Utente();

if ($utente->nuovo('mario_rossi', 'Mario', 'Rossi', 'Admin', 'password123')) {
    echo "Utente creato con successo!";
} else {
    echo "Errore nella creazione dell'utente.";
}

// Modifica un utente esistente
if ($utente->modifica('mario_rossi', 'Mario', 'Bianchi', 'User', 'newpassword123')) {
    echo "Utente modificato con successo!";
} else {
    echo "Errore nella modifica dell'utente.";
}

// Elimina un utente
if ($utente->elimina('mario_rossi')) {
    echo "Utente eliminato con successo!";
} else {
    echo "Errore nell'eliminazione dell'utente.";
}
