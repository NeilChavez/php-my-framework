<?php

namespace Test\Framework;

use App\Blog\BlogModule;
use Framework\App;
use GuzzleHttp\Psr7\ServerRequest;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;

class AppTest extends TestCase
{
    public function testRedirectionTrailingSlash()
    {
        $app = new App([], []);
        $request = new ServerRequest('GET', '/azaza/');
        $response = $app->run($request);

        $this->assertContains('/azaza', $response->getHeader('Location'));
        $this->assertEquals(301, $response->getStatusCode());
    }

    public function testBlog()
    {
        $request = new ServerRequest('GET', '/blog');
        $app = new App([
            BlogModule::class
        ], []);
        $response = $app->run($request);

        $requestToArticle = new ServerRequest('GET', '/blog/article-title');
        $responseToArticle = $app->run($requestToArticle);

        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertStringContainsString('<h1>Hello, from module index!</h1>', (string)$response->getBody());
        $this->assertStringContainsString('<h1>This is an article: article-title</h1>', (string)$responseToArticle->getBody());
    }
}
