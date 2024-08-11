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
        echo "ciao mondo";

        $ruolo = "";
        $nome = "";
        $cognome = "";
        $nome_utente = "";

        if (isset($_GET['ruolo'])) {
            $ruolo = $_GET['tipo'];
        }
        if (isset($_GET['nome'])) {
            $ruolo = $_GET['nome'];
        }
        if (isset($_GET['cognome'])) {
            $ruolo = $_GET['cognome'];
        } 
        if (isset($_GET['nome-utente'])) {
            $ruolo = $_GET['nome-utente'];
        }

        // $page = new VisualizzazioneSpaziPage($tipo, $data_inizio, $data_fine, $error);
        // $page->setPath('spazi');
        // echo $page->render();
    }

    public function match($path, $method): bool
    {
        $path = explode('?', $path)[0];
        return parent::match($path, $method);
    }
}
