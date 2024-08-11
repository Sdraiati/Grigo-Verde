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
        $error = "";

        if (isset($_GET['tipo'])) {
            $tipo = $_GET['tipo'];
        }
        if (isset($_GET['data']) && isset($_GET['orario_inizio']) && isset($_GET['orario_fine'])) {
            $data = $_GET['data'];
            $start = $_GET['orario_inizio'];
            $end = $_GET['orario_fine'];

            $data_inizio = $data . " " . $start . "";
            $data_fine = $data . " " . $end. "";
        }

        $page = new VisualizzazioneSpaziPage($tipo, $data_inizio, $data_fine, $error);
        $page->setPath('spazi');
        echo $page->render();
    }

    public function match($path, $method): bool
    {
        $path = explode('?', $path)[0];
        return parent::match($path, $method);
    }
}
