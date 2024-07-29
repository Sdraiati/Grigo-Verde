<?php
include_once 'page.php';
$project_root = dirname(__FILE__, 2);
include_once 'controller/login.php';
class loginPage extends Page
{
    private string $username ='';
    private string $password='';
    private string $error='';
    public function __construct(string $username="", string $password="", string $error="")
    {
        parent::setTitle('Login');
        parent::setNav([]);
        parent::setBreadcrumb([
            'Home' => '',
        ]);

        $this->username = $username;
        $this->password = $password;
        $this->error = $error;
    }

    public function render()
    {
        $content = parent::render();
        $content = str_replace("{{ content }}", $this->getContent('login'), $content);
        if($this->username != '')
        {
            $content = str_replace("{{ username }}", $this->username, $content);
            $content = str_replace("{{ password }}", $this->password, $content);
            $content = str_replace("{{ error }}", parent::error($this->error), $content);
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