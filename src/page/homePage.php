<?php

include_once 'page.php';

class HomePage extends Page
{
    public $title = 'Home';
    public $nav = [
        'About us' => 'about_us',
    ];
    public $breadcrumb = [];
    public $keywords = ["Grigo verde", "aule verdi", "Liceo Scientifico", "M. Grigoletti", "scuola superiore", "Pordenone", "prenotazione", "area ping pong"];
    public $path = '/';

    public function __construct() {}

    public function render()
    {
        $content = $this->getContent('layout');
        $content = str_replace('{{ title }}', $this->title . ' - Grigo Verde', $content);
        $content = str_replace('{{ description }}', 'This is a description', $content);
        $content = str_replace('{{ keywords }}', implode(', ', $this->keywords), $content);
        $content = str_replace('{{ page_path }}', $this->path, $content);

        $nav = new ReferenceList($this->nav);
        $content = str_replace('{{ menu }}', $nav->render(), $content);

        $breadcrumb = new Breadcrumb($this->breadcrumb, $this->title);
        $content = str_replace('{{ breadcrumbs }}', $breadcrumb->render(), $content);
        $content = $this->takeOffCircularReference($content);

        $content = str_replace("{{ content }}", $this->getContent('home'), $content);
        $content = str_replace("href=\"/\"", "href=\"#\"", $content);

        $content = str_replace('{{ base_path }}', BASE_URL, $content);
        return $content;
    }
}
