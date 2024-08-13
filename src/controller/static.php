<?php

$project_root = dirname(__FILE__, 2);
include_once 'endpoint.php';

class StaticPage extends Endpoint
{
    private $page;

    public function __construct($path, $page)
    {
        parent::__construct($path, 'GET');
        $page->setPath($path);
        $this->page = $page;
    }

    public function handle()
    {
        echo $this->page->render();
    }
}
