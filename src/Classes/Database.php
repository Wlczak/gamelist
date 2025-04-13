<?php

namespace Gamelist\Classes;

use mysqli;
use mysqli_sql_exception;

class Database
{
    # Config
    public $hostname = "gamelist-db";
    public $username = "root";
    public $password = "root";
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
        $keys = ["listId"];
        $request = $this->checkKeys($request, $keys);
        if (!$request["status"])
            return $request;

        #function content
        $listId = $request["listId"];

        $listId = $_SESSION['uidSecret']; //overwrite
        $result = $this->query("SELECT id,content,pointScore,status FROM `tasks` WHERE listId = $listId AND status = 0");
        while ($row = $result->fetch_assoc()) {
            $response[] = $row;
        }
        if (!isset($response)) {
            $response['msg'] = "No task found";
        }
        return $response;
    }

    function removeTask($request): array
    {
        $keys = ["taskId"];
        $request = $this->checkKeys($request, $keys);
        if (!$request["status"])
            return $request;

        $taskId = $request['taskId'];
        $this->query("DELETE FROM `tasks` WHERE id = $taskId");
        $response["status"] = true;
        return $response;
    }

    function getPoints($request): array
    {
        $uid = $_SESSION['uidSecret'];
        $sql = "SELECT points FROM users WHERE id = $uid";
        $result = $this->query($sql);
        $response["points"] = $result->fetch_column();

        return $response;
    }

    function doneTask($request): array
    {
        $keys = ["taskId", "taskScore"];
        $request = $this->checkKeys($request, $keys);
        if (!$request["status"])
            return $request;


        $taskId = $request['taskId'];
        $taskScore = $request['taskScore'];
        $uid = $_SESSION['uidSecret'];

        $sql = "SELECT `status` FROM `tasks` WHERE `tasks`.`id` = $taskId";
        $result = $this->query($sql);
        if ($result->fetch_column()[0]) {
        }



        $sql = "UPDATE `tasks` SET `status` = '1' WHERE `tasks`.`id` = $taskId";
        $this->updateScore($uid, $taskScore);
        $this->query($sql);
        $response["status"] = true;
        return $response;
    }

    function createTask($request)
    {
        $keys = ["taskContent", "taskScore"];
        $request = $this->checkKeys($request, $keys);
        if (!$request["status"])
            return $request;

        $taskContent = $request['taskContent'];
        $taskScore = $request['taskScore'];
        $uid = $_SESSION['uidSecret'];

        $sql = "INSERT INTO `tasks` (`id`, `listId`, `uid`, `content`, `pointScore`, `status`) VALUES (NULL, '$uid', '$uid', '$taskContent', '$taskScore', '0');";
        $result = $this->query($sql);

        if ($result === false) {
            return $response['error'] = "Sql connection failed.";
        }
        $response["msg"] = "Task created succesfully";
        return $response;
    }

    function query($sql)
    {
        try {
            $conn = new mysqli($this->hostname, $this->username, $this->password, $this->database);
        } catch (mysqli_sql_exception $e) {
            $this->throwDatabaseError($e);
        }
        $result = $conn->query($sql);
        $conn->close();
        return $result;
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
        include "templates/dbError.php";
        die;
    }
    function verifyToken()
    {
        if ($_SESSION['isLoggedIn'] && isset($_COOKIE['authToken']) && $_COOKIE['authToken'] == $_SESSION['tokenSecret']) {
            //$_SESSION['authMsg'] = 1; #debug
            $Database = new Database;
            $token = $_SESSION['tokenSecret'];
            if ($Database->checkIfExists("tokens", "token", $token)) {
                $Token = new Token;
                $Token->extendTokenTime($_SESSION['uidSecret'], $_SESSION['tokenSecret']);
                return true;
            }
        }
        return false;
    }

    function updateScore($uid, $score)
    {

        $sql = "UPDATE `users` SET `points` = `points`+$score WHERE `users`.`id` = $uid";
        $this->query($sql);
    }
}
