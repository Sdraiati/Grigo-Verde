<?php
require_once 'endpoint.php';
require_once 'autenticazione.php';
include_once 'model/utente.php';

class Dashboard extends Endpoint
{
    private string $nome = "";
    public function __construct()
    {
        parent::__construct('dashboard', 'GET');
    }

    public function validate(): bool
    {
        $auth = new Autenticazione();
        if ($auth->isLogged()) {
            $this->nome = $_SESSION['username'];
            return true;
        }
        return false;
    }


    public function handle(): void
    {
        $auth = new Autenticazione();
        if ($this->validate()) {
            if (!$auth->is_amministratore()) {
                $page = new DashboardDocentePage($this->nome);
                $page->setPath("dashboard");
                echo $page->render();
                return;
            }
            $page = new DashboardAmministratorePage($this->nome);
            $page->setPath("dashboard");
            echo $page->render();
            return;
        } else {
            header("Location: /login");
        }
    }
}
