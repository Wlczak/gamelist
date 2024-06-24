<?php

namespace Gamelist\Api;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class TodoAuth
{
    function main(RequestInterface $request, ResponseInterface $html): ResponseInterface
    {
        ob_start();
        var_dump($_POST);
        echo "<title>Loading...</title>";
        $html->getBody()->write(ob_get_clean());
        return $html;
    }
    function test(RequestInterface $request, ResponseInterface $html): ResponseInterface
    {
        ob_start();
        echo "<title>Testing</title>";
        $html->getBody()->write(ob_get_clean());
        return $html;
    }
}
