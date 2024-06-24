<?php

namespace Gamelist\Api;

use Gamelist\Classes\Database;
use Psr\Http\Message\ServerRequestInterface as RequestInterface;
use Psr\Http\Message\ResponseInterface;

class TodoApi
{
    # variable declaration
    public $request;
    public $Database;

    function main(RequestInterface $request, ResponseInterface $html): ResponseInterface
    {
        $this->request = $this->getRecievedArray(); // set array
        $this->Database = new Database;

        $response = $this->handleRequest($this->request);

        $html->getBody()->write($this->returnResponse($response)); // Set the html body
        return $html;
    }
    function handleRequest($request): array
    {
        if (!isset($request) || !key_exists("requestType", $request)) {
            return $response = [
                "error" => "[requestType] not defined"
            ];
        } else {
            switch ($request["requestType"]) {
                case "dbQuery":

                    $response = $this->Database->apiQuery($request);
                    break;
                case "getList":
                    $response = $this->Database->getList($request);
                    break;
                case "removeTask":
                    $response = $this->Database->removeTask($request);

                    break;
                default:
                    $response["msg"] = "given requestType is undefined: \"" . $this->request['requestType'] . "\"";
                    $response["help"] = "Get help with the api at: https://github.com/Wlczak/gamelist/wiki/Api-backend";
                    break;
            }
        }
        /*if(){

        }*/
        //echo gettype($response);
        if (!gettype($response) == "array" || !isset($response)) {
            $response =  ["error" => "Api method error: method returned ivalid values"];
        }
        return $response;
    }
    function returnResponse($array)
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
