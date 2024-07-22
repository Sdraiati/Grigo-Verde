<?php

include_once 'page.php';

class AboutUsPage extends Page
{
    public function __construct()
    {
        parent::setTitle('About Us');
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
        $content = str_replace("{{ content }}", $this->getContent('about_us'), $content);
        return $content;
    }
}
