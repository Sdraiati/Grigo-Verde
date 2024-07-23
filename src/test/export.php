<?php

require_once 'test_immagine.php';

$tests = [
    'Immagine Nuova' => test_nuovo_immagine(),
    'Test 2' => function () {
        return false;
    },
    'Test 3' => function () {
        return true;
    },
];
