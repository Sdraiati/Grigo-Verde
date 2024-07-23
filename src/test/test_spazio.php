<?php
$project_root = dirname(__FILE__, 2);
require_once $project_root . '/model/database.php';
require_once $project_root . '/model/spazio.php';


$spazio = new Spazio();

function nuovo_spazio() {
    global $spazio;
    if ($spazio->nuovo(1, 'Sala Conferenze', 'Una grande sala per conferenze', 'Conferenza', 20)) {
        return true;
    } else {
        return false;
    }
}

function modifica_spazio() {
    global $spazio;
    if ($spazio->modifica(1, 'Sala Riunioni', 'Una sala per riunioni', 'Riunione', 15)) {
        return true;
    } else {
        return false;
    }
}

function elimina_spazio() {
    global $spazio;
    if ($spazio->elimina(1)) {
        return true;
    } else {
        return false;
    }
}
