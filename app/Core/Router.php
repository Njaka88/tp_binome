<?php

declare(strict_types=1);

namespace App\Core;

use Closure;

final class Router
{
    /** @var array<string, array<int, array{pattern:string, handler:callable|array|string}>> */
    private array $routes = [
        'GET' => [],
        'POST' => [],
    ];

    public function get(string $pattern, callable|array|string $handler): void
    {
        $this->add('GET', $pattern, $handler);
    }

    public function post(string $pattern, callable|array|string $handler): void
    {
        $this->add('POST', $pattern, $handler);
    }

    public function dispatch(Request $request): void
    {
        $method = $request->method();
        $path = $request->path();
        $routes = $this->routes[$method] ?? [];

        foreach ($routes as $route) {
            $regex = $this->toRegex($route['pattern']);
            if (!preg_match($regex, $path, $matches)) {
                continue;
            }

            $params = [];
            foreach ($matches as $key => $value) {
                if (!is_int($key)) {
                    $params[$key] = $value;
                }
            }

            $handler = $route['handler'];
            if (is_array($handler)) {
                [$class, $action] = $handler;
                $instance = new $class();
                $instance->{$action}($request, $params);

                return;
            }

            if ($handler instanceof Closure || is_callable($handler)) {
                $handler($request, $params);

                return;
            }
        }

        http_response_code(404);
        render('front/404', [
            'metaTitle' => 'Page non trouvee',
            'metaDescription' => 'La page demandee est introuvable.',
        ]);
    }

    private function add(string $method, string $pattern, callable|array|string $handler): void
    {
        $this->routes[$method][] = [
            'pattern' => $this->normalizePattern($pattern),
            'handler' => $handler,
        ];
    }

    private function normalizePattern(string $pattern): string
    {
        if ($pattern === '/') {
            return '/';
        }

        return '/' . trim($pattern, '/');
    }

    private function toRegex(string $pattern): string
    {
        $escaped = preg_quote($pattern, '#');
        $escaped = preg_replace('#\\\{([a-zA-Z_][a-zA-Z0-9_]*)\\\}#', '(?P<$1>[^/]+)', $escaped) ?? $escaped;

        return '#^' . $escaped . '$#';
    }
}
