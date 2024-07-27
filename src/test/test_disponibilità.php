<?php
$project_root = dirname(__FILE__, 2);
require_once $project_root . '/model/database.php';
require_once $project_root . '/model/disponibilità.php';
require_once $project_root . '/model/spazio.php';

$disponibilita = new Disponibilita();
$spazio = new Spazio();

function nuovo_disponibilita() {
    global $disponibilita, $spazio;

    $spazio->nuovo(1, 'Sala Conferenze', 'Una grande sala per conferenze', 'Conferenza', 20);
    $res = $disponibilita->nuovo(1, 'Gennaio', 'Lunedì', '09:00:00', '17:00:00');
    $disponibilita->elimina(1, 'Gennaio', 'Lunedì');
    $spazio->elimina(1);

    return $res !== false;
}

function modifica_disponibilita() {
    global $disponibilita, $spazio;

    $spazio->nuovo(1, 'Sala Conferenze', 'Una grande sala per conferenze', 'Conferenza', 20);
    $disponibilita->nuovo(1, 'Gennaio', 'Lunedì', '09:00:00', '17:00:00');

    $res = $disponibilita->modifica(1, 'Febbraio', 'Martedì', '10:00:00', '18:00:00');

    $disponibilita->elimina(1, 'Gennaio', 'Lunedì');
    $disponibilita->elimina(1, 'Febbraio', 'Martedì');
    $spazio->elimina(1);

    return $res !== false;
}

function elimina_disponibilita() {
    global $disponibilita, $spazio;

    $spazio->nuovo(1, 'Sala Conferenze', 'Una grande sala per conferenze', 'Conferenza', 20);
    $disponibilita->nuovo(1, 'Gennaio', 'Lunedì', '09:00:00', '17:00:00');

    $result = $disponibilita->elimina(1, 'Gennaio', 'Lunedì');

    $spazio->elimina(1);

    return $result !== false;
}

function prendi_disponibilita() {
    global $disponibilita, $spazio;

    $spazio->nuovo(1, 'Sala Conferenze', 'Una grande sala per conferenze', 'Conferenza', 20);
    $disponibilita->nuovo(1, 'Gennaio', 'Lunedì', '09:00:00', '17:00:00');

    $disponibilitaList = $disponibilita->prendi(1);
    
    $disponibilita->elimina(1, 'Gennaio', 'Lunedì');
    $spazio->elimina(1);

    return is_array($disponibilitaList) && count($disponibilitaList) > 0;
}

function prendi_per_giorno_disponibilita() {
    global $disponibilita, $spazio;

    $spazio->nuovo(1, 'Sala Conferenze', 'Una grande sala per conferenze', 'Conferenza', 20);
    $disponibilita->nuovo(1, 'Gennaio', 'Lunedì', '09:00:00', '17:00:00');

    $disponibilitaList = $disponibilita->prendi_per_giorno(1);
    
    $disponibilita->elimina(1, 'Gennaio', 'Lunedì');
    $spazio->elimina(1);

    return is_array($disponibilitaList) && count($disponibilitaList) > 0;
}