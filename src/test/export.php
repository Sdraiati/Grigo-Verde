<?php

require_once 'test_immagine.php';

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
];
