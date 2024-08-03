<?php
$project_root = dirname(__FILE__, 2);
require_once 'endpoint.php';
include_once $project_root . '/model/database.php';
include_once $project_root . '/page/visualizzazioneSpaziPage.php';

class SpazioEndpoint extends Endpoint {

    public function __construct() {
        parent::__construct('spazio', 'GET');
    }

    public function handle() {
        $page = new VisualizzazioneSpaziPage($_GET['tipo'], $_GET['data']);
        $page->render();
        echo $content;
    }

    public function match($path, $method): bool
    {
        $path = explode('?', $path)[0];
        return parent::match($path, $method);
    }
}