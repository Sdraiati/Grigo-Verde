<?php

require_once 'test_immagine.php';
require_once 'test_spazio.php';
require_once 'test_utente.php';
require_once 'test_login.php';
require_once 'test_prenotazione.php';
require_once 'test_disponibilità.php';

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
    'Prendi Tutti Spazio' => function () {
        return prendi_tutti_spazio();
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
    'Effettua Login' => function () {
        return test_login();
    },
    'Effettua Logout' => function () {
        return test_logout();
    },
    'Controlla se loggato' => function () {
        return test_isLogged();
    },
    'Prendi utente loggato' => function () {
        return test_getLoggedUser();
    },
    'New Reservation' => function () {
        return nuovo_prenotazione();
    },
    'Modify Reservation' => function () {
        return modifica_prenotazione();
    },
    'Delete Reservation' => function () {
        return elimina_prenotazione();
    },
    'Fetch Reservation' => function () {
        return prendi_prenotazione();
    },
    'Fetch Weekly Reservations' => function () {
        return prendi_per_settimana_prenotazione();
    },
    'Nuovo Disponibilità' => function () {
        return nuovo_disponibilita();
    },
    'Modifica Disponibilità' => function () {
        return modifica_disponibilita();
    },
    'Elimina Disponibilità' => function () {
        return elimina_disponibilita();
    },
    'Prendi Disponibilità' => function () {
        return prendi_disponibilita();
    },
    'Prendi per Giorno Disponibilità' => function () {
        return prendi_per_giorno_disponibilita();
    }
];
