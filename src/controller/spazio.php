<?php
$project_root = dirname(__FILE__, 2);
require_once 'endpoint.php';
include_once $project_root . '/model/database.php';
include_once $project_root . '/page/visualizzazioneSpaziPage.php';

class SpazioEndpoint extends Endpoint {

    public function __construct() {
        parent::__construct('spazi', 'GET');
    }

    public function handle() {
        $tipo = "";
        $data_inizio = "";
        $data_fine = "";
        if (isset($_GET['tipo'])) {
            $tipo = $_GET['tipo'];
        }
        if (isset($_GET['Data_inizio']) || isset($_GET['Data_fine'])) {
            if(isset($_GET['Data_inizio']) && isset($_GET['Data_fine'])) {
                $data_inizio = $_GET['Data_inizio'];
                $data_fine = $_GET['Data_fine'];
            } 
            if ($data_inizio != "" && $data_fine != "") {
                $data_inizio = str_replace("T", " ", $data_inizio);
                $data_fine = str_replace("T", " ", $data_fine);
                $data_inizio = $data_inizio . ":00";
                $data_fine = $data_fine . ":00";
            }
        }
        // DEBUG
        // var_dump($tipo);
        

        // var_dump($data_inizio);
        // var_dump($data_fine);
        $page = new VisualizzazioneSpaziPage($tipo, $data_inizio, $data_fine);
        $content = $page->render();
        echo $content;
    }

    public function match($path, $method): bool
    {
        $path = explode('?', $path)[0];
        return parent::match($path, $method);
    }
}