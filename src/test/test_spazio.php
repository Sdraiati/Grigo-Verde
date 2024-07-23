<?php
require_once 'database.php';
require_once 'spazio.php';


$spazio = new Spazio(Database::getInstance());

echo "Test creazione spazio:\n";
if ($spazio->nuovo(1, 'Sala Conferenze', 'Una grande sala per conferenze', 'Conferenza', 20)) {
    echo "Spazio creato con successo.\n";
} else {
    echo "Errore nella creazione dello spazio.\n";
}


echo "\nTest modifica spazio:\n";
if ($spazio->modifica(1, 'Sala Riunioni', 'Una sala per riunioni', 'Riunione', 15)) {
    echo "Spazio modificato con successo.\n";
} else {
    echo "Errore nella modifica dello spazio.\n";
}


echo "\nTest eliminazione spazio:\n";
if ($spazio->elimina(1)) {
    echo "Spazio eliminato con successo.\n";
} else {
    echo "Errore nell'eliminazione dello spazio.\n";
}