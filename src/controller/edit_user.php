<?php
require_once 'endpoint.php';
$project_root = dirname(__FILE__, 2);
include_once $project_root . '/model/utente.php';
require_once 'message.php';

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

    public function validate(): bool
    {
        $username = $this->post('username');
        $nome = $this->post('nome');
        $cognome = $this->post('cognome');
        $ruolo = $this->post('ruolo');

        if (empty($username) || empty($nome) || empty($cognome) || empty($ruolo)) {
            return false;
        }

        try {
            $password = $this->post('password');
        } catch (Exception $e) {
            $password = '';
        }

        $this->username = $username;
        $this->password = $password;
        $this->nome = $nome;
        $this->cognome = $cognome;
        $this->ruolo = $ruolo;
        return true;
    }

    public function render_edit_page_with_error($error)
    {
        $page = new EditUserPage(
            $this->username,
            $this->nome,
            $this->cognome,
            $this->ruolo,
            $error
        );

        $page->setPath("utenti/modifica");
        echo $page->render();
    }

    public function handle(): void
    {
        if (!$this->validate($this->username, $this->password, $this->nome, $this->cognome, $this->ruolo)) {
            $this->render_edit_page_with_error("Inserire tutti i campi");
            return;
        }
        $utente = new Utente();
        if ($utente->prendi($this->username) === null) {
            $this->render_edit_page_with_error("Utente non esistente");
            return;
        }
        //if password is epmpty don't update it
        if ($this->password === '') {
            $this->password = $utente->prendi($this->username)['Password'];
        }

        $utente->modifica($this->username, $this->nome, $this->cognome, $this->ruolo, $this->password);
        Message::set("Utente modificato con successo");
        $this->redirect('dashboard');
    }
}

