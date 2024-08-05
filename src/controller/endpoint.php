<?php

$project_root = dirname(__FILE__, 2);
include_once $project_root . '/global_values.php';

abstract class Endpoint
{
    private $path;
    private $method;

    public function __construct($path, $method)
    {
        $this->path = $path;
        $this->method = $method;
    }

    private static function sanitizeInput($input): string
    {
        return htmlspecialchars(strip_tags(trim($input)), ENT_QUOTES, 'UTF-8');
    }

    protected function post($param)
    {
        $json = file_get_contents("php://input");

        $data = json_decode($json, true);
        if ($data !== null && isset($data[$param])) {
            return $this->sanitizeInput($data[$param]);
        } else if (isset($_POST[$param])) {
            return $this->sanitizeInput($_POST[$param]);
        } else {
            // TODO: return a page with the error
            http_response_code(400);
            echo 'Error Missing parameter: ' . $param;
        }
    }

    protected function get($param)
    {
        $json = file_get_contents("php://input");

        $data = json_decode($json, true);
        if ($data !== null && isset($data[$param])) {
            return $this->sanitizeInput($data[$param]);
        } else if (isset($_GET[$param])) {
            return $this->sanitizeInput($_GET[$param]);
        } else {
            // TODO: return a page with the error
            http_response_code(400);
            echo 'Error Missing parameter: ' . $param;
        }
    }

    protected function redirect($url)
    {
        header('Location: ' . BASE_URL . $url);
        exit();
    }

    public function match($path, $method): bool
    {
        return BASE_URL . $this->path === $path && $this->method === $method;
    }

    abstract public function handle();
}
