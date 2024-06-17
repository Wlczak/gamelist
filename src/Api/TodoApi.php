<?php

namespace Gamelist\Api;

use Psr\Http\Message\ServerRequestInterface as RequestInterface;
use Psr\Http\Message\ResponseInterface;

class TodoApi
{
    public $recievedArray;
    function main(RequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $this->recievedArray = $this->getRecievedArray(); // set array

        $response->getBody()->write($this->sendArray($this->getRecievedArray())); // Set the response body
        return $response;
    }
    function sendArray($array)
    {
        ob_start();
        header('Content-Type: application/json');
        echo json_encode($array);
        return ob_get_clean();
    }
    function getRecievedArray(): array
    {
        $json = file_get_contents('php://input'); // get JSON
        return json_decode($json, true); // decode and return JSON
    }
}
