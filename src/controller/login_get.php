<?php
require_once 'endpoint.php';
require_once 'autenticazione.php';
require_once 'message.php';
$project_root = dirname(__FILE__, 2);
require_once $project_root . '/page/loginPage.php';

class LoginGet extends Endpoint
{
    public function __construct()
    {
        parent::__construct('login', 'GET');
    }

    public function handle(): void
    {
        Message::setRedirect();

        $page = new LoginPage();
        $page->setPath('login');
        echo $page->render();
    }
}
