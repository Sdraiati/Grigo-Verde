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
        $data = "";
        if (isset($_GET['tipo'])) {
            $tipo = $_GET['tipo'];
        }
        if (isset($_GET['data'])) {
            $data = $_GET['data'];
        }
        $page = new VisualizzazioneSpaziPage($tipo, $data);
        $content = $page->render();
        echo $content;
        // echo "ciao mondo";
    }

    public function match($path, $method): bool
    {
        $path = explode('?', $path)[0];
        return parent::match($path, $method);
    }
}