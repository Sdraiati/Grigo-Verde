<?php

include_once 'model/immagine.php';
include_once 'model/spazio.php';
include_once 'model/utente.php';

define('BASE_URL', '/');
define('DB_HOST', 'db');
define('DB_NAME', 'scaregna');
define('DB_USER', 'root');
define('DB_PASS', 'root');

$IMMAGINE = new Immagine();
$SPAZIO = new Spazio();
$UTENTE = new Utente();
