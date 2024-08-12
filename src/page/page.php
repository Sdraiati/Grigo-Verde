<?php

$project_root = dirname(__FILE__, 2);
include_once $project_root . '/global_values.php';
include_once 'referenceList.php';
include_once 'breadcrumb.php';
include_once $project_root . '/controller/autenticazione.php';
require_once $project_root . '/page/resource_not_found.php';
require_once $project_root . '/controller/message.php';

class Page
{
    protected $title = '';
    protected $titleBreadcrumb = '';
    protected $keywords = ['ricette', 'gustose', 'cucina', 'italiana'];
    protected $path = '/';
    protected $breadcrumb = [];
    private $nav;
    protected $description = '';
    public function __construct($title = '', $path = '/')
    {
        $this->title = $title;
        $this->path = $path;

        if (!Autenticazione::isLogged()) {
            $this->nav = [
                '<span lang="en">Home</span>' => '',
                '<span>Spazi</span>' => 'spazi',
                '<span>Prenotazioni</span>' => 'prenotazioni',
                '<span lang="en">About us</span>' => 'about_us',
            ];
        } else if (Autenticazione::is_amministratore()) {
            $this->nav = [
                '<span lang="en">Home</span>' => '',
                'Cruscotto' => 'cruscotto',
                'Spazi' => 'spazi',
                'Utenti' => 'utenti',
                'Prenotazioni' => 'prenotazioni',
                '<span lang="en">About us</span>' => 'about_us',
            ];
        } else {
            $this->nav = [
                '<span lang="en">Home</span>' => '',
                'Cruscotto' => 'cruscotto',
                'Spazi' => 'spazi',
                'Prenotazioni' => 'prenotazioni',
                '<span lang="en">About us</span>' => 'about_us',
            ];
        }
    }

    protected function setDescription($description)
    {
        $this->description = $description;
    }

    protected function makeLogin()
    {
        if (Autenticazione::isLogged()) {
            return '<a href="cruscotto">Cruscotto</a>';
        } else {
            return '<a href="login"><span lang="en">Login</span></a>';
        }
    }

    protected function getContent($path)
    {
        global $project_root;

        $filename = $project_root . '/template/' . $path . '.html';
        if (file_exists($filename)) {
            return file_get_contents($filename);
        }
        $page = new ResourceNotFoundPage();
        $page->setPath($this->path);
        echo $page->render();
        exit;
    }

    public function setTitle($title)
    {
        $this->titleBreadcrumb = $title;
        $title = preg_replace('/^<span[^>]*>(.*?)<\/span>$/i', '$1', $title);
        $this->title = $title;
    }

    // expects an array of keywords
    protected function addKeywords($keywords)
    {
        foreach ($keywords as $keyword) {
            if (!in_array($keyword, $this->keywords)) {
                $this->keywords[] = $keyword;
            }
        }
    }

    public function setPath($path)
    {
        $this->path = $path;
    }

    public function setBreadcrumb($breadcrumb)
    {
        $this->breadcrumb = $breadcrumb;
    }

    // TODO: check circular reference
    protected function takeOffCircularReference($content)
    {
        return str_replace('href="' . $this->path . '"', 'href="#"', $content);
    }

    // path is the path of the page, which is used to skip the navbar and jump
    // to the content
    public function render()
    {
        $content = $this->getContent('layout');
        $content = str_replace('{{ base_path }}', BASE_URL, $content);
        $content = str_replace('{{ title }}', $this->title . ' - Grigo Verde', $content);
        $content = str_replace('{{ login }}', $this->makeLogin(), $content);
        $content = str_replace('{{ description }}', $this->description, $content);
        $content = str_replace('{{ keywords }}', implode(', ', $this->keywords), $content);
        $content = str_replace('{{ page_path }}', $this->path, $content);

        // Pass the current path to ReferenceList
        $nav = new ReferenceList($this->nav, $this->path);
        $content = str_replace('{{ menu }}', $nav->render(), $content);

        $breadcrumb = new Breadcrumb($this->breadcrumb, $this->titleBreadcrumb);
        $content = str_replace('{{ breadcrumbs }}', $breadcrumb->render(), $content);

        $content = str_replace('{{ message }}', Message::get(), $content);
        $content = $this->takeOffCircularReference($content);

        return $content;
    }

    public function error($message)
    {
        return '<div class="errore"><p>' . $message . '</p></div>';
    }
}
