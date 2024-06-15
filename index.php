<?php

use Gamelist\Api\TodoApi;
use Gamelist\Controllers\Todo;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

require __DIR__ . '/vendor/autoload.php';

$app = AppFactory::create();

$app->addErrorMiddleware(true, true, false);

$app->setBasePath('/gamelist');

define('BASE_PATH', __DIR__);

//$container = $app->getContainer(); #idk

$app->get('/', function (Request $request, Response $response, $args) {
    ob_start();
    echo "<form action='api' method='post'>
        <button type='submit'>plz work now -_-</button>
    </form>";
    $html = ob_get_clean();
    $response->getBody()->write($html);
    return $response;
});

$app->get('/todo', [Todo::class, 'view']);

$app->post('/api', [TodoApi::class, 'todo']);

$app->run();
