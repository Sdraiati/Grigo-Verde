<?php
require_once 'endpoint.php';
require_once 'autenticazione.php';
class Logout extends Endpoint
{
    public function __construct()
    {
        parent::__construct('logout', 'GET');
    }

    public function handle() : void
    {
        Autenticazione::logout();
        echo "Logout effettuato";
    }
}