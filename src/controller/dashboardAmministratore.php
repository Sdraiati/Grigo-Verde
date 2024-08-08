<?php
require_once 'endpoint.php';
require_once 'autenticazione.php';
include_once 'model/utente.php';

class DashboardAmministratore extends Endpoint
{
    private string $amministratore_nome = "";
    public function __construct()
    {
        parent::__construct('dashboard/amministratore', 'GET');
    }

    public function validate(): bool
    {
        $username = $_COOKIE['LogIn']??null;
        $user = new Utente();
        $user = $user->prendi($username);
        if(isset($user['Ruolo']) && $user['Ruolo'] == 'Amministratore')
        {
            $this->amministratore_nome = $username;
            return true;
        }
        return false;
    }


    public function handle(): void
    {
        if($this->validate())
        {
            $page = new DashboardAmministratorePage($this->amministratore_nome);
            $page->setPath("dashboard/amministratore");
            echo $page->render();
        }
        else{
            header("Location: /login");
        }
    }
}
