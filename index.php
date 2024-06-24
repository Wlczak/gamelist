<?php

use Gamelist\Api\TodoApi;
use Gamelist\Controllers\Todo;
use Gamelist\Middleware\AuthMiddleware;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

require __DIR__ . '/vendor/autoload.php';

$app = AppFactory::create();

$app->addErrorMiddleware(true, true, false);

$app->setBasePath('/gamelist');

define('BASE_PATH', __DIR__);

//$container = $app->getContainer(); #idk

$app->add(AuthMiddleware::class);

$app->get('/', [Todo::class, 'view']);

$app->get('/login', [Todo::class, 'login']);

$app->get('/register', [Todo::class, 'register']);

$app->post('/api', [TodoApi::class, 'main']);

$app->run();
