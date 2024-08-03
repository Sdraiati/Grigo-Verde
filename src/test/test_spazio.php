<?php
$project_root = dirname(__FILE__, 2);
require_once $project_root . '/model/database.php';
require_once $project_root . '/model/spazio.php';
require_once 'utils.php';


$spazio = new Spazio();

function nuovo_spazio()
{
    global $spazio;
    $res = $spazio->nuovo(1000, 'Sala Conferenze', 'Una grande sala per conferenze', 'Conferenza', 20);
    $spazio->elimina(1000);
    return $res;
}

function modifica_spazio()
{
    global $spazio;
    $spazio->nuovo(1000, 'Sala Conferenze', 'Una grande sala per conferenze', 'Conferenza', 20);
    $res = $spazio->modifica(1000, 'Sala Riunioni', 'Una sala per riunioni', 'Riunione', 15);
    $spazio->elimina(1000);
    return $res;
}

function elimina_spazio()
{
    global $spazio;
    $spazio->nuovo(1000, 'Sala Conferenze', 'Una grande sala per conferenze', 'Conferenza', 20);
    return $spazio->elimina(1000);
}

function prendi_spazio()
{
    global $spazio;
    $spazio->nuovo(1000, 'Sala Conferenze', 'Una grande sala per conferenze', 'Conferenza', 20);
    $aula = $spazio->prendi(1000);
    if (!$aula || $aula['Nome'] != 'Sala Conferenze' || $aula['Descrizione'] != 'Una grande sala per conferenze' || $aula['Tipo'] != 'Conferenza' || $aula['N_tavoli'] != 20) {
        return False;
    }
    $spazio->elimina(1000);
    return True;
}

function prendi_tutti_spazio()
{
    global $spazio;
    $s1 = ['Posizione' => 1000, 'Nome' => 'Sala Conferenze', 'Descrizione' => 'Una grande sala per conferenze', 'Tipo' => 'Conferenza', 'N_tavoli' => 20];
    $s2 = ['Posizione' => 2000, 'Nome' => 'Ping pong', 'Descrizione' => 'Un tavolo da ping pong', 'Tipo' => 'Area Ricreativa', 'N_tavoli' => 10];

    $spazio->nuovo(1000, 'Sala Conferenze', 'Una grande sala per conferenze', 'Conferenza', 20);
    $spazio->nuovo(2000, 'Ping pong', 'Un tavolo da ping pong', 'Area Ricreativa', 10);
    $spazi = $spazio->prendi_tutti();

    if (count($spazi) < 2 || !contiene($spazi, $s1) || !contiene($spazi, $s2)) {
        return False;
    }

    $spazio->elimina(1000);
    $spazio->elimina(2000);
    return True;
}
