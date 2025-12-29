<?php

require __DIR__ . '/../vendor/autoload.php';

use App\Blog\BlogModule;
use Framework\App;
use GuzzleHttp\Psr7\ServerRequest;

use function Http\Response\send;

$app = new App([
    BlogModule::class
]);

$request = ServerRequest::fromGlobals();
$response = $app->run($request);
send($response);
