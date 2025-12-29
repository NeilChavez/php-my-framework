<?php

namespace Framework;

use FastRoute\Dispatcher;
use Framework\Router\Route;
use FastRoute\RouteCollector;
use GuzzleHttp\Psr7\ServerRequest;

use function FastRoute\simpleDispatcher;

class Router
{
    public $routes = [];
    private $dispatcher;

    private function buildRoutes()
    {
        $this->dispatcher = simpleDispatcher(function (RouteCollector $r) {
            foreach (array_keys($this->routes) as $method) {
                foreach ($this->routes[$method] as $route) {
                    $r->addRoute($method, $route['path'], $route['name']);
                }
            }
        });
    }

    public function get(string $path, callable $callback, string $name)
    {
        $this->routes['GET'][$name] = [
            'path' => $path,
            'callback' => $callback,
            'name' => $name
        ];
    }

    public function post(string $path, callable $callback, string $name)
    {
        $this->routes['POST'][$name] = [
            'path' => $path,
            'callback' => $callback,
            'name' => $name
        ];
    }

    public function put(string $path, callable $callback, string $name)
    {
        $this->routes['PUT'][$name] = [
            'path' => $path,
            'callback' => $callback,
            'name' => $name
        ];
    }

    public function delete(string $path, callable $callback, string $name)
    {
        $this->routes['DELETE'][$name] = [
            'path' => $path,
            'callback' => $callback,
            'name' => $name
        ];
    }

    public function match(ServerRequest $request): ?Route
    {
        $this->buildRoutes();
        $method = $request->getMethod();
        $path = $request->getUri()->getPath();

        $routeInfo = $this->dispatcher->dispatch(
            $method,
            $path
        );

        if ($routeInfo[0] === Dispatcher::FOUND) {
            $nameRoute = $routeInfo[1];
            $params = $routeInfo[2];

            $route = $this->routes[$method][$nameRoute];

            return new Route($nameRoute, $route['callback'], $params);
        }
        return null;
    }

    public function generateUri(string $nameRoute, array $params): ?string
    {
        $routesGet = $this->routes['GET'];
        if (!isset($routesGet[$nameRoute])) {
            return null;
        }

        $route = $routesGet[$nameRoute];
        // "/blog/{slug:[a-z0-9\-]+}/[{id:\d+}]"
        $path = $route['path'];
        // "['slug' => 'my-article', 'id' => '42']"
        foreach ($params as $key => $value) {
            $path = str_replace($key, $value, $path);
        }
        // "/blog/{my-article:[a-z0-9\-]+}/[{42:\d+}]";
        $path = preg_replace('#\{([^:}]+)(?::[^}]+)?\}#', '$1', $path);
        // "/blog/my-article/[42]";
        $path = str_replace(['[', ']'], '', $path);
        // clean Uri "/blog/my-article/42"
        return $path;
    }
}
