<?php

namespace Gamelist\Classes;

class Session
{
    function getAuthError(): array
    {
        $response["authError"] = "";
        $response["authMsg"] = false;

        if (key_exists("authError", $_SESSION)) {
            $response['authError'] = $_SESSION['authError'];
            unset($_SESSION['authError']);
        }
        if (key_exists("authMsg", $_SESSION)) {
            $response['authMsg'] = $_SESSION['authMsg'];
            unset($_SESSION['authMsg']);
        }
        if (key_exists("authToken", $_SESSION)) {
            $response['authToken'] = $_SESSION['authToken'];
            unset($_SESSION['authToken']);
        }
        //var_dump($_COOKIE);
        if (key_exists("authToken", $_COOKIE)) {
            $response['cookieTest'] = $_COOKIE['authToken'];
            unset($_COOKIE['authToken']);
        }

        return $response;
    }
}
