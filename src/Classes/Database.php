<?php

namespace Gamelist\Classes;

use mysqli;

class Database
{
    # Config
    public $hostname = "192.168.0.106";
    public $username = "gamelist";
    public $password = "todolist";
    public $database = "gamelist";

    # universal SQL query
    function query($request): array
    {
        $keys = ["query"];
        $request = $this->checkKeys($request, $keys);
        if (!$request["status"])
            return $request;

        #function content
        $conn = new mysqli($this->hostname, $this->username, $this->password, $this->database);
        $conn->close();
        $response["msg"] = "good";
        return $response;
    }
    function getList($request): array
    {
        $keys = ["listId", "accessToken"];
        $request = $this->checkKeys($request, $keys);
        if (!$request["status"])
            return $request;

        #function content
        $listId = $request["listId"];
        $conn = new mysqli($this->hostname, $this->username, $this->password, $this->database);
        $result = $conn->query("SELECT content,pointScore,status FROM `tasks` WHERE listId = $listId");
        $response = $result->fetch_all();

        //$response = ["status" => 1];
        return $response;
    }
    function checkKeys($request, $keys)
    {
        foreach ($keys as $key) {
            if (!key_exists($key, $request)) {
                $response = ["error" => "key not defined: $key", "status" => false];
                return $response;
            }
        }
        $request["status"] = true;
        return $request;
    }
}
