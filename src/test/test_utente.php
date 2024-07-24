<?php
$project_root = dirname(__FILE__, 2);
require_once $project_root . '/model/database.php';
require_once $project_root . '/model/utente.php';

$utente = new Utente();

function nuovo_utente()
{
    global $utente;
    return $utente->nuovo('mario_rossi', 'Mario', 'Rossi', 'Amministratore', 'password123');
}

function modifica_utente()
{
    global $utente;
    return $utente->modifica('mario_rossi', 'Mario', 'Bianchi', 'Docente', 'newpassword123');
}

function elimina_utente()
{
    global $utente;
    return $utente->elimina('mario_rossi');
}

function prendi_utente()
{
    global $utente;
    $utente->nuovo('mario_rossi', 'Mario', 'Rossi', 'Amministratore', 'password123');
    $mario = $utente->prendi('mario_rossi');
    if (!$mario || $mario['Nome'] != 'Mario' || $mario['Cognome'] != 'Rossi' || $mario['Ruolo'] != 'Amministratore' || !password_verify('password123', $mario['Password'])) {
        return False;
    }
    $utente->elimina('mario_rossi');
    return True;
}
