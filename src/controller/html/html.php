<?php

$project_root = dirname(__FILE__, 3);
include_once $project_root . '/global_values.php';

class Html
{
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

    // TODO: check circular reference
    // protected function takeOffCircularReference($path)
    // might work with something like
    // $content = str_replace('path', '#', $content);

    public function render($path)
    {
        $content = $this->getContent('layout');
        $content = str_replace('{{ base_path }}', BASE_URL, $content);
        $content = str_replace('{{ title }}', $path, $content);
        $content = str_replace('{{ description }}', 'This is a description', $content);
        $content = str_replace('{{ keywords }}', 'This is a keywords', $content);
        $content = str_replace('{{ page_path }}', $path, $content);
        $content = str_replace('{{ menu }}', '<li> about us </li>', $content);
        $content = str_replace('{{ breadcrumbs }}', '/' . $path, $content);

        return $content;
    }
}
