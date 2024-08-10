<?php
require_once 'endpoint.php';
$project_root = dirname(__FILE__, 2);
include_once $project_root . '/model/utente.php';
include_once 'autenticazione.php';
require_once 'message.php';


class EditPassword extends Endpoint
{
    public function __construct()
    {
        parent::__construct('dashboard/modifica-password', 'POST');
    }

    public function validate($password): bool
    {
        return !empty($password);
    }

    public function handle(): void
    {
        $username = Autenticazione::getLoggedUser();
        $password = $this->post('password');

        if (!$this->validate($password)) {
            $page = new EditPasswordPage("Inserire la nuova password");
            echo $page->render();
        }

        $utente = new Utente();
        $utente->modifica_password($username, $password);
        Message::set("Password modificata con successo");
        $this->redirect('dashboard');
    }
}

