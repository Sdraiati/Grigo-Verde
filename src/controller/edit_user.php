<?php
require_once 'endpoint.php';
$project_root = dirname(__FILE__, 2);
include_once $project_root . '/model/utente.php';

class EditUser extends Endpoint
{
    private string $username = '';
    private string $password = '';
    private string $nome = '';
    private string $cognome = '';
    private string $ruolo = '';

    public function __construct()
    {
        parent::__construct('utenti/modifica', 'POST');
    }

    public function validate($username, $password, $nome, $cognome, $ruolo): bool
    {
        if (empty($username) || empty($password) || empty($nome) || empty($cognome) || empty($ruolo)) {
            return false;
        }
        $this->username = $username;
        $this->password = $password;
        $this->nome = $nome;
        $this->cognome = $cognome;
        $this->ruolo = $ruolo;
        return true;
    }

    public function handle(): void
    {
        $this->username = $this->post('username');
        $this->password = $this->post('password');
        $this->nome = $this->post('nome');
        $this->cognome = $this->post('cognome');
        $this->ruolo = $this->post('ruolo');

        if (!$this->validate($this->username, $this->password, $this->nome, $this->cognome, $this->ruolo)) {
            $page = new EditUserPage(
                $this->username,
                $this->password,
                $this->nome,
                $this->cognome,
                $this->ruolo,
                "Inserire tutti i campi"
            );
            echo $page->render();
        } else {
            $utente = new Utente();
            if ($utente->prendi($this->username) === null) {
                $page = new EditUserPage(
                    $this->username,
                    $this->password,
                    $this->nome,
                    $this->cognome,
                    $this->ruolo,
                    "Utente non esistente"
                );
                echo $page->render();
            } else {
                $utente->modifica($this->username, $this->nome, $this->cognome, $this->ruolo, $this->password);
                echo "Utente modificato con successo";
            }
        }
    }
}