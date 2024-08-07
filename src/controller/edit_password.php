<?php
require_once 'endpoint.php';
$project_root = dirname(__FILE__, 2);
include_once $project_root . '/model/utente.php';

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
        $username = $_SESSION['username'];
        $password = $this->post('password');

        if (!$this->validate($password)) {
            $page = new EditPasswordPage("Inserire la nuova password");
            echo $page->render();
        } else {
            $utente = new Utente();
            $utente->modifica_password($username, $password);
            echo "Password modificata con successo";
        }
    }
}