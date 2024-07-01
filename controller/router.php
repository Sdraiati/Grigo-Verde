<?php
class Router
{
    private $routes = [];

    public function __construct()
    {
        $this->routes = [];
    }

    // Add an api to the list of routes (apis)
    public function add($endpoint)
    {
        array_push($this->routes, $endpoint);
    }

    // Check if any of the routes match the path and method
    // If a match is found, return true
    // Otherwise, return false
    // This method is used to make it possible to assemble routers
    public function match($path, $method): bool
    {
        foreach ($this->routes as $route) {
            if ($route->match($path, $method)) {
                return true;
            }
        }
        return false;
    }

    // Find the route that matches the path and method, and call its handle 
    // method to process the request
    public function handle($path, $method)
    {
        foreach ($this->routes as $route) {
            if ($route->match($path, $method)) {
                $route->handle($path, $method);
                return true;
            }
        }
        return false;
    }
}
