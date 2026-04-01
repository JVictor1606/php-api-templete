<?php

namespace Src\core\Http;

use Src\core\Container;


class Router
{
    private array $routes = [];
    private string $basePath;

    public function __construct(?string $basePath = null)
    {

        if ($basePath === null) {
            $basePath = $this->detectBasePath();
        } elseif (str_contains($basePath, 'http')) {
            $basePath = parse_url($basePath, PHP_URL_PATH) ?? '';
        }

        $this->basePath = rtrim($basePath, '/');
    }


    private function detectBasePath(): string
    {
        $scriptName = $_SERVER['SCRIPT_NAME'] ?? '';
        $basePath = dirname($scriptName);

        $basePath = str_replace('\\', '/', $basePath);

        if ($basePath === '/' || $basePath === '.' || $basePath === '') {
            return '';
        }

        return rtrim($basePath, '/');
    }


    public function get(string $path, array $action, array $middlewares = []): void
    {
        $this->routes[] = ['GET', $path, $action, $middlewares];
    }

    public function post(string $path, array $action, array $middlewares = []): void
    {
        $this->routes[] = ['POST', $path, $action, $middlewares];
    }

    public function put(string $path, array $action, array $middlewares = []): void
    {
        $this->routes[] = ['PUT', $path, $action, $middlewares];
    }

    public function delete(string $path, array $action, array $middlewares = []): void
    {
        $this->routes[] = ['DELETE', $path, $action, $middlewares];
    }


    public function run(): void
    {
        try {
            $method = $_SERVER['REQUEST_METHOD'];
            $uri = $this->getUri();


            foreach ($this->routes as [$routeMethod, $routePath, $action, $middlewares]) {
                if ($routeMethod !== $method) {
                    continue;
                }

                $params = $this->match($routePath, $uri);

                if ($params !== false) {
                    $this->runMiddlewares($middlewares);
                    echo $this->call($action, $params);
                    return;
                }
            }

            echo JsonResponse::error("Rota não encontrada", 404);
        } catch (HttpException $e) {
            echo JsonResponse::error($e->getMessage(), $e->getStatusCode());
        } catch (\Throwable $error) {
            echo JsonResponse::error('Erro interno do servidor ', 500);
        }
    }

    private function runMiddlewares(array $middlewares): void
    {
        foreach ($middlewares as $middlewareClass) {
            $middleware = Container::get($middlewareClass);
            $middleware->handle();
        }
    }

    private function getUri(): string
    {
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        if ($this->basePath) {
            $uri = substr($uri, strlen($this->basePath)) ?: '/';
        }

        return $uri;
    }


    private function match(string $route, string $uri): array|false
    {

        if ($route === $uri) {
            return [];
        }


        $pattern = preg_replace('/\{(\w+)\}/', '([^/]+)', $route);

        if (preg_match('#^' . $pattern . '$#', $uri, $values)) {
            preg_match_all('/\{(\w+)\}/', $route, $keys);

            $params = [];
            foreach ($keys[1] as $i => $key) {
                $params[$key] = $values[$i + 1];
            }
            return $params;
        }

        return false;
    }


    private function call(array $action, array $params): string
    {
        [$controller, $method] = $action;

        $instance = Container::get($controller);

        if (!empty($params)) {
            return $instance->$method(...array_values($params));
        }

        return $instance->$method();
    }
}
