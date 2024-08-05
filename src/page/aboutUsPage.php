<?php

include_once 'page.php';

class AboutUsPage extends Page
{
    public $title = 'About Us';
    public $nav = [
        '<span lang="en">Home</span>' => '',
        '<span lang="en">Login</span>' => 'login'
    ];
    public $keywords = [""];
    public $path = '/about_us';
    public function __construct()
    {
        $this->setTitle($this->title);
        $this->setNav($this->nav);
        $this->setBreadcrumb([], true);
        $this->addKeywords($this->keywords);
        $this->setPath($this->path);
    }

    public function render()
    {
        $content = parent::render();
        $content = str_replace("{{ content }}", $this->getContent('about_us'), $content);
        $content = str_replace("{{ error }}", '', $content);
        return $content;
    }
}
