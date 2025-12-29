<?php

namespace App\Blog;

use Framework\Router;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class BlogModule
{
    public function __construct(Router $router)
    {
        $router->get('/blog', [$this, 'index'], 'blog.home');
        $router->get('/blog/{slug:[a-z0-9\-]+}[/{id:\d+}]', [$this, 'show'], 'blog.show');
    }

    public function index(): ResponseInterface
    {
        return new Response(200, [], '<h1>Hello, from module index!</h1>');
    }

    public function show(ServerRequestInterface $request)
    {
        return '<h1>This is an article: ' . $request->getAttribute('slug') . '</h1>';
    }
}
