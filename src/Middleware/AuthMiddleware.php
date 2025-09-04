<?php

namespace Gamelist\Middleware;

use Gamelist\Classes\Database;
use Gamelist\Classes\Token;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

class AuthMiddleware
{
    private Database $Database;
    private Token $Token;
    function __invoke(Request $request, RequestHandler $handler): Response
    {
        session_start();

        $this->Token = new Token;
        $this->Database = new Database;

        $response = $handler->handle($request);
        $this->Token->checkTokens();
        $response = $this->loginCheck($response);
        return $response;
    }

    function loginCheck(ResponseInterface $response): ResponseInterface
    {

        if (!isset($_SESSION['isLoggedIn'])) {
            $_SESSION['isLoggedIn'] = false;
        }

        if (preg_match('/\.(?:png|jpg|jpeg|gif|ico|css|js|svg|woff2|woff|eot|ttf|otf)$/', $_SERVER['REQUEST_URI'])) {
            return $response;
        }

        if ($_SERVER['REQUEST_URI'] == BASE_URL . "/login" || $_SERVER['REQUEST_URI'] == BASE_URL . "/register" || $_SERVER['REQUEST_URI'] == BASE_URL . "/api" || $_SERVER['REQUEST_URI'] == BASE_URL . "/auth") {
            return $response;
        } else {
            if ($this->Database->verifyToken()) {
                return $response;
            }
        }

        return $response->withHeader('Location', BASE_URL . '/login')->withStatus(302);
    }
}
