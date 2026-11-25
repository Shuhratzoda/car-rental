<?php

declare(strict_types=1);

namespace App\Core;

final class Router
{
    private array $routes = [];

    public function get(string $path, array $handler): void
    {
        $this->routes['GET'][$path] = $handler;
    }

    public function post(string $path, array $handler): void
    {
        $this->routes['POST'][$path] = $handler;
    }

    public function dispatch(string $method, string $uri): void
    {
        $path = parse_url($uri, PHP_URL_PATH) ?: '/';
        $path = rtrim($path, '/') ?: '/';

        // Ҷустуҷӯи дақиқ
        if (isset($this->routes[$method][$path])) {
            $this->call($this->routes[$method][$path]);
            return;
        }

        // Ҷустуҷӯи параметрӣ {id}
        foreach ($this->routes[$method] ?? [] as $route => $handler) {
            $pattern = preg_replace('#\{([a-zA-Z_]+)\}#', '([^/]+)', $route);
            $pattern = '#^' . $pattern . '$#';
            if (preg_match($pattern, $path, $matches)) {
                array_shift($matches);
                $this->call($handler, $matches);
                return;
            }
        }

        http_response_code(404);
        echo (new View(BASE_PATH . '/views'))->render('errors/error', [
            'title'   => 'Саҳифа ёфт нашуд',
            'code'    => 404,
            'message' => 'Саҳифаи дархостшуда вуҷуд надорад.',
        ]);
    }

    private function call(array $handler, array $params = []): void
    {
        [$class, $method] = $handler;
        $controller = new $class();
        $controller->$method(...$params);
    }
}
