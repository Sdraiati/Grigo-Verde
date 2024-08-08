<?php
require_once 'endpoint.php';
require_once 'autenticazione.php';
include_once 'model/utente.php';

class DashboardDocente extends Endpoint
{
    private string $docente_nome = "";
    public function __construct()
    {
        parent::__construct('dashboard/docente', 'GET');
    }

    public function validate(): bool
    {
        $auth = new Autenticazione();
        if($auth->isLogged() && !$auth->is_amministratore())
        {
            $this->docente_nome = $_SESSION['username'];
            return true;
        }
        return false;
    }


    public function handle(): void
    {
        if($this->validate())
        {
            $page = new DashboardDocentePage($this->docente_nome);
            $page->setPath("dashboard/docente");
            echo $page->render();
        }
        else{
            header("Location: /login");
        }
    }
}
