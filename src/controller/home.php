<?php

$project_root = dirname(__FILE__, 2);
include_once 'endpoint.php';
include_once $project_root . '/page/homePage.php';

class Home extends Endpoint
{
    public function __construct()
    {
        parent::__construct('', 'GET');
    }

    public function match($path, $method): bool
    {
        $path = rtrim($path, '/');

        $uri = parse_url(BASE_URL . $this->path);
        $endpoint = '';

        if (isset($uri['path'])) {
            $endpoint = $uri['path'];
        }
        if (isset($uri['query'])) {
            $endpoint .= '?' . $uri['query'];
        }
        return parent::match($path, $method);
    }

    public function handle()
    {
        $page = new HomePage();
        $page->setPath('');
        $html = $page->render();
        if ($_SERVER['REQUEST_URI'] === BASE_URL) {
            $html = str_replace('href="#"', 'href="' . BASE_URL . '#"', $html);
            $html = str_replace('href="#content"', 'href="' . BASE_URL . '#content"', $html);
        } else {
            $html = str_replace('href="#"', 'href="' . rtrim(BASE_URL, '/') . '#"', $html);
            $html = str_replace('href="#content"', 'href="' . rtrim(BASE_URL, '/') . '#content"', $html);
        }
        echo $html;
    }
}
