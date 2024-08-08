<?php
session_start();

include_once 'controller/routes.php';
include_once 'controller/autenticazione.php';

Autenticazione::session_by_cookie();

$uri = strtok($_SERVER['REQUEST_URI'], '?');
if ($router->match($uri, $_SERVER['REQUEST_METHOD'])) {
    $router->handle($uri, $_SERVER['REQUEST_METHOD']);
} else {
    // TODO: define a 404 page
    echo '404';
    echo $_SERVER['REQUEST_URI'];
}
