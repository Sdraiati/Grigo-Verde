<?php
$project_root = dirname(__FILE__, 2);
require_once $project_root . '/model/database.php';
require_once $project_root . '/controller/autenticazione.php';

function test_login() : bool
{
    session_reset();
    $username = 'user';
    $password = 'user';

    $result = Autenticazione::login($username, $password);

    $pass = false;
    if ($result && $_SESSION['username'] == $username) {
        $pass = true;
    }
    Autenticazione::logout();
    return $pass;
}

function test_logout() : bool
{
    session_reset();
    $username = 'user';
    $password = 'user';

    Autenticazione::login($username, $password);
    Autenticazione::logout();

    $pass = false;
    if (!isset($_SESSION['username'])) {
        $pass = true;
    }
    Autenticazione::logout();
    return $pass;
}

function test_isLogged() : bool
{
    session_reset();
    $username = 'user';
    $password = 'user';

    Autenticazione::login($username, $password);

    $pass = false;
    if (Autenticazione::isLogged()) {
        $pass = true;
    }
    Autenticazione::logout();
    return $pass;
}

function test_getLoggedUser() : bool
{
    session_reset();
    $username = 'user';
    $password = 'user';

    Autenticazione::login($username, $password);

    $pass = false;
    if (Autenticazione::getLoggedUser() == $username) {
        $pass = true;
    }
    Autenticazione::logout();
    return $pass;
}