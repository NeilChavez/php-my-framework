<?php

namespace App\Blog;

use Framework\Renderer;
use Framework\Router;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class BlogModule
{
    private $renderer;

    public function __construct(Router $router, Renderer $renderer)
    {
        $this->renderer = $renderer;
        $this->renderer->addPath('blog', __dir__ . '/views');
        $router->get('/blog', [$this, 'index'], 'blog.home');
        $router->get('/blog/{slug:[a-z0-9\-]+}[/{id:\d+}]', [$this, 'show'], 'blog.show');
    }

    public function index(): string
    {
        return $this->renderer->render('@blog/index', [
            'name' => 'neil'
        ]);
    }

    public function show(ServerRequestInterface $request)
    {
        return '<h1>This is an article: ' . $request->getAttribute('slug') . '</h1>';
    }
}
