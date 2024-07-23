<?php

require_once 'test_immagine.php';
require_once 'test_spazio.php';
require_once 'test_utente.php';

$tests = [
    'Immagine Nuova' => function () {
        return test_nuova_immagine();
    },
    'Immagine Modifica' => function () {
        return test_modifica_immagine();
    },
    'Immagine Elimina' => function () {
        return test_elimina_immagine();
    },
    'Nuovo Utente' => function () {
        return nuovo_utente();
    },
    'Modifica Utente' => function () {
        return modifica_utente();
    },
    'Elimina Utente' => function () {
        return elimina_utente();
    },
    'Nuovo Spazio' => function () {
        return nuovo_spazio();
    },
    'Modifica Spazio' => function () {
        return modifica_spazio();
    },
    'Elimina Spazio' => function () {
        return elimina_spazio();
    },
];
