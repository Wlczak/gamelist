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

            $response['authMsg'] = $_SESSION['authMsg'];
            unset($_SESSION['authMsg']);
        }
        return $response;
    }
}
