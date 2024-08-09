<?php
$project_root = dirname(__FILE__, 2);
require_once 'endpoint.php';
include_once $project_root . '/model/database.php';
include_once $project_root . '/page/visualizzazioneSpaziPage.php';

class SpazioEndpoint extends Endpoint
{

    public function __construct()
    {
        parent::__construct('spazi', 'GET');
    }

    public function handle()
    {
        $tipo = "";
        $data_inizio = "";
        $data_fine = "";

        if (isset($_GET['tipo'])) {
            $tipo = $_GET['tipo'];
        }
        if (isset($_GET['data']) && isset($_GET['orario_inizio']) && isset($_GET['orario_fine'])) {
            $data = $_GET['data'];
            $start = $_GET['orario_inizio'];
            $end = $_GET['orario_fine'];

            if ($data != "" && $start != "" && $end != "") {
                $data_inizio = $data . " " . $start . "";
                $data_fine = $data . " " . $end. "";
            } else {
                $error = "I campi data devono essere tutti selezionati";
            }
        }

        // DEBUG
        // var_dump($tipo);
        var_dump($data_inizio);
        var_dump($data_fine);

        // capire cosa fare con {{ error }}
        // questo in quanto nel caso in cuoi non vengano inserite sia data di inizio
        // che data di fine bisogerebbe ritornare un messaggio di errore ù// nel quale si ottiene all'utente di
        // scegliere tutte e due le date. 
        $page = new VisualizzazioneSpaziPage($tipo, $data_inizio, $data_fine);
        $page->setPath('spazi');
        $content = $page->render();
        echo $content;
    }

    public function match($path, $method): bool
    {
        $path = explode('?', $path)[0];
        return parent::match($path, $method);
    }
}

