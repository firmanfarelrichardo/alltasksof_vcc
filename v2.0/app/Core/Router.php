<?php

declare(strict_types=1);

namespace App\Core;

final class Router
{
    /** @var array<string, array<int, array{uri:string, pattern:string, handler:callable}>> */
    private array $routes = [];

    public function get(string $uri, callable $handler): void
    {
        $this->add('GET', $uri, $handler);
    }

    public function post(string $uri, callable $handler): void
    {
        $this->add('POST', $uri, $handler);
    }

    public function patch(string $uri, callable $handler): void
    {
        $this->add('PATCH', $uri, $handler);
    }

    public function delete(string $uri, callable $handler): void
    {
        $this->add('DELETE', $uri, $handler);
    }

    public function add(string $method, string $uri, callable $handler): void
    {
        $uri = $this->normalizePath($uri);

        $this->routes[strtoupper($method)][] = [
            'uri' => $uri,
            'pattern' => $this->routePattern($uri),
            'handler' => $handler,
        ];
    }

    public function dispatch(string $method, string $uri): void
    {
        $method = $this->effectiveMethod($method);
        $path = $this->normalizePath((string) parse_url($uri, PHP_URL_PATH));
        $basePath = $this->normalizePath(dirname($_SERVER['SCRIPT_NAME'] ?? ''));

        if ($basePath !== '/' && str_starts_with($path, $basePath)) {
            $path = $this->normalizePath(substr($path, strlen($basePath)));
        }

        foreach ($this->routes[strtoupper($method)] ?? [] as $route) {
            if (!preg_match($route['pattern'], $path, $matches)) {
                continue;
            }

            $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
            $route['handler'](...array_values($params));
            return;
        }

        Response::notFound();
    }

    private function normalizePath(string $path): string
    {
        $path = '/' . trim($path, '/');

        return $path === '/' ? '/' : rtrim($path, '/');
    }

    private function routePattern(string $uri): string
    {
        $pattern = preg_replace('#\{([a-zA-Z_][a-zA-Z0-9_]*)\}#', '(?P<$1>[^/]+)', $uri);

        return '#^' . $pattern . '$#';
    }

    private function effectiveMethod(string $method): string
    {
        $method = strtoupper($method);

        if ($method !== 'POST') {
            return $method;
        }

        $override = strtoupper((string) ($_POST['_method'] ?? ''));

        return in_array($override, ['PATCH', 'DELETE'], true) ? $override : $method;
    }
}
