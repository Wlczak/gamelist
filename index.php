<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use Gamelist\Controllers\Todo;

require __DIR__ . '/vendor/autoload.php';

$app = AppFactory::create();

$app->addErrorMiddleware(true, true, false);

define('BASE_PATH', __DIR__);

$container = $app->getContainer();

$app->get('/gamelist/', function (Request $request, Response $response, $args) {
    $response->getBody()->write("Hello world!");
    return $response;
});

$app->get('/gamelist/todo', [Todo::class, 'view']);

$app->run();
