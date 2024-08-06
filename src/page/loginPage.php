<?php
include_once 'page.php';
$project_root = dirname(__FILE__, 2);
include_once 'controller/login.php';
class loginPage extends Page
{
    private $title = '<span lang="en">Login</span>';
    private $keywords = [""];
    private $path = '/login';
    private string $username ='';
    private string $password='';
    private string $error='';
    public function __construct(string $username="", string $password="", string $error="")
    {
        parent::__construct();
        $this->setTitle($this->title);
        $this->setBreadcrumb([]);
        $this->addKeywords($this->keywords);
        $this->setPath($this->path);

        $this->username = $username;
        $this->password = $password;
        $this->error = $error;
    }

    public function render()
    {
        $content = parent::render();
        $content = str_replace("{{ content }}", $this->getContent('login'), $content);
        if($this->username != '' || $this->password != '' || $this->error != '')
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