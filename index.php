<?php

use Gamelist\Api\TodoApi;
use Gamelist\Api\TodoAuth;
use Gamelist\Controllers\Todo;
use Gamelist\Middleware\AuthMiddleware;
use Slim\Factory\AppFactory;

require __DIR__ . '/vendor/autoload.php';

$app = AppFactory::create();

$app->addErrorMiddleware(true, true, true);

$app->setBasePath('/gamelist');

define('BASE_PATH', __DIR__);

$app->add(AuthMiddleware::class);

$app->get('/', [Todo::class, 'view']);

$app->get('/login', [Todo::class, 'login']);

$app->get('/register', [Todo::class, 'register']);

$app->post('/auth', [TodoAuth::class, 'main']);

// $app->get('/test', [TodoAuth::class, 'test']);

$app->post('/api', [TodoApi::class, 'main']);

$app->run();
