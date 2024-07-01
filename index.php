<?php

include_once 'controller/routes.php';

session_start();

$logged = isset($_COOKIE["LogIn"]);
if ($logged) {
    $_SESSION["LogIn"] = $_COOKIE["LogIn"];
}

if ($router->match($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD'])) {
    $router->handle($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);
} else {
    // TODO: define a 404 page
    echo '404';
    echo $_SERVER['REQUEST_URI'];
}
