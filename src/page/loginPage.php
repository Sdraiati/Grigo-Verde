<?php
include_once 'page.php';
$project_root = dirname(__FILE__, 2);
class loginPage extends Page
{
    protected string $username = '';
    protected string $password = '';
    protected string $error = '';
    public function __construct(string $username = "", string $password = "", string $error = "")
    {
        parent::__construct();
        $this->setTitle('<span lang="en">Login</span>');
        $this->setBreadcrumb([
            '<span lang="en">Home</span>' => '',
        ]);
        $this->addKeywords([""]);
        $this->setPath('login');

        $this->username = $username;
        $this->password = $password;
        $this->error = $error;
    }

    public function render()
    {
        $content = parent::render();
        $content = str_replace("{{ content }}", $this->getContent('login'), $content);
        if ($this->username != '' || $this->password != '' || $this->error != '') {
            $content = str_replace("{{ username }}", $this->username, $content);
            $content = str_replace("{{ password }}", $this->password, $content);
            $content = str_replace("{{ error }}", parent::error($this->error), $content);
        } else {
            $content = str_replace("{{ username }}", '', $content);
            $content = str_replace("{{ password }}", '', $content);
            $content = str_replace("{{ error }}", '', $content);
        }
        return $content;
    }
}
