<?php
require_once 'endpoint.php';
$project_root = dirname(__FILE__, 2);
include_once $project_root . '/model/utente.php';

class DeleteUser extends Endpoint
{
    private string $username = '';

    public function __construct()
    {
        parent::__construct('utenti/elimina', 'POST');
    }

    public function validate(): bool
    {
        try {
            $this->username = $this->post('username');
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    public function handle(): void
    {
        if (!$this->validate() || !Autenticazione::is_amministratore()) {
            $page = new UnauthorizedPage();
            $page->setPath($this->path);
            $page->render();
        }

        $utente = new Utente();
        try {
            $utente->elimina($this->username);
        } catch (Exception $e) {
        }
        $this->redirect('cruscotto');
    }
}
