<?php

namespace Gamelist\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class Todo
{
    public function view(Request $request, Response $response, $args): Response
    {
        //require '../../vendor/autoload.php';
        ob_start();
        echo "pain...";
        //include "templates/index.html";
        $html = ob_get_clean();
        $response->getBody()->write($html);
        return $response;
    }
    public function login(Request $request, Response $response, $args): Response
    {
        //require '../../vendor/autoload.php';
        ob_start();
        echo "pain...";
        //include "templates/index.html";
        $html = ob_get_clean();
        $response->getBody()->write($html);
        return $response;
    }
}
