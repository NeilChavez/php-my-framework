<?php

namespace Test\Framework;

use Framework\Renderer;
use PHPUnit\Framework\TestCase;

class RendererTest extends TestCase
{
    private $renderer;

    protected function setUp(): void
    {
        $this->renderer = new Renderer();
    }

    public function testRenderTheRightPath()
    {
        $this->renderer->addPath('blog', __DIR__ . '/views');
        $this->assertEquals([
            'blog' => __DIR__ . '/views'
        ], $this->renderer->getPaths());

        $content = $this->renderer->render('@blog/demo');

        $this->assertEquals('hello from views/demo', $content);
    }

    public function testRenderTheDefaultPath()
    {
        $this->renderer->addPath(__DIR__ . '/views');
        $content = $this->renderer->render('demo');

        $this->assertEquals('hello from views/demo', $content);
    }
}
