<?php

namespace Test\Framework;

use Framework\App;
use GuzzleHttp\Psr7\ServerRequest;
use PHPUnit\Framework\TestCase;

class AppTest extends TestCase
{
    public function testRedirectionTrailingSlash()
    {
        $app = new App();
        $request = new ServerRequest('GET', '/azaza/');
        $response = $app->run($request);

        $this->assertContains('/azaza', $response->getHeader('Location'));
        $this->assertEquals(301, $response->getStatusCode());
    }
}
