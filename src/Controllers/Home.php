<?php

namespace Gamelist\Controllers;

use Slim\Psr7\Request;
use Slim\Psr7\Response;

class Home
{
    public function view(Request $request, Response $response): Response
    {
        ob_start();
        include "templates/home.html";
        $html = ob_get_clean();
        $response->getBody()->write($html);
        return $response;
    }
}