<?php

namespace Gamelist\Middleware;

use Gamelist\Classes\Database;
use Gamelist\Classes\Token;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

class AuthMiddleware
{
    private $Database;
    private $Token;
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
    function loginCheck($response)
    {

        if (!isset($_SESSION['isLoggedIn'])) {
            $_SESSION['isLoggedIn'] = false;
        }

        if ($_SERVER['REQUEST_URI'] == "/gamelist/login" || $_SERVER['REQUEST_URI'] == "/gamelist/register" || $_SERVER['REQUEST_URI'] == "/gamelist/api" || $_SERVER['REQUEST_URI'] == "/gamelist/auth") {
            return $response;
        } else {
            if($this->Database->verifyToken()){
                return $response;
            }
        }
        
        return $response->withHeader('Location', 'login')->withStatus(302);
    }
}
