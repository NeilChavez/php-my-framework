<?php

namespace Test\Framework;

use Framework\Router;
use PHPUnit\Framework\TestCase;
use GuzzleHttp\Psr7\ServerRequest;

class RouterTest extends TestCase
{
    private Router $router;

    protected function setUp(): void
    {
        $this->router = new Router();
    }

    public function testGetMethod()
    {
        $request = new ServerRequest('GET', '/blog');
        $this->router->get('/blog', function () {
            return 'Hello blog!';
        }, 'blog.home');

        $route = $this->router->match($request);

        $this->assertEquals('blog.home', $route->getName());
        $this->assertEquals('Hello blog!', call_user_func($route->getCallback()));
    }

    public function testGetMethodDoesNotExists()
    {
        $request = new ServerRequest('GET', '/blXg');
        $this->router->get('/blog', function () {
            return 'Hello blog!';
        }, 'blog.home');

        $route = $this->router->match($request);

        $this->assertEquals(null, $route);
    }

    public function testGetRoutesWithParams()
    {
        $request = new ServerRequest('GET', '/blog/this-is-an-article/42');

        $this->router->get("/blog/{slug:[a-z0-9\-]+}/[{id:\d+}]", function () {
            return 'Hello from article with ID!';
        }, 'blog.article');

        $route = $this->router->match($request);

        $this->assertEquals('blog.article', $route->getName());
        $this->assertEquals('Hello from article with ID!', call_user_func($route->getCallback()));
        $this->assertEquals(
            [
                'slug' => 'this-is-an-article',
                'id' => '42'
            ],
            $route->getParams()
        );
    }

    public function testGetRoutesWithNotAllowedParams()
    {
        $request = new ServerRequest('GET', '/blog/this-is-an_article/42');

        $this->router->get("/blog/{slug:[a-z0-9\-]+}/[{id:\d+}]", function () {
            return 'Hello from article with ID!';
        }, 'blog.article');

        $router = $this->router->match($request);

        $this->assertEquals(null, $router);
    }

    public function testGenerateUri()
    {
        $request = new ServerRequest('GET', '/blog/my-article/42');

        $this->router->get("/blog/{slug:[a-z0-9\-]+}/[{id:\d+}]", function () {
            return 'Hello from article with ID!';
        }, 'blog.article');

        $uri = $this->router->generateUri('blog.article', ['slug' => 'my-article', 'id' => '42']);

        $this->assertEquals('/blog/my-article/42', $uri);
    }
}
