<?php
include_once 'page.php';
$project_root = dirname(__FILE__, 2);
include_once 'controller/login.php';
class loginPage extends Page
{
    private Login $login;
    public function __construct(Login $login)
    {
        parent::setTitle('Login');
        parent::setNav([
            'Home' => '',
        ]);
        parent::setBreadcrumb([
            'Home' => '',
        ]);

        $this->login = $login;
    }

    public function render()
    {
        $content = parent::render();
        $content = str_replace("{{ content }}", $this->getContent('login'), $content);
        if($this->login->username != '')
        {
            $content = str_replace("{{ username }}", $this->login->username, $content);
            $content = str_replace("{{ password }}", $this->login->password, $content);
            $content = str_replace("{{ error }}", parent::error($this->login->error), $content);
        }
        else
        {
            $content = str_replace("{{ username }}", '', $content);
            $content = str_replace("{{ password }}", '', $content);
            $content = str_replace("{{ error }}", '', $content);
        }
        return $content;
    }
}