<?php

namespace Gamelist\Api;

use Psr\Http\Message\ServerRequestInterface as RequestInterface;
use Psr\Http\Message\ResponseInterface;

class TodoApi
{
    function todo(RequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        ob_start();
        $html = ob_get_clean();
        $response->getBody()->write($html); // Set the response body
        return $response;
    }
}
