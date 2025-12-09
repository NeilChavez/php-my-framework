<?php

namespace Framework;

use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class App
{
    public function run(ServerRequestInterface $request): ResponseInterface
    {
        $uri = $request->getUri()->getPath();
        if (!empty($uri) && $uri[-1] === '/') {
            return (new Response())
                      ->withStatus(301)
                      ->withHeader('Location', substr($uri, 0, -1));
        }
        if ($uri === '/blog') {
            return new Response(200, [], 'This is the blog');
        }
        if ($uri === '/') {
            return new Response(200, [], 'This is the home');
        }
        return new Response(404, [], 'Not found in our blog');
    }
}
