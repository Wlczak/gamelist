<?php

namespace Gamelist\Classes;

use mysqli;
use mysqli_result;
use mysqli_sql_exception;

class Database
{
    # Config
    public string $hostname = "gamelist-db";
    public string $username = "root";
    public string $password = "root";
    public string $database = "gamelist";

    # universal SQL query
    /**
     * @param  $request
     * @return array
     */
    function apiQuery($request): array
    {
        $keys = ["query"];
        $request = $this->checkKeys($request, $keys);
        if (!$request["status"]) {
            return $request;
        }

        #function content
        try {
            $conn = new mysqli($this->hostname, $this->username, $this->password, $this->database);
            $conn->close();
        } catch (mysqli_sql_exception $e) {
            $this->throwDatabaseError($e);
        }
        $response["msg"] = "good";
        return $response;
    }

    /**
     * @param  $request
     * @return array
     */
    function getList($request): array
    {
        $keys = ["listId"];
        $request = $this->checkKeys($request, $keys);
        if (!$request["status"]) {
            return $request;
        }

        #function content
        $listName = $request["listId"];

        $listId = $_SESSION['uidSecret']; //overwrite

        switch ($request["listId"]) {
            default:
            case 1:
                $result = $this->query("SELECT id,content,pointScore,status FROM `tasks` WHERE listId = $listId AND status = 0");
                break;
            case 2:
                $result = $this->query("SELECT id,content,pointScore,count,status FROM `shop` WHERE listId = $listId AND status = 0");
                break;
        }

        while ($row = $result->fetch_assoc()) {
            $response[] = $row;
        }
        if (!isset($response)) {
            $response['msg'] = "No task/shop item found";
        }
        return $response;
    }

    /**
     * @param  $request
     * @return array
     */
    function removeTask($request): array
    {
        $keys = ["taskId"];
        $request = $this->checkKeys($request, $keys);
        if (!$request["status"]) {
            return $request;
        }

        $taskId = $request['taskId'];
        $this->query("DELETE FROM `tasks` WHERE id = $taskId");
        $response["status"] = true;
        return $response;
    }

    /**
     * @param  $request
     * @return array
     */
    function getPoints($request): array
    {
        $uid = $_SESSION['uidSecret'];
        $sql = "SELECT points FROM users WHERE id = $uid";
        $result = $this->query($sql);
        $response["points"] = $result->fetch_column();

        return $response;
    }

    /**
     * @param  $request
     * @return array
     */
    function doneTask($request): array
    {
        $keys = ["taskId", "taskScore"];
        $request = $this->checkKeys($request, $keys);
        if (!$request["status"]) {
            return $request;
        }

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

    /**
     * @param  $request
     * @return mixed
     */
    function createTask($request)
    {
        $keys = ["taskContent", "taskScore"];
        $request = $this->checkKeys($request, $keys);
        if (!$request["status"]) {
            return $request;
        }

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

    /**
     * @param  $request
     * @return mixed
     */
    function createItem($request)
    {
        $keys = ["itemContent", "itemScore"];
        $request = $this->checkKeys($request, $keys);
        if (!$request["status"]) {
            return $request;
        }

        $itemContent = $request['itemContent'];
        $itemScore = $request['itemScore'];
        $itemCount = $request['itemCount'];

        $uid = $_SESSION['uidSecret'];

        $sql = "INSERT INTO `shop` (`id`, `listId`, `uid`, `content`, `pointScore`, `count` , `status`) VALUES (NULL, '$uid', '$uid', '$itemContent', '$itemScore', '$itemCount', '0');";
        $result = $this->query($sql);

        if ($result === false) {
            return $response['error'] = "Sql connection failed.";
        }
        $response["msg"] = "Task created succesfully";
        return $response;
    }

    /**
     * @param  $request
     * @return mixed
     */
    function boughtItem($request)
    {
        $keys = ["itemId", "pointScore"];
        $request = $this->checkKeys($request, $keys);
        if (!$request["status"]) {
            return $request;
        }

        $itemId = $request['itemId'];
        $pointScore = $request['pointScore'];

        $sql = "SELECT `count` FROM `shop` WHERE `id` = $itemId";

        $result = $this->query($sql);

        if ($result == false) {
            return $response['error'] = "Sql connection failed.";
        }

        $count = $result->fetch_row()[0];

        if ($count <= 1) {
            $sql = "UPDATE `shop` SET `status` = '1' WHERE `shop`.`id` = $itemId";
            $this->query($sql);
            $sql = "UPDATE `shop` SET `count` = '0' WHERE `id` = $itemId";
            $this->query($sql);
        } else {
            $count--;
            $sql = "UPDATE `shop` SET `count` = '$count' WHERE `shop`.`id` = $itemId";
            $this->query($sql);
        }

        $this->updateScore($_SESSION['uidSecret'], $pointScore * -1);

        $response["status"] = true;
        return $response;
    }

    /**
     * @param  $sql
     * @return mysqli_result
     */
    function query($sql): mysqli_result | bool
    {
        try {
            $conn = new mysqli($this->hostname, $this->username, $this->password, $this->database);
            $result = $conn->query($sql);
            $conn->close();

            return $result;

        } catch (mysqli_sql_exception $e) {
            $this->throwDatabaseError($e);
            return false;
        }
    }

    /**
     * @param  $request
     * @param  $keys
     * @return mixed
     */
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

    /**
     * @param $table
     * @param $column
     * @param $needle
     */
    function checkIfExists($table, $column, $needle)
    {
        $result = $this->query("SELECT COUNT(*) FROM $table WHERE $column = '$needle'");
        if ($result->fetch_column() > 0) {
            return true;
        };
        return false;
    }

    /**
     * @param $username
     * @param $password
     */
    function createUser($username, $password)
    {
        $this->query("INSERT INTO `users` (`id`, `username`, `password`) VALUES (NULL, '$username', '$password');");
    }

    /**
     * @param $e
     */
    function throwDatabaseError($e)
    {
        /*echo "<script>
        window.alert('We are currently experiencing database issues please try again later.')
        </script>";*/
        //var_dump($e);
        $eArr = (array) $e;
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

    /**
     * @param $uid
     * @param $score
     */
    function updateScore($uid, $score)
    {
        $sql = "UPDATE `users` SET `points` = `points`+$score WHERE `users`.`id` = $uid";
        $this->query($sql);
    }
}
