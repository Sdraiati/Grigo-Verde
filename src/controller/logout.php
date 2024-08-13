<?php
require_once 'endpoint.php';
require_once 'autenticazione.php';
require_once 'message.php';

class Logout extends Endpoint
{
    public function __construct()
    {
        parent::__construct('logout', 'GET');
    }

    public function handle(): void
    {
        Autenticazione::logout();
        Message::set("<span lang='en'>Logout</span> effettuato con successo");
        $this->redirect('');
    }
}

