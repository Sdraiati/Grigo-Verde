<?php

include_once 'page.php';

class AboutUsPage extends Page
{
    public function __construct()
    {
        parent::__construct();
        $this->setTitle('<span lang="en">About Us</span>');
        $this->setBreadcrumb([
            '<span lang="en">Home</span>' => '',
        ]);
        $this->addKeywords(["Pordenone, agenda 2030, WWF, progetto, CAD"]);
        $this->setPath('/about_us');
    }

    public function render()
    {
        $content = parent::render();
        $content = str_replace("{{ content }}", $this->getContent('about_us'), $content);
        $content = str_replace("{{ error }}", '', $content);
        return $content;
    }
}
