<?php
session_start();

include_once 'controller/routes.php';
include_once 'controller/autenticazione.php';
include_once 'page/resource_not_found.php';

Autenticazione::session_by_cookie();

$uri = strtok($_SERVER['REQUEST_URI'], '?');
if ($router->match($uri, $_SERVER['REQUEST_METHOD'])) {
    $router->handle($uri, $_SERVER['REQUEST_METHOD']);
} else {
    $page = new ResourceNotFoundPage();
    $page->setPath($uri);
    echo $page->render();
}
