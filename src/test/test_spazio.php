<?php
$project_root = dirname(__FILE__, 2);
require_once $project_root . '/model/database.php';
require_once $project_root . '/model/spazio.php';


$spazio = new Spazio();

function nuovo_spazio()
{
    global $spazio;
    $res = $spazio->nuovo(1, 'Sala Conferenze', 'Una grande sala per conferenze', 'Conferenza', 20);
    $spazio->elimina(1);
    return $res;
}

function modifica_spazio()
{
    global $spazio;
    $spazio->nuovo(1, 'Sala Conferenze', 'Una grande sala per conferenze', 'Conferenza', 20);
    $res = $spazio->modifica(1, 'Sala Riunioni', 'Una sala per riunioni', 'Riunione', 15);
    $spazio->elimina(1);
    return $res;
}

function elimina_spazio()
{
    global $spazio;
    $spazio->nuovo(1, 'Sala Conferenze', 'Una grande sala per conferenze', 'Conferenza', 20);
    return $spazio->elimina(1);
}

function prendi_spazio()
{
    global $spazio;
    $spazio->nuovo(1, 'Sala Conferenze', 'Una grande sala per conferenze', 'Conferenza', 20);
    $aula = $spazio->prendi(1);
    if (!$aula || $aula['Nome'] != 'Sala Conferenze' || $aula['Descrizione'] != 'Una grande sala per conferenze' || $aula['Tipo'] != 'Conferenza' || $aula['N_tavoli'] != 20) {
        return False;
    }
    $spazio->elimina(1);
    return True;
}
