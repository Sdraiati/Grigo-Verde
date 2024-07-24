<?php

$project_root = dirname(__FILE__, 2);
require_once $project_root . '/model/database.php';
require_once $project_root . '/model/immagine.php';
require_once $project_root . '/model/spazio.php';

$spazio = new Spazio();
$immagine = new Immagine();

$dato = file_get_contents('test/image.png');
$alt = 'Esempio di immagine';
$mime_type = 'image/png';
$posizione_spazio = 1;


function nuova_immagine()
{
    global $immagine, $dato, $alt, $mime_type, $posizione_spazio, $spazio;

    $spazio->nuovo($posizione_spazio, 'Sala Conferenze', 'Una grande sala per conferenze', 'Conferenza', 20);
    return $immagine->nuovo($dato, $alt, $mime_type, $posizione_spazio);
}

function modifica_immagine()
{
    global $immagine, $posizione_spazio;
    $new_dato = file_get_contents('test/new_image.png');
    $new_alt = 'Nuovo esempio di immagine';
    $new_mime_type = 'image/png';
    return $immagine->modifica(1, $new_dato, $new_alt, $new_mime_type, $posizione_spazio);
}

function elimina_immagine()
{
    global $immagine, $spazio, $posizione_spazio;
    $res = $immagine->elimina($posizione_spazio);
    $spazio->elimina($posizione_spazio);
    return $res;
}

function prendi_immagine()
{
    global $immagine, $dato, $alt, $mime_type, $posizione_spazio, $spazio;
    $spazio->nuovo($posizione_spazio, 'Sala Conferenze', 'Una grande sala per conferenze', 'Conferenza', 20);
    $immagine->nuovo($dato, $alt, $mime_type, $posizione_spazio);
    $img = $immagine->prendi(1);

    if (!$img || $img['Alt'] != $alt || $img['Mime_type'] != $mime_type || $img['Spazio'] != $posizione_spazio) {
        return False;
    }

    $immagine->elimina(1);
    return True;
}
