<?php

$project_root = dirname(__FILE__, 2);
include_once 'endpoint.php';
include_once $project_root . '/page/testPage.php';
include_once $project_root . '/test/export.php';
include_once $project_root . '/model/init_db.php';

class Test extends Endpoint
{
    public function __construct()
    {
        parent::__construct('test', 'GET');
    }

    public function handle()
    {
        global $tests;
        reset_db();
        $testHtml = new TestPage();
        $testHtml->setPath('test');
        $testHtml->addTests($tests);
        echo $testHtml->render('');
    }
}
