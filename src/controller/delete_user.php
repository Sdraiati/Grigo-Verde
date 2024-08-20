<?php
require_once 'endpoint.php';
$project_root = dirname(__FILE__, 2);
include_once $project_root . '/model/utente.php';
require_once 'message.php';

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
        if (!Autenticazione::isLogged()) {
            $page = new LoginPage(
                "",
                "",
                'Devi effettuare il login per accedere a questa pagina'
            );
            $page->setPath("login");
            echo $page->render();
            return;
        }

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
        Message::set('Utente eliminato con successo');
        $this->redirect('utenti');
    }
}
