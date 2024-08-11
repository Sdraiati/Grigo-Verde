<?php
$project_root = dirname(__FILE__, 2);
require_once 'endpoint.php';
include_once $project_root . '/model/database.php';
include_once $project_root . '/page/visualizzazioneUtentiPage.php';

class UtenteEndpoint extends Endpoint
{

    public function __construct()
    {
        parent::__construct('utenti', 'GET');
    }

    public function handle()
    {

        $ruolo = "";
        $username = "";
        $nome = "";
        $cognome = "";

        if (isset($_GET['Ruolo'])) {
            $ruolo = $_GET['Ruolo'];
        }
        if (isset($_GET['Nome'])) {
            $nome = $_GET['Nome'];
        }
        if (isset($_GET['Cognome'])) {
            $cognome = $_GET['Cognome'];
        } 
        if (isset($_GET['Username'])) {
            $username = $_GET['Username'];
        }

        // DEBUG
        // var_dump($ruolo);
        // var_dump($username);
        // var_dump($nome);
        // var_dump($cognome);

        $page = new VisualizzazioneUtentiPage($ruolo, $username, $nome, $cognome);
        echo $page->render();
    }

    public function match($path, $method): bool
    {
        $path = explode('?', $path)[0];
        return parent::match($path, $method);
    }
}
