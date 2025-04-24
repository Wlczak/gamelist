<?php

namespace Gamelist\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class Todo
{
    /**
     * @param  Request    $request
     * @param  Response   $response
     * @return Response
     */
    public function view(Request $request, Response $response): Response
    {
        ob_start();
        include "templates/index.html";
        $html = ob_get_clean();
        $response->getBody()->write($html);
        return $response;
    }

    /**
     * @param  Request    $request
     * @param  Response   $response
     * @return Response
     */
    public function login(Request $request, Response $response): Response
    {
        ob_start();
        include "templates/login.html";
        $html = ob_get_clean();
        $response->getBody()->write($html);
        return $response;
    }

    /**
     * @param  Request    $request
     * @param  Response   $response
     * @return Response
     */
    public function register(Request $request, Response $response): Response
    {
        ob_start();
        include "templates/register.html";
        $html = ob_get_clean();
        $response->getBody()->write($html);
        return $response;
    }
}
