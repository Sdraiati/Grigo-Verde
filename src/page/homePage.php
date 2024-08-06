<?php

include_once 'page.php';

class HomePage extends Page
{
    protected $title = '<span lang="en">Home</span>';
    protected $keywords = [];
    protected $path = '/';

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
        $content = str_replace("{{ content }}", $this->getContent('home'), $content);
        $content = str_replace("{{ error }}", '', $content);
        return $content;
    }
}
