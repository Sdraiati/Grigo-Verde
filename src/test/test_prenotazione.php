<?php
$project_root = dirname(__FILE__, 2);
require_once $project_root . '/model/database.php';
require_once $project_root . '/model/prenotazione.php';
require_once $project_root . '/model/spazio.php';
require_once $project_root . '/model/utente.php';

$prenotazione = new Prenotazione();
$spazio = new Spazio();
$utente = new Utente();

function nuovo_prenotazione()
{
    global $prenotazione, $spazio, $utente;
    $utente->nuovo('mario_rossi', 'Mario', 'Rossi', 'Amministratore', 'password123');
    $spazio->nuovo(1, 'Sala Conferenze', 'Una grande sala per conferenze', 'Conferenza', 20);
    $user = $utente->prendi('mario_rossi');
    $space = $spazio->prendi_per_nome('Sala Conferenze');
    $res = $prenotazione->nuovo('2024-08-05 14:00:00', '2024-08-05 16:00:00', $user['Username'], $space['Posizione'], 'Conferenza');
    $prenotazione->elimina('2024-08-05 14:00:00', '2024-08-05 16:00:00', $user['Username'], $space['Posizione']);
    return $res !== false;
}

function modifica_prenotazione()
{
    global $prenotazione;
    $prenotazione->nuovo('2024-08-05 14:00:00', '2024-08-05 16:00:00', 'mario_rossi', 1, 'Conferenza');
    $res = $prenotazione->modifica('2024-08-05 14:00:00', '2024-08-05 16:00:00', 'mario_rossi', 1, '2024-08-06 15:00:00', '2024-08-06 17:00:00', 'mario_rossi', 1, 'Conferenza');
    $prenotazione->elimina('2024-08-06 15:00:00', '2024-08-06 17:00:00', 'mario_rossi', 1);
    return $res !== false;
}

function elimina_prenotazione()
{
    global $prenotazione;
    $prenotazione->nuovo('2024-08-05 14:00:00', '2024-08-05 16:00:00', 'mario_rossi', 1, 'Conferenza');
    $result = $prenotazione->elimina('2024-08-05 14:00:00', '2024-08-05 16:00:00', 'mario_rossi', 1);
    return $result !== false;
}

function prendi_prenotazione()
{
    global $prenotazione, $spazio, $utente;
    $prenotazione->nuovo('2024-08-05 14:00:00', '2024-08-05 16:00:00', 'mario_rossi', 1, 'Conferenza');
    $reservations = $prenotazione->prendi(1);
    if ($reservations && $reservations[0]['Username'] === 'mario_rossi') {
        $prenotazione->elimina('2024-08-05 14:00:00', '2024-08-05 16:00:00', 'mario_rossi', 1); // Cleanup
        return true;
    }
    $prenotazione->elimina('2024-08-05 14:00:00', '2024-08-05 16:00:00', 'mario_rossi', 1);
    return false;
}

function prendi_per_settimana_prenotazione()
{
    global $prenotazione, $spazio, $utente;

    $prenotazione->nuovo('2024-08-10 14:00:00', '2024-08-10 16:00:00', 'mario_rossi', 1, 'Conferenza');
    $prenotazione->nuovo('2024-08-12 14:00:00', '2024-08-12 16:00:01', 'mario_rossi', 1, 'Conferenza');

    $reservations = $prenotazione->prendi_per_settimana(1, '2024-08-05 00:00:00');
    $prenotazione->elimina('2024-08-10 14:00:00', '2024-08-10 16:00:00', 'mario_rossi', 1);
    $prenotazione->elimina('2024-08-12 14:00:00', '2024-08-12 16:00:01', 'mario_rossi', 1);
    $utente->elimina('mario_rossi');
    $spazio->elimina(1);
    return count($reservations) > 1;
}