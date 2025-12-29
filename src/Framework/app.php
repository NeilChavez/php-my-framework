<?php

namespace Framework;

use Exception;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class App
{
    private Router $router;
    private $modules = [];

    public function __construct($modules = [])
    {
        $this->router = new Router();
        foreach ($modules as $module) {
            $this->modules[] = new $module($this->router);
        }
    }

    public function run(ServerRequestInterface $request): ResponseInterface
    {
        $uri = $request->getUri()->getPath();
        if (!empty($uri) && $uri[-1] === '/') {
            return (new Response())
                      ->withStatus(301)
                      ->withHeader('Location', substr($uri, 0, -1));
        }

        $route = $this->router->match($request);
        if ($route === null) {
            return new Response(404, [], 'No route matching');
        }

        foreach ($route->getParams() as $key => $value) {
            $request = $request->withAttribute($key, $value);
        }

        $callback = $route->getCallback();
        $response = call_user_func($callback, $request);

        if ($response instanceof ResponseInterface) {
            return $response;
        } elseif (is_string($response)) {
            return new Response(200, [], $response);
        } else {
            throw new Exception('The response is not a string or an istance of ResponseInteface');
        }
    }
}
