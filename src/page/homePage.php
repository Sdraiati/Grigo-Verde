<?php

include_once 'page.php';

class HomePage extends Page
{
    public function __construct()
    {
        parent::__construct();
        $this->setTitle('<span lang="en">Home</span>');
        $this->setBreadcrumb([]);
        $this->addKeywords([]);
        $this->setPath('');
    }

    public function render()
    {
        $content = parent::render();
        $content = str_replace("{{ content }}", $this->getContent('home'), $content);
        $content = str_replace("{{ error }}", '', $content);
        return $content;
    }
}
