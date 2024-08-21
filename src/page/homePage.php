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
        $this->setDescription("Grigo Verde, la piattaforma per prenotare gli spazi all'aperto del liceo scientifico Grigoletti di Pordenone.");
    }

    public function render()
    {
        $content = parent::render();
        $content = str_replace("{{ content }}", $this->getContent('home'), $content);
        $content = str_replace("{{ error }}", '', $content);
        return $content;
    }
}
