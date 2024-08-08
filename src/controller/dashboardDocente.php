<?php
require_once 'endpoint.php';
require_once 'autenticazione.php';
require_once 'model/utente';

class DashboardDocente extends Endpoint
{
    private string $docente_nome = "";
    public function __construct()
    {
        parent::__construct('dashboard/docente', 'GET');
    }

    public function validate(): bool
    {
        if(isset($_COOKIE['LogIn']))
        {
            $user = new Utente();
            
            //controllo se l'username appartiene ad un utente docente
            $this->docente_nome = $_COOKIE['LogIn'];
            return true;
        }
        else{
            return false;
        }
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
