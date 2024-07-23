<?php
require_once 'database.php';
require_once 'immagine.php';


$immagine = new Immagine(Database::getInstance());


$dato = file_get_contents('image.png');
$alt = 'Esempio di immagine';
$mime_type = 'image/png';
$posizione_spazio = 1;


echo "Test creazione immagine:\n";
if ($immagine->nuovo($dato, $alt, $mime_type, $posizione_spazio)) {
    echo "Immagine creata con successo.\n";
} else {
    echo "Errore nella creazione dell'immagine.\n";
}


echo "\nTest modifica immagine:\n";
$new_dato = file_get_contents('new_image.png');
$new_alt = 'Nuovo esempio di immagine';
$new_mime_type = 'image/png';
if ($immagine->modifica(1, $new_dato, $new_alt, $new_mime_type, $posizione_spazio)) {
    echo "Immagine modificata con successo.\n";
} else {
    echo "Errore nella modifica dell'immagine.\n";
}


echo "\nTest eliminazione immagine:\n";
if ($immagine->elimina(1)) {
    echo "Immagine eliminata con successo.\n";
} else {
    echo "Errore nell'eliminazione dell'immagine.\n";
}