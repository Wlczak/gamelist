<?php

namespace Gamelist\Api;

use Gamelist\Classes\Database;
use Psr\Http\Message\ServerRequestInterface as RequestInterface;
use Psr\Http\Message\ResponseInterface;

class TodoApi
{
    public $recievedArray;
    public $outputArray = [];
    function main(RequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $this->recievedArray = $this->getRecievedArray(); // set array
        //var_dump($this->recievedArray);
        if (isset($this->recievedArray["requestType"])) {

            switch ($this->recievedArray["requestType"]) {
                case "test":
                    $Database = new Database;
                    $Database->query($this->recievedArray);
                    $array = ["msg" => "good"];
                    break;
                default:
                    break;
            }
        } else {
            $array = [
                "error" => $this->recievedArray["requestType"]//"requestType not defined"
            ];
        }
        $response->getBody()->write($this->sendArray($array)); // Set the response body
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
