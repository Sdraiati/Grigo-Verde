<?php

include_once 'page.php';

class HomePage extends Page
{
    public function __construct()
    {
        parent::setTitle('Home');
        parent::setNav([
            'About us' => 'about_us',
        ]);
        parent::setBreadcrumb([]);
        parent::addKeywords(["Grigo verde", "aule verdi", "Liceo Scientifico", "M. Grigoletti", "scuola superiore", "Pordenone", "prenotazione", "area ping pong"]);
    }

    public function render()
    {
        $content = parent::render();
        $content = str_replace("{{ content }}", $this->getContent('home'), $content);
        $content = str_replace("href=\"/\"", "href=\"#\"", $content);
        return $content;
    }
}
