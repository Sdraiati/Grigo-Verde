<?php

include_once 'page.php';

class AboutUsPage extends Page
{
    private $title = '<span lang="en">About Us</span>';
    private $keywords = [""];
    private $path = '/about_us';
    public function __construct()
    {
        parent::__construct();
        $this->setTitle($this->title);
        $this->setBreadcrumb([]);
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
