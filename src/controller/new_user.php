<?php
require_once 'endpoint.php';
$project_root = dirname(__FILE__, 2);
include_once $project_root . '/model/utente.php';
require_once 'message.php';


class NewUser extends Endpoint
{
    private string $username = '';
    private string $password = '';
    private string $nome = '';
    private string $cognome = '';
    private string $ruolo = '';

    public function __construct()
    {
        parent::__construct('utenti/nuovo', 'POST');
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

    public function render_page_error($message): void
    {
        $page = new NewUserPage(
            $this->username,
            $this->password,
            $this->nome,
            $this->cognome,
            $this->ruolo,
            $message
        );
        $page->setPath('utenti/nuovo');
        echo $page->render();
    }

    public function handle(): void
    {
        $this->username = $this->post('username');
        $this->password = $this->post('password');
        $this->nome = $this->post('nome');
        $this->cognome = $this->post('cognome');
        $this->ruolo = $this->post('ruolo');

        if (!$this->validate($this->username, $this->password, $this->nome, $this->cognome, $this->ruolo)) {
            $this->render_page_error("Inserire tutti i campi");
            return;
        }
        $utente = new Utente();
        if ($utente->prendi($this->username) !== null) {
            $this->render_page_error("<span lang='en'>Username</span> già esistente, sceglierne un altro");
            return;
        }
        $utente->nuovo($this->username, $this->nome, $this->cognome, $this->ruolo, $this->password);
        Message::set("Utente creato con successo");
        $this->redirect('utenti');
    }
}
