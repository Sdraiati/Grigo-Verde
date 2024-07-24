<?php
$project_root = dirname(__FILE__, 2);
require_once $project_root . '/model/database.php';
require_once $project_root . '/model/utente.php';

$utente = new Utente();

function nuovo_utente() {
    global $utente;
    if ($utente->nuovo('mario_rossi', 'Mario', 'Rossi', 'Amministratore', 'password123')) {
        return true;
    } else {
        return false;
    }
}

function modifica_utente() {
    global $utente;
    if ($utente->modifica('mario_rossi', 'Mario', 'Bianchi', 'Docente', 'newpassword123')) {
        return true;
    } else {
        return false;
    }
}

function elimina_utente() {
    global $utente;
    if ($utente->elimina('mario_rossi')) {
        return true;
    } else {
        return false;
    }
}
