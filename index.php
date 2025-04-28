<?php

use Gamelist\Api\TodoApi;
use Gamelist\Api\TodoAuth;
use Gamelist\Controllers\Todo;
use Gamelist\Middleware\AuthMiddleware;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Exception\HttpNotFoundException;
use Slim\Factory\AppFactory;

require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/src/Bootstrap.php';

$app = AppFactory::create();

$app->addErrorMiddleware(true, true, true);

//define('BASE_PATH', __DIR__);

$app->add(AuthMiddleware::class);

$app->setBasePath(BASE_URL);

$app->get('/', [Todo::class, 'view']);

$app->get('/shop', [Todo::class, 'view']);

$app->get('/login', [Todo::class, 'login']);

$app->get('/register', [Todo::class, 'register']);

$app->post('/auth', [TodoAuth::class, 'main']);

// $app->get('/test', [TodoAuth::class, 'test']);

$app->post('/api', [TodoApi::class, 'main']);

$app->add(function (ServerRequestInterface $request, RequestHandlerInterface $handler) {
    $response = $handler->handle($request);

    // Modify the response after the application has processed the request

    if ($request->getUri()->getPath() !== '/api') {
        $response->getBody()->write("
        <script>
        window.APP_CONFIG = {
            appUrl: " . json_encode(BASE_URL) . "
        };
        </script>
        ");
    }

    return $response;
});

$app->map(['GET'], "/components/{component}", function (RequestInterface $request, ResponseInterface $response, array $args): ResponseInterface {
    $componentName = $args['component'];
    $componentFileName = __DIR__ . "/templates/components/" . $componentName . ".html";

    if (is_file($componentFileName)) {
        ob_start();
        include $componentFileName;
        $component = ob_get_clean();

        $response->getBody()->write($component);
    } else {
        throw new HttpNotFoundException($request);
    }
    return $response;
});

$app->run();
