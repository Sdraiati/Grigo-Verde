<?php
require_once 'endpoint.php';
$project_root = dirname(__FILE__, 2);
require_once $project_root . '/page/prenotazionePage.php';

class Reservations extends Endpoint
{
    public function __construct()
    {
        parent::__construct('prenotazioni', 'GET');
    }

    public function handle(): void
    {
        $page = new PrenotazionePage();
        $page->setPath('prenotazioni');
        echo $page->render();
    }
}
