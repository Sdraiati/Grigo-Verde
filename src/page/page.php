<?php

$project_root = dirname(__FILE__, 2);
include_once $project_root . '/global_values.php';
include_once 'referenceList.php';
include_once 'breadcrumb.php';

class Page
{
    private $title = '';
    private $keywords = ['ricette', 'gustose', 'cucina', 'italiana'];
    private $path = '/';
    private $nav = [];
    private $breadcrumb = [];

    protected function getContent($path)
    {
        global $project_root;

        $filename = $project_root . '/template/' . $path . '.html';
        if (file_exists($filename)) {
            return file_get_contents($filename);
        }
        http_response_code(404);
        echo '404 Not Found';
        echo '<br>';
        echo $path;
        exit;
    }

    protected function setTitle($title)
    {
        $this->title = $title;
    }

    // expectes an array of keywords
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

    protected function setNav($nav)
    {
        $this->nav = $nav;
    }

    protected function setBreadcrumb($breadcrumb)
    {
        $this->breadcrumb = $breadcrumb;
    }

    // TODO: check circular reference
    protected function takeOffCircularReference($content)
    {
        // return str_replace('"' . $this->path . '"', '\"#\"', $content);
        return $content;
    }

    // path is the path of the page, which is used to skip the navbar and jump 
    // to the content
    public function render()
    {
        $content = $this->getContent('layout');
        $content = str_replace('{{ base_path }}', BASE_URL, $content);
        $content = str_replace('{{ title }}', $this->title . ' - Grigo Verde', $content);
        $content = str_replace('{{ description }}', 'This is a description', $content);
        $content = str_replace('{{ keywords }}', implode(', ', $this->keywords), $content);
        $content = str_replace('{{ page_path }}', $this->path, $content);

        $nav = new ReferenceList($this->nav);
        $content = str_replace('{{ menu }}', $nav->render(), $content);

        $breadcrumb = new Breadcrumb($this->breadcrumb, $this->title);
        $content = str_replace('{{ breadcrumbs }}', $breadcrumb->render(), $content);
        $content = $this->takeOffCircularReference($content);

        return $content;
    }
}
