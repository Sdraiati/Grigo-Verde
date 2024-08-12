<?php
$project_root = dirname(__FILE__, 2);
require_once 'endpoint.php';
include_once $project_root . '/model/database.php';
include_once $project_root . '/page/visualizzazioneUtentiPage.php';

class UtenteEndpoint extends Endpoint
{

    private $ruolo;
    private $username;
    private $nome;
    private $cognome;

    public function __construct()
    {
        parent::__construct('utenti', 'GET');
    }

    public function validate()
    {
        try {
            $this->ruolo = $this->get('Ruolo');
        } catch (Exception $e) {
            $this->ruolo = "";
        }
        try {
            $this->username = $this->get('Username');
        } catch (Exception $e) {
            $this->username = "";
        }
        try {
            $this->nome = $this->get('Nome');
        } catch (Exception $e) {
            $this->nome = "";
        }
        try {
            $this->cognome = $this->get('Cognome');
        } catch (Exception $e) {
            $this->cognome = "";
        }
    }


    public function handle()
    {
        $this->validate();

        $page = new VisualizzazioneUtentiPage($this->ruolo, $this->username, $this->nome, $this->cognome);
        echo $page->render();
    }

    public function match($path, $method): bool
    {
        $path = explode('?', $path)[0];
        return parent::match($path, $method);
    }
}
