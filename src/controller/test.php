<?php

$project_root = dirname(__FILE__, 2);
include_once 'endpoint.php';
include_once $project_root . '/page/testPage.php';
include_once $project_root . '/test/export.php';

class Test extends Endpoint
{
    public function __construct()
    {
        parent::__construct('test', 'GET');
    }

    public function handle()
    {
        global $tests;

        $testHtml = new TestPage();
        $testHtml->addTests($tests);
        echo $testHtml->render('');
    }
}
