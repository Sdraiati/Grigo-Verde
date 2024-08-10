<?php
require_once 'endpoint.php';
$project_root = dirname(__FILE__, 2);
include_once $project_root . '/model/utente.php';

class DeleteUser extends Endpoint
{
    private string $username = '';

    public function __construct()
    {
        parent::__construct('utenti/elimina', 'GET');
    }

    public function validate($username): bool
    {
        if (empty($username)) {
            return false;
        }
        $this->username = $username;
        return true;
    }

    public function handle(): void
    {
        $this->username = $this->get('username');

        if (!$this->validate($this->username)) {
            echo "Errore nella richiesta";
        } else {
            $utente = new Utente();
            if ($utente->prendi($this->username) === null) {
                echo "Utente non esistente";
            } else {
                $utente->elimina($this->username);
                echo "Utente eliminato";
            }
        }
    }
}