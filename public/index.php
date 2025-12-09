<?php

require __DIR__ . '/../vendor/autoload.php';

use Framework\App;
use GuzzleHttp\Psr7\ServerRequest;

use function Http\Response\send;

$app = new App();

$request = ServerRequest::fromGlobals();
$response = $app->run($request);
send($response);
