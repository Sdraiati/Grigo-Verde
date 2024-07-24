<?php

require_once 'test_immagine.php';
require_once 'test_spazio.php';
require_once 'test_utente.php';

$tests = [
    'Nuovo Spazio' => function () {
        return nuovo_spazio();
    },
    'Modifica Spazio' => function () {
        return modifica_spazio();
    },
    'Elimina Spazio' => function () {
        return elimina_spazio();
    },
    'Prendi Spazio' => function () {
        return prendi_spazio();
    },
    'Immagine Nuova' => function () {
        return nuova_immagine();
    },
    'Immagine Modifica' => function () {
        return modifica_immagine();
    },
    'Immagine Elimina' => function () {
        return elimina_immagine();
    },
    'Immagine Prendi' => function () {
        return prendi_immagine();
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
    'Prendi Utente' => function () {
        return prendi_utente();
    },
];
