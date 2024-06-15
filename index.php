<?php

use Gamelist\Api\TodoApi;
use Gamelist\Controllers\Todo;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use Slim\Exception\HttpNotFoundException;

require __DIR__ . '/vendor/autoload.php';

$app = AppFactory::create();

$customErrorHandler = function (
    Request $request,
    HttpNotFoundException $exception,
    bool $displayErrorDetails,
    bool $logErrors,
    bool $logErrorDetails
) use ($app) {
    $payload = ['error' => 'Resource not found'];

    $response = $app->getResponseFactory()->createResponse();
    $response->getBody()->write(
        json_encode($payload, JSON_UNESCAPED_UNICODE)
    );

    return $response->withHeader('Content-Type', 'application/json')
                    ->withStatus(404);
};

$errorMiddleware = $app->addErrorMiddleware(true, true, true);
$errorMiddleware->setErrorHandler(HttpNotFoundException::class, $customErrorHandler);

$app->setBasePath('/gamelist');

define('BASE_PATH', __DIR__);

//$container = $app->getContainer(); #idk

$app->get('/gamelist/', function (Request $request, Response $response, $args) {
    $response->getBody()->write("Hello world!");
    return $response;
});

$app->get('/todo', [Todo::class, 'view']);

$app->post('/api',[TodoApi::class, 'todo']);

$app->run();
