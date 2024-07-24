<?php

class Login extends Endpoint
{
    public function __construct()
    {
        parent::__construct('/login', 'POST');
    }

    public function handle()
    {
        $username = $this->post('username');
        $password = $this->post('password');

        if (Autenticazione::login($username, $password)) {
            echo "Login effettuato";
        } else {
            echo "Login fallito";
        }
        return;
    }
}
