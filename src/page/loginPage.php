<?php
include_once 'page.php';

class loginPage extends Page
{
    public $username = '';
    public $password = '';
    public $error = '';

    public function __construct()
    {
        parent::setTitle('Login');
        parent::setNav([
            'Home' => '',
        ]);
        parent::setBreadcrumb([
            'Home' => '',
        ]);
    }

    public function render()
    {
        $content = parent::render();
        $content = str_replace("{{ content }}", $this->getContent('login'), $content);
        if($this->username != '')
        {
            $content = str_replace("{{ username }}", $this->username, $content);
            $content = str_replace("{{ password }}", $this->password, $content);
            $content = str_replace("<!-- {{ error }} -->", parent::error($this->error), $content);
        }
        else
        {
            $content = str_replace("{{ username }}", '', $content);
            $content = str_replace("{{ password }}", '', $content);
            $content = str_replace("<!-- {{ error }} -->", '', $content);
        }
        return $content;
    }

    public function precompila($username, $password, $error)
    {
        $this->username = $username;
        $this->password = $password;
        $this->error = $error;
    }
}