<?php

include_once 'page.php';

class HomePage extends Page
{
    public $title = 'Home';
    public $nav = [
        '<span lang="en">About us</span>' => 'about_us',
        '<span lang="en">Login</span>' => 'login'
    ];
    public $breadcrumb = [];
    public $keywords = ["Grigo verde", "aule verdi", "Liceo Scientifico", "M. Grigoletti", "scuola superiore", "Pordenone", "prenotazione", "area ping pong"];
    public $path = '/';

    public function __construct() {
        $this->setTitle($this->title);
        $this->setNav($this->nav);
        $this->setBreadcrumb($this->breadcrumb, true);
        $this->addKeywords($this->keywords);
        $this->setPath($this->path);
    }

    public function render()
    {
        $content = parent::render();

        $content = str_replace("{{ content }}", $this->getContent('home'), $content);
        $content = str_replace("href=\"/\"", "href=\"#\"", $content);

        //$content = str_replace('{{ base_path }}', BASE_URL, $content);
        $content = str_replace("{{ error }}", '', $content);
        return $content;
    }
}
