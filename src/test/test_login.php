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
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    $username = 'user';
    $password = 'user';

    $login_success = Autenticazione::login($username, $password);
    if (!$login_success) {
        return false;
    }

    Autenticazione::logout();
    $pass = !isset($_SESSION['username']);

    if (session_status() !== PHP_SESSION_NONE) {
        session_destroy();
        $_SESSION = array();
    }

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

function test_is_amministratore(): bool
{
    session_reset();
    $username = 'admin';
    $password = 'admin';

    Autenticazione::login($username, $password);
    $pass = false;
    if (Autenticazione::is_amministratore()) {
        $pass = true;
    }
    Autenticazione::logout();
    return $pass;
}