<?php

include_once 'endpoint.php';
include_once 'html/testHtml.php';
include_once 'html/testItem.php';

class Test extends Endpoint
{
    public function __construct()
    {
        parent::__construct('test', 'GET');
    }

    public function handle()
    {
        $tests = [
            new TestItem('Test 1', function () {
                return true;
            }),
            new TestItem('Test 2', function () {
                return false;
            }),
            new TestItem('Test 3', function () {
                return true;
            })
        ];

        $testHtml = new TestHtml($tests);
        echo $testHtml->render('');
    }
}
