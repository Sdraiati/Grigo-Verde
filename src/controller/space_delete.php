<?php
require_once 'endpoint.php';
require_once 'message.php';
require_once 'autenticazione.php';
$project_root = dirname(__FILE__, 2);
include_once $project_root . '/model/spazio.php';
include_once $project_root . '/page/unauthorized.php';

class DeleteUser extends Endpoint
{
    private string $posizione;

    public function __construct()
    {
        parent::__construct('spazi/elimina', 'POST');
    }

    public function validate(): bool
    {
        try {
            $this->posizione = $this->post('posizione');
        } catch (Exception $e) {
            return false;
        }
        return true;
    }

    public function handle(): void
    {
        if (!$this->validate() || !Autenticazione::is_amministratore()) {
            $page = new UnauthorizedPage();
            $page->setPath($this->path);
            $page->render();
        }

        $spazio = new Spazio();

        try {
            $spazio->elimina($this->posizione);
        } catch (Exception $e) {
        }
        $this->redirect('cruscotto');
    }
}
