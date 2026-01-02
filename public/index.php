<?php

require __DIR__ . '/../vendor/autoload.php';

use Framework\App;
use Framework\Router;
use Framework\Renderer;
use App\Blog\BlogModule;
use GuzzleHttp\Psr7\ServerRequest;

use function Http\Response\send;

$renderer = new Renderer();
$renderer->addPath(dirname(__DIR__) . '/views');

$app = new App([
    BlogModule::class
], [
    'renderer' => $renderer
]);

$request = ServerRequest::fromGlobals();
$response = $app->run($request);
send($response);
