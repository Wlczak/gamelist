<?php

namespace Gamelist\Middleware;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

class AuthMiddleware
{
    function __invoke(Request $request, RequestHandler $handler): Response
    {
        echo "I AM MIDDLEWARE:)<br>";
        $response = $handler->handle($request);
        return $response;
    }
}
