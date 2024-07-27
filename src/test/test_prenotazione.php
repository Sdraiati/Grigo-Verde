<?php
$project_root = dirname(__FILE__, 2);
require_once $project_root . '/model/database.php';
require_once $project_root . '/model/prenotazione.php';
require_once $project_root . '/model/spazio.php';
require_once $project_root . '/model/utente.php';

$prenotazione = new Prenotazione();
$spazio = new Spazio();
$utente = new Utente();

function nuovo_prenotazione() {
    global $prenotazione, $spazio, $utente;
    $utente->nuovo('mario_rossi', 'Mario', 'Rossi', 'Amministratore', 'password123');
    $spazio->nuovo(1, 'Sala Conferenze', 'Una grande sala per conferenze', 'Conferenza', 20);
    $res = $prenotazione->nuovo('2024-08-05 14:00:00', 'mario_rossi', 1);
    $prenotazione->elimina($res);
    $utente->elimina('mario_rossi');
    $spazio->elimina(1);
    return $res !== false;
}

function modifica_prenotazione() {
    global $prenotazione;
    $reservationId = $prenotazione->nuovo('2024-08-05 14:00:00', 'mario_rossi', 1);
    $res = $prenotazione->modifica($reservationId, '2024-08-06 15:00:00', 'mario_rossi', 1);
    $prenotazione->elimina($reservationId); // Cleanup
    return $res !== false;
}

function elimina_prenotazione() {
    global $prenotazione;
    $reservationId = $prenotazione->nuovo('2024-08-05 14:00:00', 'mario_rossi', 1);
    $result = $prenotazione->elimina($reservationId);
    return $result !== false;
}

function prendi_prenotazione() {
    global $prenotazione, $spazio, $utente;
    $utente->nuovo('mario_rossi', 'Mario', 'Rossi', 'Amministratore', 'password123');
    $spazio->nuovo(1, 'Sala Conferenze', 'Una grande sala per conferenze', 'Conferenza', 20);
    $reservationId = $prenotazione->nuovo('2024-08-05 14:00:00', 'mario_rossi', 1);
    $reservations = $prenotazione->prendi(1);
    if ($reservations && $reservations[0]['Username'] === 'mario_rossi') {
        $prenotazione->elimina($reservationId);
        $utente->elimina('mario_rossi');
        $spazio->elimina(1);
        return true;
    }
    return false;
}

function prendi_per_settimana_prenotazione() {
    global $prenotazione, $spazio, $utente;
    $utente->nuovo('mario_rossi', 'Mario', 'Rossi', 'Amministratore', 'password123');
    $spazio->nuovo(1, 'Sala Conferenze', 'Una grande sala per conferenze', 'Conferenza', 20);
    
    $reservationId1 = $prenotazione->nuovo('2024-08-10 14:00:00', 'mario_rossi', 1);
    $reservationId2 = $prenotazione->nuovo('2024-08-12 14:00:00', 'mario_rossi', 1);
    
    $reservations = $prenotazione->prendi_per_settimana(1, '2024-08-05 00:00:00');
    $prenotazione->elimina($reservationId1);
    $prenotazione->elimina($reservationId2);
    $utente->elimina('mario_rossi');
    $spazio->elimina(1);
    return count($reservations) > 1;
}