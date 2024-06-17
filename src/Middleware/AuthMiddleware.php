<?php

namespace Gamelist\Middleware;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

class AuthMiddleware
{
    function __invoke(Request $request, RequestHandler $handler): Response
    {
        session_start();
        $response = $handler->handle($request);
        $response->getBody()->write($this->loginCheck($response));
        return $response;
    }
    function loginCheck($response)
    {
        $_SESSION['isLoggedIn'] = true;
        ob_start();
        if (!isset($_SESSION['isLoggedIn'])) {
            $_SESSION['isLoggedIn'] = true;
        }
        if (!$_SESSION['isLoggedIn'] && $_SERVER['REQUEST_URI'] != "/gamelist/login") {
            ob_clean();
            header("Location: /gamelist/login");
            die("You need to login");
        }
        return ob_get_clean();
    }
}
