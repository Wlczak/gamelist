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
    function apiQuery($request): array
    {
        $keys = ["query"];
        $request = $this->checkKeys($request, $keys);
        if (!$request["status"])
            return $request;

        #function content
        try {
            $conn = new mysqli($this->hostname, $this->username, $this->password, $this->database);
        } catch (mysqli_sql_exception $e) {
            $this->throwDatabaseError($e);
        }
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
        $result = $this->query("SELECT id,content,pointScore,status FROM `tasks` WHERE listId = $listId AND status = 0");
        while ($row = $result->fetch_assoc()) {
            $response[] = $row;
        }
        return $response;
    }

    function removeTask($request): array
    {
        $keys = ["taskId", "accessToken"];
        $request = $this->checkKeys($request, $keys);
        if (!$request["status"])
            return $request;

        $taskId = $request['taskId'];
        $this->query("DELETE FROM `tasks` WHERE id = $taskId");
        $response["status"] = true;
        return $response;
    }

    function query($sql)
    {
        $conn = new mysqli($this->hostname, $this->username, $this->password, $this->database);
        return $conn->query($sql);
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
    function checkIfExists($table, $column, $needle)
    {
        $result = $this->query("SELECT COUNT(*) FROM $table WHERE $column = '$needle'");
        if ($result->fetch_column() > 0) {
            return true;
        };
        return false;
    }
    function createUser($username, $password)
    {
        $this->query("INSERT INTO `users` (`id`, `username`, `password`) VALUES (NULL, '$username', '$password');");
    }
    function throwDatabaseError($e)
    {
        /*echo "<script> 
        window.alert('We are currently experiencing database issues please try again later.')
        </script>";*/
        //var_dump($e);
        $eArr = (array)$e;
}
