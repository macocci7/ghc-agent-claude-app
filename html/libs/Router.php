<?php

namespace Libs;

class Router
{
    private array $routes = [];
    
    public function get(string $path, callable $handler): void
    {
        $this->addRoute('GET', $path, $handler);
    }
    
    public function post(string $path, callable $handler): void
    {
        $this->addRoute('POST', $path, $handler);
    }
    
    private function addRoute(string $method, string $path, callable $handler): void
    {
        $this->routes[] = [
            'method' => $method,
            'path' => $path,
            'handler' => $handler,
        ];
    }
    
    public function dispatch(string $method, string $uri): void
    {
        // Remove query string
        $uri = strtok($uri, '?');
        
        foreach ($this->routes as $route) {
            if ($route['method'] === $method && $this->matchPath($route['path'], $uri)) {
                call_user_func($route['handler']);
                return;
            }
        }
        
        // 404 Not Found
        http_response_code(404);
        include __DIR__ . '/../app/Views/errors/404.view.php';
    }
    
    private function matchPath(string $pattern, string $uri): bool
    {
        // Simple exact match for now
        return $pattern === $uri;
    }
}
